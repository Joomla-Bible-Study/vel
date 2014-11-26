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

jimport('joomla.application.component.view');


class VelnoticeViewVelignores extends JView
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

        $document = JFactory::getDocument();
        $document->addStyleSheet(JURI::base().'components/com_velnotice/css/icons.css');
        JHTML::_('stylesheet', 'icons.css', JURI::base().'components/com_velnotice/css/');
		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
        $canDo = VelNoticeHelper::getActions();
		JToolBarHelper::title(JText::_('VEL_IGNORE_LIST'), 'caution');
		if ($canDo->get('core.create')) 
		{
			JToolBarHelper::addNew('velignore.add', 'JTOOLBAR_NEW');
		}
		
        if ($canDo->get('core.edit')) 
		{
            JToolBarHelper::editList('velignore.edit');
        }
        
        JToolBarHelper::publishList('velignores.publish', 'JTOOLBAR_PUBLISH', true);
        JToolBarHelper::unpublishList('velignores.unpublish', 'JTOOLBAR_UNPUBLISH', true);
        if ($canDo->get('core.delete')) 
		{
            JToolBarHelper::archiveList('velignores.archive','JTOOLBAR_ARCHIVE');
            JToolBarHelper::trash('velignores.trash');
            JToolBarHelper::deleteList('', 'velignores.delete','JTOOLBAR_EMPTY_TRASH');
        }
        JToolBarHelper::divider();
        jimport( 'joomla.language.help' );
        jimport( 'joomla.i18n.help' );
        JToolBarHelper::help( 'help', true );
		
	}
}
