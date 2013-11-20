<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class JobboardModelUnsolicited extends JModel
{
	var $_data = null;
    var $_id = null;
    var $_option = null;

	function getJobData($id)    {
           $db = & JFactory :: getDBO();
           $sql = 'SELECT
                       j.id
                      , j.post_date
                      , j.job_title
                      , j.job_type
                      , j.country
                      , j.salary
                      , jc.country_name
                      , jc.country_region
                      , cl.description AS job_level
                      , j.positions
                      , j.city
                      , j.num_applications
                      , e.level AS education
                  FROM
                      #__jobboard_jobs AS j
                      INNER JOIN #__jobboard_categories  AS c
                          ON (j.category = c.id)
                      INNER JOIN #__jobboard_career_levels AS cl
                          ON (j.career_level = cl.id)
                      INNER JOIN #__jobboard_education AS e
                          ON (e.id = j.education)
                      INNER JOIN #__jobboard_countries AS jc
                          ON (j.country = jc.country_id)
                      WHERE j.id = ' . $id;
           $db->setQuery($sql);
           return $db->loadObject();
       }                                 
}
?>