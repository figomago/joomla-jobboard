<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

   // Protect from unauthorized access
   defined('_JEXEC') or die('Restricted Access');

   class JobboardModelConfig extends JModel
   {

     /**
     * Address ID
     *
     * @var int
     */
       var $_id;


     /**
     * Address action result
     *
     * @var boolean
     */
       var $_result;

     /**
     * Constructor, builds object and determines the ID  (always set to 1)
     *
     */
       function __construct()
       {
         parent :: __construct();

         $id = 1;
         $this->setId($id);
       }

     /**
     * Initialise the ID and data
     *
     * @param integer ID
     */
       function setId($id)
       {
         $this->_id = $id;
         $this->_result = null;
       }

       function getConfig() {
           $db = & $this->getDBO();
           $sql = 'SELECT * FROM '.$db->nameQuote('#__jobboard_config').'
                      WHERE id = ' . $this->_id . '';
           $db->setQuery($sql);
           $this->_result = $db->loadObject();
           return $this->_result;
       }
       
       function getApplConfig() {
           $db = & $this->getDBO();
           $sql = 'SELECT long_date_format FROM '.$db->nameQuote('#__jobboard_config').'
                      WHERE id = 1';
           $db->setQuery($sql);
           $this->_result = $db->loadObject();
           return $this->_result;
       }

       function getDepts() {
           $db = & $this->getDBO();
           $sql = 'SELECT * FROM '.$db->nameQuote('#__jobboard_departments');
           $db->setQuery($sql);
           $this->_result = $db->loadObjectlist();
           return $this->_result;
       }

       function getCountries() {
           $db = & $this->getDBO();
           $sql = 'SELECT country_id, country_name FROM '.$db->nameQuote('#__jobboard_countries');
           $db->setQuery($sql);
           $this->_result = $db->loadObjectlist();
           return $this->_result;
       }

       function getJobtypes() {
           $db = & $this->getDBO();
           $sql = 'SELECT id, type FROM '.$db->nameQuote('#__jobboard_types');
           $db->setQuery($sql);
           $this->_result = $db->loadObjectlist();
           return $this->_result;
       }

       function getCareers() {
           $db = & $this->getDBO();
           $sql = 'SELECT * FROM '.$db->nameQuote('#__jobboard_career_levels');
           $db->setQuery($sql);
           $this->_result = $db->loadObjectlist();
           return $this->_result;
       }

       function getEdu() {
           $db = & $this->getDBO();
           $sql = 'SELECT * FROM '.$db->nameQuote('#__jobboard_education');
           $db->setQuery($sql);
           $this->_result = $db->loadObjectlist();
           return $this->_result;
       }

       function getCategories() {
           $db = & $this->getDBO();
           $sql = 'SELECT * FROM '.$db->nameQuote('#__jobboard_categories');
           $db->setQuery($sql);
           $this->_result = $db->loadObjectlist();
           return $this->_result;
       }

       function getUserGroups() {
           $db = & $this->getDBO();
           $sql = 'SELECT * FROM '.$db->nameQuote('#__jobboard_usr_groups');
           $db->setQuery($sql);
           return $db->loadObjectlist();
       }

       function saveUserGroup($data) {
           $db = & $this->getDBO();
           $sql = 'UPDATE '.$db->nameQuote('#__jobboard_usr_groups').'
                SET '.$db->nameQuote('group_name').' = '.$db->Quote($data['group_name']).',
                   '.$db->nameQuote('post_jobs').' = '.$data['post_jobs'].',
                   '.$db->nameQuote('manage_jobs').' = '.$data['manage_jobs'].',
                   '.$db->nameQuote('apply_to_jobs').' = '.$data['apply_to_jobs'].',
                   '.$db->nameQuote('manage_applicants').' = '.$data['manage_applicants'].',
                   '.$db->nameQuote('search_cvs').' = '.$data['search_cvs'].',
                   '.$db->nameQuote('search_private_cvs').' = '.$data['search_private_cvs'].',
                   '.$db->nameQuote('create_questionnaires').' = '.$data['create_questionnaires'].',
                   '.$db->nameQuote('manage_questionnaires').' = '.$data['manage_questionnaires'].'
           WHERE '.$db->nameQuote('id').' = '.$data['id'];
           $db->setQuery($sql);
           return $db->Query();
       }

      /**
      * Get scheduled tasks from DB
      * @param none
      *
      * @return assoc scheduled tasks
      */
      function getSchedule(){
          $db = & JFactory::getDBO();
          $sql = "SELECT *
                      FROM ". $db->nameQuote('#__jobboard_sched_tasks'). "
                      WHERE ". $db->nameQuote('enabled'). " = 1";
          $db->setQuery($sql);
    	  return $db->loadAssoc();
      }

      /**
      * Get table structure
      * @param $table_name
      *
      * @return assoc table structure
      */
      function getTblStructure($table_name){
          $db = & JFactory::getDBO();
          $sql = "SHOW COLUMNS
                      FROM ". $db->nameQuote($table_name);
          $db->setQuery($sql);
    	  return $db->loadAssocList();
      }
}

?>