<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

class JobBoardAdmuserHelper
{

    static function checkProfilePicStatus($uid, &$user_model, $mode=1) {
        $result = array();
        $prof_id = intval($user_model->userProfileExists($uid));
        if($prof_id > 0 ) {
          $imgdata = $user_model->getProfileImageByUserId($uid);
          $imgdata->thumbpath = $imgdata->profile_image_path.DS.'thumbs'.DS.'thumb48_'.$imgdata->profile_image_name;
          $result['urithumb'] = str_replace('\\', '/' , str_ireplace(JPATH_SITE.DS, '', $imgdata->thumbpath));
          switch($mode) {
            case 1: //48 x 48 thumb only
                    //do nothing
            break;
            case 2: //include 115 x 115 thumb
                $imgdata->thumbpath = $imgdata->profile_image_path.DS.'thumbs'.DS.'thumb115_'.$imgdata->profile_image_name;
                $result['urithumb2'] = str_replace('\\', '/' , str_ireplace(JPATH_SITE.DS, '', $imgdata->thumbpath));
            break;
            case 3: //Full picture
                $result['fullpath'] = $imgdata->profile_image_path.DS.$imgdata->profile_image_name;
                $result['rootpath'] = $imgdata->profile_image_path;
                $result['picname'] = $imgdata->profile_image_name;
                $result['uripath'] = str_replace('\\', '/' , str_ireplace(JPATH_SITE.DS, '', $result['fullpath']));
            break;

          }
          $is_profile_pic = $imgdata->profile_image_present;
        } else {
          $is_profile_pic = 0;
        }
        $result['is_profile_pic'] = $is_profile_pic;
        return $result;
    }

    function byteConvert($bytes)
    {
    $size = $bytes / 1024;
    if($size < 1024)  {
        $size = number_format($size, 2);
        $size .= ' KB';
        }
    else
        {
        if($size / 1024 < 1024) {
            $size = number_format($size / 1024, 2);
            $size .= ' MB';
            }
        else if ($size / 1024 / 1024 < 1024)   {
            $size = number_format($size / 1024 / 1024, 2);
            $size .= ' GB';
            }
        }
    return $size;
    }

    /**
	 * Generate Random key
	 *
	 **/
	function randKey(){
		return sprintf(
  				 '%04x%02x%03x'
                 ,mt_rand()
                 ,mt_rand(0, 65535)
                 ,bindec(substr_replace(sprintf('%016b', mt_rand(0, 65535)), '0100', 11, 4))
                      );
	}

    function reorderSkills($arr, $count, $incl_id=false) {
            // Organise skills records into an orderly array
           $skills_arr = array();
           for($i=1; $i<=$count; $i++)
                {
                  if($arr['skillname'][$i] != '' && $arr['experience'][$i] != '') {

                       if($incl_id == true) {
                          $arr['skill_id'][$i] = isset($arr['skill_id'][$i])? $arr['skill_id'][$i] : 0;
                          $skills_arr[$i-1]['id'] = $arr['skill_id'][$i];
                       }
                       $skills_arr[$i-1]['skill_name'] = $arr['skillname'][$i];
                       $skills_arr[$i-1]['last_use'] = $arr['lastused'][$i] == 0? '0000-00-00' : $arr['lastused'][$i].'-11-01';
                       $skills_arr[$i-1]['experience_period'] = $arr['experience'][$i];
                  }
            }
            return $skills_arr;
    }

    function reorderEdu($arr, $count, $incl_id=false) {
            // Organise education records into an orderly array
            $edu_arr = array();
            for($i=1; $i<=$count; $i++)
            {
                 if($incl_id == true) {
                    $arr['edu_id'][$i] = isset($arr['edu_id'][$i])? $arr['edu_id'][$i] : 0;
                    $edu_arr[$i-1]['id'] = $arr['edu_id'][$i];
                 }
                 $edu_arr[$i-1]['edtype'] = $arr['edtype'][$i];
                 $edu_arr[$i-1]['qual_name'] = $arr['qual_name'][$i];
                 $edu_arr[$i-1]['school_name'] = $arr['school_name'][$i];
                 $edu_arr[$i-1]['edu_country'] = $arr['edu_country'][$i];
                 $edu_arr[$i-1]['country_id'] = $arr['edu_country'][$i];
                 $edu_arr[$i-1]['location'] = $arr['ed_location'][$i];
                 $edu_arr[$i-1]['ed_yr'] = $arr['ed_year'][$i] == -1? '0000-00-00' : $arr['ed_year'][$i].'-11-01';
                 $edu_arr[$i-1]['ed_year'] = $edu_arr[$i-1]['ed_yr'];
                 $ed_highest = $i == 1? 1 : 0;
                 $edu_arr[$i-1]['highest'] = $ed_highest;
            }
            return $edu_arr;
    }

    function reorderEmpl($arr, $count, $incl_id=false) {
       // Organise employment records into an orderly array
            $empl_arr = array();
            for($i=1; $i<=$count; $i++)
            {    if($arr['company'][$i] != '' && $arr['job_title'][$i] != '' && $arr['employer_city'][$i] != '') {
                     if($incl_id == true) {
                        $arr['empl_id'][$i] = isset($arr['empl_id'][$i])? $arr['empl_id'][$i] : 0;
                        $empl_arr[$i-1]['id'] = $arr['empl_id'][$i];
                     }
                     $empl_arr[$i-1]['company_name'] = $arr['company'][$i];
                     $empl_arr[$i-1]['job_title'] = $arr['job_title'][$i];
                     $empl_arr[$i-1]['country_id'] = $arr['employer_country'][$i];
                     $empl_arr[$i-1]['location'] = $arr['employer_city'][$i];
                     if(isset($arr['startyear'][$i])) {
                     $arr['startyear'][$i] = $arr['startyear'][$i] == -1? '0000-00-00' : $arr['startyear'][$i].'-'.$arr['startmon'][$i].'-01';
                     } else $arr['startyear'][$i] = '0000-00-00';
                     $empl_arr[$i-1]['start_yr'] = $arr['startyear'][$i];
                     if(isset($arr['endyear'][$i])) {
                        $arr['endyear'][$i] = $arr['endyear'][$i] == -1? '0000-00-00' : $arr['endyear'][$i].'-'.$arr['endmon'][$i].'-27'; //27 to accommodate feb month end
                     } else $arr['endyear'][$i] = '0000-00-00';
                     $empl_arr[$i-1]['end_yr'] = $arr['endyear'][$i];
                     $empl_most_recent = $i == 1? 1 : 0;
                     $empl_arr[$i-1]['most_recent'] = $empl_most_recent;
                     if((isset($arr['empl_iscurrent'][$i]))) {
                        $arr['empl_iscurrent'][$i] = $arr['empl_iscurrent'][$i] == 'yes'? 1 : 0;
                     } else
                        $arr['empl_iscurrent'][$i] = 0;

                     $empl_arr[$i-1]['current'] = $arr['empl_iscurrent'][$i];
                }
            }
            return $empl_arr;
    }

     function reorderLinkedIn($arr, $defaults=null) {
            // Organise LinkedIn records into an orderly array
            $employer_arr = array();
            $edu_arr = array();
            $skills_arr = array();

            if(isset($arr['employer_name'])) {
              $empl_count = count($arr['employer_name']);
              for($i=0; $i<$empl_count; $i++)
              {
                 $empl_arr[$i]['company_name'] = $arr['employer_name'][$i];
                 $empl_arr[$i]['job_title'] = $arr['job_title'][$i];
                 $empl_arr[$i]['country_id'] = $defaults->default_country;
                 $empl_arr[$i]['location'] = $defaults->default_city;
                 if(!isset($arr['empl_start'][$i]))
                    $arr['empl_start'][$i] = '0000-00-00';
                 $empl_arr[$i]['start_yr'] = $arr['empl_start'][$i];
                 if(!isset($arr['empl_end'][$i]))
                    $arr['empl_end'][$i] = '0000-00-00';
                 $empl_arr[$i]['end_yr'] = $arr['empl_end'][$i];
                 $empl_most_recent = $i == 0? 1 : 0;
                 $empl_arr[$i]['most_recent'] = $empl_most_recent;
                 $empl_arr[$i]['current'] = $arr['empl_iscurr'][$i];
              }
            }

            if(isset($arr['school_name'])) {
              $ed_count = count($arr['school_name']);
              for($i=0; $i<$ed_count; $i++)
              {
                 $edu_arr[$i]['edtype'] = 2;  //not available from linkedin. Default to bachelor's degree
                 $edu_arr[$i]['school_name'] = $arr['school_name'][$i];
                 $edu_arr[$i]['qual_name'] = $arr['qual_name'][$i];
                 if(isset($arr['school_end'][$i]))
                    $arr['school_end'][$i] = $arr['school_end'][$i] == ''? '0000-00-00' : $arr['school_end'][$i].'-11-01';
                 $edu_arr[$i]['ed_yr'] = $arr['school_end'][$i];
                 $edu_arr[$i]['ed_year'] = $edu_arr[$i]['ed_yr'];
                 $edu_arr[$i]['location'] = $defaults->default_city;
                 $edu_arr[$i]['edu_country'] = $defaults->default_country;
                 $ed_highest = $i == 0? 1 : 0;
                 $edu_arr[$i]['highest'] = $ed_highest;
              }
            }

            if(isset($arr['skillname'])) {

              foreach($arr['skillname'] as $skill)
              {
                 $skills_arr[] = array('skill_name' => $skill, 'last_use' => '0000-00-00', 'experience_period' => 0);   //last_use & experience not available from linkedin
              }
            }
            return array('edu' => $edu_arr, 'empl' => $empl_arr, 'skills' => $skills_arr);
    }

    //LinkedIn Sync

    function liSyncEdu($li_data, $edu)  {
          $li_data['edtype'] = $edu->edtype;
          $li_data['edu_country'] = $edu->edu_country;
          $li_data['country_id'] = $edu->edu_country;
          $li_data['ed_yr'] = $li_data['ed_year'];
          $li_data['location'] = $edu->location;
          $li_data['country_name'] = $edu->country_name;
          return  $li_data;
    }

    function liSyncEmpl($li_data, $empl)  {
          $li_data['country_id'] = $empl->country_id;
          $li_data['location'] = $empl->location;
          $li_data['country_name'] = $empl->country_name;
          return  $li_data;
    }

    function liSyncSkill($li_data, $skill)  {
          $li_data['last_use'] = $skill->last_use;
          $li_data['experience_period'] = $skill->experience_period;
          return  $li_data;
    }

}

?>