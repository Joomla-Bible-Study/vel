<?php
// No direct access to this file
defined('_JEXEC') or die;
 
/**
*  version 1.3
 * @package		VEL Notice
 * @copyright	Copyright (C) 2011 Tom Fuller, Inc. All rights reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Technical Support: http://joomlacode.org/gf/project/extensionsecure/
 */
abstract class VelNoticeHelper
{
	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($submenu) 
	{
		JSubMenuHelper::addEntry(JText::_('COM_VELNOTICE_SUBMENU_VEL'), 'index.php?option=com_velnotice&view=vel', $submenu == 'vel');
		JSubMenuHelper::addEntry(JText::_('COM_VELNOTICE_SUBMENU_IGNORE'), 'index.php?option=com_velnotice&view=velignores', $submenu == 'velignores');
		// set some global property
		$document = JFactory::getDocument();

	
	}
	/**
	 * Get the actions
	 */
	public static function getActions($messageId = 0)
	{	
			$user	= JFactory::getUser();
		$result	= new JObject;

		if (empty($messageId)) {
			$assetName = 'com_velnotice';
		} else {
			$assetName = 'com_velnotice.velignore.'.(int) $messageId;
		}

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}
 
		return $result;
	}
}