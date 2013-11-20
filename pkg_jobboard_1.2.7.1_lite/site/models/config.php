<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

   // Protect from unauthorized access
   defined('_JEXEC') or die('Restricted Access');

   class JobboardModelConfig extends JModel
   {

       var $_id;
       var $_result;

     /**
     * Constructor, builds object and determines the ID  (always set to 1)
     *
     */
       function __construct()
       {
         parent :: __construct();

         $id = 1;
         $this->setId($id);
       }

     /**
     * Initialise the ID and data
     *
     * @param integer ID
     */
       function setId($id)
       {
         $this->_id = $id;
         $this->_result = null;
       }

       function getConfig() {
           $db = & $this->getDBO();
           $sql = 'SELECT * FROM `#__jobboard_config`
                      WHERE `id` = 1';
           $db->setQuery($sql);
           $this->_result = $db->loadObject();
           return $this->_result;
       }
       
       function getJobConfig() {
           $db = & $this->getDBO();
           $sql = 'SELECT `allow_applications`, `show_social`, `show_viewcount`,
                  `show_applcount`, `show_job_summary`, `send_tofriend`, `long_date_format`,
                  `jobtype_coloring`, `use_location`, `social_icon_style`, `allow_once_off_applications`, `enable_post_maps`
                FROM `#__jobboard_config`
                WHERE `id` = 1';
           $db->setQuery($sql);
           return $db->loadObject();
       }
       
       function getApplyConfig() {
           $db = & $this->getDBO();
           $sql = 'SELECT `allow_once_off_applications`, `appl_job_summary`, `show_applcount`, `long_date_format`, `jobtype_coloring`, `use_location`  FROM `#__jobboard_config`
                      WHERE `id` = 1';
           $db->setQuery($sql);
           $this->_result = $db->loadObject();
           return $this->_result;
       }
       
       function getShareConfig() {
           $db = & $this->getDBO();
           $sql = 'SELECT `send_tofriend`, `sharing_job_summary`, `long_date_format`, `jobtype_coloring`, `use_location` FROM `#__jobboard_config`
                      WHERE `id` = 1';
           $db->setQuery($sql);
           $this->_result = $db->loadObject();
           return $this->_result;
       }
   
       function getUnsolConfig() {
           $db = & $this->getDBO();
           $sql = 'SELECT `allow_unsolicited` FROM `#__jobboard_config`
                      WHERE `id` = 1';
           $db->setQuery($sql);
           $this->_result = $db->loadObject();
           return $this->_result;
       }

       function getQuerycfg() {
           $db = & $this->getDBO();
           $sql = 'SELECT `default_post_range`, `allow_unsolicited`, `jobtype_coloring`, `use_location`, `distance_unit`, `default_distance`, `long_date_format` FROM `#__jobboard_config`
                      WHERE `id` = ' . $this->_id;
           $db->setQuery($sql);
           $this->_result = $db->loadObject();
           return $this->_result;
       }

       function getDateRangeCfg() {
           $db = & $this->getDBO();
           $sql = 'SELECT `default_post_range` FROM `#__jobboard_config`
                      WHERE `id` = ' . $this->_id;
           $db->setQuery($sql);
           return $db->loadResult();
       }

       function getListcfg() {
           $db = & $this->getDBO();
           $sql = 'SELECT `default_list_layout` FROM `#__jobboard_config`
                      WHERE `id` = 1';
           $db->setQuery($sql);
           return $db->loadResult();
       }

       function getdefaultCat() {
           $db = & $this->getDBO();
           $sql = 'SELECT `default_category` FROM `#__jobboard_config`
                      WHERE `id` = 1';
           $db->setQuery($sql);
           return $db->loadResult();
       }

    	function getLocCfg()
    	{
    		$db = & $this->getDBO();
    		$sql = 'SELECT `use_location`, `distance_unit`, `default_distance`
                  FROM
                      `#__jobboard_config`';
    		$db->setQuery($sql);
    		return $db->loadAssoc();
    	}
}

?>