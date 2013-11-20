<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

// Load framework base classes
jimport('joomla.application.component.controller');

/**
 * The Dashboard controller class
 *
 */
class JobboardControllerDashboard extends JController
{
	/**
	 * Displays the Dashboard (main page)
	 * Accessible at index.php?option=com_Jobboard
	 */
	function display()
	{
	    $doc =& JFactory::getDocument();
        $style = " .icon-48-job_board {background-image:url(components/com_jobboard/images/job_board.png); no-repeat; }";
        $doc->addStyleDeclaration( $style );

		JToolBarHelper::title(JText::_( 'COM_JOBBOARD_JOB_BOARD'), 'job_board.png');
		JToolBarHelper::addNewX('newJob', JText::_('COM_JOBBOARD_NEW_JOB'));
		JToolBarHelper::divider();

        JobBoardToolbarHelper::setToolbarLinks('dashboard');

		parent::display();
	}

	function newJob()
	{
        $this->setRedirect('index.php?option=com_jobboard&view=jobs&task=edit&cid[]=0', '');

	}
}

$controller = new JobboardControllerDashboard();
if(!isset($task)) $task = "display";
$controller->execute($task);
$controller->redirect();
