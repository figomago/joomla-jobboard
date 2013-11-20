<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

class JobBoardTblHelper
{
    function genSqlMod($col) {
       switch($col->type) {
         case 'checkbox':
            $result = 'ALTER TABLE [table] ADD `'.$col->name.'` tinyint(1) DEFAULT '.$col->deflt->value.' ;';
         break;
         case 'radio':
            $sel_deflt = 0;
            foreach($col->deflt->options as $opt) {
               $sel_deflt = ($opt->value == $col->deflt->defaultOpt)? intval($opt->value) : $sel_deflt;
            }
            $result = 'ALTER TABLE [table] ADD `'.$col->name.'` int(11) DEFAULT  "'.$sel_deflt.'" ;';
         break;
         case 'select':
            $result = 'ALTER TABLE [table] ADD `'.$col->name.'` text ;';
         break;
         case 'text':
            $result = 'ALTER TABLE [table] ADD `'.$col->name.'` varchar(255) DEFAULT  "'.$col->deflt.'" ;';
         break;
         case 'textarea':
            $result = 'ALTER TABLE [table] ADD `'.$col->name.'` text ;';
         break;
         case 'date':
            $date_day = intval($col->deflt->defaultDay);
            $date_day = ($date_day < 0 ||$date_day > 31)? 1 : $date_day;
            $date_month= intval($col->deflt->defaultMonth);
            $date_month = ($date_month < 0 ||$date_month > 12)? 1 : $date_month;
            $date_year = intval($col->deflt->defaultYear);
            $date_year = ($date_year < 0)? '2000' : $date_year;
            $default_date =  $date_year.'-'.sprintf("%02d", $date_month).'-'.sprintf("%02d", $date_day);
            $result = 'ALTER TABLE [table] ADD `'.$col->name.'` date DEFAULT  "'.$default_date.'" ;';
         break;
         default:
         ;break;
       }
       return $result;
    }

    function genSqlDrop($colname) {
       return 'ALTER TABLE [table] DROP `'.$colname.'`;';
    }
}

?>