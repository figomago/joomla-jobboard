<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');
 
//JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');
jimport('joomla.application.component.controller');

class JobboardControllerDepartments extends JController
{
    var $view;
    function add()
	{
	    JToolBarHelper::title(JText::_( 'NEW_DEPT'));
		JToolBarHelper::save();
		JToolBarHelper::cancel();
        JobBoardToolbarHelper::setToolbarLinks('departments');

		JRequest::setVar('view','departmentedit');
		$this->displaySingle('old');
	}

	function remove()
	{
		$cid = JRequest::getVar( 'cid' , array() , '' , 'array' );
		JArrayHelper::toInteger($cid);
		
		$doc =& JFactory::getDocument();
        $style = " .icon-48-job_posts {background-image:url(components/com_jobboard/images/job_posts.png); no-repeat; }";
        $doc->addStyleDeclaration( $style );

		if (count( $cid )) {
			$cids = implode( ',', $cid );
			$jobs_model= & $this->getModel('Departments');
			$delete_result = $jobs_model->deleteDepartments($cids);
			if ($delete_result <> true) {
				$this->setRedirect('index.php?option=com_jobboard&view=departments', $delete_result);
			}
			else {
				$success_msg = (count($cid ) == 1)? JText::_('COM_JOBBOARD_DEPT_DELETED') : JText::_('COM_JOBBOARD_DEPTS_DELETED');
				$this->setRedirect('index.php?option=com_jobboard&view=departments', $success_msg);
			}
		}
	}
	function edit()
	{
	    $doc =& JFactory::getDocument();
        $style = " .icon-48-applicant_details {background-image:url(components/com_jobboard/images/applicant_details.png); no-repeat; }";
        $doc->addStyleDeclaration( $style );

		JToolBarHelper::title(JText::_( 'COM_JOBBOARD_EDIT_DEPT'), 'applicant_details.png');
		JToolBarHelper::back();
		JToolBarHelper::apply();
		JToolBarHelper::save();
		JToolBarHelper::cancel();

        $status_model =& $this->getModel('Status');
        $statuses = $status_model->getStatuses();
        $departments = $status_model->getDepartments();
        JRequest::setVar('statuses', $statuses);
        JRequest::setVar('departments', $departments);
		
		JRequest::setVar('view','applicantedit');
		$this->displaySingle('old');
	}
	
	function display() //display list of all users
	{
	    $doc =& JFactory::getDocument();
        $style = " .icon-48-job_applicants {background-image:url(components/com_jobboard/images/job_applicants.png); no-repeat; }";
        $doc->addStyleDeclaration( $style );

		JToolBarHelper::title(JText::_( 'COM_JOBBOARD_DEPTS_DIVS'), 'job_applicants.png');
		JToolBarHelper::deleteList();
		JToolBarHelper::addNewX();
		JToolBarHelper::editList();
		JToolBarHelper::back();
		$view = JRequest::getVar('view');
		if(!$view)
		{
			JRequest::setVar('view', 'departments');
		}

        JobBoardToolbarHelper::setToolbarLinks('departments');
        $depts_model= & $this->getModel('Departments');
        $status_model =& $this->getModel('Status');

        $view = & $this->getView('departments', 'html');
        $view->setModel($depts_model, true);

        $statuses = $status_model->getStatuses();
        $view->assignRef('statuses', $statuses);

		$view->display();
	}	
	
	function displaySingle($type) //display a single User that can be edited
	{
		JRequest::setVar('view', 'departmentedit');
		if($type='new') JRequest::setVar('task','add');
		parent::display();
	}	
	
	function cancel()
	{
		//reset the parameters
		JRequest::setVar('task', '');
		JRequest::setVar('view','dashboard');

		//call up the dashboard screen controller
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'controllers'.DS.'dashboard.php');
	}
}

$controller = new JobboardControllerDepartments();
if(!isset($task)) $task = "display"; //cancel button doesn't pass task so may gen php warning on execute below
$controller->execute($task);
$controller->redirect();

?>