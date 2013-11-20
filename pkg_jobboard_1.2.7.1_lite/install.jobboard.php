<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');   

jimport('joomla.installer.installer');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

   // Load Jobboard language file
   $lang = &JFactory::getLanguage();
   $lang->load('com_jobboard');
   $src = $this->parent->getPath('source');
   $db = & JFactory::getDBO();

   $status = new JObject();
   $status->modules = array();
   $status->plugins = array();

   // Database upgrade
   $db = &JFactory::getDBO();

	/*always try to add the version column */
   tblAddColumn('#__jobboard_config', 'release_ver', 'TEXT NOT NULL');
   $curr_version = tblCheckColumnValue('#__jobboard_config', 'release_ver', ' WHERE id=1');
   if($curr_version <> '1.2.5' && $curr_version <> '1.2.6' && $curr_version <> '1.2.7' && $curr_version <> '1.2.7.1'){
      set_time_limit(0);

	  //update applicants table
 	  tblAddColumn('#__jobboard_applicants', 'filetype', ' varchar(24) DEFAULT NULL');

	  //update config table
 	  tblAddColumn('#__jobboard_config', 'max_filesize',' int(11) DEFAULT 1');
      tblAddColumn('#__jobboard_config', 'max_files',' int(11) DEFAULT 10');
      tblAddColumn('#__jobboard_config', 'max_quals',' int(11) DEFAULT 5');
      tblAddColumn('#__jobboard_config', 'max_employers',' int(11) DEFAULT 10');
      tblAddColumn('#__jobboard_config', 'max_skills',' int(11) DEFAULT 20');
      tblAddColumn('#__jobboard_config', 'default_upl_folder',' varchar(256) DEFAULT NULL');
      tblAddColumn('#__jobboard_config', 'default_col_layout',' tinyint(1) DEFAULT 2');
      tblAddColumn('#__jobboard_config', 'allow_once_off_applications',' tinyint(1) DEFAULT 0');
      tblAddColumn('#__jobboard_config', 'default_list_layout',' varchar(16) DEFAULT "list"');
      tblAddColumn('#__jobboard_config', 'distance_unit',' tinyint(1) DEFAULT 0');
      tblAddColumn('#__jobboard_config', 'default_distance'," enum('10','15','20','30','50','70','100','300','500','1000','5000','10000') NOT NULL DEFAULT 50");
      tblAddColumn('#__jobboard_config', 'enable_post_maps',' tinyint(1) DEFAULT 1');
      tblAddColumn('#__jobboard_config', 'home_intro_title',' text');
      tblAddColumn('#__jobboard_config', 'home_intro',' text');
      tblAddColumn('#__jobboard_config', 'home_jobs_limit',' int(11) NOT NULL DEFAULT 5');
      tblAddColumn('#__jobboard_config', 'secure_login',' tinyint(1) NOT NULL DEFAULT 0');
      tblAddColumn('#__jobboard_config', 'captcha_login',' tinyint(1) DEFAULT 0');
      tblAddColumn('#__jobboard_config', 'captcha_reg',' tinyint(1) DEFAULT 1');
      tblAddColumn('#__jobboard_config', 'captcha_public',' tinyint(1) DEFAULT 1');
      tblAddColumn('#__jobboard_config', 'allow_registration',' tinyint(1) DEFAULT 1');
      tblAddColumn('#__jobboard_config', 'show_rss',' tinyint(1) DEFAULT 1');
      tblAddColumn('#__jobboard_config', 'default_user_grp',' int(11) NOT NULL DEFAULT 5');
      tblAddColumn('#__jobboard_config', 'default_empl_grp',' int(11) NOT NULL DEFAULT 2');
      tblAddColumn('#__jobboard_config', 'maint_tasks_on',' tinyint(1) DEFAULT 1');
      tblAddColumn('#__jobboard_config', 'maint_tasks_int_type',' tinyint(1) DEFAULT 2');
      tblAddColumn('#__jobboard_config', 'maint_tasks_int',' int(11) DEFAULT 1');
      tblAddColumn('#__jobboard_config', 'last_maint_check',' bigint(20) DEFAULT 0');
      tblAddColumn('#__jobboard_config', 'last_maint_run',' bigint(20) DEFAULT 0');
      tblAddColumn('#__jobboard_config', 'sched_disable_exp',' tinyint(1) DEFAULT 1');
      tblAddColumn('#__jobboard_config', 'sched_expire_feat',' tinyint(1) DEFAULT 1');
      tblAddColumn('#__jobboard_config', 'sched_backup_data',' tinyint(1) DEFAULT 0');
      tblAddColumn('#__jobboard_config', 'email_task_results',' tinyint(1) DEFAULT 0');
      tblAddColumn('#__jobboard_config', 'empl_default_feature',' tinyint(1) DEFAULT 0');
      tblAddColumn('#__jobboard_config', 'feature_length',' int(11) DEFAULT 30');
      tblAddColumn('#__jobboard_config', 'allow_linkedin_imports',' tinyint(1) DEFAULT 0');
      tblAddColumn('#__jobboard_config', 'linkedin_key',' varchar(32) DEFAULT NULL');
      tblAddColumn('#__jobboard_config', 'linkedin_secret',' varchar(32) DEFAULT NULL');
      tblAddColumn('#__jobboard_config', 'user_show_applstatus',' tinyint(1) DEFAULT 0');
      tblAddColumn('#__jobboard_config', 'admin_show_backlink',' tinyint(1) DEFAULT 1');
      tblAddColumn('#__jobboard_config', 'user_show_backlink',' tinyint(1) DEFAULT 1');

      $query = "INSERT ignore into `#__jobboard_config`(`id`,`organisation`,`from_mail`,`reply_to`,`default_dept`,`default_country`,`default_city`,`default_jobtype`,`default_career`,`default_edu`,`default_category`,`default_post_range`,`allow_unsolicited`,`allow_applications`,`dept_notify_admin`,`dept_notify_contact`,`show_social`,`show_viewcount`,`show_applcount`,`email_cvattach`,`show_job_summary`,`send_tofriend`,`appl_job_summary`,`sharing_job_summary`,`short_date_format`,`date_separator`,`long_date_format`,`jobtype_coloring`,`use_location`,`social_icon_style`,`release_ver`,`max_filesize`,`max_files`,`max_quals`,`max_employers`,`max_skills`,`default_upl_folder`,`default_col_layout`,`allow_once_off_applications`,`default_list_layout`,`distance_unit`,`default_distance`,`enable_post_maps`,`home_intro_title`,`home_intro`,`home_jobs_limit`,`secure_login`,`captcha_login`,`captcha_reg`,`captcha_public`,`allow_registration`,`show_rss`,`default_user_grp`,`default_empl_grp`,`maint_tasks_on`,`maint_tasks_int_type`,`maint_tasks_int`,`last_maint_check`,`last_maint_run`,`sched_disable_exp`,`sched_expire_feat`,`sched_backup_data`,`email_task_results`,`empl_default_feature`,`feature_length`,`allow_linkedin_imports`,`linkedin_key`,`linkedin_secret`,`user_show_applstatus`) values (1,'Some Organization','someone@somewhere.com','no-reply@somewhere.com',1,220,'Johannesburg',1,3,3,10,'30',1,1,1,1,1,1,1,0,1,1,1,1,0,0,0,1,1,1,'1.2.5',1,10,5,10,20,NULL,2,1,'0',0,'50',1,'About **','<p>This is sample text that can be used as an introduction to your job board</p>\r\n<p> </p>\r\n<p>** Please change this in your backend settings.</p>',5,0,0,1,1,1,1,5,2,1,2,1,1330869742,0,1,1,0,0,0,30,0,'','',0);";
      $db->setQuery($query);
      $db->Query();

	  //update countries table
 	  tblAddColumn('#__jobboard_countries', 'short_code', ' varchar(2) DEFAULT NULL');
      $query = "INSERT ignore  into `#__jobboard_countries`(`country_id`,`country_name`,`short_code`,`dial_prefix`,`country_region`) values (1,'Afghanistan','AF',93,'Asia'),(2,'Albania','AL',355,'Europe'),(3,'Algeria','DZ',213,'Africa'),(4,'American Samoa','AS',1684,'Oceania'),(5,'Andorra','AD',376,'Europe'),(6,'Angola','AO',244,'Africa'),(7,'Anguilla','AI',1264,'Central America/Caribbean'),(8,'Antarctica','AQ',0,'Antarctic Region'),(9,'Antigua and Barbuda','AG',1268,'Central America/Caribbean'),(10,'Arctic Ocean','',0,'Arctic Region'),(11,'Argentina','AR',54,'South America'),(12,'Armenia','AM',374,'Commonwealth of Independent States - European States'),(13,'Aruba','AW',297,'Central America/Caribbean'),(14,'Ashmore and Cartier Islands','AU',0,'Southeast Asia'),(15,'Atlantic Ocean','',0,'World'),(16,'Australia','AU',61,'Oceania'),(17,'Austria','AT',43,'Europe'),(18,'Azerbaijan','AZ',994,'Commonwealth of Independent States - European States'),(19,'The Bahamas','BS',1242,'Central America/Caribbean'),(20,'Bahrain','BH',973,'Middle East'),(21,'Baker Island','',0,'Oceania'),(22,'Bangladesh','BD',880,'Asia'),(23,'Barbados','BB',1246,'Central America/Caribbean'),(24,'Bassas da India','',0,'Africa'),(25,'Belarus','BY',375,'Commonwealth of Independent States - European States'),(26,'Belgium','BE',32,'Europe'),(27,'Belize','BZ',501,'Central America/Caribbean'),(28,'Benin','BJ',229,'Africa'),(29,'Bermuda','BM',1441,'North America'),(30,'Bhutan','BT',975,'Asia'),(31,'Bolivia','BO',591,'South America'),(32,'Bosnia and Herzegovina','BA',387,'Europe'),(33,'Botswana','BW',267,'Africa'),(34,'Bouvet Island','BV',0,'Antarctic Region'),(35,'Brazil','BR',55,'South America'),(36,'British Indian Ocean Territory','IO',0,'World'),(37,'British Virgin Islands','',1284,'Central America/Caribbean'),(38,'Brunei','BN',673,'Southeast Asia'),(39,'Bulgaria','BG',359,'Europe'),(40,'Burkina Faso','BF',226,'Africa'),(41,'Burma','',0,'Southeast Asia'),(42,'Burundi','BI',257,'Africa'),(43,'Cambodia','KH',855,'Southeast Asia'),(44,'Cameroon','CM',237,'Africa'),(45,'Canada','CA',1,'North America'),(46,'Cape Verde','CV',238,'World'),(47,'Cayman Islands','KY',1345,'Central America/Caribbean'),(48,'Central African Republic','CF',236,'Africa'),(49,'Chad','TD',235,'Africa'),(50,'Chile','CL',56,'South America'),(51,'China','CN',86,'Asia'),(52,'Christmas Island','CX',0,'Southeast Asia'),(53,'Clipperton Island','',0,'World'),(54,'Cocos (Keeling) Islands','CC',0,'Southeast Asia'),(55,'Colombia','CO',57,'South America'),(56,'Comoros','KM',269,'Africa'),(57,'Congo','CG',242,'Africa'),(58,'Cook Islands','CK',682,'Oceania'),(59,'Coral Sea Islands','',0,'Oceania'),(60,'Costa Rica','CR',506,'Central America/Caribbean'),(61,'Cote d\'Ivoire','CI',225,'Africa'),(62,'Croatia','HR',385,'Europe'),(63,'Cuba','CU',0,'Central America/Caribbean'),(64,'Cyprus','CY',357,'Middle East'),(65,'Czech Republic','CZ',420,'Europe'),(66,'Denmark','DK',45,'Europe'),(67,'Djibouti','DJ',253,'Africa'),(68,'Dominica','DM',1767,'Central America/Caribbean'),(69,'Dominican Republic','DO',1,'Central America/Caribbean'),(70,'Ecuador','EC',593,'South America'),(71,'Egypt','EG',20,'Africa'),(72,'El Salvador','SV',503,'Central America/Caribbean'),(73,'Equatorial Guinea','GQ',240,'Africa'),(74,'Eritrea','ER',0,'Africa'),(75,'Estonia','EE',372,'Europe'),(76,'Ethiopia','ET',251,'Africa'),(77,'Europa Island','',0,'Africa'),(78,'Falkland Islands (Islas Malvinas)','FK',500,'South America'),(79,'Faroe Islands','',298,'Europe'),(80,'Fiji','FJ',679,'Oceania'),(81,'Finland','FI',358,'Europe'),(82,'France','FR',33,'Europe'),(83,'French Guiana','GF',594,'South America'),(84,'French Polynesia','PF',689,'Oceania'),(85,'French Southern and Antarctic Lands','TF',0,'Antarctic Region'),(86,'Gabon','GA',241,'Africa'),(87,'The Gambia','GM',220,'Africa'),(88,'Gaza Strip','PS',970,'Middle East'),(89,'Georgia','GE',995,'Middle East'),(90,'Germany','DE',49,'Europe'),(91,'Ghana','GH',233,'Africa'),(92,'Gibraltar','GI',350,'Europe'),(93,'Glorioso Islands','',0,'Africa'),(94,'Greece','GR',30,'Europe'),(95,'Greenland','GL',299,'Arctic Region'),(96,'Grenada','GD',1473,'Central America/Caribbean'),(97,'Guadeloupe','GP',590,'Central America/Caribbean'),(98,'Guam','GU',1671,'Oceania'),(99,'Guatemala','GT',502,'Central America/Caribbean'),(100,'Guernsey','',44,'Europe'),(101,'Guinea','GN',224,'Africa'),(102,'Guinea-Bissau','GW',0,'Africa'),(103,'Guyana','GY',592,'South America'),(104,'Haiti','HT',509,'Central America/Caribbean'),(105,'Heard and McDonald Islands','HM',0,'Antarctic Region'),(106,'Holy See (Vatican City)','VA',0,'Europe'),(107,'Honduras','HN',504,'Central America/Caribbean'),(108,'Hong Kong','HK',852,'Southeast Asia'),(109,'Howland Island','',0,'Oceania'),(110,'Hungary','HU',36,'Europe'),(111,'Iceland','IS',354,'Arctic Region'),(112,'India','IN',91,'Asia'),(113,'Indian Ocean','',0,'World'),(114,'Indonesia','ID',62,'Southeast Asia'),(115,'Iran','IR',0,'Middle East'),(116,'Iraq','IQ',964,'Middle East'),(117,'Ireland','IE',353,'Europe'),(118,'Israel','IL',972,'Middle East'),(119,'Italy','IT',39,'Europe'),(120,'Jamaica','JM',1876,'Central America/Caribbean'),(121,'Jan Mayen','',0,'Arctic Region'),(122,'Japan','JP',81,'Asia'),(123,'Jarvis Island','',0,'Oceania'),(124,'Jersey','',44,'Europe'),(125,'Johnston Atoll','',0,'Oceania'),(126,'Jordan','JO',962,'Middle East'),(127,'Juan de Nova Island','',0,'Africa'),(128,'Kazakhstan','KZ',7,'Commonwealth of Independent States - Central Asian States'),(129,'Kenya','KE',254,'Africa'),(130,'Kingman Reef','',0,'Oceania'),(131,'Kiribati','KI',0,'Oceania'),(132,'Korea,  North','KP',0,'Asia'),(133,'Korea,  South','KR',82,'Asia'),(134,'Kuwait','KW',965,'Middle East'),(135,'Kyrgyzstan','KG',996,'Commonwealth of Independent States - Central Asian States'),(136,'Laos','LA',856,'Southeast Asia'),(137,'Latvia','LV',371,'Europe'),(138,'Lebanon','LB',961,'Middle East'),(139,'Lesotho','LS',266,'Africa'),(140,'Liberia','LR',231,'Africa'),(141,'Libya','LY',218,'Africa'),(142,'Liechtenstein','LI',423,'Europe'),(143,'Lithuania','LT',370,'Europe'),(144,'Luxembourg','LU',352,'Europe'),(145,'Macau','MO',852,'Southeast Asia'),(146,'Macedonia','MK',389,'Europe'),(147,'Madagascar','MG',261,'Africa'),(148,'Malawi','MW',265,'Africa'),(149,'Malaysia','MY',60,'Southeast Asia'),(150,'Maldives','MV',960,'Asia'),(151,'Mali','ML',223,'Africa'),(152,'Malta','MT',356,'Europe'),(153,'Man,  Isle of','',44,'Europe'),(154,'Marshall Islands','MH',0,'Oceania'),(155,'Martinique','MQ',596,'Central America/Caribbean'),(156,'Mauritania','MR',222,'Africa'),(157,'Mauritius','MU',230,'World'),(158,'Mayotte','YT',269,'Africa'),(159,'Mexico','MX',52,'North America'),(160,'Micronesia, Federated States of','FM',0,'Oceania'),(161,'Midway Islands','',0,'Oceania'),(162,'Moldova','MD',373,'Commonwealth of Independent States - European States'),(163,'Monaco','MC',377,'Europe'),(164,'Mongolia','MN',976,'Asia'),(165,'Montserrat','MS',1664,'Central America/Caribbean'),(166,'Morocco','MA',212,'Africa'),(167,'Mozambique','MZ',258,'Africa'),(168,'Namibia','NA',264,'Africa'),(169,'Nauru','NR',674,'Oceania'),(170,'Navassa Island','',0,'Central America/Caribbean'),(171,'Nepal','NP',977,'Asia'),(172,'Netherlands','NL',599,'Europe'),(173,'Netherlands Antilles','AN',0,'Central America/Caribbean'),(174,'New Caledonia','NC',687,'Oceania'),(175,'New Zealand','NZ',64,'Oceania'),(176,'Nicaragua','NI',505,'Central America/Caribbean'),(177,'Niger','NE',227,'Africa'),(178,'Nigeria','NG',234,'Africa'),(179,'Niue','NU',0,'Oceania'),(180,'Norfolk Island','NF',0,'Oceania'),(181,'Northern Mariana Islands','MP',0,'Oceania'),(182,'Norway','NO',47,'Europe'),(183,'Oman','OM',968,'Middle East'),(184,'Pacific Ocean','',0,'World'),(185,'Pakistan','PK',92,'Asia'),(186,'Palau','PW',0,'Oceania'),(187,'Palmyra Atoll','',0,'Oceania'),(188,'Panama','PA',507,'Central America/Caribbean'),(189,'Papua New Guinea','PG',675,'Oceania'),(190,'Paracel Islands','',0,'Southeast Asia'),(191,'Paraguay','PY',595,'South America'),(192,'Peru','PE',51,'South America'),(193,'Philippines','PH',63,'Southeast Asia'),(194,'Pitcairn Islands','PN',0,'Oceania'),(195,'Poland','PL',48,'Europe'),(196,'Portugal','PT',351,'Europe'),(197,'Puerto Rico','PR',1,'Central America/Caribbean'),(198,'Qatar','QA',974,'Middle East'),(199,'Reunion','RE',262,'World'),(200,'Romania','RO',40,'Europe'),(201,'Russia','RU',7,'Asia'),(202,'Rwanda','RW',250,'Africa'),(203,'Saint Helena','',0,'Africa'),(204,'Saint Kitts and Nevis','KN',0,'Central America/Caribbean'),(205,'Saint Lucia','LC',0,'Central America/Caribbean'),(206,'Saint Pierre and Miquelon','',0,'North America'),(207,'Saint Vincent and the Grenadines','VC',0,'Central America/Caribbean'),(208,'San Marino','SM',378,'Europe'),(209,'Sao Tome and Principe','ST',0,'Africa'),(210,'Saudi Arabia','SA',966,'Middle East'),(211,'Senegal','SN',221,'Africa'),(212,'Serbia and Montenegro','',381,'Europe'),(213,'Seychelles','SC',248,'Africa'),(214,'Sierra Leone','SL',232,'Africa'),(215,'Singapore','SG',65,'Southeast Asia'),(216,'Slovakia','SK',421,'Europe'),(217,'Slovenia','SI',386,'Europe'),(218,'Solomon Islands','SB',677,'Oceania'),(219,'Somalia','SO',252,'Africa'),(220,'South Africa','ZA',27,'Africa'),(221,'South Georgia and the South Sandwich Islands','GS',0,'Antarctic Region'),(222,'Spain','ES',34,'Europe'),(223,'Spratly Islands','',0,'Southeast Asia'),(224,'Sri Lanka','LK',94,'Asia'),(225,'Sudan','SD',0,'Africa'),(226,'Suriname','SR',597,'South America'),(227,'Svalbard and Jan Mayen Islands','SJ',0,'Arctic Region'),(228,'Swaziland','SZ',268,'Africa'),(229,'Sweden','SE',46,'Europe'),(230,'Switzerland','CH',41,'Europe'),(231,'Syrian Arab Republic','SY',0,'Middle East'),(232,'Taiwan','TW',886,'Southeast Asia'),(233,'Tajikistan','TJ',992,'Commonwealth of Independent States - Central Asian States'),(234,'Tanzania','TZ',255,'Africa'),(235,'Thailand','TH',66,'Southeast Asia'),(236,'Togo','TG',228,'Africa'),(237,'Tokelau','TK',0,'Oceania'),(238,'Tonga','TO',676,'Oceania'),(239,'Trinidad and Tobago','TT',1868,'Central America/Caribbean'),(240,'Tromelin Island','',0,'Africa'),(241,'Tunisia','TN',216,'Africa'),(242,'Turkey','TR',90,'Middle East'),(243,'Turkmenistan','TM',993,'Commonwealth of Independent States - Central Asian States'),(244,'Turks and Caicos Islands','TC',1649,'Central America/Caribbean'),(245,'Tuvalu','TV',0,'Oceania'),(246,'Uganda','UG',256,'Africa'),(247,'Ukraine','UA',380,'Commonwealth of Independent States - European States'),(248,'United Arab Emirates','AE',971,'Middle             East'),(249,'United Kingdom','GB',44,'Europe'),(250,'United States','US',1,'North America'),(251,'Uruguay','UY',598,'South America'),(252,'Uzbekistan','UZ',998,'Commonwealth of Independent States - Central Asian States'),(253,'Vanuatu','VU',678,'Oceania'),(254,'Venezuela','VE',58,'South America'),(255,'Vietnam','VN',84,'Southeast Asia'),(256,'Virgin Islands','VG',0,'Central America/Caribbean'),(257,'Wake Island','',0,'Oceania'),(258,'Wallis and Futuna','WF',0,'Oceania'),(259,'West Bank','',0,'Middle East'),(260,'Western Sahara','EH',0,'Africa'),(261,'Western Samoa','WS',0,'Oceania'),(262,'Yemen','YE',987,'Middle East'),(263,'Zaire','',0,'Africa'),(264,'Zambia','ZM',260,'Africa'),(265,'Zimbabwe','ZU',263,'Africa'),(266,'COM_JOBBOARD_DB_ANYWHERE_CNAME','',0,'COM_JOBBOARD_DB_ANYWHERE_REGION'),(267,'Yugoslavia','YU',0,'Yugoslavia');";
      $db->setQuery($query);
      $db->Query();

	  //update departments table
      $deflt_owner_id = version_compare(JVERSION, '1.6.0', 'ge')? 42 : 62;
 	  tblAddColumn('#__jobboard_departments', 'owner_id', ' int(11) DEFAULT '.$deflt_owner_id);
      tblAddColumn('#__jobboard_departments', 'user_groups',' varchar(512) DEFAULT 0');
      $query = "INSERT ignore  into `#__jobboard_departments`(`id`,`owner_id`,`user_groups`,`name`,`contact_name`,`contact_email`,`notify`,`notify_admin`,`acceptance_notify`,`rejection_notify`) values (1,42,'0','default','admin','somedep@somewhere.com',1,1,1,1);";
      $db->setQuery($query);
      $db->Query();


	  //update jobs table
 	  tblAddColumn('#__jobboard_jobs', 'default_empl_grp', ' int(11) DEFAULT 2');
 	  tblAddColumn('#__jobboard_jobs', 'default_user_grp', ' int(11) DEFAULT 5');
 	  tblAddColumn('#__jobboard_jobs', 'featured', ' tinyint(1) DEFAULT 0');
 	  tblAddColumn('#__jobboard_jobs', 'geo_latitude', ' longtext DEFAULT NULL');
 	  tblAddColumn('#__jobboard_jobs', 'geo_longitude', ' longtext DEFAULT NULL');
 	  tblAddColumn('#__jobboard_jobs', 'geo_state_province', ' longtext DEFAULT NULL');
 	  tblAddColumn('#__jobboard_jobs', 'posted_by', ' int(11) DEFAULT 0');
 	  tblAddColumn('#__jobboard_jobs', 'questionnaire_id', ' int(11) DEFAULT 0');
      tblAddColumn('#__jobboard_jobs', 'ref_num',' varchar(255) DEFAULT NULL');

      // update job type enumerator  - jobboard_jobs

      $query = 'SELECT `id`, `job_type`
          FROM `#__jobboard_jobs` WHERE TRUE';
      $db->setQuery($query);
      $curr_jobs = $db->loadAssocList();
      tblModifyColumn('#__jobboard_jobs', 'job_type', " enum('COM_JOBBOARD_DB_JFULLTIME','COM_JOBBOARD_DB_JCONTRACT','COM_JOBBOARD_DB_JPARTTIME','COM_JOBBOARD_DB_JTEMP','COM_JOBBOARD_DB_JINTERN','COM_JOBBOARD_DB_JOTHER') NOT NULL DEFAULT 'COM_JOBBOARD_DB_JFULLTIME'");
      if(!empty($curr_jobs)){
        if(!is_numeric(stripos($curr_jobs[0]['job_type'], 'COM_JOBBOARD'))){
           set_time_limit(0);
           foreach($curr_jobs as $job) {
             $query = 'UPDATE `#__jobboard_jobs`
                      SET `job_type` = "COM_JOBBOARD_'.$job['job_type'].'"
                      WHERE `id` = '.$job['id'];
             $db->setQuery($query);
             $db->Query();
          }
        }
      }
      // update table data - emailmsg
      $query = "insert ignore  into `#__jobboard_emailmsg`(`id`,`type`,`subject`,`body`) values (1,'userrejected','Your job application for: [jobtitle]','Dear [toname],\r\n\r\nThank you for expressing an interest in applying for a position with [fromname].\r\n\r\nIt is with regret that we inform you that your application was not successful. We will however keep your resume details on our database and contact you should any suitable vacancies arise.  \r\n\r\nWe wish you everything of the best for the future.\r\n\r\nYours Sincerely\r\n[fromname]'),(2,'adminnew','Job post [jobtitle] created','The following job post has been created.\r\n\r\nJob Title: [jobtitle]\r\n\r\nJob Department: [department]\r\nJob Location: [location]\r\nJob Status: [status]\r\n\r\nCreated by [appladmin]'),(10,'userapproved','Job Application [jobtitle] approved','Dear [toname],\r\n\r\nIt is with great pleasure to inform you that you have been awarded the position of [jobtitle] with [fromname].\r\n\r\nYou will be contacted regarding further details.\r\n\r\nYours Sincerely\r\n[fromname]\r\n'),(13,'adminupdate_application','Job application for [toname] [tosurname] updated','The following job application has been updated:\r\n\r\nApplicant Name: [toname] [tosurname]\r\nStatus: [applstatus]\r\nJob Title: [jobtitle]\r\nJob ID: [jobid]\r\nJob Department: [department]\r\n\r\nUpdated by [appladmin]'),(3,'adminsms','Job post: [jobtitle] updated','Title:[jobtitle]\r\nLocation:[location]\r\n\r\nRegards,\r\n[fromname]'),(4,'adminupdate','Job post [jobtitle] updated','The following job post has been updated:\r\n\r\nJob Title: [jobtitle]\r\nJob ID: [jobid]\r\nJob Department: [department]\r\nJob Location: [location]\r\nJob Status: [status]\r\n\r\nUpdated by [appladmin]\r\n'),(14,'adminnew_application','New job application for [applname] [applsurname]','The following job application has been created:\r\n\r\nApplicant Name: [applname] [applsurname]\r\nStatus: [applstatus]\r\nJob Title: [jobtitle]\r\nJob ID: [jobid]\r\nJob Department: [department]\r\n-----------------------------\r\nCV/Resume Title: [appltitle]\r\n\r\nCover Note:\r\n***********\r\n[applcovernote]\r\n***********\r\n\r\nSubmitted by [appladmin]'),(15,'adminupdate_unsolicited','Unsolicited application for [toname] [tosurname] updated','The following unsolicited application has been updated:\r\n\r\nApplicant Name: [toname] [tosurname]\r\nApplicant ID: [applicantid]\r\n\r\nUpdated by [appladmin]'),(16,'adminnew_unsolicited','New unsolicited cv/resume','A new unsolicited CV/Resume has been submitted.\r\n\r\nApplicant Name: [toname] [tosurname]\r\nCV/Resume Title: [cvtitle]\r\n\r\n------------------------------------\r\n[fromname]'),(5,'unsolicitednew','[toname], your CV ([cvtitle])has been received','Dear [toname],\r\n\r\nThank you for submitting your CV to [fromname]. \r\n\r\nYour application will be reviewed and we will get in touch with you if a suitable position becomes available.\r\n\r\nYours sincerely,\r\n[fromname]\r\n\r\nPlease do not respond to this message. It is automatically generated and is for information purposes only.'),(6,'usernew','Job application for [jobtitle]-[location] received','Dear [toname],\r\n\r\nThank you for applying for the following position with [fromname]:\r\n[jobtitle] \r\n\r\nYour application is on file and will be reviewed.\r\n\r\nShould you not hear from us within 14 days, please consider your application unsuccessful.\r\n\r\nThank you,\r\n[fromname]\r\n\r\nPlease do not respond to this message. It is automatically generated and is for information purposes only.'),(7,'sharejob','Online job recommendation...','Hello,\r\n\r\nI found this great job and thought you would be interested in viewing the full Job advert online...'),(8,'sharejpriv','Online job recommendation...','\r\n\r\n[jobtitle] - [location] \r\n\r\n'),(9,'usersms','Job Application [jobtitle] received','Title:[jobtitle]\r\nLocation:[location]\r\n\r\nRegards,\r\n[fromname]'),(17,'userinvite','Invitation to apply for Job: [jobtitle] ','Dear [toname],\r\n\r\nYou have been invited to apply for the following position:\r\n[jobtitle] \r\n\r\nMessage from [fromname]:\r\n[message]\r\n\r\nYour associated cv/resume profile name is: [cvprofile]\r\n\r\nPlease follow the link below to view the job details:\r\n\r\n[link]\r\n\r\n'),(18,'adminvite','Job: [jobtitle] Invitation. [fromname] has responded','Dear [toname],\r\n\r\n[fromname] has accepted your invitation to apply for the following position:\r\n[jobtitle]\r\n\r\nPlease follow the link below to process the application\r\n[link] \r\n');";
      $db->setQuery($query);
      $db->Query();
      // add new table - file_uploads
      $query = "CREATE TABLE IF NOT EXISTS `#__jobboard_file_uploads` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `cvprof_id` int(11) DEFAULT NULL,
            `user_id` int(11) DEFAULT NULL,
            `create_date` datetime DEFAULT NULL,
            `filetitle` varchar(96) DEFAULT NULL,
            `filepath` text,
            `filename` varchar(256) DEFAULT NULL,
            `filehash` varchar(256) DEFAULT NULL,
            `filetype` varchar(24) DEFAULT NULL,
            `filesize` bigint(20) DEFAULT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
      $db->setQuery($query);
      $db->Query();
      // add new table - sched_tasks
      $query = "CREATE TABLE IF NOT EXISTS `#__jobboard_sched_tasks` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `title` varchar(96) DEFAULT NULL,
          `handle` varchar(32) DEFAULT NULL,
          `enabled` tinyint(1) DEFAULT '0',
          PRIMARY KEY (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;";
      $db->setQuery($query);
      $db->Query();
      // add data for table - sched_tasks
      $query = "insert ignore  into `#__jobboard_sched_tasks`(`id`,`title`,`handle`,`enabled`) values (1,'COM_JOBBOARD_TASKS_DISABLE_EXPIRED','disable_exp',1); ";
      $db->setQuery($query);
      $db->Query();
      // add new table - usercomms
      $query = "CREATE TABLE IF NOT EXISTS `#__jobboard_usercomms` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `sender_id` int(11) DEFAULT NULL,
          `recipient_id` int(11) DEFAULT NULL,
          `subject_id` int(11) DEFAULT '0',
          `subject` text,
          `message` text,
          PRIMARY KEY (`id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
      $db->setQuery($query);
      $db->Query();

      //update unsolicited table
      tblAddColumn('#__jobboard_unsolicited', 'filetype',' varchar(24) DEFAULT NULL AFTER `file_hash`');

	   //echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;--- Done<br>";

	   tblSetColumnValue('#__jobboard_config', 'release_ver', '1.2.7.1.lite');
   }
  if($curr_version <> '1.2.7.1') tblSetColumnValue('#__jobboard_config', 'release_ver', '1.2.7.1');
  $curr_version = tblCheckColumnValue('#__jobboard_config', 'release_ver', ' WHERE id=1');

  /*******************************
  * Modules
  ******************************/

  if(version_compare( JVERSION, '1.6.0', 'ge' )) {
     $modules = &$this->manifest->xpath('modules/module');
     $children = $modules;
     $is_go = true;
  } else {
     $modules = &$this->manifest->getElementByPath('modules');
     $is_go = is_a($modules, 'JSimpleXMLElement') && count($modules->children())? true : false;
      if($is_go)
        $children = $modules->children();
  }

  if ($is_go) {

  	foreach ($children as $module)
  	{
  	    set_time_limit(0);
        if(version_compare( JVERSION, '1.6.0', 'ge' )) {
    		$mname		= $module->getAttribute('module');
            $mclient    = $module->getAttribute('client');
    		$mpublish	= $module->getAttribute('publish');
    		$mgroup		= $module->getAttribute('position');
        } else {
    		$mname		= $module->attributes('module');
            $mclient    = $module->attributes('client');
    		$mpublish	= $module->attributes('publish');
    		$mgroup		= $module->attributes('position');
        }

        $path = $src . DS . 'mod_'.$mname;

        $installer = new JInstaller;
		$mstatus = $installer->install($path);
        $status->modules[] = array('name'=>$mname,'client'=>$mclient, 'result'=>$mstatus);

        if (isset($mposition) && $mposition != 'left')
    	{
    		$query = "UPDATE ".$db->nameQuote("#__modules")."
              SET ".$db->nameQuote("position")." = ".$db->Quote($mposition)."
              WHERE ".$db->nameQuote("module")." = ".$db->Quote($mname).";";
    		$db->setQuery($query);
    		$db->query();
    	}

        if ($mpublish)
    	{
    		$query = "UPDATE ".$db->nameQuote("#__modules")."
              SET ".$db->nameQuote("published")." 1
              WHERE ".$db->nameQuote("module")." = ".$db->Quote($mname).";";
    		$db->setQuery($query);
    		$db->query();
    	}
  	}
  }


   /*******************************
   * Plugins
   * *****************************/

  if(version_compare( JVERSION, '1.6.0', 'ge' )) {
     $plugins = &$this->manifest->xpath('plugins/plugin');
     $children = $plugins;
     $is_go = true;
  } else {
     $plugins = &$this->manifest->getElementByPath('plugins');
     $is_go = is_a($plugins, 'JSimpleXMLElement') && count($plugins->children())? true : false;
     if($is_go)
        $children = $plugins->children();
  }

  if ($is_go) {

  	foreach ($children as $plugin)
  	{
  	    set_time_limit(0);
        if(version_compare( JVERSION, '1.6.0', 'ge' )) {
    		$pname		= $plugin->getAttribute('plugin');
    		$ppublish	= $plugin->getAttribute('publish');
    		$pgroup		= $plugin->getAttribute('group');
        } else {
    		$pname		= $plugin->attributes('plugin');
    		$ppublish	= $plugin->attributes('publish');
    		$pgroup		= $plugin->attributes('group');
        }

        $path       = $src . DS . 'plg_'.$pname;
        if($pgroup == 'search')  $path .= '_'.$pgroup;

        $installer = new JInstaller;
		$pstatus = $installer->install($path);
		$status->plugins[] = array('name'=>$pname,'group'=>$pgroup, 'result'=>$pstatus);

        if(version_compare( JVERSION, '1.6.0', 'ge' )) {
    		$query = "UPDATE ".$db->nameQuote("#__extensions")."
              SET ".$db->nameQuote("enabled")." = 1
              WHERE ".$db->nameQuote("type")." = 'plugin' AND ".$db->nameQuote("element")." = ".$db->Quote($pname)." AND ".$db->nameQuote("folder")." = ".$db->Quote($pgroup);
        } else {
              $query = "UPDATE ".$db->nameQuote("#__plugins")."
              SET ".$db->nameQuote("published")." = 1
              WHERE ".$db->nameQuote("element")." = ".$db->Quote($pname)." AND ".$db->nameQuote("folder")." = ".$db->Quote($pgroup);
        }
		$db->setQuery($query);
		$db->query();
  	}

     if(!version_compare( JVERSION, '1.6.0', 'ge' )) {
          $query = "UPDATE ".$db->nameQuote("#__plugins")."
          SET ".$db->nameQuote("published")." = 1
          WHERE ".$db->nameQuote("element")." = ".$db->Quote('mtupgrade')." AND ".$db->nameQuote("folder")." = ".$db->Quote('system');
    	  $db->setQuery($query);
    	  $db->query();
     }
  }

   /*******************************
   * Multi-languge (JoomFish)
   * *****************************/

  $app = &JFactory::getApplication();
  if (JFolder::exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomfish'.DS.'contentelements')){

  	if(version_compare( JVERSION, '1.6.0', 'ge' )) {
  		$elements = &$this->manifest->xpath('joomfish/defn');
  		foreach ($elements as $element) {
  			JFile::copy($src.DS.'joomfish'.DS.'contentelements'.DS.$element->data(),JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomfish'.DS.'contentelements'.DS.$element->data());
  		}
  	}
  	else {
  		$elements = &$this->manifest->getElementByPath('joomfish');
  		if (is_a($elements, 'JSimpleXMLElement') && count($elements->children())) {
  			foreach ($elements->children() as $element) {
  				JFile::copy($src.DS.'joomfish'.DS.'contentelements'.DS.$element->data(),JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomfish'.DS.'contentelements'.DS.$element->data());
  			}
  		}
  	}

  $app->enqueueMessage(JText::_('COM_JOBBOARD_HAS_JOOMFISH'));

  } //else $app->enqueueMessage(JText::_('COM_JOBBOARD_NO_JOOMFISH'));

  ?>

  <?php $rows = 0; ?>
  <strong><?php echo 'Job Board v '.$curr_version ?></strong>
  <br />
  <p><?php echo JText::_('COM_JOBBOARD_WELCOME_MSG1'); ?></p>
  <ul><?php echo JText::_('COM_JOBBOARD_WELCOME_MSG2'); ?></ul>
  <a href="<?php echo JFilterOutput::AmpReplace('index.php?option=com_jobboard&view=config') ?>"><?php echo JText::_('COM_JOBBOARD_WELCOME_MSG3'); ?></a><br />
  <br />
  <img src="<?php echo JURI::root(true); ?>/administrator/components/com_jobboard/images/job_board.png" width="40" height="48" alt="Job Board Component" align="right" />
  <h2><?php echo JText::_('COM_JOBBOARD_INSTALLATION_STATUS'); ?></h2>
  <table class="adminlist">
  	<thead>
  		<tr>
  			<th class="title" colspan="2"><?php echo JText::_('COM_JOBBOARD_EXT'); ?></th>
  			<th width="30%"><?php echo JText::_('COM_JOBBOARD_INSTALL_STATUS'); ?></th>
  		</tr>
  	</thead>
  	<tfoot>
  		<tr>
  			<td colspan="3"></td>
  		</tr>
  	</tfoot>
  	<tbody>
  		<tr class="row0">
  			<td class="key" colspan="2"><?php echo JText::_('COM_JOBBOARD_COMP'); ?></td>
  			<td><strong><?php echo JText::_('COM_JOBBOARD_INSTALLED'); ?></strong></td>
  		</tr>
  		<?php if (count($status->modules)): ?>
  		<tr>
  			<th><?php echo JText::_('COM_JOBBOARD_MODULE'); ?></th>
  			<th><?php echo JText::_('COM_JOBBOARD_CLIENT'); ?></th>
  			<th></th>
  		</tr>
  		<?php foreach ($status->modules as $module): ?>
      		<tr class="row<?php echo (++ $rows % 2); ?>">
      			<td class="key"><?php echo $module['name']; ?></td>
      			<td class="key"><?php echo ucfirst($module['client']); ?></td>
      			<td>
                    <strong>
                        <?php echo ($module['result'])?JText::_('COM_JOBBOARD_INSTALLED'):JText::_('COM_JOBBOARD_NOT_INSTALLED'); ?>
                    </strong>
                  </td>
      		</tr>
  		<?php endforeach; ?>
  		<?php endif; ?>
  		<?php if (count($status->plugins)): ?>
  		<tr>
  			<th><?php echo JText::_('COM_JOBBOARD_PLUGIN'); ?></th>
  			<th><?php echo JText::_('COM_JOBBOARD_PLG_GRP'); ?></th>
  			<th></th>
  		</tr>
  		<?php foreach ($status->plugins as $plugin): ?>
      		<tr class="row<?php echo (++ $rows % 2); ?>">
      			<td class="key"><?php echo ucfirst($plugin['name']); ?></td>
      			<td class="key"><?php echo ucfirst($plugin['group']); ?></td>
      			<td>
                      <strong>
                          <?php echo ($plugin['result'])?JText::_('COM_JOBBOARD_INSTALLED'):JText::_('COM_JOBBOARD_NOT_INSTALLED'); ?>
                      </strong>
                  </td>
      		</tr>
  		<?php endforeach; ?>
  		<?php endif; ?>
  	</tbody>
  </table>
<?php

/* Add column to existing table */
function tblAddColumn($tbl,$col,$defns)
{
	$db =& JFactory::getDBO();

	// php doesn't throw an error here if the column already exists (unlike the sql install script)
	$query = "ALTER TABLE `".$tbl."` ADD `".$col."` ".$defns;
	$db->setQuery($query);
	$result = $db->query();
	//echo$query.' :: Result:'.$result.'<br />';
}

/* Modify existing column properties */
function tblModifyColumn($tbl,$col,$defns)
{
	$db =& JFactory::getDBO();

	$query = "ALTER TABLE `".$tbl."` MODIFY `".$col."` ".$defns;
	$db->setQuery($query);
	$result = $db->query();
	//echo$query.' :: Result:'.$result.'<br />';
}

/* Check column value */
function tblCheckColumnValue($tbl, $col, $cond='')
{
	$db =& JFactory::getDBO();
	$query = "SELECT ".$col." FROM ".$tbl.' '.$cond;
	$db->setQuery($query);
	$result = $db->loadResult();
	//echo $query.' :: Result:'.$result.'<br />';
	return $result;
}

/* Set column value */
function tblSetColumnValue($tbl, $col, $val, $cond='')
{
	$db =& JFactory::getDBO();
	$query = "UPDATE ".$tbl." SET ".$col." = ".$db->Quote($val).' '.$cond;
	$db->setQuery($query);
	$result = $db->query();
	//echo $query.' :: Result:'.$result.'<br />'; 
}

function tblGetColumAndIDValues($tbl, $id_colname, $col){

	$db =& JFactory::getDBO();
	$query = "SELECT `".$id_colname."`, `".$col."` FROM ".$tbl;
	$db->setQuery($query);
	$result = $db->loadObjectList();
	//echo '<br /> Object:'.json_encode($result);
	return $result;
}

function tblSetColumValuesByID($obj, $colname){
	
	foreach($obj as $row){
		//echo 'id:'.$row->id.' :: column:'.$row->$colname.'<br />';
		switch($row->$colname) {
        
		//for jobs table
		 case 'Full time/Permanent' : 
			 tblSetColumnValue('#__jobboard_jobs', $colname, 'COM_JOBBOARD_DB_JFULLTIME', ' WHERE id='.$row->id);
		 break;
		 case 'Part time/Temp' :
			tblSetColumnValue('#__jobboard_jobs', $colname, 'COM_JOBBOARD_DB_JPARTTIME', ' WHERE id='.$row->id);
		 break;
		 case 'Contract' :
			tblSetColumnValue('#__jobboard_jobs', $colname, 'COM_JOBBOARD_DB_JCONTRACT', ' WHERE id='.$row->id);
		 break;
		}
	}
}

?>