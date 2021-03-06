<?php
/**
 * Reviews.
 *
 * @copyright Michael Ueberschaer (MU)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @package Reviews
 * @author Michael Ueberschaer <kontakt@webdesign-in-bremen.com>.
 * @link http://webdesign-in-bremen.com
 * @link http://zikula.org
 * @version Generated by ModuleStudio 0.6.0 (http://modulestudio.de) at Sat Aug 10 17:43:09 CEST 2013.
 */

/**
 * Generic item list content plugin implementation class.
 */
class Reviews_ContentType_ItemList extends Reviews_ContentType_Base_ItemList
{
    // feel free to extend the content type here
}

function Reviews_Api_ContentTypes_itemlist($args)
{
    return new Reviews_Api_ContentTypes_itemListPlugin();
}
