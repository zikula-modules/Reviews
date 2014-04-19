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
 * @version Generated by ModuleStudio 0.6.2 (http://modulestudio.de).
 */

/**
 * The reviewsActionUrl modifier creates the URL for a given action.
 *
 * @param string $urlType      The url type (admin, user, etc.)
 * @param string $urlFunc      The url func (view, display, edit, etc.)
 * @param array  $urlArguments The argument array containing ids and other additional parameters
 *
 * @return string Desired url in encoded form.
 */
function smarty_modifier_reviewsActionUrl($urlType, $urlFunc, $urlArguments)
{
    return DataUtil::formatForDisplay(ModUtil::url('Reviews', $urlType, $urlFunc, $urlArguments));
}