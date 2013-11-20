<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');
jimport('joomla.utilities.date');

class JobboardModelExtlist extends JModel
{
	var $_total = null;
	var $_pagination = null;
	var $_search = null;
	var $_keysrch = null;
	var $_loc_cfg = null;
	var $_locsrch = null;
	var $_limit = null;
	var $_limitstart = null;
	var $data = null;
	var $filter_job_type  = null;
	var $filter_career_level  = null;
	var $filter_education  = null;

	function __construct()
	{
		parent::__construct();

		$app= JFactory::getApplication();
        
		$limit = $app->getUserState('com_jobboard.extlist.limit', $app->getCfg('list_limit'), 'int');
		$limitstart = $app->getUserState('com_jobboard.extlist.limitstart', 0, 'int');

		 $this->setState('limit', $limit);
		 $this->setState('limitstart', $limitstart);

    	$filter_job_type = $app->getUserStateFromRequest("com_jobboard.extlist.filter_job_type", 'filter_job_type', array(), 'array');
    	$filter_careerlvl = $app->getUserStateFromRequest("com_jobboard.extlist.filter_careerlvl", 'filter_careerlvl', array(), 'array');
    	$filter_edulevel = $app->getUserStateFromRequest("com_jobboard.extlist.filter_edulevel", 'filter_edulevel', array(), 'array');
		$this->setState('filter_job_type', $filter_job_type);
		$this->setState('filter_career_level', $filter_careerlvl);
		$this->setState('filter_education', $filter_edulevel);
        unset($filter_job_type);
        unset($filter_careerlvl);
        unset($filter_edulevel);
	}

    private function _setLimits($limits){
      $this->_limit = $limits['limit'];
      $this->_limitstart = $limits['limitstart'];
    }

	function _buildQuery()
	{
		$app= JFactory::getApplication();

		$category_id = $app->getUserStateFromRequest('com_jobboard.extlist.selcat', 'selcat', 1, 'int');
        $category_id = $category_id == 0? 1 : $category_id;
		$country_id = $app->getUserStateFromRequest('com_jobboard.extlist.country_id','country_id', 0, 'int');

		$daterange = $app->getUserStateFromRequest("com_jobboard.extlist.daterange", 'daterange', 0, 'int');
		$dateNow = new JDate($app->get('requestTime'));
		$startdate = $dateNow->toFormat('%Y-%m-%d');

		if ($category_id == 1 ) {
			$where = '';
			if($daterange <> 0) {
				$where = ' WHERE DATE_FORMAT(j.`post_date`,"%Y-%m-%d") >= DATE_SUB("'.$startdate.'",INTERVAL '.$daterange.' DAY) ';
			} else $where = ' WHERE TRUE ';
		} else {
			$where =' WHERE c.`id` = '.$category_id ;
			if($daterange <> 0) {
				$where .= ' AND DATE_FORMAT(j.`post_date`,"%Y-%m-%d") >= DATE_SUB("'.$startdate.'",INTERVAL '.$daterange.' DAY) ';
			}
		}

		$where .= ' AND (DATE_FORMAT(j.`expiry_date`,"%Y-%m-%d") >= CURDATE() OR DATE_FORMAT(j.`expiry_date`,"%Y-%m-%d") = 0000-00-00) ';

        if($country_id > 0) {
          $where .= ' AND j.`country` = '.$country_id.' ';
        }

		$search = $this->getSearch();
		$keysrch = $this->getKeySearch();
        $loc_cfg = $app->getUserState('com_jobboard.extlist.use_location', 0);
		$locsrch = $loc_cfg == 1? $this->getLocSearch() : '';

		if($search != '')  // filter by job title
		{
			$fields = array('j.`job_title`');
			$s_where = array();
			$search = $this->_db->getEscaped($search,true);

			foreach($fields as $field)
			{
				$s_where[] = $field." LIKE '%{$search}%'";
			}
			$where .= ' AND ('.implode(' OR ',$s_where).')';
		}

		if($keysrch != '') // filter by job skills/keywords
		{
			$fields = array('j.`job_title`','j.`job_tags`','j.`description`');
			$ks_where = array();
			$keysrch = $this->_db->getEscaped($keysrch,true);
			$keys_array = explode(',', $keysrch);

			foreach($keys_array as $keywd) {
				$keywd = trim($keywd);
				foreach($fields as $field)
				{
					$ks_where[] = $field." LIKE '%{$keywd}%'";
				}
			}
			$where .= ' AND ('.implode(' OR ',$ks_where).')';
		}

        $select_filters = $this->getFilters($this->getState('filter_job_type'), 'job_type');
        if($select_filters <> false) {
            $where .= $select_filters;
        }
        $select_filters = $this->getFilters($this->getState('filter_career_level'), 'career_level');
        if($select_filters <> false) {
            $where .= $select_filters;
        }
        $select_filters = $this->getFilters($this->getState('filter_education'), 'education');
        if($select_filters <> false) {
            $where .= $select_filters;
        }
        unset($select_filters);

        $ref_num = JRequest::getString('ref_num');
        if(strlen($ref_num) > 0)
             $where .= " AND `ref_num` LIKE '%{$ref_num}%' ";

		//sort order ASC or DESC depending on sort parameter
		$sort = trim($app->getUserStateFromRequest('com_jobboard.extlist.sort','sort',''));
		switch ($sort)
		{
			case 'a':
				$direction = ' ASC ';
				break;
			case 'd':
				$direction = ' DESC ';
				break;
			default:
				$direction = ' DESC ';
				break;
		}

		//build ORDER BY clause based on order parameter
		$order = trim($app->getUserStateFromRequest('com_jobboard.extlist.order','order',''));
        $basic_order = ' ORDER BY j.`featured` DESC';
		switch ($order)
		{
			case 'date':
				$order_type = ', j.`post_date`'.$direction;
				break;
			case 'title':
				$order_type = ', j.`job_title`'.$direction;
				break;
			case 'level':
				$order_type = ', cl.`description`'.$direction;
				break;
			case 'city':
				$order_type = ', j.`city`'.$direction;
                if($locsrch != '')
                    $order_type .= ', `distance`';
				break;
			case 'type':
				$order_type = ', c.`type`'.$direction;
				break;
			case 'jobtype':
				$order_type = ', j.`job_type`'.$direction;
				break;
			case 'distance':
                if($locsrch == '') :
				    $order_type = ', j.`city`'.$direction;
                    $app->setUserState('com_jobboard.extlist.order', 'city');
                else :
				    $order_type = ', `distance`'.$direction;
                endif;
				break;
			default:
				$order_type = ', j.`post_date`'.$direction;
				break;
		}

        $orderby = $basic_order.$order_type;

        $table_columns = " j.`id`
                      , j.`job_title`
                       ";
        $tables_and_joins = " #__jobboard_jobs AS j
                      INNER JOIN #__jobboard_categories  AS c
                          ON (j.`category` = c.`id`)       ";
        if($locsrch != '' && $loc_cfg == 1) { // search by location
              $sel_distance = $app->getUserStateFromRequest("com_jobboard.extlist.sel_distance", 'sel_distance', 50, 'int');
              $geo_coords = $app->getUserStateFromRequest("com_jobboard.extlist.geo_coords", 'geo_coords', array(), 'array');
              if(isset($geo_coords['geo_latitude']) && isset($geo_coords['geo_longitude'])) {
                $proximity =  ' , ROUND(( '.$geo_coords['g_radius'].' * ACOS( COS( RADIANS('.$geo_coords['geo_latitude'].') ) * COS( RADIANS( j.`geo_latitude` ) ) * COS( RADIANS( j.`geo_longitude` ) - RADIANS('.$geo_coords['geo_longitude'].') ) + SIN( RADIANS('.$geo_coords['geo_latitude'].') ) * SIN( RADIANS( j.`geo_latitude` ) ) ) ), 2) AS distance ';
              }
              if($order <> 'distance') $orderby = $basic_order.', `distance` ASC'.$order_type;
              $limit = ' LIMIT '.$this->getState('limit');
    		  $query = "(SELECT ".$table_columns.$proximity."
                      FROM ". $tables_and_joins;
        	  $query .= $where.' AND j.`published`=1 ';
        	  $query .= ' HAVING distance < '.$sel_distance.' '.$orderby.$limit.")
                      UNION ALL
                        (SELECT ".$table_columns." , 0 AS distance
                         FROM ". $tables_and_joins;
        	  $query .= $where.' AND j.`published`=1 AND (j.`country` = 266 OR (j.`country` <> 266 AND j.`city` = "'.$locsrch.'" AND (j.`geo_latitude` IS NULL) ) ) HAVING distance = 0';
    		  $query .= $orderby.$limit.')';
        } else {
    		$query = "SELECT ".$table_columns."
                      FROM ". $tables_and_joins;
    		$query .= $where.' AND j.`published`=1 ';
    		$query .= $orderby;
        }

		return $query;
	}

    function getFilters($filter_arr, $name) {

       if(!empty($filter_arr)) {
			$s_where = array();
            if($name == 'job_type') :
                $job_type_values = $this->getJobTypes();
    			foreach($filter_arr as $value)
    			{
    				$s_where[] = 'j.`'.$name.'`'." = '{$job_type_values[$value]}'";
    			}
            else :
    			foreach($filter_arr as $value)
    			{
    				$s_where[] = 'j.`'.$name.'`'." = {$value}";
    			}
            endif;
			return ' AND ('.implode(' OR ',$s_where).')';

       } else return false;
    }

	function getCategories(){
		$db = & $this->getDBO();
		$sql = 'SELECT id, type
              FROM
                  #__jobboard_categories
                      WHERE enabled = true ORDER BY type ASC';
		$db->setQuery($sql);
		return $db->loadObjectList();
	}

	function getDefaultCat(){
		$db = & $this->getDBO();
		$sql = 'SELECT default_category
              FROM
                  #__jobboard_config';
		$db->setQuery($sql);
		return $db->loadResult();
	}

	function getTotal()
	{

		if (empty($this->_total)) {
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}
		return $this->_total;
	}

	function getPaginationVars()
	{
        $num_rows = $this->getTotal();
        $limit = $this->getState('limit');
        $limitstart = $this->getState('limitstart');
        $total_pages = ceil($num_rows / $limit);
        $curr_page = $limitstart >= 0? $limitstart + 1 : 1;
        $curr_page = $curr_page > $total_pages ? $total_pages : $curr_page;
        $offset = $limitstart * $limit;
        return array('limit'=>$limit, 'curr_page'=>$curr_page, 'limitstart'=>$limitstart, 'total'=>$num_rows, 'num_pages'=>$total_pages);
	}

	function getData()
	{
		$app= JFactory::getApplication();

		if(empty($this->data))
		{
		    $db = & $this->getDbo();
			$query = $this->_buildQuery();
            $_limit = $this->getState('limit');
            $_limitstart = $this->getState('limitstart');
            $_paging = ' LIMIT '.$_limitstart*$_limit.', '.$_limit;
            $db->setQuery($query.$_paging);
            $this->data = $db->loadAssocList();

			$this->count = $this->getTotal();
			$app->setUserState('com_jobboard.extlist.count',$this->count);
		}
		return $this->data;
	}

	function getSearch()
	{
		if(!$this->_search)
		{
			$app= JFactory::getApplication();
				
			$search = $app->getUserStateFromRequest("com_jobboard.extlist.jobsearch", 'jobsearch', '', 'string');
			$this->_search = (strpos($search, '(') === 0)? '' : JString::strtolower($search);
		}

		return $this->_search;
	}

	function getKeySearch()
	{
		if(!$this->_keysrch)
		{
			$app= JFactory::getApplication();
				
			$keysrch = $app->getUserStateFromRequest("com_jobboard.extlist.keysrch", 'keysrch', '', 'string');
			$this->_keysrch = (strpos($keysrch, '(') === 0)? '' :  JString::strtolower($keysrch);
		}
		return $this->_keysrch;
	}

	function getLocSearch()
	{
		if(!$this->_locsrch)
		{
			$app= JFactory::getApplication();
				
			$locsrch = $app->getUserStateFromRequest("com_jobboard.extlist.locsrch", 'locsrch', '', 'string');
			$this->_locsrch = (strpos($locsrch, '(') === 0)? '' :  JString::strtolower($locsrch);
		}
		return $this->_locsrch;
	}

    function getJobTypes() {
		return array(
                     0 =>'COM_JOBBOARD_DB_JFULLTIME' ,
        			 1 => 'COM_JOBBOARD_DB_JPARTTIME' ,
        			 2 => 'COM_JOBBOARD_DB_JCONTRACT' ,
        			 3 => 'COM_JOBBOARD_DB_JTEMP' ,
                     4 => 'COM_JOBBOARD_DB_JINTERN' ,
                     5 => 'COM_JOBBOARD_DB_JOTHER'
                   );
    }

    function getCareerlvls() {
		$db = & $this->getDBO();
		$sql = 'SELECT *
              FROM
                  #__jobboard_career_levels
              WHERE TRUE';
		$db->setQuery($sql);
		return $db->loadAssocList();

    }
    function getEdlvls() {
		$db = & $this->getDBO();
		$sql = 'SELECT *
              FROM
                  #__jobboard_education
              WHERE TRUE';
		$db->setQuery($sql);
		return $db->loadAssocList();
    }

    /** Get preset distance values for radial search  */
    function getDistances() {
		return array(
        			 0 => 10 ,
        			 1 => 15 ,
        			 2 => 20 ,
        			 3 => 30 ,
        			 4 => 50 ,
        			 5 => 70 ,
        			 6 => 100 ,
        			 7 => 300 ,
                     8 => 500 ,
                     9 => 1000,
                     10 => 5000,
                     11 => 10000
                   ); 
    }

}

?>