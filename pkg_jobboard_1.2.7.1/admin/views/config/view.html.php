<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class JobboardViewConfig extends JView
{
	function display($tpl = null)
	{

	    if(version_compare( JVERSION, '1.7.0', 'ge' )){
  	      // Options button.
          if (JFactory::getUser()->authorise('core.admin', 'com_jobboard')) {
          	JToolBarHelper::preferences('com_jobboard');
          }
        }
		$task = JRequest::getVar('task', '');

		//get config data
		$row =& JTable::getInstance('Config', 'Table');
		$id = 1; //there is only one config record, with the id=1 (set during installation) so we don't need to look at the cid even though it might be sent

        if(!$row->load($id))
		{
			JError::raiseError(500, $row->getError());
		}
        else{
		    $this->assignRef('row',$row);
        }

        switch($this->section) {
             case 'general':

             break;
             case 'users':
                 $this->user_groups = & $this->get('UserGroups');
             break;
             case 'jobs':
                $this->dist_array = array(10,15,20,30,50,70,100,300,500,1000,5000,10000);
             break;
             default:
             ;break;
        }

       /* $this->assignRef('depts', JRequest::getVar('depts', ''));
        $this->assignRef('countries', JRequest::getVar('countries', ''));
        $this->assignRef('careers', JRequest::getVar('careers', ''));
        $this->assignRef('edu', JRequest::getVar('edu', ''));
        $this->assignRef('jobtypes', JRequest::getVar('jobtypes', ''));
        $this->assignRef('categories', JRequest::getVar('categories', ''));*/
		$this->assign('jb_render', JobBoardHelper::renderJobBoardx());
		parent::display($tpl);
	}
}
?>