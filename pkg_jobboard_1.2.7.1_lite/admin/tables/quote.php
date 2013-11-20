<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');

class TableQuote extends JTable
{
	var $id = null;
	var $adult_rate = null;
	var $child_rate = null;
    var $medical_savings = null;
	
	function __construct(&$db)
	{
		parent::__construct('#__jobboard_basic_rates','id',$db);
	}
}
?>
