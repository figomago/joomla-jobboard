<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class JobboardViewJob extends JView
{
	function display($tpl = null)
	{
        $document =& JFactory::getDocument();
        $app = JFactory::getApplication();
        $this->config = & $this->get('JobConfig', 'Config');
        
	    if($this->published) {
            jimport('joomla.utilities.date');
            require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_job.php' );
            $this->assign('post_date', JobBoardJobHelper::formatDate($this->data->post_date));
    		$this->assign('setstate', JobBoardHelper::renderJobBoard());
            $extra_keywords = ( strlen($this->data->job_tags) > 1 )? ', '.$this->data->job_tags : '';

            if($this->config->use_location) {
            	$job_location = ($this->data->country_name <> 'COM_JOBBOARD_DB_ANYWHERE_CNAME')? ', '.$this->data->city : ', '.JText::_('WORK_FROM_ANYWHERE');
            } else $job_location = '';

            $ref_num = $this->data->ref_num <> ''? ' ('.JText::_('COM_JOBBOARD_ENT_REF').': '.$this->data->ref_num.')' : '';

            $params = & JComponentHelper::getParams('com_jobboard');

            $title_string = $this->data->job_title. $job_location .$ref_num;
    		$menus = &JSite::getMenu();
    		$menu = $menus->getActive();

            $uri = &JURI::getInstance();
            $this->uri = $uri->getScheme().'://'.$uri->getHost().$uri->getPath();

    		if (is_object($menu) && isset($menu->query['view']) && $menu->query['view'] == 'job' && isset($menu->query['id']) && $menu->query['id'] == $item->id) {
    			$menu_params = new JParameter($menu->params);
    			if (!$menu_params->get('page_title')) {
    				$params->set('page_title', $title_string);
    			}
    		} else {
    			$params->set('page_title', $title_string);
    		}
    		$document->setTitle($params->get('page_title'));
        } else {
          $document->setTitle(JText::_('COM_JOBBOARD_JOB_DISABLED'));
        }
        $this->rformat = JRequest::getVar('format', '');
        $this->user_entry_point = 'com_users';
        $retries = $app->getUserState('com_jobboard.member.retry', 0, 'int');
        $this->retries = $retries;
        if(version_compare(JVERSION,'2.5.0','ge') || version_compare(JVERSION,'1.7.0','ge') || version_compare(JVERSION,'1.6.0','ge'))
            $this->user_entry_point = 'com_users';
        elseif(version_compare(JVERSION,'1.5.0','ge'))
            $this->user_entry_point = 'com_user';

        $this->day_format = !version_compare( JVERSION, '1.6.0', 'ge' )? '%d' : 'd';
        $this->long_day_format = !version_compare( JVERSION, '1.6.0', 'ge' )? '%a' : 'D';
        $this->month_long_format = !version_compare( JVERSION, '1.6.0', 'ge' )? '%B' : 'F';
        $this->month_short_format = !version_compare( JVERSION, '1.6.0', 'ge' )? '%b' : 'M';
        $this->year_format = !version_compare( JVERSION, '1.6.0', 'ge' )? '%Y' : 'Y';
	    $_format = JRequest::getString('tmpl', '');
        $this->is_modal = $_format == 'component'? true : false;

        $this->itemid = JRequest::getInt('Itemid');
        $this->_addScripts($this->is_modal);

	    parent::display($tpl);
	}

	private function _addScripts($is_modal=false)
	{

        if(!$is_modal) {
           JHTML::_('behavior.mootools');
        }
        
        jimport('joomla.environment.browser');
        $document =& JFactory::getDocument();
        $browser =& JBrowser::getInstance();
        if(is_int(strpos($browser->getBrowser(), 'msie'))) {
            $document->addStyleSheet('components/com_jobboard/css/base_ie.css');
            if(intval($browser->getVersion()) > 7){
               $cleafix = ".clearfix {display: block;}";
               $document->addStyleDeclaration($cleafix);
            }
        }     
	}

}

?>