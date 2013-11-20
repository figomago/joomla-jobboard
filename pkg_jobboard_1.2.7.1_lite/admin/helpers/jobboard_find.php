<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

class JobBoardFindHelper
{
    function getUsrLoc($uid) {
	    // $app = & JFactory::getApplication();
	    $db = & JFactory::getDBO();
        $where = ' WHERE u.`user_id` = '.$uid;
    	$sql = 'SELECT u.`contact_location` as location,
                u.`contact_country` as country_id,
                c.`country_name`
              FROM `#__jobboard_users` as u
              INNER JOIN `#__jobboard_countries` as c
              ON(c.`country_id` = u.`contact_country`)
              '.$where;
        $db->setQuery($sql);
		return $db->loadAssoc();
    }

    function getEdlvls() {
		$db = & JFactory::getDBO();
		$sql = 'SELECT *
              FROM
                  #__jobboard_education
              WHERE TRUE';
		$db->setQuery($sql);
		return $db->loadAssocList();
    }

    function hasQ($string)  {
      return (strpos($string, 'quot;'))? true : false;
    }

    function removeDuplicates($arr, $duplicates) {
        foreach($duplicates as $dupl) {
           $key = array_search($dupl, $arr);
            unset($arr[$key]);
         }
         return $arr;
    }


}

?>