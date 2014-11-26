<?php

/**
*  version 1.1
 * @package		VEL Notice
 * @copyright	Copyright (C) 2011 Tom Fuller, Inc. All rights reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Technical Support: http://joomlacode.org/gf/project/extensionsecure/
 */

defined('_JEXEC') or die('Restricted access');

/* Import library dependencies */
jimport('joomla.event.plugin');

class plgSystemVel extends JPlugin {


public function __construct(& $subject, $config)
        {
 
                parent::__construct($subject, $config);
                $this->loadLanguage();
                
                $lang = JFactory::getLanguage();
                $lang->load('plg_system_vel', JPATH_ADMINISTRATOR);

 
        }
    function onAfterInitialise() {
		
		$plugin =& JPluginHelper::getPlugin( 'system', 'vel' );
		$newurl = $this->params->get('feedurl');
        $params = $this->params;
      
        $check = $this->checktime();
        
        if ($check)
        {
            //perform the vel and email and update time
            //If we have run the velcheck the last thing we do is reset the time we did it to current
            $dovel = $this->checkvel($params);
            $updatetime = $this->updatetime();
            $email = $this->velemail($params, $dovel);
        }
        
    }
    
    function checktime()
    {
        $now = time();
        $db = JFactory::getDBO();
        $db->setQuery('SELECT `timeset` FROM `#__vel_plugin` WHERE `id` = 1', 0, 1);
        $result = $db->loadObject();
        $lasttime = $result->timeset;
        $frequency = $this->params->get('frequency','86400');
        $checkit = $now - $lasttime;
         if ($checkit > $frequency) {return true;}
         else {return false;}
    }
    
    function updatetime()
    {
        $time = time();
        $db = JFactory::getDBO();
        $db->setQuery('UPDATE `#__vel_plugin` SET `timeset` = '.$time.' WHERE `id` = 1');
        $db->query();
        $updateresult = $db->getErrorMsg();
        return $updateresult;
        if ($updateresult > 0) {return true;} else {return false;}
    }
    
    function checkvel($params)
    {
        $extensions = $this->getExtensions();
        $velinfo = $this->getVelInfo($params, $extensions);
        return $velinfo;
    }
    
    function velemail($params, $dovel)
    {
            $livesite = JURI::root();
            $config = JFactory::getConfig();
            $mailfrom   = $config->getValue('config.mailfrom');
            $fromname   = $config->getValue('config.fromname');
    		jimport('joomla.filesystem.file');
    		
            
            $mail = JFactory::getMailer();
    	 	$mail->IsHTML(true);
            jimport('joomla.utilities.date');
    		$year = '('.date('Y').')';
    		$date = date('r');
    	 	$Body   = JText::_('VEL_REPORT'). ' '.$fromname.'</strong><br />' ;
            $Body .= JText::_('VEL_PROCESS_RUN_AT').': '.$date.'<br />';
            $Body2 = '';
            if ($dovel)
            {
                foreach ($dovel as $velitem)
                {
                    $Body2 .= $velitem['title'].' - '.$velitem['description'].' - '.$velitem['link'];
                }
           // print_r ($Body2);
            //$Body2 = $dovel;
            }
    		if (!$dovel)
            {
    		  $Body2 = JText::_('VEL_NO_MATCHES');
              if ($params->get('noticetype')< 2)
              {return;}
            }
            else
            {
                $Body2a = '<strong><br />'.JText::_('VEL_MATCHES').'</strong><br /><br />';
            }
            $footer = '<br /><hl><br /><a href="http://feeds.joomla.org/JoomlaSecurityVulnerableExtensions?format=xml" target="_blank">'.JText::_('VEL_VIEW_FEED').'</a>';
    		$Body3 = $Body.'<br /.>'.$Body2a.'<br />'.$Body2.'<br /.>'.$footer;
    		$Subject       = JText::_('VEL_REPORT');
    		$FromName       = $fromname ;
    		
    		$recipients = explode(",",$params->get('recipients'));
            foreach ($recipients AS $recipient)
            {
                $mail->addRecipient($recipient);
        		$mail->setSubject($Subject.' '.$livesite);
        		$mail->setBody($Body3);
                
                $mail->Send();
            }
    }
    
    public function getExtensions()
     {
        
        $db = JFactory::getDbo();
    	$query = $db->getQuery(true);
    	$query->select('*');
    	$query->from('#__extensions');  
        $db->setQuery($query);
        $extensions = $db->loadObjectList();       
        
           return $extensions;
     }
     
    public function getVelInfo($params, $extensions)
    {
        //  get RSS parsed object
		$options = array();
        if ($params->get('urltype') > 1) 
        {$options['rssUrl']		= $params->get('feedurl','http://feeds.joomla.org/JoomlaSecurityVulnerableExtensions?format=xml');}
        else
		{$options['rssUrl']		= 'http://feeds.joomla.org/JoomlaSecurityVulnerableExtensions?format=xml';}
		$options['cache_time']	= '60';

		$rssDoc = JFactory::getXMLparser('RSS', $options); if (!$rssDoc){return false;}
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
        
        $vellist = array();
        $velnoticecomponent = 0;
        $ignorelist = $this->getIgnorelist();
        $usedescription = $params->get('usedescription','0');
        foreach($extensions as &$extension) 
        {
    		//Check to see if com_velnotice is installed
            if ($extension->name == 'com_velnotice') {$velnoticecomponent = 1;}
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
                $hit3 = substr_count(strtolower($description),strtolower($extension->name));
                
               // $ignorelistflag = 0;
               $continue = 0;
                if ($hit1 || $hit2 ){$continue = 1;}
                if ($usedescription == 1 && $hit3){$continue = 1;}
                if ($continue)
                {
                    
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
                        $vellist2 = array('title'=>$extension->name, 'description'=>$description, 'link'=>$link, 'version'=>$versiontext2, 'extension_number'=>$extension->extension_id);
                        $vellist[] = $vellist2;
                        
                      //  $vellist[] = $extension->name.' - '.$versiontext2.' - '.$description;
                      if (!empty($ignorelist))
                      {
                       foreach ($vellist as $i => $velitem)
                        { //print_r($velitem['extension_number']);
                             
                            foreach ($ignorelist AS $key => $extensionignore)
                                 { 
                                    foreach ($extensionignore as $key =>$value)
                                    { 
                                     if ($velitem['extension_number'] == $value && $params->get('useignorelist'))
                                         {
                                            unset($vellist[$i]);
                                         }
                                     }
                                 }
                            
                        }
                     }
                    
                }
             }
        }
        return $vellist;

    }
    
    public function getIgnorelist()
    {
        //Create an array of the items on the ignore list
       $db = JFactory::getDBO();
       $query = 'SELECT published, extension_number FROM #__velnotice_ignorelist WHERE published = 1';
       $db->setQuery($query);
       $db->query();
       $ignorelist = $db->loadObjectList();
       return $ignorelist;
    }
}

?>