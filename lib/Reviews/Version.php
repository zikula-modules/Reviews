<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2013, Zikula Development Team
 * @link http://www.zikula.org
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Zikula_Value_Addons
 * @subpackage Reviews
 */

class Reviews_Version extends Zikula_AbstractVersion
{
    public function getMetaData()
    {
        $meta = array();
        $meta['displayname']    = __('Reviews system');
        $meta['description']    = __('Reviews system module');
        //! this defines the module's url
        $meta['url']            = __('reviews');
        $meta['version']        = '2.4.1';
        $meta['contact']        = 'http://webdesign-in-bremen.com';

        $meta['securityschema'] = array('Reviews::' => 'Review name::Review ID');
        return $meta;
    }
}