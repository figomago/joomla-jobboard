<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard.php' );
JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables');

// Load framework base classes
jimport('joomla.application.component.controller');

class JobboardControllerUser extends JController
{
     var $_uid = null;
     var $_itemid = null;
     var $_umodel = null;
     var $_default_dash = null;
     var $_auth_model = null;
     var $_user_cred = null;
     var $_is_admin = null;
     var $_show_modeswitch = null;

    /**
	 * constructor
	 */
	public function __construct()
	{
	    $user = & JFactory::getUser();
        //kick out if user has no access
        if($user->get('guest')) {
            $return = JRequest::getString('redirect', '');
            $app = &JFactory::getApplication();
            $task = JRequest::getCmd('task', '');
            if($task == 'apply' || $task == 'addfav'){
              if(!empty($return))
                  return $app->redirect(JRoute::_('index.php?option=com_jobboard&view=member&iview=login&redirect='.$return));
              else
                  return $app->redirect(JRoute::_('index.php?option=com_jobboard&view=member&iview=login'));
            } else {
              $msg =  JText::_('COM_JOBBOARD_ENTNOAUTH');
              $msgtype = 'error';
              if(!empty($return))
                  return $app->redirect(JRoute::_('index.php?option=com_jobboard&view=member&iview=login&redirect='.$return), $msg, $msgtype);
              else
                  return $app->redirect(JRoute::_('index.php?option=com_jobboard&view=member&iview=login'), $msg, $msgtype);
            }
        }

		parent::__construct();
        $uid = $user->id;

		$this->registerTask('prof', 'showUserProfile');
		$this->registerTask('marked', 'showMarked');
		$this->registerTask('delfav', 'deleteBookmarkP');
		$this->registerTask('jdelfav', 'deleteBookmarkG');
		$this->registerTask('addfav', 'createBookmark');
		$this->registerTask('appl', 'showApplications');
		$this->registerTask('invites', 'showInvites');
		$this->registerTask('apply', 'userApply');
		$this->registerTask('saveappl', 'saveApplication');
		$this->registerTask('delappl', 'delApplication');
		$this->registerTask('cvprofs', 'showCvProfiles');
		$this->registerTask('addcv', 'editCvProfile');
		$this->registerTask('editcv', 'editCvProfile');
		$this->registerTask('viewcv', 'showCvProfile');
		$this->registerTask('delcv', 'delCvProfile');
		$this->registerTask('clonecv', 'cloneCvProfile');
		$this->registerTask('getfile', 'downloadFile');
		$this->registerTask('delcvfile', 'deleteCvFile');
		$this->registerTask('settings', 'userSettings');
		$this->registerTask('getlinkedinprof', 'importLinkedInProfile');
		$this->registerTask('tokenrevoke', 'revokeLinkedInToken');
		$this->registerTask('save', 'saveProfData');
		$this->registerTask('deledu', 'deleteEducation');
		$this->registerTask('delemp', 'deleteEmployer');
		$this->registerTask('delskill', 'deleteSkill');

		$user_model =& $this->getModel('User');
		$auth_model =& $this->getModel('Member');
        $this->_setUid($uid);

        $itemid = JRequest::getInt('Itemid');
        $this->_setItemid($itemid);
        $this->_setUmodel(&$user_model);
        $this->_setAuthmodel(&$auth_model);

        $user_enabled = $auth_model->isEnabled($uid);
        //kick out if user disabled
        if(!$user->get('guest') && !$user_enabled) {
            $msg =  JText::_('COM_JOBBOARD_ENTNOAUTH');
            $msgtype = 'error';
            return $this->setRedirect(JRoute::_('index.php?option=com_jobboard'), $msg, $msgtype);
        }

        $ucred = $this->_auth_model->getUserCred($uid);
        $default_dash = $auth_model->getDashConfig($uid);
        $show_modeswitch = $auth_model->getModeswitchConfig($uid);
        $this->_setUserCreds(&$ucred, $default_dash, $show_modeswitch);

	}

   /**
   * The class destructor.
   *
   * Explicitly clears class object from memory upon destruction.
   */
    public function __destruct() {
      unset($this);
  	}

    private function _setUid($uid){
       if($uid == 0)  {
         $return = JRequest::getString('redirect', '');
            return $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=member&redirect='.$return));
        } else {
          $this->_uid = $uid;
        }
    }

    private function _setItemid($itemid){
        $this->_itemid = $itemid;
    }

    private function _setUmodel($umodel){
        $this->_umodel = $umodel;
    }

    private function _setAuthmodel($auth_model){
        $this->_auth_model = $auth_model;
    }

    private function _setUserCreds($ucred, $default_dash, $show_modeswitch){
        $this->_user_cred = $ucred;
        $this->_default_dash = $default_dash;
        $this->_setUserLevel($ucred, $show_modeswitch);
    }

    private function _setUserLevel($ucred, $show_modeswitch){
      $this->_show_modeswitch = 0;

      if($ucred['user_status'] <> 0) :
        if($ucred['post_jobs'] == 0 && $ucred['post_jobs'] == 0 && $ucred['manage_jobs'] == 0 && $ucred['manage_applicants'] == 0 && $ucred['search_private_cvs'] == 0 && $ucred['create_questionnaires'] == 0 && $ucred['manage_questionnaires'] == 0 && $ucred['manage_departments'] == 0) {
            $this->_is_admin = 0;
            $this->_show_modeswitch = 0;
        } else {
            $this->_is_admin = 1;
            $this->_default_dash = 0;
            $this->_show_modeswitch = $show_modeswitch;
        }
      endif;
      $this->_user_cred['show_modeswitch'] = $this->_show_modeswitch;
    }

	function display()
	{
        $app = & JFactory::getApplication();
        $return = JRequest::getString('redirect', '');
        $current_dash = $app->getUserStateFromRequest('com_jobboard.curr_dash', 'curr_dash', $this->_default_dash);

        if($return == '') {
          if($current_dash == 1 && $this->_is_admin == 1) {
                 $app->setUserState('com_jobboard.curr_dash', $current_dash);
                 return $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=admin&Itemid='.$this->_itemid));
          }

        } else {
             return $this->setRedirect(JRoute::_(base64_decode($return).'&'.JUtility::getToken().'=1&Itemid='.$this->_itemid));
        }

        $profile_pic = JobBoardHelper::checkProfilePicStatus($this->_uid, $this->_umodel);
        $layout_style = $this->_umodel->getLayoutConfig();
        $num_applications = $this->_umodel->getNumApplications($this->_uid);
        $marked_jobs = $this->_umodel->getMarkedJobs($this->_uid);
        $user_skills = $this->_umodel->getCvSkills($this->_uid);
        $jobs_matching_skills = $this->_umodel->getJobsByKeywords($user_skills);
        $user_applications = $this->_umodel->getApplicationsSummary($this->_uid, 3);
        $profile_views = $this->_umodel->getProfileHits($this->_uid, JobBoardHelper::getToday());
        $invites = $this->_umodel->getNumInvites($this->_uid);
        require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_user.php' );
        $show_status = JobBoardUserHelper::showApplStatus();
        unset($user_skills);

	    $view  =& $this->getView('user', 'html');
        $view->setLayout('user');
        $view->assign('context', 'user');
        $view->assign('layout_style', $layout_style);
        $view->assign('num_applications', $num_applications);
        $view->assign('marked_jobs', $marked_jobs);
        $view->assign('is_profile_pic', $profile_pic['is_profile_pic']);
        $view->assign('imgthumb', $profile_pic['urithumb']);
        $view->assignRef('user_auth', $this->_user_cred);
        $view->assignRef('matching_jobs', $jobs_matching_skills);
        $view->assignRef('user_applications', $user_applications);
        $view->assign('profile_views', $profile_views);
        $view->assign('invites', $invites);
        $view->assign('show_status', $show_status);
        $view->assign('itemid', $this->_itemid);

	    $view->display();
	}

	function showMarked()
	{
		$bookmarks_model =& $this->getModel('Userbookmarkslist');
        $profile_pic = JobBoardHelper::checkProfilePicStatus($this->_uid, $this->_umodel);
        JRequest::setVar('uid', $this->_uid);
        $data = $bookmarks_model->getData();
        $layout_style = $this->_umodel->getLayoutConfig();

	    $view  =& $this->getView('user', 'html');
        $view->setLayout('user');
        $view->setModel($bookmarks_model, true );
        $view->assign('context', 'marked');
        $view->assign('data', $data);
        $view->assign('is_profile_pic', $profile_pic['is_profile_pic']);
        $view->assign('imgthumb', $profile_pic['urithumb']);
        $view->assign('layout_style', $layout_style);
        $view->assignRef('user_auth', $this->_user_cred);

	    $view->display();
	}

	function createBookmark()
	{
	    JRequest::checkToken('get') or jexit( JText::_('Invalid Token') );
        $jid = JRequest::getInt('job_id');

        if($this->_uid < 1)  {
          $return = JRequest::getString('redirect', '');
          return $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=member&redirect='.$return.'&Itemid='.$this->_itemid));
        }
        $bookmark_id = $this->_umodel->hasBookmark($jid, $this->_uid);

        if($bookmark_id > 0)  {
          $msg =  JText::_('COM_JOBBOARD_ALREADY_BOOKMARKED');
          return $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=job&id='.$jid.'&Itemid='.$this->_itemid), $msg, 'Message');
        }

        if(!$this->_umodel->saveBookmark($jid, $this->_uid)){
            $msg =  JText::_('COM_JOBBOARD_ERRORS_OCCURED').'<br />';
            $msg .=  JText::sprintf('COM_JOBBOARD_ENT_UPDERR', JText::_('COM_JOBBOARD_JOBMARK'));
            $msgtype = 'error';
        } else {
            $msg =  JText::sprintf('COM_JOBBOARD_ENT_CREATED', JText::_('COM_JOBBOARD_JOBMARK'));
            $msgtype = 'Message';
        }

        return $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=job&id='.$jid.'&Itemid='.$this->_itemid), $msg, $msgtype);

	}

    function deleteBookmarkP(){
        JRequest::checkToken() or jexit( JText::_('Invalid Token') );
        $result = $this->_deleteBookmark();

        return $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=user&task=marked&Itemid='.$this->_itemid), $result['msg'], $result['msgtype']);

    }

    function deleteBookmarkG(){
        JRequest::checkToken('get') or jexit( JText::_('Invalid Token') );
        $result = $this->_deleteBookmark();

        $jid = JRequest::getInt('job_id');

        $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=job&id='.$jid.'&Itemid='.$this->_itemid), $result['msg'], $result['msgtype']);
    }

	private function _deleteBookmark()
	{
        $bid = JRequest::getInt('bid');

        $success = $this->_umodel->delBookmark($bid, $this->_uid);
        if(!$success){
            $msg =  JText::_('COM_JOBBOARD_ERRORS_OCCURED').'<br />';
            $msg .=  JText::sprintf('COM_JOBBOARD_ENT_DELERR', JText::_('COM_JOBBOARD_JOBMARK'));
            $msgtype = 'error';
        } else {
            $msg =  JText::sprintf('COM_JOBBOARD_ENT_DELETED', JText::_('COM_JOBBOARD_JOBMARK'));
            $msgtype = 'Message';
        }
        return array('msg' => $msg, 'msgtype' => $msgtype);
	}

	function showInvites()
	{
		$userdata_model =& $this->getModel('Inviteslist');
        $profile_pic = JobBoardHelper::checkProfilePicStatus($this->_uid, $this->_umodel);
        $layout_style = $this->_umodel->getLayoutConfig();
        //auth?
        JRequest::setVar('uid', $this->_uid);
        $userdata = $userdata_model->getData();
        require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_invite.php' );

	    $view  =& $this->getView('user', 'html');
        $view->setLayout('user');
        $view->setModel( $userdata_model, true );
        $view->assign('context', 'invites');
        $view->assign('data', $userdata);
        $view->assign('is_profile_pic', $profile_pic['is_profile_pic']);
        $view->assign('imgthumb', $profile_pic['urithumb']);
        $view->assign('layout_style', $layout_style);
        $view->assignRef('user_auth', $this->_user_cred);

	    $view->display();

    }

	function showApplications()
	{
		$userdata_model =& $this->getModel('Userapplicationslist');
        $profile_pic = JobBoardHelper::checkProfilePicStatus($this->_uid, $this->_umodel);
        $layout_style = $this->_umodel->getLayoutConfig();
        //auth?
        JRequest::setVar('uid', $this->_uid);
        $userdata = $userdata_model->getData();

	    $view  =& $this->getView('user', 'html');
        $view->setLayout('user');
        $view->setModel( $userdata_model, true );
        $view->assign('context', 'applications');
        $view->assign('data', $userdata);
        $view->assign('is_profile_pic', $profile_pic['is_profile_pic']);
        $view->assign('imgthumb', $profile_pic['urithumb']);
        $view->assign('layout_style', $layout_style);
        $view->assignRef('user_auth', $this->_user_cred);

	    $view->display();
	}

	function userApply()
	{
	    $p_mode = JRequest::getInt('p_mode', 0);

        if($p_mode == 1) {
           JRequest::checkToken() or jexit( JText::_('Invalid Token') );
        } else {
           JRequest::checkToken('get') or jexit( JText::_('Invalid Token') );
        }

        $job_id = JRequest::getInt('jid');

        $selcat = JRequest::getInt('cat_id', 1);
        $qid = JRequest::getInt('qid');

        if($this->_uid < 1)  {
          $return = JRequest::getString('redirect', '');
          return $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=member&redirect='.$return.'&Itemid='.$this->_itemid));
        }

        if($this->_umodel->isJobOwner($this->_uid, $job_id)) {
          return $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=job&id='.$job_id.'&Itemid='.$this->_itemid), JText::_('COM_JOBBOARD_APPLICATION_DENIED'), 'error');
        }

        $has_applied = ($this->_umodel->getJobApplicationStatus($this->_uid, $job_id) > 0)? 1 : 0;

        if($has_applied == 1)  {
          $msg =  JText::_('COM_JOBBOARD_ALREADYAPPLIED');
          return $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=job&id='.$job_id.'&Itemid='.$this->_itemid), $msg, 'Message');
        }

        $profdata = $this->_umodel->getMinCvProfiles($this->_uid);
		$applications_model =& $this->getModel('Apply');
        $profile_pic = JobBoardHelper::checkProfilePicStatus($this->_uid, $this->_umodel);
        $data = $applications_model->getUserApplications($this->_uid);
        $layout_style = $this->_umodel->getLayoutConfig();
        $job_title = $this->_umodel->getJobTitle($job_id);

	    $view  =& $this->getView('user', 'html');
        $view->setLayout('user');
        $view->assign('context', 'apply');
        if($qid > 0) {
           $questionnaire = $applications_model->getQuestionnaire($qid);
           $fields = json_decode($questionnaire['fields']);
           if(!is_object($fields)) {
            $qid = 0;
           } else {
             unset($questionnaire['fields']);

             jimport('joomla.utilities.date');
             $today = new JDate();
             $view->assignRef('questionnaire', $questionnaire);
             $view->assignRef('fields', $fields->fields);
             $view->assignRef('today', $today);
           }
        }

        require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_invite.php' );
        $has_invite = JobBoardInviteHelper::hasInvite($this->_uid, $job_id);

        if($p_mode == 1 || $has_invite) {
           $cpid = JRequest::getInt('cpid');
           $inv_response = array('uid'=>$this->_uid, 'jid'=>$job_id, 'cpid'=>$cpid );

           if($this->_umodel->updateResponse($inv_response)) {
             $inv_sender = JobBoardInviteHelper::getSender($inv_response);

             if(JobBoardInviteHelper::mailInvites($inv_sender) == 1)  {
                JPluginHelper::importPlugin('Jobboard');
                $dispatcher = & JDispatcher::getInstance();
                $dispatcher->trigger('onSendInvite', array( array('uid'=>&$this->_uid, 'sid'=>$inv_sender, 'jid'=>$job_id, 'cpid'=>$cpid, 'type'=>'adminvite') ) );
             }
           }
        }

        $view->assign('data', $data);
        $view->assign('jobid', $job_id);
        $view->assign('job_title', $job_title);
        $view->assign('selcat', $selcat);
        $view->assign('qid', $qid);
        $view->assign('profdata', $profdata);
        $view->assign('is_profile_pic', $profile_pic['is_profile_pic']);
        $view->assign('imgthumb', $profile_pic['urithumb']);
        $view->assign('layout_style', $layout_style);
        $view->assignRef('user_auth', $this->_user_cred);

	    $view->display();
	}

	function saveApplication()
	{
        JRequest::checkToken() or jexit( JText::_('Invalid Token') );
        $jobid = JRequest::getInt('jobid');
        $selcat = JRequest::getInt('selcat');
        $cvid =  JRequest::getInt('cvprofile');
        $qid = JRequest::getInt('qid');
        $qid = $qid > 0? $qid : 0;
        $errors = false;
		$appl_model =& $this->getModel('Apply');
        $aid = $appl_model->saveUserApplication($this->_uid, $cvid, $jobid, $qid);

        if($aid > 0 && $qid > 0) {
           require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_appl.php' );

           $fields_arr = array();
           $fields =  json_decode($appl_model->getQuestionnaireFields($qid));

           foreach($fields->fields as $field) {
             if($field->restricted == 0){
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
           $errors = !$appl_model->saveQuestionnaire($qid, $this->_uid, $aid, $fields_arr)? true : false;
        }

        $errors = !$appl_model->incrApplications($jobid)? true : false;

        if($aid < 1 && $errors <> true )  {
            $msg =  JText::_('COM_JOBBOARD_ERRORS_OCCURED').'<br />';
            $msg .=  JText::sprintf('COM_JOBBOARD_ENT_UPDERR', JText::_('COM_JOBBOARD_JOBAPPLICATION'));
            $msgtype = 'error';
        } else {
            $msg =  JText::sprintf('COM_JOBBOARD_ENT_CREATED', JText::_('COM_JOBBOARD_JOBAPPLICATION'));
            $msgtype = 'Message';
        }

      	$appl_config = JTable::getInstance('config', 'Table');
    	$appl_config->load(1);
        $user_details = $this->_umodel->getJoomlaDetails($this->_uid);
    	$this->sendEmailToUser('usernew', $user_details, $jobid, $appl_config);
        $record_application = & $this->getModel('Upload');
    	$dept = $record_application->getDept($jobid);

    	if($dept->notify_admin == 1 || $dept->notify == 1) {
    		if($dept->notify_admin == 1 && $dept->notify == 1) {
    			$recipients =  array($appl_config->from_mail, $dept->contact_email);
    		} else {
    			if($dept->notify_admin == 1) $recipients = $appl_config->from_mail;
    			if($dept->notify == 1) $recipients = $dept->contact_email;
    		}
           $user_details['cover_note'] = $this->_umodel->getCvProfileSummary($cvid, $this->_uid);
           $user_details['cover_note'] = $user_details['cover_note']->summary;
           $user_details['title'] = $this->_umodel->getCvProfileName($cvid, $this->_uid);
    	   $this->sendAdminEmail($dept->name, 'adminnew_application', $recipients, $user_details, $jobid, $appl_config);
    	}

        $app = & JFactory::getApplication();
		$layout = $app->getUserStateFromRequest('com_jobboard.list.layout', 'layout', '');
        $layout = ($layout == '')? 'list' : $layout;
        $app->redirect(JRoute::_('index.php?option=com_jobboard&view=job&id='.$jobid.'&selcat='.$selcat.'&layout='.$layout.'&Itemid='.$this->_itemid), $msg, $msgtype);
	}

	function sendEmailToUser($type, &$recipient, $id=0, &$config)
	{
		$messg_model =& $this->getModel('Message');
		$msg_id = $messg_model->getMsgID($type);
		$msg = $messg_model->getMsg($msg_id);

		$from = $config->reply_to;
		$fromname = $config->organisation;
		$to_email = $recipient['email'];
		$to_name = $recipient['name'];

		$subject = $msg->subject;
		$body = $msg->body;

		$body = str_replace('[fromname]', $fromname, $body);
		$body = str_replace('[toname]', $to_name, $body);

    	$job_model =& $this->getModel('Job');
    	$job = $job_model->getJobdata($id);
        $job->city = $config->use_location == 1? $job->city : '';
    	$subject = str_replace('[jobtitle]', $job->job_title, $subject);
    	$subject = str_replace('[location]', $job->city, $subject);
    	$body = str_replace('[jobtitle]', $job->job_title, $body);

        return JobBoardHelper::dispatchEmail($from, $fromname, $to_email, $subject, $body);
	}

	function sendAdminEmail($dept_name, $type, &$recipients, &$application, $id=0, $config, $cvattachment=null)
	{
		$messg_model =& $this->getModel('Message');
		$msg_id = $messg_model->getMsgID($type);
		$msg = $messg_model->getMsg($msg_id);

		$from = $config->reply_to;
		$fromname = $config->organisation;
		$job_model =& $this->getModel('Job');
		$job = $job_model->getJobdata($id);
        $job->city = $config->use_location == 1? $job->city : '';

		$subject = $msg->subject;
		$body = $msg->body;

		$subject = str_replace('[applstatus]', JText::_('COM_JOBBOARD_ENT_NEW'), $subject);
		$subject = str_replace('[applname]', $application['name'], $subject);
		$subject = str_replace('[applsurname]', '', $subject);
		$subject = str_replace('[fromname]', $fromname, $subject);
		$subject = str_replace('[jobtitle]', $job->job_title, $subject);
		$subject = str_replace('[appltitle]', $application['title'], $subject);
		$subject = str_replace('[location]', $job->city, $subject);
		$subject = str_replace('[jobid]', $id, $subject);
		$subject = str_replace('[department]', $dept_name, $subject);
		$subject = str_replace('[appladmin]', $application['name'], $subject);   /* applicant in this case */

		$body = str_replace('[applstatus]', JText::_('COM_JOBBOARD_ENT_NEW'), $body);
		$body = str_replace('[applname]', $application['name'], $body);
		$body = str_replace('[applsurname]', '', $body);
		$body = str_replace('[fromname]', $fromname, $body);
		$body = str_replace('[jobtitle]', $job->job_title, $body);
		$body = str_replace('[appltitle]', $application['title'], $body);
		$body = str_replace('[applcovernote]', $application['cover_note'], $body);
		$body = str_replace('[location]', $job->city, $body);
		$body = str_replace('[jobid]', $id, $body);
		$body = str_replace('[department]', $dept_name, $body);
		$body = str_replace('[appladmin]', $application['name'], $body);   /* applicant in this case */

        return JobBoardHelper::dispatchEmail($from, $fromname, $recipients, $subject, $body, $cvattachment);
	}

	function delApplication()
	{
        JRequest::checkToken() or jexit( JText::_('Invalid Token') );
        $appl_id = JRequest::getInt('aid');
        $questionnaire_id = JRequest::getInt('qid');

		$appl_model =& $this->getModel('Apply');
        if(!$appl_model->delUserApplication($appl_id, $this->_uid, $questionnaire_id) )  {
            $msg =  JText::_('COM_JOBBOARD_ERRORS_OCCURED').'<br />';
            $msg .=  JText::sprintf('COM_JOBBOARD_ENT_DELERR', JText::_('COM_JOBBOARD_JOBAPPLICATION'));
            $msgtype = 'error';
        } else {
            $msg =  JText::sprintf('COM_JOBBOARD_ENT_DELETED', JText::_('COM_JOBBOARD_JOBAPPLICATION'));
            $msgtype = 'Message';
        }

        return $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=user&task=appl&Itemid='.$this->_itemid), $msg, $msgtype);
	}

	function showCvProfiles()
	{
		$profile_model =& $this->getModel('Usercvlist');
        $profile_pic = JobBoardHelper::checkProfilePicStatus($this->_uid, $this->_umodel);
        $layout_style = $this->_umodel->getLayoutConfig();
        //if auth?
        JRequest::setVar('uid', $this->_uid);
        $profiles = $profile_model->getData();

	    $view  =& $this->getView('user', 'html');
        $view->setLayout('user');
        $view->setModel( $profile_model, true );
        $view->assign('profiles', $profiles);
        $view->assign('context', 'cvprofiles');
        $view->assign('is_profile_pic', $profile_pic['is_profile_pic']);
        $view->assign('imgthumb', $profile_pic['urithumb']);
        $view->assign('layout_style', $layout_style);
        $view->assignRef('user_auth', $this->_user_cred);

	    $view->display();
	}

	function showCvProfile()
	{
        $prof_id = JRequest::getInt('profileid');
	    $view  =& $this->getView('user', 'html');
        $user_prof_data = $this->_umodel->getProfileDataOne($this->_uid, true);
        $cv_data = $this->_umodel->getCvProfile($prof_id, $this->_uid, true, true);
        $layout_style = $this->_umodel->getLayoutConfig();
        $li_import_on = $this->_umodel->getLinkedinPerms();

        $pp_status = JobBoardHelper::checkProfilePicStatus($this->_uid, $this->_umodel, 2);

        $view->setLayout('user');
        $view->assignRef('cv_data', $cv_data);
        $view->assignRef('user_prof_data', $user_prof_data);
        $view->assign('context', 'cvprofile');
        $view->assign('is_profile_pic', $pp_status['is_profile_pic']);
        $view->assign('imgthumb', $pp_status['urithumb']);
        $view->assign('imgthumb_115', $pp_status['urithumb2']);
        $view->assign('layout_style', $layout_style);
        $view->assign('li_import_on', $li_import_on);
        $view->assignRef('user_auth', $this->_user_cred);

	    $view->display();
	}

    function delCvProfile() {

        $errors = array();
        $cv_profile_id = JRequest::getInt('profileid') ;

        $file_data = $this->_umodel->getCvProfileFiles($cv_profile_id, $this->_uid);
        $file_count = count($file_data);
        if($file_count > 0)  {
             $file_folder = $file_data[0]->filepath;
             if(!JFolder::delete($file_folder)) {
                 $errors[] = JText::_('COM_JOBBOARD_CVFILES_DEL_ERR');
             }
             foreach($file_data as $file) {
               if(!$this->_umodel->delCvFile($file->id, $cv_profile_id, $this->_uid))
                  $errors[] = JText::_('COM_JOBBOARD_CVFILES_DEL_ERR');
             }
        }

        if(!$this->_umodel->delCvProfileEdus($cv_profile_id, $this->_uid))  {
             $errors[] = 'ed';
        }

        if(!$this->_umodel->delCvProfileEmployers($cv_profile_id, $this->_uid))  {
             $errors[] = 'em';
        }

        if(!$this->_umodel->delCvProfileSkills($cv_profile_id, $this->_uid))  {
             $errors[] = 'sk';
        }

        if(!$this->_umodel->delCvProfile($this->_uid, $cv_profile_id))  {
             $errors[] = 'pf';
        }                                            //remember to include job applications

        $del_errors = count($errors);

        $msg = ($del_errors == 0)? JText::_('COM_JOBBOARD_CVPROF_DELETED') : JText::_('COM_JOBBOARD_CVPROF_DEL_ERR');
        $msgtype = ($del_errors == 0)? 'Message' : 'error';
        $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=user&task=cvprofs&Itemid='.$this->_itemid), $msg, $msgtype);
    }

	function editCvProfile()
	{
	 // echo '<pre>'.print_r(JRequest::get('post'), true).'</pre>'; die;
	    $step = JRequest::getInt('step');
        $step = $step < 1? 1 : $step;

        // Check for request forgeries
      	//if($step > 1) JRequest::checkToken() or jexit( JText::_('Invalid Token') );
        jimport('joomla.utilities.date');

	    $getdata = JRequest::getInt('getdata');
        $getdata = $getdata <> 1? 0 : 1;
	    $editmode = JRequest::getInt('emode');
        $editmode = $editmode <> 1? 0 : $editmode;

        $errors = array();
        $msg = '';
        $cv_profile_id = JRequest::getInt('profileid');
        $cv_profile_id = $cv_profile_id < 1? 0 : $cv_profile_id;
        $user_prof_data = $this->_umodel->getProfileDataOne($this->_uid, true);
        $pp_status = JobBoardHelper::checkProfilePicStatus($this->_uid, $this->_umodel);
        $linkedin_imported = $this->_umodel->liProfileExists($this->_uid, true);
        $is_authorised_linkedin = $this->_umodel->isAuthLinkedin($this->_uid);
        $layout_style = $this->_umodel->getLayoutConfig();

	    $view  =& $this->getView('user', 'html');
        $view->setLayout('user');
        $view->assign('is_authorised_linkedin', $is_authorised_linkedin);
        $view->assign('linkedin_imported', $linkedin_imported);
        $view->assign('context', 'addcv');
        $view->assign('step', $step);
        $view->assignRef('user_auth', $this->_user_cred);

        switch($step) {
          case 1:
            $config = $this->_umodel->getAddProfileStepOnecfg();
            $li_import_on = $this->_umodel->getLinkedinPerms();
            if($cv_profile_id > 0)
            {
                //editing a profile
                $data = $this->_umodel->getEditProfileStepOnedata($cv_profile_id);
                $av_date = new JDate($data['avail_date']);
                $view->assignRef('data', $data);
            } else {

               //creating a new profile
               $av_date = JFactory::getDate();
            }
            $file_count = 1;
    		$view->assignRef('av_date', $av_date);
            $view->assign('file_count', $file_count);
            $view->assign('li_import_on', $li_import_on);
            $view->assignRef('config', $config);
          break;
          case 2:
            // Process submitted data from Step1

            $post = JRequest::get('post');

            if(isset($post['profile_name']) && isset($post['filetitle']) && isset($post['file_count'])) //we are at step 1
            {
                if($post['profile_name'] == '' && $editmode == 0)
                {
                  $msg = JText::_('COM_JOBBOARD_PROFILENAMEERR');
                  return $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=user&task=addcv&step=1&Itemid='.$this->_itemid), $msg, 'error');
                }

                $post['is_private'] = !isset($post['is_private'])? 0 : ($post['is_private'] == 'yes')? 1 : 0;

                $post['avail_date'] = $post['available_yyyy'].'-'.$post['available_mm'].'-'.$post['available_dd'];
                if(isset($post['section'])) {
                    $section = isset($post['section']);
                    unset($post['section']);
                } else {
                   $section = '';
                }

                unset($post['available_yyyy'], $post['available_mm'], $post['available_dd']);
                $file_count = $post['file_count'];
                $files = array();
                $filearr = JRequest::get('files', 'array');

                // Organise files into an orderly array
                for($i=1; $i<=$file_count; $i++)
                {
                  if($filearr['file']['error'][$i] == 0)
                  {
                     $files[$i-1]['title'] = $post['filetitle'][$i];
                     $files[$i-1]['name'] = $filearr['file']['name'][$i];
                     $files[$i-1]['type'] = $filearr['file']['type'][$i];
                     $files[$i-1]['tmp_name'] = $filearr['file']['tmp_name'][$i];
                     $files[$i-1]['error'] = $filearr['file']['error'][$i];
                     $files[$i-1]['size'] = $filearr['file']['size'][$i];
                  } else
                  {
                     $post['file_count']--;
                  }
                }
                /* Does a user profile record exist for user?*/
                $user_profile_id = $this->_umodel->userProfileExists($this->_uid);

                if($user_profile_id == 0)
                { // If no, create user profile
                  $u_key = JobBoardHelper::randKey();
                  $u_secr = JobBoardHelper::randStr($u_key);
                  $this->_umodel->createMinUserProfile($this->_uid, $u_key, $u_secr);
                }

                /*save cv profile and return new/existing record id*/
                $new_cvid = $this->_umodel->saveCvProfile($post, $cv_profile_id, $this->_uid);

                if ($new_cvid == 0 && $editmode == 0)
                { //New profile data save error
                    return $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=user&task=addcv&step=1&Itemid='.$this->_itemid), 'Error saving cv/resume profile', 'error');
                }
                $cv_profile_id = ($cv_profile_id < 1)? $new_cvid : $cv_profile_id;

                //handle file uploads
                if(count($files) > 0)  {
                   $upload_success = $this->_processFileUploads($files, $this->_uid, $cv_profile_id);
                }
                if(isset($upload_success)) {
                  if(is_array($upload_success)) {
                    $errors = array_merge($errors, $upload_success);
                    $msg .=  '<br />'.JText::_('COM_JOBBOARD_ERRORS_OCCURED').'<br />';
                    foreach($errors as $errmsg)
                    {
                        $msg .=  '<br />'.$errmsg;
                    }
                    $app = & JFactory::getApplication();
                    $app->enqueueMessage($msg, 'error');
                  }
                }

                if($editmode == 1 && $getdata == 0)
                { // we are editing an existing profile - saving step 1
                   return $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=user&task=viewcv&profileid='.$cv_profile_id.'&Itemid='.$this->_itemid), JText::_('COM_JOBBOARD_CVP_CHGSAVED'), 'message');
                }
            }  /* end if creating new cv/resume */

            $section = JRequest::getVar('section');

            if($editmode == 1 && $getdata == 1){
              if($section == 'employer')
              {
                $empl_data = $this->_umodel->getCvProfileEmplHistory($cv_profile_id, $this->_uid);
                $view->assignRef('empl_data', $empl_data);
                $employer_count = count($empl_data);
              } elseif($section == 'education')
              {
                $edu_data = $this->_umodel->getCvProfileEduHistory($cv_profile_id, $this->_uid);
                $view->assignRef('edu_data', $edu_data);
                $quals_count = count($edu_data);
              }

            }
            $quals_count = isset($quals_count) ? $quals_count : 1;
            $employer_count = isset($employer_count) ? $employer_count : 1;
		    $countries = $this->_umodel->getCountries();
            $config = $this->_umodel->getAddProfileStepTwocfg();
            $ed_levels = $this->_umodel->getEdlevels();

            $view->assignRef('post', $post);
            $view->assignRef('countries', $countries);
            $view->assignRef('ed_levels', $ed_levels);

            foreach($countries as $country)
            {
               $country_options[] =	array( "id" => $country->country_id, "name" => JText::_($country->country_name) );
            }
            foreach($ed_levels as $ed_level)
            {
               $ed_level_opts[] = array( "id" => $ed_level->id, "name" => JText::_($ed_level->level) );
            }
            $months = JobBoardHelper::getMonthsList();

            $view->assign('quals_count', $quals_count);
            $view->assign('employer_count', $employer_count);
            $view->assignRef('country_options', $country_options);
            $view->assignRef('config', $config);
            $view->assignRef('ed_level_opts', $ed_level_opts);
            $view->assignRef('months', $months);
          break;
          case 3:
            $section = JRequest::getVar('section');
            require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_user.php' );
            if($editmode == 0 && $getdata == 0)
            { // we are creating a new cv/resume
                $post = JRequest::get('post');

                $edu_arr = JobBoardUserHelper::reorderEdu($post, $post['quals_count']);
                $empl_arr = JobBoardUserHelper::reorderEmpl($post, $post['employer_count']);


                foreach($edu_arr as $edu) {
                      if($result=$this->_insertdEdu($cv_profile_id, $this->_uid, $this->_umodel, $edu) <> true) {
                          $errors[] = $result;
                      }
                }

                foreach($empl_arr as $empl) {
                     if($result=$this->_insertEmployer($this->_uid, $cv_profile_id, $this->_umodel, $empl) <> true) {
                         $errors[] = $result;
                      }
                }

                if(count($errors) > 0)
                {
                   $msg =  JText::_('COM_JOBBOARD_ERRORS_OCCURED').'<br />';
                   $app = & JFactory::getApplication();
                   foreach($errors as $err) {
                     $msg .= $err.'<br />';
                   }
                   $app->enqueueMessage($msg, 'error');
                }
            }

            if($editmode == 1 && $getdata == 1 && ($section == 'employer' || $section == 'education') )
            { // we are editing an existing cv/resume
                $post = JRequest::get('post');

                 if(isset($post['edu_id'])) {
                    $data = JobBoardUserHelper::reorderEdu($post, $post['quals_count'], true);
                    $s_section = 1;
                 }

                 if(isset($post['empl_id'])) {
                    $data= JobBoardUserHelper::reorderEmpl($post, $post['employer_count'], true);
                    $s_section = 2;
                    // echo '<pre>'.print_r($data, true).'</pre>'; die;
                 }

              switch($s_section) {
                case 1 :
                      foreach($data as $row) {
                        if($row['id'] == 0){
                            unset($row['id']);
                            if($result=$this->_insertdEdu($cv_profile_id, $this->_uid, $this->_umodel, $row) <> true) {
                             $errors[] = $result;
                          }
                        } else {
                          if($result=$this->_updEdu($this->_uid, $row['id'], $cv_profile_id, $this->_umodel, $row) <> true) {
                             $errors[] = $result;
                          }
                        }
                      }
                break;
                case 2 :
                      foreach($data as $row) {
                        if($row['id'] == 0){
                            unset($row['id']);
                            if($result=$this->_insertEmployer($this->_uid, $cv_profile_id, $this->_umodel, $row) <> true) {
                             $errors[] = $result;
                          }
                        } else {
                          if($result=$this->_updEmpl($this->_uid, $row['id'], $cv_profile_id, $this->_umodel, $row) <> true) {
                             $errors[] = $result;
                          }
                        }
                      }
                break;

              }
                if(count($errors) > 0)
                {
                   $msg =  JText::_('COM_JOBBOARD_ERRORS_OCCURED').'<br />';
                   foreach($errors as $err) {
                     $msg .= $err.'<br />';
                     $msgtype = 'error';
                   }
                } else {
                   $msg = JText::_('COM_JOBBOARD_CVP_CHGSAVED');
                   $msgtype = 'Message';
                }
               return $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=user&task=viewcv&profileid='.$cv_profile_id.'&Itemid='.$this->_itemid), $msg, $msgtype);
            }

            if($editmode == 1 && $getdata == 1 && $section == 'skills')  {
               $skills = $this->_umodel->getCvProfileSkills($cv_profile_id, $this->_uid);
               $skills_count = count($skills);
               $current_skills = array();
               $dated_skills = array();
               foreach($skills as $skill) {
                 if($skill->last_use == '0000-00-00') {
                    $current_skills[] = $skill;
                 } else {
                    $dated_skills[] = $skill;
                 }
               }
               $skills = array_merge($current_skills, $dated_skills);
               $view->assignRef('skills', $skills);
            }
            if(!isset($skills))
                  $skills_count = 1;

            $skills_count = $skills_count == 0? 1 : $skills_count;

            if($editmode == 1 && $getdata == 1 && $section == 'summary')  {
               $summary = $this->_umodel->getCvProfileSummary($cv_profile_id, $this->_uid);
               $view->assignRef('summary', $summary);
            }

            $config = $this->_umodel->getAddProfileStepThreecfg();
            $view->assignRef('config', $config);
            $view->assign('skills_count', $skills_count);
          break;
          case 4:
            $section = JRequest::getVar('section');
            $section = (isset($section))? $section : '';
            require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_user.php' );
            if($editmode == 0 && $getdata == 0)
            { // we are creating a new cv/resume
                $post = JRequest::get('post');

                $skills_data = JobBoardUserHelper::reorderSkills($post, $post['skillscount']);
                //$empl_arr = JobBoardUserHelper::reorderEmpl($post, $post['employer_count']);

                if(!empty($skills_data)) {
                  foreach($skills_data as $row) {
                      if($result=$this->_insertSkill($this->_uid, $cv_profile_id, $this->_umodel, $row) <> true) {
                         $errors[] = $result;
                      }
                    }
                }

                if($result=$this->_umodel->updCvProfileSummary($cv_profile_id, $this->_uid, $post['summary']) <> true)
                    $errors[] = $result;

                if(count($errors) > 0)
                {
                   $msg =  JText::_('COM_JOBBOARD_ERRORS_OCCURED').'<br />';
                   foreach($errors as $err) {
                     $msg .= $err.'<br />';
                     $msgtype = 'error';
                   }
                } else {
                   $msg = JText::_('COM_JOBBOARD_CVP_SAVED');
                   $msgtype = 'Message';
                }

               return $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=user&task=viewcv&profileid='.$cv_profile_id.'&Itemid='.$this->_itemid), $msg, $msgtype);
            }

            if($editmode == 1 && $getdata == 1 && ($section == 'skills' || $section == 'summary') )
            { // we are editing an existing cv/resume
                $post = JRequest::get('post');

                 if(isset($post['skillname'])) {
                    $data = JobBoardUserHelper::reorderSkills($post, $post['skillscount'], true);
                    $s_section = 1;
                 }

                 if($section == 'summary') {
                    $s_section = 2;
                 }

              switch($s_section) {
                case 1 :
                      foreach($data as $row) {
                        if($row['id'] == 0){
                            unset($row['id']);
                            if($result=$this->_insertSkill($this->_uid, $cv_profile_id, $this->_umodel, $row) <> true) {
                             $errors[] = $result;
                          }
                        } else {
                          if($result=$this->_updSkill($this->_uid, $row['id'], $cv_profile_id, $this->_umodel, $row) <> true) {
                             $errors[] = $result;
                          }
                        }
                      }
                break;
                case 2 :
                        if($this->_umodel->updCvProfileSummary($cv_profile_id, $this->_uid, $post['summary']) <> true) {
                         $errors[] = JText::sprintf('COM_JOBBOARD_ENT_UPDERR', JText::_('COM_JOBBOARD_CVPROF_SUMM'));
                      }
                break;

              }
                if(count($errors) > 0)
                {
                   $msg =  JText::_('COM_JOBBOARD_ERRORS_OCCURED').'<br />';
                   foreach($errors as $err) {
                     $msg .= $err.'<br />';
                     $msgtype = 'error';
                   }
                } else {
                   $msg = JText::_('COM_JOBBOARD_CVP_CHGSAVED');
                   $msgtype = 'Message';
                }
               return $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=user&task=viewcv&profileid='.$cv_profile_id.'&Itemid='.$this->_itemid), $msg, $msgtype);
            }

            $post = JRequest::get('post');
            $view->assignRef('post', $post);
            $view->assign('section', $section);

          break;
          case 5: //procesing linkedin import
            require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_user.php' );

            $post = JRequest::get('post');

            /* Does a user profile record exist for user?*/
            $user_profile_id = $this->_umodel->userProfileExists($this->_uid);

            if($user_profile_id == 0)
            { // If no, create user profile
              $u_key = JobBoardHelper::randKey();
              $u_secr = JobBoardHelper::randStr($u_key);
              $this->_umodel->createMinUserProfile($this->_uid, $u_key, $u_secr);
            }

            $config_defaults = $this->_umodel->getProfileEditOnecfg();
            $li_data = JobBoardUserHelper::reorderLinkedIn($post, &$config_defaults);

            $li_profile_id = $this->_umodel->liProfileExists($this->_uid);
            if($li_profile_id == 0) {  //creating linkedin profile
                $today = JobBoardHelper::getToday();
                $li_profile = array('profile_name' => JText::_('COM_JOBBOARD_LI_PROFDESCR'), 'job_type' => 'COM_JOBBOARD_DB_JFULLTIME', 'file_count' => 0
                                    , 'avail_date' => (string)$today, 'is_private' => 0 );
                $curr_profile_id = $this->_umodel->saveCvProfile($li_profile, $li_profile_id, $this->_uid, 1);

                if($curr_profile_id == 0) {
                  $errors[] = JText::sprintf('COM_JOBBOARD_ENT_UPDERR', JText::_('COM_JOBBOARD_LI_PROFDESCR'));
                }  else {

                      if($this->_umodel->updCvProfileSummary($curr_profile_id, $this->_uid, $post['profile_summary']) <> true) {
                           $errors[] = JText::sprintf('COM_JOBBOARD_ENT_UPDERR', JText::_('COM_JOBBOARD_CVPROF_SUMM'));
                      }
                      foreach($li_data['edu'] as $edu)
                      {
                          if($result=$this->_insertdEdu($curr_profile_id, $this->_uid, $this->_umodel, $edu) <> true) {
                           $errors[] = $result;
                          }
                      }
                      foreach($li_data['empl'] as $empl)
                      {
                          if($result=$this->_insertEmployer($this->_uid, $curr_profile_id, $this->_umodel, $empl) <> true) {
                           $errors[] = $result;
                          }
                      }
                      foreach($li_data['skills'] as $skill)
                      {
                          if($result=$this->_insertSkill($this->_uid, $curr_profile_id, $this->_umodel, $skill) <> true) {
                           $errors[] = $result;
                          }
                      }
                }
                if($this->_umodel->updLiProfileImportStat($this->_uid, 1) <> true) {
                 $errors[] = JText::_('COM_JOBBOARD_SAVELINKEDINERR');
                }
          }  elseif($li_profile_id > 0) {  //updating linkedin profile
                  if($this->_umodel->updCvProfileSummary($li_profile_id, $this->_uid, $post['profile_summary']) <> true) {
                       $errors[] = JText::sprintf('COM_JOBBOARD_ENT_UPDERR', JText::_('COM_JOBBOARD_CVPROF_SUMM'));
                  }
                  $curr_profile_id = $li_profile_id;
                  $curr_edu_rows = $this->_umodel->getCvProfileEduHistory($li_profile_id, $this->_uid) ;
                  $curr_edu_count = count($curr_edu_rows);
                  $new_edu_count = count($li_data['edu']);

                  if($new_edu_count == $curr_edu_count && $new_edu_count > 0){  //no new records from LinkedIn. Sync current records.
                      $edu_ctr = 0;
                      foreach($curr_edu_rows as $edu)  {
                          $synced_edu = JobBoardUserHelper::liSyncEdu($li_data['edu'][$edu_ctr], $edu);
                          if($result=$this->_updEdu($this->_uid, $edu->id, $li_profile_id, $this->_umodel, $synced_edu) <> true) {
                           $errors[] = $result;
                          }
                          $edu_ctr += 1;
                        }
                  } elseif($new_edu_count > $curr_edu_count) {
                      for($e=0; $e<$curr_edu_count; $e++)  {
                          $synced_edu = JobBoardUserHelper::liSyncEdu($li_data['edu'][$e], $curr_edu_rows[$e]);
                          if($result=$this->_updEdu($this->_uid, $curr_edu_rows[$e]->id, $li_profile_id, $this->_umodel, $synced_edu) <> true) {
                           $errors[] = $result;
                          }
                          unset($li_data[$e]);
                        }
                      foreach($li_data['edu'] as $edu)
                      {
                          if($result=$this->_insertdEdu($li_profile_id, $this->_uid, $this->_umodel, $edu) <> true) {
                           $errors[] = $result;
                          }
                      }
                  } elseif($new_edu_count < $curr_edu_count) {
                      for($e=0; $e<$new_edu_count; $e++)  {
                          $synced_edu = JobBoardUserHelper::liSyncEdu($li_data['edu'][$e], $curr_edu_rows[$e]);
                          if($result=$this->_updEdu($this->_uid, $curr_edu_rows[$e]->id, $li_profile_id, $this->_umodel, $synced_edu) <> true) {
                           $errors[] = $result;
                          }
                          unset($curr_edu_rows[$e]);
                        }
                      foreach($curr_edu_rows as $edu)
                      {
                          if($result=$this->_delEdu($this->_uid, $edu->id, $li_profile_id, $this->_umodel) <> true) {
                           $errors[] = $result;
                          }
                      }

                  }
                  $curr_empl_rows = $this->_umodel->getCvProfileEmplHistory($li_profile_id, $this->_uid) ;
                  $curr_empl_count = count($curr_empl_rows);
                  $new_empl_count = count($li_data['empl']);
                  if($new_empl_count == $curr_empl_count && $new_empl_count > 0){  //no new records from LinkedIn. Sync current records.
                      $empl_ctr = 0;
                      foreach($curr_empl_rows as $empl)  {
                          $synced_empl = JobBoardUserHelper::liSyncEmpl($li_data['empl'][$empl_ctr], $empl);
                          if($result=$this->_updEmpl($this->_uid, $empl->id, $li_profile_id, $this->_umodel, $synced_empl) <> true) {
                           $errors[] = $result;
                          }
                          $empl_ctr += 1;
                        }
                  } elseif($new_empl_count > $curr_empl_count) {
                      for($e=0; $e<$curr_empl_count; $e++)  {
                          $synced_empl = JobBoardUserHelper::liSyncEmpl($li_data['empl'][$e], $curr_empl_rows[$e]);
                          if($result=$this->_updEmpl($this->_uid, $curr_empl_rows[$e]->id, $li_profile_id, $this->_umodel, $synced_empl) <> true) {
                           $errors[] = $result;
                          }
                          unset($li_data['empl'][$e]);
                        }
                      foreach($li_data['empl'] as $empl)
                      {
                          if($result=$this->_insertEmployer($this->_uid, $li_profile_id, $this->_umodel, $empl) <> true) {
                           $errors[] = $result;
                          }
                      }
                  } elseif($new_empl_count < $curr_empl_count) {
                      for($e=0; $e<$new_empl_count; $e++)  {
                          $synced_empl = JobBoardUserHelper::liSyncEmpl($li_data['empl'][$e], $curr_empl_rows[$e]);
                          if($result=$this->_updEmpl($this->_uid, $curr_empl_rows[$e]->id, $li_profile_id, $this->_umodel, $synced_empl) <> true) {
                           $errors[] = $result;
                          }
                          unset($curr_empl_rows[$e]);
                        }
                      foreach($curr_empl_rows as $empl)
                      {
                          if($result=$this->_delEmployer($this->_uid, $empl->id, $li_profile_id, $this->_umodel) <> true) {
                           $errors[] = $result;
                          }
                      }
                  }
                  $curr_skill_rows = $this->_umodel->getCvProfileSkills($li_profile_id, $this->_uid) ;
                  $curr_skill_count = count($curr_skill_rows);
                  $new_skill_count = count($li_data['skills']);
                  if($new_skill_count == $curr_skill_count && $new_skill_count > 0){  //no new records from LinkedIn. Sync current records.
                      $skill_ctr = 0;
                      foreach($curr_skill_rows as $skill)  {
                          $synced_skill = JobBoardUserHelper::liSyncSkill($li_data['skills'][$skill_ctr], $skill);
                          if($result=$this->_updSkill($this->_uid, $skill->id, $li_profile_id, $this->_umodel, $synced_skill) <> true) {
                           $errors[] = $result;
                          }
                          $skill_ctr += 1;
                        }
                  } elseif($new_skill_count > $curr_skill_count) {
                      for($e=0; $e<$curr_skill_count; $e++)  {
                          $synced_skill = JobBoardUserHelper::liSyncSkill($li_data['skills'][$e], $curr_skill_rows[$e]);
                          if($result=$this->_updSkill($this->_uid, $curr_skill_rows[$e]->id, $li_profile_id, $this->_umodel, $synced_skill) <> true) {
                           $errors[] = $result;
                          }
                          unset($li_data['skills'][$e]);
                        }
                      foreach($li_data['skills'] as $skill)
                      {
                          if($result=$this->_insertSkill($this->_uid, $li_profile_id, $this->_umodel, $skill) <> true) {
                           $errors[] = $result;
                          }
                      }
                  } elseif($new_skill_count < $curr_skill_count) {
                      for($e=0; $e<$new_skill_count; $e++)  {
                          $synced_skill = JobBoardUserHelper::liSyncSkill($li_data['skills'][$e], $curr_skill_rows[$e]);
                          if($result=$this->_updSkill($this->_uid, $curr_skill_rows[$e]->id, $li_profile_id, $this->_umodel, $synced_skill) <> true) {
                           $errors[] = $result;
                          }
                          unset($curr_skill_rows[$e]);
                        }
                      foreach($curr_skill_rows as $skill)
                      {
                          if($result=$this->_delSkill($this->_uid, $skill->id, $li_profile_id, $this->_umodel) <> true) {
                           $errors[] = $result;
                          }
                      }
                  }

                  if($this->_umodel->updLiProfileImportStat($this->_uid, 1) <> true) {
                   $errors[] = JText::_('COM_JOBBOARD_SAVELINKEDINERR');
                  }
          }

          if(count($errors) > 0)
          {
             $msg =  JText::_('COM_JOBBOARD_ERRORS_OCCURED').'<br />';
             foreach($errors as $err) {
               $msg .= $err.'<br />';
               $msgtype = 'error';
             }
             return $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=user&task=cvprofs&Itemid='.$this->_itemid), $msg, $msgtype);
          } else {
             $msg = JText::_('COM_JOBBOARD_CVP_CHGSAVED');
             $msgtype = 'Message';
             return $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=user&task=viewcv&profileid='.$curr_profile_id.'&Itemid='.$this->_itemid), $msg, $msgtype);
          }

          break;
          default:
          ;break;
        }

        $section = isset($section)? $section: '';
        $view->assign('editmode', $editmode);
        $view->assign('section', $section);
        $view->assign('getdata', $getdata);
        $view->assign('profileid', $cv_profile_id);
        $view->assign('is_profile_pic', $pp_status['is_profile_pic']);
        $view->assignRef('user_prof_data', $user_prof_data);
        $view->assign('imgthumb', $pp_status['urithumb']);
        $view->assign('layout_style', $layout_style);
	    $view->display();
	}

    function cloneCvProfile() {

       JRequest::checkToken('get') or jexit (JText::_( 'Invalid Token' ));

       $pid = JRequest::getInt('profileid');

       $errors = array();
       $curr_cv = $this->_umodel->getCvProfile($pid, $this->_uid, true, false);


       $new_profilename =  $curr_cv->profile_name.' - '.JText::_('COM_JOBBOARD_COPY');
       $new_cv_meta =  array('profile_name' => $new_profilename, 'user_id' => $this->_uid, 'job_type' => 'COM_JOBBOARD_DB_JFULLTIME'
                        , 'file_count' => 0, 'avail_date' => $curr_cv->avail_date, 'is_private' => 0);

       /*save cv profile and return new record id*/
       $new_cvid = $this->_umodel->saveCvProfile($new_cv_meta, 0, $this->_uid);
       if($new_cvid > 0) {
           if($this->_umodel->updCvProfileSummary($new_cvid, $this->_uid, $curr_cv->summary) <> true) {
                       $errors[] = JText::sprintf('COM_JOBBOARD_ENT_UPDERR', JText::_('COM_JOBBOARD_CVPROF_SUMM'));
           }
          $ed_count = count($curr_cv->education);
          if($ed_count > 0)  {
              foreach($curr_cv->education as $edu)
              {
                  $ed_assoc = JArrayHelper::fromObject($edu);
                  unset($ed_assoc['id']);
                  if($result=$this->_insertdEdu($new_cvid, $this->_uid, $this->_umodel, $ed_assoc) <> true) {
                   $errors[] = $result;
                  }
              }
          }

          $empl_count = count($curr_cv->employers);
          if($empl_count > 0)  {
              foreach($curr_cv->employers as $empl)
              {
                  $empl_assoc = JArrayHelper::fromObject($empl);
                  unset($empl_assoc['id']);
                  if($result=$this->_insertEmployer($this->_uid, $new_cvid, $this->_umodel, $empl_assoc) <> true) {
                   $errors[] = $result;
                  }
              }
          }

          $skill_count = count($curr_cv->skills);
          if($skill_count > 0)  {
              foreach($curr_cv->skills as $skill)
              {
                  $skill_assoc = JArrayHelper::fromObject($skill);
                  unset($skill_assoc['id']);
                  if($result=$this->_insertSkill($this->_uid, $new_cvid, $this->_umodel, $skill_assoc) <> true) {
                   $errors[] = $result;
                  }
              }
          }

          $file_count = count($curr_cv->files);
          if($file_count > 0)  {
              jimport('joomla.filesystem.file');

              $component_folder = JPATH_BASE.DS.'images'.DS.'com_jobboard';
              if(!JFolder::exists($component_folder))
              {
                  $component_folder_created = JFolder::create($component_folder);
                  if($component_folder_created == false)
                  {
                    $errors[] = JText::_('COM_JOBBOARD_USERFOLDER_CREATE_ERR');
                  }
              }
              $sec_file = $component_folder.DS.'index.html';
              if(!JFile::exists($sec_file))
              {
                  $_html = '<!DOCTYPE html><title></title>';
                  JFile::write($sec_file, $_html);
              }

              $users_folder = $component_folder.DS.'users';
              if(!JFolder::exists($users_folder))
              {
                  $users_folder_created = JFolder::create($users_folder);
                  if($users_folder_created == false)
                  {
                    $errors[] = JText::_('COM_JOBBOARD_USERFOLDER_CREATE_ERR');
                  }
              }
              $sec_file = $users_folder.DS.'index.html';
              if(!JFile::exists($sec_file))
              {
                  $_html = '<!DOCTYPE html><title></title>';
                  JFile::write($sec_file, $_html);
              }

              $user_folder = $users_folder.DS.$this->_uid;
              if(!JFolder::exists($user_folder))
              {
                  $user_folder_created = JFolder::create($user_folder);
                  if($user_folder_created == false)
                  {
                    $errors[] = JText::_('COM_JOBBOARD_USERFOLDER_CREATE_ERR');
                  }
              }
              $sec_file = $user_folder.DS.'index.html';
              if(!JFile::exists($sec_file))
              {
                  $_html = '<!DOCTYPE html><title></title>';
                  JFile::write($sec_file, $_html);
              }

              $user_files_folder = $user_folder.DS.'files';
              if(!JFolder::exists($user_files_folder))
              {
                  $user_files_folder_created = JFolder::create($user_files_folder);
                  if($user_files_folder_created == false)
                  {
                    $errors[] = JText::_('COM_JOBBOARD_USERFOLDER_CREATE_ERR');
                  }
              }
              $sec_file = $user_files_folder.DS.'index.html';
              if(!JFile::exists($sec_file))
              {
                  $_html = '<!DOCTYPE html><title></title>';
                  JFile::write($sec_file, $_html);
              }

              $new_files_folder = $user_files_folder.DS.$new_cvid;

              if(!JFolder::exists($new_files_folder))
              {
                  $new_files_folder_created = JFolder::create($new_files_folder);
                  if($new_files_folder_created == false)
                  {
                    $errors[] = JText::_('COM_JOBBOARD_USERFOLDER_CREATE_ERR');
                  }
              }

              $sec_file = $new_files_folder.DS.'index.html';
              if(!JFile::exists($sec_file))
              {
                  $_html = '<!DOCTYPE html><title></title>';
                  JFile::write($sec_file, $_html);
              }

              foreach ($curr_cv->files as $file) {
                 $src = $file->filepath.DS.$file->filename;
                 $dest = $new_files_folder.DS.$file->filename;

                 $file_assoc = JArrayHelper::fromObject($file);
                 unset($file_assoc['id']);

                 if ( !JFile::copy($src, $dest) || !$this->_umodel->saveCvFile($this->_uid, $new_cvid, $file_assoc, $new_files_folder, true))
                 {
                    $errors[]  = JText::_('COM_JOBBOARD_FILE_COPYERR').' - '.$file->filetitle.' ('.$file->filename.')';
                 }
              }
          }
       }

      if(count($errors) > 0)
      {
         $msg =  JText::_('COM_JOBBOARD_ERRORS_OCCURED').'<br />';
         foreach($errors as $err) {
           $msg .= $err.'<br />';
           $msgtype = 'error';
         }
      } else {
         $msg = JText::_('COM_JOBBOARD_CVP_CLONED');
         $msgtype = 'Message';
      }
      return $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=user&task=cvprofs&Itemid='.$this->_itemid), $msg, $msgtype);
    }

    function deleteEmployer() {
       JRequest::checkToken('get') or jexit (JText::_( 'Invalid Token' ));
       $id = JRequest::getInt('empid');
       $pid = JRequest::getInt('profileid');

       $result = $this->_delEmployer($this->_uid, $id, $pid, $this->_umodel);

       if($result <> true)
       {
          $msg = $result;
          $msgtype = 'error';
       }  else
       {
          $msg = JText::sprintf('COM_JOBBOARD_ENT_DELETED', JText::_('COM_JOBBOARD_EMPLOYER'));
          $msgtype = 'Message';
       }

       $app = & JFactory::getApplication();
       $app->enqueueMessage($msg, $msgtype);

       JRequest::setVar('step', 2);
       JRequest::setVar('emode', 1);
       JRequest::setVar('getdata', 1);
       JRequest::setVar('section', 'employer');
       JRequest::setVar('profileid', $pid);

       $this->editCvProfile();
    }

    function deleteEducation() {
       JRequest::checkToken('get') or jexit (JText::_('Invalid Token'));
       $id = JRequest::getInt('edid');
       $pid = JRequest::getInt('profileid');
       $result = $this->_delEdu($this->_uid, $id, $pid, $this->_umodel);

       if($result <> true)
       {
          $msg = $result;
          $msgtype = 'error';
       }  else
       {
          $msg = JText::sprintf('COM_JOBBOARD_ENT_DELETED', JText::_('EDUCATION'));
          $msgtype = 'Message';
       }

       $app = & JFactory::getApplication();
       $app->enqueueMessage($msg, $msgtype);

       JRequest::setVar('step', 2);
       JRequest::setVar('emode', 1);
       JRequest::setVar('getdata', 1);
       JRequest::setVar('section', 'education');
       JRequest::setVar('profileid', $pid);
       $this->editCvProfile();

    }

    function deleteSkill() {
       JRequest::checkToken('get') or jexit (JText::_( 'Invalid Token' ));
       $id = JRequest::getInt('skillid');
       $pid = JRequest::getInt('profileid');

       $result = $this->_delSkill($this->_uid, $id, $pid, $this->_umodel);

       if($result <> true)
       {
          $msg = $result;
          $msgtype = 'error';
       }  else
       {
          $msg = JText::sprintf('COM_JOBBOARD_ENT_DELETED', JText::_('COM_JOBBOARD_TXTSKILL'));
          $msgtype = 'Message';
       }

       $app = & JFactory::getApplication();
       $app->enqueueMessage($msg, $msgtype);

       JRequest::setVar('step', 3);
       JRequest::setVar('emode', 1);
       JRequest::setVar('getdata', 1);
       JRequest::setVar('section', 'skills');
       JRequest::setVar('profileid', $pid);

       $this->editCvProfile();
    }

    private function _insertEmployer($uid, $pid, &$model, $data) {

        if($data['company_name'] <> '' && $data['job_title'] <> '' && $data['location'] <> '') {
          if(!$model->insertCvProfileEmployer($pid, $uid, $data)) {
                 return JText::sprintf('COM_JOBBOARD_ENT_UPDERR', JText::_('COM_JOBBOARD_EMPLOYER'));
          }
        }
       return true;
    }

    private function _updEmpl($uid, $id, $pid, &$model, $data) {

        if(!$model->updCvProfileEmployer($id, $pid, $uid, $data)) {
               return JText::sprintf('COM_JOBBOARD_ENT_UPDERR', JText::_('COM_JOBBOARD_EMPLOYER'));
        }
       return true;
    }

    private function _delEmployer($uid, $id, $pid, &$model) {

        if(!$model->delCvProfileEmployer($id, $pid, $uid)) {
               return JText::sprintf('COM_JOBBOARD_ENT_DELERR', JText::_('COM_JOBBOARD_EMPLOYER'));
        }
       return true;
    }

    private function _insertdEdu($pid, $uid, &$model, $data) {
        if($data['qual_name'] <> '' && $data['school_name'] <> '' && $data['location'] <> '') {
          if(!$model->insertCvProfileEdu($pid, $uid, $data)) {
                 return JText::sprintf('COM_JOBBOARD_ENT_UPDERR', JText::_('EDUCATION'));
          }
        }
       return true;
    }

    private function _updEdu($uid, $id, $pid, &$model, $data) {

        if(!$model->updCvProfileEdu($id, $pid, $uid, $data)) {
               return JText::sprintf('COM_JOBBOARD_ENT_UPDERR', JText::_('EDUCATION'));
        }
       return true;
    }

    private function _delEdu($uid, $id, $pid, &$model) {

        if(!$model->delCvProfileEdu($id, $pid, $uid)) {
               return JText::sprintf('COM_JOBBOARD_ENT_DELERR', JText::_('EDUCATION'));
        }
       return true;
    }

    private function _insertSkill($uid, $pid, &$model, $data) {

        if(!$model->insertCvProfileSkill($pid, $uid, $data)) {
               return JText::sprintf('COM_JOBBOARD_ENT_UPDERR', JText::_('COM_JOBBOARD_TXTSKILL'));
        }
       return true;
    }

    private function _updSkill($uid, $id, $pid, &$model, $data) {

        if(!$model->updCvProfileSkill($id, $pid, $uid, $data)) {
               return JText::sprintf('COM_JOBBOARD_ENT_UPDERR', JText::_('COM_JOBBOARD_TXTSKILL'));
        }
       return true;
    }

    private function _delSkill($uid, $id, $pid, &$model) {

        if(!$model->delCvProfileSkill($id, $pid, $uid)) {
               return JText::sprintf('COM_JOBBOARD_ENT_DELERR', JText::_('COM_JOBBOARD_TXTSKILL'));
        }
       return true;
    }

    private function _processFileUploads($files, $uid, $pid) {

        //Import filesystem libraries.
        jimport('joomla.filesystem.file');
        $errors = array();
        $config = $this->_umodel->getFileUploadCfg();
        $max = ($config['max_filesize'] * 1024) * 1024;

        require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_file.php' );
        $filetypes = JobBoardFileHelper::getValidFtypes();

        $component_folder = JPATH_BASE.DS.'images'.DS.'com_jobboard';
        if(!JFolder::exists($component_folder))
        {
            $component_folder_created = JFolder::create($component_folder);
            if($component_folder_created == false)
            {
              $errors[] = JText::_('COM_JOBBOARD_USERFOLDER_CREATE_ERR');
            }
        }
        $sec_file = $component_folder.DS.'index.html';
        if(!JFile::exists($sec_file))
        {
            $_html = '<!DOCTYPE html><title></title>';
            JFile::write($sec_file, $_html);
        }

        $users_folder = $component_folder.DS.'users';
        if(!JFolder::exists($users_folder))
        {
            $users_folder_created = JFolder::create($users_folder);
            if($users_folder_created == false)
            {
              $errors[] = JText::_('COM_JOBBOARD_USERFOLDER_CREATE_ERR');
            }
        }
        $sec_file = $users_folder.DS.'index.html';
        if(!JFile::exists($sec_file))
        {
            $_html = '<!DOCTYPE html><title></title>';
            JFile::write($sec_file, $_html);
        }


        foreach($files as $file)
        {
            $file_name_title = ($file["title"] == '')? $file["name"].'('.JText::_('COM_JOBBOARD_FILE_NOTITLE').')' : $file["name"].'('.$file["title"].')';
            if($file['size'] > $max) {
                $errors[] = JText::sprintf('COM_JOBBOARD_MAX_FILESIZE_ERR', $file_name_title, $config["max_filesize"]);
            }
            elseif(!in_array($file['type'], $filetypes)){
                $errors[] = JText::sprintf('COM_JOBBOARD_FILENOTPERM', $file_name_title, $file["type"]);
            } else
            {
                //Clean up filename
                $filename = JFile::makeSafe($file['name']);
                $filename = str_replace(" ", "_", $filename);
                $file['name'] = $filename;

                //Set up the source and destination of the file
                $src = $file['tmp_name'];
                $user_folder = $users_folder.DS.$uid;
                if(!JFolder::exists($user_folder))
                {
                    $user_folder_created = JFolder::create($user_folder);
                    if($user_folder_created == false)
                    {
                      $errors[] = JText::_('COM_JOBBOARD_USERFOLDER_CREATE_ERR');
                    }
                }
                $sec_file = $user_folder.DS.'index.html';
                if(!JFile::exists($sec_file))
                {
                    $_html = '<!DOCTYPE html><title></title>';
                    JFile::write($sec_file, $_html);
                }

                $user_files_folder = $user_folder.DS.'files';
                if(!JFolder::exists($user_files_folder))
                {
                    $user_files_folder_created = JFolder::create($user_files_folder);
                    if($user_files_folder_created == false)
                    {
                      $errors[] = JText::_('COM_JOBBOARD_USERFOLDER_CREATE_ERR');
                    }
                }
                $sec_file = $user_files_folder.DS.'index.html';
                if(!JFile::exists($sec_file))
                {
                    $_html = '<!DOCTYPE html><title></title>';
                    JFile::write($sec_file, $_html);
                }

                $new_files_folder = $user_files_folder.DS.$pid;

                if(!JFolder::exists($new_files_folder))
                {
                    $new_files_folder_created = JFolder::create($new_files_folder);
                    if($new_files_folder_created == false)
                    {
                      $errors[] = JText::_('COM_JOBBOARD_USERFOLDER_CREATE_ERR');
                    }
                }

                $sec_file = $new_files_folder.DS.'index.html';
                if(!JFile::exists($sec_file))
                {
                    $_html = '<!DOCTYPE html><title></title>';
                    JFile::write($sec_file, $_html);
                }

                $dest = $new_files_folder.DS.$file['name'];
                if ( !JFile::upload($src, $dest) ) {
        			$errors[]  = JText::_('COM_JOBBOARD_FILE_UPLDERR').' - '.$file_name_title;
                }
                else {
                      unset($file['tmp_name'], $file['error']);

                      //convert to generic MS-Office filetypes
                      if($file['type'] == $filetypes[6] || $file['type'] == $filetypes[7]) $file['type'] = "application/msword";
                      if($file['type'] == $filetypes[9] || $file['type'] == $filetypes[10]) $file['type'] = "application/msexcel";

                      //save to db
                      if($this->_umodel->saveCvFile($uid, $pid, $file, $new_files_folder) == false)
                      {
                       	    $errors[]  = JText::_('COM_JOBBOARD_FILE_UPLDERR').' - '.$file_name_title;
                      }
                }
            }
        }

		if(count($errors) <= 0)
        {
			return true;
		} else {
			return $errors;
		}
    }

    function downloadFile() {
	   // Check for request forgeries

	   JRequest::checkToken('get') or jexit( JText::_('Invalid Token') );
       $file_id = JRequest::getInt('fileid');
       $cvprof_id = JRequest::getInt('profileid');

       $file = $this->_umodel->getCvFile($file_id, $cvprof_id);
       $filename = $file->filepath.DS.$file->filename;
       if(!JFile::exists($filename))
       {
              $msg = JText::_('COM_JOBBOARD_FILE_NOTFOUND');
              return $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=user&task=viewcv&profileid='.$cvprof_id.'&Itemid='.$this->_itemid), $msg, 'error');

       }

       $view  =& $this->getView('user', 'file');
       $view->assign('file', $filename);
       $view->assign('filetype', $file->filetype);

	   $view->display();
    }

    function deleteCvFile() {
	   // Check for request forgeries
	   JRequest::checkToken() or jexit( JText::_('Invalid Token') );

       $file_id = JRequest::getInt('fileid');
       $cvprof_id = JRequest::getInt('profileid');

       $file = $this->_umodel->getCvFile($file_id, $cvprof_id, $this->_uid);
       $filename = $file->filepath.DS.$file->filename;

       //dont throw an error if the file doesn't exist in folder. proceed to delete file record from db
       if(JFile::exists($filename))
       {
         if(!$this->_umodel->delCvFile($file_id, $cvprof_id, $this->_uid) || !JFile::delete($filename))
         {
              $msg = JText::_('COM_JOBBOARD_IMGFDEL_ERR');
              return $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=user&task=viewcv&profileid='.$cvprof_id.'&Itemid='.$this->_itemid), $msg, 'error');
         }
       }

       //file delete successful
       $file->filetitle = ($file->filetitle == '')? JText::_('COM_JOBBOARD_FILE_NOTITLE') : $file->filetitle;
       $filestr_concat = $file->filename.' ('.$file->filetitle.')';
       $msg = JText::sprintf('COM_JOBBOARD_CVP_FDELETED', $filestr_concat);

       $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=user&task=viewcv&profileid='.$cvprof_id.'&Itemid='.$this->_itemid), $msg, 'Message');
    }

	function showUserProfile()
	{
	    $tab = JRequest::getInt('tab');
        $tab = $tab < 1? 1 : $tab;
	    $view  =& $this->getView('user', 'html');
        $layout_style = $this->_umodel->getLayoutConfig();
        $pp_status = JobBoardHelper::checkProfilePicStatus($this->_uid, $this->_umodel, 3);

        switch($tab) {
          case 1:
		    $countries = $this->_umodel->getCountries();
            $config = $this->_umodel->getProfileEditOnecfg();
            $data = $this->_umodel->getProfileDataOne($this->_uid);
            $view->assignRef('data', $data);
            $view->assignRef('countries', $countries);
            $view->assignRef('config', $config);
            $view->assign('targview', 'user');
            $view->assign('task', 'save');
          break;
          case 2:
            $view->assign('imgpath', $pp_status['uripath']);
            $view->assign('targview', 'image');
            $task = ($pp_status['is_profile_pic'] == 1)? 'saveimg' : 'upload';
            $view->assign('task', $task);
          break;
          case 3:

            $view->assign('targview', 'user');
            $view->assign('task', 'save');
          break;
          case 4:
            $view->assign('targview', 'user');
            $view->assign('task', 'save');
          break;
          case 5:
            $data = $this->_umodel->getProfileSettings($this->_uid);

            $view->assignRef('data', $data);
            $view->assign('targview', 'user');
            $view->assign('is_admin', $this->_is_admin);
            $view->assign('task', 'save');
          break;
          default:
          ;break;
        }
        $view->setLayout('user');
        $view->assign('context', 'profile');
        $view->assign('currtab', $tab);
        $view->assign('uid', $this->_uid);
        $view->assign('layout_style', $layout_style);
        $view->assign('imgthumb', $pp_status['urithumb']);
        $view->assign('is_profile_pic', $pp_status['is_profile_pic']);
        $view->assignRef('user_auth', $this->_user_cred);

	    $view->display();
	}

    function saveProfData() {

	   // Check for request forgeries
	   JRequest::checkToken() or jexit( JText::_('Invalid Token') );

       $post = JRequest::get('post');
       // echo '<pre>'.print_r($post, true).'</pre>'; die;
       $user_profile_id = $this->_umodel->userProfileExists($this->_uid);
       switch($post['currtab']) {
         case 1 : if(!$this->_umodel->saveProfileDataOne($post, $this->_uid, $user_profile_id)) :
        			$msg = JText::sprintf('COM_JOBBOARD_ENT_UPDERR', JText::_('COM_JOBBOARD_PROFILE'));
                    $msgtype = 'error';
        		else :
        			$msg = JText::sprintf('COM_JOBBOARD_ENT_UPDATED', JText::_('COM_JOBBOARD_PROFILE'));
                    $msgtype = 'Message';
        		endif;
         break;
         case 3 :
            echo '<pre>'.print_r($post, true).'</pre>'; die;
            // $this->_umodel->saveProfileDataThree($post, $this->_uid, $user_profile_id);
         break;
         case 4 :
            echo '<pre>'.print_r($post, true).'</pre>'; die;
            // $this->_umodel->saveProfileDataFour($post, $this->_uid, $user_profile_id);
         break;
         case 5 :
                $post = JRequest::get('post');
                $post['id'] = $this->_umodel->getUserRowId($this->_uid);
                $post['notify_on_appl_accept'] = $post['notify_on_appl_accept'] == 'yes'? 1 : 0;
                $post['notify_on_appl_reject'] = $post['notify_on_appl_reject'] == 'yes'? 1 : 0;
                $post['email_invites'] = $post['email_invites'] == 'yes'? 1 : 0;

                $user_rec = &JTable::getInstance('User', 'Table');
        		if (!$user_rec->save($post)) :
        			$msg = $user_rec->getError();
                    $msgtype = 'error';
        		else :
        			$msg = JText::sprintf('COM_JOBBOARD_ENT_UPDATED', JText::_('COM_JOBBOARD_SETTINGS'));
                    $msgtype = 'Message';
        		endif;
         break;
       }

       $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=user&task=prof&tab='.$post['currtab'].'&Itemid='.$this->_itemid), $msg, $msgtype);
    }

    function importLinkedInProfile(){
      if(!JobBoardHelper::getSite('linkedin.com')){
         $app = & JFactory::getApplication();
         $msg =  JText::_('COM_JOBBOARD_NO_NETWORK');
         $app->enqueueMessage('Linkedin: '.$msg, 'error');

         JRequest::setVar('step', 1);
         JRequest::setVar('emode', 0);
         JRequest::setVar('getdata', 0);
         JRequest::setVar('profileid', 0);

         return $this->editCvProfile();
      }  else {
        $step = JRequest::getInt('step');
        if($step == 5) {
           //$post = JRequest::get('post');
        } else {
          $step = 4;
          $li_api = $this->_umodel->getLinkedinKey();
          if($li_api['allow_linkedin_imports'] <> 1 || empty($li_api['linkedin_key']) || empty($li_api['linkedin_secret'])){
            $step = 1;
            return $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=user&task=addcv&Itemid='.$this->_itemid), JText::_('COM_JOBBOARD_IMPORTLINKEDIN_DISABLED'), 'error');
          }
          $linkedin_imported = 1;
          $view  =& $this->getView('user', 'html');

          //JPluginHelper::importPlugin('Jobboard');
          $dispatcher = & JDispatcher::getInstance();
          $li_api['uid'] = $this->_uid;
          $li_api['type'] = 'initiate';
          $linkedin_arr = $dispatcher->trigger('onCallLinkedInApi', array($li_api));

          $user_prof_data = $this->_umodel->getProfileDataOne($this->_uid, true);
          $pp_status = JobBoardHelper::checkProfilePicStatus($this->_uid, $this->_umodel);
          $layout_style = $this->_umodel->getLayoutConfig();

          $view->setLayout('user');
          $view->assign('context', 'addcv');
          $view->assign('linkedin_imported', $linkedin_imported);
          $view->assignRef('user_prof_data', $user_prof_data);
          $view->assign('step', $step);
          $view->assignRef('linkedin_arr', $linkedin_arr[0]);
          $view->assign('is_profile_pic', $pp_status['is_profile_pic']);
          $view->assign('imgthumb', $pp_status['urithumb']);
          $view->assign('layout_style', $layout_style);
          $view->assignRef('user_auth', $this->_user_cred);

          $view->display();

        }
      }
    }

    function revokeLinkedInToken(){

        $step = 4;
        $linkedin_imported = 0;
        $view  =& $this->getView('user', 'html');
        $layout_style = $this->_umodel->getLayoutConfig();
        $pp_status = JobBoardHelper::checkProfilePicStatus($this->_uid, $this->_umodel);

        JPluginHelper::importPlugin('Jobboard');
        $dispatcher = & JDispatcher::getInstance();
        $linkedin_arr = $dispatcher->trigger('onCallLinkedInApi', array(array('uid'=>&$this->_uid, 'type'=>'revoke')));

        $view->setLayout('user');
        $view->assign('context', 'addcv');
        $view->assign('linkedin_imported', $linkedin_imported);
        $view->assign('step', $step);
        $view->assignRef('linkedin_arr', $linkedin_arr);
        $view->assign('is_profile_pic', $pp_status['is_profile_pic']);
        $view->assign('imgthumb', $pp_status['urithumb']);
        $view->assign('layout_style', $layout_style);
        $view->assignRef('user_auth', $this->_user_cred);

    	$view->display();
    }

    function renderRaw($data) {
       $document =& JFactory::getDocument();
       $doc = &JDocument::getInstance('raw');
       $document = $doc;

	   $view  =& $this->getView('user', 'html');
       $view->setLayout('user');
       $view->assign('context', 'profile');
       $view->assignRef('data', $data);

	   $view->display();
    }
}

$controller = new JobboardControllerUser();
$controller->execute($task);
$controller->redirect();

?>