<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.controller');

class JobboardControllerJobEdit extends JController
{
    private $_user;

    function __construct(&$config=array()) {
            parent::__construct();
            $this->registerTask('repost', 'repostJob');
            $this->_user = & JFactory::getUser();
    }

	function save()
	{
		JRequest::checkToken() or jexit('Invalid Token');

        $app = & JFactory::getApplication();
		$job = JArrayHelper::toObject(JRequest::get('post'));
		$job->job_description = JRequest::getVar('job_description', 0, 'post', 'string', JREQUEST_ALLOWRAW);
		$job->duties = JRequest::getVar('duties', 0, 'post', 'string', JREQUEST_ALLOWRAW);
		$job->featured = isset($job->featured)? ($job->featured == 'yes'? 1 : 0) : 0;
        $poster = $app->getUserState('com_jobboard.backend.poster', 0, 'int');
        $job->posted_by = $poster == 0? $this->_user->id : $poster;

		$newjob = false;

		$job_model =& $this->getModel('Job');
		if(!$job->id || ($job->id < 1)) {
			$newjob = true;
			if(!$job_model->savenew($job)) {
				JError::raiseError(500, JText::_('COM_JOBBOARD_SAVE_ERR'));
			}
		}  else {
			$newjob = false;
			if(!$job_model->save($job)) {
				JError::raiseError(500, JText::_('COM_JOBBOARD_SAVE_ERR'));
			}

		}
		$config = & JTable::getInstance('Config', 'Table');
		$config->load(1);
		$dept_tbl = & JTable::getInstance('Department', 'Table');
		$dept_tbl->load($job->department);

		if ($dept_tbl->notify_admin == 1 || $dept_tbl->notify == 1) {
			$job->dept_name =  $dept_tbl->name;
		}
		if ($dept_tbl->notify_admin == 1) {
			if($newjob == true){
				$this->sendEmail($job, $config, $config->from_mail, 'adminnew');
			} else
			$this->sendEmail($job, $config, $config->from_mail, 'adminupdate');
		}
		if ($dept_tbl->notify == 1) {
			if($newjob == true){
				$this->sendEmail($job, $config, $dept_tbl->contact_email, 'adminnew');
			} else
			$this->sendEmail($job, $config, $dept_tbl->contact_email, 'adminupdate');
		}
		$job_id_text = JText::_('COM_JOBBOARD_JOB_ID');
		$new_job_text = JText::_('COM_JOBBOARD_NEW_JOB');
		$saved_text = JText::_('COM_JOBBOARD_SAVE_SUCCESS');
		$feedback_string = ($newjob == true)? $new_job_text.' '.$saved_text : $job_id_text. ' #'.$job->id.' '.$saved_text;

		$this->setRedirect('index.php?option=com_jobboard&view=jobs', $feedback_string);

	}

	function saveAndnew()
	{

		JRequest::checkToken() or jexit('Invalid Token');

        $app = & JFactory::getApplication();
		$job = JArrayHelper::toObject(JRequest::get('post'));
		$job->job_description = JRequest::getVar('job_description', 0, 'post', 'string', JREQUEST_ALLOWRAW);
		$job->duties = JRequest::getVar('duties', 0, 'post', 'string', JREQUEST_ALLOWRAW);
		$job->featured = isset($job->featured)? ($job->featured == 'yes'? 1 : 0) : 0;
        $poster = $app->getUserState('com_jobboard.backend.poster', 0, 'int');
        $job->posted_by = $poster == 0? $this->_user->id : $poster;

		$newjob = false;
		$job_model =& $this->getModel('Job');
		if(!$job->id || ($job->id < 1)) {
			$newjob = true;
			if(!$job_model->savenew($job)) {
				JError::raiseError(500, JText::_('COM_JOBBOARD_SAVE_ERR'));
			}
		}  else {
			$newjob = false;
			if(!$job_model->save($job)) {
				JError::raiseError(500, JText::_('COM_JOBBOARD_SAVE_ERR'));
			}
		}

		$config = & JTable::getInstance('Config', 'Table');
		$config->load(1);
		$dept_tbl = & JTable::getInstance('Department', 'Table');
		$dept_tbl->load($job->department);

		if ($dept_tbl->notify_admin == 1 || $dept_tbl->notify == 1) {
			$job->dept_name =  $dept_tbl->name;
		}
		if ($dept_tbl->notify_admin == 1) {
			if($newjob == true){
				$this->sendEmail($job, $config, $config->from_mail, 'adminnew');
			} else
			$this->sendEmail($job, $config, $config->from_mail, 'adminupdate');
		}
		if ($dept_tbl->notify == 1) {
			if($newjob == true){
				$this->sendEmail($job, $config, $dept_tbl->contact_email, 'adminnew');
			} else
			$this->sendEmail($job, $config, $dept_tbl->contact_email, 'adminupdate');
		}
		$job_id_text = JText::_('COM_JOBBOARD_JOB_ID');
		$new_job_text = JText::_('COM_JOBBOARD_NEW_JOB');
		$saved_text = JText::_('COM_JOBBOARD_SAVE_SUCCESS');
		$feedback_string = ($newjob == true)? $new_job_text.' '.$saved_text : $job_id_text. ' #'.$job->id.' '.$saved_text;

		$this->setRedirect('index.php?option=com_jobboard&view=jobs&task=edit&cid[]=0', $feedback_string);
	}

	function apply()
	{
		JRequest::checkToken() or jexit('Invalid Token');

        $app = & JFactory::getApplication();
		$job = JArrayHelper::toObject(JRequest::get('post'));
		$job->job_description = JRequest::getVar('job_description', 0, 'post', 'string', JREQUEST_ALLOWRAW);
		$job->duties = JRequest::getVar('duties', 0, 'post', 'string', JREQUEST_ALLOWRAW);
		$job->featured = isset($job->featured)? ($job->featured == 'yes'? 1 : 0) : 0;
        $poster = $app->getUserState('com_jobboard.backend.poster', 0, 'int');
        $job->posted_by = $poster == 0? $this->_user->id : $poster;

		$job_model =& $this->getModel('Job');
		if(!$job_model->save($job)) {
			JError::raiseError(500, JText::_('COM_JOBBOARD_SAVE_ERR'));
		}

		$config = & JTable::getInstance('Config', 'Table');
		$config->load(1);
		$dept_tbl = & JTable::getInstance('Department', 'Table');
		$dept_tbl->load($job->department);

		if ($dept_tbl->notify_admin == 1 || $dept_tbl->notify == 1) {
			$job->dept_name =  $dept_tbl->name;
		}
		if ($dept_tbl->notify_admin == 1) {
			$this->sendEmail($job, $config, $config->from_mail, 'adminupdate');
		}
		if ($dept_tbl->notify == 1) {
			$this->sendEmail($job, $config, $dept_tbl->contact_email, 'adminupdate');
		}

		$job_id_text = JText::_('COM_JOBBOARD_JOB_ID');
		$saved_text = JText::_('COM_JOBBOARD_SAVE_SUCCESS');
		$feedback_string = $job_id_text. ' #'.$job->id.' '.$saved_text;

		return $this->setRedirect('index.php?option=com_jobboard&view=jobs&task=edit&cid[]='.$job->id, $feedback_string);
	}

	function repostJob()
	{
		JRequest::checkToken() or jexit('Invalid Token');

        $app = & JFactory::getApplication();
		$job = JArrayHelper::toObject(JRequest::get('post'));
		$job->job_description = JRequest::getVar('job_description', 0, 'post', 'string', JREQUEST_ALLOWRAW);
		$job->duties = JRequest::getVar('duties', 0, 'post', 'string', JREQUEST_ALLOWRAW);
		$job->featured = isset($job->featured)? ($job->featured == 'yes'? 1 : 0) : 0;
        $poster = $app->getUserState('com_jobboard.backend.poster', 0, 'int');
        $job->posted_by = $poster == 0? $this->_user->id : $poster;

		$job_model =& $this->getModel('Job');
		if(!$job_model->repostJob($job)) {
			JError::raiseError(500, JText::_('COM_JOBBOARD_SAVE_ERR'));
		}

		$config = & JTable::getInstance('Config', 'Table');
		$config->load(1);
		$dept_tbl = & JTable::getInstance('Department', 'Table');
		$dept_tbl->load($job->department);

		if ($dept_tbl->notify_admin == 1 || $dept_tbl->notify == 1) {
			$job->dept_name =  $dept_tbl->name;
		}
		if ($dept_tbl->notify_admin == 1) {
			$this->sendEmail($job, $config, $config->from_mail, 'adminupdate');
		}
		if ($dept_tbl->notify == 1) {
			$this->sendEmail($job, $config, $dept_tbl->contact_email, 'adminupdate');
		}

		$job_id_text = JText::_('COM_JOBBOARD_JOB_ID');
		$saved_text = JText::_('COM_JOBBOARD_SAVE_SUCCESS');
		$feedback_string = $job_id_text. ' #'.$job->id.' '.$saved_text;

		return $this->setRedirect('index.php?option=com_jobboard&view=jobs&task=edit&cid[]='.$job->id, $feedback_string);
	}

	function back()
	{
		//reset the parameters
		JRequest::setVar('task', '');
		JRequest::setVar('view', 'jobs');

		//call up the list screen controller
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'controllers'.DS.'jobs.php');
	}
	function close()
	{
		//reset the parameters
		JRequest::setVar('task', '');
		JRequest::setVar('view', 'jobs');

		//call up the list screen controller
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'controllers'.DS.'jobs.php');
	}

	function sendEmail($msgobj, $config, $to_email, $msg_type)
	{
		$messg_model =& $this->getModel('Message');
		$msg_id = $messg_model->getMsgID($msg_type);
		$msg = $messg_model->getMsg($msg_id);

		$from = $config->reply_to;
		$fromname = $config->organisation;
		/* $to_email = $msgobj->email;*/
		$job_status = ($msgobj->published == 1)? JText::_('COM_JOBBOARD_ACTIVE') : JText::_('COM_JOBBOARD_INACT');

		$subject = str_replace('[jobtitle]', $msgobj->job_title, $msg->subject);
		$subject = str_replace('[jobid]', $msgobj->id, $subject);
		$subject = str_replace('[location]', $msgobj->city, $subject);
		$subject = str_replace('[department]', $msgobj->dept_name, $subject);
		$subject = str_replace('[status]', $job_status, $subject);

		$body = str_replace('[jobid]', $msgobj->id, $msg->body);
		$body = str_replace('[jobtitle]', $msgobj->job_title, $body);
		$body = str_replace('[location]', $msgobj->city, $body);
		$body = str_replace('[department]', $msgobj->dept_name, $body);
		$body = str_replace('[status]', $job_status, $body);

		if($msg_type == 'adminupdate' || $msg_type = 'adminnew') {
			$user = & JFactory::getUser();
			$body = str_replace('[appladmin]', $user->name, $body);
		}

        return JobBoardHelper::dispatchEmail($from, $fromname,  $to_email, $subject, $body);
	}
}

$controller = new JobboardControllerJobEdit();
$controller->execute($task);
$controller->redirect();

?>