<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

class JobBoardAdmapplHelper
{

    static function getApplIds($aid)  {
         $db = & JFactory::getDBO();
         $sql = 'SELECT '.$db->nameQuote('user_id').' AS sid
                        , '.$db->nameQuote('job_id').' AS jid
                        , '.$db->nameQuote('cvprof_id').' AS pid
                        , '.$db->nameQuote('qid').'
                          FROM '.$db->nameQuote('#__jobboard_usr_applications').'
                          WHERE '.$db->nameQuote('id').' = '.$aid;
         $db->setQuery($sql);    
         return $db->loadAssoc();
    }

}

?>