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


class VelnoticeViewVelignore extends JView
{
	protected $form;
    protected $item;
    protected $state;
    protected $defaults;

    function display($tpl = null) {
        $this->form = $this->get("Form");
        $this->item = $this->get("Item");
        $this->state = $this->get("State");
        $this->setLayout('edit');
        $document = JFactory::getDocument();
        $document->addStyleSheet(JURI::base().'components/com_velnotice/css/icons.css');
        JHTML::_('stylesheet', 'icons.css', JURI::base().'components/com_velnotice/css/');
		$this->canDo = VelNoticeHelper::getActions($this->item->id);
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
		
	    JRequest::setVar('hidemainmenu', true);
		$user = JFactory::getUser();
		$userId = $user->id;
		
		JToolBarHelper::title(JText::_('VEL_IGNORE_LIST'), 'caution');
		if ($this->canDo->get('core.create')) 
		{
            JToolBarHelper::save('velignore.save');
            JToolBarHelper::apply('velignore.apply');
        }
        JToolBarHelper::cancel('velignore.cancel', 'JTOOLBAR_CANCEL');
        JToolBarHelper::divider();
		jimport( 'joomla.language.help' );
        jimport( 'joomla.i18n.help' );
        JToolBarHelper::help( 'help', true );
	}
}
