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
     /*public function encodeurl($args)
    {
        // check we have the required input
        if (!isset($args['modname']) || !isset($args['func']) || !isset($args['args'])) {
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
        }

        // for the display function use either the title (if present) or the page id
        if ($args['func'] == 'display') {
            // check for the generic object id parameter
            if (isset($args['args']['objectid'])) {
                $args['args']['id'] = $args['args']['objectid'];
            }
            // get the item (will be cached by DBUtil)
            if (isset($args['args']['id'])) {
                $item = ModUtil::apiFunc('Reviews', 'selection', 'getEntity', array('id' => $args['args']['id']));
            } else {
                $item = ModUtil::apiFunc('Reviews', 'selection', 'getEntity', array('title' => $args['args']['title']));
            }
            if (ModUtil::getVar('Reviews', 'addcategorytitletopermalink') && isset($args['args']['cat'])) {
                $vars = $args['args']['cat'].'/'.$item['slug'];
            } else {
                $vars = $item['slug'];
            }
            if (isset($args['args']['page']) && $args['args']['page'] != 1) {
                $vars .= '/page/'.$args['args']['page'];
            }
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
    }

    /**
     * decode the custom url string
     *
     * @author Mark West
     * @return bool true if successful, false otherwise
     */
    /* public function decodeurl($args)
    {
        // check we actually have some vars to work with...
        if (!isset($args['vars'])) {
            return LogUtil::registerArgsError();
        }

        // define the available user functions
        $funcs = array('main', 'view', 'display', 'edit');
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
    public function getmodulemeta()
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
    }
}
