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
 * @version Generated by ModuleStudio 0.6.0 (http://modulestudio.de) at Sat Aug 10 17:43:08 CEST 2013.
 */

/**
 * Event handler implementation class for events of the Users module.
 */
class Reviews_Listener_Users extends Reviews_Listener_Base_Users
{
    /**
     * Listener for the `module.users.config.updated` event.
     *
     * Occurs after the Users module configuration has been
     * updated via the administration interface.
     *
     * @param Zikula_Event $event The event instance.
     */
    public static function configUpdated(Zikula_Event $event)
    {
        parent::configUpdated($event);
    }
}