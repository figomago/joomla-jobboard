<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');


require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_guest.php' );
jimport('joomla.application.component.view');

class JobboardViewUnsolicited extends JView
{
	function display($tpl = null)
	{
	    $app= JFactory::getApplication();
 	   	$this->_addScripts();
		$this->assign('setstate', JobBoardHelper::renderJobBoard());
        $this->assign('errors', JRequest::getVar('errors',0));
        $this->itemid = JRequest::getInt('Itemid');
        $retries = $app->getUserState('com_jobboard.member.retry', 0, 'int');
        $this->retries = $retries;

        $document =& JFactory::getDocument();
        $document->setTitle(JText::_('SUBMIT_YOUR_CV_RESUME'));

        if($this->errors == 1)
          $this->fields = $app->getUserState('com_jobboard.fields', null);

        $this->user_entry_point = 'com_users';
        if(version_compare(JVERSION,'2.5.0','ge') || version_compare(JVERSION,'1.7.0','ge') || version_compare(JVERSION,'1.6.0','ge'))
            $this->user_entry_point = 'com_users';
        elseif(version_compare(JVERSION,'1.5.0','ge'))
            $this->user_entry_point = 'com_user';

		parent::display($tpl);
	}

	function _addScripts()
	{
	    JHTML::_('behavior.mootools');
        jimport('joomla.environment.browser');
        $document =& JFactory::getDocument();
        $browser =& JBrowser::getInstance();
        if(is_int(strpos($browser->getBrowser(), 'msie')))
            $document->addStyleSheet('components/com_jobboard/css/base_ie.css');

	    $document->addScript('components/com_jobboard/js/submit.js');
	    $document->addScript('components/com_jobboard/js/user_login.js');
	}
	
}

?>