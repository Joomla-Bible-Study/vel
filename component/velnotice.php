<?php
/**
*  version 1.1
 * @package		VEL Notice
 * @copyright	Copyright (C) 2011 Tom Fuller, Inc. All rights reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Technical Support: http://joomlacode.org/gf/project/extensionsecure/
 */

// no direct access
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_velnotice')) 
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}
 
// require helper file
JLoader::register('VelNoticeHelper', dirname(__FILE__) . DS . 'helpers' . DS . 'velnotice.php');

// Require the com_content helper library
jimport('joomla.application.component.controller');

//JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables');

$controller	= JController::getInstance('velnotice');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();

