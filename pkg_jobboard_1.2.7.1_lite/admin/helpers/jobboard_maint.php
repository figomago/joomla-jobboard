<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

class JobBoardMaintHelper
{

    function parseXMLFile($filename, $table_suffix, $extract_folder) {
        $import_file = $extract_folder.DS.$filename;
        $imported_data = importXML($import_file);

        $sanitised_arr = array();
        $source_keys = null;
        $rec_counter = 0;

        foreach($imported_data as $row) {
            if(!is_array($source_keys))
      	      $source_keys = array_keys($row);
            foreach ($source_keys as $key) {
                $sanitised_arr[$rec_counter][$key] = (is_array($row[$key]) && empty($row[$key]))? '' : $row[$key];
            }
            $rec_counter++;
        }
        unset($imported_data);
        echo '<pre>'.print_r($sanitised_arr, true).'</pre>'; die;
    }

    function importXML($xml_file){
        $xml = simplexml_load_file($xml_file, 'SimpleXMLElement', LIBXML_NOCDATA);
        $data_json = json_encode($xml);
        return json_decode($data_json, TRUE);
    }


}

?>