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

   class JobboardControllerShare extends JController
   {

     function display()
     {                            
       $id = JRequest :: getVar('job_id', '', '', 'int');
       $this->getSharingForm($id);
     }

     function getSharingForm($id)
     {
       require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_job.php' );
       $published = JobBoardJobHelper::jobPublished($id);

       $app = JFactory::getApplication();
       $catid = $app->getUserStateFromRequest('com_jobboard.list.selcat', 'selcat', 1);

       $view  =& $this->getView('share', 'html');

       if($published) {
           $job_model =& $this->getModel('Apply');
           $job_data = $job_model->getJobData($id);

           $messg_model =& $this->getModel('Message');
           $msg_id = $messg_model->getMsgID('sharejob');
           $msg = $messg_model->getMsg($msg_id);
           $config_model =& $this->getModel('Config');
           $config = $config_model->getShareConfig();


           $view->setModel($job_model, true);
           $view->assignRef('data', $job_data);
           $view->assignRef('config', $config);
           $view->assign('job_id', $id);
           $view->assign('msg', $msg->body);
       }

        $view->assign('selcat', $catid);
        $view->assign('published', $published);
	    $view->display();
     }
   }

   $controller = new JobboardControllerShare();
   $controller->execute($task);
   $controller->redirect();
?>
