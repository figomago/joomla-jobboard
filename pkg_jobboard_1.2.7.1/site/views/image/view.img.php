<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class JobboardViewImage extends JView
{
	function display($tpl = null)
	{
          $document =& JFactory::getDocument();
          $image_info = GetImageSize($this->filename);

  		  $memoryLimitChanged = 0;
  		  $memory = (int)ini_get( 'memory_limit' );
  	      $memory = ($memory == 0)? 8 : $memory;
          if ($memory < 50) {
				ini_set('memory_limit', '64M');
				$memoryLimitChanged = 1;
		  }
          // Content type
          $document->setMimeEncoding((string)$image_info['mime']);

          $this->assign('imgtype', $image_info[2]);

          $ratio_cropped = $this->w/$this->h;
          if ($this->maxwidth/$this->maxheight > $ratio_cropped) {
             $this->maxwidth = $this->maxheight*$ratio_cropped;
          } else {
             $this->maxheight = $this->maxwidth/$ratio_cropped;
          }
       /*   echo '<pre>'.print_r($image_info, true).'</pre>';
          echo '<br />From file: '.$this->filename;
          echo '<br />Horiz Offest(x): '.$this->x;
          echo '<br />Vert Offest(y): '.$this->y;
          echo '<br />Maxwidth: '.$this->maxwidth;
          echo '<br />Maxheight: '.$this->maxheight;          ,
          die;*/
          switch($this->imgtype) {
              case IMAGETYPE_JPEG :
                      // Load
                      $src = @ImageCreateFromJPEG($this->filename);
                      $img = @ImageCreateTruecolor($this->maxwidth, $this->maxheight);

                      // Resize cropped
                      imagecopyresampled($img, $src, 0, 0, $this->x, $this->y, $this->maxwidth, $this->maxheight, $this->w, $this->h);
                      // Output

                      @ImageJPEG($img, null, 100);
              break;
              case IMAGETYPE_GIF :
                      $src = @ImageCreateFromGIF($this->filename);
                      $transp_index = imagecolortransparent( $src );
                      $img = @ImageCreateTruecolor($this->maxwidth, $this->maxheight);
                      if ( $transp_index >= 0 )
            			{
            				// Get the original image's transparent color's RGB values
            				$transp_color = imagecolorsforindex( $src, $transp_index );

            				// Allocate the same color in the new image resource
            				$trans_index = imagecolorallocate( $img, $transp_color['red'], $transp_color['green'], $transp_color['blue'] );

            				// Completely fill the background of the new image with allocated color.
            				imagefill( $img, 0, 0, $transp_index );

            				// Set the background color for new image to transparent
            				imagecolortransparent( $img, $transp_index );
            			}
                      imagecopyresampled($img, $src, 0, 0, $this->x, $this->y, $this->maxwidth, $this->maxheight, $this->w, $this->h);

                      @ImageGIF($img, null, 100);
              break;
              case IMAGETYPE_PNG :
                      $src = @ImageCreateFromPNG($this->filename);
                      $transp_index = imagecolortransparent( $src );
                      $img = @ImageCreateTruecolor($this->maxwidth, $this->maxheight);

                      // Turn off transparency blending (temporarily)
        			  imagealphablending( $img, false );

        			  // Create a new transparent color for image
        			  $color = imagecolorallocatealpha( $img, 0, 0, 0, 127 );

        			  // Completely fill the background of the new image with allocated color.
        			  imagefill( $img, 0, 0, $color );

        			  // Restore transparency blending
        			  imagesavealpha( $img, true );

                      imagecopyresampled($img, $src, 0, 0, $this->x, $this->y, $this->maxwidth, $this->maxheight, $this->w, $this->h);

                      @ImagePNG($img, null, 9);
              break;
              default:
              ;break;
            }
            imagedestroy($src);
            imagedestroy($img);
            if ($memoryLimitChanged == 1) {
					$memoryString = $memory . 'M';
					ini_set('memory_limit', $memoryString);
			}
	}

}

?>