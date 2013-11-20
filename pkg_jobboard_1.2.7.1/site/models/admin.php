<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

   // Protect from unauthorized access
   defined('_JEXEC') or die('Restricted Access');

   class JobboardModelAdmin extends JModel
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
     * Get general configuration
     * @params none
     *
     * @return object
     */
     function getGlobalConfig() {
             $db = & $this->getDBO();
             $sql = 'SELECT `use_location` FROM #__jobboard_config
                        WHERE `id` = 1';
             $db->setQuery($sql);
             return $db->loadObject();
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
     * Gets country list
     *
     * @return object
     */
      function getCountries() {
          $db =& $this->getDBO();
  		  $query = 'SELECT `country_id`, `country_name` FROM `#__jobboard_countries`';
          $db->setQuery($query);
          $this->_countries = $db->loadObjectList();
  	      return $this->_countries;
      }

    function getStatuses() {
        $db =& $this->getDBO();
		$sql = "SELECT * FROM `#__jobboard_statuses`";
        $db->setQuery($sql);
        return $db->loadObjectList();
    }

    function getDepartments() {
        $db =& $this->getDBO();
		$sql = "SELECT * FROM `#__jobboard_departments`";
        $db->setQuery($sql);
        return $db->loadObjectList();
    }

    function getCareers() {
          $db =& $this->getDBO();
		  $sql = 'SELECT * FROM `#__jobboard_career_levels`';
          $db->setQuery($sql);
	      return $db->loadObjectList();
    }

    function getEducation() {
          $db =& $this->getDBO();
		  $sql = 'SELECT * FROM `#__jobboard_education`';
          $db->setQuery($sql);
	      return $db->loadObjectList();
    }

    function getCategories() {
          $db =& $this->getDBO();
		  $sql = 'SELECT * FROM `#__jobboard_categories`';
          $db->setQuery($sql);
	      return $db->loadObjectList();
    }

     /**
     * Gets config for Step1 of add/edit cv/resume
     *
     * @return object
     */
      function getAddProfileStepOnecfg() {
             $db = & $this->getDBO();
             $sql = 'SELECT `default_country`, `max_filesize`, `max_files` FROM #__jobboard_config
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
             $sql = 'SELECT `profile_name`, `avail_date`, `job_type`, `file_uploads`, `is_linkedin` FROM #__jobboard_cvprofiles
                        WHERE `id` = '.$pid;
             $db->setQuery($sql);
             return $db->loadAssoc();
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
                    $sql = 'SELECT `filetitle`, `filepath`, `filename`, `filetype`
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
     * Get available questionnaires
     *
     * @param int $uid user id
     * @param boolean exclude fields
     *
     * @return assoc
     */
      function getQuestionnaires($uid=0, $min=false) {
             $db = & $this->getDBO();
             if($uid <> 0) $where = ' WHERE `created_by` = '.$uid;
             else $where = ' ';
             if($min == true)
               $sql = 'SELECT `id`, `qid`, `title`
                            FROM #__jobboard_questionnaires '.$where.'
                            ORDER BY `id` DESC';
             else
               $sql = 'SELECT `id`, `qid`, `title`, `fields`
                            FROM #__jobboard_questionnaires '.$where.'
                            ORDER BY `id` DESC';
             $db->setQuery($sql);  
             return $db->loadAssocList();
      }

     /**
     * Check if a table associated with a questionnaire exists
     * @params questionnaire id, column data
     *
     * @return boolean
     */
      function checkQuestionnaireTbl($qid, $column_name) {
             $db = & $this->getDBO();
             $sql = 'SHOW COLUMNS FROM `#__jobboard_q'.$qid.'` WHERE FIELD = '.$db->Quote($column_name);
             $db->setQuery($sql);
             $result = $db->loadAssocList();
             return isset($result[0])? true : false;
      }

     /**
     * Create a table associated with a questionnaire
     * @params questionnaire id
     *
     * @return boolean
     */
      function createQuestionnaireTbl($qid) {
             $db = & $this->getDBO();
             $sql = 'CREATE TABLE IF NOT EXISTS `#__jobboard_q'.$qid.'` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `uid` int(11) DEFAULT NULL,
                        `appl_id` int(11) DEFAULT NULL,
                        `job_creator_id` int(11) DEFAULT 0,
                        `creator_dept_id` int(11) DEFAULT 0,
                         PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
                    ';
             $db->setQuery($sql);
             return $db->Query();
      }

     /**
     * Update a table associated with a questionnaire
     * @params column data
     *
     * @return boolean
     */
      function updateQuestionnaireTbl($sql) {
             $db = & $this->getDBO();
             $db->setQuery($sql);
             return $db->Query();
      }

     /**
     * Save questionnaire
     *
     * @param $qid  int questionnaire id
     * @param $data assoc questionnaire data
     * @param $uid  int owner id
     *
     * @return boolean or int if inserting
     */
      function saveQuestionnaire($qid, $data, $uid=0) {
             $db = & $this->getDBO();
             if($qid < 1) {
                $sql = 'SELECT MAX(`qid`) FROM `#__jobboard_questionnaires`';
                $db->setQuery($sql);
                $maxqid = $db->loadResult();
                $maxqid++;

                $sub_t_exists = $this->checkQuestionnaireTbl($maxqid, 'id');

                if($sub_t_exists) $maxqid++;

                $sql = 'INSERT INTO `#__jobboard_questionnaires` (`qid`, `created_by`, `name`, `title`, `description`, `fields`)
                          VALUES ('.$maxqid.', '.$uid.', '.$db->Quote($data['name']).',  '.$db->Quote($data['title']).'
                                ,  '.$db->Quote($data['description']).',  '.$db->Quote($data['fields']).')';
                $db->setQuery($sql);
                $db->Query();
                return $maxqid;
             } else  {
                $sql = 'UPDATE `#__jobboard_questionnaires` SET
                           `name` = '.$db->Quote($data['name']).'
                           , `title` = '.$db->Quote($data['title']).'
                           , `description` = '.$db->Quote($data['description']).'
                           , `fields` = '.$db->Quote($data['fields']).'
                          WHERE `qid`= '.$qid;
               $db->setQuery($sql);
               return $db->Query();
             }
      }

     /**
     * Save a fields questionnaire column
     * @params questionnaire id, fields data
     *
     * @return boolean
     */
      function saveQnaireFields($qid, $data){
          $db = & $this->getDBO();
          $sql = 'UPDATE `#__jobboard_questionnaires` SET
                    `fields` = '.$db->Quote($data).'
                    WHERE `qid`= '.$qid;
         $db->setQuery($sql);
         return $db->Query();
      }

     /**
     * Delete a questionnaire
     * @params questionnaire id, user id
     *
     * @return boolean
     */
     function delQuestionnaire($qid) {
             $db = & $this->getDBO();
             $sql = 'DROP TABLE IF EXISTS `#__jobboard_q'.$qid.'`';
             $db->setQuery($sql);
             $result1 = $db->Query();
             $sql = 'DELETE FROM  `#__jobboard_questionnaires`
                     WHERE `qid` = '.$qid;
             $db->setQuery($sql);
             $result2 = $db->Query();
             $result = (!$result1 || !$result2)? false : true;
             $sql = 'UPDATE `#__jobboard_jobs` SET `questionnaire_id`=0
                     WHERE `questionnaire_id` = '.$qid;
             $db->setQuery($sql);
             $result1 = $db->Query();
             $result = (!$result || !$result1)? false : true;
             $sql = 'UPDATE `#__jobboard_usr_applications` SET `qid`=0
                     WHERE `qid` = '.$qid;
             $db->setQuery($sql);
             $result2 = $db->Query();
             $result = (!$result || !$result2)? false : true;
             return $result;
     }

     /**
     * Get jobs list data for admin/employer
     * @params user id
     *
     * @return AssocList
     */
    function getAdmJobs($uid) {
           $db = & $this->getDBO();
           $sql = 'SELECT `id`, `post_date`, `expiry_date`, `job_title`, `job_type`, `category`
                           , `positions`, `country`, `city`, `job_tags`, `department`
                           , `status`, `num_applications`
                        FROM `#__jobboard_jobs`
                        WHERE `posted_by` = '.$uid;
           $db->setQuery($sql);
           return $db->loadAssocList();
    }

    /**
     * Get jobseeker profile data
     * @params jobseeker id
     *
     * @return object
     */
     function getSeekerProfile($uid) {
             $db = & $this->getDBO();
             $sql = 'SELECT u.`id`, u.`user_status`, u.`contact_address`,
                      u.`contact_country`, u.`contact_location`, u.`contact_zip`,
                      u.`contact_phone_1`, u.`contact_phone_2`, u.`contact_fax`,
                      u.`website_url`, u.`twitter_url`, u.`facebook_url`, u.`linkedin_url`,
                      s.`name`, s.`email`
                    FROM #__jobboard_users AS u
                    INNER JOIN #__users AS s
                    ON (s.`id` = u.`user_id`)
                    WHERE s.`id` = '.$uid;
             $db->setQuery($sql);
             $result = $db->loadObject();
             return $result;
     }

    /**
     * Save job invitation data
     * @params invitation data (user ids, message)
     *
     * @return object
     */
     function saveInvite($data, $uid) {
             $db = & $this->getDBO();
             $sql = 'INSERT INTO `#__jobboard_invites` (`user_id`, `create_date`, `sender_id`, `cvprof_id`, `job_id`, `message`)
                          VALUES ('.$data['sid'].', UTC_TIMESTAMP, '.$uid.', '.$data['cpid'].',  '.$data['jid'].'
                                ,  '.$db->Quote($data['message']).')';
             $db->setQuery($sql);
             return $db->Query();
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
     * Get cv/resume profile name for user
     * @params cv/resume profile id, user id
     *
     * @return object
     */
    function getCvProfileName($pid, $uid) {
           $db = & $this->getDBO();
           $sql = 'SELECT `profile_name`
                        FROM `#__jobboard_cvprofiles`
                        WHERE `id` = '.$pid.' AND `user_id` = '.$uid;
           $db->setQuery($sql);
           return $db->loadResult();
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
     * Get admin settings for user
     * @params user id
     *
     * @return assoc
     */
     function getAdminCred($uid) {
             $db = & $this->getDBO();
             $sql = 'SELECT `user_type`, `user_status`, `manage_questionnaires`
                        , `create_questionnaires`, `post_jobs`, `manage_jobs`, `manage_applicants`
                     FROM  `#__jobboard_users`
                     WHERE `user_id` = '.$uid;
             $db->setQuery($sql);
             return $db->loadAssoc();
     }

     /**
     * Toggle job publish status
     * @params job id, new status
     *
     * @return boolean
     */
     function toggleJobStatus($jid, $new_status)  {
             $db = & $this->getDBO();
             $sql = 'UPDATE `#__jobboard_jobs` SET `published` = '.$new_status.'
                     WHERE `id` = '.$jid;
             $db->setQuery($sql);
             return $db->Query();
     }

     /**
     * Get minimumjob info
     * @params job id, user id
     *
     * @return object
     */
     function getJob($jid, $uid) {
             $db = & $this->getDBO();
             $sql = 'SELECT *
                     FROM  `#__jobboard_jobs`
                     WHERE `id` = '.$jid.' AND `posted_by` = '.$uid;
             $db->setQuery($sql);
             return $db->loadObject();
     }

     /**
     * Save primary data for a new copy of an existing job
     * return 0 if an error occured or
     * return the id of the newly inserted/updated record
     * @params new job title, user id
     *
     * @return int
     */
     function saveJobMeta($new_title, $uid) {
             $db = & $this->getDBO();
             $sql = 'INSERT INTO #__jobboard_jobs (`post_date`, `posted_by`, `job_title`,
                         `num_applications`, `published`, `hits`, `questionnaire_id`)
                        VALUES (UTC_TIMESTAMP , '.$uid.',
                        '.$db->Quote($new_title).', 0, 0, 0, 0);';
             $db->setQuery($sql);
             $result = $db->Query();
             $jid = $db->insertid();
             return ($jid > 0)? $jid : 0;
     }


     /**
     * Update job data
     * @params job id, job data
     *
     * @return boolean
     */
    function updJob($jobid, $job, $clone=false, $repost=false) {
       $db =& $this->getDBO();
       if($repost == true) {
         $clone = false;
         $published = $db->nameQuote('published').' = 1';
         $reset_date = $db->nameQuote('post_date').' = UTC_TIMESTAMP, ';

       } else $reset_date = '';
       $job_title = !$clone? $db->nameQuote('job_title').' = '.$db->Quote($job['job_title']).', ' : '';
       $published = !$clone? $db->nameQuote('published').' = '.$job['published'] : $db->nameQuote('published').' = 0';
       $questionnaire_id = !$clone? $db->nameQuote('questionnaire_id').' = '.$job['questionnaire_id'] : $db->nameQuote('questionnaire_id').' = 0';
       $job['ref_num'] = !$clone? $job['ref_num'] : '';
       if($repost == true || $clone == true) {
         $app = &JFactory::getApplication();
         $dateNow = new JDate($app->get('requestTime'));
    	 $currdate = $dateNow->toFormat('%Y-%m-%d');
         $dateExp = new JDate($job['expiry_date']);
  	     $dateExpString = $dateExp->toFormat('%Y-%m-%d');
         if($dateExpString <= $currdate) $job['expiry_date'] = '0000-00-00 00:00:00';
       }
       $update_lat = isset($job['geo_latitude'])? ", ".$db->nameQuote('geo_latitude')." =".$job['geo_latitude']." " : '';
       $update_lng = isset($job['geo_longitude'])? ", ".$db->nameQuote('geo_longitude')." =".$job['geo_longitude']." " : '';
       $update_state_province = isset($job['geo_state_province'])? ", ".$db->nameQuote('geo_state_province')." =".$db->Quote($job['geo_state_province'])." " : '';
	   $sql = "UPDATE ".$db->nameQuote('#__jobboard_jobs')." 
                 SET ".$reset_date.$job_title."
                  ".$db->nameQuote('expiry_date')."='".$job['expiry_date']."'
                 , ".$db->nameQuote('job_type')." =".$db->Quote($job['job_type'])."
                 , ".$db->nameQuote('category')." =".$job['category']."
                 , ".$db->nameQuote('career_level')." =".$job['career_level']."
                 , ".$db->nameQuote('education')." =".$job['education']."
                 , ".$db->nameQuote('positions')." =".$job['positions']."
                 , ".$db->nameQuote('country')." =".$job['country']."
                 , ".$db->nameQuote('department')." =".$job['department']."
                 , ".$published."
                 , ".$db->nameQuote('city')." =".$db->Quote($job['city'])."
                 , ".$db->nameQuote('salary')." =".$db->Quote($job['salary'])."
                 , ".$db->nameQuote('description')." =".$db->Quote($job['description'])."
                 , ".$db->nameQuote('duties')." =".$db->Quote($job['duties'])."
                 , ".$db->nameQuote('job_tags')." =".$db->Quote($job['job_tags'])."
                  ".$update_lat.$update_lng.$update_state_province."
                 , ".$db->nameQuote('ref_num')." =".$db->Quote($job['ref_num'])."
                 , ".$questionnaire_id."
                 , ".$db->nameQuote('featured')." =".$job['featured']."
                 WHERE  ".$db->nameQuote('id')."=".$jobid;
        $db->setQuery($sql);
        return $db->Query();
    }

    function getJobEditConfig() {
           $db = & $this->getDBO();
           $sql = 'SELECT `default_dept`, `default_city`, `default_country`, `default_category`
                    , `default_jobtype`, `default_career`, `default_edu`
                    , `long_date_format`, `short_date_format`, `date_separator`, `use_location`
                   FROM #__jobboard_config
                   WHERE id = 1';
           $db->setQuery($sql);
           return $db->loadObject();
    }

    /**
     * Save job data
     * @params job data , user id, location enabled
     *
     * @return boolean
     */
    function saveJob($job, $uid, $use_location=true) {
       $db =& $this->getDBO();
       $save_location = $use_location == true?
                 ", ".$db->nameQuote('geo_latitude'). "
                 , ".$db->nameQuote('geo_longitude'). "
                 , ".$db->nameQuote('geo_state_province') : '';
       if($use_location == true) :
           $job['geo_latitude'] = empty($job['geo_latitude'])? 0 : $job['geo_latitude'];
           $job['geo_longitude'] = empty($job['geo_longitude'])? 0 : $job['geo_longitude'];
           $job['geo_state_province'] = empty($job['geo_state_province'])? '' : $job['geo_state_province'];
           $save_location_data = ", ".$job['geo_latitude'].", ".$job['geo_longitude'].", ".$db->Quote($job['geo_state_province']);
       endif;
	   $sql = "INSERT INTO ".$db->nameQuote('#__jobboard_jobs')."
                 ( ".$db->nameQuote('post_date'). "
                 , ".$db->nameQuote('job_title'). "
                 , ".$db->nameQuote('expiry_date'). "
                 , ".$db->nameQuote('posted_by'). "
                 , ".$db->nameQuote('job_type'). "
                 , ".$db->nameQuote('category'). "
                 , ".$db->nameQuote('career_level'). "
                 , ".$db->nameQuote('education'). "
                 , ".$db->nameQuote('positions'). "
                 , ".$db->nameQuote('country'). "
                 , ".$db->nameQuote('department'). "
                 , ".$db->nameQuote('published'). "
                 , ".$db->nameQuote('city'). "
                 , ".$db->nameQuote('salary'). "
                 , ".$db->nameQuote('description'). "
                 , ".$db->nameQuote('duties'). "
                 , ".$db->nameQuote('job_tags'). "
                 , ".$db->nameQuote('questionnaire_id').$save_location. "
                 , ".$db->nameQuote('featured'). "
                 , ".$db->nameQuote('ref_num'). ")
                 VALUES(UTC_TIMESTAMP, ".$db->Quote($job['job_title']).", ".$db->Quote($job['expiry_date']).", ".$uid.", ".$db->Quote($job['job_type'])."
                 , ".$job['category'].", ".$job['career_level'].", ".$job['education'].", ".$job['positions'].", ".$job['country'].",
                 ".$job['department'].", ".$job['published'].", ".$db->Quote($job['city']).", ".$db->Quote($job['salary']).",
                 ".$db->Quote($job['description']).", ".$db->Quote($job['duties']).", ".$db->Quote($job['job_tags']).", ". $job['questionnaire_id'].", ".$job['featured'].$save_location_data.", ".$db->Quote($job['ref_num']).")";

        $db->setQuery($sql);
        return $db->query();
    }


     /**
     * Delete job
     * @params job id
     *
     * @return boolean
     */
    function delJob($jid) {
       $db = & $this->getDBO();
       $sql = 'DELETE FROM  `#__jobboard_jobs`
               WHERE `id` = '.$jid;
       $db->setQuery($sql);
       return $db->Query();
    }

     /**
     * Get active jobs for poster
     * @param $uid user id
     * @param $published publish status
     * @param $get_featured featured status
     *
     * @return int job count
     */
     function getEmplJobs($uid, $published=1, $get_featured=false) {
             $db = & $this->getDBO();
             $and_featured = $get_featured == true? ' AND '.$db->nameQuote('featured').' = 1 ' : '';
             $sql = 'SELECT COUNT('.$db->nameQuote('id').')
                     FROM  '.$db->nameQuote('#__jobboard_jobs').'
                     WHERE '.$db->nameQuote('posted_by').' = '.$uid.' AND '.$db->nameQuote('published').' = '.$published.$and_featured;
             $db->setQuery($sql);
             return $db->loadResult();
     }

     /**
     * Get invites for poster
     * @param $uid user id
     * @param $responded
     *
     * @return int invite count
     */
     function getEmplInvites($uid, $responded=false) {
           $db = & $this->getDBO();
           $with_response = $responded == true? ' AND i.'.$db->nameQuote('response').' = 1 ' : '';
           $sql = 'SELECT COUNT(i.'.$db->nameQuote('id').')
                 FROM  '.$db->nameQuote('#__jobboard_invites').' AS i
                 INNER JOIN '.$db->nameQuote('#__jobboard_jobs').' AS j
                   ON(i.'.$db->nameQuote('job_id').' = j.'.$db->nameQuote('id').')
                 WHERE j.'.$db->nameQuote('published').' = 1 AND i.'.$db->nameQuote('sender_id').' = '.$uid.$with_response;
           $db->setQuery($sql);
           return $db->loadResult();
     }

     /**
     * Get questionnaires for poster
     * @param $uid user id
     *
     * @return int questionnaire count
     */
     function getEmplQuestionnaires($uid) {
           $db = & $this->getDBO();
           $sql = 'SELECT COUNT('.$db->nameQuote('id').')
                   FROM  '.$db->nameQuote('#__jobboard_questionnaires').'
                   WHERE '.$db->nameQuote('created_by').' = '.$uid;
           $db->setQuery($sql);
           return $db->loadResult();
     }

     /**
     * Get once-off aplication count for poster
     * @param $uid user id
     *
     * @return int once-off aplication count
     */
     function getEmplAppls($uid, $get_registered=false) {
             $db = & $this->getDBO();
             $appls_table = $get_registered == true? $db->nameQuote('#__jobboard_usr_applications') : $db->nameQuote('#__jobboard_applicants');
             $sql = 'SELECT COUNT('.$db->nameQuote('id').')
                     FROM  '.$appls_table.'
                     WHERE '.$db->nameQuote('job_id').' IN
                        (SELECT '.$db->nameQuote('id').'
                            FROM '.$db->nameQuote('#__jobboard_jobs').'
                             WHERE '.$db->nameQuote('posted_by').' = '.$uid.')';
             $db->setQuery($sql);
             return $db->loadResult();
     }

     /**
     * Remove job reference from user applications table
     * @params job id
     *
     * @return boolean
     */
    function nullApplications($jid) {
       $db = & $this->getDBO();
       $sql = 'UPDATE `#__jobboard_usr_applications`
                SET `job_id` = 0
               WHERE `job_id` = '.$jid;
       $db->setQuery($sql);
       return $db->Query();
    }

     /**
     * Remove job reference from site applications table
     * @params job id
     *
     * @return boolean
     */
    function nullSiteApplications($jid) {
       $db = & $this->getDBO();
       $sql = 'UPDATE `#__jobboard_applicants`
                SET `job_id` = 0
               WHERE `job_id` = '.$jid;
       $db->setQuery($sql);
       return $db->Query();
    }

     /**
     * Get total active applications for job
     * Exclude current user job applications
     * @params job id, user id
     *
     * @return assoc
     */
     function getJobApplsCount($jid, $uid) {

             $db = & $this->getDBO();
             $sql = 'SELECT COUNT(a.id)
                     FROM  `#__jobboard_usr_applications` AS a
                     INNER JOIN `#__jobboard_jobs` AS j
                     ON(j.`id` = a.`job_id`)
                     WHERE a.`job_id` = '.$jid.' AND a.`user_id` <> '.$uid.' AND j.`published` = 1';
             $db->setQuery($sql);
             $user_appls = $db->loadResult();
             $sql = 'SELECT COUNT(a.id)
                     FROM  `#__jobboard_applicants` AS a
                     INNER JOIN `#__jobboard_jobs` AS j
                     ON(j.`id` = a.`job_id`)
                     WHERE a.`job_id` = '.$jid.' AND j.`published` = 1';
             $db->setQuery($sql);
             $site_appls = $db->loadResult();
             return array('user_appls' =>$user_appls, 'site_appls' => $site_appls);
     }

     /**
     * Get registered user job applications for particular job
     * Exclude current user job applications
     * @params job id, user id
     *
     * @return assoc
     */
    function getUserApplications($jid, $uid) {
        $db =& $this->getDBO();
        $query = 'SELECT *
                  FROM `#__jobboard_usr_applications`
                  WHERE `job_id`='.$jid.' AND `user_id` <> '.$uid.' ORDER BY `id` DESC';
        $db->setQuery($query);
        return $db->loadAssocList();
    }

     /**
     * Get non-registered user job applications for particular job
     * @params job id
     *
     * @return assoc
     */
    function getSiteApplications($jid) {
        $db =& $this->getDBO();
        $query = 'SELECT *
                  FROM `#__jobboard_applicants`
                  WHERE `job_id`='.$jid.' ORDER BY `id` DESC';
        $db->setQuery($query);
        return $db->loadAssocList();
    }

     /**
     * Get application data for editing
     * @params application id, boolean: application type
     *
     * @return assoc
     */
    function getApplication($aid, $site=false) {
        $db =& $this->getDBO();
        if($site <> true)
          $query = 'SELECT `id`, `status_id`, `last_modified`, `admin_notes`
                    FROM `#__jobboard_usr_applications`
                    WHERE `id`='.$aid;
        if($site == true)
          $query = 'SELECT `id`, `status`, `last_updated`, `admin_notes`
                    FROM `#__jobboard_applicants`
                    WHERE `id`='.$aid;
        $db->setQuery($query);
        return $db->loadAssoc();
    }

     /**
     * Save admin application data for job application
     * @params application id, boolean: application type
     *
     * @return boolean
     */
    function saveApplication($aid, $data, $site=false) {
        $db =& $this->getDBO();
        if($site == true)
          $query = 'UPDATE '.$db->nameQuote('#__jobboard_applicants').' SET
                    '.$db->nameQuote('status').' = '.$data['status'].',
                    '.$db->nameQuote('last_updated').' = UTC_TIMESTAMP,
                    '.$db->nameQuote('admin_notes').' = '.$db->Quote($data['admin_notes']).'
                    WHERE '.$db->nameQuote('id').' = '.$aid;
        if($site <> true)
          $query = 'UPDATE '.$db->nameQuote('#__jobboard_usr_applications').' SET
                    '.$db->nameQuote('status_id').' = '.$data['status'].',
                    '.$db->nameQuote('last_modified').' = UTC_TIMESTAMP,
                    '.$db->nameQuote('admin_notes').' = '.$db->Quote($data['admin_notes']).'
                    WHERE '.$db->nameQuote('id').' = '.$aid;
        $db->setQuery($query);     
        return $db->Query();
    }

     /**
     * Get applicant answers to questionnaire
     * @params questionnaire id, application id
     *
     * @return assoc
     */
    function getApplicantQanswers($qid, $aid) {
        $db =& $this->getDBO();
          $query = 'SELECT *
                    FROM `#__jobboard_q'.$qid.'`
                    WHERE `appl_id`='.$aid;
        $db->setQuery($query);
        return $db->loadAssoc();
    }

     /**
     * Send applicant answers to questionnaire
     * @params questionnaire id , application id, data
     *
     * @return boolean
     */
    function updApplicantQanswers($qid, $aid, $data) {
        $db = & $this->getDBO();
             $sql = 'UPDATE `#__jobboard_q'.$qid.'` SET ';
             foreach($data as $fieldset)  {
                 $curr_col = $fieldset[2] == 'text'? $db->Quote($fieldset[1]) : $fieldset[1];
                 $sql .= '`'.$fieldset[0].'` = '.$curr_col.', ';
             }
             $sql = substr($sql, 0, -2);
             $sql .= ' WHERE `appl_id` = '.$aid;
             $db->setQuery($sql);
             return $db->Query();
    }

     /**
     * Get application job title
     * @params job id
     *
     * @return string
     */
    function getApplJobTitle($jid) {
        $db =& $this->getDBO();
        $query = 'SELECT `job_title`
                  FROM `#__jobboard_jobs`
                  WHERE `id`='.$jid;
        $db->setQuery($query);
        return $db->loadResult();
    }

     /**
     * Get once-off applicant info
     * @params application id
     *
     * @return assoc
     */
    function getSiteApplInfo($aid) {
        $db =& $this->getDBO();
        $query = 'SELECT `id`, `first_name`, `last_name`, `email`
                  , `tel`, `title`, `cover_note`
                  FROM `#__jobboard_applicants`
                  WHERE `id`='.$aid;
        $db->setQuery($query);
        return $db->loadAssoc();
    }

     /**
     * Get Joomla username by id
     * @params user id
     *
     * @return string
     */
    function getJUsername($uid) {
        $db =& $this->getDBO();
        $query = 'SELECT `name`
                  FROM `#__users`
                  WHERE `id`='.$uid;
        $db->setQuery($query);
        return $db->loadResult();
    }

     /**
     * Get job application file name and hash
     * @params application id
     *
     * @return assoc
     */
    function getApplFile($aid) {
        $db =& $this->getDBO();
        $query = 'SELECT `filename`, `file_hash`
                  FROM `#__jobboard_applicants`
                  WHERE `id`='.$aid;
        $db->setQuery($query);
        return $db->loadAssoc();
    }

     /**
     * Get date format setting
     * @params none
     *
     * @return int
     */
    function getDateFormat() {
       $db = & $this->getDBO();
       $sql = 'Select `long_date_format` FROM `#__jobboard_config`
               WHERE `id` = 1';
       $db->setQuery($sql);
       return $db->loadResult();
    }


     /**
     * Increment cv profile hit counter
     * @params cv profile id
     *
     * @return boolean
     */
    function incrCVcounter($pid) {
        $db =& $this->getDBO();
        $query = 'UPDATE `#__jobboard_cvprofiles` SET
                `hits` =  `hits` + 1
                WHERE id='. $pid;
        $db->setQuery($query);
        return $db->Query();
    }
}

?>