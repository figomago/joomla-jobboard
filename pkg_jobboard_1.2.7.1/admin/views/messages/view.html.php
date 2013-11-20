<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');


jimport('joomla.application.component.view');

class JobboardViewMessages extends JView
{
	function display($tpl = null)
	{
		$rows =& $this->get('data');
		$this->assign('jb_render', JobBoardHelper::renderJobBoardx());
		$this->assignRef('rows',$rows);
		parent::display($tpl);
	}
}