<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables');
jimport('joomla.application.component.controller');

class JobboardControllerConfig extends JController
{

     /**
     * Constructor
     *
     */
       function __construct()
       {
         parent :: __construct();
         $this->registerTask('restore', 'restoreBackup');
       }
    var $view;
	function save()
	{
		JRequest::checkToken() or jexit('Invalid Token');
        $post = JRequest::get('post');
        $success = true;
        if($post['section'] == 'general'){
           $post['home_intro'] = JRequest::getVar('home_intro', '', 'POST', 'string', JREQUEST_ALLOWRAW);
        }
        if($post['section'] == 'users'){
             $user_groups = $post['group'];
             unset($post['group']);
             $cfg_model = & $this->getModel('Config');
             $user_grp = array();
             foreach($user_groups as $ug) {
               $user_grp['id'] = $ug['id'];
               $user_grp['group_name'] = $ug['group_name'];
               $user_grp['post_jobs'] = isset($ug['post_jobs'])? ($ug['post_jobs'] == 'yes'? 1 : 0) : 0;
               $user_grp['manage_jobs'] = isset($ug['manage_jobs'])? ($ug['manage_jobs'] == 'yes'? 1 : 0) : 0;
               $user_grp['apply_to_jobs'] = isset($ug['apply_to_jobs'])? ($ug['apply_to_jobs'] == 'yes'? 1 : 0) : 0;
               $user_grp['manage_applicants'] = isset($ug['manage_applicants'])? ($ug['manage_applicants'] == 'yes'? 1 : 0) : 0;
               $user_grp['search_cvs'] = isset($ug['search_cvs'])? ($ug['search_cvs'] == 'yes'? 1 : 0) : 0;
               $user_grp['search_private_cvs'] = isset($ug['search_private_cvs'])? ($ug['search_private_cvs'] == 'yes'? 1 : 0) : 0;
               $user_grp['create_questionnaires'] = isset($ug['create_questionnaires'])? ($ug['create_questionnaires'] == 'yes'? 1 : 0) : 0;
               $user_grp['manage_questionnaires'] = isset($ug['manage_questionnaires'])? ($ug['manage_questionnaires'] == 'yes'? 1 : 0) : 0;

               $success = $cfg_model->saveUserGroup($user_grp);
             }
             unset($user_grp);
        }

		$row =& JTable::getInstance('Config', 'Table');

		if (!$row->bind($post))
		{
			JError::raiseError(500, $row->getError());
		}

		if(!$row->store())
		{
			JError::raiseError(500, $row->getError());
            $this->setRedirect('index.php?option=com_jobboard&view=dashboard', JText::_('COM_JOBBOARD_SETTINGS_SAVE_ERR'));
		}  else {
          $this->setRedirect('index.php?option=com_jobboard&view=dashboard', JText::_('COM_JOBBOARD_CFIG_SAVED'));
        }
	}
	
	function apply()
	{
		JRequest::checkToken() or jexit('Invalid Token');
        $post = JRequest::get('post');
        $success = true;
        if($post['section'] == 'general'){
           $post['home_intro'] = JRequest::getVar('home_intro', '', 'POST', 'string', JREQUEST_ALLOWRAW);
        }
        if($post['section'] == 'users'){
             $user_groups = $post['group'];
             unset($post['group']);
             $cfg_model = & $this->getModel('Config');
             $user_grp = array();
             foreach($user_groups as $ug) {
               $user_grp['id'] = $ug['id'];
               $user_grp['group_name'] = $ug['group_name'];
               $user_grp['post_jobs'] = isset($ug['post_jobs'])? ($ug['post_jobs'] == 'yes'? 1 : 0) : 0;
               $user_grp['manage_jobs'] = isset($ug['manage_jobs'])? ($ug['manage_jobs'] == 'yes'? 1 : 0) : 0;
               $user_grp['apply_to_jobs'] = isset($ug['apply_to_jobs'])? ($ug['apply_to_jobs'] == 'yes'? 1 : 0) : 0;
               $user_grp['manage_applicants'] = isset($ug['manage_applicants'])? ($ug['manage_applicants'] == 'yes'? 1 : 0) : 0;
               $user_grp['search_cvs'] = isset($ug['search_cvs'])? ($ug['search_cvs'] == 'yes'? 1 : 0) : 0;
               $user_grp['search_private_cvs'] = isset($ug['search_private_cvs'])? ($ug['search_private_cvs'] == 'yes'? 1 : 0) : 0;
               $user_grp['create_questionnaires'] = isset($ug['create_questionnaires'])? ($ug['create_questionnaires'] == 'yes'? 1 : 0) : 0;
               $user_grp['manage_questionnaires'] = isset($ug['manage_questionnaires'])? ($ug['manage_questionnaires'] == 'yes'? 1 : 0) : 0;

               $success = $cfg_model->saveUserGroup($user_grp);
             }
             unset($user_grp);
        }

		$row =& JTable::getInstance('Config', 'Table');

		if (!$row->bind($post) || !$success)
		{
			JError::raiseError(500, $row->getError());
		}

		if(!$row->store())
		{
			JError::raiseError(500, $row->getError());
            $this->setRedirect('index.php?option=com_jobboard&view=config&section='.$post['section'], JText::_('COM_JOBBOARD_SETTINGS_SAVE_ERR'));
		}  else {
          $this->setRedirect('index.php?option=com_jobboard&view=config&section='.$post['section'], JText::_('COM_JOBBOARD_CFIG_SAVED'));
        }
	}

	function display() //display the config for editing
	{		
		JToolBarHelper::apply();;
		JToolBarHelper::save();
		JToolBarHelper::cancel();

		$view = JRequest::getVar('view');
		if(!$view)
		{
			JRequest::setVar('view', 'config');
		}

        JobBoardToolbarHelper::setToolbarLinks('config');


        $cfg_model = & $this->getModel('Config');
        $depts = $cfg_model->getDepts();
        $countries = $cfg_model->getCountries();
        $jobtypes = $cfg_model->getJobtypes();
        $careers = $cfg_model->getCareers();
        $edu = $cfg_model->getEdu();
        $categories = $cfg_model->getCategories();
        $section = JRequest::getString('section', 'general');

        $view = & $this->getView('config', 'html');
        $view->setModel($cfg_model, true );
        $view->setLayout('config');
        $view->assign('section', $section);
		$view->assignRef('depts', $depts);
		$view->assignRef('countries', $countries);
		$view->assignRef('jobtypes', $jobtypes);
		$view->assignRef('careers', $careers);
		$view->assignRef('edu', $edu);
		$view->assignRef('categories', $categories);

		$view->display();
	}	

	function cancel()
	{
		//reset the parameters
		JRequest::setVar('task', '');
		JRequest::setVar('view','dashboard');

		//call up the cpanel screen controller
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'controllers'.DS.'dashboard.php');
	}

    private function _importXML($xml_file){
        $xml = simplexml_load_file($xml_file, 'SimpleXMLElement', LIBXML_NOCDATA);
        $data_json = json_encode($xml);
        return json_decode($data_json, TRUE);
    }

    function restoreBackup(){

       JRequest::checkToken() or jexit( JText::_('Invalid Token') );

       jimport('joomla.filesystem.file');
       jimport('joomla.filesystem.folder');

       $archive = JRequest::getVar('archive', null, 'files', 'array');
       $zip_name = JFile::makeSafe($archive['name']);

       if(empty($zip_name)) {
         $this->setRedirect('index.php?option=com_jobboard&view=config&section=maintenance', JText::_('COM_JOBBOARD_MAINT_IMPORT_NOFILE'), 'error');
         return;
       }

       $zip_segments = explode('.', $zip_name);

       if(end($zip_segments) <> 'zip') {
         $this->setRedirect('index.php?option=com_jobboard&view=config&section=maintenance', JText::_('COM_JOBBOARD_MAINT_IMPORT_WRONGFILE'), 'error');
         return;
       }

       $import_folder = JPATH_ADMINISTRATOR.DS.'tmp';

       if(!JFolder::exists($import_folder))
       {
          $import_folder_created = JFolder::create($import_folder);
          if($import_folder_created == false)
          {
            $this->setRedirect('index.php?option=com_jobboard&view=config&section=maintenance', JText::_('COM_JOBBOARD_FOLDER_CREATE_ERR'), 'error');
            return;
          }
       }

       $sec_file = $import_folder.DS.'index.html';
       if(!JFile::exists($sec_file))
       {
          JFile::write($sec_file, JText::_('<!DOCTYPE html><title></title>'));
       }

       $extract_folder = $import_folder.DS.'job_board_'.date("mjGisY");
       $db_table_entities = array();

       $curr_max_timeout = ini_get('max_execution_time');
       ini_set('max_execution_time', 600); //10 minutes;

       $zip = new ZipArchive;
       $res = $zip->open($archive['tmp_name']);

       if ($res === true) {
             $zip->extractTo($extract_folder);
             $zip->close();
       }

       if (is_dir($extract_folder)) {
          if ($dh = opendir($extract_folder)) {
              while (($file = readdir($dh)) !== false) {
                  $filename_segments = explode('.', $file);
                  $_is_jobboard_file = (strpos($filename_segments[0], 'db_#__jobboard') >= 0 && end($filename_segments) == 'xml')? true : false;
                  if($_is_jobboard_file == true) { // read jobboard xml files only
                      $db_table_entities[] = array('filename' => $file, 'table_name' => str_ireplace('db_#', '#', $filename_segments[0]));
                  }
                  unset($filename_segments);
              }
              closedir($dh);
          }
        }

        //no valid XML files present
        if(empty($db_table_entities)){
            $this->setRedirect('index.php?option=com_jobboard&view=config&section=maintenance', JText::_('COM_JOBBOARD_MAINT_IMPORT_INVALIDXML'), 'error');
            return;
        }

        $model = & $this->getModel('Config');
        $db = & JFactory::getDBO();

        foreach($db_table_entities as $entity)
        {
            $tbl_assoc = $this->_parseXMLFile($entity['filename'], $extract_folder);

            //get table column details
            $tbl_struct = $model->getTblStructure($entity['table_name']);
            $tbl_description = array();

            foreach($tbl_struct as $column) {
                   $tbl_description[] = array($column['Field'], $column['Type']);
            }

            foreach($tbl_assoc as $row)
            {
                 $del_sql = 'DELETE FROM '.$db->nameQuote($entity['table_name']).' WHERE '.$db->nameQuote($tbl_description[0][0]).' = '.$row[$tbl_description[0][0]].';';
                 $ins_sql = 'INSERT INTO '.$db->nameQuote($entity['table_name']).' (';
                 $vals_segment = 'VALUES (';
                 $num_keys = count($tbl_description);

                 foreach($tbl_description as $key_arr)
                 {
                    if(!empty($row[$key_arr[0]]))
                    {
                       $ins_sql .= $db->nameQuote($key_arr[0]);
                       $key_type = substr($key_arr[1], 0, 5);

                       if($key_type == 'varch' || $key_type == 'longt') {
                          $vals_segment .= $db->Quote($row[$key_arr[0]]);
                       } else {
                          $vals_segment .= is_numeric($row[$key_arr[0]])? $row[$key_arr[0]] : $db->Quote($row[$key_arr[0]]);
                       }

                       $ins_sql .= ', ';
                       $vals_segment .= ', ';
                    }
                 }

                 $ins_sql = substr($ins_sql, 0, -2);
                 $vals_segment = substr($vals_segment, 0, -2);
                 $ins_sql .= ') '.$vals_segment.')';
                 $this->_processRow($ins_sql, $del_sql);
            }
        }
        JFolder::delete($extract_folder);
        ini_set('max_execution_time', $curr_max_timeout); //back to default;
        $this->setRedirect('index.php?option=com_jobboard&view=config&section=maintenance', JText::_('COM_JOBBOARD_MAINT_IMPORT_COMPLETE'), 'Message');
    }

    private function _processRow($ins_sql, $del_sql) {
         $db = & JFactory::getDBO();

         $db->setQuery($del_sql);
         $db->Query();

         $db->setQuery($ins_sql);
         return $db->Query();
    }

    private function _parseXMLFile($filename, $extract_folder) {
        $import_file = $extract_folder.DS.$filename;
        $imported_data = $this->_importXML($import_file);

        $sanitised_arr = array();
        $source_keys = null;
        $rec_counter = 0;  ;
        foreach($imported_data as $row) {
            if(!is_array($source_keys))
      	      $source_keys = array_keys($row);
            foreach ($source_keys as $key) {
                $sanitised_arr[$rec_counter][$key] = (is_array($row[$key]) && empty($row[$key]))? '' : $row[$key];
            }
            $rec_counter++;
        }
        unset($imported_data);
        return $sanitised_arr;
    }
}
	
$controller = new JobboardControllerConfig();
if(!isset($task)) $task = "display";
$controller->execute($task);
$controller->redirect();
?>