<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.controller');
JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables');

class JobboardControllerStatusEdit extends JController
{
	function save()
	{
		JRequest::checkToken() or jexit('Invalid Token');
		
        $record = JTable::getInstance('Status', 'Table');
        $post = JRequest::get('POST');
        $post['id'] = ($post['id'] <> 0)? $post['id'] : false;
        if (!$record->save($post)) {
            // uh oh failed to save
            JError::raiseError('500', JTable::getError());
        }
        
		$this->setRedirect('index.php?option=com_jobboard&view=statuses&task=save', JText::_('COM_JOBBOARD_STATUS_SAVED'));
	}

	function edit()
	{
		JToolBarHelper::save();
		JToolBarHelper::cancel();
		
		JRequest::setVar('view','statusedit');
		parent::display();
	}

	function cancel()
	{
		//reset the parameters
		JRequest::setVar('task', '');
		JRequest::setVar('view', 'statuses');

		//call up the list screen controller
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'controllers'.DS.'statuses.php');
	}
}
	
$controller = new JobboardControllerStatusEdit();
$controller->execute($task);
$controller->redirect();

?>