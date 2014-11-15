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

                try {
                    DoctrineHelper::updateSchema($this->entityManager, $this->listEntityClasses());
                } catch (\Exception $e) {
                    if (System::isDevelopmentMode()) {
                        LogUtil::registerError($this->__('Doctrine Exception: ') . $e->getMessage());
                    }
                    return LogUtil::registerError($this->__f('An error was encountered while dropping the tables for the %s extension.', array($this->getName())));
                }

                $repository = $this->getEntityManager()->getRepository('Reviews_Entity_Review');

                $result = DBUtil::executeSQL('SELECT * FROM `reviews`');
                $reviews = $result->fetchAll(Doctrine::FETCH_ASSOC);

                $workflowHelper = new Zikula_Workflow('standard', 'Reviews');

                // we get serviceManager
                $serviceManager = ServiceUtil::getManager();
                // we get entityManager
                $entityManager = $serviceManager->getService('doctrine.entitymanager');

                if (count($reviews) > 0) {
                    foreach ($reviews as $key => $review) {
                        $newReview = new Reviews_Entity_Review();
                        $newReview->setWorkflowState('approved');
                        $newReview->setTitle($review['pn_title']);
                        $newReview->setText($review['pn_text']);
                        $newReview->setReviewer($review['pn_reviewer']);
                        $newReview->setEmail($review['pn_email']);
                        $newReview->setScore($review['pn_score']);
                        $newReview->setCover($review['pn_cover']);
                        $newReview->setUrl($review['pn_url']);
                        $newReview->setUrl_title($review['pn_url_title']);
                        $newReview->setHits($review['pn_hits']);
                        $newReview->setZlanguage($review['pn_language']);
                        //$createdDate = $review['pn_cr_date'];
                        //$createdDate = DateUtil::getDatetime($createdDate);
                        //$newReview->setCreatedDate($review['pn_cr_date']);
                        $updatedDate = $review['pn_lu_date'];
                        //$updatedDate = DateUtil::getDatetime($updatedDate);
                        //$newReview->setUpdatedDate($review['pn_lu_date']);
                        $newReview->setCreatedUserId($review['pn_cr_uid']);
                        $newReview->setUpdatedUserId($review['pn_lu_uid']);

                        $tables = DBUtil::getTables();
                        $catmapcolumn = $tables['categories_mapobj_column'];
                        $where = "$catmapcolumn[obj_id] = '" . DataUtil::formatForStore($review['id']). "'";
                        $where .= " AND ";
                        $where .= "$catmapcolumn[modname] = 'Reviews'";
                        $categories = DBUtil::selectObjectArray('categories_mapobj', $where);
                        foreach ($categories as $category) {
                            LogUtil::registerError($category['category_id']);
                            $thiscategories[] = $category['category_id'] ;
                        }
                        $newReview->setCategories($thiscategories);
                        $entityManager->persist($newReview);
                        $entityManager->flush();

                    }
                }
                 
                $result2 = DBUtil::executeSQL('SELECT * FROM `reviews_review`');
                $reviews2 = $result2->fetchAll(Doctrine::FETCH_ASSOC);

                foreach ($reviews2 as $key => $review2) {
                    $obj['__WORKFLOW__']['obj_table'] = 'review';
                    $obj['__WORKFLOW__']['obj_idcolumn'] = 'id';
                    $obj['id'] = $review2['id'];
                    $workflowHelper->registerWorkflow($obj, 'approved');
                }
                
                $result3 = DBUtil::executeSQL('SELECT * FROM `categories_registry`');
                $categoriesRegistered = $result3->fetchAll(Doctrine::FETCH_ASSOC);
                //$categoriesRegistered = CategoryRegistryUtil::getRegisteredModuleCategories($this->name, 'reviews');
                foreach ($categoriesRegistered as $categoryRegistered) {
                    if ($categoryRegistered['tablename'] == 'reviews')
                    CategoryRegistryUtil::updateEntry($categoryRegistered['id'], $this->name, 'Review', $categoryRegistered['property'], $categoryRegistered['category_id']);
                }

                $pagesize = $this->getVar('itemsperpage');
                $this->setVar('pagesize', $pagesize);
                $this->delVar('itemsperpage');
                $this->setVar('scoreForUsers', false);
                $addcategorytitletopermalink = $this->getVar('addcategorytitletopermalink');
                $this->setVar('addcategorytitletopermalink');
                
                DBUtil::dropTable('reviews');

            case '2.5.0':

                // later upgrades
        }

        // upgrade successful
        return true;
    }
}