<?php

/**
*  version 1.1
 * @package		VEL Notice
 * @copyright	Copyright (C) 2011 Tom Fuller, Inc. All rights reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Technical Support: http://joomlacode.org/gf/project/extensionsecure/
 */


defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');


class VelnoticeModelvelignore extends JModelAdmin
{
	protected $text_prefix = 'COM_VELNOTICE';
    
    	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param	object	$record	A record object.
	 *
	 * @return	boolean	True if allowed to delete the record. Defaults to the permission set in the component.
	 * @since	1.6
	 */
	protected function canDelete($record)
	{
		if (!empty($record->id)) {
			if ($record->published != -2) {
				return ;
			}
			$user = JFactory::getUser();
			return $user->authorise('core.delete', 'com_velnotice.velignore.'.(int) $record->id);
		}
	}

	/**
	 * Method to test whether a record can have its state edited.
	 *
	 * @param	object	$record	A record object.
	 *
	 * @return	boolean	True if allowed to change the state of the record. Defaults to the permission set in the component.
	 * @since	1.6
	 */
	protected function canEditState($record)
	{
		$user = JFactory::getUser();

		// Check against the category.
		if (!empty($record->catid)) {
			return $user->authorise('core.edit.state', 'com_velnotice.velignore.'.(int) $record->id);
		}
		// Default to component settings if category not known.
		else {
			return parent::canEditState($record);
		}
	}

    /**
	 * Method override to check if you can edit an existing record.
	 *
	 * @param	array	$data	An array of input data.
	 * @param	string	$key	The name of the key for the primary key.
	 *
	 * @return	boolean
	 * @since	1.6
	 */
	protected function allowEdit($data = array(), $key = 'id')
	{
		// Check specific edit permission then general edit permission.
		return JFactory::getUser()->authorise('core.edit', 'com_velnotice.velignore.'.((int) isset($data[$key]) ? $data[$key] : 0)) or parent::allowEdit($data, $key);
	}
	/**
     * Get the form data
     *
     * @param <Array> $data
     * @param <Boolean> $loadData
     * @return <type>
     * @since 1.3
     */
    public function getForm($data = array(), $loadData = true) {
        // Get the form.
        $form = $this->loadForm('com_velnotice.velignore', 'velignore', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }
        if (!$this->canEditState((object) $data)) {
        $form->setFieldAttribute('published', 'disabled', 'true');
        $form->setFieldAttribute('published', 'filter', 'unset');
        }
        return $form;
    }

    /**
     *
     * @return <type>
     * @since   1.3
     */
    protected function loadFormData() {
        $data = JFactory::getApplication()->getUserState('com_velnotice.edit.velignore.data', array());
        if (empty($data))
            $data = $this->getItem();
            if ($this->getState('velignore.id') == 0) {
				$app = JFactory::getApplication();
				
			}
        return $data;
    }
    
    	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable($type = 'velignore', $prefix = 'VelnoticeTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get a single record.
	 *
	 * @param	integer	The id of the primary key.
	 *
	 * @return	mixed	Object on success, false on failure.
	 * @since	1.6
	 */
	public function getItem($pk = null)
	{
	   if ($item = parent::getItem($pk)) {
			
		}
	

		return $item;
	}

function setId($id) {
        // Set id and wipe data
        $this->_id = $id;
        $this->_data = null;
        $this->_admin = null;
    }
}


?>