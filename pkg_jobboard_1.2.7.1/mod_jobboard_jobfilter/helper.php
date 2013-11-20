<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

  /* Job Board Advanced job filter Module Helper
  *
  * @static
  */

  class modJobboardJobfilterHelper
  {

  /**
  * Gets an array of items
  *
  * @return mixed Array of items, empty array on failure
  */
    function & getItems(&$params, &$selcat=1, &$job_types=array(), &$all_job_types, &$career_levels=array(), &$edu_levels=array(), &$date_range=0, &$date_ranges, $use_location=1)
    {
      $types = array('MOD_JOBBOARD_FILTER_CATEGORIES', 'MOD_JOBBOARD_FILTER_CAREER_LEVELS', 'MOD_JOBBOARD_FILTER_EDUCATION', 'MOD_JOBBOARD_FILTER_JOB_TYPES', 'MOD_JOBBOARD_FILTER_COUNTRIES', 'MOD_JOBBOARD_FILTER_DATE_RANGE');
      $data = array();

      if($use_location <> 1) {
        array_pop($types);
      }

      $db = & JFactory::getDBO();
      $limit = $params->get('limit', 5);

      foreach($types as $type){

        $query = self::_buildQuery($type, $limit, $selcat, $job_types, $all_job_types, $career_levels, $edu_levels, $date_range, $date_ranges);

        $db->setQuery($query);
        $data[$type] = $db->loadAssocList();
      }
      return $data;
    }

  /**
  * Gets an SQL query string
  *
  * @return string SQL query
  */
    private function _buildQuery($type, $limit, $selcat, $job_types, $all_job_types, $career_levels, $edu_levels, $date_range, $date_ranges)
    {

      $ext_where = '';
      $selcat = $selcat == 0? 1 : $selcat;
      $db = & JFactory :: getDBO();

      if($selcat > 1) $ext_where = ' AND j.'.$db->nameQuote('category').' = '.$selcat.' ';


      if(!empty($job_types)) :
        $select_filters = self::_getFilters($job_types, 'job_type', $all_job_types);
        if($select_filters <> false) {
            $ext_where .= $select_filters;
        }
      endif;

      if(!empty($career_levels)) :
      $select_filters = self::_getFilters($career_levels, 'career_level');
      if($select_filters <> false) {
          $ext_where .= $select_filters;
      }
      endif;

      if(!empty($edu_levels)) :
        $select_filters = self::_getFilters($edu_levels, 'education');
        if($select_filters <> false) {
            $ext_where .= $select_filters;
        }
      endif;
      unset($select_filters);

      $app = & JFactory::getApplication();

	  $country_id = $app->getUserStateFromRequest('com_jobboard.list.country_id','country_id', 0, 'int');
      if($country_id > 0) {
        $ext_where .= ' AND j.'.$db->nameQuote('country').' = '.$country_id.' ';
      }

      $search = JString::trim($app->getUserStateFromRequest("com_jobboard.jobsearch", 'jobsearch', '', 'string') );
      $search = (strpos($search, '(') === 0)? '' : JString::strtolower($search);
      $keysrch = JString::trim($app->getUserStateFromRequest("com_jobboard.keysrch", 'keysrch', '', 'string') );
      $keysrch = (strpos($keysrch, '(') === 0)? '' : JString::strtolower($keysrch);

		if($search != '')
		{
			$fields = array('j.'.$db->nameQuote('job_title'));
			$s_where = array();
			$search = $db->getEscaped($search,true);

			foreach($fields as $field)
			{
				$s_where[] = $field." LIKE '%{$search}%'";
			}
			$ext_where .= ' AND ('.implode(' OR ',$s_where).')';
		}

		if($keysrch != '')
		{
			$fields = array('j.'.$db->nameQuote('job_title'), 'j.'.$db->nameQuote('job_tags'),'j.'.$db->nameQuote('description'));
			$ks_where = array();
			$keysrch = $db->getEscaped($keysrch,true);
			$keys_array = explode(',', $keysrch);

			foreach($keys_array as $keywd) {
				$keywd = trim($keywd);
				foreach($fields as $field)
				{
					$ks_where[] = $field." LIKE '%{$keywd}%'";
				}
			}
			$ext_where .= ' AND ('.implode(' OR ',$ks_where).')';
		}

      if($date_range > 0) {
          $dateNow = new JDate($app->get('requestTime'));
          $startdate = $dateNow->toFormat('%Y-%m-%d');

          $ext_where .= ' AND DATE_FORMAT(j.'.$db->nameQuote('post_date').',"%Y-%m-%d") >= DATE_SUB("'.$startdate.'",INTERVAL '.$date_range.' DAY) ';
      }

      $ext_where .= ' AND j.'.$db->nameQuote('published').' = 1 AND (DATE_FORMAT(j.'.$db->nameQuote('expiry_date').', "%Y-%m-%d") >= CURDATE() OR DATE_FORMAT(j.'.$db->nameQuote('expiry_date').', "%Y-%m-%d") = 0000-00-00) ';
      $sql = '';
      switch($type){
        case 'MOD_JOBBOARD_FILTER_CATEGORIES':
           if($selcat > 1 ) {
               $sql = '(SELECT 1, COUNT('.$db->nameQuote('id').') AS total, "MOD_JOBBOARD_FILTER_ALL" AS name
                      FROM '.$db->nameQuote('#__jobboard_jobs').' as j WHERE TRUE '.$ext_where.' LIMIT '.$limit.')
                      UNION ALL
                      (SELECT c.'.$db->nameQuote('id').', COUNT(category) AS total, c.'.$db->nameQuote('type').' AS name
                      FROM
                        '.$db->nameQuote('#__jobboard_categories').' AS c, '.$db->nameQuote('#__jobboard_jobs').' AS j
                      WHERE c.'.$db->nameQuote('id').' = j.'.$db->nameQuote('category').' AND c.'.$db->nameQuote('enabled').' = 1 '.$ext_where.'
                      GROUP BY category
                      HAVING total  >= 0
                      ORDER BY total DESC LIMIT '.$limit.')';
           } else {
              $sql =  '(SELECT 1, COUNT(id) AS total, "MOD_JOBBOARD_FILTER_ALL" AS `name`
                        FROM #__jobboard_jobs AS j WHERE TRUE '.$ext_where.' LIMIT '.$limit.')
                       UNION ALL
                       (SELECT c.`id`, COUNT(category) AS total, c.`type` AS `name`
                       FROM #__jobboard_categories AS c JOIN #__jobboard_jobs AS j
                       ON( c.id = j.`category`) WHERE c.`enabled` = 1 '.$ext_where.'
                       GROUP BY category
                       ORDER BY total DESC LIMIT '.$limit.')
                       UNION
                      (SELECT c.`id`, 0 AS total, c.`type` AS `name`
                       FROM #__jobboard_categories AS c
                      WHERE (NOT EXISTS
                        (SELECT `id` FROM   #__jobboard_jobs AS j WHERE  j.`category` = c.`id`) )
                        AND c.`enabled` = 1 AND c.`id` > 1  LIMIT 100)';
            }
        break;
        case 'MOD_JOBBOARD_FILTER_CAREER_LEVELS':
             $sql = 'SELECT  cl.'.$db->nameQuote('id').', COUNT('.$db->nameQuote('career_level').') AS total, cl.'.$db->nameQuote('description').' AS name
                    FROM
                      '.$db->nameQuote('#__jobboard_career_levels').' AS cl, '.$db->nameQuote('#__jobboard_jobs').' AS j
                    WHERE cl.'.$db->nameQuote('id').' = j.'.$db->nameQuote('career_level').' '.$ext_where.'
                    GROUP BY career_level
                    HAVING total  >= 0
                    ORDER BY '.$db->nameQuote('career_level').'  ASC ';
       break;
       case 'MOD_JOBBOARD_FILTER_EDUCATION':
             $sql = 'SELECT e.'.$db->nameQuote('id').', COUNT('.$db->nameQuote('education').') AS total, e.'.$db->nameQuote('level').' AS name
                FROM '.$db->nameQuote('#__jobboard_education').' AS e, '.$db->nameQuote('#__jobboard_jobs').' AS j
                WHERE e.'.$db->nameQuote('id').' = j.'.$db->nameQuote('education').' '.$ext_where.'
                GROUP BY '.$db->nameQuote('education').'
                HAVING total  >= 0
                ORDER BY '.$db->nameQuote('education').'  ASC ';
       break;
       case 'MOD_JOBBOARD_FILTER_JOB_TYPES':
            $sql = 'SELECT COUNT('.$db->nameQuote('id').') AS total, j.'.$db->nameQuote('job_type').'  AS name
                    FROM '.$db->nameQuote('#__jobboard_jobs').' AS j
                WHERE TRUE '.$ext_where.'
                GROUP BY j.'.$db->nameQuote('job_type');
       break;
       case 'MOD_JOBBOARD_FILTER_COUNTRIES':
            $sql = 'SELECT c.`country_id` AS id,  COUNT(country) AS total,  c.`country_name` AS `name`
                      FROM
                        '.$db->nameQuote('#__jobboard_countries').' AS c, '.$db->nameQuote('#__jobboard_jobs').' AS j
                      WHERE c.'.$db->nameQuote('country_id').' = j.'.$db->nameQuote('country'). ' '.$ext_where.'
                      GROUP BY '.$db->nameQuote('country'). '
                    ORDER BY total DESC';
       break;
       case 'MOD_JOBBOARD_FILTER_DATE_RANGE':
            $dateNow = new JDate($app->get('requestTime'));
            $startdate = $dateNow->toFormat('%Y-%m-%d');
            if($date_range <= 0) {
              $num_ranges = count($date_ranges);
              $sql = '';
              for($r = 0; $r<$num_ranges; $r++) {
                   $sql .= '('.self::_getRangeRow(&$date_ranges[$r], $ext_where, $startdate).')';
                   if(($num_ranges - $r) != 1)
                      $sql .= ' UNION ';
              }
            } else {
               $range_index = self::findRangeIndex($date_range, &$date_ranges);
               if($range_index > 0)
                  $sql = self::_getRangeRow(&$date_ranges[$range_index], $ext_where, $startdate, true);
            }
       break;
       default :
       ;break;
      }
      return $sql;
    }

    private function _getFilters($filter_arr, $name, $job_type_values=array()) {
       $db = & JFactory :: getDBO();
       if(!empty($filter_arr)) {
			$s_where = array();
            if($name == 'job_type') :
    			foreach($filter_arr as $value)
    			{
    				$s_where[] = "j.".$db->nameQuote($name)." = '{$job_type_values[$value]}'";
    			}
            else :
    			foreach($filter_arr as $value)
    			{
    				$s_where[] = "j.".$db->nameQuote($name)." = {$value}";
    			}
            endif;
			return ' AND ('.implode(' OR ',$s_where).')';

       } else return false;
    }

    static function _getLocConfig() {
      $db = & JFactory :: getDBO();
      $sql ='SELECT '.$db->nameQuote('use_location').' FROM '.$db->nameQuote('#__jobboard_config').' WHERE '.$db->nameQuote('id').' = 1';
      $db->setQuery($sql);
      return $db->loadResult();
    }

    static function _getRangeRow(&$row, $ext_where, $today, $single=false) {
      $db = & JFactory :: getDBO();
      $where = ($single == true)? 'WHERE TRUE' : 'WHERE DATE_FORMAT('.$db->nameQuote('j.post_date').', "%Y-%m-%d") >= DATE_SUB("'.$today.'", INTERVAL '.$row['value'].' DAY) ';
      // if($row['value'] == 0) $where = 'WHERE DATE_FORMAT('.$db->nameQuote('j.post_date').', "%Y-%m-%d") <= "'.$today.'" ';

      return 'SELECT '.$row['value'].' AS '.$db->nameQuote('range').', "'.$row['name'].'" AS '.$db->nameQuote('name').', COUNT(j.'.$db->nameQuote('id').') AS '.$db->nameQuote('total').'
            FROM '.$db->nameQuote('#__jobboard_jobs').' AS j '.$where.$ext_where.'  HAVING '.$db->nameQuote('total').' > 0 ';

    }

    static function findRangeIndex($range, $date_ranges){
        $num_vals = count($date_ranges);
        for ($i = 0; $i<$num_vals; $i++){
            $match = array_search($range, $date_ranges[$i]);
            if($match == 'value') return $i;
        }
        return false;
    }

  }


?>