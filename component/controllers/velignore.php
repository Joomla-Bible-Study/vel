<?php

/**
*  version 1.3
 * @package		VEL Notice
 * @copyright	Copyright (C) 2011 Tom Fuller, Inc. All rights reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Technical Support: http://joomlacode.org/gf/project/extensionsecure/
 */

defined('_JEXEC') or die();

    jimport('joomla.application.component.controllerform');

class VelnoticeControllerVelignore extends JControllerForm
{
    /*
     * NOTE: This is needed to prevent Joomla 1.6's pluralization mechanisim from kicking in
     *
     * @todo  BCC  We should rename this controler to "mediafile" and the list view controller
     * to "mediafiles" so that the pluralization in 1.6 would work properly
     *
     * @since 7.0
     */
    protected $view_list = 'velignores';
	
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
	//	$this->registerTask( 'add'  , 	'edit' );
	}


	
}

?>