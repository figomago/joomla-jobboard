<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.controller');
JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables');

class JobboardControllerDepartmentEdit extends JController
{
	function save()
	{
		JRequest::checkToken() or jexit('Invalid Token');

        $record = JTable::getInstance('Department', 'Table');
        $post = JRequest::get('POST');
        $post['id'] = ($post['id'] <> 0)? $post['id'] : false;

        if (!$record->save($post)) {
        //failed to save
         $msg = JText::_('COM_JOBBOARD_SAVE_ERR');
        } else $msg = JText::_('COM_JOBBOARD_DEPT_SAVED');

	   $this->setRedirect('index.php?option=com_jobboard&view=departments&task=save',  $msg);
	}
    
    function apply()
	{
		JRequest::checkToken() or jexit('Invalid Token');

        $record = JTable::getInstance('Department', 'Table');
        $post = JRequest::get('POST');
        $post['id'] = ($post['id'] <> 0)? $post['id'] : false;

        if (!$record->save($post)) {
        //failed to save
         $msg = JText::_('COM_JOBBOARD_SAVE_ERR');
        } else $msg = JText::_('COM_JOBBOARD_DEPT_SAVED');

        //$this->edit();
        if($post['id']){
	        $this->setRedirect('index.php?option=com_jobboard&view=departments&task=edit&cid[]='.$post['id'],  $msg);
        } else {
            $this->setRedirect('index.php?option=com_jobboard&view=departments&task=save', $msg);
        }
	}
	
	function edit()
	{
		JToolBarHelper::save();
		JToolBarHelper::cancel();
		
		JRequest::setVar('view','departmentedit');
		parent::display();
	}

	function cancel()
	{
		//reset the parameters
		JRequest::setVar('task', '');
		JRequest::setVar('view', 'departments');

		//call up the list screen controller
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'controllers'.DS.'departments.php');
	}
}
	
$controller = new JobboardControllerDepartmentEdit();
$controller->execute($task);
$controller->redirect();

?>