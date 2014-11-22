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

class Reviews_Version extends Reviews_Base_Version
{
    public function getMetaData()
    {
        $meta = parent::getMetaData();
        
        $meta['displayname']    = __('Reviews system');
        $meta['description']    = __('Reviews system module');
        //! this defines the module's url
        $meta['url']            = __('reviews');
        $meta['version']        = '2.5.0';
        $meta['contact']        = 'http://webdesign-in-bremen.com';

        return $meta;
    }
}