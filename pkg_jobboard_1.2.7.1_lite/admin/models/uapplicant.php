<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class JobboardModelUapplicant extends JModel
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
     * Get questionnaire for job
     * @params questionnaire id
     *
     * @return assoc
     */
     function getQuestionnaire($qid) {
             $db = & $this->getDBO();
             $sql = 'SELECT `name`, `title`, `created_by`, `description`, `fields`  FROM  `#__jobboard_questionnaires`
                     WHERE `qid` = '.$qid;
             $db->setQuery($sql);
             return $db->loadAssoc();
     }

     /**
     * Get questionnaire fields
     * @params questionnaire id
     *
     * @return string
     */
     function getQuestionnaireFields($qid) {
             $db = & $this->getDBO();
             $sql = 'SELECT `fields` FROM  `#__jobboard_questionnaires`
                     WHERE `qid` = '.$qid;
             $db->setQuery($sql);
             return $db->loadResult();
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
     * Get job application status types
     * @params none
     *
     * @return list of objects
     */
    function getStatuses() {
        $db =& $this->getDBO();
		$sql = "SELECT * FROM `#__jobboard_statuses`";
        $db->setQuery($sql);
        return $db->loadObjectList();
    }

     /**
     * Get cv/resume profile name for user
     * @params cv/resume profile id, user id
     *
     * @return string
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
     * Save admin application data for job application
     * @params application id, array: application data
     *
     * @return boolean
     */
    function saveApplication($aid, $data) {
        $db =& $this->getDBO();
        $query = 'UPDATE `#__jobboard_usr_applications` SET
                  `status_id` = '.$data['status'].',
                  `last_modified` = UTC_TIMESTAMP,
                  `admin_notes` = "'.$data['admin_notes'].'"
                  WHERE `id`='.$aid;
        $db->setQuery($query);
        return $db->Query();
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
     * Get file associated with a once-off application
     * @param $fid file/application id
     *
     * @return object
     */
      function getSiteCv($fid) {
             $db = & $this->getDBO();
             $sql = 'SELECT `filename`, `file_hash`
                    FROM `#__jobboard_applicants`
                    WHERE `id` = '.$fid;
             $db->setQuery($sql);
             return $db->loadObject();
      }


     /**
     * Get cv/resume profile id and applicant id for an application
     * @param $aid application id
     *
     * @return assoc
     */
    function getApplIds($aid) {
           $db = & $this->getDBO();
           $sql = 'SELECT `user_id` AS sid, `cvprof_id` AS pid
                        FROM `#__jobboard_usr_applications`
                        WHERE `id` = '.$aid;
           $db->setQuery($sql);
           return $db->loadAssoc();
    }

}
?>