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

class JobboardControllerUsers extends JController
{
	var $view;

	function __construct()
	{
		parent::__construct();
		$this->registerTask('unpublish', 'publish');
		$this->registerTask('feature', 'toggleFeature');
		$this->registerTask('unfeature', 'toggleFeature');
		$this->registerTask('syncusers', 'syncJoomlaUsers');
		$this->registerTask('viewcv', 'showCvProfile');
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
		if($id > 0){JToolBarHelper::apply();}
		JToolBarHelper::save();
		JToolBarHelper::save('saveAndnew', JText::_('COM_JOBBOARD_SAVE_AND_NEW'));
		JToolBarHelper::cancel('close', JText::_('COM_JOBBOARD_TXT_CLOSE'));

		$job_model =& $this->getModel('Jobs');
		$job_data = $job_model->getJob($id);
		$countries = $job_model->getCountries();
		$careers = $job_model->getCareers();
		$education = $job_model->getEducation();
		$categories = $job_model->getCategories();
		$job_applicants = $job_model->getApplicants($id);
		$config = $job_model->getConfig();

		$status_model =& $this->getModel('Status');
		$statuses = $status_model->getStatuses();
		$departments = $status_model->getDepartments();

        $view = & $this->getView('jobedit', 'html');

		$view->assignRef('job_post', $job_data);
		$view->assignRef('countries', $countries);
		$view->assignRef('statuses', $statuses);
		$view->assignRef('departments', $departments);
		$view->assignRef('careers', $careers);
		$view->assignRef('education', $education);
		$view->assignRef('categories', $categories);
		$view->assignRef('applicants', $job_applicants);
		$view->assignRef('config', $config);

        $view->display();
	}

	function add()
	{
		JToolBarHelper::title(JText::_('COM_JOBBOARD_NEW_JOB'), '');
		JToolBarHelper::save();
		JToolBarHelper::cancel();

		$this->setRedirect('index.php?option=com_jobboard&view=jobs&task=edit&cid[]=0', '');
	}

	function display()
	{

		$doc =& JFactory::getDocument();
		$style = " .icon-48-job_posts {background-image:url(components/com_jobboard/images/job_posts.png); no-repeat; }";
		$doc->addStyleDeclaration( $style );

		JToolBarHelper::title(JText::_( 'COM_JOBBOARD_USERS'), 'job_posts.png');
		JToolBarHelper::publishList('publish', JText::_('COM_JOBBOARD_JOB_POST_ACTIVATE') );
		JToolBarHelper::unpublishList('unpublish', JText::_('COM_JOBBOARD_JOB_POST_DEACTIVATE') );
		JToolBarHelper::back();

		$view = JRequest::getVar('view');
		if(!$view)
		{
			JRequest::setVar('view', 'users');
		}

        JobBoardToolbarHelper::setToolbarLinks('users');

		$users_model =& $this->getModel('Users');
        $gid = JRequest::getInt('selrow', 0);
        if($gid > 0){
          $app = & JFactory::getApplication();
          if($users_model->setUserGroup(JRequest::getInt('seluser', 0), $gid)){
            $app->enqueueMessage( JText::sprintf('COM_JOBBOARD_JOB_USERS_GRP_CHANGE_SUCCESS', $gid), 'message');
          } else
            $this->enqueueMessage( JText::sprintf('COM_JOBBOARD_JOB_USERS_GRP_CHANGE_ERR', $gid), 'error' );
        }
        $view = &$this->getView('users', 'html');
        $view->setModel($users_model, true);

		$view->display();
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
			$user_model= & $this->getModel('Users');
			$delete_result = $user_model->deleteJobs($cids);
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


	function toggleFeature()
	{
		JRequest::checkToken() or jexit( 'Invalid Token' );


		// Initialize variables
		$cid		= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$task		= JRequest::getCmd( 'task' );
		$feature	= ($task == 'feature');
		$user_count	= count( $cid );

		if (empty( $cid )) {
			return JError::raiseWarning( 500, JText::_( 'COM_JOBBOARD_NO_USERS_SELECTED' ) );
		}

		JArrayHelper::toInteger( $cid );
		$cids = implode( ',', $cid );
		$users_model= & $this->getModel('Users');
		$toggle_result = $users_model->setFeatureStatus($feature, $cids);

		if (!$toggle_result) {
		   return JError::raiseWarning( 500, JText::_( 'COM_JOBBOARD_NO_USERS_SELECTED' ) );
		}
		if($user_count == 1){
			$this->setMessage( JText::sprintf( $feature ? 'COM_JOBBOARD_JOB_USER_FEATURE_ENABLED' : 'COM_JOBBOARD_JOB_USER_FEATURE_DISABLED', $user_count ) );
		} else {
			$this->setMessage( JText::sprintf( $feature ? 'COM_JOBBOARD_JOB_USERS_FEATURE_ENABLED' : 'COM_JOBBOARD_JOB_USERS_FEATURE_DISABLED', $user_count ) );
		}

	   return  $this->setRedirect( 'index.php?option=com_jobboard&view=users' );
	}

	function syncJoomlaUsers()
	{
		$db =& JFactory::getDBO();
        $user_model = & $this->getModel('Users');

        $user_model->deleteGhostUsers();
		$new_jobboard_users = $user_model->getimportUsers();

		if(!empty($new_jobboard_users)) {
		    $user_count = count($new_jobboard_users);
			if($user_model->jImportUsers($new_jobboard_users)) {
                $msg = JText::sprintf('COM_JOBBOARD_USERS_SYNC_PASSED', $user_count);
                $msg_type = 'Message';
			} else {
                $msg = JText::sprintf('COM_JOBBOARD_USERS_SYNC_ERR', $user_count);
                $msg_type = 'error';
			}
		} else {
            $msg = JText::_('COM_JOBBOARD_USERS_SYNC_EMPTY');
            $msg_type = 'Message';
		}

        $this->setRedirect('index.php?option=com_jobboard&view=users', $msg, $msg_type);
	}

	function publish()
	{
		JRequest::checkToken() or jexit( 'Invalid Token' );


		// Initialize variables
		$cid		= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$task		= JRequest::getCmd( 'task' );
		$publish	= ($task == 'publish');
		$user_count	= count( $cid );

		if (empty( $cid )) {
			return JError::raiseWarning( 500, JText::_( 'COM_JOBBOARD_NO_USERS_SELECTED' ) );
		}

		JArrayHelper::toInteger( $cid );
		$cids = implode( ',', $cid );
		$users_model= & $this->getModel('Users');
		$toggle_result = $users_model->setPublishStatus($publish, $cids);

		if (!$toggle_result) {
		   return JError::raiseWarning( 500, JText::_( 'COM_JOBBOARD_NO_USERS_SELECTED' ) );
		}
		if($user_count == 1){
			$this->setMessage( JText::sprintf( $publish ? 'COM_JOBBOARD_JOB_USER_ENABLED' : 'COM_JOBBOARD_JOB_USER_DISABLED', $user_count ) );
		} else {
			$this->setMessage( JText::sprintf( $publish ? 'COM_JOBBOARD_JOB_POSTS_PUBLISHED' : 'COM_JOBBOARD_JOB_POSTS_UNPUBLISHED', $user_count ) );
		}

	   return  $this->setRedirect( 'index.php?option=com_jobboard&view=users' );
	}
}

$controller = new JobboardControllerUsers();
if(!isset($task)) $task = "display";
$controller->execute($task);
$controller->redirect();

?>