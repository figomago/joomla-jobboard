<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

//JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');
jimport('joomla.application.component.controller');

class JobboardControllerUnsolicited extends JController
{
	var $view;
	function edit()
	{
		$doc =& JFactory::getDocument();
		$style = " .icon-48-applicant_details {background-image:url(components/com_jobboard/images/applicant_details.png); no-repeat; }";
		$doc->addStyleDeclaration( $style );

		JToolBarHelper::title(JText::_( 'COM_JOBBOARD_JOB_APPL_DET'), 'applicant_details.png');
		JToolBarHelper::back();
		JToolBarHelper::apply();
		JToolBarHelper::save();
		JToolBarHelper::cancel('close', JText::_('COM_JOBBOARD_TXT_CLOSE'));
		$cfig_model =& $this->getModel('Config');
		$config = $cfig_model->getApplConfig();
		
		JRequest::setVar('config',$config);

		JRequest::setVar('view','unsolicitededit');
		$this->displaySingle('old');
	}

	function remove()
	{
		$cid = JRequest::getVar( 'cid' , array() , '' , 'array' );
		JArrayHelper::toInteger($cid);

		$doc =& JFactory::getDocument();
		$style = " .icon-48-job_posts {background-image:url(components/com_jobboard/images/job_applicants.png.png); no-repeat; }";
		$doc->addStyleDeclaration( $style );

		if (count( $cid )) {
			$cids = implode( ',', $cid );
			$jobs_model= & $this->getModel('Unsolicited');
			$delete_result = $jobs_model->deleteUnsolicited($cids);
			if ($delete_result <> true) {
				$this->setRedirect('index.php?option=com_jobboard&view=unsolicited', $delete_result);
			}
			else {
				$success_msg = (count($cid ) == 1)? JText::_('COM_JOBBOARD_UNSOL_DELETED') : JText::_('COM_JOBBOARD_UNSOLS_DELETED');
				$this->setRedirect('index.php?option=com_jobboard&view=unsolicited', $success_msg);
			}
		}
	}

	function display() //display list of all users
	{
		$doc =& JFactory::getDocument();
		$style = " .icon-48-job_applicants {background-image:url(components/com_jobboard/images/job_applicants.png); no-repeat; }";
		$doc->addStyleDeclaration( $style );

		JToolBarHelper::title(JText::_( 'COM_JOBBOARD_UNSOL_APPL'), 'job_applicants.png');
		JToolBarHelper::deleteList();
		JToolBarHelper::editList();
		JToolBarHelper::cancel('close', JText::_('COM_JOBBOARD_TXT_CLOSE'));

        JobBoardToolbarHelper::setToolbarLinks('applicants');

        $app =& JFactory::getApplication();
        $vcontext  = $app->getUserStateFromRequest('com.jobboard.applicatnts.vcontext', 'vcontext', 3, 'int');

		$unsol_model =& $this->getModel('Unsolicited');
		$cfig_model =& $this->getModel('Config');
		$config = $cfig_model->getApplConfig();		

        $status_model =& $this->getModel('Status');
        $statuses = $status_model->getStatuses();
        JRequest::setVar('view', 'unsolicited');

		$_view = & $this->getView('unsolicited', 'html');
        $_view->setModel($unsol_model, true);
        $_view->assignRef('statuses', $statuses);
		$_view->assignRef('config', $config);
		$_view->assign('vcontext', $vcontext);

		$_view->display();
	}

	function displaySingle($type) //display a single User that can be edited
	{
		JRequest::setVar('view', 'unsolicitededit');
		if($type='new') JRequest::setVar('task','add');      
		parent::display();
	}

	function close()
	{
		//reset the parameters
		JRequest::setVar('task', '');
		JRequest::setVar('view','dashboard');

		//call up the dashboard screen controller
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'controllers'.DS.'dashboard.php');
	}
}

$controller = new JobboardControllerUnsolicited();
if(!isset($task)) $task = "display"; //cancel button doesn't pass task so may gen php warning on execute below
$controller->execute($task);
$controller->redirect();

?>