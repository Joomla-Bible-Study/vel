<?php
/**
*  version 1.1
 * @package		VEL Notice
 * @copyright	Copyright (C) 2011 Tom Fuller, Inc. All rights reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Technical Support: http://joomlacode.org/gf/project/extensionsecure/
 */
//No Direct Access
defined('_JEXEC') or die('Restricted access');

?>
<form action="<?php echo JRoute::_('index.php?option=com_velnotice&layout=form&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm">
    <div class="width-100 fltlft">
        <fieldset class="panelform">
            <legend><?php echo JText::_('VEL_IGNORE_DETAILS'); ?></legend>
            <ul class="adminformlist">
            <li><?php echo $this->form->getLabel('id'); ?>
				<?php echo $this->form->getInput('id'); ?></li>
			</ul>
                <li>
                    <?php echo $this->form->getLabel('published'); ?>
                    <?php echo $this->form->getInput('published'); ?>
                </li>
                	
                <li>
                    <?php echo $this->form->getLabel('extension_number'); ?>
                    <?php echo $this->form->getInput('extension_number'); ?>
                
            </ul>

        </fieldset>
    </div>
    
	  <div class="clr"></div>
	<?php if ($this->canDo->get('core.admin')): ?>
		<div class="width-100 fltlft">
			<?php echo JHtml::_('sliders.start','permissions-sliders-'.$this->item->id, array('useCookie'=>1)); ?>

				<?php echo JHtml::_('sliders.panel',JText::_('VEL_FIELDSET_RULES'), 'access-rules'); ?>

				<fieldset class="panelform">
					<?php echo $this->form->getLabel('rules'); ?>
					<?php echo $this->form->getInput('rules'); ?>
				</fieldset>

			<?php echo JHtml::_('sliders.end'); ?>
		</div>
	<?php endif; ?>
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <?php echo JHtml::_('form.token'); ?>
</form>
