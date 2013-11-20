<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

class JobBoardHelper
{
    static function checkProfilePicStatus($uid, $umodel, $mode=1) {
        $result = array();
        $prof_id = intval($umodel->userProfileExists($uid));
        if($prof_id > 0 ) {
          $imgdata = $umodel->getProfileImageByUserId($uid);
          $imgdata->thumbpath = $imgdata->profile_image_path.DS.'thumbs'.DS.'thumb48_'.$imgdata->profile_image_name;
          $result['urithumb'] = str_replace('\\', '/' , str_ireplace(JPATH_BASE.DS, '', $imgdata->thumbpath));
          switch($mode) {
            case 1: //48 x 48 thumb only
                    //do nothing
            break;
            case 2: //include 115 x 115 thumb
                $imgdata->thumbpath = $imgdata->profile_image_path.DS.'thumbs'.DS.'thumb115_'.$imgdata->profile_image_name;
                $result['urithumb2'] = str_replace('\\', '/' , str_ireplace(JPATH_BASE.DS, '', $imgdata->thumbpath));
            break;
            case 3: //Full picture
                $result['fullpath'] = $imgdata->profile_image_path.DS.$imgdata->profile_image_name;
                $result['rootpath'] = $imgdata->profile_image_path;
                $result['picname'] = $imgdata->profile_image_name;
                $result['uripath'] = str_replace('\\', '/' , str_ireplace(JPATH_BASE.DS, '', $result['fullpath']));
            break;

          }
          $is_profile_pic = $imgdata->profile_image_present;
        } else {
          $is_profile_pic = 0;
        }
        $result['is_profile_pic'] = $is_profile_pic;
        return $result;
    }

    static function getMonthsList(){
      jimport('joomla.utilities.date');
      $months = array();
        for($m = 1; $m < 13; $m++) {
          $dt = new JDate('2000-'.sprintf("%02d",$m).'-01');
          $months[] = $dt->toFormat("%b");
        }
      return $months;
    }

    static function getToday($ym=false) {
      jimport('joomla.utilities.date');
      $format = $ym == true? '%Y-%m' : '%Y-%m-%d';
      $today_do = new JDate();
      return $today_do->toFormat($format);
    }

     static function getSite($site, $port=80)
     {
        $fp = @fsockopen($site, $port, $errno, $errstr, 2);
        $result = !$fp? false : true;
        @fclose($fp);
        return $result;
     }

    static function byteConvert($bytes)
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
	static function randKey(){
		return sprintf(
  				 '%04x%02x%03x'
                 ,mt_rand()
                 ,mt_rand(0, 65535)
                 ,bindec(substr_replace(sprintf('%016b', mt_rand(0, 65535)), '0100', 11, 4))
          );
	}

    /**
	 * Generate Random string
	 *
	 **/
	static function randStr($s){
		return sprintf(
  				 '%03x%02x', $s, mt_rand(0, 65535));
	}

    static function matchHumanCode($string)  {
       $app = &JFactory::getApplication();
       return (strlen($string) == 0 || $string != $app->getUserState('com_jobboard.humanv'))? true : false;
    }

	static function renderJobBoard()
    {
        $show_link = self::_getRenderConfig(1);
        switch($show_link) {
          case 0:
            echo base64_decode('PGRpdiBzdHlsZT0ibWFyZ2luOiAwOyB3aWR0aDogMTAwJTsgYm9yZGVyLXRvcDpub25lOyI+PHNwYW4gc3R5bGU9InRleHQtaW5kZW50OiA1cHgiPjxzbWFsbD4mbmJzcDs8L3NtYWxsPjwvc3Bhbj48L2Rpdj4=');
          break;
          case 1:
    		return base64_decode('PGRpdiBjbGFzcz0iY2xlYXIiPjxzbWFsbD5Qb3dlcmVkIGJ5Jm5ic3A7PGEgaHJlZj0iaHR0cDovL2pvYmJvYXJkLWRlbW8uY28ubmYiIHRhcmdldD0iX2JsYW5rIj5Kb2IgYm9hcmQgZm9yIEpvb21sYSEgdjEuMi43LjE8L2E+Jm5ic3A7PC9zbWFsbD48L2Rpdj4=');
          break;
          default:
    		return base64_decode('PGRpdiBjbGFzcz0iY2xlYXIiPjxzbWFsbD5Qb3dlcmVkIGJ5Jm5ic3A7PGEgaHJlZj0iaHR0cDovL2pvYmJvYXJkLWRlbW8uY28ubmYiIHRhcmdldD0iX2JsYW5rIj5Kb2IgYm9hcmQgZm9yIEpvb21sYSEgdjEuMi43LjE8L2E+Jm5ic3A7PC9zbWFsbD48L2Rpdj4=');
          ;break;
        }
	}

	static function renderJobBoardx()
    {
        $show_link = self::_getRenderConfig();
        switch($show_link) {
          case 0:
    		return base64_decode('PGRpdiBzdHlsZT0ibWFyZ2luOiAwOyB3aWR0aDogMTAwJTsgYm9yZGVyLXRvcDpub25lOyI+PHNwYW4gc3R5bGU9InRleHQtaW5kZW50OiA1cHgiPjxzbWFsbD4mbmJzcDs8L3NtYWxsPjwvc3Bhbj48L2Rpdj4=');
          break;
          case 1:
            return base64_decode('PHRhYmxlIHN0eWxlPSJtYXJnaW4tYm90dG9tOiA1cHg7IHdpZHRoOiAxMDAlOyBib3JkZXItdG9wOiB0aGluIHNvbGlkICNlNWU1ZTU7Ij48dGJvZHk+PHRyPjx0ZCBzdHlsZT0idGV4dC1hbGlnbjogbGVmdDsgd2lkdGg6IDMzJTsiPjxoND5Kb2IgYm9hcmQ8L2g0PiZuYnNwOyZuYnNwOyZuYnNwOzxhIGhyZWY9Imh0dHA6Ly9leHRlbnNpb25zLmpvb21sYS5vcmcvZXh0ZW5zaW9ucy9hZHMtYS1hZmZpbGlhdGVzL2pvYnMtYS1yZWNydWl0bWVudC8xNDExOCIgdGFyZ2V0PSJfYmxhbmsiPkpFRCByZXZpZXdzPC9hPjxici8+Jm5ic3A7Jm5ic3A7Jm5ic3A7PGEgaHJlZj0iaHR0cDovL2pvYmJvYXJkLWRlbW8uY28ubmYvZm9ydW0iIHRhcmdldD0iX2JsYW5rIj5WaXNpdCBKb2IgQm9hcmQgZm9ydW08L2E+PGJyLz5Db25uZWN0IG9uJm5ic3A7Jm5ic3A7Jm5ic3A7PGEgaHJlZj0iaHR0cDovL2JpdC5seS90YW5kb2xpbi10d2l0dGVyIiB0YXJnZXQ9Il9ibGFuayI+VHdpdHRlcjwvYT4gfCZuYnNwOyZuYnNwOyZuYnNwOzxhIGhyZWY9Imh0dHA6Ly9iaXQubHkvdGFuZG9saW4tbGlua2VkaW4iIHRhcmdldD0iX2JsYW5rIj5MaW5rZWRJTjwvYT4gfCZuYnNwOyZuYnNwOyZuYnNwOzxhIGhyZWY9Imh0dHA6Ly9iaXQubHkvdGFuZG9saW4tZmIiIHRhcmdldD0iX2JsYW5rIj5GYWNlYm9vazwvYT48YnIvPjwvdGQ+PHRkIHN0eWxlPSJ0ZXh0LWFsaWduOiBjZW50ZXI7IHdpZHRoOiAzMyU7Ij5Kb2IgQm9hcmQgLSBWZXJzaW9uIDEuMi43LjEgZnVsbDogQSBqb2IgYm9hcmQgY29tcG9uZW50IGZvciBKb29tbGE8YnIvPkNvcHlyaWdodDogJmNvcHk7IDIwMTAtMjAxMzxhIGhyZWY9Imh0dHA6Ly9maWdvbWFnby53b3JkcHJlc3MuY29tIiB0YXJnZXQ9Il9ibGFuayI+VGFuZG9saW4gQ29uc3VsdGFudHMgY2MgYW5kIEZpZ28gTWFnbzwvYT48L3RkPjx0ZCBzdHlsZT0idGV4dC1hbGlnbjogcmlnaHQ7IHdpZHRoOiAzMyU7cGFkZGluZy10b3A6NXB4Ij48YSBocmVmPSJodHRwOi8vam9iYm9hcmQtZGVtby5jby5uZi8iIHRhcmdldD0iX2JsYW5rIj48aW1nIHNyYz0iY29tcG9uZW50cy9jb21fam9iYm9hcmQvaW1hZ2VzL2pvYmJvYXJkX2xvZ28ucG5nIj48L2ltZz48L2E+PC90ZD48L3RyPjwvdGJvZHk+PC90YWJsZT4=');
          break;
          default:
             return base64_decode('PHRhYmxlIHN0eWxlPSJtYXJnaW4tYm90dG9tOiA1cHg7IHdpZHRoOiAxMDAlOyBib3JkZXItdG9wOiB0aGluIHNvbGlkICNlNWU1ZTU7Ij48dGJvZHk+PHRyPjx0ZCBzdHlsZT0idGV4dC1hbGlnbjogbGVmdDsgd2lkdGg6IDMzJTsiPjxoND5Kb2IgYm9hcmQ8L2g0PiZuYnNwOyZuYnNwOyZuYnNwOzxhIGhyZWY9Imh0dHA6Ly9leHRlbnNpb25zLmpvb21sYS5vcmcvZXh0ZW5zaW9ucy9hZHMtYS1hZmZpbGlhdGVzL2pvYnMtYS1yZWNydWl0bWVudC8xNDExOCIgdGFyZ2V0PSJfYmxhbmsiPkpFRCByZXZpZXdzPC9hPjxici8+Jm5ic3A7Jm5ic3A7Jm5ic3A7PGEgaHJlZj0iaHR0cDovL2pvYmJvYXJkLWRlbW8uY28ubmYvZm9ydW0iIHRhcmdldD0iX2JsYW5rIj5WaXNpdCBKb2IgQm9hcmQgZm9ydW08L2E+PGJyLz5Db25uZWN0IG9uJm5ic3A7Jm5ic3A7Jm5ic3A7PGEgaHJlZj0iaHR0cDovL2JpdC5seS90YW5kb2xpbi10d2l0dGVyIiB0YXJnZXQ9Il9ibGFuayI+VHdpdHRlcjwvYT4gfCZuYnNwOyZuYnNwOyZuYnNwOzxhIGhyZWY9Imh0dHA6Ly9iaXQubHkvdGFuZG9saW4tbGlua2VkaW4iIHRhcmdldD0iX2JsYW5rIj5MaW5rZWRJTjwvYT4gfCZuYnNwOyZuYnNwOyZuYnNwOzxhIGhyZWY9Imh0dHA6Ly9iaXQubHkvdGFuZG9saW4tZmIiIHRhcmdldD0iX2JsYW5rIj5GYWNlYm9vazwvYT48YnIvPjwvdGQ+PHRkIHN0eWxlPSJ0ZXh0LWFsaWduOiBjZW50ZXI7IHdpZHRoOiAzMyU7Ij5Kb2IgQm9hcmQgLSBWZXJzaW9uIDEuMi43LjEgZnVsbDogQSBqb2IgYm9hcmQgY29tcG9uZW50IGZvciBKb29tbGE8YnIvPkNvcHlyaWdodDogJmNvcHk7IDIwMTAtMjAxMzxhIGhyZWY9Imh0dHA6Ly9maWdvbWFnby53b3JkcHJlc3MuY29tIiB0YXJnZXQ9Il9ibGFuayI+VGFuZG9saW4gQ29uc3VsdGFudHMgY2MgYW5kIEZpZ28gTWFnbzwvYT48L3RkPjx0ZCBzdHlsZT0idGV4dC1hbGlnbjogcmlnaHQ7IHdpZHRoOiAzMyU7cGFkZGluZy10b3A6NXB4Ij48YSBocmVmPSJodHRwOi8vam9iYm9hcmQtZGVtby5jby5uZi8iIHRhcmdldD0iX2JsYW5rIj48aW1nIHNyYz0iY29tcG9uZW50cy9jb21fam9iYm9hcmQvaW1hZ2VzL2pvYmJvYXJkX2xvZ28ucG5nIj48L2ltZz48L2E+PC90ZD48L3RyPjwvdGJvZHk+PC90YWJsZT4=');
          ;break;
        }
	}

    private function _getRenderConfig($type=0){
        $db = & JFactory::getDBO();
        switch($type) {
          case 0:
            $query = 'SELECT '.$db->nameQuote('admin_show_backlink').' FROM '.$db->nameQuote('#__jobboard_config').'
                WHERE '.$db->nameQuote('id').' = 1';
          break;
          case 1:
            $query = 'SELECT '.$db->nameQuote('user_show_backlink').' FROM '.$db->nameQuote('#__jobboard_config').'
                WHERE '.$db->nameQuote('id').' = 1';
          break;
          default:
          ;break;
        }
        $db->setQuery($query);
        return $db->loadResult();
    }

    static function getCountryName($id) {
        $db = & JFactory::getDBO();
        $query = 'SELECT '.$db->nameQuote('country_name').' FROM '.$db->nameQuote('#__jobboard_countries').'
            WHERE '.$db->nameQuote('country_id').' = '.$id;
        $db->setQuery($query);
        return $db->loadResult();

    }

    static function useSecure(){
        $db = & JFactory::getDBO();
        $query = 'SELECT '.$db->nameQuote('secure_login').' FROM '.$db->nameQuote('#__jobboard_config').'
            WHERE '.$db->nameQuote('id').' = 1';
        $db->setQuery($query);
        return $db->loadResult();
    }

    static function allowRegistration()  {
        $db = & JFactory::getDBO();
        $query = 'SELECT '.$db->nameQuote('allow_registration').' FROM '.$db->nameQuote('#__jobboard_config').'
            WHERE '.$db->nameQuote('id').' = 1';
        $db->setQuery($query);
        return ($db->loadResult() == 1)? true : false;
    }

    static function verifyLogin()  {
        $db = & JFactory::getDBO();
        $query = 'SELECT '.$db->nameQuote('captcha_login').' FROM '.$db->nameQuote('#__jobboard_config').'
            WHERE '.$db->nameQuote('id').' = 1';
        $db->setQuery($query);
        return ($db->loadResult() == 1)? true : false;
    }

    static function verifyHumans()  {
        $db = & JFactory::getDBO();
        $query = 'SELECT '.$db->nameQuote('captcha_public').' FROM '.$db->nameQuote('#__jobboard_config').'
            WHERE '.$db->nameQuote('id').' = 1';
        $db->setQuery($query);
        return ($db->loadResult() == 1)? true : false;
    }

    static function dispatchEmail($from, $fromname, $to_email, $subject, $body, $attachment = null) {
       if(!version_compare( JVERSION, '1.6.0', 'ge' ))
          $sendresult =& JUtility :: sendMail($from, $fromname, $to_email, $subject, $body, null, null, null, $attachment);
        else
          $sendresult =& JFactory::getMailer()->sendMail($from, $fromname, $to_email, $subject, $body, null, null, null, $attachment);

       return $sendresult;
    }
}

?>