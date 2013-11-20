<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.controller');

class JobboardControllerApplicantEdit extends JController
{

    /**
	 * constructor
	 */
	function __construct()
	{
        parent::__construct();
    }

	function save()
	{
		JRequest::checkToken() or jexit('Invalid Token');
        $applicant = JArrayHelper::toObject(JRequest::get('POST'));

        $appl_model =& $this->getModel('Applicant');
        if(!$appl_model->save($applicant)) {
            JError::raiseError(500, $appl_model->getError());
        } else {                                                          
            require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_appl.php' );
            $messg_model =& $this->getModel('Message');
            $process_mail = JobBoardApplHelper::processMail($applicant, false, $messg_model);
    	    $this->setRedirect('index.php?option=com_jobboard&view=applicants', JText::_('COM_JOBBOARD_JOB_APP_SAVED'));
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
		$cfig_model =& $this->getModel('Config');
		$config = $cfig_model->getApplConfig();

		$view = $this->getView('config', 'html');
		$view->assignRef('config', $config);
		
		JRequest::setVar('view','applicantedit');
		$view->display();
	}

	function apply()
	{
		JRequest::checkToken() or jexit('Invalid Token');
        $applicant = JArrayHelper::toObject(JRequest::get('POST'));

        $appl_model =& $this->getModel('Applicant');
        if(!$appl_model->save($applicant)) {
            JError::raiseError(500, $appl_model->getError());
        } else {
            $saved_text = JText::_('COM_JOBBOARD_JOB_APP_SAVED');
            $feedback_string = $saved_text;
            require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_appl.php' );
            $messg_model =& $this->getModel('Message');
            $process_mail = JobBoardApplHelper::processMail($applicant, false, $messg_model);
    	    $this->setRedirect('index.php?option=com_jobboard&view=applicants&task=edit&cid[]='.$applicant->id, $feedback_string);
        }
     }

	function back()
	{
		//reset the parameters
		JRequest::setVar('task', '');
		JRequest::setVar('view', 'applicants');

		//call up the list screen controller
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'controllers'.DS.'applicants.php');
	}
	function close()
	{
		//reset the parameters
		JRequest::setVar('task', '');
		JRequest::setVar('view', 'applicants');

		//call up the list screen controller
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'controllers'.DS.'applicants.php');
	}

    function sendEmail($msgobj, $config, $to_email, $msg_type)
     {
       $messg_model =& $this->getModel('Message');
       $msg_id = $messg_model->getMsgID($msg_type);
       $msg = $messg_model->getMsg($msg_id);

       $from = $config->reply_to;
       $fromname = $config->organisation;
      /* $to_email = $msgobj->email;*/

       $subject = str_replace('[jobtitle]', $msgobj->title, $msg->subject);
       $subject = str_replace('[jobid]', $msgobj->job_id, $subject);
       $subject = str_replace('[toname]', $msgobj->first_name, $subject);
       $subject = str_replace('[tosurname]', $msgobj->last_name, $subject);
       $subject = str_replace('[fromname]', $fromname, $subject);

       $body = str_replace('[jobid]', $msgobj->job_id, $msg->body);
       $body = str_replace('[jobtitle]', $msgobj->title, $body);
       $body = str_replace('[toname]', $msgobj->first_name, $body);
       $body = str_replace('[tosurname]', $msgobj->last_name, $body);
       $body = str_replace('[fromname]', $fromname, $body);

       if($msg_type == 'adminupdate_application') {
         $status_tbl = & JTable::getInstance('Status', 'Table');
         $status_tbl->load($msgobj->status);
         $user = & JFactory::getUser();
         $body = str_replace('[appladmin]', $user->name, $body);
         $body = str_replace('[department]', $msgobj->dept_name, $body);
         $body = str_replace('[applstatus]', $status_tbl->status_description, $body);
       }

       return JobBoardHelper::dispatchEmail($from, $fromname, $to_email, $subject, $body); 
     }
}
	
$controller = new JobboardControllerApplicantEdit();
$controller->execute($task);
$controller->redirect();

?>