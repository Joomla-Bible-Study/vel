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
$this->vellist = JRequest::getVar('velarray','','get');
?>
<?php


?>

<table class="adminlist">
<thead>
<tr align="left">
    <th width="20%" class="title" align="left">
    <?php echo JText::_('VEL_EXTENSION_TITLE'); ?>
    </th>
    <th width="10%" class="title" align="left">
    <?php echo JText::_('VEL_EXTENSION_VERSION'); ?>
    </th>
    <th width="10%" align="left">
    <?php echo JText::_('VEL_STATUS'); ?>
    </th>
    <th align="left">
    <?php echo JText::_('VEL_DETAILS_FROM_FEED'); ?>
    </th>
</tr>
</thead>
<tfoot>
<tr><td colspan="4">
<?php echo JText::_('VEL_RED').': '.JText::_('VEL_PROBLEM_VERSION_MATCH').', '.JText::_('VEL_YELLOW').': '.JText::_('VEL_EXTENSION_MATCH_ONLY').', '.JText::_('VEL_GREEN').': '.JText::_('VEL_FIXED_VERSION_MATCH').' - ';?>
<a href="http://feeds.joomla.org/JoomlaSecurityVulnerableExtensions?format=xml" target="_blank"><?php echo JText::_('VEL_VIEW_FEED');?></a></td></tr>

</tfoot>
<tbody>
<?php 

if (!$this->vellist) {echo '<tr><td colspan="4"><p style="text-align:center; font-weight: bold;">'.JText::_('VEL_NO_MATCH').'</p></td><tr>';}

foreach ($this->vellist AS $velitem)
{
    ?><tr>
    <td>
    <?php echo '<strong><a href="'.$velitem['link'].'" target="_blank" title="VEL_CLICK_TO_SEE_DETAILS">'.$velitem['title'].'</a></strong>'; //print_r($velitem);?>
    </td>
    <td>
    <?php echo $velitem['version'];?>
    </td>
    <td>
    <?php echo '<img src="components/com_velnotice/images/'.$velitem['image'].'.png" title="'.JText::_('VEL_FOOTER').'">';?>
    </td>
    <td>
    <?php echo $velitem['description'];?>
    </td>
    </tr>
    <?php
}

?> 
</tbody> 
</table>
<?php $ignorelist = JRequest::getVar('ignoretrue'); 
if ($ignorelist){ ?>

<table>
<tr><td><p><a href="index.php?option=com_velnotice&view=velignores"><?php echo JText::_('VEL_IGNORE_ITEMS_TRUE'); ?></a></p></td></tr>
</table>
<?php } ?>
<?php $descriptiontrue = JRequest::getInt('descriptiontrue',0,'get');
if ($descriptiontrue)
    {
    
?>
<table>
<tr><td><p><a href="index.php?option=com_velnotice&task=dovel&view=vel&usedesc=1"><?php echo JText::_('VEL_DESCRIPTION_CLICK_LINK'); ?></a></p></td></tr>
</table>
<?php } ?>
