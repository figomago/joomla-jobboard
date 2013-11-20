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

class JobboardControllerAdmin extends JController
{
     var $_uid = null;
     var $_umodel = null;
     var $_auth_model = null;
     var $_glb_cfg = null;
     var $_user_cred = null;
     var $_itemid = null;

    /**
	 * constructor
	 */
	function __construct()
	{
        $user = & JFactory::getUser();
        //kick out if user has no access
        if($user->get('guest')) {
            $return = JRequest::getString('redirect', '');
            $app = &JFactory::getApplication();
            $msg =  JText::_('COM_JOBBOARD_ENTNOAUTH');
            $msgtype = 'error';
            if(!empty($return))
                return $app->redirect(JRoute::_('index.php?option=com_jobboard&view=member&iview=login&redirect='.$return), $msg, $msgtype);
            else
                return $app->redirect(JRoute::_('index.php?option=com_jobboard&view=member&iview=login'), $msg, $msgtype);
        }

		parent::__construct();

        $uid = $user->id;
        $this->_setUid($uid);

		$user_model =& $this->getModel('Admin');
		$auth_model =& $this->getModel('Member');
        $config = $user_model->getGlobalConfig();
        $itemid = JRequest::getInt('Itemid');

        $this->_setUmodel(&$user_model);
        $this->_setAuthmodel(&$auth_model);
        $this->_setGconfig(&$config);
        $this->_setItemid($itemid);

        $user_enabled = $auth_model->isEnabled($uid);
        //kick out if user disabled
        if(!$user->get('guest') && !$user_enabled) {
            $msg =  JText::_('COM_JOBBOARD_ENTNOAUTH');
            $msgtype = 'error';
            return $this->setRedirect(JRoute::_('index.php?option=com_jobboard'), $msg, $msgtype);
        }

        $ucred = $auth_model->getUserCred($uid);

        //kick out if user has no admin functions
        if($ucred['user_status'] == 0 || ($ucred['post_jobs'] == 0 && $ucred['post_jobs'] == 0 && $ucred['manage_jobs'] == 0 && $ucred['manage_applicants'] == 0 && $ucred['search_private_cvs'] == 0 && $ucred['create_questionnaires'] == 0 && $ucred['manage_questionnaires'] == 0 && $ucred['manage_departments'] == 0)) {
            $msg =  JText::_('COM_JOBBOARD_ENTNOAUTH');
            $msgtype = 'error';
            return $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=user'), $msg, $msgtype);
        }

        $show_modeswitch = $auth_model->getModeswitchConfig($uid);
        $this->_setUserCreds(&$ucred, $show_modeswitch);
        unset($uid, $config, $ucred, $user_model, $show_modeswitch);

		$this->registerTask('appl', 'showJobApplications');
		$this->registerTask('qlist', 'showQuestionnaires');
		$this->registerTask('viewq', 'viewQuestionnaire');
		$this->registerTask('delq', 'delQuestionnaire');
		$this->registerTask('delqrow', 'delQnaireRow');
		$this->registerTask('edq', 'editQuestionnaire');
		$this->registerTask('saveq', 'saveQuestionnaire');
        $this->registerTask('invites', 'showInvites');
		$this->registerTask('jobs', 'showMyJobs');
		$this->registerTask('jobstatus', 'toggleJobStatus');
		$this->registerTask('job', 'showJob');
		$this->registerTask('invite', 'inviteUser');
		$this->registerTask('invresend', 'resendInvite');
		$this->registerTask('getcvfile', 'downloadCV');
		$this->registerTask('getucvfile', 'downloadUCV');
		$this->registerTask('edjob', 'editJob');
		$this->registerTask('savejob', 'saveJob');
		$this->registerTask('clonejob', 'cloneJob');
		$this->registerTask('deljob', 'deleteJob');
		$this->registerTask('edappl', 'editApplication');
		$this->registerTask('saveappl', 'saveApplication');
		$this->registerTask('delappl', 'delApplication');
		$this->registerTask('addcv', 'editCvProfile');
		$this->registerTask('cvsrch', 'searchCvProfiles');
		$this->registerTask('viewcv', 'showCvProfile');
		$this->registerTask('delcv', 'delCvProfile');
		$this->registerTask('delcvfile', 'deleteCvFile');
		$this->registerTask('settings', 'userSettings');
		$this->registerTask('getlinkedinprof', 'importLinkedInProfile');
		$this->registerTask('tokenrevoke', 'revokeLinkedInToken');
		$this->registerTask('save', 'saveProfData');
		$this->registerTask('deledu', 'deleteEducation');
		$this->registerTask('delemp', 'deleteEmployer');
		$this->registerTask('delskill', 'deleteSkill');

	}

    private function _setUid($uid){
      if($uid == 0)  {
          return $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=member&Itemid='.$itemid));
        } else
            $this->_uid = $uid;
    }

    private function _setUmodel(&$umodel){
        $this->_umodel = $umodel;
    }

    private function _setAuthmodel(&$auth_model){
        $this->_auth_model = $auth_model;
    }

    private function _setGconfig(&$gconfig){
        $this->_glb_cfg = $gconfig;
    }

    private function _setUserCreds(&$ucred, $show_modeswitch){
        $ucred['show_modeswitch']  = $show_modeswitch;
        $this->_user_cred = $ucred;
    }

    private function _setItemid($itemid){
        $this->_itemid = $itemid;
    }

	function display()
	{
        $app = & JFactory::getApplication();
        $current_dash = $app->getUserStateFromRequest('com_jobboard.curr_dash', 'curr_dash', 1);
        if($current_dash == 0) $app->setUserState('com_jobboard.curr_dash', 1);

		$user_model =& $this->getModel('User');         
        $profile_pic = JobBoardHelper::checkProfilePicStatus($this->_uid, $user_model);
        $layout_style = $user_model->getLayoutConfig();
        $active_jobs = $this->_umodel->getEmplJobs($this->_uid);
        $inactive_jobs = $this->_umodel->getEmplJobs($this->_uid, 0);
        $featured_jobs = $this->_umodel->getEmplJobs($this->_uid, 1, true);
        $all_invites = $this->_umodel->getEmplInvites($this->_uid);
        $responded_invites = $this->_umodel->getEmplInvites($this->_uid, true);
        $questionnaires = $this->_umodel->getEmplQuestionnaires($this->_uid);
        $site_appls = $this->_umodel->getEmplAppls($this->_uid);
        $user_appls = $this->_umodel->getEmplAppls($this->_uid, true);

	    $view  =& $this->getView('admin', 'html');
        $view->setLayout('admin');
        $view->assign('context', 'admin');
        $view->assign('layout_style', $layout_style);
        $view->assign('is_profile_pic', $profile_pic['is_profile_pic']);
        $view->assign('imgthumb', $profile_pic['urithumb']);
        $view->assignRef('user_auth', $this->_user_cred);
        $view->assign('active_jobs', $active_jobs);
        $view->assign('inactive_jobs', $inactive_jobs);
        $view->assign('featured_jobs', $featured_jobs);
        $view->assign('all_invites', $all_invites);
        $view->assign('questionnaires', $questionnaires);
        $view->assign('responded_invites', $responded_invites);
        $view->assign('site_appls', $site_appls);
        $view->assign('user_appls', $user_appls);
        $view->assign('itemid', $this->_itemid);

	    $view->display();
	}


    function toggleJobStatus() {
	    JRequest::checkToken('get') or jexit( JText::_('Invalid Token') );
        $jid = JRequest::getInt('jid');
        $new_status = JRequest::getInt('status') == 1? 0 : 1;

        $layout_style = $this->_umodel->getLayoutConfig();
        if($this->_user_cred['manage_jobs'] == 1) {
           if(!$this->_umodel->toggleJobStatus($jid, $new_status)) {
              $msg =  JText::_('COM_JOBBOARD_ERRORS_OCCURED').'<br />';
              $msg .=  JText::sprintf('COM_JOBBOARD_ENT_UPDERR', JText::sprintf('COM_JOBBOARD_ENT_STATUS', ''));
              $msgtype = 'error';
           } else {
              $msg =  JText::sprintf('COM_JOBBOARD_ENT_CHANGED', JText::sprintf('COM_JOBBOARD_ENT_STATUS', JText::_('COM_JOBBOARD_ENT_JOB'))).' <small>('.JText::_('COM_JOBBOARD_ENT_JOB').' #'.$jid.')</small>';
              $msgtype = 'Message';
           }
        } else {
            $msg =  JText::_('COM_JOBBOARD_ENTNOAUTH');
            $msgtype = 'error';
        }
        
        $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=admin&task=jobs&Itemid='.$this->_itemid), $msg, $msgtype);
    }

	function showJobApplications()
	{
	    JRequest::checkToken() or jexit( JText::_('Invalid Token') );

	    if($this->_user_cred['manage_applicants'] <> 1) {
	     return $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=admin&task=jobs&Itemid='.$this->_itemid), JText::_('COM_JOBBOARD_ENTNOAUTH'), 'error');
	    }

        $jid = JRequest::getInt('jid');
        $current_appls = $this->_umodel->getJobApplsCount($jid, $this->_uid);
        $layout_style = $this->_umodel->getLayoutConfig();

        if($this->_user_cred['manage_applicants'] == 0){
          return $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=admin&task=jobs&Itemid='.$this->_itemid), JText::_('COM_JOBBOARD_ENTNOAUTH'), 'error');
        }

        if($current_appls['user_appls'] == 0 && $current_appls['site_appls'] == 0){
          return $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=admin&task=jobs&Itemid='.$this->_itemid), JText::_('COM_JOBBOARD_ENT_NOTFOUND'), 'error');
        }

        $sub_context = JRequest::getString('s_context', 'user');
        $sub_context = $current_appls['user_appls'] < 1? 'site' : $sub_context;

        if($current_appls['user_appls'] > 0 && $sub_context <> 'site'){
            $admin_appl_model = & $this->getModel('Admapplicationslist');
        }  else {
            if($current_appls['site_appls'] > 0){
              $admin_appl_model = & $this->getModel('Admsapplicationslist');
            }
        }

        require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_appl.php' );

        $job_title = $this->_umodel->getApplJobTitle($jid);
        JRequest::setVar('uid', $this->_uid);
        JRequest::setVar('jid', $jid);

	    $view  =& $this->getView('admin', 'html');
        $view->setLayout('admin');
        $view->setModel( $admin_appl_model, true );
        $view->assign('context', 'applications');
        $view->assign('s_context', $sub_context);
        $view->assign('current_appls', $current_appls);
        $view->assign('job_title', $job_title);
        $view->assign('jid', $jid);
        $view->assign('layout_style', $layout_style);
        $view->assignRef('user_auth', $this->_user_cred);
        $view->assign('itemid', $this->_itemid);

	    $view->display();
	}

    function downloadCV() {
	   // Check for request forgeries
	   JRequest::checkToken('get') or jexit( JText::_('Invalid Token') );
        if($this->_user_cred['manage_applicants'] == 0){
          return $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=admin&task=jobs&Itemid='.$this->_itemid), JText::_('COM_JOBBOARD_ENTNOAUTH'), 'error');
        }
       $file_id = JRequest::getInt('file');

       $file = $this->_umodel->getApplFile($file_id);
       $filename = JPATH_COMPONENT_ADMINISTRATOR.DS.'cv'.DS.$file['file_hash'].'_'.$file['filename'];

       if(!JFile::exists($filename))
       {
            $msg = JText::_('COM_JOBBOARD_FILE_NOTFOUND');
            return $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=admin&task=appl&jid='.$file['job_id'].'&Itemid='.$this->_itemid), $msg, 'error');
       }

       $view  =& $this->getView('admin', 'file');
       $view->assign('file', $filename);

	   $view->display();
    }

    function downloadUCV() {
	   // Check for request forgeries
	   JRequest::checkToken('get') or jexit( JText::_('Invalid Token') );

       if($this->_user_cred['manage_applicants'] == 0){
          return $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=admin&task=jobs&Itemid='.$this->_itemid), JText::_('COM_JOBBOARD_ENTNOAUTH'), 'error');
        }

       $file_id = JRequest::getInt('file');
       $cvprof_id = JRequest::getInt('pid');
       $cv_uid = JRequest::getInt('uid');

       $file = $this->_umodel->getCvFile($file_id, $cvprof_id, $cv_uid);
       $filename = $file->filepath.DS.$file->filename;
       if(!JFile::exists($filename))
       {
            $msg = JText::_('COM_JOBBOARD_FILE_NOTFOUND');
            return $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=user&task=viewcv&profileid='.$cvprof_id.'&Itemid='.$this->_itemid), $msg, 'error');
       }

       $view  =& $this->getView('admin', 'file');
       $view->assign('file', $filename);
       $view->assign('filetype', $file->filetype);

	   $view->display();
    }

    function showQuestionnaires(){
        //JRequest::checkToken() or jexit( JText::_('Invalid Token') );

		$questionnaire_model =& $this->getModel('Admquestionairelist');
        $layout_style = $this->_umodel->getLayoutConfig();

        //auth?
        if($this->_uid > 0) {
          JRequest::setVar('uid', $this->_uid);
          $data = $questionnaire_model->getData();
        }

        $data_arr = array();
        $i = 0;
        $row_count = count($data);

        for($i = 0; $i<$row_count; $i++)  {
          $fields = json_decode($data[$i]->fields);
          $q_fields = is_object($fields)? $fields->fields : $fields;
          $data_arr[$i]->id = $data[$i]->id;
          $data_arr[$i]->qid = $data[$i]->qid;
          $data_arr[$i]->title = $data[$i]->title;
          $data_arr[$i]->fields = $q_fields;
        }

        unset($data);

	    $view  =& $this->getView('admin', 'html');
        $view->setLayout('admin');
        $view->setModel( $questionnaire_model, true );
        $view->assign('context', 'questionnaires');
        $view->assign('data', $data_arr);
        $view->assign('layout_style', $layout_style);
        $view->assignRef('user_auth', $this->_user_cred);
        $view->assign('itemid', $this->_itemid);

	    $view->display();
    }

	function viewQuestionnaire()
	{
	    JRequest::checkToken('get') or jexit( JText::_('Invalid Token') );

        $qid = JRequest::getInt('qid');
	    $view  =& $this->getView('admin', 'html');
		$appl_model =& $this->getModel('Apply');
        $questionnaire =  $appl_model->getQuestionnaire($qid);
        $fields =  json_decode($questionnaire['fields']);
      
        unset($questionnaire['fields']);
        $layout_style = $this->_umodel->getLayoutConfig();

        $view->setLayout('admin');
        $view->assignRef('questionnaire', $questionnaire);
        $view->assign('context', 'questionnaire');
        $view->assign('layout_style', $layout_style);
        $view->assignRef('fields', $fields->fields);
        $view->assign('editing', false);
        $view->assign('qid', $qid );
        $view->assignRef('user_auth', $this->_user_cred);
        $view->assign('itemid', $this->_itemid);

	    $view->display();
	}

	function editQuestionnaire()
	{
	    JRequest::checkToken('get') or jexit( JText::_('Invalid Token') );
        if($this->_user_cred['manage_questionnaires'] == 0) {
           $msg =  JText::_('COM_JOBBOARD_ENTNOAUTH');
           $msgtype = 'error';
           return $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=admin&task=qlist&Itemid='.$this->_itemid), $msg, $msgtype);
        }

        $qid = JRequest::getInt('qid');
        $qid = $qid < 1? 0 : $qid;
        if($qid == 0 && $this->_user_cred['create_questionnaires'] == 0) {
           $msg =  JText::_('COM_JOBBOARD_ENTNOAUTH');
           $msgtype = 'error';
           return $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=admin&task=qlist&Itemid='.$this->_itemid), $msg, $msgtype);
        }

	    $view  =& $this->getView('admin', 'html');
		$appl_model =& $this->getModel('Apply');
        $questionnaire =  $appl_model->getQuestionnaire($qid);
        $fields =  json_decode($questionnaire['fields']);
        unset($questionnaire['fields']);
        $layout_style = $this->_umodel->getLayoutConfig();
        $q_fields = is_object($fields)? $fields->fields : $fields;
        $months = JobBoardHelper::getMonthsList();
        $today = JobBoardHelper::getToday();

        $view->setLayout('admin');
        $view->assignRef('fields', $q_fields);
        $view->assign('context', 'questionnaire');
        $view->assignRef('questionnaire', $questionnaire);
        $view->assign('layout_style', $layout_style);
        $view->assign('editing', true);
        $view->assign('qid', $qid );
        $view->assignRef('user_auth', $this->_user_cred);
        $view->assign('today', $today);
        $view->assignRef('months', $months);
        $view->assign('itemid', $this->_itemid);

	    $view->display();
	}

	function saveQuestionnaire()
	{
	    JRequest::checkToken() or jexit( JText::_('Invalid Token') );
        require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_tbl.php' );

        $qid = JRequest::getInt('qid');
        $qid = $qid < 1? 0 : $qid;
        $errors = false;

        $data = array();
        $data['fields'] = JRequest::getString('fields');
        $fields =  json_decode($data['fields']);
        $data['name'] = JRequest::getString('name');
        $data['title'] = JRequest::getString('title');
        if($data['name'] == '' || $data['title'] == '') {
            $msg =  JText::_('COM_JOBBOARD_ERRORS_OCCURED').'<br />';
            $msg .=  JText::sprintf('COM_JOBBOARD_ENT_DATAMISSING', JText::_('COM_JOBBOARD_QNAIRE'));
            $msgtype = 'error';
         return $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=admin&task=qlist&Itemid='.$this->_itemid), $msg, $msgtype);
        }

        $data['description'] = JRequest::getString('description');

        $result = $this->_umodel->saveQuestionnaire($qid, $data, $this->_uid);
        if($qid == 0 && $result > 0) {
           $qid = $result;
           $this->_umodel->createQuestionnaireTbl($qid);
        }

        if(count($fields->fields > 0)) foreach($fields->fields as $field) {
             $result = $this->_umodel->checkQuestionnaireTbl($qid, $field->name);
             if($result <> true) {
                $query = JobBoardTblHelper::genSqlMod($field);
                $query = str_ireplace('[table]', '`#__jobboard_q'.$qid.'`', $query);
                if(!$this->_umodel->updateQuestionnaireTbl($query)){
                  $errors = true;
                }
             }
        }

        if(!$errors)
        {
          $msg =  JText::sprintf('COM_JOBBOARD_ENT_UPDATED', JText::_('COM_JOBBOARD_QNAIRE'));
          $msgtype = 'Message';
        } else {
          $msg =  JText::_('COM_JOBBOARD_ERRORS_OCCURED').'<br />';
          $msg .=  JText::sprintf('COM_JOBBOARD_ENT_UPDERR', JText::_('COM_JOBBOARD_QNAIRE'));
          $msgtype = 'error';
        }

        return $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=admin&task=qlist&Itemid='.$this->_itemid), $msg, $msgtype);
	}

	function delQnaireRow()
	{
	    JRequest::checkToken() or jexit( JText::_('Invalid Token') );
        require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_tbl.php' );

        $qid = JRequest::getInt('qid');
        $colname = JRequest::getString('name');

        if($qid < 1) {
            $msg =  JText::_('COM_JOBBOARD_ERRORS_OCCURED').'<br />';
            $msg .=  JText::sprintf('COM_JOBBOARD_ENT_DELERR', JText::_('COM_JOBBOARD_QNAIRE'));
            $msgtype = 'error';
         return $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=admin&task=qlist&Itemid='.$this->_itemid), $msg, $msgtype);
        }

		$appl_model =& $this->getModel('Apply');

        $query = JobBoardTblHelper::genSqlDrop($colname);
        $query = str_ireplace('[table]', '`#__jobboard_q'.$qid.'`', $query);

        $fields =  json_decode($appl_model->getQuestionnaireFields($qid));
        $new_fields = array();
        foreach ($fields->fields as $field) {
            if($field->name <> $colname) {
               $new_fields['fields'][] = $field;
            }
        }

        if(!$this->_umodel->updateQuestionnaireTbl($query)){
            $msg =  JText::_('COM_JOBBOARD_ERRORS_OCCURED').'<br />';
            $msg .=  JText::sprintf('COM_JOBBOARD_ENT_DELERR', JText::_('COM_JOBBOARD_QNAIRE'));
            $msgtype = 'error';
         return $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=admin&task=qlist&Itemid='.$this->_itemid), $msg, $msgtype);
        }

        if(!$this->_umodel->saveQnaireFields($qid, json_encode($new_fields))){
            $msg =  JText::_('COM_JOBBOARD_ERRORS_OCCURED').'<br />';
            $msg .=  JText::sprintf('COM_JOBBOARD_ENT_DELERR', JText::_('COM_JOBBOARD_QNAIRE'));
            $msgtype = 'error';
         return $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=admin&task=qlist&Itemid='.$this->_itemid), $msg, $msgtype);
        }
        $msg =  JText::_('COM_JOBBOARD_QNAIRE').' '.JText::sprintf('COM_JOBBOARD_ENT_DELETED', JText::_('COM_JOBBOARD_ELFIELD'));
        $app = & JFactory::getApplication();
        $app->enqueueMessage($msg, 'Message');

        $view  =& $this->getView('admin', 'html');
        $questionnaire =  $appl_model->getQuestionnaire($qid);
        $fields =  json_decode($questionnaire['fields']);

        if(!is_object($fields)) {
          JRequest::setVar('qid', $qid);
           return $this->editQuestionnaire();
        }

        unset($questionnaire['fields']);
        $layout_style = $this->_umodel->getLayoutConfig();

        $view->setLayout('admin');
        $view->assignRef('fields', $fields->fields);
        $view->assign('context', 'questionnaire');
        $view->assignRef('questionnaire', $questionnaire);
        $view->assign('layout_style', $layout_style);
        $view->assign('editing', true);
        $view->assign('qid', $qid );
        $view->assignRef('user_auth', $this->_user_cred);
        $view->assign('itemid', $this->_itemid);

	    $view->display();
	}

    function delQuestionnaire(){
        JRequest::checkToken() or jexit( JText::_('Invalid Token') );

        $qid = JRequest::getInt('qid');

        if($this->_user_cred['manage_questionnaires'] > 0)  {
            if(!$this->_umodel->delQuestionnaire($qid)) {
                $msg =  JText::_('COM_JOBBOARD_ERRORS_OCCURED').'<br />';
                $msg .=  JText::sprintf('COM_JOBBOARD_ENT_DELERR', JText::_('COM_JOBBOARD_QNAIRE'));
                $msgtype = 'error';
            } else {
                $msg =  JText::sprintf('COM_JOBBOARD_ENT_DELETED', JText::_('COM_JOBBOARD_QNAIRE'));
                $msgtype = 'Message';
            }
        } else {
           $msg =  JText::_('COM_JOBBOARD_ENTNOAUTH');
           $msgtype = 'error';
        }

        $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=admin&task=qlist&Itemid='.$this->_itemid), $msg, $msgtype);
    }


	function showInvites()
	{
		$userdata_model =& $this->getModel('Adminviteslist');
        $user_model =& $this->getModel('User');
        $profile_pic = JobBoardHelper::checkProfilePicStatus($this->_uid, &$user_model);
        $layout_style = $this->_umodel->getLayoutConfig();
        //auth?
        JRequest::setVar('suid', $this->_uid);
        $userdata = $userdata_model->getData();
        require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_invite.php' );

	    $view  =& $this->getView('admin', 'html');
        $view->setLayout('admin');
        $view->setModel( $userdata_model, true );
        $view->assign('context', 'invites');
        $view->assign('data', $userdata);
        $view->assign('is_profile_pic', $profile_pic['is_profile_pic']);
        $view->assign('imgthumb', $profile_pic['urithumb']);
        $view->assign('layout_style', $layout_style);
        $view->assignRef('user_auth', $this->_user_cred);
        $view->assign('itemid', $this->_itemid);

	    $view->display();
    }

    function resendInvite() {

        JRequest::checkToken() or jexit( JText::_('Invalid Token') );

        JRequest::setVar(JUtility::getToken(), 1);
        JRequest::setVar('resend', 1);

        $this->inviteUser();

    }

	function inviteUser()
	{
	    JRequest::checkToken() or jexit( JText::_('Invalid Token') );

        if($this->_user_cred['search_cvs'] == 0)  {
           $msg =  JText::_('COM_JOBBOARD_ENTNOAUTH');
           $msgtype = 'error';
           return $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=admin&Itemid='.$this->_itemid), $msg, $msgtype);
        }

        $resend = JRequest::getInt('resend', 0);

        $data = array(
                      'sid'=>JRequest::getInt('sid', 0)
                      , 'jid'=>JRequest::getInt('jid', 0)
                      , 'cpid'=>JRequest::getInt('cpid', 0)
                      , 'message'=>JString::Trim(JRequest::getString('message', ''))
                    );

        require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_invite.php' );

        $has_invite = JobBoardInviteHelper::hasInvite($data['sid'], $data['jid'])? 1 : 0;
        $has_applied = JobBoardInviteHelper::getApplId(array('uid'=>$data['sid'], 'jid'=>$data['jid'])) > 0? true : false;

        if($has_invite == 0){
          if($this->_umodel->saveInvite($data, $this->_uid) == true)  {
             $msg =  JText::sprintf('COM_JOBBOARD_ENT_CREATED', JText::_('COM_JOBBOARD_TXTINVITE'));
             $msgtype = 'Message';

          } else {
             $msg =  JText::sprintf('COM_JOBBOARD_ENT_CREATE_ERR', JText::_('COM_JOBBOARD_TXTINVITE'));
             $msgtype = 'error';
          }
        } elseif($resend == 1 && !$has_applied) {
           $msg =  JText::sprintf('COM_JOBBOARD_ENT_SENT', JText::_('COM_JOBBOARD_TXTINVITE'));
           $msgtype = 'Message';
        } else {
           $msg =  JText::sprintf('COM_JOBBOARD_ENT_EXISTS_ERR', JText::_('COM_JOBBOARD_TXTINVITE')).': '.JText::_('COM_JOBBOARD_ENT_JOB').' #'.$data['jid'];
           $msgtype = 'error';
        }

        if($has_invite == 0 || !$has_applied || $resend == 1){
             if(JobBoardInviteHelper::mailInvites($data['sid']) == 1)  {
                JPluginHelper::importPlugin('Jobboard');
                $dispatcher = & JDispatcher::getInstance();
                $dispatcher->trigger('onSendInvite', array( array('uid'=>&$this->_uid, 'sid'=>$data['sid'], 'jid'=>$data['jid'], 'cpid'=>$data['cpid'], 'message'=>$data['message']) ) );
             }
        }

        $app = &JFactory::getApplication();
        $app->enqueueMessage($msg, $msgtype);

        $resend <> 1? $this->showMyJobs() : $this->showInvites();
    }

	function showMyJobs()
	{
        JRequest::setVar('uid', $this->_uid);
        $candidate_uid = JRequest::getInt('sid', 0);
		$jobs_model =& $this->getModel('Admjoblist');
        $joblist = $jobs_model->getData();
        $layout_style = $this->_umodel->getLayoutConfig();
	    $view  =& $this->getView('admin', 'html');

        $numjobs = count($joblist);
        if($candidate_uid < 1)  {
          for($i=0; $i < $numjobs; $i++) {
             $current_appls = $this->_umodel->getJobApplsCount($joblist[$i]->id, $this->_uid);
             $joblist[$i]->num_applications = $current_appls['user_appls'] + $current_appls['site_appls'];
          }
            $context = 'jobs';
            $app = & JFactory::getApplication();
    		$jmode = $app->getUserStateFromRequest('com_jobboard.admin.joblist.jmode', 'jmode', '', 'string');
            $view->assign('jmode', $jmode);
        } else {

            if($this->_user_cred['search_cvs'] == 0)  {
               $msg =  JText::_('COM_JOBBOARD_ENTNOAUTH');
               $msgtype = 'error';
               return $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=admin&Itemid='.$this->_itemid), $msg, $msgtype);
           }

           $context = 'invite';
           require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_invite.php' );

           $cpid = JRequest::getInt('cpid', 0);
           $usr_model =& $this->getModel('User');
           $profile_pic = JobBoardHelper::checkProfilePicStatus($candidate_uid, &$usr_model);
           $profile_data = array('is_profile_pic'=>$profile_pic['is_profile_pic'], 'imgthumb'=>$profile_pic['urithumb']);
           $candidate_name = $this->_umodel->getJUsername($candidate_uid);

           $view->assignRef('prof', $profile_data);
           $view->assign('candidate_name', $candidate_name);
           $view->assign('sid', $candidate_uid);
           $view->assign('cpid', $cpid);
           $view->assign('jmode', '');
        }

        $view->setLayout('admin');
        $view->setModel( $jobs_model, true );
        $view->assign('context', $context);
        $view->assign('data', $joblist);
        $view->assign('layout_style', $layout_style);
        $view->assign('use_location', $this->_glb_cfg->use_location);
        $view->assignRef('user_auth', $this->_user_cred);
        $view->assign('itemid', $this->_itemid);

	    $view->display();
	}

    function showJob()
	{
		$applications_model =& $this->getModel('Apply');
        $joblist = $this->_umodel->getAdmJobs($this->_uid);
        $applied_jobs = array();
        if(count($joblist > 0)) {
          foreach($joblist as $job) {
            $applied_jobs[] = array('job' => $job, 'applications' => $applications_model->getUsrApplicationsForJob($job['id']));
          }
          unset($joblist);
        }

        $layout_style = $this->_umodel->getLayoutConfig();

	    $view  =& $this->getView('admin', 'html');
        $view->setLayout('admin');
        $view->assign('context', 'jobs');
        $view->assign('data', $applied_jobs);
        $view->assign('layout_style', $layout_style);
        $view->assignRef('user_auth', $this->_user_cred);
        $view->assign('itemid', $this->_itemid);

	    $view->display();
	}

    function editJob() {
        //JRequest::checkToken() or jexit( JText::_('Invalid Token') );
        $jid = JRequest::getInt('jid', 0);

        $layout_style = $this->_umodel->getLayoutConfig();

		$countries = $this->_umodel->getCountries();
		$careers = $this->_umodel->getCareers();
		$education = $this->_umodel->getEducation();
		$categories = $this->_umodel->getCategories();
		$statuses =  $this->_umodel->getStatuses();
		$departments =  $this->_umodel->getDepartments();
        $long_date_format = $this->_umodel->getDateFormat();
        $config = $this->_umodel->getJobEditConfig();
        $questionnaires = $this->_umodel->getQuestionnaires($this->_uid, true);
        $can_feature = $this->_auth_model->canFeature($this->_uid);

        if($jid > 0) {
          $job_data = $this->_umodel->getJob($jid, $this->_uid);
          if(empty($job_data)){
               $msg =  JText::_('COM_JOBBOARD_ENTNOAUTH');
               $msgtype = 'error';
               return $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=admin&task=jobs&Itemid='.$this->_itemid), $msg, $msgtype);
          }
          $current_appls = $this->_umodel->getJobApplsCount($job_data->id, $this->_uid);
          $job_data->num_applications = $current_appls['user_appls'] + $current_appls['site_appls'];

        } else {
          $job_data = array(
                        'id'=>0
                        , 'post_date'=>''
                        , 'expiry_date'=>'0000-00-00 00:00:00'
                        , 'posted_by'=>null
                        , 'job_title'=>''
                        , 'job_type'=>''
                        , 'description'=>''
                        , 'duties'=>''
                        , 'positions'=>1
                        , 'salary'=>''
                        , 'job_tags'=>''
                        , 'geo_latitude'=>''
                        , 'geo_longitude'=>''
                        , 'geo_state_province'=>''
                        , 'featured'=>0
                        , 'num_applications'=>array()
                      );
          $job_data = JArrayHelper::toObject($job_data);
        }

	    $view  =& $this->getView('admin', 'html');
        $view->setLayout('admin');
        $view->assign('context', 'editjob');
        $view->assignRef('data', $job_data);
		$view->assignRef('countries', $countries);
		$view->assignRef('statuses', $statuses);
		$view->assignRef('departments', $departments);
		$view->assignRef('careers', $careers);
		$view->assignRef('education', $education);
		$view->assignRef('categories', $categories);
        $view->assign('layout_style', $layout_style);
        $view->assign('long_date_format', $long_date_format);
        $view->assignRef('questionnaires', $questionnaires);
        $view->assignRef('user_auth', $this->_user_cred);
        $view->assignRef('config', $config);
        $view->assign('can_feature', $can_feature);
        $view->assign('itemid', $this->_itemid);
                               
	    $view->display();
    }

    function saveJob() {
        JRequest::checkToken() or jexit( JText::_('Invalid Token') );
        $job = JRequest::get('post');
        if(isset($job['featured']))
         $job['featured'] = $job['featured'] == 'yes'? 1 : 0;
        else $job['featured'] = 0;

        $job['description'] = JRequest::getVar('description', '', 'POST', 'string', JREQUEST_ALLOWRAW);
        $job['duties'] = JRequest::getVar( 'duties', '', 'POST', 'string', JREQUEST_ALLOWRAW);
        $config = $this->_umodel->getJobEditConfig();
        switch($job['jid'])  {
          case 0 :
            if($this->_umodel->saveJob($job, $this->_uid, $config->use_location)){
              $msg =  JText::sprintf('COM_JOBBOARD_ENT_CREATED', JText::_('COM_JOBBOARD_ENT_JOB'));
              $msgtype = 'Message';
            } else {
              $msg =  JText::_('COM_JOBBOARD_ERRORS_OCCURED').'<br />';
              $msg .=  JText::sprintf('COM_JOBBOARD_ENT_UPDERR', JText::_('COM_JOBBOARD_ENT_JOB'));
              $msgtype = 'error';
            }
          break;
          default;
            if($this->_umodel->updJob($job['jid'], $job, false, $job['repost'])){

              $msg =  JText::sprintf('COM_JOBBOARD_ENT_UPDATED', JText::_('COM_JOBBOARD_ENT_JOB').' '.JText::_('COM_JOBBOARD_ENT_ID').' '.$job['jid']);
              $msgtype = 'Message';
            } else {
              $msg =  JText::_('COM_JOBBOARD_ERRORS_OCCURED').'<br />';
              $msg .=  JText::sprintf('COM_JOBBOARD_ENT_UPDERR', JText::_('COM_JOBBOARD_ENT_JOB'));
              $msgtype = 'error';
            }
          ;break;
        }

        $config = & JTable::getInstance('Config', 'Table');
		$config->load(1);
		$dept_tbl = & JTable::getInstance('Department', 'Table');
		$dept_tbl->load($job['department']);

		if ($dept_tbl->notify_admin == 1 || $dept_tbl->notify == 1) {
			$job['dept_name'] =  $dept_tbl->name;
		}
		if ($dept_tbl->notify_admin == 1) {
  		  if($job['jid'] == 0){
  				$this->sendJobEmail($job, $config, $config->from_mail, 'adminnew');
  			} else
			    $this->sendJobEmail($job, $config, $config->from_mail, 'adminupdate');
		}
		if ($dept_tbl->notify == 1) {
  		  if($job['jid'] == 0){
			$this->sendJobEmail($job, $config, $dept_tbl->contact_email, 'adminnew');
  			} else
			$this->sendJobEmail($job, $config, $dept_tbl->contact_email, 'adminupdate');
		}

        $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=admin&task=jobs&Itemid='.$this->_itemid), $msg, $msgtype);
    }

    function sendJobEmail(&$msgobj, &$config, $to_email, $msg_type)
	{
		$messg_model =& $this->getModel('Message');
		$msg_id = $messg_model->getMsgID($msg_type);
		$msg = $messg_model->getMsg($msg_id);

		$from = $config->reply_to;
		$fromname = $config->organisation;

		$job_status = ($msgobj['published'] == 1)? JText::_('COM_JOBBOARD_ACTIVE') : JText::_('COM_JOBBOARD_INACT');

		$subject = str_replace('[jobtitle]', $msgobj['job_title'], $msg->subject);
		$subject = str_replace('[jobid]', $msgobj['jid'], $subject);
		$subject = str_replace('[location]', $msgobj['city'], $subject);
		$subject = str_replace('[department]', $msgobj['dept_name'], $subject);
		$subject = str_replace('[status]', $job_status, $subject);

		$body = str_replace('[jobid]', $msgobj['jid'], $msg->body);
		$body = str_replace('[jobtitle]', $msgobj['job_title'], $body);
		$body = str_replace('[location]', $msgobj['city'], $body);
		$body = str_replace('[department]', $msgobj['dept_name'], $body);
		$body = str_replace('[status]', $job_status, $body);

		if($msg_type == 'adminupdate' || $msg_type = 'adminnew') {
			$user = & JFactory::getUser();
			$body = str_replace('[appladmin]', $user->name, $body);
		}

        return JobBoardHelper::dispatchEmail($from, $fromname,  $to_email, $subject, $body);
	}

    function editApplication()
	{
	    if($this->_user_cred['manage_applicants'] == 0){
          return $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=admin&task=jobs&Itemid='.$this->_itemid), JText::_('COM_JOBBOARD_ENTNOAUTH'), 'error');
        }

	    $view  =& $this->getView('admin', 'html');
        $view->setLayout('admin');

        $appl_id = JRequest::getInt('aid');
        $jid = JRequest::getInt('jid');
        $s_context = JRequest::getString('s_context');
        if($s_context == 'user') {
           $appl_uid = JRequest::getInt('sid');
           $qid = JRequest::getInt('qid');

           if($qid > 0) {
             $applications_model =& $this->getModel('Apply');
             $questionnaire = $applications_model->getQuestionnaire($qid);
             $fields = json_decode($questionnaire['fields']);
             if(!is_object($fields)) {
              $qid = 0;
             } else {
               unset($questionnaire['fields']);
               $q_answers = $this->_umodel->getApplicantQanswers($qid, $appl_id);
               jimport('joomla.utilities.date');
               $today = new JDate();
               $view->assignRef('questionnaire', $questionnaire);
               $view->assignRef('q_answers', $q_answers);
               $view->assignRef('fields', $fields->fields);
               $view->assignRef('today', $today);
             }
          }
        }

        $sitemode = $s_context == 'user'? false : true;
        $job_title = $this->_umodel->getApplJobTitle($jid);

        if($s_context == 'user'){
          $prof_id = JRequest::getInt('pid');
          $applicant_name = $this->_umodel->getJUsername($appl_uid);
          $cv_name = $this->_umodel->getCvProfileName($prof_id, $appl_uid);
          $user_prof_data = $this->_umodel->getSeekerProfile($appl_uid, true);
          $view->assign('cv_name', $cv_name);
          $view->assign('applicant_name', $applicant_name);
          $view->assign('qid', $qid);
          $view->assign('appl_uid', $appl_uid);
          $view->assign('pid', $prof_id);
        }

        if($s_context == 'site') {
          $user_prof_data = $this->_umodel->getSiteApplInfo($appl_id);
        }

        $appl_data = $this->_umodel->getApplication($appl_id, $sitemode);
        $statuses = $this->_umodel->getStatuses();
        $layout_style = $this->_umodel->getLayoutConfig();

        $user_model =& $this->getModel('User');
        $pp_status = ($s_context == 'site')? array() : JobBoardHelper::checkProfilePicStatus($appl_uid, $user_model, 2);

        $view->assign('context', 'application');
        $view->assignRef('user_prof_data', $user_prof_data);
        $view->assignRef('appl_data', $appl_data);
        $view->assignRef('statuses', $statuses);
        if($s_context == 'user') :
          $view->assign('is_profile_pic', $pp_status['is_profile_pic']);
          $view->assign('imgthumb', $pp_status['urithumb']);
          $view->assign('imgthumb_115', $pp_status['urithumb2']);
        endif;
        $view->assign('layout_style', $layout_style);
        $view->assign('job_title', $job_title);
        $view->assign('aid', $appl_id);
        $view->assign('jid', $jid);
        $view->assignRef('user_auth', $this->_user_cred);
        $view->assign('itemid', $this->_itemid);
        $view->assign('s_context', $s_context);

	    $view->display();
	}

	function saveApplication()
	{
        JRequest::checkToken() or jexit( JText::_('Invalid Token') );

        $appl_data = array('status' => JRequest::getInt('status'), 'admin_notes'=> JRequest::getVar('admin_notes', '', 'POST', 'string', JREQUEST_ALLOWRAW));
                                                                            
        $s_context = JRequest::getString('s_context');
        $jid = JRequest::getInt('jid');
        $aid = JRequest::getInt('aid');
        require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_appl.php' );

        if($s_context == 'user') {
          $qid = JRequest::getInt('qid');
          if($qid > 0) {
             $appl_model =& $this->getModel('Apply');
             $fields_arr = array();
             $fields =  json_decode($appl_model->getQuestionnaireFields($qid));

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
             $errors = !$this->_umodel->updApplicantQanswers($qid, $aid, $fields_arr)? true : false;
          }
        }

        $sitemode = $s_context == 'site'? true : false;
        if(!$this->_umodel->saveApplication($aid, $appl_data, $sitemode) )  {
            $msg =  JText::_('COM_JOBBOARD_ERRORS_OCCURED').'<br />';
            $msg .=  JText::sprintf('COM_JOBBOARD_ENT_UPDERR', JText::_('COM_JOBBOARD_JOBAPPLICATION'));
            $msgtype = 'error';
        } else {
            $applicant = JArrayHelper::toObject($appl_data);
            $messg_model =& $this->getModel('Message');
            $applicant->job_id = $jid;
            $applicant->department = JobBoardApplHelper::getDeptId($jid);
            $applicant->title = JobBoardApplHelper::getJobTitle($jid);
            switch($sitemode){
              case false :
                  $applicant_details = JobBoardApplHelper::getRegUser($aid);
                  $applicant->first_name = $applicant_details['name'];
                  $applicant->last_name = '';
                  $applicant->email = $applicant_details['email'];
                  $applicant->auid = $applicant_details['id'];
              break;
              case true :
                  $applicant_details = JobBoardApplHelper::getSiteUser($aid);
                  $applicant->first_name = $applicant_details['first_name'];
                  $applicant->last_name = $applicant_details['last_name'];
                  $applicant->email = $applicant_details['email'];
              break;
            }

            $process_mail = JobBoardApplHelper::processMail($applicant, !$sitemode, $messg_model);

            $msg =  JText::sprintf('COM_JOBBOARD_ENT_UPDATED', JText::_('COM_JOBBOARD_JOBAPPLICATION'));
            $msgtype = 'Message';
        }

        $app = & JFactory::getApplication();
        $app->enqueueMessage($msg, $msgtype);

        JRequest::setVar('jid', $jid);
        JRequest::setVar(JUtility::getToken(), 1);

        $this->showJobApplications();
	}

    function searchCvProfiles(){

        if($this->_user_cred['search_cvs'] == 0){
          return $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=admin&Itemid='.$this->_itemid), JText::_('COM_JOBBOARD_ENTNOAUTH'), 'error');
        }

        $app = & JFactory::getApplication();

        $job_title = JString::trim(JRequest::getString('job_title', ''));
        $skills = JString::trim(JRequest::getString('skills', ''));
        $qualification = JString::trim(JRequest::getString('qualification', ''));
        $ed_level = $app->getUserStateFromRequest('com_jobboard.cvsearch.ed_level', 'ed_level',  0, 'int');

        if(JRequest::getInt('f_reset', 0) == 1) {
            $job_title = $skills = $qualification = '';
            $ed_level = 0;
        }

        $app->setUserstate("com_jobboard.cvsearch.job_title", $job_title, 'string');
        $app->setUserstate("com_jobboard.cvsearch.skills", $skills, 'string');
        $app->setUserstate("com_jobboard.cvsearch.qualification", $qualification, 'string');
        $app->setUserstate("com_jobboard.cvsearch.ed_level", $ed_level, 'int');

        $query_present = ($job_title <> '' || $skills <> '' || $qualification <> '')? 1 : 0;

        if($query_present == 1) {
             JRequest::checkToken() or jexit(JText::_('Invalid Token'));
        }

        $app->setUserstate("com_jobboard.cvsearch.search_private", $this->_user_cred['search_private_cvs'], 'int');

        $layout_style = $this->_umodel->getLayoutConfig();
	    $view  =& $this->getView('admin', 'html');
        $view->setLayout('admin');
        $view->assign('ed_level', $ed_level);

        if($query_present == 1) {
           $title_filter = $skill_filter = $qual_filter = array();
           $app->setUserstate("com_jobboard.cvsearch.uid", $this->_uid, 'int');

           $view->assign('uid', $this->_uid);
           $app->setUserstate("com_jobboard.cvsearch.use_location", $this->_glb_cfg->use_location, 'int');

           $cv_model =& $this->getModel('Admcvlist');
           $data = $cv_model->getData();
           
           if(count($data) > 0) {
             require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_find.php' );

             $new_arr = array();
             $quals_arr = array();
             $prof_ids = array();
             $usr_model =& $this->getModel('User');

             foreach($data as $prof){
               $profile_pic = JobBoardHelper::checkProfilePicStatus($prof->user_id, &$usr_model);
               $prof->img = array('is_profile_pic'=>$profile_pic['is_profile_pic'], 'imgthumb'=>$profile_pic['urithumb']);
               if($this->_glb_cfg->use_location == 1) {
                 $prof->location = JobBoardFindHelper::getUsrLoc($prof->user_id);
               }
               $new_arr[] = $prof;
               $prof_ids[] = $prof->id;
               if($prof->highest_qual > 0) {
                  $quals_arr[] = $prof->highest_qual;
               }
             }

             $data = $new_arr;
             unset($new_arr);

             $ed_levels = JobBoardFindHelper::getEdlvls();

             /*if($job_title == '') {
                $title_filter = array_values($cv_model->getTitlesByProfileId($prof_ids));
             } else {*/
                $title_filter = $app->getUserState('com_jobboard.cvsearch.title_filter',  array(), 'array');
             // }

             if($skills == '') {
                $skill_filter = array_values($cv_model->getSkillsByProfileId($prof_ids));
             } else {
                $skill_filter = $app->getUserState('com_jobboard.cvsearch.skill_filter',  array(), 'array');
             }

             if($qualification == '') {
                $qual_filter = array_values($cv_model->getQualsByProfileId($prof_ids));
             } else {
                $qual_filter = $app->getUserState('com_jobboard.cvsearch.qual_filter',  array(), 'array');
             }

             $view->assignRef('title_filter', $title_filter);
             $view->assignRef('skill_filter', $skill_filter);
             $view->assignRef('qual_filter', $qual_filter);
             $view->assignRef('ed_levels', $ed_levels);
             $view->assignRef('ed_matches', $quals_arr);
             $view->assign('use_location', $this->_glb_cfg->use_location);
             $view->setModel($cv_model, true);

             unset($title_filter, $skill_filter, $qual_filter, $prof_ids);

           }
        } else {
            $data = array();
        }

        $view->assign('context', 'cvsrch');
        $view->job_title = $view->escape($job_title);
        $view->skills = $view->escape($skills);
        $view->qualification = $view->escape($qualification);
        $view->assignRef('data', $data);
        $view->assign('query_present', $query_present);
        $view->assign('layout_style', $layout_style);
        $view->assignRef('user_auth', $this->_user_cred);
        $view->assign('itemid', $this->_itemid);

	    $view->display();
    }

    function showCvProfile()
	{
	    if($this->_user_cred['manage_applicants'] == 0 || $this->_user_cred['search_cvs'] == 0){
          return $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=admin&task=jobs&Itemid='.$this->_itemid), JText::_('COM_JOBBOARD_ENTNOAUTH'), 'error');
        }                                                          
        $search_mode = JRequest::getInt('s_mode', 0);
        $prof_id = JRequest::getInt('pid');
        $cv_uid = JRequest::getInt('sid');
        if($search_mode == 0) {
          $jid = JRequest::getInt('jid');
          $job_title = $this->_umodel->getApplJobTitle($jid);
        }

        $applicant_name = $this->_umodel->getJUsername($cv_uid);
        $user_prof_data = $this->_umodel->getSeekerProfile($cv_uid, true);
        $cv_data = $this->_umodel->getCvProfile($prof_id, $cv_uid, true, true);
        $this->_umodel->incrCVcounter($prof_id);
        $layout_style = $this->_umodel->getLayoutConfig();

        $user_model =& $this->getModel('User');
        $pp_status = JobBoardHelper::checkProfilePicStatus($cv_uid, $user_model, 2);

	    $view  =& $this->getView('admin', 'html');
        $view->setLayout('admin');
        $view->assignRef('cv_data', $cv_data);
        $view->assignRef('user_prof_data', $user_prof_data);
        $view->assign('context', 'cvprofile');
        $view->assign('is_profile_pic', $pp_status['is_profile_pic']);
        $view->assign('imgthumb', $pp_status['urithumb']);
        $view->assign('imgthumb_115', $pp_status['urithumb2']);
        $view->assign('layout_style', $layout_style);
        if($search_mode == 0) {
          $view->assign('job_title', $job_title);
          $view->assign('jid', $jid);
        }
        $view->assign('s_mode', $search_mode);
        $view->assign('applicant_name', $applicant_name);
        $view->assignRef('user_auth', $this->_user_cred);
        $view->assign('itemid', $this->_itemid);

	    $view->display();
	}

    function cloneJob() {

       JRequest::checkToken() or jexit (JText::_( 'Invalid Token' ));

	   if($this->_user_cred['post_jobs'] == 0){
          return $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=admin&Itemid='.$this->_itemid), JText::_('COM_JOBBOARD_ENTNOAUTH'), 'error');
       }

       $jid = JRequest::getInt('jid');
       $errors = array();
       $curr_job = JArrayHelper::fromObject($this->_umodel->getJob($jid, $this->_uid));
       $source_id = $curr_job['id'];
       $curr_job['featured'] = 0;
       $curr_job['hits'] = 0;
       $curr_job['ref_num'] = '';
       $curr_job['num_applications'] = 0;
       unset($curr_job['id']);
       $new_jobname =  $curr_job['job_title'].' - '.JText::_('COM_JOBBOARD_COPY');

       /*save job post and return new record id*/
       $new_jobid = $this->_umodel->saveJobMeta($new_jobname, $this->_uid);

       if($new_jobid > 0) {
           if(!$this->_umodel->updJob($new_jobid, $curr_job, true)) {
               $msg = JText::sprintf('COM_JOBBOARD_ENT_UPDERR', JText::_('COM_JOBBOARD_ENT_JOB'));
               $msgtype = 'error';
           } else {
               $msg = JText::_('COM_JOBBOARD_JOB_CLONED').' '.$source_id;
               $msgtype = 'Message';
           }
       }
      $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=admin&task=jobs&Itemid='.$this->_itemid), $msg, $msgtype);
    }

    function deleteJob(){

       JRequest::checkToken() or jexit (JText::_( 'Invalid Token' ));
       $jid = JRequest::getInt('jid');
       $result = false;
       if($this->_user_cred['manage_jobs'] == 0 || $this->_user_cred['user_status'] == 0) {
          $result = JText::_('COM_JOBBOARD_ENTNOAUTH');
       }  else {
          $result = $this->_umodel->delJob($jid);
          if($result == true) {
            $result = $this->_nullApplications($jid);
            }
          if($result == true) {
            $result = $this->_nullSiteApplications($jid);
            }
        }
       if($result <> true)
       {
          $msg = $result;
          $msgtype = 'error';
       }  else
       {
            $msg = JText::sprintf('COM_JOBBOARD_ENT_DELETED', JText::_('COM_JOBBOARD_ENT_JOB'));
            $msgtype = 'Message';
       }
       $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=admin&task=jobs&Itemid='.$this->_itemid), $msg, $msgtype);
    }

    private function _nullApplications($jid) {

        if(!$this->_umodel->nullApplications($jid)) {
               return JText::sprintf('COM_JOBBOARD_ENT_UPDERR', JText::_('COM_JOBBOARD_ENT_JOB'));
        }
       return true;
    }
    private function _nullSiteApplications($jid) {
        if(!$this->_umodel->nullSiteApplications($jid)) {
               return JText::sprintf('COM_JOBBOARD_ENT_UPDERR', JText::_('COM_JOBBOARD_ENT_JOB'));
        }
       return true;
    }

}

$controller = new JobboardControllerAdmin();
$controller->execute($task);
$controller->redirect();

?>
