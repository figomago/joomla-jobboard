<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');

class TableStatus extends JTable
{
	var $id = null;
	var $status_description = null;

	function __construct(&$db)
	{
		parent::__construct('#__jobboard_statuses','id',$db);
	}
}

?>
