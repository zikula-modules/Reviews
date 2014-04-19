<?php
/**
 * Reviews.
 *
 * @copyright Michael Ueberschaer (MU)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @package Reviews
 * @author Michael Ueberschaer <kontakt@webdesign-in-bremen.com>.
 * @link http://webdesign-in-bremen.com
 * @link http://zikula.org
 * @version Generated by ModuleStudio 0.6.2 (http://modulestudio.de).
 */

/**
 * Mailz api base class.
 */
class Reviews_Api_Base_Mailz extends Zikula_AbstractApi
{
    /**
     * Returns existing Mailz plugins with type / title.
     *
     * @param array $args List of arguments.
     *
     * @return array List of provided plugin functions.
     */
    public function getPlugins(array $args = array())
    {
        $plugins = array();
        $plugins[] = array(
            'pluginid'      => 1,
            'module'        => 'Reviews',
            'title'         => $this->__('3 newest reviews'),
            'description'   => $this->__('A list of the three newest reviews.')
        );
        $plugins[] = array(
            'pluginid'      => 2,
            'module'        => 'Reviews',
            'title'         => $this->__('3 random reviews'),
            'description'   => $this->__('A list of three random reviews.')
        );
        return $plugins;
    }
    
    /**
     * Returns the content for a given Mailz plugin.
     *
     * @param array    $args                List of arguments.
     * @param int      $args['pluginid']    id number of plugin (internal id for this module, see getPlugins method).
     * @param string   $args['params']      optional, show specific one or all otherwise.
     * @param int      $args['uid']         optional, user id for user specific content.
     * @param string   $args['contenttype'] h or t for html or text.
     * @param datetime $args['last']        timestamp of last newsletter.
     *
     * @return string output of plugin template.
     */
    public function getContent(array $args = array())
    {
        ModUtil::initOOModule('Reviews');
        // $args is something like:
        // Array ( [uid] => 5 [contenttype] => h [pluginid] => 1 [nid] => 1 [last] => 0000-00-00 00:00:00 [params] => Array ( [] => ) ) 1
        $objectType = 'review';
    
        $entityClass = 'Reviews_Entity_' . ucwords($objectType);
        $serviceManager = ServiceUtil::getManager();
        $entityManager = $serviceManager->getService('doctrine.entitymanager');
        $repository = $entityManager->getRepository($entityClass);
    
        $idFields = ModUtil::apiFunc('Reviews', 'selection', 'getIdFields', array('ot' => $objectType));
    
        $sortParam = '';
        if ($args['pluginid'] == 2) {
            $sortParam = 'RAND()';
        } elseif ($args['pluginid'] == 1) {
            if (count($idFields) == 1) {
                $sortParam = $idFields[0] . ' DESC';
            } else {
                foreach ($idFields as $idField) {
                    if (!empty($sortParam)) {
                        $sortParam .= ', ';
                    }
                    $sortParam .= $idField . ' ASC';
                }
            }
        }
    
        $where = ''/*$this->filter*/;
        $resultsPerPage = 3;
    
        // get objects from database
        $selectionArgs = array(
            'ot' => $objectType,
            'where' => $where,
            'orderBy' => $sortParam,
            'currentPage' => 1,
            'resultsPerPage' => $resultsPerPage
        );
        list($entities, $objectCount) = ModUtil::apiFunc('Reviews', 'selection', 'getEntitiesPaginated', $selectionArgs);
    
        $view = Zikula_View::getInstance('Reviews', true);
    
        //$data = array('sorting' => $this->sorting, 'amount' => $this->amount, 'filter' => $this->filter, 'template' => $this->template);
        //$view->assign('vars', $data);
    
        $view->assign('objectType', $objectType)
             ->assign('items', $entities)
             ->assign($repository->getAdditionalTemplateParameters('api', array('name' => 'mailz')));
    
        if ($args['contenttype'] == 't') { /* text */
            return $view->fetch('mailz/itemlist_review_text.tpl');
        } else {
            //return $view->fetch('contenttype/itemlist_display.html');
            return $view->fetch('mailz/itemlist_review_html.tpl');
        }
    }
}
