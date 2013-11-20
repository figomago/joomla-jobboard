<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

class JobBoardInviteHelper
{
    function hasInvite($uid, $jid) {
	    $db = & JFactory::getDBO();
        $where = ' WHERE `job_id` = '.$jid.' AND `user_id` = '.$uid;
    	$sql = 'SELECT COUNT(`id`)
              FROM `#__jobboard_invites`
              '.$where;
        $db->setQuery($sql);
		return ($db->loadResult() > 0)? true : false;
    }

    function hasResponded($uid, $jid) {
	    $db = & JFactory::getDBO();
        $where = ' WHERE `job_id` = '.$jid.' AND `user_id` = '.$uid;
    	$sql = 'SELECT `response`
              FROM `#__jobboard_invites`
              '.$where;
        $db->setQuery($sql);
		return ($db->loadResult() > 0)? true : false;
    }

    function getResponseDate($uid, $jid, $qid) {
	    $db = & JFactory::getDBO();
        $where = ' WHERE `job_id` = '.$jid.' AND `user_id` = '.$uid.' AND `qid` = '.$qid;
    	$sql = 'SELECT `applied_on`
              FROM `#__jobboard_usr_applications`
              '.$where;
        $db->setQuery($sql);
		return $db->loadResult();
    }

    function getInviteCV($iid) {
	    $db = & JFactory::getDBO();
        $where = ' WHERE i.`id` = '.$iid;
    	$sql = 'SELECT cv.`profile_name`
              FROM `#__jobboard_cvprofiles` AS cv
              INNER JOIN `#__jobboard_invites` AS i
                  ON (i.`cvprof_id` = cv.`id`)
              '.$where;
        $db->setQuery($sql);
		return $db->loadResult();
    }

    function mailInvites($uid) {
	    $db = & JFactory::getDBO();
        $where = ' WHERE `user_id` = '.$uid;
    	$sql = 'SELECT `email_invites`
              FROM `#__jobboard_users`
              '.$where;
        $db->setQuery($sql);
		return $db->loadResult();
    }

    function getSender($data) {
	    $db = & JFactory::getDBO();
    	$sql = 'SELECT `sender_id`
              FROM `#__jobboard_invites`
                WHERE `user_id` = '.$data['uid'].'
                 AND `job_id` = '.$data['jid'].'
                 AND `cvprof_id` = '.$data['cpid'];
        $db->setQuery($sql);
		return $db->loadResult();
    }

    function getApplId($data) {
	    $db = & JFactory::getDBO();
    	$sql = 'SELECT `id`
              FROM `#__jobboard_usr_applications`
                WHERE `user_id` = '.$data['uid'].'
                 AND `job_id` = '.$data['jid'];
        $db->setQuery($sql);  
		return $db->loadResult();
    }
}

?>