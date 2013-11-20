<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables');
// Load framework base classes
jimport('joomla.application.component.controller');

class JobboardControllerApply extends JController
{

	function display()
	{                                
		$id = JRequest :: getInt('job_id');
		$this->_getApplicationForm($id);
	}

	private function _getApplicationForm($id)
	{
        require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_job.php' );
	    $published = JobBoardJobHelper::jobPublished($id);

        $view  =& $this->getView('apply', 'html');

         if($published) {
            $app = JFactory::getApplication();
            $catid = $app->getUserStateFromRequest('com_jobboard.list.selcat', 'selcat', 1);

    		$job_model =& $this->getModel('Apply');
    		$job_data = $job_model->getJobData($id);
    		$config_model =& $this->getModel('Config');

    		//set the view parameters
            $view->setModel($job_model, true);
            $view->setModel($config_model);
            $view->assign('job_id', $id);
            $view->assign('selcat', $catid);
            $view->assignRef('data', $job_data);
        }
        $view->assign('id', $id);
        $view->assign('published', $published);

	    $view->display();
	}

}

$controller = new JobboardControllerApply();
$controller->execute($task);
$controller->redirect();
?>
