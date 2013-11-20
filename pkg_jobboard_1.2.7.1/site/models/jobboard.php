<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

   // Protect from unauthorized access
   defined('_JEXEC') or die('Restricted Access');

   class JobboardModelJobboard extends JModel
   {

       function getIntroConfig() {
           $db = & $this->getDBO();
           $sql = 'SELECT `id`, `home_intro_title`, `home_intro`, `home_jobs_limit` FROM `#__jobboard_config`
                      WHERE `id` = 1';
           $db->setQuery($sql);
           return $db->loadAssoc();
       }

       function getIntroCats(){
           $app = JFactory::getApplication();
           $db = & $this->getDBO();
           $sql =  '(SELECT c.`id`, COUNT(category) AS total, c.`type` AS `name`
                     FROM #__jobboard_categories AS c JOIN #__jobboard_jobs AS j
                     ON( c.id = j.`category`) WHERE c.`enabled` = 1 AND j.`published` = 1
                     GROUP BY category
                     ORDER BY total DESC LIMIT 100)
                     UNION
                    (SELECT c.`id`, 0 AS total, c.`type` AS `name`
                     FROM #__jobboard_categories AS c
                    WHERE (NOT EXISTS
                      (SELECT `id` FROM   #__jobboard_jobs AS j WHERE  j.`category` = c.`id` AND j.`published` = 1) )
                      AND c.`enabled` = 1 AND c.`id` > 1  LIMIT 100)';
           $db->setQuery($sql);
           return $db->loadAssocList();
       }

   }

?>