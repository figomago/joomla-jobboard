<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables');
jimport('joomla.application.component.controller');

class JobboardControllerJobboard extends JController
{
     /**
	 * constructor
	 */
	function __construct()
	{
		parent::__construct();

		$this->registerTask('getfile', 'downloadFile');
		$this->registerTask('user', 'authenticateUser');
		$this->registerTask('json', 'getJson');
    }

    function display() {
       $this->_showList();
    }

	private function _showList($selcat=1, $reset_keywds=false)
	{
        $app = JFactory::getApplication();

    	$search = JString::trim($app->getUserStateFromRequest("com_jobboard.jobsearch", 'jobsearch', '', 'string') );
    	$search = (strpos($search, '(') === 0)? '' : JString::strtolower($search);
    	$keysrch = JString::trim($app->getUserStateFromRequest("com_jobboard.keysrch", 'keysrch', '', 'string') );
    	$keysrch = (strpos($keysrch, '(') === 0)? '' : JString::strtolower($keysrch);
    	$locsrch = JString::trim($app->getUserStateFromRequest("com_jobboard.locsrch", 'locsrch', '', 'string'));
    	$locsrch = (strpos($locsrch, '(') === 0)? '' : JString::strtolower($locsrch);

        if(strlen($search > 0) || strlen($keysrch > 0) || strlen($locsrch > 0)) JRequest::checkToken() or jexit('Invalid Token');

        $app->setUserState("com_jobboard.filter_job_type", array(), 'array');
        $app->setUserState("com_jobboard.filter_careerlvl", array(), 'array');
        $app->setUserState("com_jobboard.filter_edulevel", array(), 'array');

        $ref_num = JString::trim(JRequest::getString('ref_num'));
        JRequest::setVar('ref_num', $ref_num);
        JRequest::setVar('limitstart', 0);

        $config_model =& $this->getModel('Config');
        $default_daterange = $config_model->getDateRangeCfg();
        $daterange = $app->getUserStateFromRequest("com_jobboard.daterange", 'daterange', $default_daterange, 'int');
		$country_id = $app->getUserStateFromRequest('com_jobboard.list.country_id', 'country_id', 0, 'int');
        $sort = JString::trim($app->getUserStateFromRequest('com_jobboard.list.sort','sort',''));
        $order = JString::trim($app->getUserStateFromRequest('com_jobboard.list.order', 'order', '', 'string'));

        $layout = JRequest::getString('layout', '');
        if(empty($layout)) {
          $layout_instate = $app->getUserState('com_jobboard.list.layout');
          if(empty($layout_instate)) {
              $layout = ($config_model->getListcfg() == 0)? 'list' : 'table';
          } else {
               $layout = $layout_instate;
            }
        }

        $app->setUserState('com_jobboard.list.layout', $layout, 'string');

    	$selcat = $app->getUserStateFromRequest('com_jobboard.list.selcat', 'selcat', 1);
        $selcat = $selcat == 0? 1 : $selcat;
        $selcat = $app->setUserState('com_jobboard.list.selcat', $selcat, 'int');

        $search_cfg = $config_model->getLocCfg();

        $app->setUserState("com_jobboard.sel_distance", null, 'int');

        $cat_model =& $this->getModel('List');
        $board_model =& $this->getModel('Jobboard');
        $intro_vars =  $board_model->getIntroConfig();

		$view  =& $this->getView('jobboard', 'html');

        $view->assign('selcat', $selcat);
        $view->assign('keysrch', $keysrch);
        $view->setModel($cat_model, true);
        $view->setModel($config_model);
        $view->setModel($board_model);
        /*$view->setLayout($layout);  */
        $view->assign('layout', $layout);
        $view->assignRef('intro', $intro_vars);
        $view->assign('country_id', $country_id);
        $view->assign('daterange', $daterange);
        $view->assign('jobsearch', $search);
        $view->assign('locsrch', $locsrch);
        $view->assign('layout', $layout);
        $view->assign('ref_string', $ref_num);

	    $view->display();
	}

    function makeToken($fid){

       $token = sprintf(
          '%02x%04x%03x%05x', mt_rand(), mt_rand(0, 90), bindec(substr_replace(sprintf('%016b', mt_rand(0, 90)), '0100', 11, 4)), mt_rand()
		);
	  $user_model =& $this->getModel('User');

    }

    function downloadFile() {

       $file_id = JRequest::getInt('fid');
       $token = JRequest::getString('tkn');

	   $user_model =& $this->getModel('User');

       /* check token Validity */
       if(!$token_info = $user_model->getTokenInfo($token)) {
         jexit( JText::_('COM_JOBBOARD_DL_INVALID'));
       }
       // var_dump($token_info);die;
       if(isset($token_info)) {
         if(!is_array($token_info))
         {
            jexit( JText::_('COM_JOBBOARD_DL_INVALID'));

         } elseif($token_info['hits'] > $token_info['max_use'])
         {
            jexit( JText::_('COM_JOBBOARD_DL_INVALID'));

         } elseif($token_info['file_id'] <> $file_id)
         {
            jexit( JText::_('COM_JOBBOARD_FILE_NOTFOUND'));

         } else {
             if($token_info['expires'] <> '0000-00-00') { //skip expiration date check if not set
                  jimport('joomla.utilities.date');
                  $today_do = new JDate();
                  $today = strtotime($today_do->toFormat('%Y-%m-%d'));
                  $expiration_date = strtotime($token_info['expires']);

                  if ($expiration_date < $today) {
                       jexit( JText::_('COM_JOBBOARD_DL_INVALID'));
                  }
              }
         }

         //Phew! no errors from above. Let's get our file!

          $file = $user_model->getCvFileByToken($file_id, $token);
          $filename = $file->filepath.DS.$file->filename;


          if(!JFile::exists($filename))
          {
                jexit( JText::_('COM_JOBBOARD_FILE_NOTFOUND'));

          }

          $tokens =& JTable::getInstance('Token', 'Table');
		  $tokens->hit(intval($token_info['id']));

          $view  =& $this->getView('user', 'file');
          $view->assign('file', $filename);
          $view->assign('filetype', $file->filetype);

  	      $view->display();
       }

    }

    function authenticateUser(){

          $view  =& $this->getView('member', 'html');
          //$view->assign('file', $filename);
          //$view->assign('filetype', $file->filetype);

  	      $view->display();
    }

    function getJson(){
        JRequest::checkToken() or jexit(JText::_('Invalid Token'));
        $this->_getExtList();
    }

    private function _getExtList()
    {
	     $app = JFactory::getApplication();
         $post = JRequest::get('POST') ;

         $app->setUserState('com_jobboard.extlist.limitstart', $post['limitstart'], 'int');
         $app->setUserState('com_jobboard.extlist.limit', $post['limit'], 'int');
         $results = array();
         $model = & $this->getModel('Extlist');
         $results['data'] = $model->getData();
         $results['pagination'] = $model->getPaginationVars();
         $this->_returnJSON($results);
    }

    private function _returnJSON(&$results) {

       $view  =& $this->getView('extlist', 'json');
       $view->assignRef('data', $results);
      /* $document =& JFactory::getDocument();
       $doc = & JDocument::getInstance('json');
       $view->assignRef('doc', $doc);*/

       $view->display();
    }

}
$controller = new JobboardControllerJobboard();
$controller->execute($task);
$controller->redirect();

