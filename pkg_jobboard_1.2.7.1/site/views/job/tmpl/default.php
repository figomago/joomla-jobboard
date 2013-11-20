<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
?>
<!-- CSS -->
<?php JHTML::_('stylesheet', 'base.css', 'components/com_jobboard/css/') ?>
<?php JHTML::_('stylesheet', 'job.css', 'components/com_jobboard/css/') ?>
<?php if($this->config->jobtype_coloring == 1) :?>
    <?php JHTML::_('stylesheet', 'job_colors.css', 'components/com_jobboard/css/') ?>
<?php endif ?>
<?php JHTML::_('stylesheet', 'login_popup.css', 'components/com_jobboard/css/') ?>
<!-- End CSS -->

<?php $user = & JFactory::getUser() ?>
<?php if(!$this->published) : ?>
    <div id="jobcont">
       <div>
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
                                            <?php if(JobBoardHelper::allowRegistration()) : ?>
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
                      	    <input type="hidden" name="redirect" value="<?php echo base64_encode(JRoute::_('index.php?option=com_jobboard&view=user')); ?>" />
                            <input type="hidden" name="option" value="com_jobboard" />
                            <input type="hidden" name="view" value="member" />
                      	    <input type="hidden" name="task" value="login" />
                            <input type="hidden" name="Itemid" value="<?php echo $this->itemid ?>" />
                            <?php echo JHtml::_('form.token'); ?>
                        </form>
                    </div>
                    <a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=member&Itemid='.$this->itemid)?>" class="right login"><?php echo JText::_('COM_JOBBOARD_LOGIN_REG') ?></a>
                <?php else : ?>
                    <a class="asep right logout" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=member&iview=logout&Itemid='.$this->itemid)?>"><?php echo JText::_('COM_JOBBOARD_LOGOUT') ?></a>
                    <a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&Itemid='.$this->itemid)?>" class="right"><?php echo JText::_('COM_JOBBOARD_MYACCT') ?></a>
                <?php endif ?>
            </div>
            <h3><?php echo JText::_('COM_JOBBOARD_JOB_DISABLED') ?></h3>
            <p><?php echo JText::_('COM_JOBBOARD_JOB_DISABLED_DESCR') ?></p>
      </div>
    </div>
<?php return; endif; ?>
<?php $this->selcat = (!is_int($this->selcat) || $this->selcat <1)? 1 : $this->selcat; ?>
<?php $applink = 'index.php?option=com_jobboard&view=apply&job_id='.$this->id; ?>
<?php $back = 'index.php?option=com_jobboard&view=list&Itemid='.$this->itemid; ?>
<?php $share = 'index.php?option=com_jobboard&view=share&job_id='.$this->id.'&Itemid='.$this->itemid; ?>

<?php $registry =& JFactory::getConfig(); ?>
<?php $sitename = $registry->getValue( 'config.sitename' ); ?>

<?php $job_opng = JText::_('JOB_OPENING').': '; ?>
<?php $title_prefix = urlencode($job_opng);    ?>
<?php $LinkedIn_long = 'http://www.linkedin.com/shareArticle?mini=true&url='.$this->uri.'&title='.$title_prefix.$this->data->job_title.'&source='.$sitename; ?>
<?php $Twitter_long = 'http://twitter.com/home?status='.$title_prefix.$this->data->job_title.' - '.$this->uri; ?>
<?php $FB_long = 'http://www.facebook.com/sharer.php?u='.$this->uri.'&t='.$title_prefix.$this->data->job_title.'&src='.$sitename; ?>

<?php if(strlen($this->data->description) > 250) : ?>
<?php $article_summary = substr($this->data->description, 0, 250) . '...'; ?>
<?php else : $article_summary = '';  ?>
<?php endif; ?>
<?php $return = JText::_("RETURN_TO_LIST"); $map_coded = false; ?>

<?php if($this->config->jobtype_coloring == 1) :?>
	<?php $jt_color = '<span class="jobtype '.JobBoardJobHelper::getClass($this->data->job_type).'">'.JText::_($this->data->job_type).'</span>';?>
<?php else : ?>
	<?php $jt_color = JText::_($this->data->job_type);?>
<?php endif; ?>
<div id="jobcont">
 <?php if($this->config->show_job_summary == 1) :?>
  <div id="jobsumm">
       <h3><?php echo JText::_('JOB_SUMMARY'); ?></h3>
	   <?php if($this->config->use_location == 1) : ?>
         <div class="jsrow">
           <?php $location_string = ($this->data->country_name <> 'COM_JOBBOARD_DB_ANYWHERE_CNAME')? $this->data->city.', '.$this->data->country_name.', '.$this->data->country_region : JText::_('WORK_FROM_ANYWHERE'); ?>
           <?php if($this->config->enable_post_maps && ($this->data->country <> 266)) : ?>
               <?php $map_coded = (($this->data->geo_latitude <> 0 || !empty($this->data->geo_latitude)) || ($this->data->geo_longitude <> 0 || !empty($this->data->geo_longitude)))? true : false; ?>
               <?php if($map_coded) : ?>
                    <small><a href="#" id="sb_view_map" class="map_trigger right jbrd-mright3"><?php echo JText::_('COM_JOBBOARD_SHOWMAP'); ?></a></small>
               <?php endif ?>
           <?php endif ?>
           <?php echo '<span class="summtitle">'.JText::_('LOCATION').':</span><br />'.$location_string; ?>
         </div>
       <?php endif; ?>
       <div class="jsrow">
          <?php echo '<span class="summtitle">'.JText::_('CAREER_LEVEL').':</span><br />'.$this->data->job_level; ?>
       </div>
       <div class="jsrow">
          <?php echo '<span class="summtitle">'.JText::_('EDUCATION').':</span><br />'.$this->data->education; ?>
       </div>
       <div class="jsrow">
          <?php echo '<span class="summtitle">'.JText::_('JOB_TYPE').':</span><br />'.$jt_color; ?>
       </div>
       <div class="jsrow">
          <?php echo '<span class="summtitle">'.JText::_('POSITIONS').':</span><br />'.$this->data->positions; ?>
       </div>
       <div class="jsrow">
          <?php $this_salary = (strlen($this->data->salary) < 1)? JText::_('NEGOTIABLE') : $this->data->salary; ?>
          <?php echo '<span class="summtitle">'.JText::_('SALARY').':</span><br /><b>'.$this_salary.'</b>'; ?>
       </div>
	   <?php if($this->data->expiry_date <> "0000-00-00 00:00:00"):?>
	       <div class="jsrow">
	       	  <?php $exp_date = new JDate($this->data->expiry_date); ?>
			  <?php echo '<span class="summtitle">'.JText::_('APPLY_BEFORE').':</span><br /><b>'; ?>
				<?php switch($this->config->long_date_format) {
					case 0: echo $exp_date->toFormat("%d %b, %Y").'</b>';break;
					case 1: echo $exp_date->toFormat("%b %d, %Y").'</b>';break;
					case 2: echo $exp_date->toFormat("%Y, %b %d").'</b>';break; ?>					          	
				<?php } ?> 
	       </div>
	   <?php endif; ?>
       <div id="sb_lastrow">
        <?php if($this->config->send_tofriend == 1) :?>
          <a href="<?php echo JRoute::_($share); ?>">
            <button class="button applbut"><?php echo JText::_('EMAIL_TO_A_FRIEND'); ?></button>
         </a>
        <?php endif; ?>
         <br />
          <small><a href="<?php echo JRoute::_($back) ?>"><b>&#171;&nbsp;</b><?php echo $return; ?></a></small>
       </div>
  </div>
  <?php endif; ?>
  <div <?php if($this->config->show_job_summary == 1) echo 'id="jobdet"'; ?>>
  <div id="loginWrapper">
        <?php if($user->get('guest')) : ?>
            <div class="jbdispnone" id="loginPopup">
                <form action="<?php echo JRoute::_('index.php?option=com_jobboard&view=member'); ?>" id="loginPopupForm" method="post">
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
                                    <a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=member&iview=register'); ?>" id="signup"><?php echo JText::_('COM_JOBBOARD_TXTREGISTER') ?></a>
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
                    <input type="hidden" name="option" value="com_jobboard" />
                    <input type="hidden" name="view" value="member" />
              	  <input type="hidden" name="task" value="login" />
                    <?php echo JHtml::_('form.token'); ?>
                </form>
            </div>
            <a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=member&Itemid='.$this->itemid)?>" class="right login"><?php echo JText::_('COM_JOBBOARD_LOGIN_REG') ?></a>
        <?php else : ?>
            <a class="asep right logout" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=member&iview=logout&Itemid='.$this->itemid)?>"><?php echo JText::_('COM_JOBBOARD_LOGOUT') ?></a>
            <a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&Itemid='.$this->itemid)?>" class="right"><?php echo JText::_('COM_JOBBOARD_MYACCT') ?></a>
        <?php endif ?>
    </div>
    <h3><?php echo $this->data->job_title; ?></h3>
    <div id="jobcontent">
    <?php if($this->config->show_viewcount == 1 || $this->config->show_applcount == 1) :?>
      <div id="hitsumm">
        <small>
        	<?php if($this->config->show_applcount == 1) :?>
	            <?php if($this->data->num_applications == 1) : ?>
	              <?php echo '<b>*</b> '.JText::_('THERE_HAS_BEEN'). ' <span class="hit">'. $this->data->num_applications . '</span>  '. JText::_('APPLICATION_FOR_THIS_POSITION'); ?>
	            <?php else : ?>
	              <?php echo '<b>*</b> '.JText::_('THERE_HAVE_BEEN'). ' <span class="hit">'. $this->data->num_applications . '</span>  '. JText::_('APPLICATIONS_FOR_THIS_POSITION'); ?>
	            <?php endif; ?>
            	<br />
            <?php endif; ?>
            <?php if($this->config->show_viewcount == 1) :?>
            	<?php echo '<b>*</b> '.JText::_('THIS_JOB_OPENING_HAS_BEEN_VIEWED'). ' <span class="hit">'. $this->data->hits . '</span>  '. JText::_('TIMES'); ?>
            <?php endif; ?>
        </small>
        <small id="hsback"><a href="<?php echo JRoute::_($back) ?>"><b>&#171;&nbsp;</b><?php echo $return; ?></a></small>
      </div>
    <?php endif; ?>
    <?php if(!$user->get('guest')) : ?>
        <?php if($this->data->posted_by == $user->id) : ?>
           <div id="admin_btns">
             <a title="<?php echo JText::sprintf('COM_JOBBOARD_ENT_TOGGLE', JText::sprintf('COM_JOBBOARD_ENT_STATUS', '')) ?> "class="yesno yes right" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=jobstatus&jid='.$this->id.'&status=1&'.JUtility::getToken().'=1')?>" >&nbsp;</a>
              <form  id="frmEd_0" method="post" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=edjob&jid='.$this->id.'&Itemid='.$this->itemid)?>" >
                    <input class="button right jbrd-mleft5" type="submit" value="<?php echo JText::_('COM_JOBBOARD_EDIT') ?>" />
                    <input type="hidden" name="option" value="com_jobboard" />
                    <input type="hidden" name="view" value="admin" />
                    <input type="hidden" name="task" value="edjob" />
                    <input type="hidden" name="jid" value="<?php echo $this->id ?>" />
                    <input type="hidden" name="Itemid" value="<?php echo $this->itemid ?>" />
                    <?php echo JHTML::_('form.token'); ?>
               </form>
               <form  id="frmDupl_0" method="post" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=clonejob&jid='.$this->id.'&Itemid='.$this->itemid)?>" >
                   <input class="button right jbrd-mleft5" type="submit" value="<?php echo JText::_('COM_JOBBOARD_CLONE') ?>" />
                   <input type="hidden" name="option" value="com_jobboard" />
                    <input type="hidden" name="view" value="admin" />
                    <input type="hidden" name="task" value="clonejob" />
                    <input type="hidden" name="jid" value="<?php echo $this->id ?>" />
                    <input type="hidden" name="Itemid" value="<?php echo $this->itemid ?>" />
                    <?php echo JHTML::_('form.token'); ?>
               </form>
               <form  id="frmDel_0" method="post" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=deljob&jid='.$this->id.'&Itemid='.$this->itemid)?>" >
                   <input class="button right" type="submit" value="<?php echo JText::_('COM_JOBBOARD_DELETE') ?>" />
                   <input type="hidden" name="option" value="com_jobboard" />
                    <input type="hidden" name="view" value="admin" />
                    <input type="hidden" name="task" value="deljob" />
                    <input type="hidden" name="jid" value="<?php echo $this->id ?>" />
                    <input type="hidden" name="Itemid" value="<?php echo $this->itemid ?>" />
                    <?php echo JHTML::_('form.token'); ?>
               </form>
           </div>
        <?php endif ?>
    <?php endif ?>
      <div id="bookmrk">
        <?php if($this->bid == 0)  : ?>
          <?php $bookmrk_params = 'index.php?option=com_jobboard&view=user&task=addfav&job_id='.$this->id ?>
          <?php $bookmrk_b64 = base64_encode($bookmrk_params) ?>
          <?php $bookmrk_target = $bookmrk_params.'&'.JUtility::getToken().'=1&redirect='.$bookmrk_b64.'&Itemid='.$this->itemid ?>
          <a class="bkmrk" href="<?php echo JRoute::_($bookmrk_target) ?>">&nbsp;</a>
          <small class="startext"><?php echo JText::_('COM_JOBBOARD_JOBMARKL') ?></small>
        <?php else : ?>
          <a class="bkmrk active" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=jdelfav&bid='.$this->bid.'&job_id='.$this->id.'&'.JUtility::getToken().'=1.&Itemid='.$this->itemid) ?>">&nbsp;</a>
          <small class="startext"><?php echo JText::_('COM_JOBBOARD_JOBUNMARK') ?></small>
        <?php endif ?>
      <?php echo '<b>'.JText::_('ABOUT_THIS_JOB').'</b>'; ?>
      </div>
      <?php echo $this->data->description; ?> <br />
      <?php if(($job_duties = $this->data->duties) <> '' ) : ?>
        <?php echo '<br /><b>'.JText::_('THIS_JOB_DUTIES').'</b>'; ?> <br />
        <?php echo $job_duties; ?> <br />
      <?php endif; ?>
      <?php if(strlen($this->data->job_tags) > 0) : ?>
         <?php $job_keywords = explode(',', $this->data->job_tags); ?>
         <?php $jtag_count = count($job_keywords); ?>
         <?php $jtag_iter = 1; ?>
         <span class="jtlabel"><?php echo '<small>'.JText::_('THIS_JOB_KEYWDS').':&nbsp;</small>';?></span>
         <span class='jtspan'>
         <?php foreach ($job_keywords as $keywd) : ?>
         	<?php $jtag_link = 'index.php?option=com_jobboard&view=taglist&keysrch='.trim($keywd).'&Itemid='.$this->itemid; ?>
            <small><a href="<?php echo JRoute::_($jtag_link) ?>"><?php echo $keywd ?></a></small>
            <?php if($jtag_iter < $jtag_count) echo ', ';?>
         	<?php $jtag_iter += 1; ?>
         <?php endforeach; ?>
         </span>
      <?php endif; ?>
       <?php if(!$this->is_modal) : ?>
         <?php if($this->config->enable_post_maps && ($this->config->use_location == 1 && $this->data->country <> 266 && $this->data->geo_latitude <> '')) : ?>
           <?php if(empty($this->rformat)) : ?>
             <?php $lang = & JFactory::getLanguage()->getTag();$lang = explode('-', $lang); ?>
             <?php if($map_coded) : ?>
               <br />
               <a href="#" id="view_map" class="map_trigger right"><?php echo JText::_('COM_JOBBOARD_SHOWMAP'); ?></a><br class="clear"/>
               <a href="#" id="map_cls" class="right jbhidden"><?php echo JText::_('COM_JOBBOARD_HIDEMAP'); ?></a><br class="clear"/>
               <script type="text/javascript" src="http://maps.google.com/maps/api/js?libraries=geometry&amp;sensor=false&amp;lang=<?php echo $lang[0] ?>&amp;callback=loadMap"></script>
               <?php JHTML::_('script', 'job_geo.js', 'components/com_jobboard/js/') ?>
               <div id="mapBorder" class="jbdispnone">
                  <div id="jobpostMap"><!--  --></div>
               </div>
               <script type="text/javascript">
                    var Tandolin = Tandolin || {};
                    Tandolin.jobFe = Tandolin.jobFe || {};
                    function loadMap(){
                      Tandolin.jobFe.Job = new TandolinPublcJob({
                        jobData : {
                           'lat' : <?php echo $this->data->geo_latitude.',' ?>
                           'lng' : <?php echo $this->data->geo_longitude.',' ?>
                           'title' : <?php echo '"'.$this->data->job_title.'"' ?>
                        }
                      });
                    };
            	</script>
             <?php endif ?>
           <?php endif ?>
         <?php endif ?>
       <?php endif ?>
      <div id="divbottom">
      	<?php if($this->config->allow_applications == 1) :?>
               <?php if(!$user->get('guest') && $this->prev_applied > 0) : ?>
                 <span>
                    <?php echo JText::_('COM_JOBBOARD_ALREADYAPPLIED').'&nbsp;'; ?>
                 </span>
               <?php elseif($this->data->posted_by != $user->id || $user->get('guest')): ?>
                 <?php $apply_params = 'index.php?option=com_jobboard&view=user&task=apply&jid='.$this->id.'&cat_id='.$this->selcat.'&qid='.$this->data->questionnaire_id; ?>
                 <?php $apply_b64 = base64_encode($apply_params) ?>
                 <?php $apply_link = $apply_params.'&redirect='.$apply_b64.'&'.JUtility::getToken().'=1&Itemid='.$this->itemid ?>
                 <button class="button applbut jbsendbutton" onclick='window.location.href=<?php echo '"'.JRoute::_($apply_link).'";' ?>'><?php echo JText::_('APPLY_NOW'); ?></button>
               <?php endif ?>
               <?php if(!$user->get('guest')) : ?>
                 <a class="jbrd-mleft5" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=appl&Itemid='.$this->itemid) ?>">
                    <?php echo JText::_('COM_JOBBOARD_MYJOBAPPLICATIONS'); ?>
                 </a>
               <?php endif; ?>
                <?php if($this->config->allow_once_off_applications == 1 && $user->get('guest')) : ?>
                  <button class="button applbut right small" onclick='window.location.href=<?php echo '"'.JRoute::_($applink).'";' ?>'><?php echo JText::_('COM_JOBBOARD_APPLY_AS_GUEST'); ?></button>
                <?php endif ?>
         <?php endif; ?>
         <?php if($this->config->show_job_summary == 0 && $this->config->send_tofriend == 1) : ?>
         	<a href="<?php echo JRoute::_($share); ?>"><small><?php echo JText::_('EMAIL_TO_A_FRIEND'); ?></small></a>&nbsp;&nbsp;
         <?php endif; ?>
         <div class="lsretrn">
         	<small><a href="<?php echo JRoute::_($back) ?>"><b>&#171;&nbsp;</b><?php echo $return; ?></a></small>
         </div>
         <?php if($this->config->show_social == 1) :?>
            <?php if($this->config->social_icon_style == 1) :?>
            <div id="fb-root"></div>
            <script type="text/javascript">(function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
                fjs.parentNode.insertBefore(js, fjs);
              }(document, 'script', 'facebook-jssdk'));
            </script>
            <div class="socialrow">
                <div class="sharertwitter">
                  <a href="https://twitter.com/share" class="twitter-share-button" data-lang="en">Tweet</a>
                  <script type="text/javascript">!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                </div>
                <div class="sharelinkedin">
                   <script src="http://platform.linkedin.com/in.js" type="text/javascript"></script>
                   <script type="IN/Share" data-url="<?php echo $this->uri ?>" data-counter="right"></script>
                </div>
                <div class="sharergplus">
                   <script type="text/javascript">
                      (function() {
                        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
                        po.src = 'https://apis.google.com/js/plusone.js';
                        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
                      })();
                   </script>
                   <div class="g-plusone" data-size="medium" data-annotation="none"></div>
                </div>
                <div class="fb-like" data-href="<?php echo $this->uri ?>" data-send="true" data-layout="button_count" data-width="450" data-show-faces="false">
                </div>
               <!-- <a href="<?php echo JRoute::_($share); ?>" class="emailfriend" title="<?php echo JText::_('EMAIL_TO_A_FRIEND'); ?>"><?php echo JText::_('COM_JOBBOARD_EMAIL'); ?>
                </a>-->
            </div>
            <?php endif; ?>
            <!-- old share buttons -->
            <?php if($this->config->social_icon_style == 0) :?>
	            <a target="_blank" href="<?php echo $LinkedIn_long; ?>" title="<?php echo JText::_('LINKEDIN_SHARE') ?>"><div id="linkedin">&nbsp;</div></a> 
	            <a target="_blank" href="<?php echo $Twitter_long; ?>" title="<?php echo JText::_('TWITTER_SHARE') ?>"><div id="twitter">&nbsp;</div></a> 
	            <a target="_blank" href="<?php echo $FB_long; ?>" title="<?php echo JText::_('FACEBOOK_SHARE') ?>"><div id="facebook">&nbsp;</div></a>
	        <?php endif; ?>
         <?php endif; ?>
      </div>
    </div>
  </div>
</div>
 <?php echo $this->setstate; ?>