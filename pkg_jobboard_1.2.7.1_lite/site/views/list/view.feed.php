<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_list.php' );
jimport( 'joomla.application.component.view');
jimport('joomla.utilities.date');

class JobboardViewList extends JView
{
      function display($tpl = null)  {

        if(!JobBoardListHelper::rssEnabled()) jexit(JText::_('COM_JOBBOARD_FEEDS_NOACCES') );

        $catid = $this->selcat;
        $keywd = $this->keysrch;
        $document =& JFactory::getDocument();
        $document->setLink(JRoute::_('index.php?option=com_jobboard&selcat='.$catid));

        // get category name
        $db =& JFactory::getDBO();
        $query = 'SELECT '.$db->nameQuote('type').' FROM '.$db->nameQuote('#__jobboard_categories').' WHERE '.$db->nameQuote('id').' = '.$catid;
        $db->setQuery($query);
        $seldesc = $db->loadResult();

        // get "show location" settings:
        $query = 'SELECT '.$db->nameQuote('use_location').' FROM '.$db->nameQuote('#__jobboard_config').' WHERE '.$db->nameQuote('id').' = 1';
        $db->setQuery($query);
        $use_location = $db->loadResult();

        // get the items to add to the feed
        $where = ($catid == 1)? '' : ' WHERE c.'.$db->nameQuote('id').' = '.$catid;

        $tag_include = strlen($keywd);
        if($tag_include > 0 && $catid == 1)  {
        	$tag_requested = $this->checkTagRequest($keywd);
        	$where .= ($tag_requested <> '')? " WHERE j.".$db->nameQuote('job_tags')." LIKE '%{$tag_requested}%' " : '';
        }

        $limit = 10;

        $where .= ' AND (DATE_FORMAT(j.expiry_date,"%Y-%m-%d") >= CURDATE() OR DATE_FORMAT(j.expiry_date,"%Y-%m-%d") = 0000-00-00) ';
        $query = 'SELECT
                      j.`id`
                      , j.`post_date`
                      , j.`job_title`
                      , j.`job_type`
                      , j.`country`
                      , c.`type` AS category
                      , cl.`description` AS job_level
                      , j.`description`
                      , j.`city`
                  FROM
                      `#__jobboard_jobs` AS j
                      INNER JOIN `#__jobboard_categories`  AS c
                          ON (j.`category` = c.`id`)
                      INNER JOIN `#__jobboard_career_levels` AS cl
                          ON (j.`career_level` = cl.`id`)
                      '.$where.'
                      ORDER BY j.`post_date` DESC LIMIT '.$limit;
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        
        $site_name = $_SERVER['SERVER_NAME'];

        if($tag_requested <> ''){

        	$document->setDescription(JText::_('JOBS_WITH').' "'.ucfirst($tag_requested).'" '.JText::_('KEYWD_TAG'));
        	$rss_title = $site_name. ': '.JText::_('JOBS_WITH').' "'.ucfirst($tag_requested).'" ';

        }else {

        	$document->setDescription(JText::_('RSS_LATEST_JOBS').': '.$seldesc );
        	$rss_title = $site_name. ': '.JText::_('RSS_LATEST_JOBS').': '.$seldesc;
        }
        
        $document->setTitle($rss_title);        

        foreach ($rows as $row)
        {
            // create a new feed item
            $job = new JFeedItem();
            // assign values to the item
            
            $job_date = new JDate($row->post_date);
            $job_pubDate = new JDate();
            
            $job->category = $row->category ;
            $job->date = $job_date->toRFC822();

            $job->description = $this->trimDescr(html_entity_decode($this->escape($row->description)), '.');
            $link = htmlentities('index.php?option=com_jobboard&view=job&id='.$row->id);

            $job->link = JRoute::_($link);

            $job->pubDate = $job_pubDate->toRFC822();
	        if($use_location) {
	        	$job_location = ($row->country <> 266)? ', '.$row->city : ', '.JText::_('WORK_FROM_ANYWHERE');
	        } else $job_location = '';

            $job->title = JText::_('JOB_VACANCY').': '.html_entity_decode($this->escape($row->job_title.$job_location.' ('.JText::_($row->job_type).')'));

            // add item to the feed
            $document->addItem($job);
        }
      }

      function checkTagRequest($keywd) {
      	$key_array = explode( ',' , $keywd);
      	return (count($key_array) == 1)? $this->escape(trim(strtolower ( $key_array[0]) ) ) : '';
      }

      function trimDescr($descr, $delim){
         $first_bit = strstr($descr, '.', true);
         $remainder = strstr($descr, '.');
         return $first_bit.'. '.strstr(substr($remainder, 1), '.', true).' ...';
      }
}
?>
