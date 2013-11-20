<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

class JobBoardJobHelper
{
    /**
	 * Get CSS class name by job type
	 *
	 **/
    public static function getClass($job_type) {
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

    public static function formatDate($date) {
      if(count($date) > 0){
        $i = 0;
          $cur_date = new JDate($date);
          $f_date = $cur_date->toFormat("%B %d, %Y");

      } return $f_date;
    }

    public static function jobPublished($job_id)  {
        $db = & JFactory::getDBO();
        $query = 'SELECT '.$db->nameQuote('published').' FROM '.$db->nameQuote('#__jobboard_jobs').'
            WHERE '.$db->nameQuote('id').' = '.$job_id;  
        $db->setQuery($query);
        return ($db->loadResult() == 1)? true : false;
    }
                         
}

?>