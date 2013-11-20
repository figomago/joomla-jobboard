<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class JobboardViewExtlist extends JView
{
	function display($tpl = null)
	{

        jimport('joomla.utilities.date');

        $option = 'com_jobboard';
        $document =& JFactory::getDocument();
        $doc = & JDocument::getInstance('json');

        // Set the MIME type for JSON output.
        if(version_compare(JVERSION,'2.5.0','ge')) {

        } else {
           $doc->setMimeEncoding( 'application/json' );

          // Change the suggested filename.
          JResponse::setHeader( 'Content-Disposition', 'attachment; filename="results.json"' );

        }
        // Output the JSON data.
        echo json_encode( $this->data);
	}
}

?>