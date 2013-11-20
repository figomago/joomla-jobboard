<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class JobboardModelApplicant extends JModel
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
            $db = JFactory::getDBO();
			$this->_query = 'SELECT j.post_date
                      , j.job_title
                      , j.job_type
                      , j.country
                      , j.salary
                      , c.id AS catid
                      , c.type AS category
                      , jc.country_name
                      , jc.country_region
                      , cl.description AS job_level
                      , j.id AS job_id
                      , j.description
                      , j.positions
                      , j.city
                      , j.num_applications
                      , j.hits
                      , e.level AS education
                  FROM
                      #__jobboard_jobs AS j
                      INNER JOIN #__jobboard_categories  AS c
                          ON (j.category = c.id)
                      INNER JOIN #__jobboard_career_levels AS cl
                          ON (j.career_level = cl.id)
                      INNER JOIN #__jobboard_education AS e
                          ON (e.id = j.education)
                      INNER JOIN #__jobboard_countries AS jc
                          ON (j.country = jc.country_id)
                      WHERE j.id = ' . $this->_id;
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

    function save($applicant) {
       $db =& $this->getDBO();
		$this->_query = "UPDATE #__jobboard_applicants
                     SET last_updated = UTC_TIMESTAMP
                     , first_name ='".$db->getEscaped($applicant->first_name, true)."'
                     , last_name ='".$db->getEscaped($applicant->last_name, true)."'
                     , email ='".$db->getEscaped($applicant->email, true)."'
                     , tel ='".$db->getEscaped($applicant->tel, true)."'     
                     , cover_note ='".$db->getEscaped($applicant->cover_note, true)."'
                     , admin_notes ='".$db->getEscaped($applicant->admin_notes, true)."'
                     , status =".$db->getEscaped($applicant->status, true)."
                 WHERE id=".intval($applicant->id);
        $db->setQuery($this->_query);
        $r1 = $db->query();
        $this->_query = "UPDATE #__jobboard_jobs
                     SET department =".$db->getEscaped($applicant->department, true)."
                 WHERE id=".intval($applicant->job_id);
        $db->setQuery($this->_query);
        $r2 = $db->query();
        return ($r1 && $r2)? true : false;
    }
    function savenew($applicant) {
       $db =& $this->getDBO();
		$this->_query = "INSERT INTO #__jobboard_jobs
                    (job_title, job_type, career_level, education, positions, country, department, published, city, description, duties)
                     VALUES ('".$db->getEscaped($applicant->job_title, true)."'
                     , '".$db->getEscaped($applicant->job_type, true)."'
                     , ".$db->getEscaped($applicant->career_level, true)."
                     , ".$db->getEscaped($applicant->education_level, true)."
                     , ".$db->getEscaped($applicant->positions, true)."
                     , ".$db->getEscaped($applicant->country_name, true)."
                     , ".$db->getEscaped($applicant->department, true)."
                     , ".$db->getEscaped($applicant->published, true)."
                     , '".$db->getEscaped($applicant->city, true)."'
                     , '".$db->getEscaped($applicant->job_description, true)."'
                     , '".$db->getEscaped($applicant->duties, true)."'
                 )";
        $db->setQuery($this->_query);
        return $db->query();
    }

    function deleteApplicants($serialised_id_array) {
          $db =& $this->getDBO();
		  $this->_query =  'DELETE FROM #__jobboard_applicants'
			. ' WHERE id IN ( '. $serialised_id_array .' )';
          $db->setQuery($this->_query);
          $delete_result = $db->Query();
          $delete_result = ($delete_result == true)? $delete_result : $db->getErrorMsg(true);
	      return $delete_result;
    }

}
?>