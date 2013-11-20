<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables');

class JobboardViewJobEdit extends JView
{
	function display($tpl = null)
	{
		$cid = JRequest::getVar('cid', false, 'DEFAULT', 'array');
        if($cid){
          $id = $cid[0];
        }
        else $id = JRequest::getInt('id', 0);
        $newjob = ($id > 0)? false : true;
        if($newjob) {
            $cfigt = JTable::getInstance('Config', 'Table');
            $cfigt->load(1);
			$this->assignRef('config', $cfigt);
        }

        $lang = & JFactory::getLanguage()->getTag();
        $lang = explode('-', $lang);
        $document =& JFactory::getDocument();
        $_format = JRequest::getVar('format', '');

        jimport('joomla.environment.browser');
        $browser =& JBrowser::getInstance();
        if(is_int(strpos($browser->getBrowser(), 'msie'))) {
            if(intval($browser->getVersion()) > 7){
               $cleafix = ".clearfix {display: block;}";
               $document->addStyleDeclaration($cleafix);
            }
        }

        if($this->config->use_location == 1)  :
          if(empty($_format)) :
              $this->maps_online = JobBoardHelper::getSite('maps.google.com');
              JHTML::_('behavior.mootools');
              if($this->maps_online)
                $document->addScript( 'http://maps.google.com/maps/api/js?v=3&amp;sensor=false&amp;language='.$lang[0] );

              $js_vars  = 'var tandolin = tandolin || {}; var mapSlide, windowScroll, jobMap, mapInstrctns; var presentCoords = "'.JText::_('COM_JOBBOARD_TXT_PRESENT_COORDINATES').'";';
              $js_vars  .= 'var mapDiv, focusOn, infoSpans, vMapTrigger, mapOpen, jobForm; ';
              $js_vars .= "window.addEvent('domready', function(){
                                jobForm = document.forms['adminForm'];
                                infoSpans = document.getElementById('calc_loc').getElements('span');
                             });
                           ";
              $document->addScriptDeclaration($js_vars);
          endif;
        endif;
		$this->assign('jb_render', JobBoardHelper::renderJobBoardx());
		$this->assign('newjob', $newjob);
        $this->day_format = !version_compare( JVERSION, '1.6.0', 'ge' )? '%d' : 'd';
        $this->long_day_format = !version_compare( JVERSION, '1.6.0', 'ge' )? '%a' : 'D';
        $this->month_long_format = !version_compare( JVERSION, '1.6.0', 'ge' )? '%B' : 'F';
        $this->month_short_format = !version_compare( JVERSION, '1.6.0', 'ge' )? '%b' : 'M';
        $this->year_format = !version_compare( JVERSION, '1.6.0', 'ge' )? '%Y' : 'Y';

	    $_format = JRequest::getString('tmpl', '');
        $this->is_modal = $_format == 'component'? true : false;
        if(!$this->is_modal) {
            require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_behavior.php');
        }

		parent::display($tpl);
	}
}

?>