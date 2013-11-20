<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class JobboardModelApplicants extends JModel
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
		$option = 'com_jobboard';

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
			$app->setUserState('com_jobboard.applicants.count',$this->count);
		}

		return $this->data;
	}

	function getSearch()
	{
		if(!$this->_search)
		{
			$app= JFactory::getApplication();
			
			$search = $app->getUserStateFromRequest("com_jobboard.applicants.search", 'search', '', 'string');
			$this->_search = JString::strtolower($search);
		}

		return $this->_search;
	}

	function _buildQuery()
	{
		if(!$this->_query)
		{
			$search = $this->getSearch();
			$this->_query = 'SELECT a.'.$this->_db->nameQuote('id').', a.'.$this->_db->nameQuote('applied_on').' as request_date, a.'.$this->_db->nameQuote('job_id').', a.'.$this->_db->nameQuote('qid').'
                            , a.'.$this->_db->nameQuote('status_id').' AS '.$this->_db->nameQuote('status').', a.'.$this->_db->nameQuote('cvprof_id').' AS cvid, a.'.$this->_db->nameQuote('user_id').'
                            , ju.'.$this->_db->nameQuote('name').', jb.'.$this->_db->nameQuote('job_title').', cv.'.$this->_db->nameQuote('profile_name').'
                            , jd.'.$this->_db->nameQuote('name').' AS department

                        FROM
                            '.$this->_db->nameQuote('#__jobboard_jobs').' AS jb
                            INNER JOIN '.$this->_db->nameQuote('#__jobboard_usr_applications').' AS a
                                ON (jb.'.$this->_db->nameQuote('id').' = a.'.$this->_db->nameQuote('job_id').')
                            INNER JOIN '.$this->_db->nameQuote('#__jobboard_departments').' AS jd
                                ON (jd.'.$this->_db->nameQuote('id').' = jb.'.$this->_db->nameQuote('department').')
                            INNER JOIN '.$this->_db->nameQuote('#__users').' AS ju
                                ON (ju.'.$this->_db->nameQuote('id').' = a.'.$this->_db->nameQuote('user_id').')
                            JOIN '.$this->_db->nameQuote('#__jobboard_cvprofiles').' AS cv
                                ON (cv.'.$this->_db->nameQuote('id').' = a.'.$this->_db->nameQuote('cvprof_id').')';
			if($search != '')
			{
				$fields = array('ju.'.$this->_db->nameQuote('name'), 'jb.'.$this->_db->nameQuote('job_title'));
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

	   //	echo $this->_query;
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
		$defaultOrderField = 'request_date';
		$order = $app->getUserStateFromRequest('com_jobboard.applicants.filterOrder', 'filter_order', $defaultOrderField);
		$orderDirection = $app->getUserStateFromRequest('com_jobboard.applicants.filterOrderDirection', 'filter_order_Dir', 'DESC', 'cmd');
		$orderDirection = (strtoupper($orderDirection) == 'ASC')? 'DESC' : 'ASC';
		return ' ORDER BY ' . $db->nameQuote($order) ." $orderDirection ";
	}
}
?>