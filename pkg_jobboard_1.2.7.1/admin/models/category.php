<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class JobboardModelCategory extends JModel
{
	var $_total = null;
	var $_pagination = null;
	var $_search = null;
	var $_query = null;
	var $data = null;

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
		
		if(empty($this->data))
		{
			$query = $this->_buildQuery();

			$this->data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
			$this->count = $this->getTotal();
			$app->setUserState('com_jobboard.category.count',$this->count);
		}
		
		return $this->data;
	}

	function getSearch()
	{
		if(!$this->_search)
		{
			$app= JFactory::getApplication();
			
			$search = $app->getUserStateFromRequest("com_jobboard.category.search", 'search', '', 'string');
			$this->_search = JString::strtolower($search);
		}
		
		return $this->_search;
	}

	function _buildQuery()
	{
		if(!$this->_query)
		{
			$search = $this->getSearch();
			$this->_query = "SELECT mca.id, mca.type, mca.enabled
                    FROM #__jobboard_categories AS mca                       ";
			
			if($search != '')
			{
				$fields = array('mca.type');
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
      $defaultOrderField = 'type';
      $order = $app->getUserStateFromRequest('com_jobboard.category.filterOrder', 'filter_order', $defaultOrderField);
      $order = ($order == 'status')? 'enabled' : $order;
      $orderDirection = $app->getUserStateFromRequest('com_jobboard.category.filterOrderDirection', 'filter_order_Dir', 'ASC', 'cmd');
      $orderDirection = (strtoupper($orderDirection) == 'ASC')? 'DESC' : 'ASC';
      return ' ORDER BY ' . $db->nameQuote($order) ." $orderDirection ";
    }    

    function deleteCategories($serialised_id_array) {
          $db =& $this->getDBO();
		  $this->_query =  'DELETE FROM #__jobboard_categories'
			. ' WHERE id IN ( '. $serialised_id_array .' )';
          $db->setQuery($this->_query);
          $delete_result = $db->Query();
          $delete_result = ($delete_result == true)? $delete_result : $db->getErrorMsg(true);
	      return $delete_result;
    }

	function setPublishStatus($status, $cids) {
		
		$db = & $this->getDBO();		 
		$query = 'UPDATE #__jobboard_categories
					 SET enabled = ' . (int) $status .'
					 WHERE id IN ( '. $cids.'  )';
		$db->setQuery( $query );
		return $db->query();
	}
}
?>