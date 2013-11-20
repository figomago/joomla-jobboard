<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');

require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_job.php' );
jimport('joomla.application.component.view');

class JobboardViewShare extends JView
{
	function display($tpl = null)
	{
	    $app = JFactory::getApplication();
        $document =& JFactory::getDocument();
        $this->_addScripts();

        if($this->published)
        {
            jimport('joomla.utilities.date');

            //$this->config = & $this->get('ShareConfig', 'Config');
    		$this->assign('setstate', JobBoardHelper::renderJobBoard());

            if($this->config->use_location) {
            	$job_location = ($this->data->country_name <> 'COM_JOBBOARD_DB_ANYWHERE_CNAME')? ', '.$this->data->city : ', '.JText::_('WORK_FROM_ANYWHERE');
            } else $job_location = '';

            $ref_num = $this->data->ref_num <> ''? ' ('.JText::_('COM_JOBBOARD_ENT_REF').': '.$this->data->ref_num.')' : '';
            $document->setTitle(JText::_('EMAIL_JOB').': '.$this->data->job_title.$job_location.$ref_num );

         } else {
           $document->setTitle(JText::_('COM_JOBBOARD_JOB_DISABLED'));
        }

        $this->itemid = JRequest::getInt('Itemid');
        $this->user_entry_point = 'com_users';
        if(version_compare(JVERSION,'2.5.0','ge') || version_compare(JVERSION,'1.7.0','ge') || version_compare(JVERSION,'1.6.0','ge'))
            $this->user_entry_point = 'com_users';
        elseif(version_compare(JVERSION,'1.5.0','ge'))
            $this->user_entry_point = 'com_user';

        $retries = $app->getUserState('com_jobboard.member.retry', 0, 'int');
        $this->retries = $retries;

	    parent::display($tpl);
	}

	private function _addScripts()
	{
	    JHTML::_('behavior.mootools');
        jimport('joomla.environment.browser');
        $document =& JFactory::getDocument();
        $browser =& JBrowser::getInstance();
        if(is_int(strpos($browser->getBrowser(), 'msie')))
            $document->addStyleSheet('components/com_jobboard/css/base_ie.css');
            
	    $document->addScript('components/com_jobboard/js/user_login.js');
	}
}

?>