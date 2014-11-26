<?php
/**
*  version 1.1
 * @package		VEL Notice
 * @copyright	Copyright (C) 2011 Tom Fuller, Inc. All rights reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Technical Support: http://joomlacode.org/gf/project/extensionsecure/
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.model');

/**
 * Vel Notice Component Model
 *
 * 
 * @subpackage	com_velnotice
 * @since 1.5
 */
class VelnoticeModelVel extends JModel
{
	/**
	 * VELNotice id
	 *
	 * @var int
	 */
	var $_id = null;

	/**
	 * VELNotice data
	 *
	 * @var array
	 */
	var $_data = null;

	/**
	 * Constructor
	 *
	 * @since 1.5
	 */
	public function __construct()
	{
		parent::__construct();

		$id = JRequest::getVar('id', 0, '', 'int');
		$this->setId((int)$id);
	}

	/**
	 * Method to set the velnotice identifier
	 *
	 * @access	public
	 * @param	int velnotice identifier
	 */
	public function setId($id)
	{
		// Set newsfeed id and wipe data
		$this->_id		= $id;
		$this->_data	= null;
	}

	/**
	 * Method to get the velnotice data
	 *
	 * @since 1.5
	 */
	public function &getData()
	{
		// Load the velnotice data
		if ($this->_loadData()) {

		

		}

		return $this->_data;
	}

	/**
	 * Method to load velnotice data
	 *
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	protected function _loadData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data)) {

			$db = $this->getDbo();
			$query = $db->getQuery(true);
			$query->select('*');
			$query->from('#__velnotice');
		//	print_r($query);

	//		// Filter by start and end dates.
			$nullDate = $db->quote($db->getNullDate());
	//		$nowDate = $db->quote(JFactory::getDate()->toMySQL());

		

			$db->setQuery($query);
			$this->_data = $db->loadObject();

			return (boolean) $this->_data;
		}

		return true;
	}

}