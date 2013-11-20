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

class JobboardControllerCareerlevels extends JController
{
	var $view;
	function add()
	{
		JToolBarHelper::title(JText::_( 'COM_JOBBOARD_NEW_CAREER_LEVEL'));
		JToolBarHelper::save();
		JToolBarHelper::cancel();

		JRequest::setVar('view','careerleveledit');
		$this->displaySingle('old');
	}

	function edit()
	{
		JToolBarHelper::title(JText::_( 'COM_JOBBOARD_EDIT_CAREER_LEVEL'));
		JToolBarHelper::save();
		JToolBarHelper::cancel();
        JobBoardToolbarHelper::setToolbarLinks('careerlevels');

		JRequest::setVar('view','careerleveledit');
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
			$jobs_model= & $this->getModel('Careerlevels');
			$delete_result = $jobs_model->deleteCareers($cids);
			if ($delete_result <> true) {
				//echo "<script> alert('".$db->getErrorMsg(true)."'); window.history.go(-1); </script>\n";
				$this->setRedirect('index.php?option=com_jobboard&view=careerlevels', $delete_result);
			}
			else {
				$success_msg = (count($cid ) == 1)? JText::_('COM_JOBBOARD_CAREER_LEVEL_DELETED') : JText::_('COM_JOBBOARD_CAREERS_DELETED');
				$this->setRedirect('index.php?option=com_jobboard&view=careerlevels', $success_msg);
			}
		}
	}

	function setToolbar(){
		JToolBarHelper::title(JText::_( 'COM_JOBBOARD_CAREER_LEVELS'), 'job_posts.png');
		JToolBarHelper::deleteList();
		JToolBarHelper::addNewX();
		JToolBarHelper::editList();
		JToolBarHelper::back();
		//$selected = ($view == 'item6');
		// prepare links
	}

	function display() //display list of all users
	{
		$doc =& JFactory::getDocument();
		$style = " .icon-48-job_posts {background-image:url(components/com_jobboard/images/job_posts.png); no-repeat; }";
		$doc->addStyleDeclaration( $style );

		JToolBarHelper::title(JText::_( 'COM_JOBBOARD_CAREER_LEVELS'), 'job_posts.png');
		JToolBarHelper::deleteList();
		JToolBarHelper::addNewX();
		JToolBarHelper::editList();
		JToolBarHelper::back();
		$view = JRequest::getVar('view');
		if(!$view)
		{
			JRequest::setVar('view', 'careerlevel');
		}
        JobBoardToolbarHelper::setToolbarLinks('careerlevels');

		parent::display();
	}

	function displaySingle($type) //display a single User that can be edited
	{
		JRequest::setVar('view', 'careerleveledit');
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

$controller = new JobboardControllerCareerlevels();
if(!isset($task)) $task = "display"; //cancel button doesn't pass task so may gen php warning on execute below
$controller->execute($task);
$controller->redirect();

?>