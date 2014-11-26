<?php

/**
*  version 1.1
 * @package		VEL Notice
 * @copyright	Copyright (C) 2011 Tom Fuller, Inc. All rights reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Technical Support: http://joomlacode.org/gf/project/extensionsecure/
 */


defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');


class VelnoticeModelvelignores extends JModelList
{
	
	/**
	 * Constructor.
	 *
	 * @param	array	An optional associative array of configuration settings.
	 * @see		JController
	 * @since	1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'extension_number', 'a.extension_number',
                'extension_name', 'b.extension_name',
                'published', 'a.published'
			);
		}

		parent::__construct($config);
	}
	
	
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		
		$published = $this->getUserStateFromRequest($this->context.'.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.published', $published);

		// List state information.
		parent::populateState('a.extension_number', 'asc');
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param	string		$id	A prefix for the store id.
	 * @return	string		A store id.
	 * @since	1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id.= ':' . $this->getState('filter.extension_number');
		$id.= ':' . $this->getState('filter.published');
		$id.= ':' . $this->getState('filter.extension_name');
		$id.= ':' . $this->getState('filter.id');
		
		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return	JDatabaseQuery
	 * @since	1.6
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.id, a.extension_number, a.published, a.asset_id' 
			)
		);
		$query->from('`#__velnotice_ignorelist` AS a');

		// Join over the language
		$query->select('b.name AS extension_name');
        $query->select('b.extension_id AS extension_id');
		$query->join('LEFT', '`#__extensions` AS b ON b.extension_id = a.extension_number');

		
		// Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published)) {
			$query->where('a.published = '.(int) $published);
		} else if ($published === '') {
			$query->where('(a.published IN (0, 1))');
		}

		
		return $query;
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

}


?>