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
 $document->addStyleSheet('modules/mod_jobboard_jobscroller/css/style.css');
 if(version_compare(JVERSION,'2.5.0','ge') || version_compare(JVERSION,'1.7.0','ge') || version_compare(JVERSION,'1.6.0','ge')) {
    $document->addScript('modules/mod_jobboard_jobscroller/js/job_scroller_13x.js');
} elseif(version_compare(JVERSION,'1.5.0','ge')) {
    $document->addScript('modules/mod_jobboard_jobscroller/js/job_scroller.js');
}

require_once(dirname(__FILE__).DS.'helper.php');
$scroll_jobs =& modJobboardJobScrollerHelper::getItems($params);
$use_location = & modJobboardJobScrollerHelper::getConfig();
$view_limit = 31.2 * $params->get('limit', 5);
$show_stopstart = $params->get('stopstart', 1);
require(JModuleHelper::getLayoutPath('mod_jobboard_jobscroller'));

?>