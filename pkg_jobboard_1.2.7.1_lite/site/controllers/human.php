<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.component.controller');

class JobboardControllerHuman extends JController
{
     /**
	 * constructor
	 */
	function __construct()
	{
		parent::__construct();
    }

    function display() {

        require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_human.php' );
        $view  =& $this->getView('human', 'raw');

	    $view->display();
    }


}
$controller = new JobboardControllerHuman();
$controller->execute($task);
$controller->redirect();

