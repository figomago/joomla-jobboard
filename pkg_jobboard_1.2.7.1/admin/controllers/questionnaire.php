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

class JobboardControllerQuestionnaire extends JController
{
	/**
	 * constructor
	 */
	function __construct()
	{
		parent::__construct();
	}

	function display()
	{
		$qid = JRequest::getInt('id');
        $view  =& $this->getView('questionnaire', 'html');
          if($qid > 0) {
           $q_data = & JTable::getInstance('Questionnaire', 'Table');
    	   $q_data->load($qid);
           $fields = json_decode($q_data->fields);
           if(!is_object($fields)) {
            $qid = 0;
           } else {
             unset($q_data->fields);
             jimport('joomla.utilities.date');
             $today = new JDate();
             $view->assign('qtitle', $q_data->title);
             $view->assign('qdescr', $q_data->description);
             $view->assignRef('fields', $fields->fields);
             $view->assignRef('today', $today);
           }
        }
        $view->assign('qid', $qid);
        $view->display();
	}

}

$controller = new JobboardControllerQuestionnaire();
$controller->execute($task);
$controller->redirect();
?>
