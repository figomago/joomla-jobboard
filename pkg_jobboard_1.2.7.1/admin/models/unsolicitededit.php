<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class JobboardModelUnsolicitedEdit extends JModel
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
            $db = & $this->getDBO();
			$this->_query = 'SELECT *
                        FROM '.$db->nameQuote('#__jobboard_unsolicited').'
                            WHERE '.$db->nameQuote('id').' = '.$this->_id;
            $db->setQuery($this->_query);
            $this->_data = $db->loadObject();
		}

		return $this->_data;
	}

   }
?>