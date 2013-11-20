<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables');
// Load framework base classes
jimport('joomla.application.component.controller');
jimport('joomla.mail.helper');

class JobboardControllerJob extends JController
{
	/**
	 * constructor
	 */
	function __construct()
	{
		parent::__construct();

		$this->registerTask('share', 'emailFriend');
	}

	function display()
	{
		$id = JRequest::getInt('id');
		$this->_getJob($id);
	}

	function emailFriend() {
		JRequest::checkToken() or jexit('Invalid Token');
        require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_member.php' );
		$app = JFactory::getApplication();

        if(JobBoardHelper::verifyHumans()) {
          if(!JobBoardMemberHelper::matchHumanCode(JRequest::getString('human_ver', '')))
          {
             $post = JArrayHelper::toObject(JRequest::get('post'));
             $post->errors = 1;
             if(isset($post->human_ver)) unset($post->human_ver);
		     $app->setUserState('com_jobboard.sfields', $post);
             $app->redirect(JRoute::_('index.php?option=com_jobboard&view=share&errors=1&job_id='.$post->job_id.'&Itemid='.JRequest::getInt('Itemid')), JText::_('COM_JOBBOARD_FORM_CAPTCHA_FAILMSG'), 'error');
    		 return;
          }
        }

		$message = new JObject();
		$message->job_id = JRequest::getVar('job_id','','','int');
		$catid = JRequest::getVar('catid','','','int');
		$message->job_title = JRequest::getVar('job_title','','','string');
		$message->job_city = JRequest::getVar('job_city','','','string');
		$message->personal_message = JRequest::getVar('personal_message','','','string');
        $uri = &JURI::getInstance();
		$message->link = $uri->getScheme().'://'.$uri->getHost().JRequest::getVar('job_path','','','string');

		$fields_valid = $this->validateFields();
		$message->sender_email = $fields_valid->sender_email;
		$message->sender_name = $fields_valid->sender_name;
		$message->rec_emails = $fields_valid->rec_emails;

		if($fields_valid->errors === true) {
			$errmsg = $fields_valid->errmsg.'</ul>';
			$app->setUserState('sfields', $message);
			$link = JRoute::_('index.php?option=com_jobboard&view=share&errors=1&job_id='.$message->job_id.'&Itemid='.$itemid);
			$this->setRedirect( $link, $errmsg, '' );return;
		} else {

			if(stristr($message->rec_emails, ',') === TRUE) {
				$rec_emailarray = explode(',', $message->rec_emails);
				foreach($rec_emailarray as $email_recipient) {
					$this->sendEmail($message, trim($email_recipient));
				}
			}  else {
				$this->sendEmail($message, trim($message->rec_emails));
			}

			$mesgModel = & $this->getModel('Message');
			$saved = $mesgModel->saveMessage($message);

			if($saved) {
				$msg = '&nbsp;'.JText::_('SEND_MSG_SUCCESS');
				$link = JRoute::_('index.php?option=com_jobboard&view=job&id='.$message->job_id, false);
				$this->setRedirect( $link, $msg, '' );return;
			} else {
				$msg = '&nbsp;'.JText::_('ERR_WAIT');
				$link = JRoute::_('index.php?option=com_jobboard&view=job&id='.$message->job_id, false);
				$this->setRedirect( $link, $msg, '' );return;
			}
		}

		parent :: display();
	}

	function sendEmail($msgobj, $recipient)
	{
		$messg_model =& $this->getModel('Message');
		$msg_id = $messg_model->getMsgID('sharejpriv');
		$msg = $messg_model->getMsg($msg_id);

		$from = $msgobj->sender_email;
		$fromname = $msgobj->sender_name;
		$to_email = $recipient;

		$subject = $msg->subject;
		$body = $msgobj->personal_message;
		$body_b = str_replace('[location]', $msgobj->job_city, $msg->body);
		$body_b = str_replace('[jobtitle]', $msgobj->job_title, $body_b);
		$body = $body.$body_b.JText::_('MESSAGE_LINK_TEXT').': '.$msgobj->link;

        return JobBoardHelper::dispatchEmail($from, $fromname, $to_email, $subject, $body);
	}

	private function _getJob($id)
	{
        require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_job.php' );
	    $published = JobBoardJobHelper::jobPublished($id);

    	$view  =& $this->getView('job', 'html');
    	$config_model =& $this->getModel('Config');
    	$catid = JRequest :: getInt('selcat');

        if($published) {
    	    $app = & JFactory::getApplication();
    	    $user = & JFactory::getUser();
    		$jobs =& JTable::getInstance('Job','Table');
    		$jobs->hit($id);
    		$job_model =& $this->getModel('Job');
    		$user_model =& $this->getModel('User');
    		$job_data = $job_model->getData($id);
            $list_deflt_layout = $config_model->getListcfg();
            $layout = $app->getUserStateFromRequest('com_jobboard.list.layout', 'layout', $list_deflt_layout);

            $view->setModel($job_model, true);
            //$view->setLayout($layout);
            $bid = 0;
            $has_applied = 0;
            if($user->id > 0) {
              $bid = $user_model->hasBookmark($id, $user->id);
              $bid = ($bid > 0)? $bid : 0;
              $has_applied = ($user_model->getJobApplicationStatus($user->id, $id) > 0)? 1 : 0;
            }

            $view->assign('prev_applied', $has_applied);
            $view->assign('id', $id);
            $view->assign('bid', $bid);
            $view->assignRef('data', $job_data);
        }

        $view->setModel($config_model);
        $view->assign('published', $published);
        $view->assign('selcat', $catid);

	    $view->display();
	}

	function validateFields(){

		$message->sender_email = JRequest::getVar('sender_email','','','string');
		$message->sender_name = JRequest::getVar('sender_name','','','string');
		$message->rec_emails = JRequest::getVar('rec_emails','','','string');

		$msg = JText::_('REQ_PROCESSING_ERR').'<ul>';
		$errors = false;

		if($message->sender_email == '') {
			$msg .= '<li>'.JText::_('VALID_EMAIL_ERR').'</li>';
			$errors = true;
		}
		if($message->sender_name == '') {
			$msg .= '<li>'.JText::_('VALID_SENDER_ERR').'</li>';
			$errors = true;
		}

		if(stristr($message->rec_emails, ',') === TRUE) {
			$rec_emailarray = explode(',', $message->rec_emails);
			foreach($rec_emailarray as $email_recipient) {
				if(trim($email_recipient) == '' || !JMailHelper::cleanAddress(trim($email_recipient)) || !JMailHelper::isEmailAddress(trim($email_recipient))){
					$addr_errors = true;
					$errors = true;
				}
			}
			if($addr_errors === true) {
				$errors = true;
				$msg .= '<li>'.JText::_('ONEOR_MORE_EMAILS_INVALID').'</li>';
			}
		}  else {
			if((stristr($message->rec_emails, ',') === FALSE) && ( trim($message->rec_emails) == '' || !JMailHelper::cleanAddress(trim($message->rec_emails)) || !JMailHelper::isEmailAddress(trim($message->rec_emails)) ) ){
				$errors = true;
				$msg .= '<li>'.JText::_('EMAIL_EMPTY_OR_INVALID').'</li>';
			}
		}

		$results = new JObject();
		$results->sender_email = $message->sender_email;
		$results->sender_name = $message->sender_name;
		$results->rec_emails = $message->rec_emails;
		if($errors) {
			$results->errors = $errors;
			$results->errmsg = $msg;
		}   else {
			$results->errors = false;
		}
		return $results;
	}

}

$controller = new JobboardControllerJob();
$controller->execute($task);
$controller->redirect();
?>
