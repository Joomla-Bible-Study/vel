--
-- Table structure for table `jos_velnotice`
--

CREATE TABLE IF NOT EXISTS `#__velnotice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `params` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `jos_velnotice`
--

INSERT INTO `#__velnotice` (`id`, `params`) VALUES
(1, 'feed=http://feeds.joomla.org/JoomlaSecurityVulnerableExtensions?format=xml');

CREATE TABLE IF NOT EXISTS `#__velnotice_ignorelist` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `extension_number` int(11) NULL,
    `published` int(2) NOT NULL DEFAULT 0,
    `asset_id` int(11) NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;
