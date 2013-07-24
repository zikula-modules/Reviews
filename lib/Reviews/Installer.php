<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2002, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id: Installer.php 445 2010-07-06 16:09:10Z drak $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Zikula_Value_Addons
 * @subpackage Reviews
 */

class Reviews_Installer extends Zikula_AbstractInstaller
{
    /**
     * init reviews module
     */
    public function install()
    {
        // create table
        if (!DBUtil::createTable('reviews')) {
            return false;
        }

        // set up config variables
        $modvars = array(
                'itemsperpage' => 25,
                'enablecategorization' => true,
                'addcategorytitletopermalink' => true
        );

        // create our default category
        if (!$this->_createdefaultcategory()) {
            LogUtil::registerStatus($this->__('Warning! Could not create the default Reviews category tree. If you want to use categorization for the reviews, register at least one property for the module in the Category Registry.'));
            $modvars['enablecategorization'] = false;
        }

        // set up module variables
        ModUtil::setVars('Reviews', $modvars);

        // initialisation successful
        return true;
    }

    /**
     * upgrade
     */
    public function upgrade($oldversion)
    {
        // update table
        if (!DBUtil::changeTable('reviews')) {
            return false;
        }

        // Upgrade dependent on old version number
        switch ($oldversion)
        {
            case '2.4':
                $prefix = $this->serviceManager['prefix'];
                $connection = Doctrine_Manager::getInstance()->getConnection('default');
                $sql = 'RENAME TABLE ' . $prefix . '_' . 'reviews' . ' TO ' . 'reviews';
                $stmt = $connection->prepare($sql);
                try {
                    $stmt->execute();
                } catch (Exception $e) {
                    LogUtil::registerError($e);
                }

            case '2.4.1':
                // for later update
                break;
        }

        // upgrade successful
        return true;
    }

    /**
     * delete the reviews module
     */
    public function uninstall()
    {
        // drop table
        if (!DBUtil::dropTable('reviews')) {
            return false;
        }

        // Delete any module variables
        ModUtil::delVar('Reviews');

        // Delete entries from category registry
        ModUtil::dbInfoLoad('Categories');
        DBUtil::deleteWhere('categories_registry', "modname = 'Reviews'");
        DBUtil::deleteWhere('categories_mapobj', "modname = 'Reviews'");

        // Deletion successful
        return true;
    }

    /**
     * create default category for the module
     */
    function _createdefaultcategory($regpath = '/__SYSTEM__/Modules/Global')
    {
        // get the language file
        $lang = ZLanguage::getLanguageCode();

        // get the category path for which we're going to insert our place holder category
        $rootcat = CategoryUtil::getCategoryByPath('/__SYSTEM__/Modules');
        $rCat    = CategoryUtil::getCategoryByPath('/__SYSTEM__/Modules/Reviews');

        if (!$rCat) {
            // create placeholder for all our migrated categories
            $cat = new Categories_DBObject_Category ();
            $cat->setDataField('parent_id', $rootcat['id']);
            $cat->setDataField('name', 'Reviews');
            $cat->setDataField('display_name', array($lang => $this->__('Reviews')));
            $cat->setDataField('display_desc', array($lang => $this->__('Reviews system module')));
            if (!$cat->validate('admin')) {
                return false;
            }
            $cat->insert();
            $cat->update();
        }

        // get the category path for which we're going to insert our upgraded categories
        $rootcat = CategoryUtil::getCategoryByPath($regpath);
        if ($rootcat) {
            // create an entry in the categories registry
            $registry = new Categories_DBObject_Registry();
            $registry->setDataField('modname', 'Reviews');
            $registry->setDataField('table', 'reviews');
            $registry->setDataField('property', 'Main');
            $registry->setDataField('category_id', $rootcat['id']);
            $registry->insert();
        } else {
            return false;
        }

        return true;
    }
}