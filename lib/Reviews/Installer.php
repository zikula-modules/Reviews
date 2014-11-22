<?php
use DoctrineExtensions\Query\Mysql\CountIf;

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

                // we get all old entries
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

                        $createdDate =  $review['pn_cr_date'];
                        //$createdDate = $createdDate->getTimestamp();

                        //$createdDate = date( 'Y-m-d H:i:s', $createdDate);
                        //$createdDate = DateUtil::formatDatetime($review['pn_cr_date'], 'datetimelong');
                        //$newReview->setCreatedDate($createdDate);
                        //$updatedDate = $review['pn_lu_date'];

                        //$updatedDate = $updatedDate->getTimestamp();

                        //$updatedDate = date( 'Y-m-d H:i:s', $updatedDate);
                        //$newReview->setUpdatedDate($updatedDate);
                        $newReview->setCreatedUserId($review['pn_cr_uid']);
                        $newReview->setUpdatedUserId($review['pn_lu_uid']);

                        $entityManager->persist($newReview);
                        $entityManager->flush();
                    }
                }
                 
                $result2 = DBUtil::executeSQL('SELECT * FROM `reviews_review`');
                $reviews2 = $result2->fetchAll(Doctrine::FETCH_ASSOC);

                // we set the workflow
                foreach ($reviews2 as $key => $review2) {
                    $obj['__WORKFLOW__']['obj_table'] = 'review';
                    $obj['__WORKFLOW__']['obj_idcolumn'] = 'id';
                    $obj['id'] = $review2['id'];
                    $workflowHelper->registerWorkflow($obj, 'approved');
                }

                // move relations from categories_mapobj to reviews_category
                // then delete old data
                $connection = $this->entityManager->getConnection();
                $sqls = array();
                $sqls[] = "INSERT INTO reviews_review_category (entityId, registryId, categoryId) SELECT obj_id, reg_id, category_id FROM categories_mapobj WHERE modname = 'Reviews' AND tablename = 'reviews'";
                $sqls[] = "DELETE FROM categories_mapobj WHERE modname = 'Reviews' AND tablename = 'reviews'";
                // update category registry data to change tablename to EntityName
                $sqls[] = "UPDATE categories_registry SET tablename = 'Review' WHERE tablename = 'reviews'";
                // do changes
                foreach ($sqls as $sql) {
                    $stmt = $connection->prepare($sql);
                    try {
                        $stmt->execute();
                    } catch (Exception $e) {
                        LogUtil::registerError($e->getMessage());
                    }
                }

                $pagesize = $this->getVar('itemsperpage');
                $this->setVar('pagesize', $pagesize);
                $this->delVar('itemsperpage');
                $this->setVar('scoreForUsers', false);
                $addcategorytitletopermalink = $this->getVar('addcategorytitletopermalink');
                $this->setVar('addcategorytitletopermalink');
                
                // register persistent event handlers
                $this->registerPersistentEventHandlers();
                
                // register hook subscriber bundles
                HookUtil::registerSubscriberBundles($this->version->getHookSubscriberBundles());

                DBUtil::dropTable('reviews');

            case '2.5.0':

                // later upgrades
        }

        // upgrade successful
        return true;
    }
}