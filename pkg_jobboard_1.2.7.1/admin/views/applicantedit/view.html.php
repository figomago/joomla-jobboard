<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class JobboardViewApplicantEdit extends JView
{
	function display($tpl = null)
	{
		$task = JRequest::getVar('task', '');
		$row =& $this->get('data');
                                                                
		$this->assignRef('row', $row);
		$this->assign('jb_render', JobBoardHelper::renderJobBoardx());

        $this->day_format = !version_compare( JVERSION, '1.6.0', 'ge' )? '%d' : 'd';
        $this->long_day_format = !version_compare( JVERSION, '1.6.0', 'ge' )? '%a' : 'D';
        $this->month_long_format = !version_compare( JVERSION, '1.6.0', 'ge' )? '%B' : 'F';
        $this->month_short_format = !version_compare( JVERSION, '1.6.0', 'ge' )? '%b' : 'M';
        $this->year_format = !version_compare( JVERSION, '1.6.0', 'ge' )? '%Y' : 'Y';

	    $_format = JRequest::getString('tmpl', '');
        $this->is_modal = $_format == 'component'? true : false;
        if(!$this->is_modal) {
            require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_behavior.php');
        }

		parent::display($tpl);
	}
}

?>