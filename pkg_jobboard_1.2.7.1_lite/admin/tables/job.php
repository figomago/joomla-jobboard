<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');

class TableJob extends JTable
{
	var $id = null;
	var $post_date = null;
	var $expiry_date = null;
	var $posted_by = null;
	var $job_title = null;
    var $job_type = null;
    var $category = null;
    var $career_level = null;
    var $education = null;
    var $positions = null;
    var $salary = null;
    var $country = null;
    var $city = null;
    var $description = null;
    var $duties = null;
    var $job_tags = null;
    var $department = null;
    var $status = null;
    var $num_applications = null;
    var $hits = null;
    var $published = null;
    var $questionnaire_id = null;
    var $ref_num = null;
    var $geo_latitude = null;
    var $geo_longitude = null;
    var $geo_state_province = null;
    var $featured = null;
    var $default_user_grp = null;
    var $default_empl_grp = null;

	function __construct(&$db)
	{
		parent::__construct('#__jobboard_jobs', 'id', $db);
	}
}
?>
