<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class plgUserJobboard_Usersync extends JPlugin
{
    /**
     * Constructor
     * @param object $subject The object to observe
     * @param   array  $config  An array that holds the plugin configuration
     * @since 1.5
     */
	function plgUserJobboard_Usersync(&$subject, $config) {

		parent::__construct($subject, $config);
        $lang =& JFactory::getLanguage();
        $lang->load('plg_jobboard_usersync', JPATH_ADMINISTRATOR);
        //$this->_itemid = JRequest::getInt('Itemid');
	}

	function onAfterStoreUser($user, $isnew, $success, $msg)
	{
		//if this is a new user and was stored successfully, then import them into Job board
		if($isnew && $success)
		{
			$this->_syncJobboardUser(&$user);
		}
	}

    function onUserAfterSave($user, $isnew, $success, $msg)
	{
		//if this is a new user and was stored successfully, then import them into Job board
		if($isnew && $success)
		{
           $this->_syncJobboardUser(&$user);
		}
	}

    private function _syncJobboardUser($user) {
		$app = & JFactory::getApplication();

    	$isEnabled = $this->params->get('user_status', 0);
        $api_keys = $this->_getApiKeys();

        if(self::_saveJobboardUser($user, $api_keys, $isEnabled)){

            require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jobboard'.DS.'helpers'.DS.'jobboard_member.php' );
            if($user['is_empl'] == 1) {
              $default_grp = JobBoardMemberHelper::getUserGroup(true);
              $can_feature = JobBoardMemberHelper::canFeature();
        	  if(JobBoardMemberHelper::setGroupId($user['id'], $default_grp) && JobBoardMemberHelper::setUserDash($user['id'], 1) && JobBoardMemberHelper::setFeaturePerm($user['id'], $can_feature)) {
        	     $message = JText::_('PLG_JOBBOARD_USER_REG_SUCCESS');
                 $mg_type = 'Message';
        	  } else {
        	     $message = JText::_('PLG_JOBBOARD_USER_REG_EMP_FAIL');
                 $mg_type = 'error';
        	  }
            } else {
                $default_grp = JobBoardMemberHelper::getUserGroup();
                if(JobBoardMemberHelper::setGroupId($user['id'], $default_grp)) {
          	     $message = JText::_('PLG_JOBBOARD_USER_REG_SUCCESS');
                   $mg_type = 'Message';
            	  } else {
            	     $message = JText::_('PLG_JOBBOARD_USER_REG_EMP_FAIL');
                     $mg_type = 'error';
            	  }
            }

            $admins = array();
            JPluginHelper::importPlugin('Jobboard');
            $dispatcher = & JDispatcher::getInstance();
            $dispatcher->trigger('onJobboardRegister', array( array('user'=>$user, 'admins'=>$admins) ));

        } else {
    	     $message = JText::_('PLG_JOBBOARD_USER_REG_EMP_FAIL');
             $mg_type = 'error';
        }

        $itemid = JRequest::getInt('Itemid', 0);
        $itemid_seg = $itemid > 0? '&Itemid='.$itemid : '';
            $app->enqueueMessage($message, $mg_type);
        if(isset($user['goto_board'])){
             $app->redirect(JRoute::_('index.php?option=com_jobboard'.$itemid_seg));
        }
    }

    private function _saveJobboardUser($user, $api_keys, $isEnabled=0) {

    	$db =& JFactory::getDBO();
    	//import new Joomla user into JobBoard user table
    	$query = "INSERT INTO ".$db->nameQuote('#__jobboard_users')."
                                   ( ".$db->nameQuote('user_id')." ,
                                     ".$db->nameQuote('group_id')." ,
                                     ".$db->nameQuote('user_status')." ,
                                     ".$db->nameQuote('user_key')." ,
                                     ".$db->nameQuote('user_secret')." ,
                                     ".$db->nameQuote('login_dashboard')." )
               VALUES (".$user['id'].", 5, ".$isEnabled.", ".$db->Quote($api_keys['user_key']).", ".$db->Quote($api_keys['user_secret']).", 0)";
        $db->setQuery($query);
    	return $db->query();
    }

    private function _getApiKeys() {
          $u_key = $this->_randKey();
          $u_secr = $this->_randStr($u_key);
          return array('user_key'=>$u_key, 'user_secret'=>$u_secr);
    }

	function onAfterDeleteUser($user, $success, $msg)
	{
		//if user was deleted from Joomla successfully, then delete it from Job board
		if($success)
		{
			$db =& JFactory::getDBO();
			$query = "DELETE FROM ".$db->nameQuote('#__jobboard_users')." WHERE ".$db->nameQuote('user_id')." = ".$user['id'];
			$db->setQuery($query);
			return $db->query();
		}
	}

	function onUserAfterDelete($user, $success, $msg)
	{
		//if user was deleted from Joomla successfully, then delete it from Job board
		if($success)
		{
			$db =& JFactory::getDBO();
			$query = "DELETE FROM ".$db->nameQuote('#__jobboard_users')." WHERE ".$db->nameQuote('user_id')." = ".$user['id'];
			$db->setQuery($query);
			return $db->query();
		}
	}

    /**
	 * Generate Random key
	 *
	 **/
	private function _randKey(){
		return sprintf(
  				 '%04x%02x%03x'
                 ,mt_rand()
                 ,mt_rand(0, 65535)
                 ,bindec(substr_replace(sprintf('%016b', mt_rand(0, 65535)), '0100', 11, 4))
                      );
	}

    /**
	 * Generate Random string
	 *
	 **/
	private function _randStr($s){
		return sprintf(
  				 '%03x%02x', $s, mt_rand(0, 65535));
	}
}
?>