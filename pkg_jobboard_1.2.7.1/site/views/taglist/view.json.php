<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class JobboardViewList extends JView
{
	function display($tpl = null)
	{

        jimport('joomla.utilities.date');

        $app = JFactory::getApplication();

        $daterange = $app->getUserStateFromRequest("com_jobboard.daterange", 'daterange', 0, 'int');
		$layout = $app->getUserStateFromRequest('com_jobboard.list.layout','layout','');
       // $this->_addScripts($layout);
        $data =& $this->get('data');
		$search = $this->get('search');
        $this->assignRef('data', $data);
        $this->assignRef('categories', JRequest::getVar('categories',''));
		$this->assign('daterange', intval($daterange));
        $this->assign('selcat', JRequest::getVar('selcat',''));
		$this->assign('search', $search);
		$this->assign('keysrch', JRequest::getVar('keysrch',''));
		$this->assign('locsrch', JRequest::getVar('locsrch',''));

        $document =& JFactory::getDocument();

        // Set the MIME type for JSON output.
        $document->setMimeEncoding( 'application/json' );

        // Change the suggested filename.
        JResponse::setHeader( 'Content-Disposition', 'attachment; filename="jobList.json"' );
        // Output the JSON data.
        echo json_encode( $data );
	}

	function _addScripts($layout)
	{
	    JHTML::_('behavior.mootools');
	    $layout = ($layout == '')? "table" : $layout;
	    $document =& JFactory::getDocument();
	    $document->addStyleSheet('components/com_jobboard/css/base.css');
	    $document->addStyleSheet('components/com_jobboard/css/'.$layout.'_layout.css');
	    $document->addScript('components/com_jobboard/js/list.js');
	}
}

?>