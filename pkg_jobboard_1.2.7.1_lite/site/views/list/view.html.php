<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');

require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_list.php' );
jimport('joomla.application.component.view');

class JobboardViewList extends JView
{
	function display($tpl = null)
	{

        jimport('joomla.utilities.date');

        $app = JFactory::getApplication();

        $this->data =& $this->get('Data');

        $featured_count = $this->countFeatured(&$this->data);
        $locsrch = $this->escape($this->locsrch);
        if($featured_count > 0 && $locsrch <> '') {
          $this->data = $this->sortFeatured(&$this->data);
        }
        $this->config = & $this->get('Querycfg', 'Config');
        $this->jobsearch =& $this->get('Search');
        $this->jobtypes =& $this->get('JobTypes');
        $this->jobcareerlvls =& $this->get('Careerlvls');
        $this->jobedlvls =& $this->get('Edlvls');
        if($this->config->use_location == true) :
            $this->radii =& $this->get('Distances');
            $this->dist_symbol = $this->config->distance_unit == 0? JText::_('COM_JOBBOARD_DIST_METRIC') : JText::_('COM_JOBBOARD_DIST_IMPERIAL');
            $this->sel_distance = $app->getUserStateFromRequest("com_jobboard.sel_distance", 'sel_distance', $this->config->default_distance, 'int');
            $geo_address = $app->getUserState('com_jobboard.geo_address');
            $this->assign('geo_address', $geo_address);
        endif;

        $this->featured_count = $featured_count;
    	$this->filter_job_type = $app->getUserStateFromRequest("com_jobboard.filter_job_type", 'filter_job_type', array(), 'array');
    	$this->filter_careerlvl = $app->getUserStateFromRequest("com_jobboard.filter_careerlvl", 'filter_careerlvl', array(), 'array');
    	$this->filter_edulevel = $app->getUserStateFromRequest("com_jobboard.filter_edulevel", 'filter_edulevel', array(), 'array');
        $this->pagination =& $this->get('Pagination');
        $this->categories =& $this->get('Categories');
        $this->setstate = JobBoardHelper::renderJobBoard();
        $this->jobsearch = $this->escape($this->jobsearch);
        $this->keysrch = $this->escape($this->keysrch );
        $this->locsrch = $locsrch;
        $retries = $app->getUserState('com_jobboard.member.retry', 0, 'int');
        $this->retries = $retries;

        $this->user_entry_point = 'com_users';
        if(version_compare(JVERSION,'2.5.0','ge') || version_compare(JVERSION,'1.7.0','ge') || version_compare(JVERSION,'1.6.0','ge'))
            $this->user_entry_point = 'com_users';
        elseif(version_compare(JVERSION,'1.5.0','ge'))
            $this->user_entry_point = 'com_user';

        $this->_addScripts();
        $this->rss_on = JobBoardListHelper::rssEnabled();

        $document =& JFactory::getDocument();

		parent::display($tpl);
	}

    function countFeatured($jobs){
      $featured_count = 0;
      foreach($jobs as $job) {
         if($job->featured == 1)
          $featured_count++;
      }
      return $featured_count;
    }

    function sortFeatured($jobs){
      $non_featured = array();
      $featured = array();
      foreach ($jobs as $job) {
        if($job->featured == 1)
           $featured[] = $job;
        else
           $non_featured[] = $job;
      }
      return array_merge($featured, $non_featured);
    }

	function _addScripts()
	{
	    JHTML::_('behavior.mootools');
        jimport('joomla.environment.browser');
        $document =& JFactory::getDocument();
        $browser =& JBrowser::getInstance();
        if(is_int(strpos($browser->getBrowser(), 'msie')))
            $document->addStyleSheet('components/com_jobboard/css/base_ie.css');

        $js_vars  = "var uslnk = '';var titleString = '".JText::_('LABEL_JOB_TITLE_SRCH')."';var keywdString = '".JText::_('LABEL_KEYWD_SRCH')."';var locnString = '".JText::_('LABEL_LOCATION_SRCH')."';var txtLoading = '".JText::_('LOADING_TEXT')."';var txtAdvSrch = '".JText::_('COM_JOBBOARD_ADVANCED_SEARCH')."';var txtBasicSrch = '".JText::_('COM_JOBBOARD_BASIC_SEARCH')."';";
        $document->addScriptDeclaration($js_vars);
	    $document->addScript('components/com_jobboard/js/list.js');        
	}
}

?>