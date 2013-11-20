<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');
jimport('joomla.application.component.controller');

class JobboardControllerMessageEdit extends JController
{
	function save()
	{
		JRequest::checkToken() or jexit('Invalid Token');

		$row =& JTable::getInstance('Messages', 'Table');
		
		if (!$row->bind(JRequest::get('post')))
		{
			JError::raiseError(500, $row->getError());
		}
		$row->type = JRequest::getVar('type','','post','string');
		$row->subject = JRequest::getVar('subject','','post','string');
		$row->body = JRequest::getVar('body','','post','string');
		
		if(!$row->store())
		{
			JError::raiseError(500, $row->getError());
		}
		
		$this->setRedirect('index.php?option=com_jobboard&view=messages&task=save', JText::_('COM_JOBBOARD_EML_SAVED'));
	}
	
	function edit()
	{
		JToolBarHelper::save();
		JToolBarHelper::cancel();
		
		JRequest::setVar('view','messageedit');
		parent::display();
	}

	function cancel()
	{
		
		//call up the list screen controller
		$this->setRedirect('index.php?option=com_jobboard&view=messages&cid[]=');
	}
}
	
$controller = new JobboardControllerMessageEdit();
$controller->execute($task);
$controller->redirect();
