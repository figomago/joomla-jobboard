<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// Protect from unauthorized access
defined('_JEXEC') or die();

jimport('joomla.application.component.model');

class JobboardModelAdminviteslist extends JModel
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
		$uid = JRequest::getInt('suid', 0);
		if (empty($this->_data))
		{
		    if($uid <> 0) $where = ' WHERE i.`sender_id` = '.$uid.'  AND j.`published` = 1 ';
            else $where =  ' WHERE j.`published` = 1 ';
            $orderby = ' ORDER BY i.`id` DESC';
            $sql = 'SELECT i.*, u.`name` AS invitee, j.`job_title`, j.`questionnaire_id` AS qid
                  FROM
                    `#__jobboard_invites` AS i
                    INNER JOIN `#__users` AS u
                        ON (u.`id` = i.`user_id`)
                    INNER JOIN `#__jobboard_jobs` AS j
                        ON (j.`id` = i.`job_id`)  ';
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
		    $app->setUserState('com_jobboard.admin.invlist.count', $count);
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