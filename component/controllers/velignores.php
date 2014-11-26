<?php

/**
*  version 1.3
 * @package		VEL Notice
 * @copyright	Copyright (C) 2011 Tom Fuller, Inc. All rights reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Technical Support: http://joomlacode.org/gf/project/extensionsecure/
 */

defined('_JEXEC') or die();

    jimport('joomla.application.component.controlleradmin');
   
class VelnoticeControllerVelignores extends JControllerAdmin
{
    
    /**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function getModel($name = 'velignore', $prefix = 'VelnoticeModel') 
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true)); 
		return $model;
	}
    
  //  protected $view_list = 'velignores';
	


	
}

?>