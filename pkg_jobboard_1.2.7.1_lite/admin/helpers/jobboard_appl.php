<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

class JobBoardApplHelper
{
    static function setFieldType($input) {
       switch($input) {
         case 'text' :
            $result = 'text';
         break;
         case 'checkbox' :
            $result = 'int';
         break;
         case 'textarea' :
            $result = 'text';
         break;
         case 'radio' :
            $result = 'int';
         break;
         case 'date' :
            $result = 'text';
         break;
         case 'select' :
            $result = 'text';
         break;
       }
       return $result;
    }

    static function jobDisabled($jid) {
      $db = &JFactory::getDBO();
      $sql = 'SELECT COUNT(`id`)
                  FROM
                    `#__jobboard_jobs`
                    WHERE `id` = '.$jid.' AND `published` = 0';
      $db->setQuery($sql);
	  return ($db->loadResult()> 0)? true : false;
    }

    static function sendEmail(&$msgobj, &$config, $to_email, $msg_type, &$messg_model=null)
     {
       //$messg_model =& $this->getModel('Message');
       JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables');
       $msg_id = $messg_model->getMsgID($msg_type);
       $msg = $messg_model->getMsg($msg_id);

       $from = $config->reply_to;
       $fromname = $config->organisation;

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

     static function processMail(&$applicant, $registered=false, &$messg_model=null){

            $config = & JTable::getInstance('Config', 'Table');
            $config->load(1);
            $dept_tbl = & JTable::getInstance('Department', 'Table');
            $dept_tbl->load($applicant->department);

            switch ($applicant->status) {
              case 6 :
                if($dept_tbl->acceptance_notify == 1) {
                    if(!$registered)
                        self::sendEmail($applicant, $config, $applicant->email, 'userapproved', $messg_model);
                    else {
                       if(self::getApplAcceptDefault($applicant->auid))
                        self::sendEmail($applicant, $config, $applicant->email, 'userapproved', $messg_model);
                    }
                }
              break;
              case 7 :
                if($dept_tbl->rejection_notify == 1) {
                    if(!$registered)
                        self::sendEmail($applicant, $config, $applicant->email, 'userrejected', $messg_model);
                    else {
                       if(self::getApplRejectDefault($applicant->auid))
                        self::sendEmail($applicant, $config, $applicant->email, 'userrejected', $messg_model);
                    }
                }
              break;
              default:
              ;break;
            }

            if ($dept_tbl->notify_admin == 1 || $dept_tbl->notify == 1) {
                $applicant->dept_name =  $dept_tbl->name;
            }
            if ($dept_tbl->notify_admin == 1) {
                self::sendEmail($applicant, $config, $config->from_mail, 'adminupdate_application', $messg_model);
            }
            if ($dept_tbl->notify == 1) {
                self::sendEmail($applicant, $config, $dept_tbl->contact_email, 'adminupdate_application', $messg_model);
            }
     }

    function getApplCV($aid) {
	    $db = & JFactory::getDBO();
        $where = ' WHERE a.`id` = '.$aid;
    	$sql = 'SELECT cv.`profile_name`
              FROM `#__jobboard_cvprofiles` AS cv
              INNER JOIN `#__jobboard_usr_applications` AS a
                  ON (a.`cvprof_id` = cv.`id`)
              '.$where;
        $db->setQuery($sql);
		return $db->loadResult();
    }

    /*
              $subject_tags = array('name'=>'jobtitle', 'replacement'=>$job_title, 'name'=>'fromname', 'replacement'=>$sender->name);

              $body_tags = array(array('name'=>'jobtitle', 'replacement'=>$job_title), array('name'=>'fromname', 'replacement'=>$sender->name), array('name'=>'toname', 'replacement'=>$recipient->name)
                              , array('name'=>'link', 'replacement'=>$link));
                               */

    private function _replaceTags($string, $tags) {
        if(!empty($string) && !empty($tags)) {
          foreach($tags as $tag){
             $string = str_replace('['.$tag["name"].']', $tag['replacement'], $string);
          }
          return $string;
        }
    }

    static function getDeptId($job_id) {
         $db = & JFactory::getDBO();
         $sql = 'SELECT '.$db->nameQuote('department').'
                  FROM '.$db->nameQuote('#__jobboard_jobs').'
                 WHERE '.$db->nameQuote('id').' = '.intval($job_id);
         $db->setQuery($sql);
         return $db->loadResult();
    }

    static function getJobTitle($job_id) {
         $db = & JFactory::getDBO();
         $sql = 'SELECT '.$db->nameQuote('job_title').'
                  FROM '.$db->nameQuote('#__jobboard_jobs').'
                 WHERE '.$db->nameQuote('id').' = '.intval($job_id);
         $db->setQuery($sql);
         return $db->loadResult();
    }

    static function getRegUser($aid) {
         $db = & JFactory::getDBO();
         $sql = 'SELECT u.'.$db->nameQuote('id').', u.'.$db->nameQuote('name').', u.'.$db->nameQuote('email').'
               FROM '.$db->nameQuote('#__users').' AS u
                  INNER JOIN '.$db->nameQuote('#__jobboard_usr_applications').' AS a
                  ON(a.'.$db->nameQuote('user_id').' = u.'.$db->nameQuote('id').')
                    WHERE a.'.$db->nameQuote('id').' = '.intval($aid);
         $db->setQuery($sql); 
         return $db->loadAssoc();
    }

    static function getSiteUser($aid) {
         $db = & JFactory::getDBO();
         $sql = 'SELECT '.$db->nameQuote('first_name').', '.$db->nameQuote('last_name').', '.$db->nameQuote('email').'
                FROM '.$db->nameQuote('#__jobboard_applicants').'
                    WHERE '.$db->nameQuote('id').' = '.intval($aid);
         $db->setQuery($sql);
         return $db->loadAssoc();
    }

    //notify_on_appl_accept
    static function getApplAcceptDefault($uid) {
         $db = & JFactory::getDBO();
         $sql = 'SELECT `notify_on_appl_accept` FROM `#__jobboard_users`
                    WHERE `user_id` = '.intval($uid);
         $db->setQuery($sql);
         return ($db->loadResult() == 1)? true : false;
    }

    static function getApplRejectDefault($uid) {
         $db = & JFactory::getDBO();
         $sql = 'SELECT `notify_on_appl_reject` FROM `#__jobboard_users`
                    WHERE `user_id` = '.intval($uid);
         $db->setQuery($sql);
         return ($db->loadResult() == 1)? true : false;
    }
}

?>