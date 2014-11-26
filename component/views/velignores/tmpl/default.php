<?php
/**
*  version 1.1
 * @package		VEL Notice
 * @copyright	Copyright (C) 2011 Tom Fuller, Inc. All rights reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Technical Support: http://joomlacode.org/gf/project/extensionsecure/
 */

defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
?>
<form action="<?php echo JRoute::_('index.php?option=com_velnotice&view=velignores'); ?>" method="post" name="adminForm" id="adminForm">
<fieldset id="filter-bar">
    <div class="filter-select fltrt">

			<select name="filter_published" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true);?>
			</select>
   </div>
</fieldset>
    <div id="editcell">
        <table class="adminlist">
            <thead>
                <tr>
                    <th width="1%">
                        <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
                    </th>
                    <th width="8%" align="center">
                        <?php echo JText::_('VEL_PUBLISHED'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('VEL_ID'); ?>
                    </th>
                    <th align="left">
                        <?php echo JText::_('VEL_EXTENSIONS'); ?>
                    </th>
                    
                </tr>
            </thead>
            	<tfoot>
			<tr>
				<td colspan="10">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
            <?php
                        foreach ($this->items as $i => $item) :
                            
            ?>
                            <tr class="row<?php echo $i % 2; ?>">
                                <td width="20">
                    <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                        </td>
                        <td width="20" align="center">
                    <?php echo JHtml::_('jgrid.published', $item->published, $i, 'velignores.', true, 'cb', '', ''); ?>
                    <?php //echo JHtml::_('jgrid.published', $item->state, $i, 'weblinks.', $canChange, 'cb', $item->publish_up, $item->publish_down); ?>
                        </td>
                        <td width="10" align="center"><?php echo $item->id; ?></td>
                        <td align="left">
                        <a href="<?php echo JRoute::_('index.php?option=com_velnotice&task=velignore.edit&id='.(int) $item->id); ?>">
                            <?php echo $item->extension_name; ?>
                            </a>
                        </td>
                        
                    </tr>
            <?php endforeach; ?>
                        </table>
                    </div>
                    <input type="hidden" name="task" value=""/>
                    <input type="hidden" name="boxchecked" value="0"/>
                    
    <?php echo JHtml::_('form.token'); ?>
</form>
