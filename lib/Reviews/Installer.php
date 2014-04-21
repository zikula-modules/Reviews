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

class Reviews_Installer extends Reviews_Base_Installer
{
    /**
     * init reviews module
     */
    /*public function install()
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
                $connection = Doctrine_Manager::getInstance()->getConnection('default');
                $sql = 'RENAME TABLE reviews TO reviews_review';
                $stmt = $connection->prepare($sql);
                try {
                    $stmt->execute();
                } catch (Exception $e) {
                    LogUtil::registerError($e);
                }

                $sql2 = "ALTER TABLE `reviews_review`
                        CHANGE `pn_id` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
                        ADD `workflowState` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
                        CHANGE `pn_title` `title` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
                        CHANGE `pn_urltitle` `slug` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
                        CHANGE `pn_text` `text` VARCHAR( 5000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL  ,
                        CHANGE `pn_language` `zlanguage` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
                        CHANGE `pn_reviewer` `reviewer` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
                        CHANGE `pn_email` `email` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
                        CHANGE `pn_score` `score` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
                        CHANGE `pn_url` `url` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                        CHANGE `pn_url_title` `url_title` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                        CHANGE `pn_hits` `hits` INT( 18 ) NOT NULL ,
                        CHANGE `pn_cr_date` `createdDate` DATETIME NOT NULL DEFAULT '1970-01-01 00:00:00',
                        CHANGE `pn_lu_date` `updatedDate` DATETIME NOT NULL DEFAULT '1970-01-01 00:00:00',
                        CHANGE `pn_cr_uid` `createdUserId` INT( 11 ) NOT NULL DEFAULT '0',
                        CHANGE `pn_lu_uid` `updatedUserId` INT( 11 ) NOT NULL DEFAULT '0'";
                
                
                $stmt2 = $connection->prepare($sql2);
                try {
                    $stmt2->execute();
                } catch (Exception $f) {
                    LogUtil::registerError($f);
                }

                try {
                    DoctrineHelper::updateSchema($this->entityManager, $this->listEntityClasses());
                } catch (\Exception $e) {
                    if (System::isDevelopmentMode()) {
                        LogUtil::registerError($this->__('Doctrine Exception: ') . $e->getMessage());
                    }
                    return LogUtil::registerError($this->__f('An error was encountered while dropping the tables for the %s extension.', array($this->getName())));
                }

                // we adapt the modvars
                $pagesize = $this->getVar('itemsperpage');
                $this->setVar('pagesize', $pagesize);
                $this->delVar('itemsperpage');
                $this->setVar('scoreForUsers', false);
                $addcategorytitletopermalink = $this->getVar('addcategorytitletopermalink');
                $this->setVar('addcategorytitletopermalink', $addcategorytitletopermalink);

                $serviceManager = ServiceUtil::getManager();
                $entityManager = $serviceManager->getService('doctrine.entitymanager');
                $repository = $entityManager->getRepository('Reviews_Entity_Review');
                
                //$repository = Reviews_Util_Model::getReviewRepository();
                //$reviews = $repository->selectWhere();
                $where = "tbl.workflowState = ''";
                $selectionArgs = array('ot' => 'review', 'where' => $where);
                $reviews = ModUtil::apiFunc($this->name, 'selection', 'getEntities', $selectionArgs);
                
                LogUtil::registerError(count($reviews));
                if (count($reviews) > 0) {
                    $serviceManager = ServiceUtil::getManager();
                    $entityManager = $serviceManager->getService('doctrine.entitymanager');
                    foreach ($reviews as $review) {
                        $thisreview = $repository->selectById($review['id']);
                        $thisreview->setWorkflowState('approved');
                        $thisreview->setCoverUploadMeta('a:0:{}');
                        
                        $tables = DBUtil::getTables();
                        $catmapcolumn = $tables['categories_mapobj_column'];
                        $where = "$catmapcolumn[obj_id] = '" . DataUtil::formatForStore($review['id']). "'";
                        $where .= " AND ";
                        $where .= "$catmapcolumn[modname]] = Reviews";
                        $categories = DBUtil::selectObjectArray('categories_mapobj', $where);
                        foreach ($categories as $category) {
                            $thiscategories[] = $category['category_id'] ;
                        }
                        $thisreview->setCategories($thiscategories);
                        $entityManager->flush();                 
                    }
                }
                
            case '2.5.0':
                
                // later upgrades
        }

        // upgrade successful
        return true;
    }

    /**
     * delete the reviews module
     */
    /*public function uninstall()
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
    /*function _createdefaultcategory($regpath = '/__SYSTEM__/Modules/Global')
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

    return true;*/
}