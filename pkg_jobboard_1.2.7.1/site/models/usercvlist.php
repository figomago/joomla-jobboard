<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// Protect from unauthorized access
defined('_JEXEC') or die();

jimport('joomla.application.component.model');

class JobboardModelUsercvlist extends JModel
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
		$config =& JFactory::getConfig();
        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart = JRequest::getVar('limitstart', 0, '', 'int');
        //  $limit = 2;
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}

    function _buildQuery()
	{
		$uid = JRequest::getInt('uid', 0);
		if (empty($this->_data))
		{
		    if($uid <> 0) $where = ' WHERE `user_id` = '.$uid.' ';
            else $where =  ' ';
            $orderby = ' ORDER BY `id` DESC';
            $sql = 'SELECT `id`, `user_id`, `profile_name`, `created_date`, `modified_date`, `avail_date`, `is_linkedin`, `is_private`, `hits` as profile_hits
                          FROM #__jobboard_cvprofiles ';
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
		    $app->setUserState('com.jobboard.user.cvlist.count', $count);
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