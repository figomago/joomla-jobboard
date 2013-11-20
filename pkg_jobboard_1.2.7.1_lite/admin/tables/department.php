<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');

class TableDepartment extends JTable
{
	var $id = null;
	var $name = null;
	var $contact_name = null;
	var $contact_email = null;
	var $notify = null;
	var $notify_admin = null;
        var $acceptance_notify = null;
        var $rejection_notify = null;

	function __construct(&$db)
	{
		parent::__construct('#__jobboard_departments','id',$db);
	}
}

?>
