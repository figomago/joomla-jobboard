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

class JobboardControllerJobs extends JController
{
	var $view;

	function __construct()
	{
		parent::__construct();
		$this->registerTask('unpublish', 'publish');
		$this->registerTask('feature', 'toggleFeature');
		$this->registerTask('unfeature', 'toggleFeature');
	}

	function edit()
	{
		$cid = JRequest::getVar('cid', false, 'DEFAULT', 'array');
		if($cid){
			$id = $cid[0];
		}
		else $id = JRequest::getInt('id', 0);

		$doc =& JFactory::getDocument();
		$style = " .icon-48-job_details {background-image:url(components/com_jobboard/images/job_details.png); no-repeat; }";
		$doc->addStyleDeclaration( $style );

		JToolBarHelper::title(JText::_( 'COM_JOBBOARD_JOB_DETAILS'), 'job_details.png');
		JToolBarHelper::back();
		if($id > 0){
		  JToolBarHelper::apply();
        }
		JToolBarHelper::save();
		JToolBarHelper::save('saveAndnew', JText::_('COM_JOBBOARD_SAVE_AND_NEW'));
		JToolBarHelper::cancel('close', JText::_('COM_JOBBOARD_TXT_CLOSE'));
        JobBoardToolbarHelper::setToolbarLinks('jobs');

		$job_model =& $this->getModel('Jobs');
		$job_data = $job_model->getJob($id);
        $app = & JFactory::getApplication();
        if(isset($job_data->posted_by)) {
          $app->setUserState('com_jobboard.backend.poster', $job_data->posted_by, 'int');
        } else
          $app->setUserState('com_jobboard.backend.poster', 0, 'int');

		$countries = $job_model->getCountries();
		$careers = $job_model->getCareers();
		$education = $job_model->getEducation();
		$categories = $job_model->getCategories();
		$job_applicants = $job_model->getJobApplsCount($id);
		$config = $job_model->getConfig();

		$status_model =& $this->getModel('Status');
		$statuses = $status_model->getStatuses();
		$departments = $status_model->getDepartments();
        $questionnaires = $job_model->getQuestionnaires();

        $view = & $this->getView('jobedit', 'html');
		$view->setModel($job_model, true);

		$view->assignRef('job_post', $job_data);
		$view->assignRef('countries', $countries);
		$view->assignRef('statuses', $statuses);
		$view->assignRef('departments', $departments);
		$view->assignRef('careers', $careers);
		$view->assignRef('education', $education);
		$view->assignRef('categories', $categories);
		$view->assignRef('applicants', $job_applicants);
		$view->assignRef('config', $config);
		$view->assignRef('questionnaires', $questionnaires);
		JRequest::setVar('view','jobedit');

        $view->display();
	   //$this->displaySingle('old');
	}

	function add()
	{
		JToolBarHelper::title(JText::_('COM_JOBBOARD_NEW_JOB'), '');
		JToolBarHelper::save();
		JToolBarHelper::cancel();

		$this->setRedirect('index.php?option=com_jobboard&view=jobs&task=edit&cid[]=0', '');
		/*JRequest::setVar('view','jobedit');
		 $this->displaySingle('old');*/
	}

	function display()
	{
		$doc =& JFactory::getDocument();
		$style = " .icon-48-job_posts {background-image:url(components/com_jobboard/images/job_posts.png); no-repeat; }";
		$doc->addStyleDeclaration( $style );

		JToolBarHelper::title(JText::_( 'COM_JOBBOARD_M_JOBS'), 'job_posts.png');
		JToolBarHelper::deleteList();
		JToolBarHelper::publishList('publish', JText::_('COM_JOBBOARD_JOB_POST_ACTIVATE') );
		JToolBarHelper::unpublishList('unpublish', JText::_('COM_JOBBOARD_JOB_POST_DEACTIVATE') );
		JToolBarHelper::addNewX();
		JToolBarHelper::editList();
		JToolBarHelper::back();
		$view = JRequest::getVar('view');
		if(!$view)
		{
			JRequest::setVar('view', 'jobs');
		}

        JobBoardToolbarHelper::setToolbarLinks('jobs');
		$job_model =& $this->getModel('Jobs');

		parent::display();
	}

	function displaySingle($type)
	{
		JRequest::setVar('view', 'jobedit');
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

	//-> begin: Juan Jose Perez
	function remove()
	{

		$cid = JRequest::getVar( 'cid' , array() , '' , 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid )) {
			$cids = implode( ',', $cid );
			$jobs_model= & $this->getModel('Jobs');
			$delete_result = $jobs_model->deleteJobs($cids);
			if (!$delete_result) {
				$this->setRedirect('index.php?option=com_jobboard&view=jobs', $db->getErrorMsg(true));
			}
			else {
				$success_msg = (count($cid ) == 1)? JText::_('COM_JOBBOARD_JOB_DELETED') : JText::_('COM_JOBBOARD_JOBS_DELETED');
				$this->setRedirect('index.php?option=com_jobboard&view=jobs', $success_msg);
			}
		}
	}
	//-> end: Juan Jose Perez

	function publish()
	{
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$this->setRedirect( 'index.php?option=com_jobboard&view=jobs' );

		// Initialize variables
		$cid		= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$task		= JRequest::getCmd( 'task' );
		$publish	= ($task == 'publish');
		$job_count	= count( $cid );

		if (empty( $cid )) {
			return JError::raiseWarning( 500, JText::_( 'COM_JOBBOARD_NO_JOBS_SELECTED' ) );
		}

		JArrayHelper::toInteger( $cid );
		$cids = implode( ',', $cid );
		$jobs_model= & $this->getModel('Jobs');
		$delete_result = $jobs_model->setPublishStatus($publish, $cids);

		if (!$delete_result) {
			return JError::raiseWarning( 500, $db->getError() );
		}
		if($job_count == 1){
			$this->setMessage( JText::sprintf( $publish ? 'COM_JOBBOARD_JOB_POST_PUBLISHED' : 'COM_JOBBOARD_JOB_POST_UNPUBLISHED', $job_count ) );
		} else {
			$this->setMessage( JText::sprintf( $publish ? 'COM_JOBBOARD_JOB_POSTS_PUBLISHED' : 'COM_JOBBOARD_JOB_POSTS_UNPUBLISHED', $job_count ) );
		}
	}

	function toggleFeature()
	{
		JRequest::checkToken() or jexit( 'Invalid Token' );


		// Initialize variables
		$cid		= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$task		= JRequest::getCmd( 'task' );
		$feature	= ($task == 'feature');
		$job_count	= count( $cid );

		if (empty( $cid )) {
			return JError::raiseWarning( 500, JText::_( 'COM_JOBBOARD_NO_JOBS_SELECTED' ) );
		}

		JArrayHelper::toInteger( $cid );
		$cids = implode( ',', $cid );
		$jobs_model= & $this->getModel('Jobs');
		$toggle_result = $jobs_model->setFeatureStatus($feature, $cids);

		if (!$toggle_result) {
		   return JError::raiseWarning( 500, JText::_( 'COM_JOBBOARD_NO_USERS_SELECTED' ) );
		}
		if($job_count == 1){
			$this->setMessage( JText::sprintf( $feature ? 'COM_JOBBOARD_JOB_POST_FEATURE_ENABLED' : 'COM_JOBBOARD_JOB_POST_FEATURE_DISABLED', $job_count ) );
		} else {
			$this->setMessage( JText::sprintf( $feature ? 'COM_JOBBOARD_JOB_POSTS_FEATURE_DISABLED' : 'COM_JOBBOARD_JOB_POSTS_FEATURE_DISABLED', $job_count ) );
		}

	   return  $this->setRedirect( 'index.php?option=com_jobboard&view=jobs' );
	}
}

$controller = new JobboardControllerJobs();
if(!isset($task)) $task = "display";
$controller->execute($task);
$controller->redirect();

?>