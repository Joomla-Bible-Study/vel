<?php
/**
*  version 1.1
 * @package		VEL Notice
 * @copyright	Copyright (C) 2011 Tom Fuller, Inc. All rights reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Technical Support: http://joomlacode.org/gf/project/extensionsecure/
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
$done = JRequest::getInt('veldone','','get');
if ($done > 0) 
{
    echo $this->loadTemplate('messages');
} 
echo $this->loadTemplate('main');
?>