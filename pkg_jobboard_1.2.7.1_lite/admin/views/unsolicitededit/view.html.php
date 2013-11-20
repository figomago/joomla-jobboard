<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class JobboardViewUnsolicitedEdit extends JView
{
	function display($tpl = null)
	{
		$task = JRequest::getVar('task', '');
		$row =& $this->get('data');
        
		$this->assign('jb_render', JobBoardHelper::renderJobBoardx());
		$this->assignRef('config', JRequest::getVar('config', ''));

		$this->assignRef('row', $row);

        $this->day_format = !version_compare( JVERSION, '1.6.0', 'ge' )? '%d' : 'd';
        $this->long_day_format = !version_compare( JVERSION, '1.6.0', 'ge' )? '%a' : 'D';
        $this->month_long_format = !version_compare( JVERSION, '1.6.0', 'ge' )? '%B' : 'F';
        $this->month_short_format = !version_compare( JVERSION, '1.6.0', 'ge' )? '%b' : 'M';
        $this->year_format = !version_compare( JVERSION, '1.6.0', 'ge' )? '%Y' : 'Y';

		parent::display($tpl);
	}
}

?>