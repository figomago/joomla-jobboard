<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class JobboardModelStatuses extends JModel
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
			$app->setUserState('com_jobboard.statuses.count',$this->count);
		}

		return $this->data;
	}

	function getSearch()
	{
		if(!$this->_search)
		{
			$app= JFactory::getApplication();
			$option = 'com_jobboard';
			$search = $app->getUserStateFromRequest("com_jobboard.statuses.search", 'search', '', 'string');
			$this->_search = JString::strtolower($search);
		}

		return $this->_search;
	}

	function _buildQuery()
	{
		if(!$this->_query)
		{
			$search = $this->getSearch();
			$this->_query = "SELECT *
                    FROM #__jobboard_statuses ";

			if($search != '')
			{
				$fields = array('status_description');
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
		$defaultOrderField = 'id';
		$order = $app->getUserStateFromRequest('com_jobboard.statuses.filterOrder', 'filter_order', $defaultOrderField);
		$orderDirection = $app->getUserStateFromRequest('com_jobboard.statuses.filterOrderDirection', 'filter_order_Dir', 'DESC', 'cmd');
		$orderDirection = (strtoupper($orderDirection) == 'ASC')? 'DESC' : 'ASC';
		return ' ORDER BY ' . $db->nameQuote($order) ." $orderDirection ";
	}

	function deleteStatuses($serialised_id_array) {
		$db =& $this->getDBO();
		$this->_query =  'DELETE FROM #__jobboard_statuses'
		. ' WHERE id IN ( '. $serialised_id_array .' )';
		$db->setQuery($this->_query);
		$delete_result = $db->Query();
		$delete_result = ($delete_result == true)? $delete_result : $db->getErrorMsg(true);
		return $delete_result;
	}
}
?>