<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class JobboardViewAdmin extends JView
{
	function display($tpl = null)
	{

        $option = 'com_jobboard';
        $app = JFactory::getApplication();
        $today = JFactory::getDate();
        $this->assignRef('today', $today);
        $uri = &JURI::getInstance();
        $this->uri = $uri->getScheme().'://'.$uri->getHost().$uri->getPath();

        $this->step = isset($this->step)? $this->step : 0;
        if($this->step <> 1 && $this->step > 0)
             $this->assignRef('av_date', $today);
	    $_format = JRequest::getString('tmpl', '');
        $this->is_modal = $_format == 'component'? true : false;
        $this->_addScripts($this->step, $this->is_modal);
        if(isset($this->config)) {
          if(isset($this->config->use_location))  {
            if($this->config->use_location == 1) :
                $this->setLocationVars();
            endif;
          }
        }
        $this->setstate = JobBoardHelper::renderJobBoard();
        $this->day_format = !version_compare( JVERSION, '1.6.0', 'ge' )? '%d' : 'd';
        $this->month_long_format = !version_compare( JVERSION, '1.6.0', 'ge' )? '%B' : 'F';
        $this->month_short_format = !version_compare( JVERSION, '1.6.0', 'ge' )? '%b' : 'M';
        $this->year_format = !version_compare( JVERSION, '1.6.0', 'ge' )? '%Y' : 'Y';

        $user = & JFactory::getUser();

        $this->user_entry_point = 'com_users';
        if(version_compare(JVERSION,'2.5.0','ge') || version_compare(JVERSION,'1.7.0','ge') || version_compare(JVERSION,'1.6.0','ge'))
            $this->user_entry_point = 'com_users';
        elseif(version_compare(JVERSION,'1.5.0','ge'))
            $this->user_entry_point = 'com_user';

        $this->assignRef('user', $user);

		parent::display($tpl);
	}

    function setLocationVars(){

       $lang = & JFactory::getLanguage()->getTag();
       $lang = explode('-', $lang);
       $document =& JFactory::getDocument();
       $this->maps_online = JobBoardHelper::getSite('maps.google.com');
       if($this->maps_online)
          $document->addScript( 'http://maps.google.com/maps/api/js?v=3&amp;sensor=false&amp;language='.$lang[0] );
       $js_vars  = 'var mapSlide, windowScroll, jobMap, mapInstrctns; var presentCoords = "'.JText::_('COM_JOBBOARD_TXT_PRESENT_COORDINATES').'";';
       $js_vars  .= 'var mapDiv, focusOn, infoSpans, vMapTrigger, mapOpen; ';
       $js_vars .= "window.addEvent('domready', function(){
                        infoSpans = document.getElementById('calc_loc').getElements('span');
                     });
                     ";
       $document->addScriptDeclaration($js_vars);
    }

	function _addScripts($step, $is_modal=false)
	{
        if(!$is_modal) {
           JHTML::_('behavior.mootools');
           require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_behavior.php');
        }
        jimport('joomla.utilities.date');
        jimport('joomla.environment.browser');
        $document =& JFactory::getDocument();
        $browser =& JBrowser::getInstance();
        if(is_int(strpos($browser->getBrowser(), 'msie'))) {
            $document->addStyleSheet('components/com_jobboard/css/user_ie.css');
            if(intval($browser->getVersion()) > 7){
               $cleafix = ".clearfix {display: block;}";
               $document->addStyleDeclaration($cleafix);
            }
            $this->is_early_ie = intval($browser->getVersion()) < 7? true : false;
        }

        switch($this->context) {
          case 'questionnaire' :
              $js_vars  = 'var jbVars ={
                                  "siteRoot": "'.JURI::base().'",
                                  "defVal": "'.JText::_('COM_JOBBOARD_ENT_DEFVAL').'",
                                  "defSet": "'.JText::_('COM_JOBBOARD_ENT_DEFSETT').'",
                                  "defValSet": "'.JText::_('COM_JOBBOARD_ENT_DEFVALSETT').'",
                                  "fieldType": "'.JText::_('COM_JOBBOARD_FIELDTYPE').'",
                                  "txtSave": "'.JText::_('COM_JOBBOARD_TXTSAVE').'",
                                  "txtDone": "'.JText::_('COM_JOBBOARD_TXTDONE').'",
                                  "txtDel": "'.JText::_('COM_JOBBOARD_DELETE').'",
                                  "txtEdit": "'.JText::_('COM_JOBBOARD_EDIT').'",
                                  "txtChecked": "'.JText::_('COM_JOBBOARD_FCHECKED').'",
                                  "txtUnhecked": "'.JText::_('COM_JOBBOARD_FUNCHECKED').'",
                                  "txtAdmOnly": "'.JText::_('COM_JOBBOARD_FADMONLY').'",
                                  "txtAdmOnlyQ": "'.JText::_('COM_JOBBOARD_FADMONLYQ').'",
                                  "txtReqd": "'.JText::_('COM_JOBBOARD_ENT_REQUIRED').'",
                                  "txtOption": "'.JText::_('COM_JOBBOARD_ENT_OPTION').'",
                                  "txtSubLbl": "'.JText::_('COM_JOBBOARD_FIELDSUBLBL').'",
                                  "txtMulti": "'.JText::_('COM_JOBBOARD_FIELDMULTI').'",
                                  "currDay":'.$this->today->toFormat("%d").',
                                  "currMonth":'.$this->today->toFormat("%m").',
                                  "currYear": '.$this->today->toFormat("%Y").',
                                  "months": '.json_encode($this->months).',
                                  "txtIncl": "'.JText::_('COM_JOBBOARD_QINCLUDE').'",
                                  "txtInclday": "'.JText::_('COM_JOBBOARD_QDAY').'",
                                  "txtInclmonth": "'.JText::_('COM_JOBBOARD_QMO').'",
                                  "txtYear": "'.JText::_('COM_JOBBOARD_QYR').'"
                                  };';
              $document->addScriptDeclaration($js_vars);
          break;
          case 'jobs' :
                  $pagination =& $this->get('Pagination');
                  $this->assignRef('pagination', $pagination);
                  $results_count =  $this->pagination->getResultsCounter();
                  $this->assignRef('results_count', $results_count);
          break;
          case 'questionnaires' :
                  $pagination =& $this->get('Pagination');
                  $this->assignRef('pagination', $pagination);
                  $results_count =  $this->pagination->getResultsCounter();
                  $this->assignRef('results_count', $results_count);
          break;
          case 'applications' :
                  $data = & $this->get('Data');
                  $pagination =& $this->get('Pagination');
                  $this->assignRef('pagination', $pagination);
                  $results_count =  $this->pagination->getResultsCounter();
                  $this->assignRef('results_count', $results_count);
                  $this->assignRef('data', $data);
          break;
          case 'editjob' :
                  $job_types = array('COM_JOBBOARD_DB_JFULLTIME', 'COM_JOBBOARD_DB_JCONTRACT', 'COM_JOBBOARD_DB_JPARTTIME', 'COM_JOBBOARD_DB_JTEMP', 'COM_JOBBOARD_DB_JINTERN', 'COM_JOBBOARD_DB_JOTHER');
                  $this->assignRef('job_types', $job_types);
                  $js_vars  = 'var jobForm;';
                  $js_vars .= "window.addEvent('domready', function(){
                                  jobForm = document.forms['jobForm_b'];
                               });
                               ";
                  $document->addScriptDeclaration($js_vars);
          break;
          case 'cvsrch' :
                  if($this->query_present == 1) {
                    $pagination =& $this->get('Pagination');
                    $this->assignRef('pagination', $pagination);
                    $results_count =  $this->pagination->getResultsCounter();
                    $this->assignRef('results_count', $results_count);
                  }
          	      $document->addScript('components/com_jobboard/js/cv_search.js');
          break;
             case 'invites':
                  $app = & JFactory::getApplication();
                  $cat_id = $app->getUserState('com_jobboard.list.selcat', 1);
                  $pagination =& $this->get('Pagination');
                  $this->assignRef('pagination', $pagination);
                  $results_count =  $this->pagination->getResultsCounter();
                  $this->assignRef('results_count', $results_count);
                  $this->assign('cat_id', $cat_id);
             break;
          case 'invite' :
                  $pagination =& $this->get('Pagination');
                  $this->assignRef('pagination', $pagination);
                  $results_count =  $this->pagination->getResultsCounter();
                  $this->assignRef('results_count', $results_count);
          break;
        }

	}

    function check()
    {
        jimport( 'joomla.filter.output' );
       $app =& JFactory::getApplication();
       $menu =& $app->getMenu();
       $item =& $menu->getActive();
        echo '<pre>'.print_r($item, true).'</pre>'; die;
        if(empty($this->alias)) {
    	    $this->alias = $this->title;
        }
        $this->alias = JFilterOutput::stringURLSafe($this->alias);

    }
}

?>