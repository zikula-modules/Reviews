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

class Reviews_Controller_Admin extends Reviews_Controller_Base_Admin 
{
    protected function postInitialize()
    {
        $this->view->setCaching(false);
    }
}