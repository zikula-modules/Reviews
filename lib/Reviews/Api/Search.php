<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2001, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id: Search.php 445 2010-07-06 16:09:10Z drak $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Zikula_Value_Addons
 * @subpackage Reviews
 */

class Reviews_Api_Search extends Zikula_AbstractApi
{
    /**
     * Search plugin info
     **/
    public function info()
    {
        return array('title' => 'Reviews',
                'functions' => array('Reviews' => 'search'));
    }

    /**
     * Search form component
     **/
    public function options($args)
    {
        if (SecurityUtil::checkPermission('Reviews::', '::', ACCESS_READ)) {
            // Create output object - this object will store all of our output so that
            // we can return it easily when required
            $render = Zikula_View::getInstance('Reviews');
            $render->assign('active', !isset($args['active']) || isset($args['active']['Reviews']));
            return $render->fetch('reviews_search_options.htm');
        }

        return '';
    }

    /**
     * Search plugin main function
     **/
    public function search($args)
    {
        ModUtil::dbInfoLoad('Search');
        $pntable = DBUtil::getTables();
        $reviewstable = $pntable['reviews'];
        $reviewscolumn = $pntable['reviews_column'];
        $searchTable = $pntable['search_result'];
        $searchColumn = $pntable['search_result_column'];

        $where = search_construct_where($args,
                array($reviewscolumn['title'],
                $reviewscolumn['text']),
                null);

        $sessionId = session_id();

        $sql = "
SELECT 
                $reviewscolumn[title] as title,
                $reviewscolumn[text] as text,
                $reviewscolumn[id] as id,
                $reviewscolumn[cr_date] as date
FROM $reviewstable
WHERE $where";

        $result = DBUtil::executeSQL($sql);
        if (!$result) {
            return LogUtil::registerError(__('Error! Could not load items.', $dom));
        }

        $insertSql =
                "INSERT INTO $searchTable
  ($searchColumn[title],
                $searchColumn[text],
                $searchColumn[extra],
                $searchColumn[created],
                $searchColumn[module],
                $searchColumn[session])
VALUES ";


        // Process the result set and insert into search result table
        for (; !$result->EOF; $result->MoveNext()) {
            $item = $result->GetRowAssoc(2);
            if (SecurityUtil::checkPermission('Reviews::', "$item[title]::$item[id]", ACCESS_READ)) {
                $sql = $insertSql . '('
                        . '\'' . DataUtil::formatForStore($item['title']) . '\', '
                        . '\'' . DataUtil::formatForStore($item['text']) . '\', '
                        . '\'' . DataUtil::formatForStore($item['id']) . '\', '
                        . '\'' . DataUtil::formatForStore($item['date']) . '\', '
                        . '\'' . 'Reviews' . '\', '
                        . '\'' . DataUtil::formatForStore($sessionId) . '\')';
                $insertResult = DBUtil::executeSQL($sql);
                if (!$insertResult) {
                    return LogUtil::registerError(__('Error! Could not load items.', $dom));
                }
            }
        }

        return true;
    }

    /**
     * Do last minute access checking and assign URL to items
     *
     * Access checking is ignored since access check has
     * already been done. But we do add a URL to the found item
     */
    public function search_check(&$args)
    {
        $datarow  = &$args['datarow'];
        $reviewId = $datarow['extra'];

        $datarow['url'] = ModUtil::url('Reviews', 'user', 'display', array('id' => $reviewId));

        return true;
    }
}