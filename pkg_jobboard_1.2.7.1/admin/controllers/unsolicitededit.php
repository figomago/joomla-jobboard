<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.controller');
JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables');

class JobboardControllerUnsolicitedEdit extends JController
{
	function save()
	{
		JRequest::checkToken() or jexit('Invalid Token');

		$applicant = JRequest::get('POST');

		jimport('joomla.utilities.date');
		$now = new JDate();

		if($applicant['job_id'] <> 0) {
			$unsol_id = $applicant['id'];
			$applicant['id'] = false;
			$applicant['request_date'] = $now->toMySQL();
			$record =& JTable::getInstance('Applicant', 'Table');
			if (!$record->save($applicant)) {
				// uh oh failed to save
				JError::raiseError('500', JTable::getError());
			}
			$unsol =& JTable::getInstance('Unsolicited', 'Table');
			if(!$unsol->delete($unsol_id)) {
				// uh oh failed to delete
				JError::raiseError('500', JTable::getError());
			}
			$this->extendSave($applicant);

		} else {
			$applicant['last_updated'] = $now->toMySQL();
			$unsol_record =& JTable::getInstance('Unsolicited', 'Table');
			if(!$unsol_record->save($applicant)) {
				// uh oh failed to save
				JError::raiseError('500', JTable::getError());
			}
			$this->extendSave($applicant);
		}

	}

	function extendSave($applicant) {
		 
		$saved_text = JText::_('COM_JOBBOARD_JOB_APP_SAVED');
		$feedback_string = $saved_text;

		$config = & JTable::getInstance('Config', 'Table');
		$config->load(1);

		if($applicant['job_id'] <> 0) {
			$job = & JTable::getInstance('Job', 'Table');
			$job->load($applicant['job_id']);
			$dept_tbl = & JTable::getInstance('Department', 'Table');
			$dept_tbl->load($job->department);
			if ($dept_tbl->notify_admin == 1 || $dept_tbl->notify == 1) {
				$applicant['dept_name'] =  $dept_tbl->name;
			}
			if ($dept_tbl->notify_admin == 1) {
				$this->sendEmail($applicant, $config, $config->from_mail, 'adminnew_application');
			}
			if ($dept_tbl->notify == 1) {
				$this->sendEmail($applicant, $config, $dept_tbl->contact_email, 'adminnew_application');
			}
			$this->setRedirect('index.php?option=com_jobboard&view=applicants', $feedback_string);
		} else {
			$this->sendEmail($applicant, $config, $config->from_mail, 'adminupdate_unsolicited');
			$this->setRedirect('index.php?option=com_jobboard&view=unsolicited', $feedback_string);
		}

	}
	function apply()
	{
		JRequest::checkToken() or jexit('Invalid Token');

		$applicant = JRequest::get('POST');

		jimport('joomla.utilities.date');
		$now = new JDate();

		if($applicant['job_id'] <> 0) {
			$unsol_id = $applicant['id'];
			$applicant['id'] = false;
			$applicant['request_date'] = $now->toMySQL();
			$record =& JTable::getInstance('Applicant', 'Table');
			if (!$record->save($applicant)) {
				// uh oh failed to save
				JError::raiseError('500', JTable::getError());
			}
			$unsol =& JTable::getInstance('Unsolicited', 'Table');
			if(!$unsol->delete($unsol_id)) {
				// uh oh failed to delete
				JError::raiseError('500', JTable::getError());
			}
			$this->extendApply($applicant);

		} else {
			$applicant['last_updated'] = $now->toMySQL();
			$unsol_record =& JTable::getInstance('Unsolicited', 'Table');
			if(!$unsol_record->save($applicant)) {
				// uh oh failed to save
				JError::raiseError('500', JTable::getError());
			}
			$this->extendApply($applicant);
		}
	}

	function extendApply($applicant) {
		 
		$saved_text = JText::_('COM_JOBBOARD_JOB_APP_SAVED');
		$feedback_string = $saved_text;

		$config = & JTable::getInstance('Config', 'Table');
		$config->load(1);

		if($applicant['job_id'] <> 0) {
			$job = & JTable::getInstance('Job', 'Table');
			$job->load($applicant['job_id']);
			$dept_tbl = & JTable::getInstance('Department', 'Table');
			$dept_tbl->load($job->department);
			if ($dept_tbl->notify_admin == 1 || $dept_tbl->notify == 1) {
				$applicant['dept_name'] =  $dept_tbl->name;
			}
			if ($dept_tbl->notify_admin == 1) {
				$this->sendEmail($applicant, $config, $config->from_mail, 'adminnew_application');
			}
			if ($dept_tbl->notify == 1) {
				$this->sendEmail($applicant, $config, $dept_tbl->contact_email, 'adminnew_application');
			}
			$this->setRedirect('index.php?option=com_jobboard&view=applicants', $feedback_string);
		} else {
			$this->sendEmail($applicant, $config, $config->from_mail, 'adminupdate_unsolicited');
			$this->setRedirect('index.php?option=com_jobboard&view=unsolicited&task=edit&cid[]='.$applicant['id'], $feedback_string);
		}

	}

	function edit()
	{
		$doc =& JFactory::getDocument();
		$style = " .icon-48-applicant_details {background-image:url(components/com_jobboard/images/applicant_details.png); no-repeat; }";
		$doc->addStyleDeclaration( $style );

		JToolBarHelper::title(JText::_( 'COM_JOBBOARD_APPLICANT_DETAILS'), 'applicant_details.png');
		JToolBarHelper::save();
		JToolBarHelper::cancel('close', JText::_('COM_JOBBOARD_TXT_CLOSE'));

		JRequest::setVar('view','unsolicitededit');
		parent::display();
	}

	function back()
	{
		//reset the parameters
		JRequest::setVar('task', '');
		JRequest::setVar('view', 'unsolicited');

		//call up the list screen controller
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'controllers'.DS.'applicants.php');
	}

	function close()
	{
		//reset the parameters
		JRequest::setVar('task', '');
		JRequest::setVar('view', 'unsolicited');

		//call up the list screen controller
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'controllers'.DS.'unsolicited.php');
	}

	function sendEmail($msgobj, $config, $to_email, $msg_type)
	{

		$messg_model =& $this->getModel('Message');
		$msg_id = $messg_model->getMsgID($msg_type);
		$msg = $messg_model->getMsg($msg_id);

		$from = $config->reply_to;
		$fromname = $config->organisation;

		$subject = str_replace('[toname]', $msgobj['first_name'], $msg->subject);
		$subject = str_replace('[tosurname]', $msgobj['last_name'], $subject);
		$body = str_replace('[toname]', $msgobj['first_name'], $msg->body);
		$body = str_replace('[tosurname]', $msgobj['last_name'], $body);
		$user = & JFactory::getUser();
		$body = str_replace('[appladmin]', $user->name, $body);

		if($msg_type == 'adminnew_application') {
			$subject = str_replace('[jobtitle]', $msgobj['title'], $subject);
			$subject = str_replace('[jobid]', $msgobj['job_id'], $subject);
			$subject = str_replace('[fromname]', $fromname, $subject);

			$body = str_replace('[jobid]', $msgobj['job_id'], $body);
			$body = str_replace('[jobtitle]', $msgobj['title'], $body);
			$body = str_replace('[fromname]', $fromname, $body);

			$status_tbl = & JTable::getInstance('Status', 'Table');
			$status_tbl->load($msgobj['status']);
			$body = str_replace('[department]', $msgobj['dept_name'], $body);
			$body = str_replace('[applstatus]', $status_tbl->status_description, $body);
		}

		if($msg_type == 'adminupdate_unsolicited') {
			$body = str_replace('[applicantid]', $msgobj['id'], $body);
		}
        return JobBoardHelper::dispatchEmail($from, $fromname, $to_email, $subject, $body);
	}
}

$controller = new JobboardControllerUnsolicitedEdit();
$controller->execute($task);
$controller->redirect();

?>