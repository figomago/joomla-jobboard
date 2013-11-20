<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class  JobboardModelMessages extends JModel
{
	var $data = null;
	function getData()
	{
		if(empty($this->data))
		{
			$query = "SELECT * FROM #__jobboard_emailmsg ORDER BY type";
			$this->data = $this->_getList($query);
		}
		
		return $this->data;
	}
}