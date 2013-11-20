<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

  /**
  * Job Board Popular Module Helper
  *
  * @static
  */
  class modJobboardPopularHelper
  {
  /**
  * Gets an array of items
  *
  * @param JParameter Module parameters
  * @return mixed Array of items, false on failure
  */
    function & getItems(&$params)
    {
      $db = & JFactory :: getDBO();
      $limit = $params->get('limit', 5);
      $query = modJobboardPopularHelper::_buildQuery($limit);
      $db->setQuery($query);
      $instance = $db->loadObjectList();
      return $instance;
    }


  /**
  * Gets an SQL query string
  *
  * @return string SQL query
  */
    function _buildQuery($limit)
    {
      $db = & JFactory :: getDBO();
      return 'SELECT id, job_title, city, hits FROM ' . $db->nameQuote('#__jobboard_jobs') . ' WHERE ' . $db->nameQuote('published') . ' = 1
        ORDER BY hits DESC LIMIT '.$limit;
    }


  }


?>