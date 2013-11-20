<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

  /**
  * Job Board Cattotals Jobs Module Helper
  *
  * @static
  */
  class modJobboardCattotalsHelper
  {
  /**
  * Gets an array of items
  *
  * @return mixed Array of items, false on failure
  */
    function & getItems(&$params)
    {
      $db = & JFactory :: getDBO();
      $limit = $params->get('limit', 10);
      $query = modJobboardCattotalsHelper::_buildQuery($limit);
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
      return 'SELECT a.type, a.id, count( category ) as total FROM ' . $db->nameQuote('#__jobboard_categories') . ' as a, '.$db->nameQuote('#__jobboard_jobs') . ' as b  WHERE a.id = b.category AND a.enabled = 1
        GROUP BY category ORDER BY category DESC LIMIT '.$limit;
    }

	
  }


?>