<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

class JobBoardFormatHelper
{
     function formatQuerySegments($string) {
        $str_result = '';
        $str_segs = explode(' ', $string);
        $str_segs_first = explode(',', $str_segs[0]);
        $segs_count = count($str_segs);
        $str_result .= $str_segs[0];

        if($segs_count > 1) :
          unset($str_segs[0]);
          foreach($str_segs as $seg) {
            $sub_segs = explode(',', $seg);
            if(count($sub_segs) < 2) {
             $str_result .= '+'.$seg;
            } else {
               foreach($sub_segs as $s_seg) {
                 $str_result .= '+'.$s_seg;
               }
            }
          }
        endif;
        return $str_result;
    }


    function getJsonFromUrl($url) {
       $context = stream_context_create(array('https' => array( 'method'=>"GET", 'header'=>'Connection: close')));
       return json_decode(file_get_contents($url, false, $context), true);
    }
}

?>