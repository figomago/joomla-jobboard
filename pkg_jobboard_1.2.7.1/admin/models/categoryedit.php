<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class JobboardModelCategoryEdit extends JModel
{
	var $_total;
    var $_id;
	var $_query;
	var $_data;

	function __construct()
	{
		parent::__construct();

	    $cid = JRequest::getVar('cid', false, 'DEFAULT', 'array');
        if($cid){
          $id = $cid[0];
        }
        else $id = JRequest::getInt('id', 0);
        $this->setId($id);
	}

    function setId($id=0)
    {
      $this->_id = $id;
      $this->_query = null;
      $this->_data = null;
      $this->_total = null;
    }

	function getData()
	{
		if(empty($this->_data))
		{
            $db = JFactory::getDBO();
			$this->_query = "SELECT mca.id, mca.option_type, mca.num_adults, mca.num_children, mco.adult_contr, mco.childr_contr, mco.savings_fac
                    FROM #__jobboard_categories AS mca
                    INNER JOIN #__jobboard_contributions AS mco ON (mca.id = mco.id)
                    WHERE mca.id=".$this->_id;
            $db->setQuery($this->_query);
            $this->_data = $db->loadObject();
		}
		
		return $this->_data;
	}

    function save($data) {
        $db = JFactory::getDBO();
		$this->_query = "UPDATE #__jobboard_categories AS mca, #__jobboard_contributions AS mco
                     SET mca.option_type ='".$data->option_type."'
                     , mca.num_adults =".$data->num_adults."
                     , mca.num_children =".$data->num_children."
                     , mco.adult_contr =".$data->adult_contr."
                     , mco.childr_contr =".$data->childr_contr."
                     , mco.total =".$data->total."
                     , mco.savings_fac =".$data->savings_fac."
                 WHERE mca.id = mco.id
                 AND mca.id=".$data->id;
        $db->setQuery($this->_query);  
        return $db->query();
    }

}
?>