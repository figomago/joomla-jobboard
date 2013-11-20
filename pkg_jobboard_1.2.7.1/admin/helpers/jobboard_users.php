<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

class JobBoardUsersHelper
{

    static function getGroups()  {
          $db = & JFactory::getDBO();
          $sql = 'SELECT * FROM '.$db->nameQuote('#__jobboard_usr_groups').'
           ORDER BY '.$db->nameQuote('id').';';
          $db->setQuery($sql);
          return $db->loadObjectList();
    }

}

?>