<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');

class TableToken extends JTable
{
	var $id = null;
	var $file_id = null;
	var $token = null;
    	var $expires = null;
    	var $max_use = null;      
    	var $hits = null;
	
	function __construct(&$db)
	{
		parent::__construct('#__jobboard_file_tokens','id',$db);
	}
}
?>
