<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

require_once JPATH_SITE.'/components/com_jobboard/router.php';
jimport('joomla.application.component.helper');

  if (!JComponentHelper::isEnabled('com_jobboard', true))
  {
      JError::raiseError('Component not found or not enabled', JText('MOD_JOBBOARD_JOBFILTER_COMP_NOTFOUND'));
  }

  $app = & JFactory::getApplication();

  $locsrch = JString::trim($app->getUserStateFromRequest("com_jobboard.locsrch", 'locsrch', '', 'string'));
  $locsrch = (strpos($locsrch, '(') === 0)? '' : JString::strtolower($locsrch);

  $search = JString::trim($app->getUserStateFromRequest("com_jobboard.jobsearch", 'jobsearch', '', 'string') );
  $search = (strpos($search, '(') === 0)? '' : JString::strtolower($search);

  $keysrch = JString::trim($app->getUserStateFromRequest("com_jobboard.keysrch", 'keysrch', '', 'string') );
  $keysrch = (strpos($keysrch, '(') === 0)? '' : JString::strtolower($keysrch);

  if($locsrch != '') {
         // do nothing
  } else {

    require_once(dirname(__FILE__).DS.'helper.php');
    $app = & JFactory::getApplication();

    $selcat = $app->getUserStateFromRequest("com_jobboard.list.selcat", 'selcat', 1, 'int');
    $selcat = $selcat == 0? 1 : $selcat;

    $filter_job_type = $app->getUserStateFromRequest("com_jobboard.filter_job_type", 'filter_job_type', array(), 'array');
    $filter_careerlvl = $app->getUserStateFromRequest("com_jobboard.filter_careerlvl", 'filter_careerlvl', array(), 'array');
    $filter_edulevel = $app->getUserStateFromRequest("com_jobboard.filter_edulevel", 'filter_edulevel', array(), 'array');

    $job_types_arr = array(0 =>'COM_JOBBOARD_DB_JFULLTIME', 1 => 'COM_JOBBOARD_DB_JPARTTIME', 2 => 'COM_JOBBOARD_DB_JCONTRACT', 3 => 'COM_JOBBOARD_DB_JTEMP', 4 => 'COM_JOBBOARD_DB_JINTERN', 5 => 'COM_JOBBOARD_DB_JOTHER');

    $date_ranges = array(array('value'=>1, 'name'=>'MOD_JOBBOARD_FILTER_TODAY'), array('value'=>2, 'name'=>'MOD_JOBBOARD_FILTER_YESTERDAY'), array('value'=>3, 'name'=>'MOD_JOBBOARD_FILTER_LAST_X_DAYS'),  array('value'=>7, 'name'=>'MOD_JOBBOARD_FILTER_LAST_X_DAYS'), array('value'=>14, 'name'=>'MOD_JOBBOARD_FILTER_LAST_X_DAYS'), array('value'=>30, 'name'=>'MOD_JOBBOARD_FILTER_LAST_X_DAYS'), array('value'=>60, 'name'=>'MOD_JOBBOARD_FILTER_LAST_X_DAYS') );
    $date_range = $app->getUserStateFromRequest("com_jobboard.daterange", 'daterange', 0, 'int');

	$country_id = $app->getUserStateFromRequest('com_jobboard.list.country_id','country_id', 0, 'int');

    $use_location = modJobboardJobfilterHelper::_getLocConfig();
    $data =& modJobboardJobfilterHelper::getItems($params, $selcat, $filter_job_type, $job_types_arr, $filter_careerlvl, $filter_edulevel, $date_range, $date_ranges);

    unset($date_ranges);

    $title_deflt = JText::_('MOD_JOBBOARD_FILTER_LABEL_JOB_TITLE_SRCH'); $keysrch_deflt = JText::_('MOD_JOBBOARD_FILTER_LABEL_KEYWD_SRCH');

    $limit = $params->get('limit', 5); 

   if(version_compare(JVERSION,'1.6.0','ge')) {
        $js_seg = '_13x';
    } elseif(version_compare(JVERSION,'1.5.0','ge')) {
        $js_seg = '';
    }
  }

  jimport('joomla.environment.browser');
  $document =& JFactory::getDocument();
  $browser =& JBrowser::getInstance();
                                           
  require(JModuleHelper::getLayoutPath('mod_jobboard_jobfilter'));

?>