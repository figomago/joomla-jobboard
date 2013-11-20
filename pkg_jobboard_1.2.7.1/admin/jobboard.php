<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted Access');  // Access check.
if(version_compare( JVERSION, '1.7.0', 'ge' )){
  if (!JFactory::getUser()->authorise('core.manage', 'com_jobboard')) {
  	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
  }
}

JToolBarHelper::title(JText::_('COM_JOBBOARD_JOB_BOARD'), 'generic.png');
jimport('joomla.application.component.controller');
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard.php' );
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_toolbar.php' );

// Get the view and controller from the request, or set to default if they weren't set
JRequest::setVar('view', JRequest::getCmd('view','dashboard'));
JRequest::setVar('cont', JRequest::getCmd('view','dashboard')); // Get controller based on the selected view
JRequest::setVar('task', JRequest::getCmd('task','display'));
 
jimport('joomla.filesystem.file');

// Load the appropriate controller
$cont = JRequest::getCmd('cont','dashboard');
$task = JRequest::getCmd('task', 'display');
$path = JPATH_COMPONENT_ADMINISTRATOR.DS.'controllers'.DS.$cont.'.php';
$jb_version = '1.2.5';
if(JFile::exists($path))
{
	require_once($path);
    if(!version_compare( JVERSION, '1.6.0', 'ge' )){
       // Load Jobboard language file
       $lang = &JFactory::getLanguage();
       $lang->load('com_jobboard.sys', JPATH_ADMINISTRATOR);
    }
}
else
{
	// Invalid controller was passed
	JError::raiseError('500',JText::_('Unknown controller' . $path));
}
?>
