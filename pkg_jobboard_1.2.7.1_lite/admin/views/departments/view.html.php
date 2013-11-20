<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');


jimport('joomla.application.component.view');

class JobboardViewDepartments extends JView
{
	function display($tpl = null)
	{
        $app= JFactory::getApplication();
        
		$rows =& $this->get('data');
		$this->search =& $this->get('search');
        $this->search = !empty($this->search)? $this->escape($this->search) : $this->search;
        $pagination =& $this->get('pagination');
		$this->assignRef('rows',$rows);
		$this->assignRef('pagination',$pagination);
		$this->assign('jb_render', JobBoardHelper::renderJobBoardx());
        $lists['order'] = $app->getUserStateFromRequest('com_jobboard.departments.filterOrder', 'filter_order', 'name', 'word');
        $lists['orderDirection'] = $app->getUserStateFromRequest( 'com_jobboard.departments.filterOrderDirection', 'filter_order_Dir', 'ASC', 'cmd');
        $lists['orderDirection'] = (strtoupper($lists['orderDirection']) == 'ASC')? 'ASC' : 'DESC';
        $this->assignRef('lists', $lists);
		parent::display($tpl);
	}
}