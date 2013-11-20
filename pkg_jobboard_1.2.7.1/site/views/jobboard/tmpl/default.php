<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
$app=JFactory::getApplication();
$itemid = JRequest::getInt('Itemid');
$user = & JFactory::getUser();
?>

<!-- Style sheets -->
<?php JHTML::_('stylesheet', 'base.css', 'components/com_jobboard/css/') ?>
<?php JHTML::_('stylesheet', 'list_view_common.css', 'components/com_jobboard/css/') ?>
<?php JHTML::_('stylesheet', 'home_layout.css', 'components/com_jobboard/css/') ?>
<?php if($this->config->jobtype_coloring == 1) :?>
    <?php JHTML::_('stylesheet', 'job_colors.css', 'components/com_jobboard/css/') ?>
<?php endif ?>
<?php JHTML::_('stylesheet', 'login_popup.css', 'components/com_jobboard/css/') ?>
<!-- End Style sheets -->

<?php $sortlink = JRoute::_('index.php?option=com_jobboard&view=list&Itemid='.$itemid); ?>
<?php $document =& JFactory::getDocument(); ?>
<?php $daterange = $this->daterange; ?>
<?php $params =& $app->getParams('com_content'); ?>
<?php $selcat = $this->selcat; ?>
<?php $seldesc = ''; ?>
<!-- feed prefix (SEF dependent) -->
<?php $test_link = JRoute::_('index.php?option=com_jobboard', 1, false); ?>
<?php $test_rsult = strstr($test_link, 'option=com_jobboard') ?>
<?php $feedPrefix = (empty($test_rsult))? '?': '&amp;' ?>
<?php $allow_reg = JobBoardHelper::allowRegistration() ?>
<div id="loginWrapper">
    <?php if($user->get('guest')) : ?>
        <div class="jbdispnone" id="loginPopup">
            <form action="<?php echo JRoute::_('index.php?option=com_jobboard&view=member', true, JobBoardHelper::useSecure()); ?>" id="loginPopupForm" method="post">
                <div class="content">
                    <div class="fields">
                        <div class="field">
                            <label><?php echo JText::_('COM_JOBBOARD_TXTUSERNAME') ?></label>
                            <div class="elements">
                                <input type="text" name="username" />
                            </div>
                        </div>
                        <div class="field">
                            <label><?php echo JText::_('COM_JOBBOARD_TXTPASSWORD') ?></label>
                            <div class="elements">
                                <input type="password" name="password" />
                            </div>
                            <div class="forgotPass">
                                <a href="<?php echo JRoute::_('index.php?option='.$this->user_entry_point.'&view=reset'); ?>" class="forgot"><?php echo JText::_('COM_JOBBOARD_TXTRESETPASS') ?></a>
                                <?php if($allow_reg) : ?>
                                  <a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=member&iview=register'); ?>" id="signup"><?php echo JText::_('COM_JOBBOARD_TXTREGISTER') ?></a>
                                <?php endif ?>
                            </div>
                        </div>
                        <div class="field fLoginOptions clear">
                            <div class="elements">
                                <label><input type="checkbox" name="remember" value="yes" /><span><?php echo JText::_('COM_JOBBOARD_TXTREMEMBER') ?></span></label>
                            </div>
                        </div>
                        <?php if(JobBoardHelper::verifyLogin() || $this->retries <> 0) : ?>
                            <div class="field">
                              <label><?php echo JText::_('COM_JOBBOARD_FORM_CAPTCHA') ?></label>
                              <img class="human_v" src="<?php echo JRoute::_('index.php?option=com_jobboard&view=human&format=raw') ?>" alt="<?php echo JText::_('COM_JOBBOARD_FORM_CAPTCHA') ?>" />
                              <a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=member') ?>" class="clear hv_refresh"><?php echo JText::_('COM_JOBBOARD_FORM_CAPTCHA_REFRESH') ?></a>
                              <label class="left clear"><?php echo JText::_('COM_JOBBOARD_FORM_CAPTCHA_CODE') ?></label>
                      	      <input type="text" name="human_ver" value="" />
                              <?php JHTML::_('script', 'human_ver.js', 'components/com_jobboard/js/') ?>
                           </div>
                       <?php endif ?>
                    </div>
                </div>
                <div class="action">
                    <button type="submit" class="button"><span><?php echo JText::_('COM_JOBBOARD_LOGIN');?></span></button>
                    <a href="#" id="pLoginCancel"><?php echo JText::_('CANCEL');?></a>
                </div>
          	    <input type="hidden" name="return" value="<?php echo base64_encode(JRoute::_('index.php?option=com_jobboard&view=user')); ?>" />
                <input type="hidden" name="option" value="com_jobboard" />
                <input type="hidden" name="view" value="member" />
                <input type="hidden" name="task" value="login" />
                <input type="hidden" name="Itemid" value="<?php echo $itemid ?>" />
                <?php echo JHtml::_('form.token'); ?>
            </form>
        </div>
        <a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=member&Itemid='.$itemid)?>" class="right login"><?php echo JText::_('COM_JOBBOARD_LOGIN_REG') ?></a>
    <?php else : ?>
        <a class="asep right logout" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=member&iview=logout&Itemid='.$itemid)?>"><?php echo JText::_('COM_JOBBOARD_LOGOUT') ?></a>
        <a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&Itemid='.$itemid)?>" class="right"><?php echo JText::_('COM_JOBBOARD_MYACCT') ?></a>
    <?php endif ?>
    <?php if($this->config->allow_unsolicited && $user->get('guest')) : ?>
       <?php $unsolicited_link = 'index.php?option=com_jobboard&view=unsolicited&Itemid='.$itemid; ?>
       <script type="text/javascript">uslnk = <?php echo '"'.JRoute::_($unsolicited_link).'";' ?></script>
       <button class="button" id="topSubmitCV" ><?php echo JText::_('SUBMIT_CV_RESUME');?></button>
    <?php endif; ?>
</div>
<br class="clear" />

<?php $link = 'index.php?option=com_jobboard&view=list&Itemid='.$itemid; ?>
<form id="category_list" name="category_list" method="post" action="<?php echo JRoute::_($link); ?>">
    <?php $all_jobs = 'index.php?option=com_jobboard&view=list&selcat=1&daterange=&jobsearch=&keysrch=&locsrch=&Itemid='.$itemid; ?>
      <div id="srchInputs">
    		<div class="filterset">
                <label for="jobsearch">
                    <small><?php echo JText::_('JOB_TITLE');?>&nbsp;</small>
                </label>
                <br />
                <input class="inputfield " type="text" name="jobsearch" value="<?php echo $this->jobsearch; ?>" id="jobsearch" />
            </div>
    		<div class="filterset">
                <label for="keysrch">
                    <small><?php echo JText::_('SKILLS_KNOWLEDGE_ETC');?>&nbsp;</small>
                </label>
                <br />
                <input class="inputfield " type="text" name="keysrch" value="<?php echo $this->keysrch ?>" id="keysrch" />
            </div>
    		<?php if($this->config->use_location == 1) : ?>
    			<div class="filterset">
                    <label for="locsrch">
                        <small><?php echo JText::_('LOCATION');?>&nbsp;</small>
                    </label>
                    <br />
                    <input class="inputfield " type="text" name="locsrch" value="<?php echo $this->locsrch ?>" id="locsrch" />
                    <span><?php echo JText::_('COM_JOBBORARD_TXTSRCH_RADIUS') ?></span>
                    <select id="srchRadius" class="radius" name="radius">
                          <?php $radius_option_count = count($this->radii) ?>
                          <?php for($r=0; $r<$radius_option_count; $r++)  : ?>
      					    <option <?php if($this->sel_distance == $this->radii[$r]) echo 'selected="selected"' ?> value="<?php echo $this->radii[$r] ?>"><?php echo $this->radii[$r].' '.$this->dist_symbol ?></option>
                          <?php endfor ?>
          			</select>
                </div>
    		<?php endif; ?>
    		<div class="filterset submit">
                <input class="button filterSub" type="submit" id="filtrsubmt" value="<?php echo JText::_('SHOW_JOBS');?>" />
                <span id="loadr" class="hidel"><!--  --></span>
            </div>
            <br class="clear" />
            <div id="jbrdadvsrch" class="jbdispnone">
              <label><?php echo JText::_('COM_JOBBOARD_ADVANCED_FILTERS') ?>
                <br class="clear" />
              </label>
              <select name="selcat" id="fcats" class="inputfield jbrd-fleft">
                  <?php foreach($this->categories as $cat) : ?>
                    <option class="catitem" value="<?php echo $cat->id; ?>" <?php if($cat->id == $this->selcat) {$selcat = $cat->id; $seldesc = $cat->type; echo ' selected="selected"';}?>>
                        <?php echo $cat->type;?>
                    </option>
                  <?php endforeach; ?>
                  <?php if($this->rss_on) : ?>
                    <?php  $feed_title = $seldesc.' '.JText::_('FEED'); ?>
                    <?php  $rss = array('type' => 'application/rss+xml', 'title' => $feed_title.' (RSS)' ); ?>
                    <?php  $atom = array('type' => 'application/atom+xml', 'title' => $feed_title. ' (Atom)' ); ?>
                    <?php $all_cat_feedlink = JRoute::_('index.php?option=com_jobboard&view=rss&selcat=1'); ?>
                    <?php $feedlink = 'index.php?option=com_jobboard&view=rss&selcat='.$selcat.'&Itemid='.$itemid; ?>
                    <!-- add the header links -->
                     <?php $document->addHeadLink(JRoute::_($feedlink.'&type=rss').$feedPrefix.'format=feed', 'alternate', 'rel', $rss); $document->addHeadLink(JRoute::_($feedlink.'&type=atom').$feedPrefix.'format=feed', 'alternate', 'rel', $atom); ?>
                  <?php endif; ?>
                  <?php $document->setTitle($params->get('page_title')); ?>
               </select>
               <label for="daterange" id="drcapt">
                    <?php echo JText::_('JOBS_FROM') ?>
               </label>
               <select id="daterange" name="daterange" class="inputfield jbrd-fleft jbrd-mleft5">
                  <option class="catitem" value="0" <?php if($daterange == 0) echo ' selected="selected"';?>>
                      <?php echo JText::_('ALL_POST_DATES');?>
                  </option>
                  <option class="catitem" value="1" <?php if($daterange == 1) echo ' selected="selected"';?>>
                      <?php echo JText::_('TODAY');?>
                  </option>
                  <option class="catitem" value="2" <?php if($daterange == 2) echo ' selected="selected"';?>>
                      <?php echo JText::_('YESTERDAY');?>
                  </option>
                  <option class="catitem" value="3" <?php if($daterange == 3) echo ' selected="selected"';?>>
                      <?php echo JText::_('LAST_3_DAYS');?>
                  </option>
                  <option class="catitem" value="7" <?php if($daterange == 7) echo ' selected="selected"';?>>
                      <?php echo JText::_('LAST_7_DAYS');?>
                  </option>
                  <option class="catitem" value="14" <?php if($daterange == 14) echo ' selected="selected"';?>>
                      <?php echo JText::_('LAST_14_DAYS');?>
                  </option>
                  <option class="catitem" value="30" <?php if($daterange == 30) echo ' selected="selected"';?>>
                      <?php echo JText::_('LAST_30_DAYS');?>
                  </option>
                  <option class="catitem" value="60" <?php if($daterange == 60) echo ' selected="selected"';?>>
                      <?php echo JText::_('LAST_60_DAYS');?>
                  </option>
               </select>
               <div class="clear">&nbsp;</div>
               <label>
                <strong><?php echo JText::_('COM_JOBBOARD_ENT_REF') ?></strong>&nbsp;#&nbsp;
               </label>
               <input type="text" id="ref_num" value="" name="ref_num" maxlength="150" />
               <div class="clear">&nbsp;</div>
               <div class="clear">
                  <label>
                    <strong><?php echo JText::_('JOB_TYPE') ?></strong>
                  </label>
                  <a id="clearJtypeFilters" <?php if(empty($this->filter_job_type)) echo 'class="hidel"' ?>><?php echo JText::_('COM_JOBBOARD_RESET_FILTERS') ?></a>
                  <div id="jtCboxes" class="checkRow">
                    <?php $num_jobtypes = count($this->jobtypes);  ?>
                    <?php for($jt=0; $jt<$num_jobtypes; $jt++)  : ?>
                       <input type="checkbox" id="job_type<?php echo $jt ?>" name="filter_job_type[]" value="<?php echo $jt ?>" <?php if(in_array($jt, $this->filter_job_type)) echo 'checked="checked"' ?> /><label for="job_type<?php echo $jt ?>"><?php echo JText::_($this->jobtypes[$jt]) ?></label>
                    <?php endfor ?>
                  </div>
               </div>
               <div class="clear">&nbsp;</div>
               <div id="clCboxes" class="clear">
                  <label><strong><?php echo JText::_('CAREER_LEVEL') ?></strong></label><a id="clearClevelFilters" <?php if(empty($this->filter_careerlvl)) echo 'class="hidel"' ?>><?php echo JText::_('COM_JOBBOARD_RESET_FILTERS') ?></a>
                  <div class="checkRow">
                    <div class="jbdcolumn">
                      <?php $num_clevels = count($this->jobcareerlvls); $cl_count = 0; $multicol = $num_clevels > 4? true : false; ?>
                       <?php foreach($this->jobcareerlvls as $clevel)  : ?>
                         <input type="checkbox" id="careerlvl<?php echo $clevel['id'] ?>" name="filter_careerlvl[]" value="<?php echo $clevel['id'] ?>" <?php if(in_array($clevel['id'], $this->filter_careerlvl)) echo 'checked="checked"' ?> /><label for="careerlvl<?php echo $clevel['id'] ?>"><?php echo $clevel['description'] ?></label>
                         <br />
                       <?php if($multicol == true && $cl_count == floor($num_clevels/2)-1 )  :?>
                         </div><div class="jbdcolumn">
                       <?php endif ?>
                       <?php if($cl_count == ($num_clevels-1)) :?>
                         </div>
                       <?php endif ?>
                       <?php $cl_count++ ?>
                    <?php endforeach ?>
                  </div>
               </div>
               <div class="clear">&nbsp;</div>
               <div id="elCboxes" class="clear">
                  <label><strong><?php echo JText::_('EDUCATION') ?></strong></label><a id="clearElevelFilters" <?php if(empty($this->filter_edulevel)) echo 'class="hidel"' ?>><?php echo JText::_('COM_JOBBOARD_RESET_FILTERS') ?></a>
                  <div class="checkRow">
                    <div class="jbdcolumn">
                      <?php $num_elevels = count($this->jobedlvls); $el_count = 0; $multicol = $num_elevels > 4? true : false; ?>
                       <?php foreach($this->jobedlvls as $elevel)  : ?>
                         <input type="checkbox" id="edulevel<?php echo $elevel['id'] ?>" name="filter_edulevel[]" value="<?php echo $elevel['id'] ?>" <?php if(in_array($elevel['id'], $this->filter_edulevel)) echo 'checked="checked"' ?> /><label for="edulevel<?php echo $elevel['id'] ?>"><?php echo $elevel['level'] ?></label>
                         <br />
                       <?php if($multicol == true && $el_count == floor($num_elevels/2)-1 )  :?>
                         </div><div class="jbdcolumn">
                       <?php endif ?>
                       <?php if($el_count == ($num_elevels-1)) :?>
                         </div>
                       <?php endif ?>
                       <?php $el_count++ ?>
                    <?php endforeach ?>
                  </div>
               </div>
               <input class="button filterSub jbrd-fright jbrd-mtop50" id="filtersub_b" type="submit" value="<?php echo JText::_('SHOW_JOBS');?>" />
            </div> <!-- #jbrdadvsrch -->
            <a id="advsrch" href="#" class="jbrd-fright closed" ><?php echo JText::_('COM_JOBBOARD_ADVANCED_SEARCH') ?></a>
      </div><!-- #srchInputs -->
      <?php if ($selcat <> 1 || $daterange <> 0 || $this->country_id <> 0) : ?>
        <div id="keywd_info" class="filterset">
            <?php if($this->country_id == 0) : ?>
              <?php if ($selcat <> 1 && $daterange < 1) echo $seldesc ?>
              <?php if ($selcat <> 1 && $daterange > 0): echo $seldesc; ?> (<?php echo strtolower(JText::sprintf('COM_JOBBOARD_PAST_N_DAYS', $daterange)); ?>)<?php endif; ?>
              <?php if ($selcat == 1 && $daterange > 0) echo JText::sprintf('COM_JOBBOARD_PAST_N_DAYS', $daterange) ?>
            <?php elseif($this->country_id <> 0) : ?>
              <?php $filter_countryname = JobBoardHelper::getCountryName($this->country_id); ?>
              <?php if($this->country_id == 266) $filter_countryname = JText::_($filter_countryname); ?>
              <?php if ($selcat <> 1 && $daterange < 1) : echo $seldesc; ?> (<?php echo $filter_countryname; ?>)<?php endif; ?>
              <?php if ($selcat <> 1 && $daterange > 0) : echo $seldesc; ?> (<?php echo strtolower(JText::sprintf('COM_JOBBOARD_PAST_N_DAYS', $daterange)).' - '.$filter_countryname; ?>)<?php endif; ?>
              <?php if ($selcat == 1 && $daterange > 0) : echo JText::sprintf('COM_JOBBOARD_PAST_N_DAYS', $daterange); ?> (<?php echo $filter_countryname; ?>)<?php endif; ?>
              <?php if ($selcat == 1 && $daterange < 1)  echo ucfirst($filter_countryname); ?>
            <?php endif ?>
            <?php if ($selcat <> 1) : ?>: <a id="jall" href="<?php echo JRoute::_($all_jobs); ?>" class="JobLink"><?php echo JText::_('VIEW_ALL_JOBS'); ?></a><?php endif; ?>
        </div>
      <?php endif; ?>
      <div id="srch_info" class="filterset clear">
          <?php $reset_keywords = 'index.php?option=com_jobboard&view=list&task=reset_keywds&Itemid='.$itemid; ?>
          <?php if (strlen($this->locsrch) > 0 && $this->geo_address <> '') echo JText::_('COM_JOBBOARD_JOBS_NEAR').' '.ucfirst($this->geo_address).' &bull; ';  ?><a id="reset_keywds" href="<?php echo JRoute::_($reset_keywords); ?>" class="JobLink<?php if (strlen($this->jobsearch) == 0 && strlen($this->keysrch) == 0 && strlen($this->locsrch) == 0) echo ' jbhidden' ?>"><?php echo JText::_('COM_JOBBOARD_RESET_KEYWDS'); ?></a><br class="clear" />
          <?php if(!empty($this->filter_job_type) || !empty($this->filter_careerlvl) || !empty($this->filter_edulevel) || $this->country_id > 0 || $this->daterange > 0) : ?>
           <?php echo JText::_('COM_JOBBOARD_ADVANCED_FILTERS_ACTIVE'); ?>&nbsp;&bull;&nbsp;<a id="reset_advfilters" href="#"><?php echo JText::_('COM_JOBBOARD_RESET_ACTIVE_FILTERS'); ?></a>
          <?php endif ?>
      </div>
      <input type="hidden" name="layout" value="<?php echo $this->layout ?>" />
      <input type="hidden" name="switch_layout" value="0" />
      <input type="hidden" id="country_id" name="country_id" value="<?php echo $this->country_id ?>" />
      <?php if($this->config->use_location <> 1) : ?>
         <input class="inputfield" type="hidden" name="locsrch" value="<?php echo $this->locsrch ?>" id="locsrch" />
      <?php endif; ?>
      <input class="inputfield" type="hidden" name="cb_reset" value="0" id="cb_reset" />
      <?php echo JHTML::_('form.token'); ?>
</form>
      <div id="jobtable">
        <?php $tbl_view_link = 'index.php?option=com_jobboard&view=list&Itemid='.$itemid; ?>
        <div class="pagination" >&nbsp;</div>
        <div class="home-panel first pright20">
          <h3><?php echo $this->intro['home_intro_title'] ?></h3>
          <?php echo $this->intro['home_intro'] ?>
        </div>
        <div class="home-panel second">
              <?php $list_link = 'index.php?option=com_jobboard&view=list&limitstart=0&daterange='.$daterange.'&Itemid='.$itemid; ?>
              <button class="button" onclick='window.location.href=<?php echo '"'.JRoute::_($list_link).'";' ?>'><?php echo JText::_('COM_JOBBOARD_ENT_BROWSE_JOBS');?></button>
              <br class="clear" /><br />
              <?php if($user->get('guest')) : ?>
                <?php if($allow_reg) : ?>
                  &rarr;&nbsp;<a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=member&iview=register&Itemid='.$itemid); ?>"><?php echo JText::_('COM_JOBBOARD_TXTREGISTER') ?></a>
                <?php endif ?>
              <?php endif ?>
              <?php $protected_link = 'index.php?option=com_jobboard&view=member&iview=login'; ?>
              <div id="toolpanel">
                  <?php if($user->get('guest')) : ?>
                    <ul>
                       <li class="heading"><?php echo JText::_('COM_JOBBOARD_HOME_TOOLS') ?></li>
                       <li><a href="<?php echo JRoute::_($protected_link.'&redirect='.base64_encode('index.php?option=com_jobboard&view=user&task=invites').'&Itemid='.$itemid) ?>"><?php echo JText::_('COM_JOBBOARD_HOME_VIEW_INVITES') ?></a></li>
                       <li><a href="<?php echo JRoute::_($protected_link.'&redirect='.base64_encode('index.php?option=com_jobboard&view=user&task=addcv').'&Itemid='.$itemid) ?>"><?php echo JText::_('COM_JOBBOARD_ADDCVPROFILE') ?></a></li>
                       <li><a href="<?php echo JRoute::_($protected_link.'&redirect='.base64_encode('index.php?option=com_jobboard&view=user&task=cvprofs').'&Itemid='.$itemid) ?>"><?php echo JText::_('COM_JOBBOARD_HOME_MANAGE_CVS') ?></a></li>
                       <li><a href="<?php echo JRoute::_($protected_link.'&redirect='.base64_encode('index.php?option=com_jobboard&view=user&task=appl').'&Itemid='.$itemid) ?>"><?php echo JText::_('COM_JOBBOARD_HOME_VIEW_APPLS') ?></a></li>
                       <li><a href="<?php echo JRoute::_($protected_link.'&redirect='.base64_encode('index.php?option=com_jobboard&view=user&task=prof').'&Itemid='.$itemid) ?>"><?php echo JText::_('COM_JOBBOARD_HOME_VIEW_PROFILE') ?></a></li>
                    </ul>
                    <ul>
                       <li class="heading"><?php echo JText::_('COM_JOBBOARD_HOME_EMPL_HEADING') ?></li>
                       <li><a href="<?php echo JRoute::_($protected_link.'&redirect='.base64_encode('index.php?option=com_jobboard&view=admin').'&Itemid='.$itemid) ?>">&rarr;&nbsp;<?php echo JText::_('COM_JOBBOARD_HOME_VIEW_LOGIN') ?></a></li>
                       <li><a href="<?php echo JRoute::_($protected_link.'&redirect='.base64_encode('index.php?option=com_jobboard&view=admin&task=cvsrch&f_reset=1').'&Itemid='.$itemid) ?>"><?php echo JText::_('COM_JOBBOARD_HOME_SEARCH_CVS') ?></a></li>
                       <li><a href="<?php echo JRoute::_($protected_link.'&redirect='.base64_encode('index.php?option=com_jobboard&view=admin&task=jobs').'&Itemid='.$itemid) ?>"><?php echo JText::_('COM_JOBBOARD_HOME_MANAGE_JOBS') ?></a></li>
                       <li><a href="<?php echo JRoute::_($protected_link.'&redirect='.base64_encode('index.php?option=com_jobboard&view=admin&task=invites').'&Itemid='.$itemid) ?>"><?php echo JText::_('COM_JOBBOARD_HOME_MANAGE_INVITES') ?></a></li>
                       <li><a href="<?php echo JRoute::_($protected_link.'&redirect='.base64_encode('index.php?option=com_jobboard&view=admin&task=qlist').'&Itemid='.$itemid) ?>"><?php echo JText::_('COM_JOBBOARD_HOME_MANAGE_QNAIRES') ?></a></li>
                    </ul>
                  <?php endif ?>
                  <?php if(!$user->get('guest')) : ?>
                    <ul>
                       <li class="heading"><?php echo JText::_('COM_JOBBOARD_HOME_TOOLS') ?></li>
                       <li><a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=invites&Itemid='.$itemid) ?>"><?php echo JText::_('COM_JOBBOARD_HOME_VIEW_INVITES') ?></a></li>
                       <li><a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=addcv&Itemid='.$itemid) ?>"><?php echo JText::_('COM_JOBBOARD_ADDCVPROFILE') ?></a></li>
                       <li><a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=cvprofs&f_reset=1&Itemid='.$itemid) ?>"><?php echo JText::_('COM_JOBBOARD_HOME_MANAGE_CVS') ?></a></li>
                       <li><a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=appl&Itemid='.$itemid) ?>"><?php echo JText::_('COM_JOBBOARD_HOME_VIEW_APPLS') ?></a></li>
                       <li><a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=prof&Itemid='.$itemid) ?>"><?php echo JText::_('COM_JOBBOARD_HOME_VIEW_PROFILE') ?></a></li>
                    </ul>
                    <ul>
                       <li class="heading"><?php echo JText::_('COM_JOBBOARD_HOME_EMPL_HEADING') ?></li>
                       <li><a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=cvsrch&Itemid='.$itemid) ?>"><?php echo JText::_('COM_JOBBOARD_HOME_SEARCH_CVS') ?></a></li>
                       <li><a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=jobs&Itemid='.$itemid) ?>"><?php echo JText::_('COM_JOBBOARD_HOME_MANAGE_JOBS') ?></a></li>
                       <li><a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=invites&Itemid='.$itemid) ?>"><?php echo JText::_('COM_JOBBOARD_HOME_MANAGE_INVITES') ?></a></li>
                       <li><a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=qlist&Itemid='.$itemid) ?>"><?php echo JText::_('COM_JOBBOARD_HOME_MANAGE_QNAIRES') ?></a></li>
                    </ul>
                  <?php endif ?>
              </div>
        </div>
        <br class="clear" />
        <div class="snapshot">
            <ul id="snapshot-tabs" class="content-box-sub-tab sub-tab-prime">
                <li class="active" id="snapshot-featured">
                    <a href="#">
                      <?php echo JText::_('COM_JOBBOARD_ENT_FEATURED_JOBS'); ?>
                    </a>
                </li>
                <li id="snapshot-latest">
                    <a href="#">
                      <?php echo JText::_('RSS_LATEST_JOBS'); ?>
                   </a>
                </li>
                <li id="snapshot-categories">
                    <a href="#">
                      <?php echo JText::_('COM_JOBBOARD_ENT_CATEGS'); ?>
                   </a>
                </li>
            </ul>
            <div class="subcontent" id="content-featured">
              <table class="data-table listing-container">
                  <colgroup>
                      <col />
                          <col class="col-200" />
                              <col class="col-100" />
                                  <col class="col-150" />
                                      <col />
                                        <col />
                  </colgroup>
                  <thead>
                      <tr>
                          <th><?php echo JText::_('POSTED_ON') ?></th>
                          <th><?php echo JText::_('TITLE'); ?></th>
                          <th><?php echo JText::_('COM_JOBBOARD_ENT_REF'); ?></th>
                          <th><?php echo JText::_('CAREER_LEVEL'); ?></th>
                          <th><?php echo JText::_('LOCATION'); ?></th>
                          <th><?php echo JText::_('JOB_TYPE'); ?></th>
                      </tr>
                  </thead>
                  <tbody>
                    <?php if ($this->featured_count < 1) : ?>
                      <tr>
                        <td colspan="6"><?php echo JText::_('NO_JOBS_LISTED'); ?></td>
                      </tr>
                    <?php else: ?>
                      <?php $rt = 0; $featured_incr = 0;  ?>
                      <?php foreach($this->featured_jobs as $row) : ?>
                          <?php $row_style = ''; ?>
                          <tr>
                            <?php $date = new JDate($row->post_date); ?>

                            <td class="<?php echo $row_style?> fleft">
                               <?php switch($this->config->long_date_format) {
              		   		  	case 0: echo ' '.$date->toFormat("%d %b, %Y");break;
              		   		  	case 1: echo ' '.$date->toFormat("%b %d, %Y");break;
              		   		  	case 2: echo ' '.$date->toFormat("%Y, %b %d");break; ?>
              			     <?php } ?>
                            </td>
                            <?php $job_link = 'index.php?option=com_jobboard&view=job&id='.$row->id.'&Itemid='.$itemid; ?>
                            <td class="<?php echo $row_style?>">
                              <a href="<?php echo JRoute::_($job_link); ?>" class="JobLink">
                                  <?php if(strlen($this->jobsearch) > 0) : ?>
                                      <?php $pattern = $this->jobsearch; $replacement = '<span class="highlight">'.$this->jobsearch.'</span>'; ?>
                                      <?php $job_title_h = str_ireplace ( $pattern, $replacement, $row->job_title); ?>
                                  <?php else : ?>
                                      <?php $job_title_h = $row->job_title; ?>
                                  <?php endif; ?>
                                  <?php $city_h = $row->city; ?>
                                  <?php if(strlen($this->keysrch) > 0) : ?>
                                      <?php $skillsets = explode(',', $this->keysrch); ?>
                                      <?php foreach ($skillsets as $keywd) : ?>
                                        <?php $pattern = $keywd; $replacement = '<span class="highlight">'.$keywd.'</span>'; ?>
                                        <?php $job_title_h = str_ireplace ( $pattern, $replacement, $job_title_h); ?>
                                      <?php endforeach; ?>
                                  <?php endif; ?>
                                  <?php if(strlen($this->locsrch) > 0) : ?>
                                      <?php $pattern = $this->locsrch; $replacement = '<span class="highlight">'.$this->locsrch.'</span>'; ?>
                                      <?php $job_title_h = str_ireplace ( $pattern, $replacement, $job_title_h); ?>
                                      <?php $city_h = str_ireplace ( $pattern, $replacement, $city_h); ?>
                                  <?php endif; ?>
                                  <strong><?php echo $job_title_h; ?></strong>
                              </a>
                              <?php if($this->config->use_location == 1 && isset($row->distance)) : ?>
                                  <?php if($row->distance > 0) : ?>
                                      <?php if($this->config->distance_unit == 0) $alt_distance = ' ('.number_format ($row->distance*0.621371192, 2).' '.JText::_('COM_JOBBOARD_DIST_IMPERIAL').')'; elseif($this->config->distance_unit == 1) $alt_distance = ' ('.number_format ($row->distance*1.609344, 2).' '.JText::_('COM_JOBBOARD_DIST_METRIC').')'; ?>
                                      <span class="">
                                          <br /><strong>&plusmn;&nbsp;<?php echo $row->distance.' '.$this->dist_symbol ?>*</strong><?php echo $alt_distance ?><br /><?php echo JText::sprintf('COM_JOBBOARD_FROM_LOCATION', '<strong>'.ucfirst($this->locsrch).'</strong>'); ?><br />
                                          <small><?php echo '* '.JText::_('COM_JOBBOARD_DIST_ESTIMATION_TYPE'); ?></small>
                                      </span>
                                    <?php endif ?>
                              <?php endif ?>
                            </td>
                            <td class="<?php echo $row_style?>"><?php echo $row->ref_num; ?></td>
                            <td class="<?php echo $row_style?>"><?php echo $row->job_level; ?></td>
                            <td class="<?php echo $row_style?> jobsnippet">
                            <?php if($this->config->use_location == 1) : ?>
                           	 <?php if($row->country <> 266) echo $city_h; else echo JText::_('WORK_ANYWHERE'); ?><br />
                            <?php endif; ?>
              	            <!-- job coloring -->
              					<?php if($this->config->jobtype_coloring == 1) :?>
              						<?php $jt_color = '<span class="jobtype '.JobBoardListHelper::getClass($row->job_type).'">'.JText::_($row->job_type).'</span>';?>
              					<?php else : ?>
              						<?php $jt_color = '<br />';?>
              					<?php endif; ?>
              					<?php echo $jt_color; ?>
              				<!-- end job coloring -->
              				</td>
                            <td class="<?php echo $row_style?> fright"><?php echo $row->category; ?></td>
                          </tr>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </tbody>
              </table>
            </div>
            <div class="subcontent jbdispnone" id="content-latest">
              <table class="data-table listing-container">
                  <colgroup>
                      <col />
                          <col class="col-200" />
                              <col class="col-100" />
                                  <col class="col-150" />
                                      <col />
                                        <col />
                  </colgroup>
                  <thead>
                      <tr>
                          <th><?php echo JText::_('POSTED_ON') ?></th>
                          <th><?php echo JText::_('TITLE'); ?></th>
                          <th><?php echo JText::_('COM_JOBBOARD_ENT_REF'); ?></th>
                          <th><?php echo JText::_('CAREER_LEVEL'); ?></th>
                          <th><?php echo JText::_('LOCATION'); ?></th>
                          <th><?php echo JText::_('JOB_TYPE'); ?></th>
                      </tr>
                  </thead>
                  <tbody>
                    <?php if (count($this->latest_jobs) < 1) : ?>
                      <tr>
                        <td colspan="6"><?php echo JText::_('NO_JOBS_LISTED'); ?></td>
                      </tr>
                    <?php else: ?>
                      <?php $featured_incr = 0;  ?>
                      <?php foreach($this->latest_jobs as $row) : ?>
                          <?php $row_style = ''; ?>            
                          <tr>
                            <?php $date = new JDate($row->post_date); ?>

                            <td class="<?php echo $row_style?> fleft">
                               <?php switch($this->config->long_date_format) {
              		   		  	case 0: echo ' '.$date->toFormat("%d %b, %Y");break;
              		   		  	case 1: echo ' '.$date->toFormat("%b %d, %Y");break;
              		   		  	case 2: echo ' '.$date->toFormat("%Y, %b %d");break; ?>
              			     <?php } ?>
                            </td>
                            <?php $job_link = 'index.php?option=com_jobboard&view=job&id='.$row->id.'&Itemid='.$itemid; ?>
                            <td class="<?php echo $row_style?>">
                              <a href="<?php echo JRoute::_($job_link); ?>" class="JobLink">
                                  <?php if(strlen($this->jobsearch) > 0) : ?>
                                      <?php $pattern = $this->jobsearch; $replacement = '<span class="highlight">'.$this->jobsearch.'</span>'; ?>
                                      <?php $job_title_h = str_ireplace ( $pattern, $replacement, $row->job_title); ?>
                                  <?php else : ?>
                                      <?php $job_title_h = $row->job_title; ?>
                                  <?php endif; ?>
                                  <?php $city_h = $row->city; ?>
                                  <?php if(strlen($this->keysrch) > 0) : ?>
                                      <?php $skillsets = explode(',', $this->keysrch); ?>
                                      <?php foreach ($skillsets as $keywd) : ?>
                                        <?php $pattern = $keywd; $replacement = '<span class="highlight">'.$keywd.'</span>'; ?>
                                        <?php $job_title_h = str_ireplace ( $pattern, $replacement, $job_title_h); ?>
                                      <?php endforeach; ?>
                                  <?php endif; ?>
                                  <?php if(strlen($this->locsrch) > 0) : ?>
                                      <?php $pattern = $this->locsrch; $replacement = '<span class="highlight">'.$this->locsrch.'</span>'; ?>
                                      <?php $job_title_h = str_ireplace ( $pattern, $replacement, $job_title_h); ?>
                                      <?php $city_h = str_ireplace ( $pattern, $replacement, $city_h); ?>
                                  <?php endif; ?>
                                  <strong><?php echo $job_title_h; ?></strong>
                              </a>
                              <?php if($this->config->use_location == 1 && isset($row->distance)) : ?>
                                  <?php if($row->distance > 0) : ?>
                                      <?php if($this->config->distance_unit == 0) $alt_distance = ' ('.number_format ($row->distance*0.621371192, 2).' '.JText::_('COM_JOBBOARD_DIST_IMPERIAL').')'; elseif($this->config->distance_unit == 1) $alt_distance = ' ('.number_format ($row->distance*1.609344, 2).' '.JText::_('COM_JOBBOARD_DIST_METRIC').')'; ?>
                                      <span class="">
                                          <br /><strong>&plusmn;&nbsp;<?php echo $row->distance.' '.$this->dist_symbol ?>*</strong><?php echo $alt_distance ?><br /><?php echo JText::sprintf('COM_JOBBOARD_FROM_LOCATION', '<strong>'.ucfirst($this->locsrch).'</strong>'); ?><br />
                                          <small><?php echo '* '.JText::_('COM_JOBBOARD_DIST_ESTIMATION_TYPE'); ?></small>
                                      </span>
                                    <?php endif ?>
                              <?php endif ?>
                            </td>
                            <td class="<?php echo $row_style?>"><?php echo $row->ref_num; ?></td>
                            <td class="<?php echo $row_style?>"><?php echo $row->job_level; ?></td>
                            <td class="<?php echo $row_style?> jobsnippet">
                            <?php if($this->config->use_location == 1) : ?>
                           	 <?php if($row->country <> 266) echo $city_h; else echo JText::_('WORK_ANYWHERE'); ?><br />
                            <?php endif; ?>
              	            <!-- job coloring -->
              					<?php if($this->config->jobtype_coloring == 1) :?>
              						<?php $jt_color = '<span class="jobtype '.JobBoardListHelper::getClass($row->job_type).'">'.JText::_($row->job_type).'</span>';?>
              					<?php else : ?>
              						<?php $jt_color = '<br />';?>
              					<?php endif; ?>
              					<?php echo $jt_color; ?>
              				<!-- end job coloring -->
              				</td>
                            <td class="<?php echo $row_style?> fright"><?php echo $row->category; ?></td>
                          </tr>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </tbody>
              </table>
            </div>
            <div class="subcontent jbdispnone" id="content-categories">
                <?php $count_categories = count($this->intro_cats) ?>
                <?php if($count_categories > 0) : ?>
                <?php $categ_link = 'index.php?option=com_jobboard&view=list&selcat=' ?>
                    <?php $item_limit = 10 ?>
                    <?php $first_view_items = ($count_categories > $item_limit)? $item_limit : $count_categories; ?>
                        <ul class="categories-itemcol">
                          <?php for($c=0; $c<$first_view_items; $c++) : ?>
                            <?php $link = $categ_link.$this->intro_cats[$c]['id'].'&Itemid='.$itemid; ?>
        				      <li>
                                <a href="<?php echo JRoute::_($link) ?>"><?php echo $this->intro_cats[$c]['name'] ?></a>
                                <span class="jobCount"> (<?php echo $this->intro_cats[$c]['total'] ?>)</span>
                            </li>
                          <?php endfor ?>
                        </ul>
                    <?php if($count_categories > $item_limit) : ?>
                      <?php $col_items = $all_iems = 0; $col_num = 2; ?>
                      <?php for($c=$first_view_items; $c<$count_categories; $c++) : ?>
                          <?php if($col_items == 0) : ?>
                              <ul class="categories-itemcol<?php if($col_num%2 === 1) echo ' clear' ?>">
                          <?php endif ?>
                          <?php $link = $categ_link.$this->intro_cats[$c]['id'].'&Itemid='.$itemid; ?>
      				      <li>
                              <a href="<?php echo JRoute::_($link) ?>"><?php echo $this->intro_cats[$c]['name'] ?></a>
                              <span class="jobCount"> (<?php echo $this->intro_cats[$c]['total'] ?>)</span>
                          </li>
                          <?php $col_items += 1; $all_iems += 1 ?>
                          <?php if($col_items == $first_view_items || ($count_categories - $all_iems <= $first_view_items)) : ?>
                            </ul>
                            <?php $col_num += 1 ?>
                            <?php $col_items = 0; ?>
                          <?php endif ?>
                      <?php endfor ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="clear browseall">
            <a class="right mright20 JobLink" href="<?php echo JRoute::_($list_link); ?>">&rarr;&nbsp;<?php echo JText::_('COM_JOBBOARD_ENT_BROWSE_JOBS');?></a>
        </div>
        <?php if($this->rss_on) : ?>
          <div id="feedarea" class="clear">
             <?php echo '<b>'.JText::_('RSS'). ' </b>' .JText::_('FEED'); ?>: <a class="feedicon" href="<?php echo JRoute::_($feedlink).$feedPrefix.'format=feed' ?>"><?php echo $seldesc; ?></a>
          </div>
        <?php endif; ?>
      </div>
<?php echo $this->setstate; ?>