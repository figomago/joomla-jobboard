
/*Table structure for table `#__jobboard_applicants` */

CREATE TABLE IF NOT EXISTS `#__jobboard_applicants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `job_id` int(11) NOT NULL DEFAULT '0',
  `first_name` varchar(96) NOT NULL DEFAULT '',
  `last_name` varchar(96) NOT NULL DEFAULT '',
  `email` varchar(254) NOT NULL,
  `tel` varchar(32) NOT NULL,
  `title` varchar(96) NOT NULL DEFAULT '',
  `filename` varchar(254) NOT NULL DEFAULT '',
  `file_hash` varchar(254) NOT NULL DEFAULT '',
  `cover_note` text NOT NULL,
  `admin_notes` text NOT NULL,
  `notify` int(3) NOT NULL DEFAULT '1',
  `notify_admin` int(3) NOT NULL DEFAULT '1',
  `status` int(3) NOT NULL DEFAULT '1',
  `filetype` varchar(24) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `#__jobboard_applicants` */

/*Table structure for table `#__jobboard_bookmarks` */

CREATE TABLE IF NOT EXISTS `#__jobboard_bookmarks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `job_id` int(11) DEFAULT NULL,
  `mark_date` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__jobboard_bookmarks` */

/*Table structure for table `#__jobboard_career_levels` */

CREATE TABLE IF NOT EXISTS `#__jobboard_career_levels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `#__jobboard_career_levels` */

insert ignore  into `#__jobboard_career_levels`(`id`,`description`) values (1,'Internship'),(2,'Entry Level (Less than 2 years of Experience)'),(3,'Mid Career (2+ years of experience)'),(4,'Senior (5+ years of experience)'),(5,'Executive (SVP, EVP, VP etc)'),(6,'Management (Manager/Director)'),(7,'Not Specified');

/*Table structure for table `#__jobboard_categories` */

CREATE TABLE IF NOT EXISTS `#__jobboard_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(64) NOT NULL,
  `enabled` tinyint(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;

/*Data for the table `#__jobboard_categories` */

insert ignore  into `#__jobboard_categories`(`id`,`type`,`enabled`) values (1,'All Categories',1),(2,'Academic',1),(3,'Accounts',1),(4,'Advertising',1),(5,'Aviation',1),(6,'Banking / Finance And Investment',1),(7,'Call Centre',1),(8,'Chemical / Petrochemical',1),(9,'Civil / Building',1),(10,'Computer and Information Technology',1),(11,'Engineering',1),(12,'Environmental / Horticulture / Agriculture',1),(13,'Fmcg',1),(14,'Freight / Shipping / Transport / Import / Export',1),(15,'Government / Municipal',1),(16,'Hotel / Catering / Hospitality / Leisure',1),(17,'Human Resources',1),(18,'Insurance',1),(19,'Legal',1),(20,'Logistics',1),(21,'Management Consulting',1),(22,'Manufacturing',1),(23,'Matriculants',1),(24,'Mining',1),(25,'Motor Industry',1),(26,'NGO / Non-profit',1),(27,'Office Support',1),(28,'Optometry',1),(29,'Part Time (no Experience Needed)',1),(30,'Pharmaceutical / Medical / Healthcare / Hygiene',1),(31,'Pr / Communications / Journalism / Media And Promotions',1),(32,'Production',1),(33,'Professional',1),(34,'Property',1),(35,'Publishing',1),(36,'Purchasing',1),(37,'Research',1),(38,'Retail',1),(39,'Safety And Security',1),(40,'Sales And Marketing',1),(41,'Stockbroking',1),(42,'Supply Chain',1),(43,'Technical',1),(44,'Telecommunications',1),(45,'Tender And Service Information',1),(46,'Textiles  / Clothing Industry',1),(47,'Travel / Tourism',1);

/*Table structure for table `#__jobboard_config` */

CREATE TABLE IF NOT EXISTS `#__jobboard_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organisation` varchar(255) NOT NULL DEFAULT 'Some Organisation',
  `from_mail` varchar(255) NOT NULL DEFAULT 'someone@somewhere.com',
  `reply_to` varchar(255) NOT NULL DEFAULT 'no-reply@somewhere.com',
  `default_dept` int(11) NOT NULL DEFAULT '1',
  `default_country` int(5) NOT NULL DEFAULT '220',
  `default_city` varchar(64) NOT NULL DEFAULT 'SomeCity',
  `default_jobtype` int(11) NOT NULL DEFAULT '1',
  `default_career` int(11) NOT NULL DEFAULT '3',
  `default_edu` int(11) NOT NULL DEFAULT '3',
  `default_category` int(11) NOT NULL DEFAULT '2',
  `default_post_range` enum('0','1','2','3','7','14','30','60') NOT NULL DEFAULT '0',
  `allow_unsolicited` tinyint(4) NOT NULL DEFAULT '1',
  `allow_applications` tinyint(4) NOT NULL DEFAULT '1',
  `dept_notify_admin` int(11) NOT NULL DEFAULT '1',
  `dept_notify_contact` int(11) NOT NULL DEFAULT '1',
  `show_social` tinyint(4) NOT NULL DEFAULT '1',
  `show_viewcount` tinyint(4) NOT NULL DEFAULT '1',
  `show_applcount` tinyint(4) NOT NULL DEFAULT '1',
  `email_cvattach` tinyint(4) NOT NULL DEFAULT '0',
  `show_job_summary` tinyint(4) NOT NULL DEFAULT '1',
  `send_tofriend` tinyint(4) NOT NULL DEFAULT '1',
  `appl_job_summary` tinyint(4) NOT NULL DEFAULT '1',
  `sharing_job_summary` tinyint(4) NOT NULL DEFAULT '1',
  `short_date_format` tinyint(4) NOT NULL DEFAULT '0',
  `date_separator` tinyint(4) NOT NULL DEFAULT '0',
  `long_date_format` tinyint(4) NOT NULL DEFAULT '0',
  `jobtype_coloring` tinyint(4) NOT NULL DEFAULT '1',
  `use_location` tinyint(4) NOT NULL DEFAULT '1',
  `social_icon_style` tinyint(4) NOT NULL DEFAULT '1',
  `release_ver` text NOT NULL,
  `max_filesize` int(11) DEFAULT '2',
  `max_files` int(11) DEFAULT '10',
  `max_quals` int(11) DEFAULT '5',
  `max_employers` int(11) DEFAULT '10',
  `max_skills` int(11) DEFAULT '20',
  `default_upl_folder` varchar(256) DEFAULT NULL,
  `default_col_layout` tinyint(1) DEFAULT '2',
  `allow_once_off_applications` tinyint(1) DEFAULT '0',
  `default_list_layout` varchar(16) DEFAULT 'list',
  `distance_unit` tinyint(1) DEFAULT '0',
  `default_distance` enum('10','15','20','30','50','70','100','300','500','1000','5000','10000') NOT NULL DEFAULT '50',
  `enable_post_maps` tinyint(1) DEFAULT '1',
  `home_intro_title` text,
  `home_intro` text,
  `home_jobs_limit` int(11) NOT NULL DEFAULT '5',
  `secure_login` tinyint(1) NOT NULL DEFAULT '0',
  `captcha_login` tinyint(1) DEFAULT '1',
  `captcha_reg` tinyint(1) DEFAULT '1',
  `captcha_public` tinyint(1) DEFAULT '1',
  `allow_registration` tinyint(1) DEFAULT '1',
  `show_rss` tinyint(1) DEFAULT '1',
  `default_user_grp` int(11) NOT NULL DEFAULT '5',
  `default_empl_grp` int(11) NOT NULL DEFAULT '2',
  `maint_tasks_on` tinyint(1) DEFAULT '1',
  `maint_tasks_int_type` tinyint(1) DEFAULT '1',
  `maint_tasks_int` int(11) DEFAULT '2',
  `last_maint_check` bigint(20) DEFAULT '0',
  `last_maint_run` bigint(20) DEFAULT '0',
  `sched_disable_exp` tinyint(1) DEFAULT '1',
  `sched_expire_feat` tinyint(1) DEFAULT '1',
  `sched_backup_data` tinyint(1) DEFAULT '1',
  `email_task_results` tinyint(1) DEFAULT '0',
  `empl_default_feature` tinyint(1) DEFAULT '0',
  `feature_length` int(11) DEFAULT '30',
  `allow_linkedin_imports` tinyint(1) DEFAULT '1',
  `linkedin_key` varchar(32) DEFAULT NULL,
  `linkedin_secret` varchar(32) DEFAULT NULL,
  `user_show_applstatus` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `#__jobboard_config` */

/*Table structure for table `#__jobboard_countries` */

CREATE TABLE IF NOT EXISTS `#__jobboard_countries` (
  `country_id` int(11) NOT NULL AUTO_INCREMENT,
  `country_name` varchar(100) NOT NULL,
  `short_code` varchar(2) NOT NULL,
  `dial_prefix` int(11) NOT NULL,
  `country_region` varchar(100) NOT NULL,
  PRIMARY KEY (`country_id`)
) ENGINE=MyISAM AUTO_INCREMENT=268 DEFAULT CHARSET=utf8;

/*Data for the table `#__jobboard_countries` */

/*Table structure for table `#__jobboard_cvprofiles` */

CREATE TABLE IF NOT EXISTS `#__jobboard_cvprofiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `created_date` datetime DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  `hiring_dept` int(11) NOT NULL DEFAULT '0',
  `profile_name` varchar(64) DEFAULT NULL,
  `desired_posn` varchar(96) DEFAULT '0',
  `avail_date` date DEFAULT '0000-00-00',
  `third_party` tinyint(1) DEFAULT '0',
  `job_type` varchar(48) DEFAULT NULL,
  `security_clearance` tinyint(1) DEFAULT '0',
  `file_uploads` tinyint(1) DEFAULT '0',
  `edu_id` tinyint(1) DEFAULT '0',
  `edu_institution` varchar(48) DEFAULT NULL,
  `edu_country_id` int(11) DEFAULT '0',
  `edu_city` varchar(32) DEFAULT NULL,
  `latest_employer_id` int(11) DEFAULT '0',
  `pref_country` int(11) DEFAULT NULL,
  `pref_locns` text,
  `summary` text,
  `hits` int(11) DEFAULT '0',
  `invites` int(11) DEFAULT '0',
  `is_linkedin` tinyint(1) DEFAULT '0',
  `is_private` tinyint(1) DEFAULT '0',
  `highest_qual` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__jobboard_cvprofiles` */

/*Table structure for table `#__jobboard_departments` */

CREATE TABLE IF NOT EXISTS `#__jobboard_departments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) DEFAULT '42',
  `user_groups` varchar(512) DEFAULT '0',
  `name` varchar(64) NOT NULL DEFAULT 'default',
  `contact_name` varchar(72) NOT NULL DEFAULT 'Someone',
  `contact_email` varchar(254) NOT NULL DEFAULT 'somedep@somewhere.com',
  `notify` tinyint(1) NOT NULL DEFAULT '1',
  `notify_admin` tinyint(1) NOT NULL DEFAULT '1',
  `acceptance_notify` tinyint(1) NOT NULL DEFAULT '1',
  `rejection_notify` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `#__jobboard_departments` */

/*Table structure for table `#__jobboard_education` */

CREATE TABLE IF NOT EXISTS `#__jobboard_education` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `#__jobboard_education` */

insert ignore  into `#__jobboard_education`(`id`,`level`) values (1,'Advanced Degree'),(2,'Bachelor\'s Degree'),(3,'Diploma'),(4,'High School');

/*Table structure for table `#__jobboard_emailmsg` */

CREATE TABLE IF NOT EXISTS `#__jobboard_emailmsg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` text NOT NULL,
  `subject` text NOT NULL,
  `body` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

/*Data for the table `#__jobboard_emailmsg` */

insert ignore  into `#__jobboard_emailmsg`(`id`,`type`,`subject`,`body`) values (1,'userrejected','Your job application for: [jobtitle]','Dear [toname],\r\n\r\nThank you for expressing an interest in applying for a position with [fromname].\r\n\r\nIt is with regret that we inform you that your application was not successful. We will however keep your resume details on our database and contact you should any suitable vacancies arise.  \r\n\r\nWe wish you everything of the best for the future.\r\n\r\nYours Sincerely\r\n[fromname]'),(2,'adminnew','Job post [jobtitle] created','The following job post has been created.\r\n\r\nJob Title: [jobtitle]\r\n\r\nJob Department: [department]\r\nJob Location: [location]\r\nJob Status: [status]\r\n\r\nCreated by [appladmin]'),(10,'userapproved','Job Application [jobtitle] approved','Dear [toname],\r\n\r\nIt is with great pleasure to inform you that you have been awarded the position of [jobtitle] with [fromname].\r\n\r\nYou will be contacted regarding further details.\r\n\r\nYours Sincerely\r\n[fromname]\r\n'),(13,'adminupdate_application','Job application for [toname] [tosurname] updated','The following job application has been updated:\r\n\r\nApplicant Name: [toname] [tosurname]\r\nStatus: [applstatus]\r\nJob Title: [jobtitle]\r\nJob ID: [jobid]\r\nJob Department: [department]\r\n\r\nUpdated by [appladmin]'),(3,'adminsms','Job post: [jobtitle] updated','Title:[jobtitle]\r\nLocation:[location]\r\n\r\nRegards,\r\n[fromname]'),(4,'adminupdate','Job post [jobtitle] updated','The following job post has been updated:\r\n\r\nJob Title: [jobtitle]\r\nJob ID: [jobid]\r\nJob Department: [department]\r\nJob Location: [location]\r\nJob Status: [status]\r\n\r\nUpdated by [appladmin]\r\n'),(14,'adminnew_application','New job application for [applname] [applsurname]','The following job application has been created:\r\n\r\nApplicant Name: [applname] [applsurname]\r\nStatus: [applstatus]\r\nJob Title: [jobtitle]\r\nJob ID: [jobid]\r\nJob Department: [department]\r\n-----------------------------\r\nCV/Resume Title: [appltitle]\r\n\r\nCover Note:\r\n***********\r\n[applcovernote]\r\n***********\r\n\r\nSubmitted by [appladmin]'),(15,'adminupdate_unsolicited','Unsolicited application for [toname] [tosurname] updated','The following unsolicited application has been updated:\r\n\r\nApplicant Name: [toname] [tosurname]\r\nApplicant ID: [applicantid]\r\n\r\nUpdated by [appladmin]'),(16,'adminnew_unsolicited','New unsolicited cv/resume','A new unsolicited CV/Resume has been submitted.\r\n\r\nApplicant Name: [toname] [tosurname]\r\nCV/Resume Title: [cvtitle]\r\n\r\n------------------------------------\r\n[fromname]'),(5,'unsolicitednew','[toname], your CV ([cvtitle])has been received','Dear [toname],\r\n\r\nThank you for submitting your CV to [fromname]. \r\n\r\nYour application will be reviewed and we will get in touch with you if a suitable position becomes available.\r\n\r\nYours sincerely,\r\n[fromname]\r\n\r\nPlease do not respond to this message. It is automatically generated and is for information purposes only.'),(6,'usernew','Job application for [jobtitle]-[location] received','Dear [toname],\r\n\r\nThank you for applying for the following position with [fromname]:\r\n[jobtitle] \r\n\r\nYour application is on file and will be reviewed.\r\n\r\nShould you not hear from us within 14 days, please consider your application unsuccessful.\r\n\r\nThank you,\r\n[fromname]\r\n\r\nPlease do not respond to this message. It is automatically generated and is for information purposes only.'),(7,'sharejob','Online job recommendation...','Hello,\r\n\r\nI found this great job and thought you would be interested in viewing the full Job advert online...'),(8,'sharejpriv','Online job recommendation...','\r\n\r\n[jobtitle] - [location] \r\n\r\n'),(9,'usersms','Job Application [jobtitle] received','Title:[jobtitle]\r\nLocation:[location]\r\n\r\nRegards,\r\n[fromname]'),(17,'userinvite','Invitation to apply for Job: [jobtitle] ','Dear [toname],\r\n\r\nYou have been invited to apply for the following position:\r\n[jobtitle] \r\n\r\nMessage from [fromname]:\r\n[message]\r\n\r\nYour associated cv/resume profile name is: [cvprofile]\r\n\r\nPlease follow the link below to view the job details:\r\n\r\n[link]\r\n\r\n'),(18,'adminvite','Job: [jobtitle] Invitation. [fromname] has responded','Dear [toname],\r\n\r\n[fromname] has accepted your invitation to apply for the following position:\r\n[jobtitle]\r\n\r\nPlease follow the link below to process the application\r\n[link] \r\n');

/*Table structure for table `#__jobboard_file_tokens` */

CREATE TABLE IF NOT EXISTS `#__jobboard_file_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_id` int(11) DEFAULT NULL,
  `token` varchar(36) DEFAULT NULL,
  `expires` date DEFAULT '0000-00-00',
  `max_use` int(11) DEFAULT '10',
  `hits` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__jobboard_file_tokens` */

/*Table structure for table `#__jobboard_file_uploads` */

CREATE TABLE IF NOT EXISTS `#__jobboard_file_uploads` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__jobboard_file_uploads` */

/*Table structure for table `#__jobboard_invites` */

CREATE TABLE IF NOT EXISTS `#__jobboard_invites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `create_date` datetime DEFAULT '0000-00-00 00:00:00',
  `user_id` int(11) DEFAULT '0',
  `sender_id` int(11) DEFAULT '0',
  `cvprof_id` int(11) DEFAULT '0',
  `job_id` int(11) DEFAULT '0',
  `message` text,
  `response` tinyint(1) DEFAULT '0',
  `hits` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__jobboard_invites` */

/*Table structure for table `#__jobboard_jobs` */

CREATE TABLE IF NOT EXISTS `#__jobboard_jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `expiry_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `posted_by` int(11) DEFAULT '0',
  `job_title` varchar(128) NOT NULL,
  `job_type` enum('COM_JOBBOARD_DB_JFULLTIME','COM_JOBBOARD_DB_JCONTRACT','COM_JOBBOARD_DB_JPARTTIME','COM_JOBBOARD_DB_JTEMP','COM_JOBBOARD_DB_JINTERN','COM_JOBBOARD_DB_JOTHER') NOT NULL DEFAULT 'COM_JOBBOARD_DB_JFULLTIME',
  `category` int(11) NOT NULL DEFAULT '1',
  `career_level` int(11) NOT NULL DEFAULT '1',
  `education` int(11) NOT NULL DEFAULT '2',
  `positions` int(11) NOT NULL DEFAULT '1',
  `salary` varchar(96) NOT NULL,
  `country` int(11) NOT NULL DEFAULT '220',
  `city` varchar(64) NOT NULL DEFAULT 'Some City',
  `description` text NOT NULL,
  `duties` text NOT NULL,
  `job_tags` text NOT NULL,
  `department` int(11) unsigned NOT NULL DEFAULT '1',
  `status` enum('new','reviewed','scheduled','rejected','accepted') NOT NULL DEFAULT 'new',
  `num_applications` int(11) NOT NULL DEFAULT '0',
  `hits` int(11) unsigned NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `questionnaire_id` int(11) DEFAULT '0',
  `ref_num` varchar(255) DEFAULT NULL,
  `geo_latitude` longtext,
  `geo_longitude` longtext,
  `geo_state_province` longtext,
  `featured` tinyint(1) DEFAULT '0',
  `default_user_grp` int(11) DEFAULT '5',
  `default_empl_grp` int(11) DEFAULT '2',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `#__jobboard_jobs` */

/*Table structure for table `#__jobboard_msg` */

CREATE TABLE IF NOT EXISTS `#__jobboard_msg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) NOT NULL,
  `sender_name` varchar(64) NOT NULL,
  `sender_email` varchar(128) NOT NULL,
  `recipient_list` text NOT NULL,
  `message` text NOT NULL,
  `send_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `#__jobboard_msg` */

/*Table structure for table `#__jobboard_past_edu` */

CREATE TABLE IF NOT EXISTS `#__jobboard_past_edu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cvprof_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT '0',
  `edtype` int(11) DEFAULT NULL,
  `qual_name` varchar(256) DEFAULT NULL,
  `school_name` varchar(256) DEFAULT NULL,
  `edu_country` int(11) DEFAULT NULL,
  `location` varchar(56) DEFAULT NULL,
  `ed_year` date DEFAULT '0000-00-00',
  `highest` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__jobboard_past_edu` */

/*Table structure for table `#__jobboard_past_employers` */

CREATE TABLE IF NOT EXISTS `#__jobboard_past_employers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cvprof_id` int(11) DEFAULT '0',
  `user_id` int(11) DEFAULT NULL,
  `job_title` varchar(64) DEFAULT NULL,
  `company_name` varchar(64) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `location` varchar(64) DEFAULT NULL,
  `start_yr` date DEFAULT '0000-00-00',
  `end_yr` date DEFAULT '0000-00-00',
  `most_recent` tinyint(1) DEFAULT '0',
  `current` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__jobboard_past_employers` */

/*Table structure for table `#__jobboard_questionnaires` */

CREATE TABLE IF NOT EXISTS `#__jobboard_questionnaires` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `qid` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT '42',
  `params` text,
  `name` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `layout` text,
  `fields` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__jobboard_questionnaires` */

/*Table structure for table `#__jobboard_sched_tasks` */

CREATE TABLE IF NOT EXISTS `#__jobboard_sched_tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(96) DEFAULT NULL,
  `handle` varchar(32) DEFAULT NULL,
  `enabled` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `#__jobboard_sched_tasks` */

insert ignore  into `#__jobboard_sched_tasks`(`id`,`title`,`handle`,`enabled`) values (1,'COM_JOBBOARD_TASKS_DISABLE_EXPIRED','disable_exp',1);

/*Table structure for table `#__jobboard_statuses` */

CREATE TABLE IF NOT EXISTS `#__jobboard_statuses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status_description` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Data for the table `#__jobboard_statuses` */

insert ignore  into `#__jobboard_statuses`(`id`,`status_description`) values (1,'new'),(2,'screened'),(3,'interview scheduled'),(4,'interviewed'),(5,'shortlisted'),(6,'approved/placed'),(7,'rejected'),(8,'on hold');

/*Table structure for table `#__jobboard_types` */

CREATE TABLE IF NOT EXISTS `#__jobboard_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `#__jobboard_types` */

insert ignore  into `#__jobboard_types`(`id`,`type`) values (1,'Full Time'),(2,'Contract'),(3,'Part Time'),(4,'Internship'),(5,'Temp'),(6,'Other');

/*Table structure for table `#__jobboard_unsolicited` */

CREATE TABLE IF NOT EXISTS `#__jobboard_unsolicited` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `job_id` int(11) NOT NULL DEFAULT '0',
  `first_name` varchar(96) NOT NULL DEFAULT '',
  `last_name` varchar(96) NOT NULL DEFAULT '',
  `email` varchar(254) NOT NULL,
  `tel` varchar(32) NOT NULL,
  `title` varchar(96) NOT NULL DEFAULT '',
  `filename` varchar(254) NOT NULL DEFAULT '',
  `file_hash` varchar(254) NOT NULL DEFAULT '',
  `cover_note` text NOT NULL,
  `status` int(3) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `#__jobboard_unsolicited` */

/*Table structure for table `#__jobboard_usercomms` */

CREATE TABLE IF NOT EXISTS `#__jobboard_usercomms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) DEFAULT NULL,
  `recipient_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT '0',
  `subject` text,
  `message` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__jobboard_usercomms` */

/*Table structure for table `#__jobboard_users` */

CREATE TABLE IF NOT EXISTS `#__jobboard_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) DEFAULT '5',
  `user_status` tinyint(4) NOT NULL DEFAULT '1',
  `feature_jobs` tinyint(1) NOT NULL DEFAULT '0',
  `contact_address` text,
  `contact_country` int(11) DEFAULT '0',
  `contact_location` varchar(48) DEFAULT NULL,
  `contact_zip` varchar(11) DEFAULT NULL,
  `contact_phone_1` varchar(32) DEFAULT NULL,
  `contact_phone_2` varchar(32) DEFAULT NULL,
  `contact_fax` varchar(32) DEFAULT NULL,
  `website_url` varchar(64) DEFAULT NULL,
  `twitter_url` varchar(64) DEFAULT NULL,
  `facebook_url` varchar(64) DEFAULT NULL,
  `linkedin_url` varchar(64) DEFAULT NULL,
  `is_authorised_linkedin` tinyint(1) DEFAULT '0',
  `profile_image_present` tinyint(1) unsigned zerofill DEFAULT '0',
  `profile_image_path` text,
  `profile_image_name` text,
  `user_key` varchar(36) DEFAULT NULL,
  `user_secret` varchar(36) DEFAULT NULL,
  `send_notifications` tinyint(1) DEFAULT '1',
  `notify_on_appl_accept` tinyint(1) DEFAULT '1',
  `notify_on_appl_reject` tinyint(1) DEFAULT '1',
  `notify_on_appl_update` tinyint(1) DEFAULT '1',
  `login_dashboard` tinyint(1) DEFAULT '0',
  `email_latest_jobs` tinyint(1) DEFAULT '1',
  `email_invites` tinyint(1) DEFAULT '1',
  `show_modeswitch` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__jobboard_users` */

/*Table structure for table `#__jobboard_userskills` */

CREATE TABLE IF NOT EXISTS `#__jobboard_userskills` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `skill_name` varchar(36) DEFAULT NULL,
  `profile_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `last_use` date DEFAULT '0000-00-00',
  `experience_period` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#__jobboard_userskills` */

/*Table structure for table `#__jobboard_usr_applications` */

CREATE TABLE IF NOT EXISTS `#__jobboard_usr_applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `job_id` int(11) DEFAULT NULL,
  `cvprof_id` int(11) DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL,
  `qid` int(11) DEFAULT '0',
  `modified_by` int(11) DEFAULT NULL,
  `applied_on` datetime DEFAULT '0000-00-00 00:00:00',
  `last_modified` datetime DEFAULT '0000-00-00 00:00:00',
  `admin_notes` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

/*Table structure for table `#__jobboard_usr_groups` */

CREATE TABLE IF NOT EXISTS `#__jobboard_usr_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(128) DEFAULT NULL,
  `post_jobs` tinyint(1) DEFAULT '0',
  `manage_jobs` tinyint(1) DEFAULT '0',
  `apply_to_jobs` tinyint(1) DEFAULT '1',
  `manage_applicants` tinyint(1) DEFAULT '0',
  `search_cvs` tinyint(1) DEFAULT '0',
  `search_private_cvs` tinyint(1) DEFAULT '0',
  `create_questionnaires` tinyint(1) DEFAULT '0',
  `manage_questionnaires` tinyint(1) DEFAULT '0',
  `manage_departments` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `#__jobboard_usr_groups` */

insert ignore  into `#__jobboard_usr_groups`(`id`,`group_name`,`post_jobs`,`manage_jobs`,`apply_to_jobs`,`manage_applicants`,`search_cvs`,`search_private_cvs`,`create_questionnaires`,`manage_questionnaires`,`manage_departments`) values (1,'Admins',1,1,1,1,1,1,1,1,1),(2,'Employers',1,1,1,1,1,0,1,1,0),(3,'Job posters',0,1,1,0,0,0,0,0,0),(4,'Applicant managers',0,0,1,1,1,0,0,0,0),(5,'Jobseekers',0,0,1,0,0,0,0,0,0),(6,'Custom group',0,0,0,0,0,0,0,0,0);