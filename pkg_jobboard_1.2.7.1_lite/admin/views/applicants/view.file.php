<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class JobboardViewApplicants extends JView
{
	function display($tpl = null)
	{
        $document =& JFactory::getDocument();

        if ($fd = fopen ($this->file, "r")) {
            $fileSize = filesize($this->file);
            $path_parts = pathinfo($this->file);
            // echo '<pre>'.print_r($path_parts, true).'</pre>';die;
          	// Clears file status cache
        	clearstatcache();
        	$mimeType			= '';

            if(!isset($this->filetype))  {
              switch($path_parts['extension']){
                case 'doc' :
                  $mimeType = 'application/msword';
                case 'docx' :
                  $mimeType = 'application/msword';
                case 'pdf' :
                  $mimeType = 'application/pdf';
                case 'txt' :
                  $mimeType = 'text/plain';
              }
           } else {
                $mimeType = $this->filetype;
           }

        	// Clean the output buffer
        	ob_end_clean();

        	header("Cache-Control: public, must-revalidate");
        	header("Expires: 0");
            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        	header('Cache-Control: pre-check=0, post-check=0, max-age=0');
        	header("Pragma: no-cache");
        	header("Content-Description: File Transfer");
        	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
        	header("Accept-Ranges: bytes");

        	// HTTP Range
        	$httpRange = 0;
        	if(isset($_SERVER['HTTP_RANGE'])) {
        		list($a, $httpRange) = explode('=', $_SERVER['HTTP_RANGE']);
        		str_replace($httpRange, '-', $httpRange);
        		$new_fsize	= $fileSize - 1;
        		$new_fsize_hr	= $fileSize - $httpRange;
        		header("HTTP/1.1 206 Partial Content");
        		header("Content-Length: ".(string)$new_fsize_hr);
        		header("Content-Range: bytes ".$httpRange . $new_fsize .'/'. $fileSize);
        	} else {
        		$new_fsize	= $fileSize - 1;
        		header("Content-Length: ".(string)$fileSize);
        		header("Content-Range: bytes 0-".$new_fsize . '/'.$fileSize);
        	}
        	header("Content-Type: " . (string)$mimeType);
        	header('Content-Disposition: attachment; filename="'.$path_parts["basename"].'"');
        	header("Content-Transfer-Encoding: binary\n");

        	@set_time_limit(0);
        	$fp = @fopen($this->file, 'rb');
        	if ($fp !== false) {
        		while (!feof($fp)) {
        			echo fread($fp, 8192);
        		}
        		fclose($fp);
        	} else {
        		@readfile($this->file);
        	}
        	flush();
        	exit;
      }
	}
}

?>