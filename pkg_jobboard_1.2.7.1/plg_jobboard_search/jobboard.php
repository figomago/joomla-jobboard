<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
require_once JPATH_SITE.'/components/com_jobboard/router.php';

class plgSearchJobboard extends JPlugin
{

	function onContentSearchAreas(){
		return $this->onSearchAreas();
	}

	function onContentSearch($text, $phrase = '', $ordering = '', $areas = null){
		return $this->onSearch($text, $phrase = '', $ordering = '', $areas = null);
	}

    /**
     * @return assoc
     */
    function onSearchAreas()
    {
        JPlugin::loadLanguage('plg_jobboard_search', JPATH_ADMINISTRATOR);
        static $areas = array('jobboard' => "PLG_SEARCH_JOBBOARD");
        return $areas;
    }

    /**
     * Performs the search
     *
     * @param string $keyword
     * @param string $match
     * @param unknown_type $ordering
     * @param unknown_type $areas
     * @return unknown_type
     */
    function onSearch( $keyword, $match='', $ordering='', $areas=null )
    {
        JPlugin::loadLanguage('plg_jobboard_search', JPATH_ADMINISTRATOR);
        if ( is_array( $areas ) )
        {
            if ( !array_intersect( $areas, array_keys( $this->onSearch() ) ) )
            {
                return array();
            }
        }

        $keyword = JString::strtolower(trim( $keyword ));
        if (empty($keyword))
        {
            return array();
        }

        $app = &JFactory::getApplication();

        $selcat = $app->getUserState('com_jobboard.list.selcat', 1, 'int');
        $country =  $app->getUserState('com_jobboard.list.country_id', 0, 'int');
		$daterange =  $app->getUserState("com_jobboard.daterange",  0, 'int');
        $city =  $app->getUserState("com_jobboard.locsrch", '', 'string');

        $ordering = !empty($ordering)? $ordering : JRequest::getString('ordering');
        $match = !empty($match)? $match : JRequest::getString('searchphrase');
        $areas = !empty($areas)? $areas : JRequest::getVar('areas', array(), 'array');

		$app->setUserState('com_jobboard.list.selcat', 1, 'int');
		$app->setUserState('com_jobboard.list.country_id', 0, 'int');
		$app->setUserState("com_jobboard.daterange",  0, 'int');
        $app->setUserState("com_jobboard.locsrch", '', 'string');

        $match = strtolower($match);
        switch ($match)
        {
            case 'exact':
              $app->setUserState("com_jobboard.jobsearch", $keyword);
              $app->setUserState("com_jobboard.keysrch", '');
			break;
            case 'all':
              $app->setUserState("com_jobboard.jobsearch", $keyword);
              $app->setUserState("com_jobboard.keysrch", '');
			break;
            case 'any':
              $words = JString::trim(implode(',', explode( ' ', $keyword )) );
              $app->setUserState("com_jobboard.jobsearch", '');
              $app->setUserState("com_jobboard.keysrch", $words);
			break;
            default:
              $app->setUserState("com_jobboard.jobsearch", $keyword);
              $app->setUserState("com_jobboard.keysrch", '');
			;break;
        }

        $order = $app->getUserState('com_jobboard.list.order', 'date');
		$sort = $app->getUserState('com_jobboard.list.sort', 'd');

        // order the items according to the ordering selected in com_search  
        switch ( $ordering )
        {
            case 'newest':
                $app->setUserState('com_jobboard.list.order', 'date');
		        $app->setUserState('com_jobboard.list.sort', 'd');
            break;
            case 'oldest':
                $app->setUserState('com_jobboard.list.order', 'date');
		        $app->setUserState('com_jobboard.list.sort', 'a');
            break;
            case 'alpha':
                $app->setUserState('com_jobboard.list.order', 'title');
		        $app->setUserState('com_jobboard.list.sort', 'a');
            break;
    		case 'category':
                $app->setUserState('com_jobboard.list.order', 'type');
		        $app->setUserState('com_jobboard.list.sort', 'a');
			break;
            case 'popular':
            default:
                $app->setUserState('com_jobboard.list.order', 'date');
		        $app->setUserState('com_jobboard.list.sort', 'd');
                break;
        }

        JModel::addIncludePath( JPATH_SITE.DS.'components'.DS.'com_jobboard'.DS.'models' );

        $model = &JModel::getInstance( 'List', 'JobboardModel' );

		$model->setState('layout', 'list');
		$model->setState('filter_job_type', array());
		$model->setState('filter_career_level', array());
		$model->setState('filter_education', array());

        $items = $model->getData();
        if (empty($items)) { return array(); }

        // format the items array according to what com_search expects
        foreach ($items as $key => $item)
        {
            $item->href         = JRoute::_("index.php?option=com_jobboard&view=job&id=".$item->id.'&Itemid='.JRequest::getString('Itemid'));
            $item->title        = JText::_( $item->job_title );
            $item->created      = $item->post_date;
            $item->section      = JText::_("PLG_SEARCH_JOBBOARD").' | '.JText::_('PLG_SEARCH_JOBBOARD_CATEG').': '.$item->category;
            $item->text         = substr( preg_replace("/(<\/?)(\w+)([^>]*>)/e", "", strip_tags($item->description, '<p><div><span><a><br /><br><ul><li>')), 0, 250);
            $item->browsernav   = "0";
        }

        $app->setUserState("com_jobboard.jobsearch", '', 'string');
        $app->setUserState("com_jobboard.keysrch", '', 'string');
        $app->setUserState('com_jobboard.list.selcat', $selcat, 'int');
        $app->setUserState('com_jobboard.list.country_id', $country, 'int');
		$app->setUserState("com_jobboard.daterange",  $daterange, 'int');
        $app->setUserState("com_jobboard.locsrch", $city, 'string');
        $app->setUserState('com_jobboard.list.order', $order);
		$app->setUserState('com_jobboard.list.sort', $sort);

        return $items;
    }
}
?>