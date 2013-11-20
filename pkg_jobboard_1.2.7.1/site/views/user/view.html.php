<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class JobboardViewUser extends JView
{
	function display($tpl = null)
	{                                   
        $app = &JFactory::getApplication();
        $today = &JFactory::getDate();
        $uri = &JURI::getInstance();
        $this->uri = $uri->getScheme().'://'.$uri->getHost().$uri->getPath();
        $itemid = JRequest::getInt('Itemid');
        $this->step = isset($this->step)? $this->step : 0;
        $this->assignRef('today', $today);
        if($this->step <> 1 && $this->step > 0)
             $this->assignRef('av_date', $today);
	    $_format = JRequest::getString('tmpl', '');
        $this->is_modal = $_format == 'component'? true : false;
        $this->_addScripts($this->step, $this->is_modal);
        $user = & JFactory::getUser();
        $this->assignRef('user', $user);
        $this->setstate = JobBoardHelper::renderJobBoard();
        $this->day_format = !version_compare( JVERSION, '1.6.0', 'ge' )? '%d' : 'd';
        $this->month_long_format = !version_compare( JVERSION, '1.6.0', 'ge' )? '%B' : 'F';
        $this->month_short_format = !version_compare( JVERSION, '1.6.0', 'ge' )? '%b' : 'M';
        $this->year_format = !version_compare( JVERSION, '1.6.0', 'ge' )? '%Y' : 'Y';

        $this->user_entry_point = 'com_users';
        if(version_compare(JVERSION,'1.6.0','ge'))
            $this->user_entry_point = 'com_users';
        elseif(version_compare(JVERSION,'1.5.0','ge'))
            $this->user_entry_point = 'com_user';

		parent::display($tpl);
	}

	function _addScripts($step, $is_modal=false)
	{
        if(!$is_modal) {
           JHTML::_('behavior.mootools');
           require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_behavior.php');
        }
        jimport('joomla.utilities.date');
        jimport('joomla.environment.browser');
        $document =& JFactory::getDocument();
        $browser =& JBrowser::getInstance();
        $this->is_ie = is_int(strpos($browser->getBrowser(), 'msie'));
        if($this->is_ie) {
            $document->addStyleSheet('components/com_jobboard/css/user_ie.css');
            if(intval($browser->getVersion()) > 7){
               $cleafix = ".clearfix {display: block;}";
               $document->addStyleDeclaration($cleafix);
            }
            $this->is_early_ie = intval($browser->getVersion()) < 7? true : false;
        }

        //add cv profile switch
        switch($step) {
          case 1:
                $js_vars  = 'var jbVars ={"siteRoot": "'.JURI::base().'", "maxFiles": '.$this->config->max_files.', "txtTitle": "'.JText::_('TITLE').'", "txtUplfile": "'.JText::_('COM_JOBBOARD_FILETOUPLOAD').'", "txtRemove": "'.JText::_('COM_JOBBOARD_TXTREMOVE').'", "txtLiImport": "'.JText::_('COM_JOBBOARD_IMPORTINGLINKEDIN').'", fileNum:'.$this->file_count.'};';
                $document->addScriptDeclaration($js_vars);
          break;
          case 2:
                $js_vars  = 'var jbVars ={"txtEdu": "'.JText::_('EDUCATION').'", "txtType": "'.JText::_('COM_JOBBOARD_TXTTYPE').'", "txtQualname": "'.JText::_('COM_JOBBOARD_QUALNAME').'", "txtSchoolname": "'.JText::_('COM_JOBBOARD_SCHOOLNAME').'", "txtCountry": "'.JText::_('COM_JOBBOARD_TXTCOUNTRY').'", "txtCity": "'.JText::_('COM_JOBBOARD_TXTCITY').'", "txtQualyr": "'.JText::_('COM_JOBBOARD_QUALYEAR').'", "txtEmployer": "'.JText::_('COM_JOBBOARD_EMPLOYER').'", "txtCompany": "'.JText::_('COM_JOBBOARD_TXTCOMPANY').'", "txtJobtitle": "'.JText::_('JOB_TITLE').'", "txtPresent": "'.JText::_('COM_JOBBOARD_TXTPRESENT').'", "txtStartyr": "'.JText::_('COM_JOBBOARD_START').'", "txtEndyr": "'.JText::_('COM_JOBBOARD_END').'", "txtEmplCurr": "'.JText::_('COM_JOBBOARD_ISCURRENT_EMPL').'", "txtNoMoreThan": "'.JText::_('COM_JOBBOARD_TXTNOTMORETHAN').'", "txtRemove": "'.JText::_('COM_JOBBOARD_TXTREMOVE').'", "txtEntriesAllwd": "'.JText::_('COM_JOBBOARD_TXTENTRIESALLOWED').'", "currYear": '.$this->today->toFormat("%Y").', "countries": '.json_encode($this->country_options).', "employer":[], "edu":[], "defaultCountry": '.$this->config->default_country.', "maxQuals": '.$this->config->max_quals.', "maxEmployers": '.$this->config->max_employers.', "qualNum": '.$this->quals_count.', "emplNum": '.$this->employer_count.', "edLevels":'.json_encode($this->ed_level_opts).', "months":'.json_encode($this->months).'};';
                $document->addScriptDeclaration($js_vars);
          break;                                                                                                                                                                    //
          case 3:
                $js_vars  = 'var jbVars ={"maxSkills": '.$this->config->max_skills.', "txtNoMoreThan": "'.JText::_('COM_JOBBOARD_TXTNOTMORETHAN').'", "txtSkillsAllwd": "'.JText::_('COM_JOBBOARD_TXTSKILLENTRIESALLOWED').'", "txtCurrent": "'.JText::_('COM_JOBBOARD_TXTCURRENT').'", "txtRemoveSkill": "'.JText::_('COM_JOBBOARD_TXTREMOVE').'", "skillNum": '.$this->skills_count.', "currYear": '.$this->today->toFormat("%Y").'};';
                $document->addScriptDeclaration($js_vars);
          break;
          case 4:

          break;
          default:
          ;break;

        }

        //user profile javascript
        if(isset($this->currtab)) {

          switch($this->currtab) {
             case 1:

                 $js_vars  = 'var jbVars ={"userTab": '.$this->currtab.', "tKn": "'.JUtility::getToken().'"};';
                 $document->addScriptDeclaration($js_vars);
             break;
             case 2:
                 $js_vars  = 'var jbaseUrl = "'.JURI::base().'"; var jbVars ={"userTab": '.$this->currtab.', "txtImgPresent": "'.JText::_('COM_JOBBOARD_PICPRESENT').'", "txtImgAbsent": "'.JText::_('COM_JOBBOARD_PICABSENT').'", "txtEditOn": "'.JText::_('COM_JOBBOARD_PICEDMODE').'", "tKn": "'.JUtility::getToken().'"};';
                 $document->addScriptDeclaration($js_vars);
             break;
             case 3:

                 $js_vars  = 'var jbVars ={"userTab": '.$this->currtab.', "tKn": "'.JUtility::getToken().'"};';
                 $document->addScriptDeclaration($js_vars);
             break;
             case 4:

                 $js_vars  = 'var jbVars ={"userTab": '.$this->currtab.', "tKn": "'.JUtility::getToken().'"};';
                 $document->addScriptDeclaration($js_vars);
             break;
             case 5:

                 $js_vars  = 'var jbVars ={"userTab": '.$this->currtab.', "tKn": "'.JUtility::getToken().'"};';
                 $document->addScriptDeclaration($js_vars);
             break;
             default:
             ;break;
          } //end switch

        } //end if

        //global
        $js_vars  = 'var profilePicPresent = '.$this->is_profile_pic.';';
        $document->addScriptDeclaration($js_vars);

        switch($this->context) {
             case 'cvprofiles':
                  $pagination =& $this->get('Pagination');
                  $this->assignRef('pagination', $pagination);
                  $results_count =  $this->pagination->getResultsCounter();
                  $this->assignRef('results_count', $results_count);
             break;
             case 'marked':
                  $pagination =& $this->get('Pagination');
                  $this->assignRef('pagination', $pagination);
                  $results_count =  $this->pagination->getResultsCounter();
                  $this->assignRef('results_count', $results_count);
             break;
             case 'invites':
                  $app = & JFactory::getApplication();
                  $cat_id = $app->getUserState('com_jobboard.list.selcat', 1);
                  $pagination =& $this->get('Pagination');
                  $this->assignRef('pagination', $pagination);
                  $results_count =  $this->pagination->getResultsCounter();
                  $this->assignRef('results_count', $results_count);
                  $this->assign('cat_id', $cat_id);
             break;
             case 'applications':
                  require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_user.php' );
                  $pagination =& $this->get('Pagination');
                  $this->assignRef('pagination', $pagination);
                  $results_count =  $this->pagination->getResultsCounter();
                  $this->assignRef('results_count', $results_count);
                  $this->show_status = JobBoardUserHelper::showApplStatus();
             break;
        }
	}

    function check()
    {
        jimport( 'joomla.filter.output' );
       $app =& JFactory::getApplication();
       $menu =& $app->getMenu();
       $item =& $menu->getActive();
        if(empty($this->alias)) {
    	    $this->alias = $this->title;
        }
        $this->alias = JFilterOutput::stringURLSafe($this->alias);

    }
}

?>