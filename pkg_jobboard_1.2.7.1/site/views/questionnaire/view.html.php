<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class JobboardViewQuestionnaire extends JView
{
	function display($tpl = null)
	{                                                    
        jimport('joomla.environment.browser');
        $document =& JFactory::getDocument();
        $browser =& JBrowser::getInstance();
        if(is_int(strpos($browser->getBrowser(), 'msie'))) {
            $document->addStyleSheet('components/com_jobboard/css/user_ie.css');
            if(intval($browser->getVersion()) > 7){
               $cleafix = ".clearfix {display: block;}";
               $document->addStyleDeclaration($cleafix);
            }
        }
        $this->month_long_format = !version_compare( JVERSION, '1.6.0', 'ge' )? '%B' : 'F';
		parent::display($tpl);
	}
}

?>