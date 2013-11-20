<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');
jimport('joomla.utilities.date');

class JobboardModelRss extends JModel
{

	function __construct()
	{
		parent::__construct();
	}

    function getCatname($catid) {
        // get category name
        $db =& $this->getDBO();
        $query = 'SELECT '.$db->nameQuote('type').'
            FROM '.$db->nameQuote('#__jobboard_categories').'
            WHERE '.$db->nameQuote('id').' = '.$catid;
        $db->setQuery($query);
        return $db->loadResult();
    }

    function getLocConf(){
        $db =& $this->getDBO();
        // get "show location" settings:
        $query = 'SELECT '.$db->nameQuote('use_location').'
        FROM '.$db->nameQuote('#__jobboard_config').'
        WHERE '.$db->nameQuote('id').' = 1';
        $db->setQuery($query);
        return $db->loadResult();
    }
}

?>