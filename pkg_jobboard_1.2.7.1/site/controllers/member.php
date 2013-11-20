<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.controller');

class JobboardControllerMember extends JController
{
     /**
	 * constructor
	 */
	function __construct()
	{
		parent::__construct();

		$this->registerTask('login', 'authenticateUser');
		$this->registerTask('signup', 'registerUser');
		// $this->registerTask('logout', 'logoutUser');
    }

    function display() {
          $app =& JFactory::getApplication();

          $initial_view = JRequest::getString('iview', 'login');
          if($initial_view == 'logout') {
            return $this->logoutUser();
          } else {
              if($initial_view == 'register') {
                if(!JobBoardHelper::allowRegistration()) {
                   $app->redirect(JRoute::_('index.php?option=com_jobboard&view=member&iview=login'), JText::_('COM_JOBBOARD_ENT_NOTALLOWED'), 'error');
                   return;
                 }
              }
              $app->setUserState('com_jobboard.member.initial_view', $initial_view, 'string');

              $return = JRequest::getString('redirect', '');
              $view  =& $this->getView('member', 'html');
              $view->assign('iview', $initial_view);
              $view->assign('redirect', $return);
        	  $view->display();
          }
    }

    function authenticateUser(){

		JRequest::checkToken() or jexit( 'Invalid Token' );

        require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_member.php' );
        $app =& JFactory::getApplication();

        $retries = $app->getUserState('com_jobboard.member.retry', 0, 'int');
        $app->setUserState('com_jobboard.member.initial_view', 'login', 'string');

        if(JobBoardHelper::verifyLogin()) {
          if(!JobBoardMemberHelper::matchHumanCode(JRequest::getString('human_ver', ''))) {
             $retries += 1;
             $app->setUserState('com_jobboard.member.retry', $retries, 'int');
             $app->redirect(JRoute::_('index.php?option=com_jobboard&view=member'), JText::_('COM_JOBBOARD_FORM_CAPTCHA_FAILMSG'), 'error');
    		 return;
          }
        }

        $user = array();
		$user['username'] = JRequest::getString('username', '');
		$user['password'] = JRequest::getString('password', '');
        $return = JRequest::getString('redirect', '');

        $remember = JRequest::getString('remember', '');
        $remember = $remember == 'yes'? true : false;
        $this->_login( $user, $remember, $return);
    }

    /**
     * Returns yes/no
     * @param array [username] & [password]
     * @param mixed Boolean
     *
     * @return boolean
     */
    private function _login( $user_object, $remember=false, $return='' ) {
        $app = & JFactory::getApplication();

        $options = array();
        $options['remember'] = $remember;
        $options['return'] = '';

        $retries = $app->getUserState('com_jobboard.member.retry', 0, 'int');
        if(!$app->login($user_object, $options)){
            $retries += 1;
            $app->setUserState('com_jobboard.member.retry', $retries, 'int');
            $app->redirect(JRoute::_('index.php?option=com_jobboard&view=member'), JText::_('COM_JOBBOARD_LOGIN_FAIL'), 'error');
            return;
        } else {
           $app->setUserState('com_jobboard.member.retry', 0, 'int');
        }

        if($return == '')  {
          $user = & JFactory::getUser();
          $user_model = & $this->getModel('Member');
          $user_view = $user_model->getDashConfig($user->id);
          $user_view = $user_view == 1? 'admin' : 'user';
  	      $app->redirect(JRoute::_('index.php?option=com_jobboard&view='.$user_view));
          return;
        }
        $app->redirect(JRoute::_('index.php?option=com_jobboard&view=user&redirect='.$return));
    }

    private function _newUser($tpl = null) {
      $app = JFactory::getApplication();
      $usersConfig = &JComponentHelper::getParams( 'com_users' );
	  if (!$usersConfig->get( 'allowUserRegistration' )) {
			JError::raiseError( 403, JText::_( 'Access Forbidden' ));
			return;
		}

		$user =& JFactory::getUser();

		if ( $user->get('guest')) {
            // JRequest::setVar('layout','form');
            $app->redirect(JRoute::_('index.php?option=com_jobboard&view=member'));
		} else {
			$app->redirect(JRoute::_('index.php?option=com_jobboard&view=member'), JText::_('COM_JOBBOARD_REG_EXISTING'));
		}
    }

    /**
	 * Save user registration and notify users and admins if required
	 * @return void
	 */
	function registerUser()
	{
		JRequest::checkToken() or jexit( JText::_('Invalid Token' ));
        require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_member.php' );
		$app = & JFactory::getApplication();

        if(JobBoardMemberHelper::verifyReg()) {
          if(!JobBoardMemberHelper::matchHumanCode(JRequest::getString('human_ver', ''))) {
              $app->redirect(JRoute::_('index.php?option=com_jobboard&view=member&iview=register'), JText::_('COM_JOBBOARD_FORM_CAPTCHA_FAILMSG'), 'error');
  			return;
          }
        }

		// Get required system objects
		$user 		= clone(JFactory::getUser());
		$pathway 	=& $app->getPathway();
		$config		=& JFactory::getConfig();
		$authorize	=& JFactory::getACL();
		$document   =& JFactory::getDocument();

		// If user registration is not allowed, show 403 not authorized.
		$usersConfig = &JComponentHelper::getParams( 'com_users' );
		if ($usersConfig->get('allowUserRegistration') == '0') {
			JError::raiseError( 403, JText::_( 'Access Forbidden' ));
			return;
		}

		// Initialize new usertype setting
		$newUsertype = $usersConfig->get('new_usertype');
        if(!version_compare(JVERSION,'1.6.0','ge'))  {
          $newUsertype = !$newUsertype? 2 : $newUsertype;
        }  else {
		  $newUsertype = !$newUsertype? 'Registered' : $newUsertype;
        }

        $post = JRequest::get('post');
		if (!$user->bind( $post, 'usertype' )) {
			JError::raiseError( 500, $user->getError());
		}

		// Set some initial user values
		$user->set('id', 0);
  		$user->set('usertype', $newUsertype);

        if(version_compare(JVERSION,'1.6.0','ge'))  {
		    $user->groups[] = $newUsertype;

        } else {
    		$user->set('gid', $authorize->get_group_id( '', $newUsertype, 'ARO' ));
        }

		$date =& JFactory::getDate();
		$user->set('registerDate', $date->toMySQL());

	    $useractivation = $usersConfig->get( 'useractivation' );
		$user->must_activate = intval($useractivation);
		if ($useractivation == '1')
		{
			jimport('joomla.user.helper');
			$user->set('activation', JUtility::getHash( JUserHelper::genRandomPassword()) );
			$user->set('block', '1');
		}

        $user->is_empl = JRequest::getString('isemployer', '') == 'yes'? 1 : 0;
        $user->goto_board = true;

		if ( !$user->save() ) {
			JError::raiseWarning('', JText::_( $user->getError()));
			JRequest::setVar('iview', 'register');
            $app->enqueueMessage(JText::_('COM_JOBBOARD_REG_FAIL'), 'error');
            $this->display();
			return false;
		}
	}

    function logoutUser() {
		$app = & JFactory::getApplication();
        if($app->logout())
    	    $app->redirect(JRoute::_('index.php?option=com_jobboard'));
    }

}
$controller = new JobboardControllerMember();
$controller->execute($task);
$controller->redirect();

