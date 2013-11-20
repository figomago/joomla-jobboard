<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class JobboardModelApplicantEdit extends JModel
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
			$this->_query = "SELECT ja.id
                            , ja.first_name
                            , ja.last_name
                            , ja.email
                            , ja.tel
                            , ja.title
                            , ja.filename
                            , ja.cover_note
                            , ja.file_hash
                            , ja.request_date
                            , ja.last_updated
                            , ja.admin_notes
                            , ja.status
                            , ja.filetype
                            , jb.id AS job_id
                            , jb.post_date
                            , jb.job_title
                            , jb.department
                            , jb.num_applications
                            , jb.hits
                        FROM
                            #__jobboard_jobs AS jb
                            INNER JOIN #__jobboard_applicants AS ja
                                ON (jb.id = ja.job_id)
                            WHERE ja.id=".$this->_id;
            $db->setQuery($this->_query);
            $this->_data = $db->loadObject();
		}

		return $this->_data;
	}
           
    function update($data) {
        $db = JFactory::getDBO();
		$this->_query = "UPDATE #__jobboard_applicants
                     SET email ='".$data->email."'
                     , tel ='".$data->tel."'
                 WHERE id=".$data->id;
        $db->setQuery($this->_query);
        return $db->query();
    }

}
?>