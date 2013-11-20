<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

/**
 * Job Board Plugin
 *
 * @package     Joomla
 * @subpackage  JFramework
 * @since       1.5
 */

class plgSystemJobboard extends JPlugin {

     private $_itemid;

    /**
     * Constructor
     * @param object $subject The object to observe
     * @param   array  $config  An array that holds the plugin configuration
     * @since 1.5
     */
	function plgSystemJobboard(&$subject, $config) {

		parent::__construct($subject, $config);
        $lang =& JFactory::getLanguage();
        $lang->load('plg_jobboard', JPATH_ADMINISTRATOR);
        $this->_itemid = JRequest::getInt('Itemid');
	}

	function onCallLinkedInApi($action=array()) {
	    $credentials = array();

        $credentials['key'] = $action['linkedin_key'];
        $credentials['secret'] = $action['linkedin_secret'];

        if(!empty($action) && ($credentials['key'] <> '' && $credentials['secret'] <> '' && $action['allow_linkedin_imports'] == 1))
          return self::_startLinkedinHandshake($action['type'], &$credentials);
	}

    function oauth_session_exists() {
      $session =& JFactory::getSession();
      $oauth_insession = ($session->get('oauth'))? true : false;
        if(!$oauth_insession){
           $session_oauth = array();
           $session->set('oauth', $session_oauth);
        }
      return $session;
    }

    private function _startLinkedinHandshake($req_type, $credentials) {
        $session =  self::oauth_session_exists();
        $app = JFactory::getApplication();
        require_once(dirname(__FILE__).DS.'jobboard'.DS.'lib'.DS.'linkedin'.DS.'linkedin_3.1.1.class.php');

        $API_CONFIG = array(
          'appKey'       => $credentials['key'],
      	  'appSecret'    => $credentials['secret'],
      	  'callbackUrl'  => null
        );

        switch($req_type) {
          case 'initiate':

            $API_CONFIG['callbackUrl'] = JURI::root().'index.php?option=com_jobboard&view=user&task=getlinkedinprof&' . $req_type . '=initiate&' . LINKEDIN::_GET_RESPONSE . '=1&Itemid='.$this->_itemid;

            $OBJ_linkedin = new LinkedIn($API_CONFIG);
            $_GET[LINKEDIN::_GET_RESPONSE] = (isset($_GET[LINKEDIN::_GET_RESPONSE])) ? $_GET[LINKEDIN::_GET_RESPONSE] : '';

            if(!$_GET[LINKEDIN::_GET_RESPONSE]) {
              $response = $OBJ_linkedin->retrieveTokenRequest();
              if($response['success'] === TRUE) {

                  $session_oauth = $session->get('oauth');
                  $session_oauth['oauth']['linkedin']['request'] = $response['linkedin'];
                  $session->set('oauth', $session_oauth);

                  $app->redirect(LINKEDIN::_URL_AUTH . $response['linkedin']['oauth_token']);

              } else {
                    $msg = JText::_('PLG_JOBBOARD_REQUEST_TOKEN_RETRIEVAL_FAILED');
                    $app->redirect('index.php?option=com_jobboard&view=user&task=addcv&Itemid='.$this->_itemid, $msg, 'error');
              }

            } else {
                self::_processResponse(&$OBJ_linkedin);
            }
            return self::_getLinkedInProfile(&$OBJ_linkedin);

          break;

          case 'revoke':

            $session_oauth = $session->get('oauth');
            $OBJ_linkedin = new LinkedIn($API_CONFIG);
            $OBJ_linkedin->setTokenAccess($session_oauth['oauth']['linkedin']['access']);
            $response = $OBJ_linkedin->revoke();
            if($response['success'] === TRUE) {

              if($session->clear('oauth')) {
                  $msg = JText::_('PLG_JOBBOARD_AUTH_REVOKE_SUCCESS');
                  $msg_type = 'Message';
              } else {
                    $msg = JText::_('PLG_JOBBOARD_SESSION_CLEAR_FAILED');
                    $msg_type = 'error';
              }
            } else {
                    $msg = JText::_('PLG_JOBBOARD_AUTH_REVOKE_FAILED');
                    $msg_type = 'error';
            }
            $app->redirect('index.php?option=com_jobboard&view=user&task=addcv&Itemid='.$this->_itemid, $msg, $msg_type);

          break;
          default:
          ;break;
          }
      }

      private function _processResponse($OBJ_linkedin){
          $session =  self::oauth_session_exists();
          $app = JFactory::getApplication();

          if(JRequest::getString('oauth_problem') == 'user_refused') {
             return $app->redirect('index.php?option=com_jobboard&view=user&task=addcv&Itemid='.$this->_itemid, JText::_('COM_JOBBOARD_IMPORTLINKEDINCANCELLED'));
          }

          $session_oauth = $session->get('oauth');
          $response = $OBJ_linkedin->retrieveTokenAccess($session_oauth['oauth']['linkedin']['request']['oauth_token'], $session_oauth['oauth']['linkedin']['request']['oauth_token_secret'], $_GET['oauth_verifier']);

          if($response['success'] === TRUE) {

            $session_oauth['oauth']['linkedin']['access'] = $response['linkedin'];
            $session_oauth['oauth']['linkedin']['authorized'] = TRUE;
            $session->set('oauth', $session_oauth);

          } else {
            $msg = JText::_('PLG_JOBBOARD_ACCESS_TOKEN_RETRIEVAL_FAILED');
            return $app->redirect('index.php?option=com_jobboard&view=user&task=addcv&Itemid='.$this->_itemid, $msg, 'error');
          }
      }

      private function _getLinkedInProfile($OBJ_linkedin) {

          $response = $OBJ_linkedin->profile('~:(id,first-name,last-name,summary,educations,positions,skills)');

          if($response['success'] === TRUE) {
            $profile = new SimpleXMLElement($response['linkedin']);
            $profile_json =  json_encode($profile);
            $profile_assoc = array_unique(json_decode($profile_json, TRUE), SORT_REGULAR);
            $profile_assoc['response'] = $response['success'];
          } else {
            $app = JFactory::getApplication();
            $msg = JText::_('PLG_JOBBOARD_PROFILE_RETRIEVAL_FAILED');
            return $app->redirect('index.php?option=com_jobboard&view=user&task=addcv&Itemid='.$this->_itemid, $msg, 'error');
          }

        return $profile_assoc;
      }

      function doOauth($url) {
          $app = JFactory::getApplication();

          $app->redirect($url);
      }

      function onSendInvite($details) {
        if(!empty($details)){
          if(!isset($details['type'])){
            $details['type'] = 'userinvite';
          }
          self::_sendEmailToUser($details['type'], $details);
        }
      }

      private function _sendEmailToUser($type, $data) {

        require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_message.php' );
        JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables');

        $msg = JTable::getInstance('Messages', 'Table');
        $msg_id = JobBoardMessageHelper::getMsgId($type);
		$msg->load($msg_id);

        $sender = JobBoardMessageHelper::getUser($data['uid']);
        $recipient = JobBoardMessageHelper::getUser($data['sid']);
        $mail_invites = JobBoardMessageHelper::mailInvites($data['sid']);
		$subject = $msg->subject;
		$body = $msg->body;
        $job_title = '';

        if($type == 'userinvite' || $type == 'adminvite') {
          $db = & JFactory::getDBO();
          $query = 'SELECT '.$db->nameQuote('job_title').' FROM '.$db->nameQuote('#__jobboard_jobs').' WHERE '.$db->nameQuote('id').' = '.$data['jid'];
          $db->setQuery($query);
          $job_title = $db->loadResult();
        }

        switch($type) {
          case  'userinvite' :
              if($mail_invites) {
                $profile_name = JobBoardMessageHelper::getProfName($data['cpid']);
                $link = JRoute::_('index.php?option=com_jobboard&view=job&id='.$data['jid'].'&Itemid='.$this->_itemid, false, 1);

                $subject_tags = array(array('name'=>'jobtitle', 'replacement'=>$job_title));

                $body_tags = array(array('name'=>'jobtitle', 'replacement'=>$job_title), array('name'=>'message', 'replacement'=>$data['message']), array('name'=>'fromname', 'replacement'=>$sender['name']), array('name'=>'toname', 'replacement'=>$recipient['name'])
                                , array('name'=>'link', 'replacement'=>$link), array('name'=>'cvprofile', 'replacement'=>$profile_name));
              } else return;
          break;
          case  'adminvite' :
              $link = JRoute::_('index.php?option=com_jobboard&view=admin&task=jobs&Itemid='.$this->_itemid, false, 1);

              $subject_tags = array(array('name'=>'jobtitle', 'replacement'=>$job_title), array('name'=>'fromname', 'replacement'=>$sender['name']));

              $body_tags = array(array('name'=>'jobtitle', 'replacement'=>$job_title), array('name'=>'fromname', 'replacement'=>$sender['name']), array('name'=>'toname', 'replacement'=>$recipient['name'])
                              , array('name'=>'link', 'replacement'=>$link));
          break;
          default:
          ;break;
        }

        if($type == 'userinvite' && !$mail_invites)  return;
        $subject = self::_replaceTags($subject, $subject_tags);
        $body = self::_replaceTags($body, $body_tags);

        return JobBoardMessageHelper::dispatchEmail($sender['email'], $sender['name'], $recipient['email'], $subject, $body);
	}

    function onJobboardRegister($data) {

        if(!isset($data['user']['goto_board'])) return;
        if($data['user']['goto_board'] <> 1) return;

        require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_message.php' );

        $uri = JURI::getInstance();
        $hostname = $uri->getHost();

        $system_config = JobBoardMessageHelper::getMsgConfig();
        $emailSubject = JText::sprintf(
            'PLG_JOBBOARD_REG_SUBJECT',
            $system_config['organisation']
        );

        $send_success =  array('user'=>false, 'admin'=>false);
        $body_type = $body_activation_link = null;

        if($data['user']['must_activate'] == 1) {
            $body_type = 'PLG_JOBBOARD_REG_EMAIL_ACTIVATE';
            if(version_compare(JVERSION,'1.6.0','ge'))  {
                $body_activation_link = JUri::base().'index.php?option=com_users&task=registration.activate&token='.$data['user']['activation'];
            } else
                $body_activation_link = JUri::base().'index.php?option=com_user&task=activate&activation='.$data['user']['activation'];
        } else {
            $body_type = 'PLG_JOBBOARD_REG_EMAIL';
            $body_activation_link = '';
        }

        $emailBody = JText::sprintf(
            $body_type,
            $data['user']['name'],
            $system_config['organisation'],
            $body_activation_link
        );

        $emailBody .= $system_config['organisation'].' - '.$hostname;
        if($system_config['notify_admin'] == 1) {
            $usertype = $data['user']['is_empl'] <> 1? 'PLG_JOBBOARD_REG_JOBSEEKER' : 'PLG_JOBBOARD_REG_EMPLOYER';
            $adm_subject = JText::sprintf(
                'PLG_JOBBOARD_REG_ADM_SUBJECT',
                JText::_($usertype),
                $data['user']['username']
            );

            $adm_body = JText::sprintf(
               'PLG_JOBBOARD_ADMIN_REG_EMAIL',
                $system_config['organisation'],
                $data['user']['name'],
                $data['user']['email'],
                $data['user']['username'],
                JText::_($usertype)
            );
            $adm_body .= $system_config['organisation'].' - '.$hostname;
            $send_success['admin'] = JobBoardMessageHelper::dispatchEmail($system_config['reply_to'], $system_config['organisation'], $system_config['from_mail'], $adm_subject, $adm_body);
        } else $send_success['admin'] = true;

        $send_success['user'] = JobBoardMessageHelper::dispatchEmail($system_config['reply_to'], $system_config['organisation'], $data['user']['email'], $emailSubject, $emailBody);

        if(!$send_success['user'] || !$send_success['admin']) {
           //email send failure. notify admin? (will think it over)
        }
    }

    private function _replaceTags($string, $tags) {
        if(!empty($string) && !empty($tags)) {
          foreach($tags as $tag){
             $string = str_replace('['.$tag['name'].']', $tag['replacement'], $string);
          }
          return $string;
        }
    }
}
?>