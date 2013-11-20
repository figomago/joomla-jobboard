<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');

class TableUser extends JTable
{
	var $id = null;
	var $user_id = null;
	var $group_id = null;
  	var $user_status = null;
  	var $feature_jobs = null;
  	var $contact_address = null;
  	var $contact_country = null;
  	var $contact_location = null;
  	var $contact_zip = null;
  	var $contact_phone_1 = null;
  	var $contact_phone_2 = null;
  	var $contact_fax = null;
  	var $website_url = null;
  	var $twitter_url = null;
  	var $facebook_url = null;
  	var $linkedin_url = null;
  	var $is_authorised_linkedin = null;
  	var $profile_image_path = null;
  	var $user_key = null;
  	var $user_secret = null;
  	var $send_notifications = null;
  	var $notify_on_appl_accept = null;
  	var $notify_on_appl_reject = null;
  	var $notify_on_appl_update = null;
  	var $login_dashboard = null;
  	var $email_latest_jobs = null;
  	var $email_invites = null;
  	var $show_modeswitch = null;

	function __construct(&$db)
	{
		parent::__construct('#__jobboard_users','id',$db);
	}
}
?>
