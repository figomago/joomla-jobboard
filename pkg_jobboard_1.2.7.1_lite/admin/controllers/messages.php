<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');
jimport('joomla.application.component.controller');

class JobboardControllerMessages extends JController
{
    var $view;

  	/**
  	 * constructor
  	 */
  	function __construct()
  	{
  	   parent::__construct();
      // $this->registerTask('edit', 'edit');
    }

	function add()
	{
		JToolBarHelper::save();
		JToolBarHelper::cancel();
        JobBoardToolbarHelper::setToolbarLinks('messages');

		JRequest::setVar('view','messageedit');
		$this->displaySingle('new');
	}
	
	function edit()
	{
		JToolBarHelper::save();
		JToolBarHelper::cancel();
        JobBoardToolbarHelper::setToolbarLinks('messages');

		JRequest::setVar('view','messageedit');
		$this->displaySingle('old');
	}
	
	function display() //display list of all messages
	{
		$doc =& JFactory::getDocument();
        $style = " .icon-48-job_posts {background-image:url(components/com_jobboard/images/job_posts.png); no-repeat; }";
        $doc->addStyleDeclaration( $style );

		JToolBarHelper::title(JText::_( 'COM_JOBBOARD_EMAIL_TEMPLATES'), 'job_posts.png');
		// JToolBarHelper::addNewX();
		JToolBarHelper::editList();
		// JToolBarHelper::deleteList();
		JToolBarHelper::back();
		$view = JRequest::getVar('view');
		if(!$view)
		{
			JRequest::setVar('view', 'messages');
		}
        JobBoardToolbarHelper::setToolbarLinks('messages');

		parent::display();
	}	
	
	function displaySingle($type) //display a single email that can be edited
	{

      /* $_view  =& $this->getView('messageedit', 'html');
       if($type='new') $_view->assign('task', 'add');

      // echo '<pre>'.print_r(JRequest::get('post'), true).'</pre>' ; die;
       $_view->display();*/
	   	JRequest::setVar('view', 'messageedit');
		if($type='new') JRequest::setVar('task','add');
		parent::display();
	}	
	
	function remove()
	{
		JRequest::checkToken() or jexit('Invalid Token');

		$cid = JRequest::getVar('cid', array(0));
		$row =& JTable::getInstance('Messages', 'Table');
		
		foreach ($cid as $id)
		{
			$id = (int) safe($id);
			if (!$row->delete($id))
			{
				JError::raiseError(500, $row->getError());
			}
		}
		
		$msg_sel='';
		
		if(count($cid)>1)
		{
			$msg_sel = 'MSGS';
		}
		else
		{
			$msg_sel = 'MSG';
		}
		
		$this->setRedirect('index.php?option=com_jobboard&view=messages', JText::_($msg_sel) .' '. JText::_('COM_JOBBOARD_DELETED'));
	}

	function cancel()
	{
		//reset the parameters
		JRequest::setVar('task', '');
		JRequest::setVar('view','dashboard');

		//call up the dashboard screen controller
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'controllers'.DS.'dashboard.php');
	}
}

$controller = new JobboardControllerMessages();
if(!isset($task)) $task = "display";
$controller->execute($task);
$controller->redirect();

