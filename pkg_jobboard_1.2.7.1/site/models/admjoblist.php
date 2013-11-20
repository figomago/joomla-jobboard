<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// Protect from unauthorized access
defined('_JEXEC') or die();

jimport('joomla.application.component.model');

class JobboardModelAdmjoblist extends JModel
{
	var $_data = null;
    var $_db = null;
	var $_total = null;
	var $_pagination = null;

	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();

        $app = & JFactory::getApplication();
		$jmode = $app->getUserStateFromRequest('com_jobboard.admin.joblist.jmode', 'jmode', '', 'string');
		$config =& JFactory::getConfig();
        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart = JRequest::getVar('limitstart', 0, '', 'int');
        //$limit = 10;
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		$this->setState('jmode', $jmode);
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}

    function _buildQuery()
	{
		$uid = JRequest::getInt('uid', 0);
		$sid = JRequest::getInt('sid', 0);
		$jmode = $this->getState('jmode', '');
		if (empty($this->_data))
		{
		    if($uid <> 0) {
		      $where = 'WHERE `posted_by`='.$uid.' ';
              if($sid <> 0) $where .= 'AND `published` = 1 ';
            } else $where = 'WHERE true ';
            $where_and = '';
            if(!empty($jmode) && $jmode <> '0' && $sid == 0) {
              switch($jmode){
                case 'active' :
                     $where_and .= ' AND `published` = 1 ';
                break;
                case 'inactive' :
                     $where_and .= ' AND `published` = 0 ';
                break;
                case 'featured' :
                     $where_and .= ' AND `published` = 1 AND `featured` = 1 ';
                break;
              }
            }
            $orderby = ' ORDER BY `post_date` DESC';
            $sql = 'SELECT `id`, `post_date`, `expiry_date`, `job_title`, `job_type`, `category`
                           , `positions`, `country`, `city`, `job_tags`, `department`
                           , `status`, `num_applications`, `published`, `featured`, `ref_num`
                        FROM `#__jobboard_jobs`';
            return $sql.$where.$where_and.$orderby;
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
		    $app->setUserState('com.jobboard.admin.joblist.count', $count);
		}
	   return $this->_data;
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