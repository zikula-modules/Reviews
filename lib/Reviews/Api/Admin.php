<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2002, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id: Admin.php 445 2010-07-06 16:09:10Z drak $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Zikula_Value_Addons
 * @subpackage Reviews
 */

class Reviews_Api_Admin extends Zikula_AbstractApi
{


    /**
     * Purge the permalink fields in the Reviews table
     * @author Mateo Tibaquira
     * @return bool true on success, false on failure
     */
    public function purgepermalinks($args)
    {
        // Security check
        if (!SecurityUtil::checkPermission('Reviews::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        // disable categorization to do this (if enabled)
        $catenabled = ModUtil::getVar('Reviews', 'enablecategorization');
        if ($catenabled) {
            ModUtil::setVar('Reviews', 'enablecategorization', false);
            ModUtil::dbInfoLoad('Reviews', 'Reviews', true);
        }

        // get all the ID and permalink of the table
        $data = DBUtil::selectObjectArray('reviews', '', '', -1, -1, 'id', null, null, array('id', 'slug'));

        // loop the data searching for non equal permalinks
        $perma = '';
        foreach (array_keys($data) as $id) {
            $perma = strtolower(DataUtil::formatPermalink($data[$id]['slug']));
            if ($data[$id]['slug'] != $perma) {
                $data[$id]['slug'] = $perma;
            } else {
                unset($data[$id]);
            }
        }

        // restore the categorization if was enabled
        if ($catenabled) {
            ModUtil::setVar('Reviews', 'enablecategorization', true);
        }

        if (empty($data)) {
            return true;
            // store the modified permalinks
        } elseif (DBUtil::updateObjectArray($data, 'reviews', 'id')) {
            // Let the calling process know that we have finished successfully
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Returns available admin panel links.
     *
     * @return array Array of admin links.
     */
    public function getLinks()
    {
        $links = array();
    
        if (SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_READ)) {
            $links[] = array('url' => ModUtil::url($this->name, 'user', 'main'),
                    'text' => $this->__('Frontend'),
                    'title' => $this->__('Switch to user area.'),
                    'class' => 'z-icon-es-home');
        }
        if (SecurityUtil::checkPermission($this->name . ':Review:', '::', ACCESS_ADMIN)) {
            $links[] = array('url' => ModUtil::url($this->name, 'admin', 'view', array('ot' => 'review')),
                    'text' => $this->__('Reviews'),
                    'title' => $this->__('Review list'));
        } 
        if (SecurityUtil::checkPermission('Reviews::', '::', ACCESS_ADMIN)) {
            $links[] = array('url'  => ModUtil::url('Reviews', 'admin', 'view', array('purge' => 1)),
                    'text' => $this->__('Purge permalinks'));
        }
        if (SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_ADMIN)) {
            $links[] = array('url' => ModUtil::url($this->name, 'admin', 'config'),
                    'text' => $this->__('Configuration'),
                    'title' => $this->__('Manage settings for this application'));
        }
    
        return $links;
    }
}