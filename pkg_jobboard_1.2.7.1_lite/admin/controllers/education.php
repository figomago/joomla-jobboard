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

class JobboardControllerEducation extends JController
{
	var $view;
	function add()
	{
		JToolBarHelper::title(JText::_( 'COM_JOBBOARD_NEW_EDLEVEL'));
		JToolBarHelper::save();
		JToolBarHelper::cancel();
        JobBoardToolbarHelper::setToolbarLinks('education');

		JRequest::setVar('view','educationedit');
		$this->displaySingle('old');
	}

	function edit()
	{
		JToolBarHelper::save();
		JToolBarHelper::cancel();
        JobBoardToolbarHelper::setToolbarLinks('education');

		JRequest::setVar('view','educationedit');
		$this->displaySingle('old');
	}

	function remove()
	{
		$cid = JRequest::getVar( 'cid' , array() , '' , 'array' );
		JArrayHelper::toInteger($cid);

		$doc =& JFactory::getDocument();
		$style = " .icon-48-job_posts {background-image:url(components/com_jobboard/images/job_posts.png); no-repeat; }";
		$doc->addStyleDeclaration( $style );
		$this->setToolbar();

		if (count( $cid )) {
			$cids = implode( ',', $cid );
			$jobs_model= & $this->getModel('Education');
			$delete_result = $jobs_model->deleteEdlevels($cids);
			if ($delete_result <> true) {
				$this->setRedirect('index.php?option=com_jobboard&view=education', $delete_result);
			}
			else {
				$success_msg = (count($cid ) == 1)? JText::_('COM_JOBBOARD_EDLEVEL_DELETED') : JText::_('COM_JOBBOARD_EDLEVELS_DELETED');
				$this->setRedirect('index.php?option=com_jobboard&view=education', $success_msg);
			}
		}
	}

	function display() //display list of all users
	{
		$doc =& JFactory::getDocument();
		$style = " .icon-48-job_posts {background-image:url(components/com_jobboard/images/job_posts.png); no-repeat; }";
		$doc->addStyleDeclaration( $style );
		$view = JRequest::getVar('view');
		if(!$view)
		{
			JRequest::setVar('view', 'education');
		}
		$this->setToolbar($view);

		parent::display();
	}

	function displaySingle($type) //display a single User that can be edited
	{
		JRequest::setVar('view', 'educationedit');
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

	function setToolbar($view){
		JToolBarHelper::title(JText::_( 'COM_JOBBOARD_EDUCATION_LEVELS'), 'job_posts.png');
		JToolBarHelper::deleteList();
		JToolBarHelper::addNewX();
		JToolBarHelper::editList();
		JToolBarHelper::back();

        JobBoardToolbarHelper::setToolbarLinks($view);
	}
}

$controller = new JobboardControllerEducation();
if(!isset($task)) $task = "display";
$controller->execute($task);
$controller->redirect();

?>