<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */


defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class JobboardModelJob extends JModel
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
					  , j.'.$db->nameQuote('posted_by').'
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
                      , j.'.$db->nameQuote('featured').'
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

    function repostJob($job) {
        $db =& $this->getDBO();
        $job->geo_latitude = empty($job->geo_latitude)? 0 : $job->geo_latitude;
        $job->geo_longitude = empty($job->geo_longitude)? 0 : $job->geo_longitude;
        $job->geo_state_province = empty($job->geo_state_province)? '' : $job->geo_state_province;
		$this->_query = "UPDATE ".$db->nameQuote('#__jobboard_jobs')."
                     SET ".$db->nameQuote('job_title')."=".$db->Quote($job->job_title)."
                     , ".$db->nameQuote('post_date')."= UTC_TIMESTAMP
                     , ".$db->nameQuote('expiry_date')."='".$job->expiry_date."'
                     , ".$db->nameQuote('posted_by')."=".$job->posted_by."
                     , ".$db->nameQuote('job_type')." =".$db->Quote($job->job_type)."
                     , ".$db->nameQuote('category')." =".$db->Quote($job->category)."
                     , ".$db->nameQuote('career_level')." =".$db->Quote($job->career_level)."
                     , ".$db->nameQuote('education')." =".$db->Quote($job->education_level)."
                     , ".$db->nameQuote('positions')." =".$db->Quote($job->positions)."
                     , ".$db->nameQuote('country')." =".$db->Quote($job->country)."
                     , ".$db->nameQuote('department')." =".$db->Quote($job->department)."
                     , ".$db->nameQuote('published')." =1
                     , ".$db->nameQuote('city')." =".$db->Quote($job->city)."
                     , ".$db->nameQuote('salary')." =".$db->Quote($job->salary)."
                     , ".$db->nameQuote('description')." =".$db->Quote($job->job_description)."
                     , ".$db->nameQuote('duties')." =".$db->Quote($job->duties)."
                     , ".$db->nameQuote('job_tags')." =".$db->Quote($job->job_tags)."
                     , ".$db->nameQuote('geo_latitude')." =".$job->geo_latitude."
                     , ".$db->nameQuote('geo_longitude')." =".$job->geo_longitude."
                     , ".$db->nameQuote('geo_state_province')." =".$db->Quote($job->geo_state_province)."
                     , ".$db->nameQuote('questionnaire_id')." =".$job->questionnaire_id."
                     , ".$db->nameQuote('ref_num')." =".$db->Quote($job->ref_num)."
                     , ".$db->nameQuote('featured')." =".$job->featured."
                 WHERE  ".$db->nameQuote('id')."=".intval($job->id);
        $db->setQuery($this->_query);
        return $db->query();
    }

    function save($job) {
        $db =& $this->getDBO();
        $job->geo_latitude = empty($job->geo_latitude)? 0 : $job->geo_latitude;
        $job->geo_longitude = empty($job->geo_longitude)? 0 : $job->geo_longitude;
        $job->geo_state_province = empty($job->geo_state_province)? '' : $job->geo_state_province;
		$this->_query = "UPDATE ".$db->nameQuote('#__jobboard_jobs')."
                     SET ".$db->nameQuote('job_title')."=".$db->Quote($job->job_title)."
                     , ".$db->nameQuote('expiry_date')."='".$job->expiry_date."'
                     , ".$db->nameQuote('posted_by')."=".$job->posted_by."
                     , ".$db->nameQuote('job_type')." =".$db->Quote($job->job_type)."
                     , ".$db->nameQuote('category')." =".$db->Quote($job->category)."
                     , ".$db->nameQuote('career_level')." =".$db->Quote($job->career_level)."
                     , ".$db->nameQuote('education')." =".$db->Quote($job->education_level)."
                     , ".$db->nameQuote('positions')." =".$db->Quote($job->positions)."
                     , ".$db->nameQuote('country')." =".$db->Quote($job->country)."
                     , ".$db->nameQuote('department')." =".$db->Quote($job->department)."
                     , ".$db->nameQuote('published')." =".$db->Quote($job->published)."
                     , ".$db->nameQuote('city')." =".$db->Quote($job->city)."
                     , ".$db->nameQuote('salary')." =".$db->Quote($job->salary)."
                     , ".$db->nameQuote('description')." =".$db->Quote($job->job_description)."
                     , ".$db->nameQuote('duties')." =".$db->Quote($job->duties)."
                     , ".$db->nameQuote('job_tags')." =".$db->Quote($job->job_tags)."
                     , ".$db->nameQuote('geo_latitude')." =".$job->geo_latitude."
                     , ".$db->nameQuote('geo_longitude')." =".$job->geo_longitude."
                     , ".$db->nameQuote('geo_state_province')." =".$db->Quote($job->geo_state_province)."
                     , ".$db->nameQuote('questionnaire_id')." =".$job->questionnaire_id."
                     , ".$db->nameQuote('ref_num')." =".$db->Quote($job->ref_num)."
                     , ".$db->nameQuote('featured')." =".$job->featured."
                 WHERE  ".$db->nameQuote('id')."=".intval($job->id);
        $db->setQuery($this->_query);
        return $db->query();
    }

    function savenew($job) {
        $db =& $this->getDBO();
        $job->geo_latitude = empty($job->geo_latitude)? 0 : $job->geo_latitude;
        $job->geo_longitude = empty($job->geo_longitude)? 0 : $job->geo_longitude;
        $job->geo_state_province = empty($job->geo_state_province)? '' : $job->geo_state_province;
		$this->_query = "INSERT INTO #__jobboard_jobs
                    (post_date, job_title, expiry_date, posted_by, job_type, category, career_level, education, positions, country, department, published, city, salary
                    , description, duties, ref_num, job_tags, geo_latitude, geo_longitude, geo_state_province, questionnaire_id, featured)
                     VALUES (UTC_TIMESTAMP, '".$db->getEscaped($job->job_title, true)."'
                     , '".$db->getEscaped($job->expiry_date, true)."'
                     , ".$db->getEscaped($job->posted_by)."
                     , '".$job->job_type."'
                     , ".$db->getEscaped($job->category, true)."
                     , ".$db->getEscaped($job->career_level, true)."
                     , ".$db->getEscaped($job->education_level, true)."
                     , ".$db->getEscaped($job->positions, true)."
                     , ".$db->getEscaped($job->country, true)."
                     , ".$db->getEscaped($job->department, true)."
                     , ".$db->getEscaped($job->published, true)."
                     , '".$db->getEscaped($job->city, true)."'
                     , '".$db->getEscaped($job->salary, true)."'
                     , '".$db->getEscaped($job->job_description)."'
                     , '".$db->getEscaped($job->duties)."'
                     , '".$db->getEscaped($job->ref_num, true)."'
                     , '".$db->getEscaped($job->job_tags, true)."'
                     , ".$db->getEscaped($job->geo_latitude)."
                     , ".$db->getEscaped($job->geo_longitude)."
                     , '".$db->getEscaped($job->geo_state_province)."'
                     , ".$db->getEscaped($job->questionnaire_id)."
                     , ".$db->getEscaped($job->featured)."
                 )";
        $db->setQuery($this->_query);
        return $db->query();
    }

}
?>