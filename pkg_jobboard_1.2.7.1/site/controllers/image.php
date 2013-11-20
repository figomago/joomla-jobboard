<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');


// Load framework base classes
jimport('joomla.application.component.controller');

class JobboardControllerImage extends JController
{
    /**
	 * constructor
	 */
	function __construct()
	{
		parent::__construct();
		$this->registerTask('imgdel', 'usrImgDel');
		$this->registerTask('upload', 'imgFileUploadProcess');
		$this->registerTask('saveimg', 'saveImage');
		$this->registerTask('crop', 'cropImg');
	    $user = & JFactory::getUser();
        $uid = $user->id;
        $this->_setUid($uid);
	}

    private function _setUid($uid){
       if($uid == 0)  {
         // $return = base64_encode(JRoute::_('index.php?option=com_jobboard&view=member'));
        //  return $this->setRedirect(JRoute::_('index.php?option=com_users&task=login&return='.$return));
            return $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=member'));
        } else {
          $this->_uid = $uid;
        }
    }

	function cropImg()
	{
        $x = JRequest::getInt('x');
        $y = JRequest::getInt('y');
        $w = JRequest::getInt('w');
        $h = JRequest::getInt('h');

		$user_model =& $this->getModel('User');

        $imgdata = $user_model->getProfileImageByUserId($this->_uid);
        $filename = $imgdata->profile_image_path.DS.$imgdata->profile_image_name;

        if(!JFile::exists($filename)){
			return false;
		}
       $maxwidth = 130;
       $maxheight = 130;

       $view  =& $this->getView('image', 'img');
       $view->assign('x', $x);
       $view->assign('y', $y);
       $view->assign('w', $w);
       $view->assign('h', $h);
       $view->assign('maxwidth', $maxwidth);
       $view->assign('maxheight', $maxheight);
       $view->assign('filename', $filename);

	   $view->display();
	}

	function saveImage()
	{
	    // Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
        require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard.php' );
        $err_msg = array();

        $w = JRequest::getInt('crop_w');
        $h = JRequest::getInt('crop_h');
        $x = JRequest::getInt('crop_x');
        $y = JRequest::getInt('crop_y');

        if($w == 0 && $h == 0 && $x == 0 && $y == 0) {  // No changes to thumbnails
          $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=user&task=prof&tab=2'), JText::_('COM_JOBBOARD_PICSAVED'), 'mesage');
          $this->redirect();
        }

        //$this->usrImgDel(false, false); // delete current thumbs to circumvent browser caching

		$user_model =& $this->getModel('User');
        $profile_pic = JobBoardHelper::checkProfilePicStatus($this->_uid, $user_model, 3);

        $save_path = $profile_pic['rootpath'].DS.'thumbs';
        $save_path48 = $profile_pic['rootpath'].DS.'thumbs'.DS.'thumb48_'.$profile_pic['picname'];
        $save_path115 = $profile_pic['rootpath'].DS.'thumbs'.DS.'thumb115_'.$profile_pic['picname'];

        if(!JFolder::exists($profile_pic['rootpath']))
        {
            $profile_pic_folder_created = JFolder::create($profile_pic['rootpath']);
            if($profile_pic_folder_created == false)
            {
              $err_msg[] = JText::_('COM_JOBBOARD_USERFOLDER_CREATE_ERR');
            }
        }

        $sec_file = $profile_pic['rootpath'].DS.'index.html';
        if(!JFile::exists($sec_file))
        {
            $_html = '<!DOCTYPE html><title></title>';
            JFile::write($sec_file, $_html);
        }

        if(!JFolder::exists($save_path))
        {
            $user_folder_created = JFolder::create($save_path);
            if($user_folder_created == false)
            {
              $err_msg[] = JText::_('COM_JOBBOARD_USERFOLDER_CREATE_ERR');
            }
        }

        $sec_file = $save_path.DS.'index.html';
        if(!JFile::exists($sec_file))
        {
            $_html = '<!DOCTYPE html><title></title>';
            JFile::write($sec_file, $_html);
        }

        $image_info = getimagesize($profile_pic['fullpath']);

        $max_w115 = 115;  $max_h115 = 115;
        $max_w48 = 48; $max_h48 = 48;

        $ratio_cropped = $w/$h;
        if ($max_w115/$max_h115 > $ratio_cropped)
        {
           $max_w115 = $max_h115*$ratio_cropped;
        } else
        {
           $max_h115 = $max_w115/$ratio_cropped;
        }
        if ($max_w48/$max_h48 > $ratio_cropped) {
           $max_w48 = $max_h48*$ratio_cropped;
        } else
        {
           $max_h48 = $max_w48/$ratio_cropped;
        }

        switch($image_info[2]) {
            case IMAGETYPE_JPEG :
                    // Load
                    $src = @imagecreatefromjpeg($profile_pic['fullpath']);
                    $img_48 = @imagecreatetruecolor($max_w48, $max_h48);
                    $img_115 = @imagecreatetruecolor($max_w115, $max_h115);

                    // Resize cropped
                    imagecopyresampled($img_48, $src, 0, 0, $x, $y, $max_w48, $max_h48, $w, $h);
                    imagecopyresampled($img_115, $src, 0, 0, $x, $y, $max_w115, $max_h115, $w, $h);
                    // Output
                    if(!imagejpeg($img_48, $save_path48, 100) || !imagejpeg($img_115, $save_path115, 100))
                    {
                       $err_msg[] = JText::_( 'COM_JOBBOARD_USER_IMGCREATE_ERR');
                    }
            break;
            case IMAGETYPE_GIF :
                    $src = @imagecreatefromgif($profile_pic['fullpath']);
                    $transp_index = imagecolortransparent( $src );
                    $img_48 = @imagecreatetruecolor($max_w48, $max_h48);
                    $img_115 = @imagecreatetruecolor($max_w115, $max_h115);
                    if ( $transp_index >= 0 )
          			{
          				// Get the original image's transparent color's RGB values
          				$transp_color = imagecolorsforindex( $src, $transp_index );

          				// Allocate the same color in the new image resource
          				$trans_index_48 = imagecolorallocate( $img_48, $transp_color['red'], $transp_color['green'], $transp_color['blue'] );
          				$trans_index_115 = imagecolorallocate( $img_115, $transp_color['red'], $transp_color['green'], $transp_color['blue'] );

          				// Completely fill the background of the new image with allocated color.
          				imagefill( $img_48, 0, 0, $transp_index );
          				imagefill( $img_115, 0, 0, $transp_index );

          				// Set the background color for new image to transparent
          				imagecolortransparent( $img_48, $transp_index_48 );
          				imagecolortransparent( $img_115, $transp_index_115 );
          			}
                    imagecopyresampled($img_48, $src, 0, 0, $x, $y, $max_w48, $max_h48, $w, $h);
                    imagecopyresampled($img_115, $src, 0, 0, $x, $y, $max_w115, $max_h115, $w, $h);
                    if(!imagegif($img_48, $save_path48, 100) || !imagegif($img_115, $save_path115, 100))
                    {
                       $err_msg[] = JText::_( 'COM_JOBBOARD_USER_IMGCREATE_ERR');
                    }
            break;
            case IMAGETYPE_PNG :
                  $src = @imagecreatefrompng($profile_pic['fullpath']);
                  $transp_index = imagecolortransparent( $src );
                  $img_48 = @imagecreatetruecolor($max_w48, $max_h48);
                  $img_115 = @imagecreatetruecolor($max_w115, $max_h115);

                    // Turn off transparency blending (temporarily)
      			  imagealphablending( $img_48, false );
      			  imagealphablending( $img_115, false );

      			  // Create a new transparent color for image
      			  $color_48 = imagecolorallocatealpha( $img_48, 0, 0, 0, 127 );
      			  $color_115 = imagecolorallocatealpha( $img_115, 0, 0, 0, 127 );

      			  // Completely fill the background of the new image with allocated color.
      			  imagefill( $img_48, 0, 0, $color_48 );
      			  imagefill( $img_115, 0, 0, $color_115 );

      			  // Restore transparency blending
      			  imagesavealpha( $img_48, true );
      			  imagesavealpha( $img_115, true );

                  imagecopyresampled($img_48, $src, 0, 0, $x, $y, $max_w48, $max_h48, $w, $h);
                  imagecopyresampled($img_115, $src, 0, 0, $x, $y, $max_w115, $max_h115, $w, $h);
                  if(!imagepng($img_48, $save_path48, 9) || !imagepng($img_115, $save_path115, 9))
                  {
                     $err_msg[] = JText::_( 'COM_JOBBOARD_USER_IMGCREATE_ERR');
                  }
            break;
            default:
            ;break;
        }
        //free up memory
        imagedestroy($src);
        imagedestroy($img_48);
        imagedestroy($img_115);

        if(count($err_msg)> 0) {
            $msg = JText::_('COM_JOBBOARD_IMGUPLD_ERR');
            foreach($uploaded as $errmsg)
            {
                $msg .=  '<br />'.$errmsg;
            }
        } else
        {
            $msg = JText::_('COM_JOBBOARD_PICSAVED');
        }
        $this->setRedirect(JRoute::_('index.php?option=com_jobboard&view=user&task=prof&tab=2'), $msg, 'message');
	}

    function imgFileUploadProcess()
	{
	    // Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
        $post = JRequest::get('post');
        $file = JRequest::getVar('profile-image-file', null, 'files', 'array');

        $uploaded = false;
        $uploaded = $this->_uploadImage($file, $this->_uid, $post);

	    $app = & JFactory::getApplication();
        if($uploaded == true) {
          $msg = JText::_('COM_JOBBOARD_IMGUPLD_SUCCESS');
        } else {
            $msg = JText::_('COM_JOBBOARD_IMGUPLD_ERR');
            foreach($uploaded as $errmsg) {
                $msg .=  '<br />'.$errmsg;
            }
        }
        $app->redirect(JRoute::_('index.php?option=com_jobboard&view=user&task=prof&tab=2'), $msg);
    }

	private function _uploadImage($img, $uid=0, $post)
	{
	    $upload_msg = array();
		$user_model =& $this->getModel('User');

        $user_profile_id = $user_model->userProfileExists($uid);

        if($user_profile_id > 0)
        {
            $imgdata = $user_model->getProfileImageByUserId($uid);
            if($imgdata->profile_image_present > 0)
            {  //delete existing pic
              $imgdata->fullpath = $imgdata->profile_image_path.DS.$imgdata->profile_image_name;
              $imgdata->thumbpath = $imgdata->profile_image_path.DS.'thumbs'.DS.'thumb48_'.$imgdata->profile_image_name;
              $imgdata->thumb115 = $imgdata->profile_image_path.DS.'thumbs'.DS.'thumb115_'.$imgdata->profile_image_name;
  			  if(JFile::exists($imgdata->fullpath)) JFile::delete($imgdata->fullpath);
              if(JFile::exists($imgdata->thumbpath)) JFile::delete($imgdata->thumbpath);
              if(JFile::exists($imgdata->thumb115)) JFile::delete($imgdata->thumb115);
              $user_model->delProfileImage($uid, $user_profile_id);
            }
        }
        /** Check for file errors */

		if($img["size"] > 0){
			if($img["size"] > 1048576)
            { // Max filesize check
				$upload_msg[] = JText::_('COM_JOBBOARD_MAX_FILESIZE_ERR');
			}
            //sanitize filename
            $img_name = JFile::makeSafe($img["name"]);
            $img_name = str_replace(" ", "_", $img_name);

            // Check valid file format for Upload
            $img_ext = strtolower(strrchr($img_name, '.'));
			if(($img_ext!='.jpg') && ($img_ext!='.jpeg') && ($img_ext!='.gif') && ($img_ext!='.png'))
            {
				$upload_msg[] = JText::_('COM_JOBBOARD_FILEFORMAT_ERR');
			}

		} else if(strlen($img_name)<=0 || $img["size"] <= 0)
            {  // name length and zero-byte check
    			$upload_msg[] = JText::_( 'COM_JOBBOARD_MIN_FILESIZE_ERR');
    		}

		if(count($upload_msg) > 0)
        {
			$upload_msg['errors'] = true;
			return $upload_msg;
		}

        /** No file errors. Proceed with uploading image */
		$base_folder = JPATH_BASE.DS.'images'.DS.'com_jobboard';

        if(!JFolder::exists($base_folder))
        {
            $base_folder_created = JFolder::create($base_folder);
            if($base_folder_created == false)
            {
              $upload_msg[] = JText::_('COM_JOBBOARD_USERFOLDER_CREATE_ERR');
            }
        }
        $sec_file = $base_folder.DS.'index.html';
        if(!JFile::exists($sec_file))
        {
            $_html = '<!DOCTYPE html><title></title>';
            JFile::write($sec_file, $_html);
        }

		$working_folder = $base_folder.DS.'users';

        if(!JFolder::exists($working_folder))
        {
            $working_folder_created = JFolder::create($working_folder);
            if($working_folder_created == false)
            {
              $upload_msg[] = JText::_('COM_JOBBOARD_USERFOLDER_CREATE_ERR');
            }
        }
        $sec_file = $working_folder.DS.'index.html';
        if(!JFile::exists($sec_file))
        {
            $_html = '<!DOCTYPE html><title></title>';
            JFile::write($sec_file, $_html);
        }

        $user_folder = $working_folder.DS.$uid;

        if(!JFolder::exists($user_folder))
        {
            $user_folder_created = JFolder::create($user_folder);
            if($user_folder_created == false)
            {
              $upload_msg[] = JText::_('COM_JOBBOARD_USERFOLDER_CREATE_ERR');
            }
        }

        $sec_file = $user_folder.DS.'index.html';
        if(!JFile::exists($sec_file))
        {
            $_html = '<!DOCTYPE html><title></title>';
            JFile::write($sec_file, $_html);
        }

        $user_prof_folder = $user_folder.DS.'profile';

        if(!JFolder::exists($user_prof_folder))
        {
            $user_prof_folder_created = JFolder::create($user_prof_folder);
            if($user_prof_folder_created == false)
            {
              $upload_msg[] = JText::_('COM_JOBBOARD_USERFOLDER_CREATE_ERR');
            }
        }

        $sec_file = $user_prof_folder.DS.'index.html';
        if(!JFile::exists($sec_file))
        {
            $_html = '<!DOCTYPE html><title></title>';
            JFile::write($sec_file, $_html);
        }

        $user_thumb_folder = $user_prof_folder.DS.'thumbs';

        if(!JFolder::exists($user_thumb_folder))
        {
            $user_thumb_folder_created = JFolder::create($user_thumb_folder);
            if($user_thumb_folder_created == false)
            {
              $upload_msg[] = JText::_('COM_JOBBOARD_USERFOLDER_CREATE_ERR');
            }
        }

        $sec_file = $user_thumb_folder.DS.'index.html';
        if(!JFile::exists($sec_file))
        {
            $_html = '<!DOCTYPE html><title></title>';
            JFile::write($sec_file, $_html);
        }

		if(count($upload_msg) > 0)
        {
			$upload_msg['errors'] = true;
			return $upload_msg;
		}

        /** No user folder errors. Proceed with uploading image */

        $newimage_filepath = $user_prof_folder.DS.$img_name;
        $newimage_thumbpath = $user_prof_folder.DS.'thumbs'.DS.'thumb48_'.$img_name;
        $newimage_thumbpath115 = $user_prof_folder.DS.'thumbs'.DS.'thumb115_'.$img_name;
        list($width, $height) = getimagesize($img["tmp_name"]);
        //set resize parameters

        $newwidth = ($width > $post['maxwidth'])? $post['maxwidth'] : $width;
        $newheight = ($height/$width) * $newwidth;

        $thumb_ratio = 1;
        $thumb48_width = 48; $thumb48_height = $thumb48_width;
        $thumb115_width = 115; $thumb115_height = $thumb115_width;
        if ($width/$height > $thumb_ratio) {
           $thumb48_width = 48*$thumb_ratio;
           $thumb115_width = 115*$thumb_ratio;
        } else {
           $thumb48_height = 48/$thumb_ratio;
           $thumb115_height = 115/$thumb_ratio;
        }

        $img["type"] = ($img["type"] == 'image/jpg' || $img["type"] == 'image/jpeg')? 'image/jpeg' : $img["type"];
        switch($img["type"]){
              case 'image/jpeg' :
                      // Load
                      $src = @imagecreatefromjpeg($img["tmp_name"]);
                      $img = @imagecreatetruecolor($newwidth, $newheight);
                      $thumb48 = @imagecreatetruecolor($thumb48_width, $thumb48_height);
                      $thumb115 = @imagecreatetruecolor($thumb115_width, $thumb115_height);

                      // Resize cropped
                      imagecopyresampled($img, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
                      imagecopyresampled($thumb48, $src, 0, 0, 0, 0, $thumb48_width, $thumb48_height, $width, $height);
                      imagecopyresampled($thumb115, $src, 0, 0, 0, 0, $thumb115_width, $thumb115_height, $width, $height);
                      // Output
                      if(!imagejpeg($img, $newimage_filepath, 100) || !imagejpeg($thumb48, $newimage_thumbpath, 100) || !imagejpeg($thumb115, $newimage_thumbpath115, 100) )
                      {
                         $upload_msg[] = JText::_( 'COM_JOBBOARD_USER_IMGCREATE_ERR');
                      }
              break;
              case 'image/gif' :
                      $src = @imagecreatefromgif($img["tmp_name"]);
                      $transp_index = imagecolortransparent( $src );
                      $img = @imagecreatetruecolor($newwidth, $newheight);
                      $thumb48 = @imagecreatetruecolor($thumb48_width,$thumb48_height);
                      $thumb115 = @imagecreatetruecolor($thumb115_width, $thumb115_height);
                      if ( $transp_index >= 0 )
            			{
            				// Get the original image's transparent color's RGB values
            				$transp_color = imagecolorsforindex( $src, $transp_index );

            				// Allocate the same color in the new image resource
            				$trans_index = imagecolorallocate( $img, $transp_color['red'], $transp_color['green'], $transp_color['blue'] );
            				$trans_index_48 = imagecolorallocate( $thumb48, $transp_color['red'], $transp_color['green'], $transp_color['blue'] );
            				$trans_index_115 = imagecolorallocate( $thumb115, $transp_color['red'], $transp_color['green'], $transp_color['blue'] );

            				// Completely fill the background of the new image with allocated color.
            				imagefill( $img, 0, 0, $transp_index );
            				imagefill( $thumb48, 0, 0, $transp_index );
            				imagefill( $thumb115, 0, 0, $transp_index );

            				// Set the background color for new image to transparent
            				imagecolortransparent( $img, $transp_index );
            				imagecolortransparent( $thumb48, $transp_index_48 );
            				imagecolortransparent( $thumb115, $transp_index_115 );
            			}
                      imagecopyresampled($img, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
                      imagecopyresampled($thumb48, $src, 0, 0, 0, 0, $thumb48_width, $thumb48_height, $width, $height);
                      imagecopyresampled($thumb115, $src, 0, 0, 0, 0, $thumb115_width, $thumb115_height, $width, $height);
                      if(!imagegif($img, $newimage_filepath, 100) || !imagegif($thumb48, $newimage_thumbpath, 100) || !imagegif($thumb115, $newimage_thumbpath115, 100))
                      {
                         $upload_msg[] = JText::_( 'COM_JOBBOARD_USER_IMGCREATE_ERR');
                      }
              break;
              case 'image/png' :
                      $src = @imagecreatefrompng($img["tmp_name"]);
                      $transp_index = imagecolortransparent( $src );
                      $img = @imagecreatetruecolor($newwidth, $newheight);
                      $thumb48 = @imagecreatetruecolor($thumb48_width,$thumb48_height);
                      $thumb115 = @imagecreatetruecolor($thumb115_width, $thumb115_height);

                      // Turn off transparency blending (temporarily)
        			  imagealphablending( $img, false );
        			  imagealphablending( $thumb48, false );
        			  imagealphablending( $thumb115, false );

        			  // Create a new transparent color for image
        			  $color = imagecolorallocatealpha( $img, 0, 0, 0, 127 );
        			  $color_48 = imagecolorallocatealpha( $thumb48, 0, 0, 0, 127 );
        			  $color_115 = imagecolorallocatealpha( $thumb115, 0, 0, 0, 127 );

        			  // Completely fill the background of the new image with allocated color.
        			  imagefill( $img, 0, 0, $color );
        			  imagefill( $thumb48, 0, 0, $color_48 );
        			  imagefill( $thumb115, 0, 0, $color_115 );

        			  // Restore transparency blending
        			  imagesavealpha( $img, true );
        			  imagesavealpha( $thumb48, true );
        			  imagesavealpha( $thumb115, true );

                      imagecopyresampled($img, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
                      imagecopyresampled($thumb48, $src, 0, 0, 0, 0, $thumb48_width, $thumb48_height, $width, $height);
                      imagecopyresampled($thumb115, $src, 0, 0, 0, 0, $thumb115_width, $thumb115_height, $width, $height);
                      if(!imagepng($img, $newimage_filepath, 9) || !imagepng($thumb48, $newimage_thumbpath, 9) || !imagepng($thumb115, $newimage_thumbpath115, 9))
                      {
                         $upload_msg[] = JText::_( 'COM_JOBBOARD_USER_IMGCREATE_ERR');
                      }
              break;
              default:
              ;break;
        }

        //free up memory
        imagedestroy($src);
        imagedestroy($img);
        imagedestroy($thumb48);
        imagedestroy($thumb115);

        if($user_profile_id <> 0)
        {
           $success = $user_model->saveProfileImage($user_profile_id, $uid, $user_prof_folder, $img_name);
        } else
           $success = $user_model->saveProfileImage(0, $uid, $user_prof_folder, $img_name);

		if(count($upload_msg) <= 0 && $success == true)
        {
			$upload_msg['errors'] = false;
			return true;
		} else {
			$upload_msg['errors'] = true;
			return $upload_msg;
		}
	}

   function usrImgDel($redirect=true, $del_references=true) //$del_references --> delete original image plus database record image column values
   {
        JRequest::checkToken('get') or jexit( JText::_('Invalid Token') );

        $errors = array();
		$user_model =& $this->getModel('User');

        $user_profile_id = $user_model->userProfileExists($this->_uid);

        if($user_profile_id > 0)
        {
            $imgdata = $user_model->getProfileImageByUserId($this->_uid);
            if($imgdata->profile_image_present > 0)
            { //delete existing pic
                $imgdata->fullpath = $imgdata->profile_image_path.DS.$imgdata->profile_image_name;
                $imgdata->thumbpath = $imgdata->profile_image_path.DS.'thumbs'.DS.'thumb48_'.$imgdata->profile_image_name;
              $imgdata->thumb115 = $imgdata->profile_image_path.DS.'thumbs'.DS.'thumb115_'.$imgdata->profile_image_name;
  			  if($del_references == true) {
    			if(JFile::exists($imgdata->fullpath)) {
        			if(!JFile::delete($imgdata->fullpath)) {
                       $errors[] = JText::_('COM_JOBBOARD_IMGFDEL_ERR');
        			}
                }
              }
              if(JFile::exists($imgdata->thumbpath)) {
    			if(!JFile::delete($imgdata->thumbpath)) {
                   $errors[] = JText::_('COM_JOBBOARD_IMGFDEL_ERR');
    			}
              }
              if(JFile::exists($imgdata->thumb115)) {
    			if(!JFile::delete($imgdata->thumb115)) {
                   $errors[] = JText::_('COM_JOBBOARD_IMGFDEL_ERR');
    			}
              }
            }
            if($del_references == true) {
                $success = $user_model->delProfileImage($this->_uid, $user_profile_id);
            } else $success = true;
        }

        $app = & JFactory::getApplication();
        if($user_profile_id <= 0 || ($success == true && count($errors) <= 0))
        {
          $msg = JText::_('COM_JOBBOARD_IMGDEL_SUCCESS');
        if($redirect == true)
            $app->redirect(JRoute::_('index.php?option=com_jobboard&view=user&task=prof&tab=2'), $msg);
        else return true;
        } else
        {
          if($redirect == true) {
              $msg = JText::_('COM_JOBBOARD_IMGDEL_ERR');
              foreach($errors as $errmsg)
              {
                  $msg .=  '<br />'.$errmsg;
              }
              $app->redirect(JRoute::_('index.php?option=com_jobboard&view=user&task=prof&tab=2'), $msg);
          }
          else return false;
        }
   }
}

$controller = new JobboardControllerImage();
$controller->execute($task);
$controller->redirect();

?>