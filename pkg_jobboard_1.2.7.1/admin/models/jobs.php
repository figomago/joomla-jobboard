<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class JobboardModelJobs extends JModel
{
	var $_total = null;
	var $_pagination = null;
	var $_search = null;
	var $_query = null;
	var $_data = null;
	var $_job = null;
	var $_applicants = null;
	var $_countries = null;
	var $_careers = null;
	var $_education = null;
	var $_categories = null;

	function __construct()
	{
		parent::__construct();
 
        $option = 'com_jobboard';
        $app= JFactory::getApplication();
 
        // Get pagination request variables
        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
        $limitstart = JRequest::getVar('limitstart', 0, '', 'int');
 
        // In case limit has been changed, adjust it
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
 
        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
        $this->flush();
	}

    function flush() {
    	$this->_total = null;
    	$this->_pagination = null;
    	$this->_search = null;
    	$this->_query = null;
    	$this->_data = null;
    	$this->_job = null;
    	$this->_applicants = null;
    	$this->_countries = null;
    	$this->_careers = null;
    	$this->_education = null;
    	$this->_categories = null;

    }

	function getTotal()
	{
		// Load the content if it doesn't already exist
		if (empty($this->_total)) {
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);    
		}
		return $this->_total;
	}
	
	function getPagination()
	{
        // Load the content if it doesn't already exist
        if (empty($this->_pagination)) {
            jimport('joomla.html.pagination');
            $this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
        }
        return $this->_pagination;
	}

	function getData()
	{
		$app= JFactory::getApplication();
		
		if(empty($this->_data))
		{
			$query = $this->_buildQuery();

			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
			$this->count = $this->getTotal();
			$app->setUserState('com_jobboard.jobs.count',$this->count);
		}
		
		return $this->_data;
	}
      
	function getSearch()
	{
		if(!$this->_search)
		{
			$app= JFactory::getApplication();
			
			$search = $app->getUserStateFromRequest("com_jobboard.jobs.search", 'search', '', 'string');
			$this->_search = JString::strtolower($search);
		}

		return $this->_search;
	}

	function _buildQuery()
	{
		if(!$this->_query)
		{
			$search = $this->getSearch();
			$this->_query = "SELECT jb.id
                          , jb.job_title
                          , jc.type AS category
                          , jb.job_type
                          , jb.post_date
                          , jb.positions
                          , jb.num_applications
                          , jb.hits
                          , jb.published
                          , jb.featured
                          , jb.ref_num
                      FROM
                          #__jobboard_jobs AS jb
                          INNER JOIN #__jobboard_categories AS jc
                              ON (jb.category = jc.id)";
			
			if($search != '')
			{
				$fields = array('category', 'jb.job_title', 'jb.description', 'jb.duties', 'jb.ref_num');
				$where = array();
				$search = $this->_db->getEscaped($search,true);
				
				foreach($fields as $field)
				{
					$where[] = $field." LIKE '%{$search}%'";
				}

				$this->_query .= ' WHERE '.implode(' OR ',$where);
			}

			$this->_query .= $this->_buildQueryOrderBy();
		}

		return $this->_query;
	}

    /**
    * Builds an ORDER BY clause for the getData query
    *
    * @return string
    */
    function _buildQueryOrderBy() { // get the application and DBO
      $app= JFactory::getApplication();
      
      $db =& $this->getDBO();
      $defaultOrderField = 'post_date';
      $order = $app->getUserStateFromRequest('com_jobboard.jobs.filterOrder', 'filter_order', $defaultOrderField);
      //$order = ($order == 'status')? 'enabled' : $order;
      $orderDirection = $app->getUserStateFromRequest('com_jobboard.jobs.filterOrderDirection', 'filter_order_Dir', 'ASC', 'cmd');
      $orderDirection = (strtoupper($orderDirection) == 'ASC')? 'DESC' : 'ASC';
      return ' ORDER BY ' . $db->nameQuote($order) ." $orderDirection ";
    }

    function getJob($id)
	{

		if(empty($this->_job))
		{
            $db =& $this->getDBO();
			$this->_query = 'SELECT *
                  FROM
                      #__jobboard_jobs
                      WHERE id = ' . intval($id);
            $db->setQuery($this->_query);
            $this->_job = $db->loadObject();
		}
	     return $this->_job;
	}

    function getApplicants($job_id)
	{
        $db =& $this->getDBO();
        $this->_query = 'SELECT a.id, a.first_name, a.last_name, a.request_date
                  FROM
                      #__jobboard_applicants AS a
                      WHERE a.job_id = ' . intval($job_id);
          $db->setQuery($this->_query);
          $this->_applicants = $db->loadAssocList();
         //  echo $this->_applicants ;
	      return $this->_applicants;
	}

    function getCountries() {
          $db =& $this->getDBO();
		  $this->_query = 'SELECT * FROM #__jobboard_countries';
          $db->setQuery($this->_query);
          $this->_countries = $db->loadObjectList();
	      return $this->_countries;
    }

    function getCareers() {
          $db =& $this->getDBO();
		  $this->_query = 'SELECT * FROM #__jobboard_career_levels';
          $db->setQuery($this->_query);
          $this->_careers = $db->loadObjectList();
	      return $this->_careers;
    }

    function getEducation() {
          $db =& $this->getDBO();
		  $this->_query = 'SELECT * FROM #__jobboard_education';
          $db->setQuery($this->_query);
          $this->_education = $db->loadObjectList();
	      return $this->_education;
    }

    function getCategories() {
          $db =& $this->getDBO();
		  $this->_query = 'SELECT * FROM #__jobboard_categories';
          $db->setQuery($this->_query);
          $this->_categories = $db->loadObjectList();
	      return $this->_categories;
    }

    function deleteJobs($serialised_id_array) {
          $db =& $this->getDBO();
		  $this->_query =  'DELETE FROM #__jobboard_jobs'
			. ' WHERE id IN ( '. $serialised_id_array .' )';
          $db->setQuery($this->_query);
          $delete_result = $db->Query();
          $delete_result = ($delete_result == true)? $delete_result : $db->getErrorMsg(true);
	      return $delete_result;
    }
       
    function getConfig() {
           $db = & $this->getDBO();
           $sql = 'SELECT long_date_format, short_date_format, date_separator, use_location FROM #__jobboard_config
                      WHERE id = 1';
           $db->setQuery($sql);
           $this->_result = $db->loadObject();
           return $this->_result;
    }
    
    function getListConfig() {
           $db = & $this->getDBO();
           $sql = 'SELECT long_date_format FROM #__jobboard_config
                      WHERE id = 1';
           $db->setQuery($sql);
           $this->_result = $db->loadObject();
           return $this->_result;
    }

	function setPublishStatus($status, $cids) {
		
		$db = & $this->getDBO();		 
		$query = 'UPDATE #__jobboard_jobs
					 SET published = ' . (int) $status .'
					 WHERE id IN ( '. $cids.'  )';
		$db->setQuery( $query );
		return $db->query();
	}


    /**
     * Get available questionnaires
     *
     * @params none
     *
     * @return assoc
     */
      function getQuestionnaires() {
             $db = & $this->getDBO();
               $sql = 'SELECT q.`id`, q.`qid`, q.`created_by`, q.`title`, q.`fields`,
                              u.`name`
                            FROM #__jobboard_questionnaires AS q
                            INNER JOIN #__users AS u
                            ON(u.`id` = q.`created_by`)
                            ORDER BY q.`id` DESC';
             $db->setQuery($sql); 
             return $db->loadAssocList();
      }

    /**
     * Toggle job feature status
     *
     * @param $value boolean
     * @param $cids array job ids
     *
     * @return boolean
     */
	function setFeatureStatus($value, $cids) {

		$db = & $this->getDBO();
		$query = 'UPDATE '.$db->nameQuote('#__jobboard_jobs').'
					 SET '.$db->nameQuote('featured').' = ' . (int) $value .'
					 WHERE '.$db->nameQuote('id').' IN ( '. $cids.'  )';
		$db->setQuery( $query );
		return $db->query();
	}
     /**
     * Get total active applications for job
     * Exclude current user job applications
     * @params job id, user id
     *
     * @return assoc
     */
     function getJobApplsCount($jid) {

             $db = & $this->getDBO();
             $sql = 'SELECT COUNT(a.id)
                     FROM  `#__jobboard_usr_applications` AS a
                     INNER JOIN `#__jobboard_jobs` AS j
                     ON(j.`id` = a.`job_id`)
                     WHERE a.`job_id` = '.$jid.' AND j.`published` = 1';
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
}
?>