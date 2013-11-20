<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

   // Protect from unauthorized access
   defined('_JEXEC') or die('Restricted Access');

   class JobboardModelMember extends JModel
   {
       var $_id;
       var $_result;
       var $_db;
       var $_query;
       var $_sql;
       var $_countries;
       var $_config;
       var $_edlevels;

     /**
     * Constructor, builds object
     *
     */
       function __construct()
       {
         parent :: __construct();
       }

     /**
     * Get group settings for user
     * @params user id
     *
     * @return assoc
     */
     function getUserCred($uid) {
           $db = & $this->getDBO();
           $sql = 'SELECT jg.*
                         ,ju.'.$db->nameQuote('user_status').'
                  FROM '.$db->nameQuote('#__jobboard_usr_groups').' AS jg
                  INNER JOIN '.$db->nameQuote('#__jobboard_users').' AS ju
                  ON(ju.'.$db->nameQuote('group_id').' = jg.'.$db->nameQuote('id').')
                  WHERE ju.'.$db->nameQuote('user_id').' =  '.$uid;
           $db->setQuery($sql);
           return $db->loadAssoc();
     }

     /**
     * Get default dashboard setting
     * @params user id
     *
     * @return integer
     */
     function getDashConfig($user_id) {
           $db = & $this->getDBO();
           $sql = 'SELECT '.$db->nameQuote('login_dashboard').' FROM '.$db->nameQuote('#__jobboard_users').'
                      WHERE '.$db->nameQuote('user_id').' = '.$user_id;
           $db->setQuery($sql);
           return $db->loadResult();
     }

     /**
     * Get default mode switch button setting
     * @params $user_id user id
     *
     * @return integer
     */
     function getModeswitchConfig($user_id) {
           $db = & $this->getDBO();
           $sql = 'SELECT '.$db->nameQuote('show_modeswitch').' FROM '.$db->nameQuote('#__jobboard_users').'
                      WHERE '.$db->nameQuote('user_id').' = '.$user_id;
           $db->setQuery($sql);
           return $db->loadResult();
     }

     /**
     * Enable/disable user account
     * @params user id, new status
     *
     * @return boolean
     */
     function toggleUserStatus($jid, $new_status)  {
             $db = & $this->getDBO();
             $sql = 'UPDATE '.$db->nameQuote('#__jobboard_users').' SET '.$db->nameQuote('user_status').' = '.$new_status.'
                     WHERE '.$db->nameQuote('id').' = '.$jid;
             $db->setQuery($sql);
             return $db->Query();
     }

     /**
     * Check if user can feature jobs
     * @param $uid user id
     *
     * @return integer
     */
      function canFeature($uid)  {
          $db = & JFactory::getDBO();
          $query = 'SELECT '.$db->nameQuote('feature_jobs').' FROM '.$db->nameQuote('#__jobboard_users').'
              WHERE '.$db->nameQuote('user_id').' = '.$uid;
          $db->setQuery($query);
          return $db->loadResult();
      }

     /**
     * Check if user is allowed to access Job Board
     * @param $uid user id
     *
     * @return integer
     */
      function isEnabled($uid)  {
          $db = & JFactory::getDBO();
          $query = 'SELECT '.$db->nameQuote('user_status').' FROM '.$db->nameQuote('#__jobboard_users').'
              WHERE '.$db->nameQuote('user_id').' = '.$uid;
          $db->setQuery($query);
          $result = $db->loadResult();
          return (!empty($result) && $result == 1)? true : false;
      }
}

?>