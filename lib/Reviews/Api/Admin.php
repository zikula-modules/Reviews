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
     * delete a Reviews item
     *
     * @param $args['tid'] ID of the item
     * @return bool true on success, false on failure
     */
    public function delete($args)
    {
        // Argument check
        if (!isset($args['id'])) {
            return LogUtil::registerArgsError();
        }

        // Get the review
        $item = ModUtil::apiFunc('Reviews', 'user', 'get', array('id' => $args['id']));

        if ($item == false) {
            return LogUtil::registerError($this->__('No such review found.'));
        }

        // Security check
        if (!SecurityUtil::checkPermission('Reviews::', "$item[title]::$item[id]", ACCESS_DELETE)) {
            return LogUtil::registerPermissionError();
        }

        if (!DBUtil::deleteObjectByID('reviews', $item['id'], 'id')) {
            return LogUtil::registerError($this->__('Error! Deletion attempt failed.'));
        }

        // Let any hooks know that we have deleted an item.
        $this->callHooks('item', 'delete', $item['id'], array('module' => 'Reviews'));

        return true;
    }

    /**
     * update a Reviews item
     *
     * @param $args['tid'] the ID of the item
     * @param $args['name'] the new name of the item
     * @param $args['number'] the new number of the item
     */
    public function update($args)
    {
        // Argument check
        if ((!isset($args['id'])) ||
                (!isset($args['title'])) ||
                (!isset($args['text'])) ||
                (!isset($args['reviewer'])) ||
                (!isset($args['email']))) {
            return LogUtil::registerArgsError();
        }

        // Check review to update exists, and get information for
        // security check
        $item = ModUtil::apiFunc('Reviews', 'user', 'get', array('id' => $args['id']));

        if ($item == false) {
            return LogUtil::registerError($this->__('No such review found.'));
        }

        // Security check
        if (!SecurityUtil::checkPermission('Reviews::', "$item[title]::$args[id]", ACCESS_EDIT)) {
            return LogUtil::registerPermissionError();
        }
        if (!SecurityUtil::checkPermission('Reviews::', "$args[title]::$args[id]", ACCESS_EDIT)) {
            return LogUtil::registerPermissionError();
        }

        // set some defaults
        if (!isset($args['language'])) {
            $args['language'] = '';
        }

        // define the permalink title if not present
        if (!isset($args['urltitle']) || empty($args['urltitle'])) {
            $args['urltitle'] = DataUtil::formatPermalink($args['title']);
        }

        if (!DBUtil::updateObject($args, 'reviews', '', 'id')) {
            return LogUtil::registerError($this->__('Error! Update attempt failed.'));
        }

        // Let any other modules know we have updated an item
        $this->callHooks('item', 'update', $args['id'], array('module' => 'Reviews'));

        // The item has been modified, so we clear all cached pages of this item.
        $render = & Zikula_View::getInstance('Reviews');
        $render->clear_cache(null, $args['id']);

        return true;
    }

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
        $data = DBUtil::selectObjectArray('reviews', '', '', -1, -1, 'id', null, null, array('id', 'urltitle'));

        // loop the data searching for non equal permalinks
        $perma = '';
        foreach (array_keys($data) as $id) {
            $perma = strtolower(DataUtil::formatPermalink($data[$id]['urltitle']));
            if ($data[$id]['urltitle'] != $perma) {
                $data[$id]['urltitle'] = $perma;
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
     * get available admin panel links
     *
     * @author Mark West
     * @return array array of admin links
     */
    public function getlinks()
    {
        $links = array();

        if (SecurityUtil::checkPermission('Reviews::', '::', ACCESS_READ)) {
            $links[] = array('url'  => ModUtil::url('Reviews', 'admin', 'view'),
                    'text' => $this->__('View reviews list'));
        }
        if (SecurityUtil::checkPermission('Reviews::', '::', ACCESS_ADD)) {
            $links[] = array('url'  => ModUtil::url('Reviews', 'admin', 'newreview'),
                    'text' => $this->__('Create a review'));
        }
        if (SecurityUtil::checkPermission('Reviews::', '::', ACCESS_ADMIN)) {
            $links[] = array('url'  => ModUtil::url('Reviews', 'admin', 'view', array('purge' => 1)),
                    'text' => $this->__('Purge permalinks'));
            $links[] = array('url'  => ModUtil::url('Reviews', 'admin', 'modifyconfig'),
                    'text' => $this->__('Settings'));
        }

        return $links;
    }
}