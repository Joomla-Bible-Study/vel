<?php

/**
*  version 1.1
 * @package		VEL Notice
 * @copyright	Copyright (C) 2011 Tom Fuller, Inc. All rights reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Technical Support: http://joomlacode.org/gf/project/extensionsecure/
 */
jimport('joomla.application.component.view');

defined('_JEXEC') or die;

class VelnoticeViewVel extends JView
{
	function display($tpl = null)
	{
    
	$document = JFactory::getDocument();
    $document->addStyleSheet(JURI::base().'components/com_velnotice/css/icons.css');
    JHTML::_('stylesheet', 'icons.css', JURI::base().'components/com_velnotice/css/');
        $this->addToolbar();
		parent::display($tpl);
    }

	protected function _prepareDocument()
	{
	
		$title 		= null;
		$title = JText::_('VEL_PAGE_TITLE');
		$this->document->setTitle($title);
	}

	protected function addToolbar()
	{
		JToolBarHelper::title(JText::_('VEL_PAGE_TITLE'), 'caution');
		JToolBarHelper::divider();
        $canDo = VelNoticeHelper::getActions();
        if ($canDo->get('core.admin')) 
		{
		  JToolBarHelper::preferences('com_velnotice');
        }
    	jimport( 'joomla.language.help' );
        jimport( 'joomla.i18n.help' );
        JToolBarHelper::help( 'help', true );
	}    
}
?>