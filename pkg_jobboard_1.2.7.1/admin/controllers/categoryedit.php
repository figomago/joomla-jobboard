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

class JobboardControllerCategoryEdit extends JController
{
	function save()
	{
		JRequest::checkToken() or jexit('Invalid Token');
		
        $record = JTable::getInstance('category', 'Table');
        $post = JRequest::get('POST');
        $post['id'] = ($post['id'] <> 0)? $post['id'] : false;
        if (!$record->save($post)) {
            // uh oh failed to save
            JError::raiseError('500', JTable::getError());
        }
		$this->setRedirect('index.php?option=com_jobboard&view=category&task=save', JText::_('COM_JOBBOARD_CATEG_SAVED'));
	}
	
	function edit()
	{
	    $doc =& JFactory::getDocument();
        $style = " .icon-48-applicant_details {background-image:url(components/com_jobboard/images/applicant_details.png); no-repeat; }";
        $doc->addStyleDeclaration( $style );

		JToolBarHelper::title(JText::_( 'EDIT_CATEGORY'), 'applicant_details.png');
		JToolBarHelper::save();
		JToolBarHelper::cancel();
		
		JRequest::setVar('view','categoryedit');
		parent::display();
	}

	function apply()
	{
		//reset the parameters
		JRequest::setVar('task', '');
		JRequest::setVar('view', 'category');

		//call up the list screen controller
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'controllers'.DS.'category.php');
	}

	function back()
	{
		//reset the parameters
		JRequest::setVar('task', '');
		JRequest::setVar('view', 'category');

		//call up the list screen controller
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'controllers'.DS.'category.php');
	}
	function cancel()
	{
		//reset the parameters
		JRequest::setVar('task', '');
		JRequest::setVar('view', 'category');

		//call up the list screen controller
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'controllers'.DS.'category.php');
	}
}
	
$controller = new JobboardControllerCategoryEdit();
$controller->execute($task);
$controller->redirect();

?>