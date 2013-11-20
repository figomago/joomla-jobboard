<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

   // Protect from unauthorized access
   defined('_JEXEC') or die('Restricted Access');

   class JobboardModelUpload extends JModel
   {
       var $_id;


       var $_result;
       var $_session;

     /**
     * Constructor
     *
     */
       function __construct()
       {
         parent :: __construct();
       }

     /**
     * Saves Application data
     *
     * @return boolean
     */
       function saveApplication(&$fileobj, $field_array, $unsol=false)
       {                                                            
           $db = & $this->getDBO();
           $tbl = $unsol == true? $db->nameQuote('#__jobboard_unsolicited') : $db->nameQuote('#__jobboard_applicants');
           $query = 'INSERT INTO '.$tbl.'
                ('.$db->nameQuote('request_date').', '.$db->nameQuote('job_id').',
                '.$db->nameQuote('first_name').', '.$db->nameQuote('last_name').',
                '.$db->nameQuote('email').', '.$db->nameQuote('tel').',
                '.$db->nameQuote('title').', '.$db->nameQuote('filename').',
                '.$db->nameQuote('file_hash').', '.$db->nameQuote('filetype').', '.$db->nameQuote('cover_note').')
                VALUES (UTC_TIMESTAMP
                  , "'.$db->getEscaped($field_array->job_id).'"
                  , "'.$db->getEscaped($field_array->fields->first_name).'"
                  , "'.$db->getEscaped($field_array->fields->last_name).'"
                  , "'.$db->getEscaped($field_array->fields->email).'"
                  , "'.$db->getEscaped($field_array->fields->tel).'"
                  , "'.$db->getEscaped($field_array->fields->title).'"
                  , "'.$db->getEscaped($fileobj[0]).'"
                  , "'.$db->getEscaped($fileobj[1]).'"
                  , "'.$db->getEscaped($fileobj[2]).'"
                  , "'.$db->getEscaped($field_array->fields->cover_note).'")';    
           $db->setQuery($query);
           return  $db->Query();
       }


     /**
     * Saves Unsolicited Application data
     *
     * @return boolean
     */
       function saveUnsolicited(&$fileobj, $field_array)
       {
           $field_array->job_id = 0;
           return self::saveApplication($fileobj, $field_array, true);
       }

       function incrApplications($id) {
           $db = & $this->getDBO();
           $query = 'UPDATE #__jobboard_jobs SET
                num_applications =  num_applications + 1
                WHERE id='. $id;
           $db->setQuery($query);
           $this->_result = $db->Query();
        // return the save response
         return $this->_result;
       }


       function getData($id) {
           $db = & $this->getDBO();
           $sql = 'SELECT
                       j.post_date
                      , j.job_title
                      , j.job_type
                      , j.country
                      , c.id AS catid
                      , c.type AS category
                      , jc.country_name
                      , jc.country_region
                      , cl.description AS job_level
                      , j.description
                      , j.positions
                      , j.city
                      , j.num_applications
                  FROM
                      #__jobboard_jobs AS j
                      INNER JOIN #__jobboard_categories  AS c
                          ON (j.category = c.id)
                      INNER JOIN #__jobboard_career_levels AS cl
                          ON (j.career_level = cl.id)
                      INNER JOIN #__jobboard_countries AS jc
                          ON (j.country = jc.country_id)
                      WHERE j.id = ' . $id;
           $db->setQuery($sql);
           return $db->loadObject();
       }

       function getDept($job_id) {
           $department = $this->_getDeptId($job_id);
           $db = & $this->getDBO();
           $sql = 'SELECT  `name`, `contact_name`, `contact_email`, `notify`, `notify_admin` FROM #__jobboard_departments
                      WHERE `id` = '.intval($department);
           $db->setQuery($sql);
           return $db->loadObject();
       }

       function _getDeptId($job_id) {
           $db = & $this->getDBO();
           $sql = 'SELECT `department`  FROM #__jobboard_jobs
                      WHERE id = '.intval($job_id);
           $db->setQuery($sql);
           return $db->loadResult();
       }

       function getJobLocation($job_id) {
           $db = & $this->getDBO();
           $sql = 'SELECT city FROM  #__jobboard_jobs
                      WHERE id = '.intval($job_id);
           $db->setQuery($sql);
           return $db->loadResult();
       }

}

?>