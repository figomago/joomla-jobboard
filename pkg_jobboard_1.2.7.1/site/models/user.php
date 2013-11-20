<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

   // Protect from unauthorized access
   defined('_JEXEC') or die('Restricted Access');

   class JobboardModelUser extends JModel
   {

       var $_id;
       var $_result;
       var $_db;
       var $_query;
       var $_sql;
       var $_countries;
       var $_config;
       var $_edlevels;

     /**
     * Constructor, builds object and determines the Address ID
     *
     */
       function __construct()
       {
         parent :: __construct();
       }

     /**
     * Gets country list
     *
     * @return object
     */
      function getCountries() {
            $db =& $this->getDBO();
  		  $query = 'SELECT `country_id`, `country_name` FROM #__jobboard_countries';
            $db->setQuery($query);
            $this->_countries = $db->loadObjectList();
  	      return $this->_countries;
      }

     /**
     * Gets config for Step1 of add/edit cv/resume
     *
     * @return object
     */
      function getAddProfileStepOnecfg() {
             $db = & $this->getDBO();
             $sql = 'SELECT `id`, `default_country`, `max_filesize`, `max_files` FROM #__jobboard_config
                        WHERE `id` = 1';
             $db->setQuery($sql);
             $this->_config = $db->loadObject();
             return $this->_config;
      }

     /**
     * Get data for Step1 of add/edit cv/resume
     * @params cv profile id
     *
     * @return Associative array
     */
      function getEditProfileStepOnedata($pid) {
             $db = & $this->getDBO();
             $sql = 'SELECT `id`, `profile_name`, `avail_date`, `job_type`, `file_uploads`, `is_linkedin`, `is_private` FROM #__jobboard_cvprofiles
                        WHERE `id` = '.$pid;
             $db->setQuery($sql);
             return $db->loadAssoc();
      }

     /**
     * Get cv/resume profiles for user
     * @params user id
     *
     * @return object
     */
      function getCvProfiles($uid) {
             $db = & $this->getDBO();
             $sql = 'SELECT `id`, `user_id`, `profile_name`, `created_date`, `modified_date`, `avail_date`, `is_linkedin`
                          FROM #__jobboard_cvprofiles
                          WHERE `user_id` = '.$uid.' ORDER BY `id` DESC';
             $db->setQuery($sql);
             return $db->loadObjectList();
      }

     /**
     * Get minimal cv/resume profiles for user
     * @params user id
     *
     * @return object
     */
      function getMinCvProfiles($uid) {
             $db = & $this->getDBO();
             $sql = 'SELECT `id`, `profile_name`
                          FROM #__jobboard_cvprofiles
                          WHERE `user_id` = '.$uid.' ORDER BY `id` DESC';
             $db->setQuery($sql);
             return $db->loadObjectList();
      }

     /**
     * Get all data for a cv/resume profile for user
     * @params cv/resume profile id, user id, extended info, exclude filepath , exclude file data
     *
     * @return object
     */
    function getCvProfile($pid, $uid, $extended=false, $exclpath=false, $exclfiles=false) {
           $db = & $this->getDBO();
           $sql = 'SELECT `id`, `user_id`, `profile_name`, `avail_date`, `summary`, `is_linkedin`
                        FROM #__jobboard_cvprofiles
                        WHERE `id` = '.$pid.' AND `user_id` = '.$uid;
           $db->setQuery($sql);
           $profile = $db->loadObject();
           if($extended == true) {
             $profile->education = $this->getCvProfileEduHistory($pid, $uid);
             $profile->employers = $this->getCvProfileEmplHistory($pid, $uid);
             $profile->skills = $this->getCvProfileSkills($pid, $uid);
             if($exclfiles <> true)
                $profile->files = $this->getCvProfileFiles($pid, $uid, $exclpath);
           }
           return $profile;
    }

     /**
     * Get cv/resume title
     * @params cv/resume profile id, user id
     *
     * @return string
     */
    function getCvProfileName($pid, $uid) {
           $db = & $this->getDBO();
           $sql = 'SELECT '.$db->nameQuote('profile_name').'
                        FROM '.$db->nameQuote('#__jobboard_cvprofiles').'
                        WHERE '.$db->nameQuote('id').' = '.$pid.' AND '.$db->nameQuote('user_id').' = '.$uid;
           $db->setQuery($sql);
           return $db->loadResult();
    }

     /**
     * Get past education data for a cv/resume profile for user
     * @params cv/resume profile id, user id
     *
     * @return object
     */
      function getCvProfileEduHistory($pid, $uid) {
             $db = & $this->getDBO();
             $sql = 'SELECT e.`id`, e.`edtype`, e.`qual_name`, e.`school_name`, e.`edu_country`,
                            e.`location`, e.`ed_year`, e.`highest`, c.`country_name`
                          FROM #__jobboard_countries AS c
                          INNER JOIN #__jobboard_past_edu as e
                          ON (e.`edu_country` = c.`country_id`)
                          WHERE e.`cvprof_id` = '.$pid.' AND e.`user_id` = '.$uid.' ORDER BY e.`highest` DESC, e.`ed_year` DESC';
             $db->setQuery($sql);
             return $db->loadObjectList();
      }

     /**
     * Insert education record asociated with a cv/resume profile for user
     * @params cv/resume profile id, user id, data
     *
     * @return boolean
     */
      function insertCvProfileEdu($pid, $uid, $data) {
             $db = & $this->getDBO();
             $sql = 'INSERT INTO #__jobboard_past_edu (`cvprof_id` , `user_id` , `edtype` , `qual_name`
                            , `school_name`, `edu_country` , `location` , `ed_year` , `highest`)
                          VALUES ('.$pid.'
                            , '.$uid.'
                            , '.$data["edtype"].'
                            , '.$db->Quote($db->getEscaped($data["qual_name"], true)).'
                            , '.$db->Quote($db->getEscaped($data["school_name"], true)).'
                            , '.$data["edu_country"].'
                            , '.$db->Quote($db->getEscaped($data["location"], true)).'
                            , '.$db->Quote($db->getEscaped($data["ed_year"], true)).'
                            , '.$data["highest"].')';
             $db->setQuery($sql);
             return $db->Query();
      }

     /**
     * Update education record asociated with a cv/resume profile for user
     * @params id, cv/resume profile id, user id, data
     *
     * @return boolean
     */
      function updCvProfileEdu($id, $pid, $uid, $data) {
             $db = & $this->getDBO();
             $sql = 'UPDATE #__jobboard_past_edu
                          SET `edtype` = '.$data["edtype"].', `qual_name` = '.$db->Quote($data["qual_name"]).', `school_name` = '.$db->Quote($data["school_name"]).'
                          , `edu_country` = '.$data["country_id"].', `location` = '.$db->Quote($data["location"]).'
                          , `ed_year` = '.$db->Quote($data["ed_yr"]).', `highest` = '.$data["highest"].'
                          WHERE `id` = '.$id.' AND `cvprof_id` = '.$pid.' AND `user_id` = '.$uid;
             $db->setQuery($sql);
             return $db->Query();
      }

     /**
     * Delete education record asociated with a cv/resume profile for user
     * @params id, cv/resume profile id, user id
     *
     * @return boolean
     */
      function delCvProfileEdu($id, $pid, $uid) {
             $db = & $this->getDBO();
             $sql = 'DELETE FROM #__jobboard_past_edu
                          WHERE `id` = '.$id.' AND `cvprof_id` = '.$pid.' AND `user_id` = '.$uid;
             $db->setQuery($sql);
             return $db->Query();
      }

     /**
     * Delete all education records asociated with a cv/resume profile for user
     * @params cv/resume profile id, user id
     *
     * @return boolean
     */
      function delCvProfileEdus($pid, $uid) {
             $db = & $this->getDBO();
             $sql = 'DELETE FROM #__jobboard_past_edu
                          WHERE `cvprof_id` = '.$pid.' AND `user_id` = '.$uid;
             $db->setQuery($sql);
             return $db->Query();
      }

     /**
     * Get skills data for a cv/resume profile for user
     * @params cv/resume profile id, user id
     *
     * @return object
     */
      function getCvProfileSkills($pid, $uid) {
             $db = & $this->getDBO();
             $sql = 'SELECT `id`, `skill_name`, `last_use`, `experience_period`
                          FROM #__jobboard_userskills
                          WHERE `profile_id` = '.$pid.' AND `user_id` = '.$uid.' ORDER BY `last_use` DESC';
             $db->setQuery($sql);
             return $db->loadObjectList();
      }

     /**
     * Insert skill record asociated with a cv/resume profile for user
     * @params cv/resume profile id, user id, data
     *
     * @return boolean
     */
      function insertCvProfileSkill($pid, $uid, $data) {
             $db = & $this->getDBO();
             $sql = 'INSERT INTO #__jobboard_userskills (`profile_id`, `user_id`, `skill_name`,
                            `last_use`, `experience_period`)
                          VALUES ('.$pid.', '.$uid.', '.$db->Quote($data["skill_name"]).',
                              '.$data["last_use"].', '.$data["experience_period"].');';
             $db->setQuery($sql);
             return $db->Query();
      }

     /**
     * Update skill record asociated with a cv/resume profile for user
     * @params id, cv/resume profile id, user id, data
     *
     * @return object
     */
      function updCvProfileSkill($id, $pid, $uid, $data) {
             $db = & $this->getDBO();
             $sql = 'UPDATE #__jobboard_userskills
                          SET `skill_name` = '.$db->Quote($db->getEscaped($data["skill_name"], true)).'
                          ,`last_use` = '.$db->Quote($data["last_use"]).', `experience_period` = '.$data["experience_period"].'
                          WHERE `id` = '.$id.' AND `profile_id` = '.$pid.' AND `user_id` = '.$uid;
             $db->setQuery($sql);
             return $db->Query();
      }

     /**
     * Delete skill record asociated with a cv/resume profile for user
     * @params id, cv/resume profile id, user id
     *
     * @return boolean
     */
      function delCvProfileSkill($id, $pid, $uid) {
             $db = & $this->getDBO();
             $sql = 'DELETE FROM #__jobboard_userskills
                          WHERE `id` = '.$id.' AND `profile_id` = '.$pid.' AND `user_id` = '.$uid;
             $db->setQuery($sql);
             return $db->Query();
      }

     /**
     * Delete all skills records asociated with a cv/resume profile for user
     * @params cv profile id, user id
     *
     * @return boolean
     */
      function delCvProfileSkills($pid, $uid) {
             $db = & $this->getDBO();
             $sql = 'DELETE FROM #__jobboard_userskills
                          WHERE `profile_id` = '.$pid.' AND `user_id` = '.$uid;
             $db->setQuery($sql);
             return $db->Query();
      }

     /**
     * Get past employment data for a cv/resume profile for user
     * @params cv/resume profile id, user id
     *
     * @return object
     */
      function getCvProfileEmplHistory($pid, $uid) {
             $db = & $this->getDBO();
             $sql = 'SELECT e.`id`, e.`job_title`, e.`company_name`, e.`country_id`, e.`location`, e.`start_yr`,
                            e.`end_yr`, e.`most_recent`, e.`current`, c.`country_name`
                          FROM #__jobboard_countries AS c
                          INNER JOIN #__jobboard_past_employers AS e
			              ON (e.`country_id` = c.`country_id`)
                          WHERE e.`cvprof_id` = '.$pid.' AND e.`user_id` = '.$uid.' ORDER BY e.`current` DESC, e.`most_recent` DESC, e.`id`';
             $db->setQuery($sql);
             return $db->loadObjectList();
      }

     /**
     * Insert employment record asociated with a cv/resume profile for user
     * @params id, cv/resume profile id, user id, data
     *
     * @return boolean
     */
      function insertCvProfileEmployer($pid, $uid, $data) {
             $db = & $this->getDBO();
             $sql = 'INSERT INTO #__jobboard_past_employers (`cvprof_id`, `user_id`, `job_title`, `company_name`,
                            `country_id`, `location`, `start_yr`, `end_yr`, `most_recent`, `current`)
                          VALUES ('.$pid.', '.$uid.', '.$db->Quote($db->getEscaped($data["job_title"], true)).', '.$db->Quote($db->getEscaped($data["company_name"], true)).',
                             '.$data["country_id"].', '.$db->Quote($db->getEscaped($data["location"], true)).', '.$db->Quote($db->getEscaped($data["start_yr"], true)).',
                             '.$db->Quote($db->getEscaped($data["end_yr"], true)).', '.$data["most_recent"].', '.$data["current"].');';
             $db->setQuery($sql);
             return $db->Query();
      }

     /**
     * Update employment record asociated with a cv/resume profile for user
     * @params id, cv/resume profile id, user id, data
     *
     * @return object
     */
      function updCvProfileEmployer($id, $pid, $uid, $data) {
             $db = & $this->getDBO();
             $sql = 'UPDATE #__jobboard_past_employers
                          SET `job_title` = '.$db->Quote($data["job_title"]).', `company_name` = '.$db->Quote($data["company_name"]).'
                          ,`country_id` = '.$data["country_id"].', `location` = '.$db->Quote($data["location"]).'
                          , `start_yr` = '.$db->Quote($data["start_yr"]).', `end_yr` = '.$db->Quote($data["end_yr"]).'
                          ,`most_recent` = '.$data["most_recent"].',`current` = '.$data["current"].'
                          WHERE `id` = '.$id.' AND `cvprof_id` = '.$pid.' AND `user_id` = '.$uid;
             $db->setQuery($sql);
             return $db->Query();
      }

     /**
     * Delete employment record asociated with a cv/resume profile for user
     * @params id, cv/resume profile id, user id
     *
     * @return boolean
     */
      function delCvProfileEmployer($id, $pid, $uid) {
             $db = & $this->getDBO();
             $sql = 'DELETE FROM #__jobboard_past_employers
                          WHERE `id` = '.$id.' AND `cvprof_id` = '.$pid.' AND `user_id` = '.$uid;
             $db->setQuery($sql);
             return $db->Query();
      }

     /**
     * Delete all employment records asociated with a cv/resume profile for user
     * @params cv/resume profile id, user id
     *
     * @return boolean
     */
      function delCvProfileEmployers($pid, $uid) {
             $db = & $this->getDBO();
             $sql = 'DELETE FROM #__jobboard_past_employers
                          WHERE `cvprof_id` = '.$pid.' AND `user_id` = '.$uid;
             $db->setQuery($sql);
             return $db->Query();
      }

     /**
     * Get summary for a cv/resume profile for user
     * @params cv/resume profile id, user id
     *
     * @return object
     */
      function getCvProfileSummary($pid, $uid) {
             $db = & $this->getDBO();
             $sql = 'SELECT `id`, `summary`
                          FROM #__jobboard_cvprofiles
                          WHERE `id` = '.$pid.' AND `user_id` = '.$uid;
             $db->setQuery($sql);
             return $db->loadObject();
      }

     /**
     * Update summary for a cv/resume profile for user
     * @params cv/resume profile id, user id
     *
     * @return boolean
     */
      function updCvProfileSummary($pid, $uid, $summary) {
             $db = & $this->getDBO();
             $sql = 'UPDATE #__jobboard_cvprofiles SET `summary` = '.$db->Quote($summary).'
                          WHERE `id` = '.$pid.' AND `user_id` = '.$uid;
             $db->setQuery($sql);
             return $db->Query();
      }

     /**
     * Get default configuration for file uploads
     * @params none
     *
     * @return associative array
     */
      function getFileUploadCfg() {
             $db = & $this->getDBO();
             $sql = 'SELECT '.$db->nameQuote('id').', '.$db->nameQuote('max_filesize').', '.$db->nameQuote('max_files').'
                          FROM '.$db->nameQuote('#__jobboard_config').'
                          WHERE '.$db->nameQuote('id').' = 1';
             $db->setQuery($sql);
             return $db->loadAssoc();
      }

     /**
     * Save file info for a file upload (CV profile)
     * @params userid, profileid, file name, title, path and type
     *
     * @return boolean
     */
      function saveCvFile($uid, $pid, $file, $filepath, $cloning=false) {
             $db = & $this->getDBO();
             if($cloning == true) {
               $sql = 'INSERT INTO #__jobboard_file_uploads (`create_date`, `cvprof_id`, `user_id`, `filetitle`,
                              `filepath`, `filename`, `filetype`, `filesize`)
                            VALUES (UTC_TIMESTAMP, '.$pid.', '.$uid.', '.$db->Quote($file["filetitle"]).',
                               '.$db->Quote($filepath).', '.$db->Quote($file["filename"]).',
                               '.$db->Quote($file["filetype"]).', '.$file["filesize"].')';
             } else {
               $sql = 'INSERT INTO #__jobboard_file_uploads (`create_date`, `cvprof_id`, `user_id`, `filetitle`,
                              `filepath`, `filename`, `filetype`, `filesize`)
                            VALUES (UTC_TIMESTAMP, '.$pid.', '.$uid.', '.$db->Quote($file["title"]).',
                               '.$db->Quote($filepath).', '.$db->Quote($file["name"]).',
                               '.$db->Quote($file["type"]).', '.$file["size"].')';
             }
             $db->setQuery($sql);
             return $db->Query();
      }

     /**
     * Get file info for files associated with a CV/Resume profile
     * @params cv/resume profile id, user id, exclude filepath
     *
     * @return object
     */
      function getCvProfileFiles($pid, $uid, $exclpath=false) {
             $db = & $this->getDBO();
             if($exclpath == false ) {
               $sql = 'SELECT `id`, `cvprof_id`, `create_date`, `filetitle`, `filepath`, `filename`, `filetype`, `filesize` ';
             }  else {
               $sql = 'SELECT `id`, `cvprof_id`, `create_date`, `filetitle`, `filename`, `filetype`, `filesize` ';
             }
             $sql .= ' FROM `#__jobboard_file_uploads`
                            WHERE `cvprof_id` = '.$pid.' AND `user_id` = '.$uid.' ORDER BY `id` DESC';
             $db->setQuery($sql);
             return $db->loadObjectList();
      }

     /**
     * Get file associated with a CV/Resume profile
     * @params file id, cv/resume profile id, user id
     *
     * @return object
     */
      function getCvFile($fid, $pid, $uid=0) {
             $db = & $this->getDBO();
             switch($uid) {
               case 0 :
                    $sql = 'SELECT `id`, `filetitle`, `filepath`, `filename`, `filetype`
                          FROM `#__jobboard_file_uploads`
                          WHERE `id` = '.$fid.' AND `cvprof_id` = '.$pid;
               break;
               default:
                    $sql = 'SELECT `filetitle`, `filepath`, `filename`
                          FROM `#__jobboard_file_uploads`
                          WHERE `id` = '.$fid.' AND `cvprof_id` = '.$pid.' AND `user_id` = '.$uid;
               ;break;
             }

             $db->setQuery($sql);
             return $db->loadObject();
      }

     /**
     * Get file info by passing assigned token
     * @params file id, token
     *
     * @return object
     */
      function getCvFileByToken($fid, $token) {
             $db = & $this->getDBO();
                    $sql = 'SELECT f.`filepath`, f.`filename`, f.`filetype`
                          FROM `#__jobboard_file_tokens` AS t
                          INNER JOIN `#__jobboard_file_uploads` AS f
                          WHERE f.`id` = '.$fid.' AND t.`token` = '.$db->Quote($token);
             $db->setQuery($sql);
             return $db->loadObject();
      }

     /**
     * Get info for a token
     * @params token
     *
     * @return Associative array
     */
      function getTokenInfo($token) {
             $db = & $this->getDBO();
                    $sql = 'SELECT `id`, `file_id`, `expires`, `max_use`, `hits`
                          FROM `#__jobboard_file_tokens`
                          WHERE `token` = '.$db->Quote($token);
             $db->setQuery($sql);
             return $db->loadAssoc();
      }

     /**
     * Delete file associated with a CV/Resume profile
     * @params file id, cv/resume profile id, user id
     *
     * @return boolean
     */
      function delCvFile($fid, $pid, $uid) {
             $db = & $this->getDBO();
             $sql = 'DELETE FROM `#__jobboard_file_uploads`
                     WHERE `id` = '.$fid.' AND `cvprof_id` = '.$pid.' AND `user_id` = '.$uid;
             $db->setQuery($sql);
             return $db->Query();
      }

     /**
     * Save primary data for a cv/resume profile for user
     * return 0 if an error occured or
     * return the id of the newly inserted/updated record
     * @params cv/resume profile id, user id
     *
     * @return int
     */
     function saveCvProfile($data, $pid, $uid, $is_linkedin=0) {
             $db = & $this->getDBO();
             if($pid == 0) {
               $sql = 'INSERT INTO #__jobboard_cvprofiles (`created_date`, `profile_name`, `user_id`,
                           `modified_date`, `job_type`, `file_uploads`, `avail_date`, `is_linkedin`, `is_private`)
                          VALUES (UTC_TIMESTAMP, '.$db->Quote($data["profile_name"]).', '.$uid.', UTC_TIMESTAMP,
                            '.$db->Quote($data["job_type"]).', '.$data["file_count"].', '.$db->Quote($data["avail_date"]).', '.$is_linkedin.', '.$data["is_private"].');';
             } elseif($pid > 0){
               $sql = 'UPDATE #__jobboard_cvprofiles SET `profile_name` = '.$db->Quote($data["profile_name"]).', `user_id` = '.$uid.',
                           `modified_date` = UTC_TIMESTAMP, `job_type` = '.$db->Quote($data["job_type"]).', `file_uploads` = '.$data["file_count"].',
                           `avail_date` = '.$db->Quote($data["avail_date"]).', `is_private` = '.$data["is_private"].'
                          WHERE `id` = '.$pid.' AND `user_id` = '.$uid;
             } else return 0;
             $db->setQuery($sql);
             $result = $db->Query();
             return ($pid == 0)? $db->insertid() : $pid;
     }

     /**
     * Delete a cv/resume profile for user
     * @params cv/resume profile id, user id
     *
     * @return boolean
     */
     function delCvProfile($uid, $pid) {
             $db = & $this->getDBO();
             $sql = 'DELETE FROM #__jobboard_cvprofiles
                      WHERE `id` = '.$pid.' AND `user_id` = '.$uid;
             $db->setQuery($sql);
             return $db->Query();
     }

     /**
     * Get config for Step2 of add/edit cv/resume
     * @params cv profile id
     *
     * @return object
     */
      function getAddProfileStepTwocfg() {
             $db = & $this->getDBO();
             $sql = 'SELECT `id`, `default_country`, `max_quals`, `max_employers` FROM #__jobboard_config
                        WHERE `id` = 1';
             $db->setQuery($sql);
             $this->_config = $db->loadObject();
             return $this->_config;
      }

     /**
     * Get config for Step3 of add/edit cv/resume
     * @params cv profile id
     *
     * @return object
     */
      function getAddProfileStepThreecfg() {
             $db = & $this->getDBO();
             $sql = 'SELECT `id`, `default_country`, `max_skills` FROM #__jobboard_config
                        WHERE `id` = 1';
             $db->setQuery($sql);
             $this->_config = $db->loadObject();
             return $this->_config;
      }

     /**
     * Get education levels
     * @params none
     *
     * @return object
     */
     function getEdlevels() {
             $db = & $this->getDBO();
             $sql = 'SELECT * FROM #__jobboard_education';
             $db->setQuery($sql);
             $this->_edlevels = $db->loadObjectList();
             return $this->_edlevels;
     }

     /**
     * Get config for user profile edit
     *
     * @return object
     */
     function getProfileEditOnecfg() {
             $db = & $this->getDBO();
             $sql = 'SELECT `id`, `default_country`, `default_city`, `max_skills` FROM #__jobboard_config
                        WHERE `id` = 1';
             $db->setQuery($sql);
             $this->_config = $db->loadObject();
             return $this->_config;
     }

     /**
     * Get data for Tab1 of user profile
     * @params user id, cv edit mode
     *
     * @return object
     */
     function getProfileDataOne($uid , $cv_edit_mode=false) {
             $db = & $this->getDBO();
             switch($cv_edit_mode){
               case false :
                 $sql = 'SELECT u.`id`, u.`user_status`, u.`contact_address`,
                          u.`contact_country`, u.`contact_location`, u.`contact_zip`,
                          u.`contact_phone_1`, u.`contact_phone_2`, u.`contact_fax`,
                          u.`website_url`, u.`twitter_url`, u.`facebook_url`, u.`linkedin_url`,
                          s.`name`, s.`email`, s.`username`
                        FROM #__jobboard_users AS u
                        INNER JOIN #__users AS s
                        ON (s.`id` = u.`user_id`)
                        WHERE s.`id` = '.$uid;
               break;
               case true :
                 $sql = 'SELECT u.`contact_address`, u.`contact_location`, u.`contact_zip`,
                          u.`contact_phone_1`, u.`contact_phone_2`, u.`contact_fax`,
                          u.`website_url`, u.`twitter_url`, u.`facebook_url`, u.`linkedin_url`,
                          s.`name`, s.`email`
                        FROM #__jobboard_users AS u
                        INNER JOIN #__users AS s
                        ON (s.`id` = u.`user_id`)
                        WHERE s.`id` = '.$uid;
               break;
             }
             $db->setQuery($sql);
             $result = $db->loadObject();
             return $result;
     }

     /**
     * Save data for Tab1 user profile
     * @params profile data, user id, user profile id
     *
     * @return boolean
     */
     function saveProfileDataOne($data, $uid, $pid=0) {
             $db = & $this->getDBO();
             if($pid== 0) {
                $sql = 'INSERT INTO #__jobboard_users (`user_id`, `contact_address`, `contact_country`, `contact_location`,
                                `contact_zip`, `contact_phone_1`, `contact_phone_2`, `contact_fax`, `website_url`,
                                `twitter_url`, `facebook_url`, `linkedin_url`)
                          VALUES ('.$uid.', '.$db->Quote($data["contact_address"]).', '.$data["contact_country"].', '.$db->Quote($data["contact_location"]).'),
                              '.$db->Quote($data["contact_zip"]).', '.$db->Quote($data["contact_phone_1"]).', '.$db->Quote($data["contact_phone_2"]).',
                              '.$db->Quote($data["contact_fax"]).',  '.$db->Quote($data["website_url"]).',  '.$db->Quote($data["twitter_url"]).',
                              '.$db->Quote($data["facebook_url"]).', '.$db->Quote($data["linkedin_url"]).';';
                 $db->setQuery($sql);
                 $result1 = $db->Query();
                 $sql = 'UPDATE #__users SET `name` = '.$db->Quote($data["name"]).', `email` = '.$db->Quote($data["email"]).'
                     WHERE '.$db->nameQuote('id').' = '.$uid;
                 $db->setQuery($sql);
                 $result2 = $db->Query();
                 $result = ($result1 == true && $result2 == true)? true : false;
             } elseif($pid > 0) {
                $sql = 'UPDATE #__jobboard_users SET `contact_address` = '.$db->Quote($data["contact_address"]).',
                      `contact_country` = '.$data["contact_country"].',
                      `contact_location` = '.$db->Quote($data["contact_location"]).',
                      `contact_zip` = '.$db->Quote($data["contact_zip"]).',
                      `contact_phone_1` = '.$db->Quote($data["contact_phone_1"]).',
                      `contact_phone_2` = '.$db->Quote($data["contact_phone_2"]).',
                      `contact_fax` = '.$db->Quote($data["contact_fax"]).',
                      `website_url` = '.$db->Quote($data["website_url"]).',
                      `twitter_url` = '.$db->Quote($data["twitter_url"]).',
                      `facebook_url` = '.$db->Quote($data["facebook_url"]).',
                      `linkedin_url` = '.$db->Quote($data["linkedin_url"]).'
                    WHERE `user_id` = '.$uid;
                 $db->setQuery($sql);
                 $result1 = $db->Query();
                 $sql = 'UPDATE #__users SET `name` = '.$db->Quote($data["name"]).', `email` = '.$db->Quote($data["email"]).'
                     WHERE '.$db->nameQuote('id').' = '.$uid;
                 $db->setQuery($sql);
                 $result2 = $db->Query();
                 $result = ($result1 == true && $result2 == true)? true : false;
             }
             return $result;
     }

     /**
     * Get data for Settings Tab of user profile
     * @param $uid user id
     *
     * @return assoc
     */
     function getProfileSettings($uid) {
             $db = & $this->getDBO();
             $sql = 'SELECT '.$db->nameQuote('user_status').', '.$db->nameQuote('feature_jobs').'
                      ,'.$db->nameQuote('notify_on_appl_accept').' AS accepted
                      ,'.$db->nameQuote('notify_on_appl_reject').' AS rejected
                      ,'.$db->nameQuote('email_invites').'
                      ,'.$db->nameQuote('login_dashboard').'
                      ,'.$db->nameQuote('show_modeswitch').'
                    FROM '.$db->nameQuote('#__jobboard_users').'
                    WHERE '.$db->nameQuote('user_id').' = '.$uid;
             $db->setQuery($sql);
             return $db->loadAssoc();
     }

     /**
     * Get job board user profile id
     * @param $uid user id
     *
     * @return int user profile id
     */
     function getUserRowId($uid) {
             $db = & $this->getDBO();
             $sql = 'SELECT '.$db->nameQuote('id').'
                    FROM '.$db->nameQuote('#__jobboard_users').'
                    WHERE '.$db->nameQuote('user_id').' = '.$uid;
             $db->setQuery($sql);
             return $db->loadResult();
     }

     /**
     * Check if job board user profile record exists
     * @params user id
     *
     * @return int user id or 0 for not found
     */
     function userProfileExists($user_id) {
             $db = & $this->getDBO();
             $sql = 'SELECT `id` FROM #__jobboard_users
                        WHERE `user_id` = '.$user_id;
             $db->setQuery($sql);
             $result = $db->loadResult();
             return (count($result) > 0)? $result : 0;
     }

     /**
     * Check if linkedin token is authorised by user
     * @params user id
     *
     * @return int
     */
     function isAuthLinkedin($user_id) {
             $db = & $this->getDBO();
             $sql = 'SELECT `is_authorised_linkedin` FROM #__jobboard_users
                        WHERE `user_id` = '.$user_id;
             $db->setQuery($sql);
             return $db->loadResult();
     }

     /**
     * Check if linkedin user profile record exists
     * @params user id, return boolean or profile id
     *
     * @return int user id or 0 for not found
     */
     function liProfileExists($user_id, $boolean=false) {
             $db = & $this->getDBO();
             $sql = 'SELECT  `id` FROM #__jobboard_cvprofiles
                        WHERE  `user_id` = '.$user_id.' AND  `is_linkedin` = 1';
             $db->setQuery($sql);
             $result = $db->loadResult();
             if($boolean == true) {
                return (count($result) > 0)? $result : 0;
             } else return $result;
     }

     /**
     * Update user profile for linkedin user profile record
     * @params user id
     *
     * @return boolean
     */
     function updLiProfileImportStat($user_id, $status) {
             $db = & $this->getDBO();
             $sql = 'UPDATE #__jobboard_users
                        SET `is_authorised_linkedin` = '.$status.'
                        WHERE `id` = '.$user_id;
             $db->setQuery($sql);
             return $db->Query();
     }

     /**
     * Create user profile with minimal data
     * @params user id
     *
     * @return boolean
     */
     function createMinUserProfile($user_id, $key, $secret) {
             $db = & $this->getDBO();
             $sql = 'INSERT INTO #__jobboard_users (`user_id`, `user_key`, `user_secret`)
                        VALUES ('.$user_id.', '.$db->Quote($key).',  '.$db->Quote($secret).');';
             $db->setQuery($sql);
             return $db->Query();
     }

     /**
     * Save user image data for user profile
     * @params user profile id, user id, image path, image name
     *
     * @return boolean
     */
     function saveProfileImage($id, $uid, $path, $name) {
             $db = & $this->getDBO();
             if($id == 0) {
               $sql = 'INSERT INTO #__jobboard_users (`user_id`, `profile_image_path`, `profile_image_name`, `profile_image_present`)
                      VALUES ('.$uid.', '.$db->Quote($path).', '.$db->Quote($name).', 1)';
             } else{
               $sql = 'UPDATE #__jobboard_users SET `profile_image_path` = '.$db->Quote($path).', `profile_image_name` = '.$db->Quote($name).', `profile_image_present` = 1
                      WHERE `id` = '.$id.' AND `user_id` = '.$uid;
             }
             $db->setQuery($sql);
             return $db->Query();
     }

     /**
     * Get default layout setting
     * @params none
     *
     * @return integer
     */
     function getLayoutConfig() {
             $db = & $this->getDBO();
             $sql = 'SELECT `default_col_layout` FROM #__jobboard_config
                        WHERE `id` = 1';
             $db->setQuery($sql);
             return $db->loadResult();
     }


     /**
     * Get profile image data by user id
     * @params user id
     *
     * @return object
     */
     function getProfileImageByUserId($uid) {
             $db = & $this->getDBO();
             $sql = 'SELECT `id`, `profile_image_path`, `profile_image_name`, `profile_image_present` FROM #__jobboard_users
                        WHERE `user_id` = '.$uid;
             $db->setQuery($sql);
             $result = $db->loadObject();
             return $result;
     }

     /**
     * Get profile image data by row id
     * @params user profile id
     *
     * @return object
     */
     function getProfileImageById($pid) {
             $db = & $this->getDBO();
             $sql = 'SELECT `id`, `profile_image_path`, `profile_image_name`, `profile_image_present` FROM #__jobboard_users
                        WHERE `id` = '.$pid;
             $db->setQuery($sql);
             $result = $db->loadObject();
             return $result;
     }

     /**
     * Delete image data for user profile
     * @params user id, cv profile id
     *
     * @return boolean
     */
     function delProfileImage($uid, $pid) {
             $db = & $this->getDBO();
             $sql = 'UPDATE #__jobboard_users SET `profile_image_path` = "", `profile_image_name` = "", `profile_image_present` = 0
                      WHERE `id` = '.$pid.' AND `user_id` = '.$uid;
             $db->setQuery($sql);
             return $db->Query();
     }

     /**
     * Select bookmark data for user
     * @params user id
     *
     * @return array
     */
     function getBookmarks($uid) {
             $db = & $this->getDBO();
             $sql = 'SELECT   b.*
                    	, j.`job_title`
                    	, j.`post_date`
                        , j.`expiry_date`
                        , j.`job_tags`
                        , j.`category`
                     FROM `#__jobboard_jobs` AS j
                     INNER JOIN `#__jobboard_bookmarks` AS b
                     ON (b.`job_id` = j.`id`)
                     WHERE b.`user_id` = '.$uid.' ORDER BY b.`id` DESC';
             $db->setQuery($sql);
             return $db->loadAssocList();
     }

     /**
     * Check if a bookmark exists for user on a job listing
     * @params job id, user id
     *
     * @return integer
     */
     function hasBookmark($jid, $uid) {
             $db = & $this->getDBO();
             $sql = 'SELECT `id` FROM `#__jobboard_bookmarks`
                     WHERE `user_id` = '.$uid.' AND `job_id` = '.$jid;
             $db->setQuery($sql);
             return $db->loadResult();
     }

     /**
     * Save a bookmark for user
     * @params job id, user id
     *
     * @return boolean
     */
     function saveBookmark($jid, $uid) {
             $db = & $this->getDBO();
             $sql = 'INSERT INTO `#__jobboard_bookmarks` (`user_id`, `job_id`, `mark_date`)
                     VALUES ('.$uid.', '.$jid.', UTC_TIMESTAMP)';
             $db->setQuery($sql);
             return $db->Query();
     }

     /**
     * Delete a bookmark for user
     * @params bookmark id, user id
     *
     * @return boolean
     */
     function delBookmark($bid, $uid) {
             $db = & $this->getDBO();
             $sql = 'DELETE FROM  `#__jobboard_bookmarks`
                     WHERE `user_id` = '.$uid.' AND `id` = '.$bid;
             $db->setQuery($sql);
             return $db->Query();
     }

     /**
     * Get credentials for user
     * @params user id
     *
     * @return assoc
     */
     function getUsrCred($uid) {
             $db = & $this->getDBO();
             $sql = 'SELECT `user_type`, `user_status`  FROM  `#__jobboard_users`
                     WHERE `user_id` = '.$uid;
             $db->setQuery($sql);
             return $db->loadAssoc();
     }

     /**
     * Check if user has already applied for a post
     * @params user id , job id
     *
     * @return int
     */
     function getJobApplicationStatus($uid, $jid) {
             $db = & $this->getDBO();
             $sql = 'SELECT COUNT(`id`)  FROM  `#__jobboard_usr_applications`
                     WHERE `user_id` = '.$uid.' AND `job_id` = '.$jid;
             $db->setQuery($sql);
             return $db->loadResult();
     }

     /**
     * Get number of applications for a given user
     * @params user id
     *
     * @return int
     */
     function getNumApplications($uid) {
             $db = & $this->getDBO();
             $sql = 'SELECT COUNT(`id`)  FROM  `#__jobboard_usr_applications`
                     WHERE `user_id` = '.$uid.' AND `job_id` <> 0';
             $db->setQuery($sql);
             return $db->loadResult();
     }

     /**
     * Get marked jobs for a given user
     * @params user id
     *
     * @return int
     */
     function getMarkedJobs($uid) {
             $db = & $this->getDBO();
             $sql = 'SELECT COUNT(`id`)  FROM  `#__jobboard_bookmarks`
                     WHERE `user_id` = '.$uid;
             $db->setQuery($sql);
             return $db->loadResult();
     }

     /**
     * Get cv profile skills data for given user
     * @params user id
     *
     * @return array
     */
     function getCvSkills($uid) {      
             $db = & $this->getDBO();
             $sql = 'SELECT DISTINCT LOWER(`skill_name`) FROM  `#__jobboard_userskills`
                     WHERE `user_id` = '.$uid;
             $db->setQuery($sql);
             return $db->loadResultArray();
     }

     /**
     * Get latest jobs for given keywords
     * @params keywords, limit
     *
     * @return assoc
     */
     function getJobsByKeywords($keywords, $limit=3) {
          if(!empty($keywords))  :
             $db = & $this->getDBO();
             $s_where = array();
             $alt_subkey = array();
             $alt_first_subkey = array();
             $keyword_segments = count($keywords);
             if($keyword_segments == 1) {
                    $s_where[] = " `job_tags` LIKE '%{$keywords[0]}%'";
             } elseif($keyword_segments > 1) {
                  $ks_count = 0;
                  foreach($keywords as $ks) {
                    if($ks_count > 0) {
                      $alt_subkey[] = " OR `job_tags` LIKE '%{$ks}%'";
                    } else {
                      $alt_subkey[] = " `job_tags` LIKE '%{$keywords[0]}%'";
                    }
                    $ks_count++;
                  }
                  $s_where[] = implode(' ',$alt_subkey)." ";
             } else $s_where[] =  " `job_tags` LIKE '%{$keywords[0]}%'";
             $where = ' '.implode(' + ',$s_where).' ';
             $sql = 'SELECT `id`, `job_title`, `job_tags` FROM `#__jobboard_jobs`
                     WHERE '.$where.' AND `published` = 1  ORDER BY `post_date` LIMIT '.$limit;
             $db->setQuery($sql);
             return $db->loadAssocList();
         else :
             return false;
         endif;
     }


     /**
     * Get summary of job applications for given user
     * @params user id, limit
     *
     * @return assoclist
     */
     function getApplicationsSummary($uid, $limit=3) {
             $db = & $this->getDBO();
             $sql = 'SELECT
                      a.`id`, a.`user_id`, a.`job_id`, a.`cvprof_id`,
                      j.`job_title`,
                      p.`profile_name`,
                      s.`status_description`
                    FROM `#__jobboard_usr_applications` AS a
                      INNER JOIN `#__jobboard_jobs` AS j
                      ON (j.`id` = a.`job_id`)
                      INNER JOIN `#__jobboard_cvprofiles` AS p
                      ON (p.`id` = a.`cvprof_id`)
                      INNER JOIN `#__jobboard_statuses` AS s
                      ON (s.`id` = a.`status_id`)
                     WHERE a.`user_id` = '.$uid.' AND j.`published` = 1 LIMIT '.$limit;
             $db->setQuery($sql);
             return $db->loadAssocList();
     }


     /**
     * Get profile hit count for a given user
     * @param int $uid user id
     * @param string $today date in '%Y-%m-%d' format
     *
     * @return int
     */
     function getProfileHits($uid, $today) {
             $db = & $this->getDBO();
             $sql = 'SELECT SUM(`hits`)  FROM  `#__jobboard_cvprofiles`
                     WHERE `user_id` = '.$uid;
             $db->setQuery($sql);
             $result = $db->loadResult();
             return isset($result)? $result : 0;
     }

     /**
     * Get Job title
     * @params job id
     *
     * @return string
     */
     function getJobTitle($jid) {
             $db = & $this->getDBO();
             $sql = 'SELECT `job_title`  FROM  `#__jobboard_jobs`
                     WHERE `id` = '.$jid;
             $db->setQuery($sql);
             return $db->loadResult();
     }

     /**
     * Get Jooomla name and email
     * @params user id
     *
     * @return assoc
     */
     function getJoomlaDetails($uid) {
             $db = & $this->getDBO();
             $sql = 'SELECT '.$db->nameQuote('name').', '.$db->nameQuote('email').'
             FROM  '.$db->nameQuote('#__users').'
                     WHERE '.$db->nameQuote('id').' = '.$uid;
             $db->setQuery($sql);
             return $db->loadAssoc();
     }


     /**
     * Update invite response
     * @params array
     *
     * @return boolean
     */
    function updateResponse($data) {
       $db = & $this->getDBO();
       $sql = 'UPDATE `#__jobboard_invites`
            SET `response` = 1
                WHERE `user_id` = '.$data['uid'].'
                 AND `job_id` = '.$data['jid'];
       $db->setQuery($sql);
       return $db->Query();
    }

     /**
     * Get invite count for user
     * @params user id
     *
     * @return int
     */
    function getNumInvites($uid) {
       $db = & $this->getDBO();
       $sql = 'SELECT COUNT(`id`) FROM `#__jobboard_invites`
                WHERE `user_id` = '.$uid;
       $db->setQuery($sql);
       return $db->loadResult();
    }

     /**
     * Check if user posted the job
     * @params user id, job id
     *
     * @return int
     */
    function isJobOwner($uid, $jid) {
       $db = & $this->getDBO();
       $sql = 'SELECT COUNT(`id`) FROM `#__jobboard_jobs`
                WHERE `posted_by` = '.$uid.' AND `id` = '.$jid;
       $db->setQuery($sql);
       return ($db->loadResult() > 0)? true : false;
    }

     /**
     * Get the LinkedIn API key and Secret
     * @param none
     *
     * @return assoc
     */
    function getLinkedinKey() {
       $db = & $this->getDBO();
       $sql = 'SELECT '.$db->nameQuote('allow_linkedin_imports').',
                '.$db->nameQuote('linkedin_key').',
                '.$db->nameQuote('linkedin_secret').'
            FROM '.$db->nameQuote('#__jobboard_config').'
                WHERE '.$db->nameQuote('id').' = 1';
       $db->setQuery($sql);
       return $db->loadAssoc();
    }

     /**
     * Check if LinkedIn imports are enabled
     * @param none
     *
     * @return assoc
     */
    function getLinkedinPerms() {
       $db = & $this->getDBO();
       $sql = 'SELECT '.$db->nameQuote('allow_linkedin_imports').'
            FROM '.$db->nameQuote('#__jobboard_config').'
                WHERE '.$db->nameQuote('id').' = 1';
       $db->setQuery($sql);
       return $db->loadResult();
    }
}

?>