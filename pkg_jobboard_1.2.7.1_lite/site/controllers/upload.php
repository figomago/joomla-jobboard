<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

// Load framework base classes
jimport('joomla.application.component.controller');
jimport('joomla.mail.helper');
JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables');

class JobboardControllerUpload extends JController
{

    private $_filename;

	function __construct()
	{
		parent :: __construct();

		$this->registerTask('uload', 'saveUnsolicitedCV');
		$this->registerTask('notify', 'sendEmailToUser');
		$errors = 0;
	}

	function display()
	{

		JRequest::checkToken() or jexit('Invalid Token');
        require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_member.php' );
		$app = JFactory::getApplication();

		$id = JRequest::getVar('job_id','','','int');
		$itemid = JRequest::getInt('Itemid');

        if(JobBoardHelper::verifyHumans()) {
          if(!JobBoardMemberHelper::matchHumanCode(JRequest::getString('human_ver', '')))
          {
             $post = JArrayHelper::toObject(JRequest::get('post'));
             $post->errors = 1; $post->cover_note = $post->cover_text;
             unset($post->cover_text);
             if(isset($post->human_ver)) unset($post->human_ver);
		     $app->setUserState('com_jobboard.afields', $post);
             $app->redirect(JRoute::_('index.php?option=com_jobboard&view=apply&errors=1&job_id='.$id.'&Itemid='.$itemid), JText::_('COM_JOBBOARD_FORM_CAPTCHA_FAILMSG'), 'error');
    		 return;
          }
        }

		$app->setUserState('com_jobboard.afields', null);

		$msg = JText::_( 'PROCESSING_ERR' ).'<ul>';

		$fields = $this->validateFields();
		$upload_result = $this->clearForUpload($fields);

		if($upload_result->errors) {
			$app->setUserState('com_jobboard.afields', $fields->fields);
			$msg .= $upload_result->msg.'</ul>';
			$link = JRoute::_('index.php?option=com_jobboard&view=apply&errors=1&job_id='.$id.'&Itemid='.$itemid, false);
			$this->setRedirect( $link, $msg, 'error' );return;
		}

		//no errors
		$fields->job_id = $id;
		$record_application = & $this->getModel('Upload');
		$saved = $record_application->saveApplication($upload_result->hash_filename, $fields);
		if($saved){
			$record_application->incrApplications($id); //increment hit counter
			$msg = '&nbsp;&nbsp;'.JText::_('APPLICATION_SUBMITTED');
			$link = JRoute::_('index.php?option=com_jobboard&view=job&id='.$id.'&Itemid='.$itemid);
			$config = JTable::getInstance('config', 'Table');
			$config->load(1);
			$this->sendEmailToUser('usernew', $fields->fields, $id, $config);
			$dept = $record_application->getDept($id);
			if($dept->notify_admin == 1 || $dept->notify == 1) {
				if($dept->notify_admin == 1 && $dept->notify == 1) {
					$recipients =  array($config->from_mail, $dept->contact_email);
				} else {
					if($dept->notify_admin == 1) $recipients = $config->from_mail;
					if($dept->notify == 1) $recipients = $dept->contact_email;
				}

				if($config->email_cvattach == 1) {
					//-> begin: Bade Adesemowo
					$cvattachment =  JPATH_ADMINISTRATOR .DS.'components'.DS.'com_jobboard' . DS . 'cv' . DS . $upload_result->hash_filename[1] . "_" . $upload_result->hash_filename[0];
					$this->sendAdminEmail($dept->name, 'adminnew_application', $recipients, $fields->fields->city, $fields->fields, $id, $config, $cvattachment);
					//-> end: Bade Adesemowo
				} else $this->sendAdminEmail($dept->name, 'adminnew_application', $recipients, $fields->fields->city, $fields->fields, $id, $config);
			}
			$this->setRedirect( $link, $msg, 'notice' );return;
		} else { //not saved
			$msg .= '<li>'.JText::_('INTERNAL_ERROR').'</li></ul>';
			$link = $link = JRoute::_('index.php?option=com_jobboard&view=apply&errors=1&job_id='.$id.'&Itemid='.$itemid, false);
			$this->setRedirect( $link, $msg, 'error' );return;
		}
	}

	function clearForUpload($fields) {

		$clear_msg = '';
        $is_errors = false;
        if(isset($fields->errors))  {
    		switch ($fields->errors) {
    			case true :
    				$clear_msg .= $fields->msg;
                    $is_errors = true;
    				break;
    			case false :
    				break;
    			default :
    				;break;
    		}
        }
  		$hash_filename = $this->uploadCv();
          if(isset($hash_filename->errors))  {
      		switch ($hash_filename->errors) {
      			case true :
      				$clear_msg .= $hash_filename->msg;
                      $is_errors = true;
      				break;
      			case false :
      				break;
      			default :
      				;break;
      		}
        }
		$result = new JObject();
		$result->errors = $is_errors;
		if($is_errors == true) {
			$result->msg = $clear_msg;
		}  else {
			$result->hash_filename = $hash_filename;
		}
		return $result;
	}

	function uploadCv()
	{

        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');

        require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_file.php' );
        require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_guest.php' );

        $filetypes = JobBoardFileHelper::getMinValidFtypes();
		$file_errors = false;
		$upload_msg = '';
        $upload_file = JRequest::getVar('cv', null, 'files', 'array');

        //Clean up filename
        $this->_filename = JFile::makeSafe($upload_file['name']);
        $this->_filename = str_replace(" ", "_", $this->_filename);

		// Check valid file format for Upload
		if($upload_file["size"] > 0){
			if($upload_file["size"] > JobBoardGuestHelper::getMaxFilesizeBytes()) {
				$upload_msg = '<li>'.JText::_( 'MAX_CVSIZE_ERR').'</li>';
				$file_errors = true;
			}
			if(!in_array($upload_file['type'], $filetypes)){
				$upload_msg .= '<li>'.JText::_( 'CV_FILEFORMAT_MSG').'</li>';
				$file_errors = true;
			}
		}else if(strlen($this->_filename)<=0 || $upload_file["size"] <= 0){
			$upload_msg = '<li>'.JText::_( 'CV_FILE_ERR').'</li>';
			$file_errors = true;
		}

        $cv_folder = JPATH_COMPONENT_ADMINISTRATOR.DS.'cv';
        if(!JFolder::exists($cv_folder))
        {
            $upload_msg = '<li>'.JText::_( 'COM_JOBBOARD_FILE_NOFOLDER').'</li>';
			$file_errors = true;
        }
		if($file_errors){
			$corrections = new JObject();
			$corrections->errors = true;
			$corrections->msg = $upload_msg;
			return $corrections;
		}

		$file_hash = strtolower($this->randId());
		$hashed_file = strtolower($file_hash.'_'.$this->_filename);

        //Set up the source and destination of the file
        $src = $upload_file['tmp_name'];
        $dest = $cv_folder.DS.$hashed_file;

        if ( !JFile::upload($src, $dest) ) {

            $upload_msg = '<li>'.JText::_( 'COM_JOBBOARD_FILE_UPLDERR').' - '.$file_name_title.'</li>';
            $file_errors = true;
        } else {
            unset($upload_file['tmp_name'], $upload_file['error']);
            //convert to generic MS-Office filetypes
            if($upload_file['type'] == $filetypes[3] || $upload_file['type'] == $filetypes[4]) $upload_file['type'] = "application/msword";
        }

		if($file_errors){
			$corrections = new JObject();
			$corrections->errors = true;
			$corrections->msg = $upload_msg;
			return $corrections;
		}

		return array(strtolower($this->_filename), $file_hash, $upload_file['type']);
	}

	function saveUnsolicitedCV()
	{
		JRequest::checkToken() or jexit('Invalid Token');
        require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_member.php' );
		$app = JFactory::getApplication();

		$itemid = JRequest::getInt('Itemid');

        if(JobBoardHelper::verifyHumans()) {
          if(!JobBoardMemberHelper::matchHumanCode(JRequest::getString('human_ver', '')))
          {
             $post = JArrayHelper::toObject(JRequest::get('post'));
             $post->errors = 1; $post->cover_note = $post->cover_text;
             unset($post->cover_text);
             if(isset($post->human_ver)) unset($post->human_ver);
		     $app->setUserState('com_jobboard.fields', $post);
             $app->redirect(JRoute::_('index.php?option=com_jobboard&view=unsolicited&errors=1&Itemid='.$itemid), JText::_('COM_JOBBOARD_FORM_CAPTCHA_FAILMSG'), 'error');
    		 return;
          }
        }

		$app->setUserState('com_jobboard.fields', null);

		$msg = JText::_( 'CV_SUBMIT_ERR' ).'<ul>';

		$fields = $this->validateFields();
                                                               
		$upload_result = $this->clearForUpload($fields);

		if($upload_result->errors) {
			$app->setUserState('com_jobboard.fields', $fields->fields);
			$msg .= $upload_result->msg.'</ul>';
			$link = JRoute::_('index.php?option=com_jobboard&view=unsolicited&errors=1&Itemid='.$itemid, false);
			$this->setRedirect( $link, $msg, 'error' );return;
		}
		//no errors
		$record_application = & $this->getModel('Upload');
		$saved = $record_application->saveUnsolicited($upload_result->hash_filename, $fields);
		if($saved){
			$msg = '&nbsp;&nbsp;'.JText::_('CV_SUBMITTED');
			$link = JRoute::_('index.php?option=com_jobboard&view=list&Itemid='.$itemid);
			$config = JTable::getInstance('config', 'Table');
			$config->load(1);
			$this->sendEmailToUser('unsolicitednew', $fields->fields, 0, $config);

    		if($config->email_cvattach == 1) {
    			//-> begin: Bade Adesemowo
    			$cvattachment =  JPATH_ADMINISTRATOR .DS.'components'.DS.'com_jobboard' . DS . 'cv' . DS . $upload_result->hash_filename[1] . "_" . $upload_result->hash_filename[0];
    			$this->sendEmailUnsolicited('adminnew_unsolicited', $fields->fields, $config, $cvattachment);
    			//-> end: Bade Adesemowo
    		} else $this->sendEmailUnsolicited('adminnew_unsolicited', $fields->fields, $config);

			$this->setRedirect( $link, $msg, 'notice' );return;
		} else { //not saved
			$msg .= '<li>'.JText::_('INTERNAL_ERROR').'</li></ul>';
			$link =  JRoute::_('index.php?option=com_jobboard&view=unsolicited&errors=1&Itemid='.$itemid, false);
			$this->setRedirect( $link, $msg, 'error' );return;
		}
	}

	function validateFields(){

		$first_name = JRequest::getVar('first_name','','','string');
		$last_name = JRequest::getVar('last_name','','','string');
		$email = JRequest::getVar('email','','','string');
		$tel = JRequest::getVar('tel','','','string');
		$title = JRequest::getVar('title','','','string');
		$cover_note = JRequest::getVar('cover_text','','','string');
        $msg = '';

		$errors = false;

		if($first_name == '') {
			$msg .= '<li>'.JText::_('FIRSTNAME_ERR').'</li>';
			$errors = true;
		}
		if($last_name == '') {
			$msg .= '<li>'.JText::_('LASTNAME_ERR').'</li>';
			$errors = true;
		}
		if($email == '') {
			$msg .= '<li>'.JText::_('EMAIL_ERR').'</li>';
			$errors = true;
		} else {
			$mail_errors = false;
			$mail_errors = (!JMailHelper::cleanAddress($email))? true: false;
			$mail_errors = (!JMailHelper::isEmailAddress($email))? true: false;

			if($mail_errors)  {
				$errors = true;
				$msg .= '<li>'.JText::_('VALID_EMAIL_ERR').'</li>';
			}
		}

		if($tel == '') {
			$msg .= '<li>'.JText::_('VALID_TEL_ERR').'</li>';
			$errors = true;
		}
		if($title == '') {
			$msg .= '<li>'.JText::_('CVTITLE_ERR').'</li>';
			$errors = true;
		}

		$results = new JObject();
		$results->msg = $msg;
		$results->fields->first_name = $first_name;
		$results->fields->last_name = $last_name;
		$results->fields->email = $email;
		$results->fields->tel = $tel;
		$results->fields->title = $title;
		$results->fields->cover_note = $cover_note;
		$results->fields->city = JRequest::getVar('city','','','string');
		if($errors) {
			$results->errors = $errors;
		}   else {
			$results->errors = false;
		}
		return $results;
	}

	/**
	 * Generate Random ID
	 *
	 **/
	function randId(){

	  if(version_compare(JVERSION,'2.5.0','ge') || version_compare(JVERSION,'1.7.0','ge'))
            $token = JSession::getFormToken();
        elseif( version_compare(JVERSION,'1.6.0','ge') || version_compare(JVERSION,'1.5.0','ge'))
		    $token = JUtility::getToken();
		return sprintf('%08x-%04x', mt_rand(), mt_rand(0, 65535) ).$token;
	}

	function sendEmailUnsolicited($type, $recipient, $config, $cvattachment=null)
	{
		$messg_model =& $this->getModel('Message');
		$msg_id = $messg_model->getMsgID($type);
		$msg = $messg_model->getMsg($msg_id);

		$from = $config->reply_to;
		$fromname = $config->organisation;
		$to_email = $config->from_mail;
		$to_name = $recipient->first_name;
		$to_surname = $recipient->last_name;
		$to_title = $recipient->title;

		$subject = $msg->subject;
		$body = $msg->body;

		$subject = str_replace('[fromname]', $fromname, $subject);
		$subject = str_replace('[toname]', $to_name, $subject);
		$subject = str_replace('[tosurname]', $to_surname, $subject);
		$subject = str_replace('[cvtitle]', $to_title, $subject);

		$body = str_replace('[fromname]', $fromname, $body);
		$body = str_replace('[toname]', $to_name, $body);
		$body = str_replace('[tosurname]', $to_surname, $body);
		$body = str_replace('[cvtitle]', $to_title, $body);

        return JobBoardHelper::dispatchEmail($from, $fromname, $to_email, $subject, $body, $cvattachment);
	}

	function sendEmailToUser($type, $recipient, $id=0, $config)
	{
		$messg_model =& $this->getModel('Message');
		$msg_id = $messg_model->getMsgID($type);
		$msg = $messg_model->getMsg($msg_id);

		$from = $config->reply_to;
		$fromname = $config->organisation;
		$to_email = $recipient->email;
		$to_name = $recipient->first_name;
		$to_title = $recipient->title;

		$subject = $msg->subject;
		$body = $msg->body;

		$body = str_replace('[fromname]', $fromname, $body);
		$body = str_replace('[toname]', $to_name, $body);

		if($type === 'unsolicitednew') {
			$subject = str_replace('[toname]', $to_name, $subject);
			$subject = str_replace('[cvtitle]', $to_title, $subject);
		}
		if($type === 'usernew') {
			$job_model =& $this->getModel('Job');
			$job = $job_model->getJobdata($id);
			$subject = str_replace('[jobtitle]', $job->job_title, $subject);
			$subject = str_replace('[location]', $recipient->city, $subject);
			$body = str_replace('[jobtitle]', $job->job_title, $body);
		}

        return JobBoardHelper::dispatchEmail($from, $fromname, $to_email, $subject, $body);
	}

	function sendAdminEmail($dept_name, $type, $recipients, $job_location, $application, $id=0, $config, $cvattachment=null)
	{
		$messg_model =& $this->getModel('Message');
		$msg_id = $messg_model->getMsgID($type);
		$msg = $messg_model->getMsg($msg_id);

		$from = $config->reply_to;
		$fromname = $config->organisation;
		$applicant_email = $application->email;
		$applicant_firstname = $application->first_name;
		$applicant_lastname = $application->last_name;
		$job_model =& $this->getModel('Job');
		$job = $job_model->getJobdata($id);
		$job_title = $job->job_title;

		$application_title = $application->title;
		$application_note = $application->cover_note;
		$appl_admin = $applicant_firstname.' '.$applicant_lastname;

		$subject = $msg->subject;
		$body = $msg->body;

		$subject = str_replace('[applstatus]', JText::_('COM_JOBBOARD_ENT_NEW'), $subject);
		$subject = str_replace('[applname]', $applicant_firstname, $subject);
		$subject = str_replace('[applsurname]', $applicant_lastname, $subject);
		$subject = str_replace('[fromname]', $fromname, $subject);
		$subject = str_replace('[jobtitle]', $job_title, $subject);
		$subject = str_replace('[appltitle]', $application_title, $subject);
		$subject = str_replace('[location]', $job_location, $subject);
		$subject = str_replace('[jobid]', $id, $subject);
		$subject = str_replace('[department]', $dept_name, $subject);
		$subject = str_replace('[appladmin]', $appl_admin, $subject);   /* applicant in this case */

		$body = str_replace('[applstatus]', JText::_('COM_JOBBOARD_ENT_NEW'), $body);
		$body = str_replace('[applname]', $applicant_firstname, $body);
		$body = str_replace('[applsurname]', $applicant_lastname, $body);
		$body = str_replace('[fromname]', $fromname, $body);
		$body = str_replace('[jobtitle]', $job_title, $body);
		$body = str_replace('[appltitle]', $application_title, $body);
		$body = str_replace('[applcovernote]', $application_note, $body);
		$body = str_replace('[location]', $job_location, $body);
		$body = str_replace('[jobid]', $id, $body);
		$body = str_replace('[department]', $dept_name, $body);
		$body = str_replace('[appladmin]', $appl_admin, $body);   /* applicant in this case */

        return JobBoardHelper::dispatchEmail($from, $fromname, $recipients, $subject, $body, $cvattachment);
	}
}

$controller = new JobboardControllerUpload();
$controller->execute($task);
$controller->redirect();
?>
