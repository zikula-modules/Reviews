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
 * Utility base class for list field entries related methods.
 */
class Reviews_Util_Base_ListEntries extends Zikula_AbstractBase
{
    /**
     * Return the name or names for a given list item.
     *
     * @param string $value      The dropdown value to process.
     * @param string $objectType The treated object type.
     * @param string $fieldName  The list field's name.
     * @param string $delimiter  String used as separator for multiple selections.
     *
     * @return string List item name.
     */
    public function resolve($value, $objectType = '', $fieldName = '', $delimiter = ', ')
    {
        if (empty($value) || empty($objectType) || empty($fieldName)) {
            return $value;
        }
    
        $isMulti = $this->hasMultipleSelection($objectType, $fieldName);
        if ($isMulti === true) {
            $value = $this->extractMultiList($value);
        }
    
        $options = $this->getEntries($objectType, $fieldName);
        $result = '';
    
        if ($isMulti === true) {
            foreach ($options as $option) {
                if (!in_array($option['value'], $value)) {
                    continue;
                }
                if (!empty($result)) {
                    $result .= $delimiter;
                }
                $result .= $option['text'];
            }
        } else {
            foreach ($options as $option) {
                if ($option['value'] != $value) {
                    continue;
                }
                $result = $option['text'];
                break;
            }
        }
    
        return $result;
    }
    

    /**
     * Extract concatenated multi selection.
     *
     * @param string  $value The dropdown value to process.
     *
     * @return array List of single values.
     */
    public function extractMultiList($value)
    {
        $listValues = explode('###', $value);
        $amountOfValues = count($listValues);
        if ($amountOfValues > 1 && $listValues[$amountOfValues - 1] == '') {
            unset($listValues[$amountOfValues - 1]);
        }
        if ($listValues[0] == '') {
            // use array_shift instead of unset for proper key reindexing
            // keys must start with 0, otherwise the dropdownlist form plugin gets confused
            array_shift($listValues);
        }
    
        return $listValues;
    }
    

    /**
     * Determine whether a certain dropdown field has a multi selection or not.
     *
     * @param string $objectType The treated object type.
     * @param string $fieldName  The list field's name.
     *
     * @return boolean True if this is a multi list false otherwise.
     */
    public function hasMultipleSelection($objectType, $fieldName)
    {
        if (empty($objectType) || empty($fieldName)) {
            return false;
        }
    
        $result = false;
        switch ($objectType) {
            case 'review':
                switch ($fieldName) {
                    case 'workflowState':
                        $result = false;
                        break;
                    case 'score':
                        $result = false;
                        break;
                }
                break;
        }
    
        return $result;
    }
    

    /**
     * Get entries for a certain dropdown field.
     *
     * @param string  $objectType The treated object type.
     * @param string  $fieldName  The list field's name.
     *
     * @return array Array with desired list entries.
     */
    public function getEntries($objectType, $fieldName)
    {
        if (empty($objectType) || empty($fieldName)) {
            return array();
        }
    
        $entries = array();
        switch ($objectType) {
            case 'review':
                switch ($fieldName) {
                    case 'workflowState':
                        $entries = $this->getWorkflowStateEntriesForReview();
                        break;
                    case 'score':
                        $entries = $this->getScoreEntriesForReview();
                        break;
                }
                break;
        }
    
        return $entries;
    }

    
    /**
     * Get 'workflow state' list entries.
     *
     * @return array Array with desired list entries.
     */
    public function getWorkflowStateEntriesForReview()
    {
        $states = array();
        $states[] = array('value'   => 'waiting',
                          'text'    => $this->__('Waiting'),
                          'title'   => $this->__('Content has been submitted and waits for approval.'),
                          'image'   => '',
                          'default' => false);
        $states[] = array('value'   => 'approved',
                          'text'    => $this->__('Approved'),
                          'title'   => $this->__('Content has been approved and is available online.'),
                          'image'   => '',
                          'default' => false);
        $states[] = array('value'   => 'suspended',
                          'text'    => $this->__('Suspended'),
                          'title'   => $this->__('Content has been approved, but is temporarily offline.'),
                          'image'   => '',
                          'default' => false);
        $states[] = array('value'   => '!waiting',
                          'text'    => $this->__('All except waiting'),
                          'title'   => $this->__('Shows all items except these which are waiting'),
                          'image'   => '',
                          'default' => false);
        $states[] = array('value'   => '!approved',
                          'text'    => $this->__('All except approved'),
                          'title'   => $this->__('Shows all items except these which are approved'),
                          'image'   => '',
                          'default' => false);
        $states[] = array('value'   => '!suspended',
                          'text'    => $this->__('All except suspended'),
                          'title'   => $this->__('Shows all items except these which are suspended'),
                          'image'   => '',
                          'default' => false);
    
        return $states;
    }
    
    /**
     * Get 'score' list entries.
     *
     * @return array Array with desired list entries.
     */
    public function getScoreEntriesForReview()
    {
        $states = array();
        $states[] = array('value'   => '10',
                          'text'    => $this->__('Ten'),
                          'title'   => '',
                          'image'   => '',
                          'default' => false);
        $states[] = array('value'   => '9',
                          'text'    => $this->__('Nine'),
                          'title'   => '',
                          'image'   => '',
                          'default' => false);
        $states[] = array('value'   => '8',
                          'text'    => $this->__('Eight'),
                          'title'   => '',
                          'image'   => '',
                          'default' => false);
        $states[] = array('value'   => '7',
                          'text'    => $this->__('Seven'),
                          'title'   => '',
                          'image'   => '',
                          'default' => false);
        $states[] = array('value'   => '6',
                          'text'    => $this->__('Six'),
                          'title'   => '',
                          'image'   => '',
                          'default' => false);
        $states[] = array('value'   => '5',
                          'text'    => $this->__('Five'),
                          'title'   => '',
                          'image'   => '',
                          'default' => false);
        $states[] = array('value'   => '4',
                          'text'    => $this->__('Four'),
                          'title'   => '',
                          'image'   => '',
                          'default' => false);
        $states[] = array('value'   => '3',
                          'text'    => $this->__('Three'),
                          'title'   => '',
                          'image'   => '',
                          'default' => false);
        $states[] = array('value'   => '2',
                          'text'    => $this->__('Two'),
                          'title'   => '',
                          'image'   => '',
                          'default' => false);
        $states[] = array('value'   => '1',
                          'text'    => $this->__('One'),
                          'title'   => '',
                          'image'   => '',
                          'default' => false);
    
        return $states;
    }
}
