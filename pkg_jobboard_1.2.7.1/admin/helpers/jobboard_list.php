<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

class JobBoardListHelper
{
    /**
	 * Get CSS class name by job type
	 *
	 **/
    function getClass($job_type) {
    	 switch($job_type){
    		 case 'COM_JOBBOARD_DB_JFULLTIME' :
    			 return 'full-time';
    		 break;
    		 case 'COM_JOBBOARD_DB_JPARTTIME' :
    			return 'part-time';
    		 break;
    		 case 'COM_JOBBOARD_DB_JCONTRACT' :
    			return 'contract';
    		 break;
    		 case 'COM_JOBBOARD_DB_JTEMP' :
    			return 'temporary';
    		 break;
    		 case 'COM_JOBBOARD_DB_JINTERN' :
    			return 'internship';
    		 break;
    		 case 'COM_JOBBOARD_DB_JOTHER' :
    			return 'other';
    		 break;
        }
     }

    function rssEnabled() {
        $db = & JFactory::getDBO();
        $query = 'SELECT '.$db->nameQuote('show_rss').' FROM '.$db->nameQuote('#__jobboard_config').'
            WHERE '.$db->nameQuote('id').' = 1';
        $db->setQuery($query);
        return ($db->loadResult() == 1)? true : false;
    }
}

?>