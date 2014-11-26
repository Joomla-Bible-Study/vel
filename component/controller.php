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

jimport('joomla.application.component.controller');

/**
 * VELNotice Component Controller
 *
 
 */
class VelnoticeController extends JController
{
	/**
	 * Method to show a VEL Notice view
	 *
	 * @param	boolean			If true, the view output will be cached
	 * 
	 *
	 * @return	JController		This object to support chaining.
	 * @since	1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{
	//	$cachable = true;

        require_once JPATH_COMPONENT.'/helpers/velnotice.php';

		// Load the submenu.
		VelnoticeHelper::addSubmenu(JRequest::getCmd('view', 'vel'));
		// Set the default view name and format from the Request.
        $view = JRequest::getWord('view', 'vel');
        $layout = JRequest::getWord('layout', 'default');
        $id		= JRequest::getInt('id');
        
		$vName		= JRequest::getWord('view', '','get');
        if (!$vName)
        {
            JRequest::setVar('view', 'vel');
        }
        JRequest::setVar('view', JRequest::getCmd('view', 'vel'));
        $veldone = 0;
        $task = JRequest::getWord('task','','get');
    
        if ($task == 'dovel')
        {
            $performvel = $this->velnotice();
            if ($performvel)
            {
                $veldone = JRequest::setVar('veldone',1,'get');
            }
        }
        
        
	

		parent::display($cachable);
	}
    
    public function velnotice()
    {
        $descriptionhit = array();
        $cacheDir = JPATH_BASE.DS.'cache'.DS;
		if (!is_writable($cacheDir)) {
			echo JText::_('CACHE_DIRECTORY_UNWRITABLE');
			return;
		}

        $db = JFactory::getDBO();
        $query = 'SELECT * FROM #__velnotice';
        $db->setQuery($query);
        $veldata = $db->loadObject();
      
     
        $registry = new JRegistry;
        $registry->loadJSON($veldata->params);
        $params = $registry;
        
		
        
        //  get RSS parsed object
		$options = array();
        $options['rssUrl']		= $params->get('feed');

		$options['cache_time']	= '60';

		$rssDoc = JFactory::getXMLparser('RSS', $options);
        if (!$rssDoc){$this->setRedirect( 'index.php?option=com_velnotice&view=vel', JText::_('VEL_FEED_PROBLEM') );}
        else{
        $vel = new stdclass();
   
        $vel->channel['title']			= $rssDoc->get_title();
		$vel->channel['link']			= $rssDoc->get_link();
		$vel->channel['description']	= $rssDoc->get_description();
		$vel->channel['language']		= $rssDoc->get_language();

		// channel image if exists
		$vel->image['url']		= $rssDoc->get_image_url();
		$vel->image['title']	= $rssDoc->get_image_title();
		$vel->image['link']	    = $rssDoc->get_image_link();
		$vel->image['height']	= $rssDoc->get_image_height();
		$vel->image['width']	= $rssDoc->get_image_width();

		// items
		$vel->items = $rssDoc->get_items();

		// feed elements
	
        $vel->items = array_slice($vel->items, 0);
        
        $db = JFactory::getDBO();
        $query = 'SELECT * FROM #__extensions';
        $db->setQuery($query);
        $extensions = $db->loadObjectList();
     //  print_r($extensions);
       echo '<br /><br />';
     //  print_r ($vel->items);
       //Create an array of the items on the ignore list
       $query2 = 'SELECT published, extension_number FROM #__velnotice_ignorelist WHERE published = 1';
       $db->setQuery($query2);
       $db->query();
       $ignorelist = $db->loadObjectList();
      
        $vellist = array();
        
        foreach($extensions as &$extension) 
        {
    	   
           foreach ($vel->items as $feed)
           {
                set_time_limit(60);
                $link = $feed->get_link();
                $description = $feed->get_description();
                $description = str_replace('&apos;', "'", $description);
                $description = '<table><tr>'.$description.'</tr></table>';
                $text = $feed->get_title();
                $hit1 = substr_count($text,$extension->name);
                $hit2 = substr_count(strtolower($text),strtolower($extension->name));
                $usedescription = JRequest::getInt('usedesc',0,'get');
                $hit3 = substr_count(strtolower($description),strtolower($extension->name));
                
                if ($hit3)
                    {
                        $descriptionhit[] = 1; 
                    }
                $continue = 0;
                if ($hit1 || $hit2 ){$continue = 1;}
                if ($usedescription == 1 && $hit3){$continue = 1;}
                if ($continue)
                { //print_r($extensionnumber);
                    //Check for version match
                     if ($extension->manifest_cache)
                        {
                          $version = substr_count($extension->manifest_cache,'"version"');
                          if ($version)
                              {
                                $manifestvariable = json_decode($extension->manifest_cache);
                                if (is_object($manifestvariable))
                                    {
                                        $versiontext2 = $manifestvariable->version; 
                                        $versiontext = preg_replace ('/[^\d\s]/', '', $manifestvariable->version);
                                        
                                    }
                                else
                                
                                {
                                    $versionfind = strpos($extension->manifest_cache,'"version"');
                                    $versionstart = $versionfind + 15;
                                    $versionend = strpos($extension->manifest_cache,'"',$versionstart) ;
                                    $versionlength = $versionend - $versionstart;
                                    $versiontext = substr($extension->manifest_cache,$versionstart,$versionlength);
                                    $versiontext2 = $versiontext;
                                    $versiontext = preg_replace ('/[^\d\s]/', '', $versiontext);
                                    
                                    
                                }
                            }
                        }
                    $reportedversion = @substr_count(strtolower($description),$version);
                    $reportedversion = preg_replace ('/[^\d\s]/', '', $reportedversion);
                    if ($reportedversion == $versiontext){$image = 'red';} else {$image = 'yellow';}
                    $vellist2 = array('title'=>$extension->name, 'description'=>$description, 'image'=>$image, 'link'=>$link, 'version'=>$versiontext2, 'extension_number'=>$extension->extension_id);
                    $vellist[] = $vellist2;
                }
             }
        }
        //Go through the array and remove items from the ignore list
        foreach ($vellist as $i => $velitem)
        { //print_r($velitem['extension_number']);
            if ($ignorelist)
            { 
                foreach ($ignorelist AS $key => $extensionignore)
                     { 
                        foreach ($extensionignore as $key =>$value)
                        { 
                         if ($velitem['extension_number'] == $value)
                             {
                                
                                unset($vellist[$i]);
                             }
                         }
                     }
            }
        }
        $velarray = JRequest::setVar('velarray',$vellist,'get');
        $done = JRequest::setVar('veldone',1,'get');
        if ($ignorelist){$ignoretrue = JRequest::setVar('ignoretrue',1,'get');}
        if (in_array('1',$descriptionhit)) {JRequest::setVar('descriptiontrue',1,'get');}
      } // end of else - if the feed is not false
    }	
}
