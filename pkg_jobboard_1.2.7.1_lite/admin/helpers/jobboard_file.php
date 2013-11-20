<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

class JobBoardFileHelper
{
    function getValidFtypes() {
      return array("image/png", "image/gif", "image/jpeg", "text/plain", "application/pdf",
            "application/msword", "application/vnd.ms-word", "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
            "application/msexcel", "application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    }

    function getMinValidFtypes() {
      return array("text/plain", "application/pdf", "application/msword",
      "application/vnd.ms-word", "application/vnd.openxmlformats-officedocument.wordprocessingml.document");
    }
}

?>