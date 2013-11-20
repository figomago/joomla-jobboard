<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_users.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_grid.php');
jimport('joomla.application.component.view');

class JobboardViewUsers extends JView
{
	function display($tpl = null)
	{
        $app= JFactory::getApplication();
        
		$rows =& $this->get('data');
		$pagination =& $this->get('pagination');
    	$app= JFactory::getApplication();
    	$this->group = $app->getUserState("com_jobboard.users.group");
        $this->config = & $this->get('ListConfig');

		$this->search = $this->get('search');
		
		$this->assignRef('rows',$rows);
		$this->assignRef('pagination', $pagination);
		$this->assign('jb_render', JobBoardHelper::renderJobBoardx());
        $lists['order'] = $app->getUserStateFromRequest('com_jobboard.users.filterOrder', 'filter_order', 'id');
        $lists['orderDirection'] = $app->getUserStateFromRequest( 'com_jobboard.users.filterOrderDirection', 'filter_order_Dir', 'ASC', 'cmd');
        $lists['orderDirection'] = (strtoupper($lists['orderDirection']) == 'ASC')? 'ASC' : 'DESC';
        $this->assignRef('lists', $lists);
        if(!empty($this->search)) $this->search = $this->escape($this->search);
		
		parent::display($tpl);
	}
}

?>