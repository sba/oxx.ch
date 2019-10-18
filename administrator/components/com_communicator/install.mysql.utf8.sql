CREATE TABLE IF NOT EXISTS `#__communicator` (
	  `id` int(11) NOT NULL auto_increment,
	  `subject` varchar(50) NOT NULL default '',
	  `headers` text NOT NULL,
	  `message` text NOT NULL,
	  `html_message` text NOT NULL,
	  `published` tinyint(1) NOT NULL default '0',
	  `checked_out` int(11) NOT NULL default '0',
	  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
	  `publish_up` datetime NOT NULL default '0000-00-00 00:00:00',
	  `publish_down` datetime NOT NULL default '0000-00-00 00:00:00',
	  `created` datetime NOT NULL default '0000-00-00 00:00:00',
	  `send` datetime NOT NULL default '0000-00-00 00:00:00',
	  `hits` int(11) NOT NULL default '0',
	  `access` int(11) unsigned NOT NULL default '0',
	  PRIMARY KEY  (`id`)
	) ENGINE=MyISAM CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' COMMENT='Used to store all newsletters for Communicator.';

REPLACE INTO `#__communicator` VALUES (1, 'Sample Newsletter', '', 'Hello [NAME],\r\n\r\nthis is a nice sample Newsletter *Text Edition* from your guys at the newsletter team!.\r\n\r\nAfter having set up this newsletter component you can easily delete or modify this Newsletter.\r\n\r\nNow please enjoy Communicator!\r\n\r\nRegards, XXX\r\n','\r\n<p style="font-family: verdana,arial,helvetica,sans-serif;">Hello [NAME],<img vspace="5" hspace="3" border="0" align="right" src="components/com_communicator/communicator.png" /></p><p style="font-family: verdana,arial,helvetica,sans-serif;">this is a nice sample Newsletter <span style="font-weight: bold;">with Graphics</span> from your guys at the newsletter team!.</p><p style="font-weight: bold;"><br /></p><p style="font-family: verdana,arial,helvetica,sans-serif;">After having set up this newsletter component you can easily delete or modify this Newsletter.<br /></p><p style="font-weight: bold;"><span style="font-weight: normal; font-family: verdana,arial,helvetica,sans-serif;">Now please enjoy Communicator!</span><br /></p><p style="font-weight: bold;">Regards, XXX<br /></p>', 1, 0, '0000-00-00 00:00:00', '2005-02-04 00:00:00', '2007-02-23 00:00:00', '2005-02-01 15:26:02', '2005-02-07 15:42:13', 6, 0);

CREATE TABLE IF NOT EXISTS `#__communicator_subscribers` (
          `subscriber_id` int(11) NOT NULL auto_increment,
          `user_id` int(11) NOT NULL default '0',
          `subscriber_name` varchar(64) NOT NULL default '',
          `subscriber_email` varchar(64) NOT NULL default '',
          `confirmed` tinyint(1) NOT NULL default '0',
          `subscribe_date` datetime NOT NULL default '0000-00-00 00:00:00',
          PRIMARY KEY  (`subscriber_id`),
          UNIQUE KEY `subscriber_email` (`subscriber_email`)
        ) ENGINE=MyISAM CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' COMMENT='Subscribers for Communicator are stored here.';
