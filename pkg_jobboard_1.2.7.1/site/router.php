<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
* Builds route for the JobBoard component.
*
* @access public
* @param array Query associative array
* @return array SEF URI segments
*/

function JobboardBuildRoute( & $query) {

	$segments = array ();
	$menu = & JSite::getMenu();

	if ( empty($query['Itemid'])) {
		$menuItem = & $menu->getActive();
	}
	else {
		$menuItem = & $menu->getItem($query['Itemid']);
	}

	$jView = ( empty($menuItem->query['view']))?null:$menuItem->query['view'];
	$jLayout = ( empty($menuItem->query['layout']))?null:$menuItem->query['layout'];
	$jCatId = ( empty($menuItem->query['selcat']))?null:$menuItem->query['selcat'];
	$jDaterange = ( empty($menuItem->query['daterange']))?null:$menuItem->query['daterange'];
	$jjobsearch = ( empty($menuItem->query['jobsearch']))?null:$menuItem->query['jobsearch'];
	$jKeysrch = ( empty($menuItem->query['keysrch']))?null:$menuItem->query['keysrch'];
	$jLocsrch = ( empty($menuItem->query['locsrch']))?null:$menuItem->query['locsrch'];
	$jID = ( empty($menuItem->query['id']))?null:$menuItem->query['id'];                                              // && @intval($query['id']) > 0
	$jJobid = ( empty($menuItem->query['job_id']))?null:$menuItem->query['job_id'];
    $is_jobitem = $is_listcat = $has_keywd = $has_keysrch = $has_locsrch = false;

	if ( isset ($query['view'])) {
		$segments[] = $query['view'];
        $is_jobitem = $query['view'] == 'job'? true : false;
        $is_listcat = $query['view'] == 'list'? true : false;
		unset ($query['view']);
	}
	if ( isset ($query['task'])) {
		$segments[] = $query['task'];
		unset ($query['task']);
	}

	if ( isset ($query['id'])) {
		$segments[] = $query['id'];
		unset ($query['id']);
	}
    if ( isset ($query['bid'])) {
		$segments[] = $query['bid'];
		unset ($query['bid']);
    }
	if ( isset ($query['job_id'])) {
		$segments[] = $query['job_id'];
		unset ($query['job_id']);
    }

	if ( isset ($query['selcat'])) {
		$segments[] = $query['selcat'];
		unset ($query['selcat']);
    }

	if (@ isset ($query['layout'])) {
		$segments[] = $query['layout'];
		unset ($query['layout']);
	}

	if ( isset ($query['daterange'])) {
		$segments[] = $query['daterange'];
		unset ($query['daterange']);
	}

	if ( isset ($query['jobsearch'])) {
		$segments[] = $query['jobsearch'];
        $has_keywd = true;
		unset ($query['jobsearch']);
	}

	if ( isset ($query['keysrch'])) {
		$segments[] = $query['keysrch'];
        $has_keysrch = true;
		unset ($query['keysrch']);
	}

	if ( isset ($query['locsrch'])) {
		$segments[] = $query['locsrch'];
        $has_locsrch = true;
		unset ($query['locsrch']);
	}

	if ( isset ($query['fileid'])) {
		$segments[] = $query['fileid'];
		unset ($query['fileid']);
	}

	if ( isset ($query['profileid'])) {
		$segments[] = $query['profileid'];
		unset ($query['profileid']);
	}

	if ( isset ($query['prof'])) {
		$segments[] = $query['prof'];
		unset ($query['prof']);
	}
	if ( isset ($query['tab'])) {
		$segments[] = $query['tab'];
		unset ($query['tab']);
	}

	if ( isset ($query['emode'])) {
		$segments[] = $query['emode'];
		unset ($query['emode']);
	}

	if ( isset ($query['callstep'])) {
		$segments[] = $query['callstep'];
		unset ($query['callstep']);
	}

	if ( isset ($query['admin'])) {
		$segments[] = $query['admin'];
		unset ($query['admin']);
	}
	if ( isset ($query['qlist'])) {
		$segments[] = $query['qlist'];
		unset ($query['qlist']);
	}
	if ( isset ($query['iview'])) {
		$segments[] = $query['iview'];
		unset ($query['iview']);
	}
	if ( isset ($query['f_reset'])) {
		$segments[] = $query['f_reset'];
		unset ($query['f_reset']);
	}
   	if ( isset ($query['curr_dash'])) {
		$segments[] = $query['curr_dash'];
		unset ($query['curr_dash']);
	}
   	if ( isset ($query['aid'])) {
		$segments[] = $query['aid'];
		unset ($query['aid']);
	}
   	if ( isset ($query['pid'])) {
		$segments[] = $query['pid'];
		unset ($query['pid']);
	}
   	if ( isset ($query['sid'])) {
		$segments[] = $query['sid'];
		unset ($query['sid']);
	}
   	if ( isset ($query['jid'])) {
		$segments[] = $query['jid'];
		unset ($query['jid']);
	}
   	if ( isset ($query['s_mode'])) {
		$segments[] = $query['s_mode'];
		unset ($query['s_mode']);
	}
   	if ( isset ($query['status'])) {
		$segments[] = $query['status'];
		unset ($query['status']);
	}
   	if ( isset ($query['cat_id'])) {
		$segments[] = $query['cat_id'];
		unset ($query['cat_id']);
	}
   	if ( isset ($query['qid'])) {
		$segments[] = $query['qid'];
		unset ($query['qid']);
	}
    if ( isset ($query['redirect'])) {
       if(empty($query['redirect']))
        unset($query['redirect']);
    }

    if($is_jobitem){
       $segments_job_title = _parseItem($segments[1]);
       if(!empty($segments_job_title))
         $segments[] = $segments_job_title;
    }
    if($is_listcat){
       $category_id = empty($segments[1])? 1 : $segments[1];
       $segments_catname = _parseCateg($category_id);
       if(!empty($segments_catname)) {
         if(!isset($segments[1]))
            $segments[] = $category_id.'-'.$segments_catname;
         else
            $segments[1] = $category_id.'-'.$segments_catname;
       }
    }

	return $segments;
}

/**
* Decodes SEF URI segments for the JobBoard component.
*
* @access public
* @param array SEF URI segments array
* @return array Query associative array
*/
function JobboardParseRoute($segments) {
	$vars = array ();
	$vars['view'] = $segments[0];

	if ($segments[0] == 'job') {
    	if (!isset($segments[1]))
    	   $segments[1]='';
        if (isset($segments[1])) {
               if($segments[1] == 'share'){
                 $vars['task'] = $segments[1];
               } else {
            	   $vars['id'] = $segments[1];
                   if (isset($segments[2]))
              	    $vars['selcat'] = $segments[2];
                   if (isset($segments[3]))
                     $vars['lyt'] = $segments[3];
               }
        }
	}
	elseif($segments[0] == 'list') {
        	if (!isset($segments[1]))
        		$segments[1]='';
            if (isset($segments[1])) {
                $cat_segments = explode(':', $segments[1]);
			    $vars['selcat'] = $cat_segments[0];
            }
            if (isset($segments[2]))
			    $vars['jobsearch'] = $segments[2];
            if (isset($segments[3]))
			    $vars['keysrch'] = $segments[3];
            if (isset($segments[4]))
			    $vars['locsrch'] = $segments[4];
	}
	elseif($segments[0] == 'taglist') {
        	if (!isset($segments[1]))
        		$segments[1]='';
            if (isset($segments[1]))
        	    $vars['keysrch'] = $segments[1];
	}
	elseif ($segments[0] == 'apply' || $segments[0] == 'share') {
        	if (!isset($segments[1]))
        		$segments[1]='';
            if (isset($segments[1]))
        	    $vars['job_id'] = $segments[1];
	}
	elseif ($segments[0] == 'upload') {
            if (isset($segments[1]))
        	    $vars['task'] = $segments[1];
	}
	elseif ($segments[0] == 'query') {
        	if (!isset($segments[1]))
        		$segments[1]='';
        	$vars['layout'] = $segments[1];
            if (isset($segments[2]))
        	    $vars['catid'] = $segments[2];
            if (isset($segments[3]))
        	    $vars['jobsearch'] = $segments[3];
            if (isset($segments[3]))
        	    $vars['locsrch'] = $segments[3];
            if (isset($segments[4]))
        	    $vars['locsrch'] = $segments[4];
	}
	elseif ($segments[0] == 'user') {
        	if (!isset($segments[1]))
        		$segments[1]='';
            if($segments[1] == '0' || $segments[1] == '1')
               $vars['curr_dash'] = $segments[1];
            else{
            	$vars['task'] = $segments[1];
            	if (!isset($segments[2]))
            		$segments[2]='';
                if($vars['task'] == 'getfile' || $vars['task'] == 'delcvfile' ){
                    if(isset($segments[2]))
                        $vars['fileid'] = $segments[2];
                    if(isset($segments[3]))
                        $vars['profileid'] = $segments[3];
                } else{
                  if($vars['task'] == 'prof'){
        		       $vars['tab'] = $segments[2];
                    }   else
                	$vars['profileid'] = $segments[2];
                	if (!isset($segments[3]))
                		$segments[3]='';
                	$vars['emode'] = $segments[3];
                	if (!isset($segments[4]))
                		$segments[4]='';
                	$vars['callstep'] = $segments[4];
                }
                if($vars['task'] == 'clonecv' ){
                    if(isset($segments[2]))
                        $vars['profileid'] = $segments[2];
                }
                if($vars['task'] == 'apply' ){
                    if(isset($segments[2]))
                        $vars['jid'] = $segments[2];
                    if(isset($segments[3]))
                        $vars['cat_id'] = $segments[3];
                    if(isset($segments[4]))
                        $vars['qid'] = $segments[4];
                }
                if($vars['task'] == 'prof' ){
                    if(isset($segments[2]))
                        $vars['tab'] = $segments[2];
                }
                if($vars['task'] == 'addfav'){
                    if(isset($segments[2]))
                        $vars['job_id'] = $segments[2];
                }
                if($vars['task'] == 'jdelfav'){
                    if(isset($segments[2]))
                        $vars['bid'] = $segments[2];
                    if(isset($segments[3]))
                        $vars['job_id'] = $segments[3];
                }
            }
	}
        elseif ($segments[0] == 'admin') {
        	if (!isset($segments[1]))
        		$segments[1]='';
            if($segments[1] == '0' || $segments[1] == '1')
               $vars['curr_dash'] = $segments[1];
            else{
            	$vars['task'] = $segments[1];
                if($segments[1] == 'appl' && isset($segments[2]) )
                    $vars['jid'] = $segments[2];
                if($segments[1] == 'viewcv' && isset($segments[2]) ) {
                    $vars['pid'] = $segments[2];
                    if(isset($segments[3]))
                        $vars['sid'] = $segments[3];
                    if(isset($segments[4]))
                        $vars['jid'] = $segments[4];
                    if(isset($segments[5]))
                        $vars['s_mode'] = $segments[5];
                }
                if($segments[1] == 'cvsrch' && isset($segments[2]) )
                    $vars['f_reset'] = $segments[2];
                if($segments[1] == 'edappl' && isset($segments[2]) )  {
                    $vars['aid'] = $segments[2];
                    if(isset($segments[3]))
                        $vars['pid'] = $segments[3];
                }
                if(($segments[1] == 'viewq' || $segments[1] == 'edq' || $segments[1] == 'delq') && isset($segments[2]) )
                    $vars['qid'] = $segments[2];
                if($segments[1] == 'jobstatus' && isset($segments[2]) )  {
                    $vars['jid'] = $segments[2];
                    if(isset($segments[3]))
                        $vars['status'] = $segments[3];
                }
            }
        }

        elseif ($segments[0] == 'member') {
           	if (isset($segments[1]))
              if($segments[1] == 'login' || $segments[1] == 'register' || $segments[1] == 'logout'){
              	$vars['iview'] = $segments[1];
            }
        }

        elseif ($segments[0] == 'rss') {
           	if (isset($segments[1]))
              	$vars['selcat'] = $segments[1];
           	if (isset($segments[2]))
              	$vars['keysrch'] = $segments[2];
        }

	return $vars;
}

    function _parseItem($job_id)  {
        $db = & JFactory::getDBO();
        $_city = _useLoc()? ', '.$db->nameQuote('city') : '';
        $query = 'SELECT '.$db->nameQuote('job_title').$_city.', '.$db->nameQuote('ref_num').'
            FROM '.$db->nameQuote('#__jobboard_jobs').'
            WHERE '.$db->nameQuote('id').' = '.$job_id;
        $db->setQuery($query);
        $result = $db->loadAssoc();
        $_processed_title = empty($result['job_title'])? null : $result['job_title'];
        if(!empty($_processed_title)) :
          $result['job_title'] = !empty($result['city'])? $result['job_title'].' '.$result['city'] : $result['job_title'];
          $result['job_title'] = !empty($result['ref_num'])? $result['job_title'].' '.$result['ref_num'] : $result['job_title'];
          $_title = str_replace(array("&amp", "&", "/"), 'and', preg_replace("/(<\/?)(\w+)([^>]*>)/e", "", strip_tags($result['job_title'], '<p><div><span><a><br /><br><ul><li>') ));
          $_processed_title = strtolower(str_replace(array(":", ",", ';'), '', preg_replace('/\s/', '-', $_title)));
        endif;
        return $_processed_title;
    }

    function _useLoc()  {
        $db = & JFactory::getDBO();
        $query = 'SELECT '.$db->nameQuote('use_location').'
            FROM '.$db->nameQuote('#__jobboard_config').'
            WHERE '.$db->nameQuote('id').' = 1';
        $db->setQuery($query);
        return ($db->loadResult() == 1)? true : false;
    }

    function _parseCateg($cat_id)  {
        $db = & JFactory::getDBO();
        $query = 'SELECT '.$db->nameQuote('type').'
            FROM '.$db->nameQuote('#__jobboard_categories').'
            WHERE '.$db->nameQuote('id').' = '.$cat_id;
        $db->setQuery($query);
        $result = $db->loadResult();
        $_processed_catname = empty($result)? null : $result;
        if(!empty($_processed_catname)) :
          $_cat_title = str_replace(array("&amp", "&", "/"), 'and', preg_replace("/(<\/?)(\w+)([^>]*>)/e", "", strip_tags($result, '<p><div><span><a><br /><br><ul><li>') ));
          $_processed_catname = strtolower(str_replace(array(":", ",", ';'), '', preg_replace('/\s/', '-', $_cat_title)));
        endif;
        return $_processed_catname;
    }