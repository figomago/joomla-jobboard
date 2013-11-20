<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');

class TableConfig extends JTable
{
	var $id = null;
	var $organisation = null;
	var $from_mail = null;
	var $reply_to = null;
	var $default_dept = null;
	var $default_country = null;
	var $default_city = null;
    var $default_jobtype = null;
    var $default_career = null;
    var $default_edu = null;
	var $default_category = null;
	var $default_post_range = null;
	var $allow_unsolicited = null;
	var $allow_applications = null;
	var $dept_notify_admin = null;
	var $dept_notify_contact = null;
    var $show_social = null;
    var $show_viewcount = null;
    var $show_applcount = null;
    var $email_cvattach = null;
    var $show_job_summary = null;
    var $send_tofriend = null;
    var $appl_job_summary = null;
    var $sharing_job_summary = null;
    var $short_date_format = null;
    var $date_separator = null;
    var $long_date_format = null;
    var $jobtype_coloring = null;
    var $use_location = null;    
    var $social_icon_style = null;
	var $release_ver = null;
    var $max_filesize = null;
    var $max_files = null;
    var $max_quals = null;
    var $max_employers = null;
    var $max_skills = null;
    var $default_upl_folder = null;
    var $default_col_layout = null;
    var $allow_once_off_applications = null;
    var $default_list_layout = null;
    var $distance_unit = null;
    var $default_distance = null;
    var $enable_post_maps = null;
    var $home_intro_title = null;
    var $home_intro = null;
    var $home_jobs_limit = null;
    var $secure_login = null;
    var $captcha_login = null;
    var $captcha_reg = null;
    var $captcha_public = null;
    var $allow_registration = null;
    var $show_rss = null;
    var $default_user_grp = null;
    var $default_empl_grp = null;
    var $maint_tasks_on = null;
    var $maint_tasks_int_type = null;
    var $maint_tasks_int = null;
    var $last_maint_check = null;
    var $last_maint_run = null;
    var $sched_disable_exp = null;
    var $sched_expire_feat = null;
    var $sched_backup_data = null;
    var $email_task_results = null;
    var $empl_default_feature = null;
    var $feature_length = null;
    var $allow_linkedin_imports = null;
    var $linkedin_key = null;
    var $linkedin_secret = null;
    var $user_show_applstatus = null;

	function __construct(&$db)
	{
		parent::__construct('#__jobboard_config', 'id', $db);
	}
}
?>