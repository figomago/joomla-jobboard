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

   class JobboardControllerUnsolicited extends JController
   {

     function display()
     {                            
       $this->getApplicationForm();
     }

     function getApplicationForm()
     {
       $catid = JRequest :: getVar('catid', '', '', 'int');
       $config_model =& $this->getModel('Config');
       $config = $config_model->getUnsolConfig();

       //set the view parameters
       $view  =& $this->getView('unsolicited', 'html');

       $view->setModel($config_model, true);
       $view->assignRef('config', $config);
       $view->assign('catid', $catid);

       $view->display();
     }
        
   }

   $controller = new JobboardControllerUnsolicited();
   $controller->execute($task);
   $controller->redirect();
?>
