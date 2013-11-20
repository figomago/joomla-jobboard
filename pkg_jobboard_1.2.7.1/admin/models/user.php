<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */


defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class JobboardModelUser extends JModel
{
	var $_total;
    var $_id;
	var $_query;
	var $_data;

	function __construct()
	{
		parent::__construct();

	    $cid = JRequest::getVar('cid', false, 'DEFAULT', 'array');
        if($cid){
          $id = $cid[0];
        }
        else $id = JRequest::getInt('id', 0);
        $this->setId($id);
	}

    function setId($id=0)
    {
      $this->_id = $id;
      $this->_query = null;
      $this->_data = null;
      $this->_total = null;
    }

	function getJob()
	{

		if(empty($this->_data))
		{
            $db = $this->getDBO();
			$this->_query = 'SELECT j.'.$db->nameQuote('post_date').'
					  , j.'.$db->nameQuote('expiry_date').'
                      , j.'.$db->nameQuote('job_title').'
                      , j.'.$db->nameQuote('job_type').'
                      , j.'.$db->nameQuote('country').'
                      , j.'.$db->nameQuote('salary').'
                      , c.'.$db->nameQuote('id').' AS catid
                      , c.'.$db->nameQuote('type').' AS category
                      , jc.'.$db->nameQuote('country_name').'
                      , jc.'.$db->nameQuote('country_region').'
                      , cl.'.$db->nameQuote('description').' AS job_level
                      , j.'.$db->nameQuote('id AS job_id').'
                      , j.'.$db->nameQuote('description').'
                      , j.'.$db->nameQuote('positions').'
                      , j.'.$db->nameQuote('city').'
                      , j.'.$db->nameQuote('questionnaire_id').'
                      , j.'.$db->nameQuote('ref_num').'
                      , j.'.$db->nameQuote('geo_latitude').'
                      , j.'.$db->nameQuote('geo_longitude').'
                      , j.'.$db->nameQuote('geo_state_province').'
                      , j.'.$db->nameQuote('num_applications').'
                      , j.'.$db->nameQuote('hits').'
                      , e.'.$db->nameQuote('level').' AS education
                  FROM
                      '.$db->nameQuote('#__jobboard_jobs').' AS j
                      INNER JOIN '.$db->nameQuote('#__jobboard_categories').'  AS c
                          ON (j.category = c.id)
                      INNER JOIN '.$db->nameQuote('#__jobboard_career_levels').' AS cl
                          ON (j.'.$db->nameQuote('career_level').' = cl.'.$db->nameQuote('id').')
                      INNER JOIN '.$db->nameQuote('#__jobboard_education').' AS e
                          ON (e.'.$db->nameQuote('id').' = j.'.$db->nameQuote('education').')
                      INNER JOIN '.$db->nameQuote('#__jobboard_countries').' AS jc
                          ON (j.'.$db->nameQuote('country').' = jc.'.$db->nameQuote('country_id').')
                      WHERE j.'.$db->nameQuote('id').' = ' . $this->_id;
            $db->setQuery($this->_query);
            $this->_data = $db->loadObject();
		}

		return $this->_data;
	}

    function update($data) {
        $db = JFactory::getDBO();
		$this->_query = "UPDATE #__jobboard_applicants
                     SET email ='".$data->email."'
                     , tel ='".$data->tel."'
                 WHERE id=".$data->id;
        $db->setQuery($this->_query);
        return $db->query();
    }

    function save($job) {
       $db =& $this->getDBO();
		$this->_query = "UPDATE ".$db->nameQuote('#__jobboard_jobs')."
                     SET ".$db->nameQuote('job_title')."=".$db->Quote($job->job_title)."
                     , ".$db->nameQuote('expiry_date')."='".$job->expiry_date."'
                     , ".$db->nameQuote('job_type')." =".$db->Quote($job->job_type)."
                     , ".$db->nameQuote('category')." =".$db->Quote($job->category)."
                     , ".$db->nameQuote('career_level')." =".$db->Quote($job->career_level)."
                     , ".$db->nameQuote('education')." =".$db->Quote($job->education_level)."
                     , ".$db->nameQuote('positions')." =".$db->Quote($job->positions)."
                     , ".$db->nameQuote('country')." =".$db->Quote($job->country_name)."
                     , ".$db->nameQuote('department')." =".$db->Quote($job->department)."
                     , ".$db->nameQuote('published')." =".$db->Quote($job->published)."
                     , ".$db->nameQuote('city')." =".$db->Quote($job->city)."
                     , ".$db->nameQuote('salary')." =".$db->Quote($job->salary)."
                     , ".$db->nameQuote('description')." =".$db->Quote($job->job_description)."
                     , ".$db->nameQuote('duties')." =".$db->Quote($job->duties)."
                     , ".$db->nameQuote('job_tags')." =".$db->Quote($job->job_tags)."
                     , ".$db->nameQuote('ref_num')." =".$db->Quote($job->ref_num)."
                 WHERE  ".$db->nameQuote('id')."=".intval($job->id);
        $db->setQuery($this->_query);
        return $db->query();
    }
    function savenew($job) {
       $db =& $this->getDBO();
		$this->_query = "INSERT INTO #__jobboard_jobs
                    (post_date, job_title, expiry_date, job_type, category, career_level, education, positions, country, department, published, city, salary, description, duties, ref_num, job_tags)
                     VALUES (UTC_TIMESTAMP, '".$db->getEscaped($job->job_title, true)."'
                     , '".$db->getEscaped($job->expiry_date, true)."'
                     , '".$job->job_type."'
                     , ".$db->getEscaped($job->category, true)."
                     , ".$db->getEscaped($job->career_level, true)."
                     , ".$db->getEscaped($job->education_level, true)."
                     , ".$db->getEscaped($job->positions, true)."
                     , ".$db->getEscaped($job->country_name, true)."
                     , ".$db->getEscaped($job->department, true)."
                     , ".$db->getEscaped($job->published, true)."
                     , '".$db->getEscaped($job->city, true)."'
                     , '".$db->getEscaped($job->salary, true)."'
                     , '".$db->getEscaped($job->job_description)."'
                     , '".$db->getEscaped($job->duties)."'
                     , '".$db->getEscaped($job->ref_num, true)."'
                     , '".$db->getEscaped($job->job_tags, true)."'
                 )";
        $db->setQuery($this->_query);
        return $db->query();
    }

    /**
     * Get jobseeker profile data
     * @param $uid jobseeker id
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
             $db->setQuery($sql);   // echo 'getSeekerProfile - $uid<br />'.$sql.'<br />';
             $result = $db->loadObject();
             return $result;
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

}
?>