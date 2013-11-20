<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables');

class JobboardViewUser extends JView
{
	function display($tpl = null)
	{
		$task = JRequest::getVar('task', '');
		$cid = JRequest::getVar('cid', false, 'DEFAULT', 'array');
        if($cid){
          $id = $cid[0];
        }
        else $id = JRequest::getInt('id', 0);
        $newjob = ($id > 0)? false : true;
        if($newjob) {
           /* $cfigt = JTable::getInstance('Config', 'Table');
            $cfigt->load(1);
			$this->assignRef('config', $cfigt);*/
        }

        $this->day_format = !version_compare( JVERSION, '1.6.0', 'ge' )? '%d' : 'd';
        $this->long_day_format = !version_compare( JVERSION, '1.6.0', 'ge' )? '%a' : 'D';
        $this->month_long_format = !version_compare( JVERSION, '1.6.0', 'ge' )? '%B' : 'F';
        $this->month_short_format = !version_compare( JVERSION, '1.6.0', 'ge' )? '%b' : 'M';
        $this->year_format = !version_compare( JVERSION, '1.6.0', 'ge' )? '%Y' : 'Y';
        
		$this->assign('jb_render', JobBoardHelper::renderJobBoardx());
		// $this->assign('newjob', $newjob);

		parent::display($tpl);
	}                 
}

?>