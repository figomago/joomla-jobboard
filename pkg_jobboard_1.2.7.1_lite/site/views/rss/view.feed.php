<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.view');
jimport('joomla.utilities.date');

class JobboardViewRss extends JView
{
      function display($tpl = null)  {

        $keywd = JString::strtolower($this->keysrch);
        $document =& JFactory::getDocument();
        $document->setLink(JRoute::_('index.php?option=com_jobboard&view=list&selcat='.$this->selcat));
        $db = &JFactory::getDBO();

        $this->use_location = & $this->get('LocConf');

        // get the items to add to the feed
        $where = ($this->selcat == 1)? '' : ' WHERE c.'.$db->nameQuote('id').' = '.$this->selcat;

        $tag_requested = '';
        $tag_include = strlen($keywd);
        
        if($tag_include > 0 && $this->selcat == 1)  {
        	$tag_requested = $this->checkTagRequest($keywd);
        	$where .= ($tag_requested <> '')? " WHERE j.".$db->nameQuote('job_tags')." LIKE '%{$tag_requested}%' " : '';
        }

        $limit = 10;

        $where .= ' AND (DATE_FORMAT(j.'.$db->nameQuote('expiry_date').',"%Y-%m-%d") >= CURDATE() OR DATE_FORMAT(j.'.$db->nameQuote('expiry_date').',"%Y-%m-%d") = 0000-00-00) ';
        $query = 'SELECT
                      j.'.$db->nameQuote('id').'
                      , j.'.$db->nameQuote('post_date').'
                      , j.'.$db->nameQuote('job_title').'
                      , j.'.$db->nameQuote('job_type').'
                      , j.'.$db->nameQuote('country').'
                      , c.'.$db->nameQuote('type').' AS category
                      , cl.'.$db->nameQuote('description').' AS job_level
                      , j.'.$db->nameQuote('description').'
                      , j.'.$db->nameQuote('city').'
                  FROM
                      '.$db->nameQuote('#__jobboard_jobs').' AS j
                      INNER JOIN '.$db->nameQuote('#__jobboard_categories').'  AS c
                          ON (j.'.$db->nameQuote('category').' = c.'.$db->nameQuote('id').')
                      INNER JOIN '.$db->nameQuote('#__jobboard_career_levels').' AS cl
                          ON (j.'.$db->nameQuote('career_level').' = cl.'.$db->nameQuote('id').')
                      '.$where.'
                      ORDER BY j.'.$db->nameQuote('post_date').' DESC LIMIT '.$limit;
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        
        $site_name = $_SERVER['SERVER_NAME'];

        if($tag_requested <> ''){

        	$document->setDescription(JText::_('JOBS_WITH').' "'.ucfirst($tag_requested).'" '.JText::_('KEYWD_TAG'));
        	$rss_title = $site_name. ': '.JText::_('JOBS_WITH').' "'.ucfirst($tag_requested).'" ';

        } else {

        	$document->setDescription(JText::_('RSS_LATEST_JOBS').': '.$this->seldesc );
        	$rss_title = $site_name. ': '.JText::_('RSS_LATEST_JOBS').': '.$this->seldesc;
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
	        if($this->use_location) {
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
