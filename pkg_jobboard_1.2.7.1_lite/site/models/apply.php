<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class JobboardModelApply extends JModel
{
	var $_data = null;
    var $_id = null;
    var $_option = null;

	function getJobData($id)    {
           $db = & JFactory :: getDBO();
           $sql = 'SELECT  j.'.$db->nameQuote('id').'
                      , j.'.$db->nameQuote('post_date').'
                      , j.'.$db->nameQuote('expiry_date').'
                      , j.'.$db->nameQuote('job_title').'
                      , j.'.$db->nameQuote('posted_by').'
                      , j.'.$db->nameQuote('job_type').'
                      , j.'.$db->nameQuote('country').'
                      , j.'.$db->nameQuote('salary').'
                      , c.'.$db->nameQuote('id').' AS catid
                      , c.'.$db->nameQuote('type').' AS category
                      , jc.'.$db->nameQuote('country_name').'
                      , jc.'.$db->nameQuote('country_region').'
                      , cl.'.$db->nameQuote('description').' AS job_level
                      , j.'.$db->nameQuote('positions').'
                      , j.'.$db->nameQuote('city').'
                      , j.'.$db->nameQuote('num_applications').'
                      , j.'.$db->nameQuote('published').'
                      , j.'.$db->nameQuote('ref_num').'
                      , e.'.$db->nameQuote('level').' AS education
                  FROM
                      '.$db->nameQuote('#__jobboard_jobs').' AS j
                      INNER JOIN '.$db->nameQuote('#__jobboard_categories').'  AS c
                          ON (j.'.$db->nameQuote('category').' = c.'.$db->nameQuote('id').')
                      INNER JOIN '.$db->nameQuote('#__jobboard_career_levels').' AS cl
                          ON (j.'.$db->nameQuote('career_level').' = cl.'.$db->nameQuote('id').')
                      INNER JOIN '.$db->nameQuote('#__jobboard_education').' AS e
                          ON (e.'.$db->nameQuote('id').' = j.'.$db->nameQuote('education').')
                      INNER JOIN #__jobboard_countries AS jc
                          ON (j.'.$db->nameQuote('country').' = jc.'.$db->nameQuote('country_id').')
                      WHERE j.'.$db->nameQuote('id').' = ' . $id;
           $db->setQuery($sql);
           return $db->loadObject();
       }


    function getOption($id) {
        $query = 'SELECT option_type FROM #__jobboard_options
                  WHERE id ='.$id;
    	$db =& JFactory::getDBO();
    	$db->setQuery($query);
		$this->_option = $db->loadResult();

        return $this->_option;
    }

    function saveUserApplication($uid, $pid, $jid, $qid)
	{
        $db =& $this->getDBO();
        $query = 'INSERT INTO `#__jobboard_usr_applications` (`user_id`, `job_id`, `cvprof_id`, `status_id`, `qid`, `applied_on`)
                  VALUES ('.$uid.', '.$jid.', '.$pid.', 1, '.$qid.', UTC_TIMESTAMP) ';
    	$db->setQuery($query);
	    $db->Query();
        return $db->insertid();
	}

    function getUserApplications($uid) {
        $db =& $this->getDBO();
        $query = 'SELECT
                       a.*,
                       j.`job_title`,
                       s.`status_description`,
                       c.`profile_name`
                  FROM `#__jobboard_statuses` AS s
                  INNER JOIN `#__jobboard_usr_applications` AS a
                    ON (s.`id` = a.`status_id`)
                  INNER JOIN `#__jobboard_jobs` AS j
                    ON (j.`id` = a.`job_id`)
                  INNER JOIN `#__jobboard_cvprofiles` AS c
                    ON (c.`id` = a.`cvprof_id`)
                  WHERE a.`user_id`='.$uid.' ORDER BY a.`id` DESC';
        $db->setQuery($query);
        return $db->loadAssocList();
    }

    function delUserApplication($appl_id, $uid, $qid) {
        $db =& $this->getDBO();
        $query = 'DELETE FROM `#__jobboard_usr_applications`
              WHERE `id`='.$appl_id.' AND `user_id` = '.$uid;
        $db->setQuery($query);
	    $result1 = $db->Query();
        if($qid > 0) {
            $query = 'DELETE FROM `#__jobboard_q'.$qid.'`
                  WHERE `appl_id`='.$appl_id;
            $db->setQuery($query);
    	    $result2 = $db->Query();
        } else $result2 = true;
	    return (!$result1 || !$result2)? false : true;
    }


     /**
     * Increment the job applications hit counter
     * @params job id
     *
     * @return boolean
     */
       function incrApplications($jid) {
           $db = & JFactory :: getDBO();
           $query = 'UPDATE #__jobboard_jobs SET
                num_applications =  num_applications + 1
                WHERE id='. $jid;
           $db->setQuery($query);
           return $db->Query();
       }

     /**
     * Get jobs applications list for a particular job by registered users
     * @params job id
     *
     * @return AssocList
     */
    function getUsrApplicationsForJob($jid) {         
           return 0;
    }

     /**
     * Get jobs applications list for a particular job by site users
     * @params job id
     *
     * @return AssocList
     */
    function getSiteApplicationsForJob($jid) {
           $db = & $this->getDBO();
           $sql = 'SELECT * FROM `#__jobboard_applicants`
                        WHERE `job_id` = '.$jid;
           $db->setQuery($sql);
           return $db->loadAssocList();
    }
}
?>