<?php

/**
*  version 1.1
 * @package		VEL Notice
 * @copyright	Copyright (C) 2011 Tom Fuller, Inc. All rights reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Technical Support: http://joomlacode.org/gf/project/extensionsecure/
 */

defined('_JEXEC') or die;

class VelnoticeTablevelignore extends JTable
{
	/**
	 * Constructor
	 *
	 * @param JDatabase A database connector object
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__velnotice_ignorelist', 'id', $db);
	}
    
   public function bind($array, $ignore = '')
    {
   
    // Bind the rules.
    if (isset($array['rules']) && is_array($array['rules'])) {
    $rules = new JRules($array['rules']);
    $this->setRules($rules);
    }
    
    return parent::bind($array, $ignore);
    } 
    
    /**
	 * Method to compute the default name of the asset.
	 * The default name is in the form `table_name.id`
	 * where id is the value of the primary key of the table.
	 *
	 * @return	string
	 * @since	1.6
	 */
	protected function _getAssetName()
	{
		$k = $this->_tbl_key;
		return 'com_velnotice.velignore.'.(int) $this->$k;
	}
 
	/**
	 * Method to return the title to use for the asset table.
	 *
	 * @return	string
	 * @since	1.6
	 */
	protected function _getAssetTitle()
	{
		return $this->extension_number;
	}
 
	/**
	 * Get the parent asset id for the record
	 *
	 * @return	int
	 * @since	1.6
	 */
	protected function _getAssetParentId()
	{
		$asset = JTable::getInstance('Asset');
		$asset->loadByName('com_velnotice');
		return $asset->id;
	}
}



?>