<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

class JobBoardEncodeHelper
{

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

    /**
	 * Generate Random string
	 *
	 **/
	function randStr($s){
		return sprintf(
  				 '%03x%02x', $s, mt_rand(0, 65535));  
	}

}

?>