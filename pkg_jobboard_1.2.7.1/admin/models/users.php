<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class JobboardModelUsers extends JModel
{
	private $_total = null;
	private $_pagination = null;
	private $_search = null;
	private $_group = null;
	private $_query = null;
	private $_data = null;
	private $_job = null;
	private $_applicants = null;
	private $_countries = null;
	private $_careers = null;
	private $_education = null;
	private $_categories = null;

	function __construct()
	{
		parent::__construct();
                                   
        $app= JFactory::getApplication();
 
        // Get pagination request variables
        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
        $limitstart = JRequest::getVar('limitstart', 0, '', 'int');
 
        // In case limit has been changed, adjust it
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
 
        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);

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
			$app->setUserState('com_jobboard.users.count',$this->count);
		}

		return $this->_data;
	}
      
	function getSearch()
	{
		if(!$this->_search)
		{
			$app= JFactory::getApplication();
			
			$search = $app->getUserStateFromRequest("com_jobboard.users.search", 'search', '', 'string');
			$this->_search = JString::strtolower($search);
		}

		return $this->_search;
	}

	function getGroup()
	{
		if(!$this->_group)
		{
			$app= JFactory::getApplication();
			$this->_group = $app->getUserStateFromRequest("com_jobboard.users.group", 'group', 0, 'int');

		}
		return $this->_group;
	}

	function _buildQuery()
	{
		if(!$this->_query)
		{
		    $db = & $this->getDBO();
			$search = $this->getSearch();
			$group = $this->getGroup();
			$this->_query = "SELECT u.".$db->nameQuote('id')."
                          , u.".$db->nameQuote('user_id')."
                          , u.".$db->nameQuote('group_id')."
                          , u.".$db->nameQuote('user_status')." AS published
                          , u.".$db->nameQuote('feature_jobs')."
                          , g.".$db->nameQuote('group_name')."
                          , ju.".$db->nameQuote('username')."
                          , ju.".$db->nameQuote('name')."
                      FROM
                          ".$db->nameQuote('#__jobboard_users')." AS u
                          INNER JOIN ".$db->nameQuote('#__jobboard_usr_groups')." AS g
                          ON(g.".$db->nameQuote('id')." = u.".$db->nameQuote('group_id').")
                          INNER JOIN ".$db->nameQuote('#__users')." AS ju
                          ON(ju.".$db->nameQuote('id')." = u.".$db->nameQuote('user_id').")"
                          ;

			if($search != '')
			{
				$fields = array('ju.'.$this->_db->nameQuote('name'), 'ju.'.$this->_db->nameQuote('username'));
				$where = array();
				$search = $this->_db->getEscaped($search, true);

				foreach($fields as $field)
				{
					$where[] = $field." LIKE '%{$search}%'";
				}

				$this->_query .= ' WHERE '.implode(' OR ',$where);
			}

            if($group != 0)
			{
			  if($search == '')
				$this->_query .= ' WHERE g.'.$this->_db->nameQuote('id').' = '.$group;
              else
				$this->_query .= ' AND g.'.$this->_db->nameQuote('id').' = '.$group;
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
    function _buildQueryOrderBy() {
      $app= JFactory::getApplication();
      
      $db =& $this->getDBO();
      $defaultOrderField = 'id';
      $order = $app->getUserStateFromRequest('com_jobboard.users.filterOrder', 'filter_order', $defaultOrderField);
      //$order = ($order == 'status')? 'enabled' : $order;
      $orderDirection = $app->getUserStateFromRequest('com_jobboard.users.filterOrderDirection', 'filter_order_Dir', 'DESC', 'cmd');
      $orderDirection = (strtoupper($orderDirection) == 'ASC')? 'DESC' : 'ASC';
      return ' ORDER BY ' . $db->nameQuote($order) ." $orderDirection ";
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

	function setUserGroup($uid, $gid) {

		$db = & $this->getDBO();
		$query = 'UPDATE '.$db->nameQuote('#__jobboard_users').'
					 SET '.$db->nameQuote('group_id').' = ' . (int) $gid .'
					 WHERE '.$db->nameQuote('user_id').' = '. $uid.'  ';
		$db->setQuery( $query );
		return $db->query();
	}

	function setFeatureStatus($value, $cids) {

		$db = & $this->getDBO();
		$query = 'UPDATE '.$db->nameQuote('#__jobboard_users').'
					 SET '.$db->nameQuote('feature_jobs').' = ' . (int) $value .'
					 WHERE '.$db->nameQuote('id').' IN ( '. $cids.'  )';
		$db->setQuery( $query );
		return $db->query();
	}

	function setPublishStatus($status, $cids) {

		$db = & $this->getDBO();
		$query = 'UPDATE '.$db->nameQuote('#__jobboard_users').'
					 SET '.$db->nameQuote('user_status').' = ' . (int) $status .'
					 WHERE '.$db->nameQuote('id').' IN ( '. $cids.'  )';
		$db->setQuery( $query );
		return $db->query();
	}

	function deleteGhostUsers() {

		$db = & $this->getDBO();
		$query = 'DELETE FROM '.$db->nameQuote('#__jobboard_users').'
					 WHERE '.$db->nameQuote('user_id').' NOT IN (
                     SELECT '.$db->nameQuote('id').' FROM '. $db->nameQuote('#__users').'  )';
		$db->setQuery( $query );
		return $db->query();
	}
                      
	function getimportUsers() {
		$db = & $this->getDBO();
		$query = 'SELECT '.$db->nameQuote('id').' FROM '.$db->nameQuote('#__users').'
					 WHERE '.$db->nameQuote('id').' NOT IN (
                     SELECT '.$db->nameQuote('user_id').' FROM '. $db->nameQuote('#__jobboard_users').'  )';
		$db->setQuery( $query );
		return $db->loadResultArray();
	}

	function jImportUsers($users) {
		$db = & $this->getDBO();
        $values = '';
        if(!empty($users)) {
           foreach($users as $user){
              $values .= '('.$user.'), ';
           }
           $values = substr($values, 0, -2);
        }
		$query = 'INSERT INTO '.$db->nameQuote('#__jobboard_users').' ('.$db->nameQuote('user_id').')
					 VALUES '. $values;
		$db->setQuery( $query );
		return $db->query();
	}
}
?>