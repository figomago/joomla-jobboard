<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');

class TableCvprofile extends JTable
{
	var $id = null;
	var $user_id = null;
        var $created_date = null;
        var $modified_date = null;
	var $hiring_dept = null;
	var $profile_name = null;
	var $desired_posn = null;
	var $avail_date = null;
	var $third_party = null;
	var $job_type = null;
	var $security_clearance = null;
	var $file_uploads = null;
	var $edu_id = null;
	var $edu_institution = null;
	var $edu_city = null;
	var $latest_employer_id = null;
	var $pref_country = null;
	var $pref_locns = null;
	var $summary = null;
	var $hits = null;
	var $invites = null;
	var $is_linkedin = null;

	function __construct(&$db)
	{
		parent::__construct('#__jobboard_cvprofiles','id',$db);
	}
}

?>
