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

class JobboardControllerApplicants extends JController
{

    private $view;

    /**
	 * constructor
	 */
	function __construct()
	{
        parent::__construct();

		$this->registerTask('edappl', 'editApplication');
		$this->registerTask('saveappl', 'saveApplication');
		$this->registerTask('applyappl', 'saveApplication');
		$this->registerTask('getucvfile', 'downloadUCV');
    }

	function edit()
	{
		$this->displaySingle('old');
	}
	
	function display()
	{
	    $doc =& JFactory::getDocument();
        $style = " .icon-48-job_applicants {background-image:url(components/com_jobboard/images/job_applicants.png); no-repeat; }";
        $doc->addStyleDeclaration( $style );
		JToolBarHelper::title(JText::_( 'COM_JOBBOARD_JOB_APPLICANTS'), 'job_applicants.png');

		JToolBarHelper::editList();
		$view = JRequest::getVar('view');
		if(!$view)
		{
			JRequest::setVar('view', 'applicants');
		}

        JobBoardToolbarHelper::setToolbarLinks('applicants');

		$cfig_model =& $this->getModel('Config');
		$config = $cfig_model->getApplConfig();

        $status_model =& $this->getModel('Status');
        $statuses = $status_model->getStatuses();

		$_view = & $this->getView('applicants', 'html');

        $app =& JFactory::getApplication();
        $vcontext  = $app->getUserStateFromRequest('com.jobboard.applicatnts.vcontext', 'vcontext', 1, 'int');
        if($vcontext > 2 ) {
            $vcontext = 1;
            $app->setUserState('com.jobboard.applicatnts.vcontext', $vcontext, 'int');
        }

        switch($vcontext) {
          case 1 :
		    $appl_model =& $this->getModel('Applicants');
          break;
          case 2 :
            $_view->setLayout('default_site');
		    $appl_model =& $this->getModel('Applicantssite');
            JToolBarHelper::deleteList();
          break;
        }

		JToolBarHelper::cancel('close', JText::_('COM_JOBBOARD_TXT_CLOSE'));

        $_view->setModel($appl_model, true);
        $_view->assignRef('statuses', $statuses);
		$_view->assignRef('config', $config);
		$_view->assign('vcontext', $vcontext);

		$_view->display();
	}	
	
	function displaySingle($type)
	{
	    $app =& JFactory::getApplication();
	    $vcontext  = $app->getUserStateFromRequest('com.jobboard.applicatnts.vcontext', 'vcontext', 1, 'int');
        switch($vcontext){
           case 1:
               $this->editApplication();
           break;
           case 2:
      	    $doc =& JFactory::getDocument();
              $style = " .icon-48-applicant_details {background-image:url(components/com_jobboard/images/applicant_details.png); no-repeat; }";
              $doc->addStyleDeclaration( $style );

      		  JToolBarHelper::title(JText::_( 'COM_JOBBOARD_JOB_APPL_DETAILS'), 'applicant_details.png');
      		  JToolBarHelper::back();
      		  JToolBarHelper::apply();
      		  JToolBarHelper::save();
      		  JToolBarHelper::cancel('close', JText::_('COM_JOBBOARD_TXT_CLOSE'));
              JobBoardToolbarHelper::setToolbarLinks('applicants');

              $status_model =& $this->getModel('Status');
              $appl_model =& $this->getModel('Applicantedit');
              $statuses = $status_model->getStatuses();
              $departments = $status_model->getDepartments();
      		  $cfig_model =& $this->getModel('Config');
      		  $config = $cfig_model->getApplConfig();

              JRequest::setVar('view', 'applicantedit');
              $view = &$this->getView('applicantedit', 'html');
              $view->setLayout('default_site');
      		  $view->setModel($appl_model, true);

      		  $view->assignRef('config',$config);
              $view->assignRef('statuses', $statuses);
              $view->assignRef('departments', $departments);
        	  if($type='new') JRequest::setVar('task','add');

        	  $view->display();
           break;
        }
	}	
	
	function close()
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
            $jobs_model= & $this->getModel('Applicant');
            $delete_result = $jobs_model->deleteApplicants($cids);
    		if ($delete_result <> true) {
    			//echo "<script> alert('".$db->getErrorMsg(true)."'); window.history.go(-1); </script>\n";
    			$this->setRedirect('index.php?option=com_jobboard&view=applicants', $delete_result);
    		}
    		 else {
				$success_msg = (count($cid ) == 1)? JText::_('COM_JOBBOARD_APPLICANT_DELETED') : JText::_('COM_JOBBOARD_APPLICANTS_DELETED');
				$this->setRedirect('index.php?option=com_jobboard&view=applicants', $success_msg);
		    }
	    }
	}
    //-> end: Juan Jose Perez

    function editApplication()
	{

	    $doc =& JFactory::getDocument();
        $style = " .icon-48-applicant_details {background-image:url(components/com_jobboard/images/applicant_details.png); no-repeat; }";
        $doc->addStyleDeclaration( $style );

		JToolBarHelper::title(JText::_( 'COM_JOBBOARD_JOB_APPL_DETAILS'), 'applicant_details.png');
		JToolBarHelper::back();
		JToolBarHelper::apply('applyappl');
		JToolBarHelper::save('saveappl');
		JToolBarHelper::cancel('close', JText::_('COM_JOBBOARD_TXT_CLOSE'));
        JobBoardToolbarHelper::setToolbarLinks('applicants');
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_admappl.php');

	    $view  =& $this->getView('applicantedit', 'html');
       // $view->setLayout('admin');

        $cids = JRequest::getVar('cid', array(), 'array');
        $appl_id = isset($cids[0])? $cids[0] : JRequest::getInt('aid') ;
        $appl_ids = JobBoardAdmapplHelper::getApplIds($appl_id);
        $jid = $appl_ids['jid'];

        $appl_uid = $appl_ids['sid'];
        $qid = $appl_ids['qid'];
        $applicant_model =& $this->getModel('Uapplicant');
      	$cfig_model =& $this->getModel('Config');

        if($qid > 0) {
           $questionnaire = $applicant_model->getQuestionnaire($qid);
           $fields = json_decode($questionnaire['fields']);
           if(!is_object($fields)) {
            $qid = 0;
           } else {
             unset($questionnaire['fields']);
             $q_answers = $applicant_model->getApplicantQanswers($qid, $appl_id);
             jimport('joomla.utilities.date');
             $today = new JDate();
             $view->assignRef('questionnaire', $questionnaire);
             $view->assignRef('q_answers', $q_answers);
             $view->assignRef('fields', $fields->fields);
             $view->assignRef('today', $today);
           }
        }

        $user_model = &$this->getModel('User');
        $job_title = $applicant_model->getApplJobTitle($jid);

        $prof_id = $appl_ids['pid'];
        $applicant_name = $user_model->getJUsername($appl_uid);
        $cv_name = $applicant_model->getCvProfileName($prof_id, $appl_uid);
        $user_prof_data = $user_model->getSeekerProfile($appl_uid, true);
        $view->assign('cv_name', $cv_name);
        $view->assign('applicant_name', $applicant_name);
        $view->assign('qid', $qid);
        $view->assign('appl_uid', $appl_uid);
        $view->assign('pid', $prof_id);

        $appl_data = $applicant_model->getApplication($appl_id);
        $statuses = $applicant_model->getStatuses();

        require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_admuser.php');
        $pp_status = JobBoardAdmuserHelper::checkProfilePicStatus($appl_uid, &$user_model, 2);

        $view->assign('context', 'application');
        $view->assignRef('user_prof_data', $user_prof_data);
        $view->assignRef('appl_data', $appl_data);
        $view->assignRef('statuses', $statuses);
        $view->assign('is_profile_pic', $pp_status['is_profile_pic']);
        $view->assign('imgthumb', $pp_status['urithumb']);
        $view->assign('imgthumb_115', $pp_status['urithumb2']);
        $view->assign('job_title', $job_title);
        $view->assign('aid', $appl_id);
        $view->assign('jid', $jid);
      	$view->assignRef('config',$config);

	    $view->display();
	}

	function saveApplication()
	{
        JRequest::checkToken() or jexit( JText::_('Invalid Token') );

        $appl_data = array('status' => JRequest::getInt('status'), 'admin_notes'=> JRequest::getVar('admin_notes', '', 'POST', 'string', JREQUEST_ALLOWRAW));

        $jid = JRequest::getInt('jid');
        $aid = JRequest::getInt('aid');
        $qid = JRequest::getInt('qid');

        $applicant_model =& $this->getModel('Uapplicant');

        if($qid > 0) {
           require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_appl.php' );
           $fields_arr = array();
           $fields =  json_decode($applicant_model->getQuestionnaireFields($qid));

           foreach($fields->fields as $field) {
             if($field->restricted == 1){
                $field_type = JobBoardApplHelper::setFieldType($field->type);
                $default_type = $field_type == 'int'? 0 : '';

                if($field->type == 'select') {
                  $value =  JRequest::getVar($field->name, $default_type, 'post', 'array');
                  $value = (count($value) == 1)? $value[0] : implode(',', $value);
                }
                if($field->type == 'date') {
                  $value =  JRequest::getVar($field->name, $default_type, 'post', 'array');
                  $date_day = !isset($value['day'])? 1 : intval($value['day']);
                  $date_day = ($date_day < 0 ||$date_day > 31)? 1 : $date_day;
                  $date_month = !isset($value['month'])? 1 : intval($value['month']);
                  $date_month = ($date_month < 0 ||$date_month > 12)? 1 : $date_month;
                  $date_year = !isset($value['year'])? '2000' : intval($value['year']);
                  $date_year = ($date_year < 0)? '2000' : $date_year;
                  $value =  $date_year.'-'.sprintf("%02d", $date_month).'-'.sprintf("%02d", $date_day);
                } elseif($field->type <> 'select') {
                  $value =  JRequest::getVar($field->name, $default_type, 'post', 'string');
                }
                if($field->type == 'checkbox') {
                  $value =  $value == 'yes'? 1 : 0;
                }
                $fields_arr[] = array($field->name, $value, $field_type);
              }
           }

           $errors = !$applicant_model->updApplicantQanswers($qid, $aid, $fields_arr)? true : false;
        }

        if(!$applicant_model->saveApplication($aid, $appl_data) )  {
            $msg =  JText::_('COM_JOBBOARD_ERRORS_OCCURED').'<br />';
            $msg .=  JText::sprintf('COM_JOBBOARD_ENT_UPDERR', JText::_('COM_JOBBOARD_JOBAPPLICATION'));
            $msgtype = 'error';
        } else {
            $applicant = JArrayHelper::toObject($appl_data);
            require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_appl.php' );
            $applicant->job_id = $jid;
            $applicant->department = JobBoardApplHelper::getDeptId($jid);
            $applicant->title = JobBoardApplHelper::getJobTitle($jid);
            $applicant_details = JobBoardApplHelper::getRegUser($aid);
            $applicant->first_name = $applicant_details['name'];
            $applicant->last_name = '';
            $applicant->email = $applicant_details['email'];
            $applicant->auid = $applicant_details['id'];
            $messg_model =& $this->getModel('Message');
            $process_mail = JobBoardApplHelper::processMail($applicant, true, $messg_model);
            $msg =  JText::sprintf('COM_JOBBOARD_ENT_UPDATED', JText::_('COM_JOBBOARD_JOBAPPLICATION'));
            $msgtype = 'Message';
        }


        $savemode = JRequest::getCmd('task');
        switch($savemode){
          case 'applyappl':
              $extra_ids = $applicant_model->getApplIds($aid);
              $this->setRedirect('index.php?option=com_jobboard&view=applicants&task=edappl&aid='.$aid.'&sid='.$extra_ids['sid'].'&pid='.$extra_ids['pid'].'&qid='.$qid.'&jid='.$jid, $msg, $msgtype);
          break;
          case 'saveappl':
             $this->setRedirect('index.php?option=com_jobboard&view=applicants', $msg, $msgtype);
          break;
        }
	}

    function downloadUCV() {
	   // Check for request forgeries
	   JRequest::checkToken('get') or jexit( JText::_('Invalid Token') );

       $dmode = JRequest::getInt('dmode', 0);
       $applicant_model =& $this->getModel('Uapplicant');
       $file_id = JRequest::getInt('file');

       if($dmode == 0)  {
         $cvprof_id = JRequest::getInt('pid');
         $cv_uid = JRequest::getInt('uid');

         $file = $applicant_model->getCvFile($file_id, $cvprof_id, $cv_uid);
         $filename = $file->filepath.DS.$file->filename;
         if(!JFile::exists($filename))
         {
              $msg = JText::_('COM_JOBBOARD_FILE_NOTFOUND');
              return ;//$this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=user&task=viewcv&profileid='.$cvprof_id.'&Itemid='.$this->_itemid), $msg, 'error');
         }
       } elseif($dmode == 1){

              $file = $applicant_model->getSiteCv($file_id);
              $filepath = "components/com_jobboard/cv/";
              $fname = ($file->file_hash == '')? $file->filename : $file->file_hash.'_'.$file->filename;

              $filename = $filepath.$fname;
         }

       $view  =& $this->getView('applicants', 'file');
       $view->assign('file', $filename);

	   $view->display();
    }

}

$controller = new JobboardControllerApplicants();
if(!isset($task)) $task = "display";
$controller->execute($task);
$controller->redirect();

?>