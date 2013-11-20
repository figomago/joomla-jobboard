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
JError::raiseError('Component not found or not enabled', JText('This module requires the Job Board component'));
}
 JHTML::_('behavior.mootools');
 $document =& JFactory::getDocument();
 $document->addStyleSheet('modules/mod_jobboard_joblister/css/style.css');

jimport('joomla.environment.browser');
$document =& JFactory::getDocument();
$browser =& JBrowser::getInstance();

if(is_int(strpos($browser->getBrowser(), 'msie')) && intval($browser->getVersion()) < 7) :

else :
     if(version_compare(JVERSION,'1.6.0','ge')) {
       $document->addScript('modules/mod_jobboard_joblister/js/job_lister_13x.js');
    } elseif(version_compare(JVERSION,'1.5.0','ge')) {
       $document->addScript('modules/mod_jobboard_joblister/js/job_lister.js');
    }
endif;

require_once(dirname(__FILE__).DS.'helper.php');

$limit =  $params->get('limit', 5);
$show_stopstart = $params->get('stopstart', 1);
$app= JFactory::getApplication();
$module_key = $app->getUserState('mod_jobboard.joblister.keys', 0, 'int');
$module_key++;

require(JModuleHelper::getLayoutPath('mod_jobboard_joblister'));

?>