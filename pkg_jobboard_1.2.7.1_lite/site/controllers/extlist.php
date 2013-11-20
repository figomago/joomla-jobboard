<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// Protect from unauthorized access
defined('_JEXEC') or die(JText::_('Restricted Access'));
jimport('joomla.application.component.controller');

class JobboardControllerExtlist extends JController
{
    /**
    * constructor
    */
	function __construct()
	{
		parent::__construct();
    }

    function display() {

        JRequest::checkToken() or jexit(JText::_('Invalid Token'));
        $this->_getExtList();
	}

    private function _getExtList()
    {
	     $app = JFactory::getApplication();
         $post = JRequest::get('POST') ;

         $app->setUserState('com_jobboard.extlist.limitstart', $post['limitstart'], 'int');
         $app->setUserState('com_jobboard.extlist.limit', $post['limit'], 'int');
         $results = array();
         $model = & $this->getModel('Extlist');
         $results['data'] = $model->getData();
         $results['pagination'] = $model->getPaginationVars();
         $this->_returnJSON($results);
    }

    private function _returnJSON($results) {

       $view  =& $this->getView('extlist', 'json');
       $view->assignRef('data', $results);
      /* $document =& JFactory::getDocument();
       $doc = & JDocument::getInstance('json');
       $view->assignRef('doc', $doc);*/

       $view->display();
    }

}

$controller = new JobboardControllerExtlist();
$controller->execute($task);
$controller->redirect();

?>