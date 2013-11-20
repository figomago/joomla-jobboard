<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables');
jimport('joomla.application.component.controller');

class JobboardControllerList extends JController
{
    /**
    * constructor
    */
	function __construct()
	{
		parent::__construct();

		$this->registerTask('reset_keywds', 'resetListKeywords');
		$this->registerTask('switch_layout', 'switchLayout');

    }

    function display() {
      $this->_showList();
    }

    function switchLayout() {
        $app = JFactory::getApplication();

        $layout = $app->getUserStateFromRequest('com_jobboard.list.layout', 'layout');
        $layout = $layout == 'list'? 'table' : 'list' ;
        $app->setUserState('com_jobboard.list.layout', $layout, 'string');

        $this->_showList();
    }

    function getGeoJson($geo_uri) {
       return JobBoardFormatHelper::getJsonFromUrl($geo_uri);
    }

    private function _getGeoCoordinates($lat_long_coordinates = '', $location, $distance=50, $units=0) {
      require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jobboard_format.php' );

      $g_radius = $units == 1? 3959 : 6371;   // Miles/Km
      $lang = & JFactory::getLanguage()->getTag();
      $lang = explode('-', $lang);

      if(!is_array($lat_long_coordinates)) {
        $app = JFactory::getApplication();

        $api_cache = & JFactory::getCache();
        $api_cache->setCaching(1);
        //get coordinates by location string if not provided

        $geo_uri = "https://maps.googleapis.com/maps/api/geocode/json?address=".JobBoardFormatHelper::formatQuerySegments($location)."&sensor=false&language=".$lang[0];

        $api_cache->setLifeTime(2*60*60*24*7); /*Cache for 2 weeks*/
        $remote_result = $api_cache->call( array('JobboardControllerList', 'getGeoJson'), $geo_uri);

        $address_coords; $address_components; $address_string = ''; $address_data = array();

         if(!empty($remote_result) && is_array($remote_result)) {
           if($remote_result['status'] == 'OK') {

                $address_coords = $remote_result['results'][0]['geometry']['location'];
                $address_components = $remote_result['results'][0]['address_components'];
                unset($remote_result);

                if(!empty($address_coords) && is_array($address_coords)){
                  $lat_long_coordinates = array('geo_latitude'=>$address_coords['lat'], 'geo_longitude'=>$address_coords['lng'], 'g_radius'=>$g_radius);
                }

                if(!empty($address_components) && is_array($address_components)){
                   foreach($address_components as $addr_seg)  {
                         $addr_types = $addr_seg['types'];

                         if(in_array('political', $addr_types)) {
                             if(in_array('sublocality', $addr_types)) {
                                $address_data['suburb'] = $addr_seg['long_name'];
                             }

                             if(in_array('locality', $addr_types)) {
                                $address_data['city'] = $addr_seg['long_name'];
                             }

                             if(in_array('administrative_area_level_1', $addr_types)) {
                                $address_data['province'] = $addr_seg['long_name'];
                             } else { //fallback
                               if(in_array('administrative_area_level_2', $addr_types)) {
                                    $address_data['province'] = $addr_seg['long_name'];
                               } else {
                                   if(in_array('administrative_area_level_3', $addr_types))
                                     $address_data['province'] = $addr_seg['long_name'];
                                   }
                               }
                         }
                         if(in_array('postal_code', $addr_types)) {
                            $address_data['postal_code'] = $addr_seg['long_name'];
                         }

                         if(in_array('country', $addr_types) && in_array('political', $addr_types)) {
                            $address_data['country'] = $addr_seg['long_name'];
                            if($address_data['country'] == 'United Kingdom') $address_data['country'] = 'UK';
                         }
                     }
                }

               $keys = array_keys($address_data);
               if(count($address_data) > 3 ) {
                  $address_string .= '<strong>'.$address_data[$keys[0]].'</strong>';
                  $address_string .= ', <strong>'.$address_data[$keys[1]].'</strong>, ';
                  unset($address_data[$keys[0]], $address_data[$keys[1]]);
               } elseif(count($address_data) <= 3 ) {
                  $address_string .= '<strong>'.$address_data[$keys[0]].'</strong>, ';
                  unset($address_data[$keys[0]]);
               }

               $address_string .= implode(', ', $address_data);

               $app->setUserState("com_jobboard.geo_address", $address_string, 'string');
           }
         }
      }

      if(isset($lat_long_coordinates['geo_latitude']) && isset($lat_long_coordinates['geo_longitude'])) {
        return $lat_long_coordinates;
      }

  }

    private function _showList($selcat=1, $reset_keywds=false)
	{
        $app = JFactory::getApplication();

        $r_srch = JRequest::getString('jobsearch');
        $r_srchkey = JRequest::getString('keysrch');
        $r_srchloc = JRequest::getString('locsrch');
        if(!empty($r_srch) || !empty($r_srchkey) || !empty($r_srchloc)) JRequest::checkToken() or jexit('Invalid Token');

    	$search = JString::trim($app->getUserStateFromRequest("com_jobboard.jobsearch", 'jobsearch', '', 'string') );
    	$search = (strpos($search, '(') === 0)? '' : JString::strtolower($search);
    	$keysrch = JString::trim($app->getUserStateFromRequest("com_jobboard.keysrch", 'keysrch', '', 'string') );
    	$keysrch = (strpos($keysrch, '(') === 0)? '' : JString::strtolower($keysrch);
    	$locsrch = JString::trim($app->getUserStateFromRequest("com_jobboard.locsrch", 'locsrch', '', 'string'));
    	$locsrch = (strpos($locsrch, '(') === 0)? '' : JString::strtolower($locsrch);

        $cb_reset = JRequest::getInt('cb_reset', 0);
        if($cb_reset == 1) {                         
           $app->setUserState("com_jobboard.filter_job_type", array(), 'array');
           $app->setUserState("com_jobboard.filter_careerlvl", array(), 'array');
           $app->setUserState("com_jobboard.filter_edulevel", array(), 'array');
        } else {
           $filter_job_type = $app->getUserStateFromRequest("com_jobboard.filter_job_type", 'filter_job_type', array(), 'array');
           $filter_careerlvl = $app->getUserStateFromRequest("com_jobboard.filter_careerlvl", 'filter_careerlvl', array(), 'array');
           $filter_edulevel = $app->getUserStateFromRequest("com_jobboard.filter_edulevel", 'filter_edulevel', array(), 'array');
        }
                    
        $ref_num = JRequest::getString('ref_num');
        JRequest::setVar('ref_num', $ref_num);

        $config_model =& $this->getModel('Config');
        $default_daterange = $config_model->getDateRangeCfg();
        $daterange = $app->getUserStateFromRequest("com_jobboard.daterange", 'daterange', $default_daterange, 'int');
		$country_id = $app->getUserStateFromRequest('com_jobboard.list.country_id', 'country_id', 0, 'int');
        $sort = JString::trim($app->getUserStateFromRequest('com_jobboard.list.sort','sort',''));
        $order = JString::trim($app->getUserStateFromRequest('com_jobboard.list.order', 'order', '', 'string'));

        $layout = JRequest::getString('layout', '');
        if(empty($layout)) {
          $layout_instate = $app->getUserState('com_jobboard.list.layout');
          if(empty($layout_instate)) {
              $layout = ($config_model->getListcfg() == 0)? 'list' : 'table';
          } else {
               $layout = $layout_instate;
            }
        }

        $app->setUserState('com_jobboard.list.layout', $layout, 'string');

        $switch_layout = JRequest::getInt('switch_layout', 0);

        if($switch_layout === 1) {
            $layout = $layout == 'list'? 'table' : 'list';
            $app->setUserState('com_jobboard.list.layout', $layout);
        }

    	$selcat = $app->getUserStateFromRequest('com_jobboard.list.selcat', 'selcat', 1);
        $selcat = $selcat == 0? 1 : $selcat;
        $selcat = $app->setUserState('com_jobboard.list.selcat', $selcat, 'int');
		$country_id = $app->getUserStateFromRequest('com_jobboard.list.country_id','country_id', 0, 'int');

        $search_cfg = $config_model->getLocCfg();

        if($search_cfg['use_location'] == 1 && strlen($locsrch) > 0){
           $app->setUserState("com_jobboard.geo_address", '', 'string');
           if(!JobBoardHelper::getSite('google.com')) {
             $msg =  JText::_('COM_JOBBOARD_NO_NETWORK');
             $locsrch = '';
             $app->setUserState("com_jobboard.locsrch", '', 'string');
             $app->enqueueMessage(JText::_('COM_JOBBOARD_LIST_LOCDATA').': '.$msg, 'error');
           } else {
             $app->setUserState('com_jobboard.use_location', $search_cfg['use_location'], 'int');
             $app->setUserState('com_jobboard.list.measuring_unit', 'measuring_unit', $search_cfg['distance_unit'], 'string');
             $radius = JRequest::getInt('radius', $search_cfg['default_distance']);
             $sel_distance = $app->setUserState("com_jobboard.sel_distance", $radius, 'int');

             $geo_coords = $this->_getGeoCoordinates('', $locsrch, $radius, $search_cfg['distance_unit']);
             $app->setUserState("com_jobboard.geo_coords", $geo_coords, 'array');
           }
        } else {
          $app->setUserState("com_jobboard.sel_distance", null, 'int');
          $app->setUserState('com_jobboard.use_location', 0, 'int');
        }

        $cat_model =& $this->getModel('List');

		$view = $app->getUserStateFromRequest('com_jobboard.list.view','view','list');
        $format = JRequest::getString('format') == 'feed'? 'feed' : 'html';
		$view  =& $this->getView($view, $format);

        $view->assign('selcat', $selcat);
        $view->assign('keysrch', $keysrch);

        if($format == 'html') {
          $view->setModel($cat_model, true);
          $view->setModel($config_model);
          //$view->setLayout($layout);
          $view->assign('country_id', $country_id);
          $view->assign('daterange', $daterange);
          $view->assign('jobsearch', $search);
          $view->assign('locsrch', $locsrch);
          $view->assign('layout', $layout);
          $view->assign('ref_string', $ref_num);
        }

	    $view->display();
	}

    function resetListKeywords() {
        $app = JFactory::getApplication();

  	    $selcat = $app->getUserStateFromRequest('com_jobboard.list.selcat', 'selcat', 1, 'int');
        $this->_showList($selcat, true);
    }
}

$controller = new JobboardControllerList();
$controller->execute($task);
$controller->redirect();

?>