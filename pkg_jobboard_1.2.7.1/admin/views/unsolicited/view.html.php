<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class JobboardViewUnsolicited extends JView
{
	function display($tpl = null)
	{
	    $app= JFactory::getApplication();
	    
		$rows =& $this->get('data');
		$pagination =& $this->get('pagination');
		$search = $this->get('search');

		$this->assignRef('rows',$rows);
		$this->assignRef('pagination', $pagination);
		$this->assign('search', $search);
		$this->assign('jb_render', JobBoardHelper::renderJobBoardx());
        $lists['order'] = $app->getUserStateFromRequest('com_jobboard.unsolicited.filterOrder', 'filter_order', 'request_date');
        $lists['orderDirection'] = $app->getUserStateFromRequest( 'com_jobboard.unsolicited.filterOrderDirection', 'filter_order_Dir', 'DESC', 'cmd');
        $lists['orderDirection'] = (strtoupper($lists['orderDirection']) == 'ASC')? 'ASC' : 'DESC';
        $this->assignRef('lists', $lists);
        $this->day_format = !version_compare( JVERSION, '1.6.0', 'ge' )? '%d' : 'd';
        $this->long_day_format = !version_compare( JVERSION, '1.6.0', 'ge' )? '%a' : 'D';
        $this->month_long_format = !version_compare( JVERSION, '1.6.0', 'ge' )? '%B' : 'F';
        $this->month_short_format = !version_compare( JVERSION, '1.6.0', 'ge' )? '%b' : 'M';
        $this->year_format = !version_compare( JVERSION, '1.6.0', 'ge' )? '%Y' : 'Y';
		
		parent::display($tpl);
	}
}

?>