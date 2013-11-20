<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class plgSystemJobboard_Schedule extends JPlugin
{

	   private $_timezone_offset = null;
	   private $_cfg = null;
	   private $_notify_vars  = array();

      /**
      * Constructor.
      *
      * @access protected
      * @param object $subject The object to observe
      * @param array   $config  An array that holds the plugin configuration
      * @since 1.0
      */
      function plgSystemJobboard_Schedule(& $subject, $config)
      {
	       parent :: __construct($subject, $config);
           $lang =& JFactory::getLanguage();
           $lang->load('plg_jobboard_schedule', JPATH_ADMINISTRATOR);

           $cfg = & JFactory::getConfig();
           $this->_setConfig($cfg);
           $this->_setTzOffset() ;
      }

      private function _setConfig($cfg) {
           $this->_cfg = $cfg;
      }

      private function _setTzOffset() {
           $this->_timezone_offset = $this->_cfg->getValue('config.offset');
      }

      /**
      * Check schedule settings
      * @param none
      *
      * @return null
      */
	  function onAfterRoute()
      {
          $sched_config = self::_getSchedConfig();

          if($sched_config['maint_tasks_on'] == 1) {
             $right_now = $this->_getNow();
             $right_now_unix = $this->_getUnixTime($right_now);

             /** calculate the rest hourly instead of after every onAfterRoute event
             *   to lighten server load
             */
             if(($right_now_unix - $sched_config['last_maint_check']) > 3600) {
                 $run_schedule = self::_calcNextRun($sched_config['maint_tasks_int_type'], $sched_config['maint_tasks_int'], $right_now_unix, $sched_config['last_maint_run']);
                 $this->_setLastCalcTime($right_now_unix);
                 if($run_schedule == true) {
                     $all_passed = false;
                     $tasks = $this->_getSchedTasks();

                     if($tasks['sched_disable_exp'] == 1)  {
                         $all_passed = $this->_processExpired();
                         if($tasks['email_task_results'] == 1)
                             $this->_notify_vars['expired_jobs'] = $all_passed;
                     }

                     if($tasks['sched_expire_feat'] == 1)  {
                         $all_passed = $this->_processFeatured($tasks['feature_length']);
                         if($tasks['email_task_results'] == 1)
                             $this->_notify_vars['featured_jobs'] = $all_passed;
                     }

                     if($tasks['sched_backup_data'] == 1)  {
                         $all_passed = $this->_runBackup($tasks['email_task_results']);
                         if($tasks['email_task_results'] == 1)
                             $this->_notify_vars['backup'] = $all_passed;
                     }

                     if($tasks['email_task_results'] == 1)  {
                        $this->_notify_vars['all_tasks'] = $all_passed;
                        $this->_sendAdminEmail();
                     }
                    $all_passed == true? $this->_setLastRunTime($right_now_unix) : false;

                 }
             }
          }
      }

      /**
      * Get schedule parameters from DB
      * @param none
      *
      * @return assoc schedule parameters
      */
      private function _getSchedConfig(){
          $db = & JFactory::getDBO();
          $sql = "SELECT ". $db->nameQuote('maint_tasks_on'). ",  ". $db->nameQuote('maint_tasks_int_type'). ",  ". $db->nameQuote('maint_tasks_int'). ", ". $db->nameQuote('last_maint_run'). ", ". $db->nameQuote('last_maint_check'). "
                      FROM ". $db->nameQuote('#__jobboard_config'). "
                      WHERE ". $db->nameQuote('id'). " = 1";
          $db->setQuery($sql);
    	  return $db->loadAssoc();
      }

      /**
      * Get scheduled tasks from DB
      * @param none
      *
      * @return assoc scheduled tasks
      */
      private function _getSchedTasks(){
          $db = & JFactory::getDBO();
          $sql = "SELECT ". $db->nameQuote('sched_disable_exp'). ",  ". $db->nameQuote('sched_expire_feat'). ",  ". $db->nameQuote('feature_length'). ",  ". $db->nameQuote('sched_backup_data'). ",  ". $db->nameQuote('email_task_results'). "
                      FROM ". $db->nameQuote('#__jobboard_config'). "
                      WHERE ". $db->nameQuote('id'). " = 1";
          $db->setQuery($sql);
    	  return $db->loadAssoc();
      }

      /**
      * Check if its time to run scheduled tasks
      *
      * @param int $interval_type
      * @param int $interval
      * @param string $right_now date
      * @param longint $last_run
      *
      * @return boolean
      */
      private function _calcNextRun($interval_type=0, $interval=1, $right_now, $last_run)  {

         //Hour intervals
         $_adjusted_interval = 3600 * $interval;

         if($interval_type > 0) {
           switch($interval_type) {
              case 1 : //Day intervals (x 24 hrs)
                 $_adjusted_interval *= 24;
              break;
              case 2 : //Week intervals (x 24 hrs x 7 days)
                 $_adjusted_interval *= 168;
              break;
              case 3 : //Month intervals (x 24 hrs x 7 days x 4 weeks)
                 $_adjusted_interval *= 672;
              break;
           }
         }

         return ($last_run + $_adjusted_interval) <= $right_now? true : false;

      }

      private function _getPublishedJobs()  {
          $db = & JFactory::getDBO();
          $sql = "SELECT ".$db->nameQuote('id'). ", DATE_FORMAT(".$db->nameQuote('expiry_date'). ", '%Y-%m-%d') AS expiry_date
                            , ".$db->nameQuote('published')."
                  FROM ".$db->nameQuote('#__jobboard_jobs'). "
                  WHERE ".$db->nameQuote('published'). " = 1 AND ".$db->nameQuote('expiry_date'). " <> '0000-00-00'";
          $db->setQuery($sql);
    	  return $db->loadAssocList();
      }

      private function _getFeaturedJobs()  {
          $db = & JFactory::getDBO();
          $sql = "SELECT ".$db->nameQuote('id'). ", DATE_FORMAT(".$db->nameQuote('post_date'). ", '%Y-%m-%d') AS post_date
                  FROM ".$db->nameQuote('#__jobboard_jobs'). "
                  WHERE ".$db->nameQuote('published'). " = 1 AND ".$db->nameQuote('featured'). " = 1 ";
          $db->setQuery($sql);
    	  return $db->loadAssocList();
      }

      private function _processExpired(){
         $jobs = $this->_getPublishedJobs();
         $success = true;
         $failed = $passed = $count_jobs = 0;
         if(!empty($jobs)) {
            $count_jobs = count($jobs);

            if ($count_jobs > 0) {
               $date_now = date("Y-m-d");

               foreach ($jobs as $job) {
                  $success = self::_checkExpired(&$job, &$date_now);
                  $success == true? $passed++ : $failed++;
               }
            }
         }

         $this->_notify_vars['pub_tot'] = $count_jobs;
         $this->_notify_vars['pub_pass'] = $passed;
         $this->_notify_vars['pub_fail'] = $failed;

         return $success;
      }

      private function _processFeatured($feature_length=30){
         $jobs = $this->_getFeaturedJobs();
         $success = true;
         $failed = $passed = $count_jobs = 0;
         if(!empty($jobs)) {
            $count_jobs = count($jobs);

            if ($count_jobs > 0) {
               $date_now = date("Y-m-d");
               foreach ($jobs as $job) {
                  $success = self::_checkFeatured(&$job, &$date_now, $feature_length);
                  $success == true? $passed++ : $failed++;
               }
            }
         }

         $this->_notify_vars['feat_tot'] = $count_jobs;
         $this->_notify_vars['feat_pass'] = $passed;
         $this->_notify_vars['feat_fail'] = $failed;

         return $success;
      }

      private function _checkExpired($job, $date_now) {

            $expire_date = strtotime(date("Y-m-d", strtotime($date)) . " +30 days");

            if($job['expiry_date'] <= $date_now && $job['published'] == 1)  {

                  $db = & JFactory::getDBO();
                  $query = 'UPDATE '.$db->nameQuote('#__jobboard_jobs').'
                        SET '.$db->nameQuote('published').' = 0
                    WHERE '.$db->nameQuote('id').' = '.$job['id'];
                  $db->setQuery($query );
                  return $db->Query();
              }
      }

      private function _checkFeatured($job, $date_now, $feature_length) {

          $fdate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($job['post_date'])) . " +".$feature_length." days") );

          if($fdate <= $date_now)  {

                $db = & JFactory::getDBO();
                $query = 'UPDATE '.$db->nameQuote('#__jobboard_jobs').'
                      SET '.$db->nameQuote('featured').' = 0
                  WHERE '.$db->nameQuote('id').' = '.$job['id'];
                $db->setQuery($query );
                return $db->Query();
            }
      }

      private function _runBackup($notify_admin){

        jimport('joomla.filesystem.folder');
        jimport('joomla.filesystem.file');
        jimport('joomla.utilities.date');

        $db = & JFactory::getDBO();

        $table_suffixes = array('applicants', 'bookmarks', 'career_levels', 'categories', 'config',
                          'countries', 'cvprofiles', 'departments', 'education', 'emailmsg', 'file_tokens',
                          'file_uploads', 'invites', 'jobs', 'past_edu', 'past_employers', 'questionnaires', 'statuses',
                          'types', 'unsolicited', 'usercomms', 'users', 'userskills', 'usr_applications', 'usr_groups');

        $file_array = $errors = array();
        $backup_folder = JPATH_ADMINISTRATOR.DS.'tmp';

        if(!JFolder::exists($backup_folder))
        {
            $backup_folder_created = JFolder::create($backup_folder);
            if($backup_folder_created == false)
            {
              $errors[] = JText::_('COM_JOBBOARD_FOLDER_CREATE_ERR');
              return;
            }
        }
        $sec_file = $backup_folder.DS.'index.html';
        if(!JFile::exists($sec_file))
        {
            $_html = '<!DOCTYPE html><title></title>';
            JFile::write($sec_file, $_html);
        }

        foreach($table_suffixes as $tbl)
        {
          $db_table = '#__jobboard_'.$tbl;
          $XML_table_name = $tbl;
          $XML_table_rowname = $XML_table_name . '_row';

          $sql = "SELECT * FROM " .$db->nameQuote($db_table);
          $db->setQuery($sql);
    	  $tbl_data = $db->loadAssocList();
          $count_rows = count($tbl_data);

          if ($count_rows > 0)
          {

             $file_out_local = "db_". $db_table . ".xml";
             $file_out = $backup_folder . DS . $file_out_local;
        	 $strXML = "<?xml version=\"1.0\"  encoding=\"utf-8\"?>\n";
        	 $table_keys = array_keys($tbl_data[0]);
        	 $r_count = 0;
        	 $strXML = $strXML . "<" . $XML_table_name . ">\n";

        	 foreach($tbl_data as $row_rsRecordset) {
        				$strXML = $strXML . "<" . $XML_table_rowname . $r_count . ">\n";
        				foreach ($table_keys as $key) {
        						$node_value = is_numeric($row_rsRecordset[$key]) ? $row_rsRecordset[$key] : "<![CDATA[" . $row_rsRecordset[$key] . "]]>";
        						$strXML = $strXML . "<" . $key . ">" . $node_value . "</" . $key . ">\n";
        				}

        				$strXML = $strXML . "</" . $XML_table_rowname . $r_count . ">\n";
        				$r_count += 1;
        	 }

      		 $strXML = utf8_encode($strXML . "</" . $XML_table_name . ">\n");
      		 $XMLFile = fopen($file_out, "w");
      		 fwrite($XMLFile, $strXML);
      		 fclose($XMLFile);
             $file_array[]  = array($file_out, $file_out_local);
          }
        }

        $destination = $backup_folder . DS . 'job_board_'.date("mjGisY").'.zip';
        $success = $this->_createZip($file_array, $destination);

        //delete single files
        foreach($file_array as $file){
          unlink($file[0]);
        }

        if($notify_admin == 1)
            $this->_notify_vars['zip'] = $destination;

        return $success;
      }

      private function _createZip($files, $destination = '', $overwrite = false) {
            //if the zip file already exists and overwrite is false, return false
      		if (file_exists($destination) && !$overwrite) {
      				return false;
      		}
      		$valid_files = array();
      		if (is_array($files) && !empty ($files)) {
            //cycle through each file
  				foreach ($files as $file) {
                        //make sure the file exists
  						if (file_exists($file[0])) {
  								$valid_files[] = $file;
  						}
  				}
      		}
            //if we have good files...
      		if (count($valid_files)) {
            //create the archive
      				$zip = new ZipArchive();
      				if ($zip->open($destination, $overwrite ? ZIPARCHIVE :: OVERWRITE : ZIPARCHIVE :: CREATE) !== true) {
      						return false;
      				}
                    //add the files
      				foreach ($valid_files as $file) {
      						$zip->addFile($file[0], $file[1]);
      				}
                  //debug
                  //echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
                  //close the zip -- done!
      				$zip->close();

        //check to make sure the file exists
      				return file_exists($destination);
      		}
      		else {
      				return false;
      		}
      }

      private function _runSync($right_now){
         echo '<br />***********************************';
         echo '<br />Running sync';
         $sync_folder = JPATH_ADMINISTRATOR.DS.'itris';
         if(!JFolder::exists($sync_folder))
         {
            $sync_folder_created = JFolder::create($sync_folder);
            if($sync_folder_created == false)
            {
              $errors[] = JText::_('COM_JOBBOARD_USERFOLDER_CREATE_ERR');
            }
         }

         $sec_file = $sync_folder.DS.'index.html';
         if(!JFile::exists($sec_file))
         {
            JFile::write($sec_file, JText::_('COM_JOBBOARD_BLANK_HTML'));
         }

         $xml_file = $sync_folder.DS.$this->_getFileName();
         if(!JFile::exists($xml_file))
         {
            $empty_file_xml = '<?xml version="1.0" encoding="UTF-8" ?><JOBS></JOBS>';
            JFile::write($xml_file, $empty_file_xml);
         } else {
           // parse xml and populate job data
            $this->_parseXML($xml_file);
            $this->_setLastSyncTime($this->_getUnixTime(&$right_now));
         }
      }

      private function _parseXML($xml_file) {
        echo '<br />Parsing '.$xml_file;
        $xml = simplexml_load_file($xml_file);
        $data_json = json_encode($xml);
        $data_array = json_decode($data_json, TRUE);

        if(count($data_array['JOB']) > 0){
          foreach($data_array['JOB'] as $job) {
            $this->_syncJob($job);
          }
        }
      }

      private function _syncJob($job){
        $db = & JFactory::getDBO();
        $sql = 'SHOW COLUMNS FROM';
        echo '<br />-----------------------------------';
        echo '<br />Syncing '.$job['TITLE'].' ....';
        echo '<br /><pre>'.print_r($job, true).'</pre>';
      }

      private function _dbColExists(){
        $db = & JFactory::getDBO();
        $sql = 'SHOW COLUMNS FROM `#__jobboard_config` WHERE FIELD = '.$db->Quote('last_sync_check');
        $db->setQuery($sql);
        $result = $db->loadAssocList();
        return isset($result[0])? true : false;
      }

      private function _addDbSyncCol(){
        $db = & JFactory::getDBO();
        $sql = 'ALTER TABLE `#__jobboard_config` ADD ' .$db->nameQuote('last_sync_check'). ' BIGINT NOT NULL DEFAULT 0;';
        $db->setQuery($sql);
        return $db->Query();
      }

      private function _getFileName(){
        return $this->params->get('import_fname', 'jreposts.xml');
      }

      private function _setLastRunTime($unix_ts){
        $db = & JFactory::getDBO();
        $sql = 'UPDATE '. $db->nameQuote('#__jobboard_config').' SET '. $db->nameQuote('last_maint_run').' = '.$unix_ts.'
                  WHERE '. $db->nameQuote('id').' = 1';
        $db->setQuery($sql);
        return $db->Query();
      }

      private function _setLastCalcTime($unix_ts){
        $db = & JFactory::getDBO();
        $sql = 'UPDATE '. $db->nameQuote('#__jobboard_config').' SET '. $db->nameQuote('last_maint_check').' = '.$unix_ts.'
                  WHERE '. $db->nameQuote('id').' = 1';
        $db->setQuery($sql);
        return $db->Query();
      }

      private function _getNow(){
        jimport('joomla.utilities.date');
        $now = & JFactory::getDate();
		$date = new JDate($now->toRFC822(), $this->_timezone_offset);
		return $date;
      }

      private function _getUnixTime(&$date_obj){
        return $date_obj->toUnix();
      }

      private function _getSyncHours(&$date_obj){
        return $date_obj->toFormat("%H");
      }

      private function _getAdminDetails(){
        $db = & JFactory::getDBO();
        $sql = 'SELECT '. $db->nameQuote('organisation').'
            , '. $db->nameQuote('from_mail').' AS admin_email, ' . $db->nameQuote('reply_to').', ' . $db->nameQuote('feature_length').'
                  FROM '. $db->nameQuote('#__jobboard_config').'
                  WHERE '. $db->nameQuote('id').' = 1';
        $db->setQuery($sql);
        return $db->loadAssoc();
      }

      private function _sendAdminEmail()
      {
          $admin_details = $this->_getAdminDetails();

          $subject = JText::sprintf('PLG_JOBBOARD_MAINT_TASKS_SUBJECT', $admin_details['organisation']);
          $body = ($this->_notify_vars['all_tasks'] == 1)? JText::_('PLG_JOBBOARD_MAINT_TASKS_SUCCESS') : JText::_('PLG_JOBBOARD_MAINT_TASKS_ERRORS');
          $body .= "\n*****************\n\n";

          if(isset($this->_notify_vars['expired_jobs']))
          {
              $task_result = ($this->_notify_vars['expired_jobs'] == 1)? JText::_('PLG_JOBBOARD_MAINT_TASKS_OK') : JText::_('PLG_JOBBOARD_MAINT_TASKS_FAIL');
              $body .= JText::_('PLG_JOBBOARD_MAINT_TASKS_DISABLE_EXPIRED'). ": ". $task_result ."\n";
              $body .= JText::_('PLG_JOBBOARD_MAINT_TOTAL_RECORDS'). ": ". $this->_notify_vars['pub_tot'] ."\n";
              $body .= JText::sprintf('PLG_JOBBOARD_MAINT_NUM_RECORDS', $this->_notify_vars['pub_pass']). " ". JText::_('PLG_JOBBOARD_MAINT_RECORDS_DISABLED') ."\n";
              $body .= "------------------------\n\n";
          }

          if(isset($this->_notify_vars['featured_jobs']))
          {
              $task_result = ($this->_notify_vars['featured_jobs'] == 1)? JText::_('PLG_JOBBOARD_MAINT_TASKS_OK') : JText::_('PLG_JOBBOARD_MAINT_TASKS_FAIL');
              $body .= JText::sprintf('PLG_JOBBOARD_MAINT_TASKS_EXPIRE_FEAT', $admin_details['feature_length']). ": ". $task_result ."\n";
              $body .= JText::_('PLG_JOBBOARD_MAINT_TOTAL_RECORDS'). ": ". $this->_notify_vars['feat_tot'] ."\n";
              $body .= JText::sprintf('PLG_JOBBOARD_MAINT_NUM_RECORDS', $this->_notify_vars['feat_pass']). " ". JText::_('PLG_JOBBOARD_MAINT_RECORDS_UNFEATURED') ."\n";
              $body .= "------------------------\n\n";
          }

          if(isset($this->_notify_vars['backup']))
          {
              $task_result = ($this->_notify_vars['backup'] == 1)? JText::_('PLG_JOBBOARD_MAINT_TASKS_OK') : JText::_('PLG_JOBBOARD_MAINT_TASKS_FAIL');
              $body .= JText::_('PLG_JOBBOARD_MAINT_TASKS_DB_BACKUP'). ": ". $task_result ."\n";
              $body .= "------------------------\n\n";

          	  $admin_sendresult =& JFactory::getMailer()->sendMail($admin_details['reply_to'], $admin_details['reply_to'], $admin_details['admin_email'], $subject, $body,null,null,null,$this->_notify_vars['zip']);

          } else $admin_sendresult =& JFactory::getMailer()->sendMail($admin_details['reply_to'], $admin_details['admin_email'], $recipients, $subject, $body);
          return $admin_sendresult;
      }
}
?>