<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// Protect from unauthorized access
defined('_JEXEC') or die(JText::_('Restricted Access'));
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_list.php' );
jimport('joomla.application.component.controller');

class JobboardControllerRss extends JController
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
        if(!JobBoardListHelper::rssEnabled()) jexit(JText::_('COM_JOBBOARD_FEEDS_NOACCES') );

        $selcat = JRequest::getInt('selcat', 1);
        $keywd = JRequest::getString('keysrch', '');

        $feed_model = & $this->getModel('Rss');
        $seldesc = $feed_model->getCatname($selcat);

        $_view = & $this->getView('rss', 'feed');
        $_view->setModel($feed_model, true);
        $_view->assign('seldesc', $seldesc);
        $_view->assign('selcat', $selcat);
        $_view->assign('keysrch', $keywd);

        $_view->display();
	}
}

$controller = new JobboardControllerRss();
$controller->execute($task);
$controller->redirect();

?>