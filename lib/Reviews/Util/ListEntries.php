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
 * @version Generated by ModuleStudio 0.6.0 (http://modulestudio.de) at Fri Aug 09 17:57:06 CEST 2013.
 */

/**
 * Utility implementation class for list field entries related methods.
 */
class Reviews_Util_ListEntries extends Reviews_Util_Base_ListEntries
{
    /**
     * Get 'score' list entries.
     *
     * @return array Array with desired list entries.
     */
    public function getScoreEntriesForReview()
    {
        $states = array();
        $states[] = array('value'   => '10',
                          'text'    => $this->__('10'),
                          'title'   => '',
                          'image'   => '',
                          'default' => false);
        $states[] = array('value'   => '9',
                          'text'    => $this->__('9'),
                          'title'   => '',
                          'image'   => '',
                          'default' => false);
        $states[] = array('value'   => '8',
                          'text'    => $this->__('8'),
                          'title'   => '',
                          'image'   => '',
                          'default' => false);
        $states[] = array('value'   => '7',
                          'text'    => $this->__('7'),
                          'title'   => '',
                          'image'   => '',
                          'default' => false);
        $states[] = array('value'   => '6',
                          'text'    => $this->__('6'),
                          'title'   => '',
                          'image'   => '',
                          'default' => false);
        $states[] = array('value'   => '5',
                          'text'    => $this->__('5'),
                          'title'   => '',
                          'image'   => '',
                          'default' => false);
        $states[] = array('value'   => '4',
                          'text'    => $this->__('4'),
                          'title'   => '',
                          'image'   => '',
                          'default' => false);
        $states[] = array('value'   => '3',
                          'text'    => $this->__('3'),
                          'title'   => '',
                          'image'   => '',
                          'default' => false);
        $states[] = array('value'   => '2',
                          'text'    => $this->__('2'),
                          'title'   => '',
                          'image'   => '',
                          'default' => false);
        $states[] = array('value'   => '1',
                          'text'    => $this->__('1'),
                          'title'   => '',
                          'image'   => '',
                          'default' => false);
    
        return $states;
    }
}
