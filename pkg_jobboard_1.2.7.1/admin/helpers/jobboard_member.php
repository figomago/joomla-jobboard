<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

class JobBoardMemberHelper
{
    function setGroupId($uid, $gid) {
      $db = &JFactory::getDBO();
      $sql = 'UPDATE '.$db->nameQuote('#__jobboard_users').'
            SET '.$db->nameQuote('group_id').' = '.$gid.'
            WHERE '.$db->nameQuote('user_id').' = '.$uid;
      $db->setQuery($sql);
	  return $db->Query();
    }

    function setUserDash($uid, $type) {
      $db = &JFactory::getDBO();
      $sql = 'UPDATE '.$db->nameQuote('#__jobboard_users').'
            SET '.$db->nameQuote('login_dashboard').' = '.$type.'
            WHERE '.$db->nameQuote('user_id').' = '.$uid;
      $db->setQuery($sql);
	  return $db->Query();
    }

   /**
   * Enable new employer to feature jobs by default
   * @param none
   *
   * @return boolean
   */
    function setFeaturePerm($uid, $val)  {
      $db = &JFactory::getDBO();
      $sql = 'UPDATE '.$db->nameQuote('#__jobboard_users').'
            SET '.$db->nameQuote('feature_jobs').' = '.$val.'
            WHERE '.$db->nameQuote('user_id').' = '.$uid;
      $db->setQuery($sql);
	  return $db->Query();
    }

    function getUserGroup($is_employer=false) {
      $db = &JFactory::getDBO();
      $_default_grp = $is_employer == true? 'default_empl_grp' : 'default_user_grp';
      $sql = 'SELECT '.$db->nameQuote($_default_grp).'
            FROM '.$db->nameQuote('#__jobboard_config').'
            WHERE '.$db->nameQuote('id').' = 1';
      $db->setQuery($sql);
	  return $db->loadResult();
    }

    function matchHumanCode($string)  {
       $app = &JFactory::getApplication();
       return (strlen($string) == 0 || $string != $app->getUserState('com_jobboard.humanv'))? false : true;
    }

    function verifyReg()  {
        $db = & JFactory::getDBO();
        $query = 'SELECT '.$db->nameQuote('captcha_reg').' FROM '.$db->nameQuote('#__jobboard_config').'
            WHERE '.$db->nameQuote('id').' = 1';
        $db->setQuery($query);
        return ($db->loadResult() == 1)? true : false;
    }

     /**
     * Check if user can feature jobs by default
     * @param $uid user id
     *
     * @return integer
     */
      function canFeature()  {
          $db = & JFactory::getDBO();
          $query = 'SELECT '.$db->nameQuote('empl_default_feature').' FROM '.$db->nameQuote('#__jobboard_config').'
              WHERE '.$db->nameQuote('id').' = 1';
          $db->setQuery($query);
          return $db->loadResult();
      }

}

?>