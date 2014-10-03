<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2002, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id: User.php 445 2010-07-06 16:09:10Z drak $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Zikula_Value_Addons
 * @subpackage Reviews
 */

class Reviews_Api_User extends Zikula_AbstractApi
{
    /**
     * Returns available user panel links.
     *
     * @return array Array of user links.
     */
    public function getLinks()
    {
        $links = array();

        if (SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_ADMIN)) {
            $links[] = array('url' => ModUtil::url($this->name, 'admin', 'main'),
                    'text' => $this->__('Backend'),
                    'title' => $this->__('Switch to administration area.'),
                    'class' => 'z-icon-es-options');
        }

        $controllerHelper = new Reviews_Util_Controller($this->serviceManager);
        $utilArgs = array('api' => 'user', 'action' => 'getLinks');
        $allowedObjectTypes = $controllerHelper->getObjectTypes('api', $utilArgs);

        if (in_array('review', $allowedObjectTypes)
                && SecurityUtil::checkPermission($this->name . ':Review:', '::', ACCESS_READ)) {
            $links[] = array('url' => ModUtil::url($this->name, 'user', 'view', array('ot' => 'review')),
                    'text' => $this->__('Reviews'),
                    'title' => $this->__('Review list'));
        }
        if (in_array('review', $allowedObjectTypes)
                && SecurityUtil::checkPermission($this->name . '::', '.*', ACCESS_EDIT)) {
            $links[] = array('url' => ModUtil::url($this->name, 'user', 'edit', array('ot' => 'review')),
                    'text' => $this->__('Create Review'),
                    'title' => $this->__('Create a review'));
        }


        return $links;
    }

    /**
     * form custom url string
     *
     * @author Mark West
     * @return string custom url string
     */
    public function encodeurl($args)
    {
        // check if we have the required input
        if (!isset($args['modname']) || !isset($args['func'])) {
            throw new \InvalidArgumentException(__('Invalid arguments array received.'));
        }
         
        // set default values
        if (!isset($args['type'])) {
            $args['type'] = 'user';
        }
        if (!isset($args['args'])) {
            $args['args'] = array();
        }
         
        // return if function url scheme is not being customised
        $customFuncs = array('view', 'display');
        if (!in_array($args['func'], $customFuncs)) {
            return false;
        }
         
        // initialise url routing rules
        $routerFacade = new Reviews_RouterFacade();
        // get router itself for convenience
        $router = $routerFacade->getRouter();
         
        // initialise object type
        $controllerHelper = new Reviews_Util_Controller($this->serviceManager);
        $utilArgs = array('controller' => 'user', 'action' => 'encodeurl');
        $allowedObjectTypes = $controllerHelper->getObjectTypes('api', $utilArgs);
        $objectType = ((isset($args['args']['ot']) && in_array($args['args']['ot'], $allowedObjectTypes)) ? $args['args']['ot'] : $controllerHelper->getDefaultObjectType('api', $utilArgs));
         
        // initialise group folder
        $groupFolder = $routerFacade->getGroupingFolderFromObjectType($objectType, $args['func'], $args['args']);
         
        // start pre processing
         
        // convert object type to group folder
        $args['args']['ot'] = $groupFolder;
         
        // handle special templates
        $displayDefaultEnding = System::getVar('shorturlsext', '');
        $endingPrefix = ($args['func'] == 'view') ? '.' : '';
        foreach (array('csv', 'rss', 'atom', 'xml', 'pdf', 'json', 'kml') as $ending) {
            if (!isset($args['args']['use' . $ending . 'ext'])) {
                continue;
            }
            if ($args['args']['use' . $ending . 'ext'] == '1') {
                $args['args'][$args['func'] . 'ending'] = $endingPrefix . $ending;
            }
            unset($args['args']['use' . $ending . 'ext']);
        }
        // fallback to default templates
        if (!isset($args['args'][$args['func'] . 'ending'])) {
            if ($args['func'] == 'view') {
                // category list
               /* if (isset($args['args']['prop'])) {
                    $vars = $args['args']['prop'];
                    if (isset($args['args']['cat'])) {
                        $vars .= '/'.$args['args']['cat'];
                    }
                    // letter list
                } elseif (isset($args['args']['letter'])) {
                    $vars = 'letter/'.$args['args']['letter'];
                }
                if (isset($args['args']['page']) && $args['args']['page'] != 1) {
                    $vars .= (empty($vars) ? '' : '/').'page/'.$args['args']['page'];
                }*/
                $args['args'][$args['func'] . 'ending'] = '';//'/';
            } else if ($args['func'] == 'display') {
                $args['args'][$args['func'] . 'ending'] = $displayDefaultEnding;
            }
        }
         
        if ($args['func'] == 'view') {
            // TODO filter views (e.g. /orders/customer/mr-smith.csv)
            /**
             $filterEntities = array('customer', 'region', 'federalstate', 'country');
             foreach ($filterEntities as $filterEntity) {
             $filterField = $filterEntity . 'id';
             if (!isset($args['args'][$filterField]) || !$args['args'][$filterField]) {
             continue;
             }
             $filterId = $args['args'][$filterField];
             unset($args['args'][$filterField]);
              
             $filterGroupFolder = $routerFacade->getGroupingFolderFromObjectType($filterEntity, 'display', $args['args']);
             $filterSlug = $routerFacade->getFormattedSlug($filterEntity, 'display', $args['args'], $filterId);
             $result .= $filterGroupFolder . '/' . $filterSlug .'/';
             break;
             }
             */
        } elseif ($args['func'] == 'display') {
            // determine given id
            $id = 0;
            foreach (array('id', strtolower($objectType) . 'id', 'objectid') as $idFieldName) {
                if (isset($args['args'][$idFieldName])) {
                    $id = $args['args'][$idFieldName];
                    unset($args['args'][$idFieldName]);
                }
            }
            
            if (ModUtil::getVar('Reviews', 'addcategorytitletopermalink') == 1 && ModUtil::getVar('Reviews', 'enablecategorization') == 1) {
                $prop = $this->request->query->filter('prop', '');
                if ($prop != '') {
                $args['args']['cat'] = $prop;
                }
            }
             
            // check if we have a valid slug given
            if (isset($args['args']['slug']) && (!$args['args']['slug'] || $args['args']['slug'] == $id)) {
                unset($args['args']['slug']);
            }
            // try to determine missing slug
            if (!isset($args['args']['slug'])) {
                $slug = '';
                if ($id > 0) {
                    $slug = $routerFacade->getFormattedSlug($objectType, $args['func'], $args['args'], $id);
                }
                if (!empty($slug) && $slug != $id) {
                    // add slug expression
                    $args['args']['slug'] = $slug;
                }
            }
            // check if we have one now
            if (!isset($args['args']['slug'])) {
                // readd id as fallback
                $args['args']['id'] = $id;
            }
        }
         
        // add func as first argument
        $routerArgs = array_merge(array('func' => $args['func']), $args['args']);
         
        // now create url based on params
        $result = $router->generate(null, $routerArgs);
         
        // post processing
        if (
                ($args['func'] == 'view' && !empty($args['args']['viewending']))
                || $args['func'] == 'display') {
            // check if url ends with a trailing slash
            if (substr($result, -1) == '/') {
                // remove the trailing slash
                $result = substr($result, 0, strlen($result) - 1);
            }
        }
         
        // enforce url name of the module, but do only 1 replacement to avoid changing other params
        $modInfo = ModUtil::getInfoFromName('Reviews');
        $result = preg_replace('/' . $modInfo['name'] . '/', $modInfo['url'], $result, 1);
         
        return $result;
         
        /////////////Old code
         
        // check we have the required input
        /*  if (!isset($args['modname']) || !isset($args['func']) || !isset($args['args'])) {
         return LogUtil::registerArgsError();
        }

        if (!isset($args['type'])) {
        $args['type'] = 'user';
        }

        // create an empty string ready for population
        $vars = '';

        // view function
        if ($args['func'] == 'view') {
        // category list
        if (isset($args['args']['prop'])) {
        $vars = $args['args']['prop'];
        if (isset($args['args']['cat'])) {
        $vars .= '/'.$args['args']['cat'];
        }
        // letter list
        } elseif (isset($args['args']['letter'])) {
        $vars = 'letter/'.$args['args']['letter'];
        }
        if (isset($args['args']['page']) && $args['args']['page'] != 1) {
        $vars .= (empty($vars) ? '' : '/').'page/'.$args['args']['page'];
        }
        }*/

        // for the display function use either the title (if present) or the page id
        if ($args['func'] == 'display') {
            // check for the generic object id parameter
            /* if (isset($args['args']['objectid'])) {
             $args['args']['id'] = $args['args']['objectid'];
            }
            // get the item (will be cached by DBUtil)
            if (isset($args['args']['id'])) {
            $item = ModUtil::apiFunc('Reviews', 'selection', 'getEntity', array('id' => $args['args']['id']));
            } else {
            $item = ModUtil::apiFunc('Reviews', 'selection', 'getEntity', array('title' => $args['args']['title']));
            }*/
            /*if (ModUtil::getVar('Reviews', 'addcategorytitletopermalink') && isset($args['args']['cat'])) {
                $vars = $args['args']['cat'].'/'.$item['slug'];
            } else {
            $vars = $item['slug'];
            }
            if (isset($args['args']['page']) && $args['args']['page'] != 1) {
            $vars .= '/page/'.$args['args']['page'];
            }*/
        }

        // don't display the function name if either displaying an page or the normal overview
        if ($args['func'] == 'main' || $args['func'] == 'display') {
            $args['func'] = '';
        }

        // construct the custom url part
        if (empty($args['func']) && empty($vars)) {
         return $args['modname'] . '/';
        } elseif (empty($args['func'])) {
        return $args['modname'] . '/' . $vars . '/';
        } elseif (empty($vars)) {
        return $args['modname'] . '/' . $args['func'] . '/';
        } else {
        return $args['modname'] . '/' . $args['func'] . '/' . $vars . '/';
        }

        return $result;
    }

    /**
     * decode the custom url string
     *
     * @author Mark West
     * @return bool true if successful, false otherwise
     */
    public function decodeurl($args)
    {
        // check we actually have some vars to work with
        if (!is_array($args) || !isset($args['vars']) || !is_array($args['vars']) || !count($args['vars'])) {
            throw new \InvalidArgumentException(__('Invalid arguments array received.'));
        }
         
        // define the available user functions
        $funcs = array('main', 'view', 'display', 'edit');
         
        // return if function url scheme is not being customised
        $customFuncs = array('view', 'display');
         
        // set the correct function name based on our input
        if (empty($args['vars'][2])) {
            // no func and no vars = main
            System::queryStringSetVar('func', 'main');
            return true;
        } else if (in_array($args['vars'][2], $funcs) && !in_array($args['vars'][2], $customFuncs)) {
            // normal url scheme, no need for special decoding
            return false;
        }
         
        $func = $args['vars'][2];
         
        // usually the language is in $args['vars'][0], except no mod name is in the url and we are set as start app
        $modInfo = ModUtil::getInfoFromName('Reviews');
        $lang = (strtolower($args['vars'][0]) == $modInfo['url']) ? $args['vars'][1] : $args['vars'][0];
         
        // remove some unrequired parameters
        foreach ($_GET as $k => $v) {
            if (in_array($k, array('module', 'type', 'func', 'lang', 'ot')) === false) {
                unset($_GET[$k]);
            }
        }
         
        // process all args except language and module
        $urlVars = array_slice($args['vars'], 2); // all except [0] and [1]
         
        // get arguments as string
        $url = implode('/', $urlVars);
         
        // check if default view urls end with a trailing slash
        if ($func == 'view' && strpos($url, '.') === false && substr($url, -1) != '/') {
            // add missing trailing slash
            $url .= '/';
        }
         
        $isDefaultModule = (System::getVar('shorturlsdefaultmodule', '') == $modInfo['name']);
        if (!$isDefaultModule) {
            $url = $modInfo['url'] . '/' . $url;
        }
         
        // initialise url routing rules
        $routerFacade = new Reviews_RouterFacade();
        // get router itself for convenience
        $router = $routerFacade->getRouter();
         
        // read params out of url
        $parameters = $router->parse($url);
        //var_dump($parameters);
         
        if (!$parameters || !is_array($parameters)) {
            return false;
        }
         
        // post processing
        if (!isset($parameters['func'])) {
            $parameters['func'] = 'view';
        }
         
        $func = $parameters['func'];
        // convert group folder to object type
        $parameters['ot'] = $routerFacade->getObjectTypeFromGroupingFolder($parameters['ot'], $func);
         
        // handle special templates
        $displayDefaultEnding = System::getVar('shorturlsext', '');
        $endingPrefix = ($func == 'view') ? '.' : '';
        if (isset($parameters[$func . 'ending']) && !empty($parameters[$func . 'ending']) && $parameters[$func . 'ending'] != ($endingPrefix . $displayDefaultEnding)) {
            if ($func == 'view') {
                $parameters[$func . 'ending'] = str_replace($endingPrefix, '', $parameters[$func . 'ending']);
            }
            $parameters['use' . $parameters[$func . 'ending'] . 'ext'] = '1';
            unset($parameters[$func . 'ending']);
        }
         
        // rename id to objid (primary key for display pages, optional filter id for view pages)
        /* may be obsolete now
         if (isset($parameters['id'])) {
        $parameters[strtolower($parameters['ot']) . 'id'] = $parameters['id'];
        unset($parameters['id']);
        }*/
         
        // write vars to GET
        foreach ($parameters as $k => $v) {
            System::queryStringSetVar($k, $v);
        }
         
        return true;

        ///////////////// old code
         
        // check we actually have some vars to work with...
        /*if (!isset($args['vars'])) {
         return LogUtil::registerArgsError();
        }*/

        // define the available user functions
        //$funcs = array('main', 'view', 'display', 'edit');
        // set the correct function name based on our input
        if (empty($args['vars'][2])) {
            System::queryStringSetVar('func', 'main');
        } elseif (!in_array($args['vars'][2], $funcs)) {
            System::queryStringSetVar('func', 'display');
            $nextvar = 2;
        } else {
            System::queryStringSetVar('func', $args['vars'][2]);
            $nextvar = 3;
        }

        // check the list function
        if (FormUtil::getPassedValue('func') == 'view' && isset($args['vars'][$nextvar])) {
            // get rid of unused vars
            $args['vars'] = array_slice($args['vars'], $nextvar);

            // check if the letter parameter is present
            if ($args['vars'][0] == 'letter') {
                System::queryStringSetVar('letter', (string)$args['vars'][1]);
                $nextvar = 2;
            } elseif ($args['vars'][0] == 'page') {
                System::queryStringSetVar('page', (int)$args['vars'][1]);
                $nextvar = 0;
            } else {
                // add the category info
                System::queryStringSetVar('prop', (string)$args['vars'][0]);
                $nextvar = 1;

                if (isset ($args['vars'][1])) {
                    // check if there's a page arg
                    $varscount = count($args['vars']);
                    ($args['vars'][$varscount-2] == 'page') ? $pagersize = 2 : $pagersize = 0;
                    // extract the category path
                    $cat = implode('/', array_slice($args['vars'], 1, $varscount - $pagersize - 1));
                    System::queryStringSetVar('cat', $cat);
                    $nextvar = 2;
                }
            }
            if (isset($args['vars'][$nextvar]) && $nextvar != 0 && $args['vars'][$nextvar] == 'page') {
                System::queryStringSetVar('page', (int)$args['vars'][$nextvar+1]);
            }
        }

        // identify the correct parameter to identify the page
        if (FormUtil::getPassedValue('func') == 'display') {
            // get rid of unused vars
            $args['vars'] = array_slice($args['vars'], $nextvar);
            $nextvar = 0;
            // remove any category path down to the leaf category
            $varscount = count($args['vars']);
            if (ModUtil::getVar('Reviews', 'addcategorytitletopermalink') && !empty($args['vars'][$nextvar+1])) {
                ($args['vars'][$varscount-2] == 'page') ? $pagersize = 2 : $pagersize = 0;
                $category = array_slice($args['vars'], 0, $varscount - 1 - $pagersize);
                System::queryStringSetVar('cat', implode('/',$category));
                array_splice($args['vars'], 0,  $varscount - 1 - $pagersize);
            }
            if (is_numeric($args['vars'][$nextvar])) {
                System::queryStringSetVar('id', $args['vars'][$nextvar]);
            } else {
                System::queryStringSetVar('title', $args['vars'][$nextvar]);
            }
            $nextvar++;
            if (isset($args['vars'][$nextvar]) && $args['vars'][$nextvar] == 'page') {
                System::queryStringSetVar('page', (int)$args['vars'][$nextvar+1]);
            }
        }

        return true;
    }

    /**
     * get meta data for the module
     */
    /* public function getmodulemeta()
     {
    return array('viewfunc'    => 'view',
            'displayfunc' => 'display',
            'newfunc'     => 'new',
            'createfunc'  => 'create',
            'modifyfunc'  => 'modify',
            'updatefunc'  => 'update',
            'deletefunc'  => 'delete',
            'titlefield'  => 'title',
            'itemid'      => 'id');
    }*/
}
