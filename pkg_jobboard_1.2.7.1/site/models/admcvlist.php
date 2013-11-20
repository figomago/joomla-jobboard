<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2012 Tandolin
  @license : GNU General Public License v2 or later
----------------------------------------------------------------------- */

// Protect from unauthorized access
defined('_JEXEC') or die();
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_db.php' );

jimport('joomla.application.component.model');

class JobboardModelAdmcvlist extends JobboardModel
{
	var $_data = null;
    var $_db = null;
	var $_total = null;
	var $_pagination = null;
	var $_uid = null;
	var $_tinylimit = null;

	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();

        $app = & JFactory::getApplication();
		$config =& JFactory::getConfig();
        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart = JRequest::getVar('limitstart', 0, '', 'int');
        $uid = $app->getUserstate("com_jobboard.cvsearch.uid", 0, 'int');

		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
        $tinylimit = ceil($limit/2);
		$this->setState('tinylimit', $tinylimit);
		$this->setState('uid', $uid);
		$this->setCustVars();
	}

    function setCustVars(){
       $this->_tinylimit = $this->getState('tinylimit');
       $this->_uid = $this->getState('uid');
    }

    function _buildQuery()
	{
		$app= JFactory::getApplication();

		if (empty($this->_data))
		{
            $db = & $this->getDBO();

    		$job_title = $app->getUserState('com_jobboard.cvsearch.job_title',  '', 'string');
    		$skills = $app->getUserState('com_jobboard.cvsearch.skills', '', 'string');
    		$qualification = $app->getUserState('com_jobboard.cvsearch.qualification', '', 'string');
            $search_private = $app->getUserState("com_jobboard.cvsearch.search_private", 0, 'int');
            $use_location = $app->getUserState("com_jobboard.cvsearch.use_location", 0, 'int');
            $ed_level = $app->getUserState('com_jobboard.cvsearch.ed_level',  0, 'int');

            $profiles = $job_title_arr = $skills_arr = $qualification_arr = array();

            if($job_title != '') {
                $job_title_arr = $this->_getProfilesByTitle($job_title);
                $job_title_arr = !empty($job_title_arr)? $job_title_arr : array();
                $profiles = array_merge($profiles, $job_title_arr);
            }

            if($skills != '') {
               $skills_arr = $this->_getProfilesBySkills($skills);
               $skills_arr = !empty($skills_arr)? $skills_arr : array();

               if($job_title != '') {
                 $duplicates = array_intersect($profiles, $skills_arr);
                 if(!empty($duplicates)) {
                     $skills_arr = JobBoardFindHelper::removeDuplicates($skills_arr, $duplicates);
                 }
               }
                $profiles = array_merge($profiles, $skills_arr);
            }

            if($qualification != '') {
               $qualification_arr = $this->_getProfilesByQual($qualification);
               $qualification_arr = !empty($qualification_arr)? $qualification_arr : array();

               if($job_title != '' || $skills != '') {
                 $duplicates = array_intersect($profiles, $qualification_arr);
                 if(!empty($duplicates)) {
                     $qualification_arr = JobBoardFindHelper::removeDuplicates($qualification_arr, $duplicates);
                 }
               }
                $profiles = array_merge($profiles, $qualification_arr);
            }
            if(empty($this->_uid))
                $this->_uid =  $app->getUserstate("com_jobboard.cvsearch.uid", 0, 'int');

            $where = ' WHERE c.'.JobBoardDbHelper::dbNameQuote('user_id').' <> '.$this->_uid.' ';
            $s_where = array();
            foreach($profiles as $profile)
        	{
        		$s_where[] = " c.".JobBoardDbHelper::dbNameQuote('id')." = '{$profile}'";
        	}
            if(!empty($s_where)) {
        	    $where .= ' AND ('.implode(' OR ',$s_where).')';
            } else {
                //$where .= ' AND c.`id` = 0 ';
            }

            if($search_private == 1){
                $where .= ' AND c.'.JobBoardDbHelper::dbNameQuote('is_private').' >= 0 ';
            } else {
                $where .= ' AND c.'.JobBoardDbHelper::dbNameQuote('is_private').' = 0 ';
            }

            if($ed_level > 0){
               $where .= ' AND c.'.JobBoardDbHelper::dbNameQuote('highest_qual').' = ' .$ed_level. ' ';
            }
            $orderby = ' ORDER BY '.JobBoardDbHelper::dbNameQuote('modified_date').' DESC';
            $sql = 'SELECT c.'.JobBoardDbHelper::dbNameQuote('id').', c.'.JobBoardDbHelper::dbNameQuote('user_id').', c.'.JobBoardDbHelper::dbNameQuote('profile_name').', c.'.JobBoardDbHelper::dbNameQuote('created_date').', c.'.JobBoardDbHelper::dbNameQuote('modified_date').', c.'.JobBoardDbHelper::dbNameQuote('avail_date').', c.'.JobBoardDbHelper::dbNameQuote('is_linkedin').', c.'.JobBoardDbHelper::dbNameQuote('is_private').', c.'.JobBoardDbHelper::dbNameQuote('highest_qual').', c.'.JobBoardDbHelper::dbNameQuote('hits').'
                           ,u.'.JobBoardDbHelper::dbNameQuote('name').'
                          FROM '.JobBoardDbHelper::dbNameQuote('#__jobboard_cvprofiles').' AS c
                        INNER JOIN '.JobBoardDbHelper::dbNameQuote('#__users').' AS u
                          ON(u.'.JobBoardDbHelper::dbNameQuote('id').' = c.'.JobBoardDbHelper::dbNameQuote('user_id').')';
            // echo $sql.$where.$orderby; // die;
            return $sql.$where.$orderby;
        }
    }

	function getData()
	{
		if(empty($this->_data))
		{
			$query = $this->_buildQuery();
		    $this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));

            $count = $this->getTotal($query);
            $app = & JFactory::getApplication();
		    $app->setUserState('com_jobboard.cvlist.count', $count);
		}
	   return $this->_data;
	}


	private function _getProfilesByTitle($job_title)  {
	    $app = & JFactory::getApplication();
	    $db = & $this->getDBO();
        $where = ' WHERE '.JobBoardDbHelper::dbNameQuote('user_id').' <> '.$this->_uid.' ';

    	$fields = array(''.JobBoardDbHelper::dbNameQuote('job_title').'');
    	$ks_where = $keys_array = array();
        $sentence_mode = 0;
        if (preg_match('#^(\'|").+\1$#', $job_title) == 1){
           $keys_array[] = JobBoardDbHelper::dbEscape(trim($job_title,"\x22\x27"), true);
           $sentence_mode = 1;
        } else {
    	  $job_title = JobBoardDbHelper::dbEscape($job_title, true);
          $keys_array = explode(',', $job_title);
          $keys_array = explode(' ', $job_title);
        }

    	foreach($keys_array as $keywd) {
    		$keywd = JString::trim($keywd);
    		foreach($fields as $field)
    		{
    			$ks_where[] = $sentence_mode == 0? $field." LIKE '%{$keywd}%'" : $field." = '{$keywd}'";
    		}
    	}
    	$where .= ' AND ('.implode(' OR ',$ks_where).')';

    	$sql = 'SELECT DISTINCT('.JobBoardDbHelper::dbNameQuote('cvprof_id').') AS id
                    FROM '.JobBoardDbHelper::dbNameQuote('#__jobboard_past_employers').' '.$where.' LIMIT '.$this->_tinylimit;
        $db->setQuery($sql);
		$result = $db->loadResultArray();

    	$sql = 'SELECT DISTINCT('.JobBoardDbHelper::dbNameQuote('cvprof_id').') AS id , '.JobBoardDbHelper::dbNameQuote('job_title').' as value, COUNT(*) AS `count`
                    FROM '.JobBoardDbHelper::dbNameQuote('#__jobboard_past_employers').' '.$where.'
                     GROUP BY '.JobBoardDbHelper::dbNameQuote('job_title').' LIMIT '.$this->_tinylimit;
        $db->setQuery($sql);
		$sub_result = $db->loadAssocList();
        $sub_result = $this->_splitResults(&$sub_result);

        $app->setUserState('com_jobboard.cvsearch.title_filter', $sub_result, 'array');
        unset($sub_result);

		return $result;
	}

	private function _getProfilesBySkills($skills) {
	    $app = & JFactory::getApplication();
	    $db = & $this->getDBO();
        $where = ' WHERE '.JobBoardDbHelper::dbNameQuote('user_id').' <> '.$this->_uid.' ';

    	$fields = array(''.JobBoardDbHelper::dbNameQuote('skill_name').'');
    	$ks_where = array();
    	$skills = JobBoardDbHelper::dbEscape(trim($skills,"\x22\x27"),true);
    	$keys_array = explode(',', $skills);

    	foreach($keys_array as $keywd) {
    		$keywd = JString::trim($keywd);
    		foreach($fields as $field)
    		{
    			$ks_where[] = $field." LIKE '%{$keywd}%'";
    		}
    	}
    	$where .= ' AND ('.implode(' OR ',$ks_where).')';

    	$sql = 'SELECT DISTINCT('.JobBoardDbHelper::dbNameQuote('profile_id').') AS id
                    FROM '.JobBoardDbHelper::dbNameQuote('#__jobboard_userskills').' '.$where.' LIMIT '.$this->_tinylimit;

        $db->setQuery($sql);
		$result = $db->loadResultArray();

    	$sql = 'SELECT DISTINCT('.JobBoardDbHelper::dbNameQuote('profile_id').') AS id , '.JobBoardDbHelper::dbNameQuote('skill_name').' as value, COUNT(*) AS `count`
                    FROM '.JobBoardDbHelper::dbNameQuote('#__jobboard_userskills').' '.$where.'
                     GROUP BY '.JobBoardDbHelper::dbNameQuote('skill_name').' LIMIT '.$this->_tinylimit;
        $db->setQuery($sql);
		$sub_result = $db->loadAssocList();
        $sub_result = $this->_splitResults(&$sub_result);

        $app->setUserState('com_jobboard.cvsearch.skill_filter', $sub_result, 'array');
        unset($sub_result);

		return $result;
	}

	private function _getProfilesByQual($qualification) {
	    $app = & JFactory::getApplication();
	    $db = & $this->getDBO();
        $where = ' WHERE '.JobBoardDbHelper::dbNameQuote('user_id').' <> '.$this->_uid.' ';

    	$fields = array(''.JobBoardDbHelper::dbNameQuote('qual_name').'');
    	$ks_where = $keys_array = array();
        if (preg_match('#^(\'|").+\1$#', $qualification) == 1){
           $keys_array[] = JobBoardDbHelper::dbEscape(trim($qualification,"\x22\x27"), true);
           $sentence_mode = 1;
        } else {
    	  $qualification = JobBoardDbHelper::dbEscape($qualification, true);
          $keys_array = explode(',', $qualification);
          $keys_array = explode(' ', $qualification);
          $sentence_mode = 0;
        }

    	foreach($keys_array as $keywd) {
    		$keywd = JString::trim($keywd);
    		foreach($fields as $field)
    		{
    			$ks_where[] = $sentence_mode == 0? $field." LIKE '%{$keywd}%'" : $field." = '{$keywd}'";
    		}
    	}

    	$where .= ' AND ('.implode(' OR ',$ks_where).')';

    	$sql = 'SELECT DISTINCT('.JobBoardDbHelper::dbNameQuote('cvprof_id').') AS id
                    FROM '.JobBoardDbHelper::dbNameQuote('#__jobboard_past_edu').' '.$where;
        $db->setQuery($sql);
		$result = $db->loadResultArray();

    	$sql = 'SELECT DISTINCT('.JobBoardDbHelper::dbNameQuote('cvprof_id').') AS id , '.JobBoardDbHelper::dbNameQuote('qual_name').' as value, COUNT(*) AS `count`
                    FROM '.JobBoardDbHelper::dbNameQuote('#__jobboard_past_edu').' '.$where.'
                     GROUP BY '.JobBoardDbHelper::dbNameQuote('qual_name').' LIMIT '.$this->_tinylimit;
        $db->setQuery($sql);
		$sub_result = $db->loadAssocList();
        $sub_result = $this->_splitResults(&$sub_result);

        $app->setUserState('com_jobboard.cvsearch.qual_filter', $sub_result, 'array');
        unset($sub_result);

		return $result;
	}

    function getTitlesByProfileId($pids) {
        if(!empty($pids)) {
            $where = ' WHERE '.JobBoardDbHelper::dbNameQuote('user_id').' <> '.$this->_uid.' ';

        	foreach($pids as $pid) {
    			$ks_where[] = "`cvprof_id').' = {$pid}";
        	}

        	$where .= ' AND ('.implode(' OR ',$ks_where).')';
    	    $db = & $this->getDBO();
        	$sql = 'SELECT '.JobBoardDbHelper::dbNameQuote('job_title').', COUNT('.JobBoardDbHelper::dbNameQuote('job_title').') AS total
                FROM '.JobBoardDbHelper::dbNameQuote('#__jobboard_past_employers').' '.$where.' GROUP BY job_title LIMIT '.$this->_tinylimit;
            $db->setQuery($sql);
    		return $db->loadRowList();
        } else return array();
    }

    function getSkillsByProfileId($pids) {
        if(!empty($pids)) {
            $where = ' WHERE '.JobBoardDbHelper::dbNameQuote('user_id').' <> '.$this->_uid.' ';

        	foreach($pids as $pid) {
    			$ks_where[] = "".JobBoardDbHelper::dbNameQuote('profile_id')."').' = {$pid}";
        	}

        	$where .= ' AND ('.implode(' OR ',$ks_where).')';
    	    $db = & $this->getDBO();
        	$sql = 'SELECT '.JobBoardDbHelper::dbNameQuote('skill_name').', COUNT('.JobBoardDbHelper::dbNameQuote('skill_name').') AS total
                FROM '.JobBoardDbHelper::dbNameQuote('#__jobboard_userskills').' '.$where.' GROUP BY skill_name LIMIT '.$this->_tinylimit;
            $db->setQuery($sql);
    		return $db->loadRowList();
        } else return array();
    }

    function getQualsByProfileId($pids) {
        if(!empty($pids)) {
            $where = ' WHERE '.JobBoardDbHelper::dbNameQuote('user_id').' <> '.$this->_uid.' ';

        	foreach($pids as $pid) {
    			$ks_where[] = JobBoardDbHelper::dbNameQuote('cvprof_id')." = {$pid}";
        	}

        	$where .= ' AND ('.implode(' OR ',$ks_where).')';
    	    $db = & $this->getDBO();
        	$sql = 'SELECT '.JobBoardDbHelper::dbNameQuote('qual_name').', COUNT('.JobBoardDbHelper::dbNameQuote('qual_name').') AS total
                FROM '.JobBoardDbHelper::dbNameQuote('#__jobboard_past_edu').' '.$where.' GROUP BY qual_name LIMIT '.$this->_tinylimit;
            $db->setQuery($sql);
    		return $db->loadRowList();
        } else return array();
    }

    private function _removeDuplicates($arr, $duplicates) {
        foreach($duplicates as $dupl) {
           $key = array_search($dupl, $arr);
            unset($arr[$key]);
         }
         return $arr;
    }

    private function _splitResults($arr) {
          $vals_array = array();

          if(!empty($arr)){
            foreach($arr as $row) {
               $vals_array[] = array($row['value'], $row['count']);
             }
          }

         return $vals_array;
    }

	/**
	 * Method to get the total number of items
	 *
	 * @access public
	 * @return integer
	 */
	function getTotal($query=null)
	{
        $app = & JFactory::getApplication();
		if (empty($this->_total)) {
			if(!$query) $query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}
		return $this->_total;
	}

	/**
	 * Method to get a pagination object of the weblink items for the category
	 *
	 * @access public
	 * @return integer
	 */
	function getPagination()
	{
        $app = & JFactory::getApplication();
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}
		return $this->_pagination;
	}

}

?>