<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

   // Protect from unauthorized access
   defined('_JEXEC') or die('Restricted Access');

   class JobboardModelJob extends JModel
   {

     /**
     * Address ID
     *
     * @var int
     */
       var $_id;


     /**
     *
     *
     * @var boolean
     */
       var $_result;
       var $_db;
       var $_query;
       var $_sql;


     /**
     * Constructor, builds object
     *
     */
       function __construct()
       {
         parent :: __construct();

       }

       function getData($id) {
           $db = & $this->getDBO();
           $sql = 'SELECT  j.'.$db->nameQuote('id').'
                      , j.'.$db->nameQuote('post_date').'
                      , j.'.$db->nameQuote('expiry_date').'
                      , j.'.$db->nameQuote('job_title').'
                      , j.'.$db->nameQuote('posted_by').'
                      , j.'.$db->nameQuote('job_type').'
                      , j.'.$db->nameQuote('country').'
                      , j.'.$db->nameQuote('salary').'
                      , c.'.$db->nameQuote('id').' AS catid
                      , c.'.$db->nameQuote('type').' AS category
                      , jc.'.$db->nameQuote('country_name').'
                      , jc.'.$db->nameQuote('country_region').'
                      , cl.'.$db->nameQuote('description').' AS job_level
                      , j.'.$db->nameQuote('description').'
                      , j.'.$db->nameQuote('duties').'
                      , j.'.$db->nameQuote('positions').'
                      , j.'.$db->nameQuote('job_tags').'
                      , j.'.$db->nameQuote('city').'
                      , j.'.$db->nameQuote('num_applications').'
                      , j.'.$db->nameQuote('hits').'
                      , j.'.$db->nameQuote('published').'
                      , j.'.$db->nameQuote('questionnaire_id').'
                      , j.'.$db->nameQuote('ref_num').'
                      , j.'.$db->nameQuote('geo_latitude').'
                      , j.'.$db->nameQuote('geo_longitude').'
                      , j.'.$db->nameQuote('geo_state_province').'
                      , e.'.$db->nameQuote('level').' AS education
                  FROM
                      '.$db->nameQuote('#__jobboard_jobs').' AS j
                      INNER JOIN '.$db->nameQuote('#__jobboard_categories').'  AS c
                          ON (j.'.$db->nameQuote('category').' = c.'.$db->nameQuote('id').')
                      INNER JOIN '.$db->nameQuote('#__jobboard_career_levels').' AS cl
                          ON (j.'.$db->nameQuote('career_level').' = cl.'.$db->nameQuote('id').')
                      INNER JOIN '.$db->nameQuote('#__jobboard_education').' AS e
                          ON (e.'.$db->nameQuote('id').' = j.'.$db->nameQuote('education').')
                      INNER JOIN #__jobboard_countries AS jc
                          ON (j.'.$db->nameQuote('country').' = jc.'.$db->nameQuote('country_id').')
                      WHERE j.'.$db->nameQuote('id').' = ' . $id;
           $db->setQuery($sql);
           return $db->loadObject();
       }

       function getJobdata($id) {
           $db = & $this->getDBO();
           $sql = 'SELECT j.job_title 
                      , j.city
                  FROM
                      #__jobboard_jobs AS j
                      WHERE j.id = ' . $id;
           $db->setQuery($sql);
           return $db->loadObject();
       }

       function getTopfive() {
           $db = & $this->getDBO();
           $sql = 'SELECT  SELECT  j.id
                      , j.job_title
                      , j.city
                      , j.hits
                  FROM
                      #__jobboard_jobs AS j
                      ORDER BY j.hits DESC LIMIT 5';
           $db->setQuery($sql);
           return $db->loadObjectList();
       }

       function getLatestfive() {
           $db = & $this->getDBO();
           $sql = 'SELECT  j.id
                      , j.job_title
                      , j.city
                      , j.num_applications
                  FROM
                      #__jobboard_jobs AS j
                      ORDER BY j.id DESC LIMIT 5';
           $db->setQuery($sql);
           return $db->loadObjectList();
       }

       function getDefaultprefix() {
           $db = & $this->getDBO();
           $sql = 'SELECT  j.id
                      , j.job_title
                      , j.city
                      , j.num_applications
                  FROM
                      #__jobboard_jobs AS j
                      ORDER BY j.id DESC LIMIT 5';
           $db->setQuery($sql);
           return $db->loadObjectList();
       }
}

?>