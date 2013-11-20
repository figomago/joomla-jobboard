<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
?>
<?php $user = & JFactory::getUser() ?>

<!-- CSS -->
<?php JHTML::_('stylesheet', 'base.css', 'components/com_jobboard/css/') ?>
<?php JHTML::_('stylesheet', 'apply.css', 'components/com_jobboard/css/') ?>
<?php JHTML::_('stylesheet', 'login_popup.css', 'components/com_jobboard/css/') ?>
<!-- End CSS -->

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
<?php else : ?>
  <?php if($this->config->allow_applications == 0) return JText::_('APPL_NOT_ALLOWED'); ?>
  <?php if(!$user->get('guest')) : ?>
      <?php $app = &JFactory::getApplication() ?>
      <?php $app->redirect('index.php?option=com_jobboard&view=job&id='.$this->job_id.'&Itemid='.$this->itemid, JText::_('COM_JOBBOARD_APPLY_ASREG_PROHIBITED'))  ?>
  <?php endif ?>
  <?php if($this->config->jobtype_coloring == 1) :?>
      <?php JHTML::_('stylesheet', 'job_colors.css', 'components/com_jobboard/css/') ?>
  <?php endif ?>
  <!-- job coloring -->
  <?php if($this->config->jobtype_coloring == 1) :?>
  	<?php $jt_color = '<span class="jobtype '.JobBoardJobHelper::getClass($this->data->job_type).'">'.JText::_($this->data->job_type).'</span>';?>
  <?php else : ?>
  	<?php $jt_color = JText::_($this->data->job_type);?>
  <?php endif; ?>
  <!-- end job coloring -->

  <?php if($this->errors > 0) : ?>
     <?php $app= JFactory::getApplication(); ?>
     <?php $afields = $app->getUserState('com_jobboard.afields');   ?>
  <?php endif; ?>
  <?php $req_marker = '*'; ?>
  <?php $verify_login = JobBoardHelper::verifyLogin();  ?>
  <?php $verify_humans = JobBoardHelper::verifyHumans();  ?>
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
                            <?php if($verify_login || $this->retries <> 0) : ?>
                              <div class="field">
                                <label><?php echo JText::_('COM_JOBBOARD_FORM_CAPTCHA') ?></label>
                                <img class="human_v" src="<?php echo JRoute::_('index.php?option=com_jobboard&view=human&format=raw') ?>" alt="<?php echo JText::_('COM_JOBBOARD_FORM_CAPTCHA') ?>" />
                                <a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=member') ?>" class="clear hv_refresh"><?php echo JText::_('COM_JOBBOARD_FORM_CAPTCHA_REFRESH') ?></a>
                                <label class="left clear"><?php echo JText::_('COM_JOBBOARD_FORM_CAPTCHA_CODE') ?></label>
                      	      <input type="text" name="human_ver" value="" />
                                <?php if(!$verify_humans) : ?>
                                   <?php JHTML::_('script', 'human_ver.js', 'components/com_jobboard/js/') ?>
                                <?php endif ?>
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
                    <input type="hidden" name="Itemid" value="<?php echo $this->itemid ?>" />
                    <?php echo JHtml::_('form.token'); ?>
                </form>
            </div>
            <a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=member&Itemid='.$this->itemid)?>" class="right login"><?php echo JText::_('COM_JOBBOARD_LOGIN_REG') ?></a>
        <?php else : ?>
            <a class="asep right logout" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=member&iview=&task=logout&Itemid='.$this->itemid)?>"><?php echo JText::_('COM_JOBBOARD_LOGOUT') ?></a>
            <a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&Itemid='.$this->itemid)?>" class="right"><?php echo JText::_('COM_JOBBOARD_MYACCT') ?></a>
        <?php endif ?>
     </div>
     <?php $path = 'index.php?option=com_jobboard&view=upload'; ?>
     <form method="post" action="<?php echo JRoute::_($path); ?>" id="applFRM" name="applFRM" enctype="multipart/form-data">
      <div id="aplpwrapper">
          <?php echo JText::_('APPLY_FOR_POSITION'); ?>
          <br class="clear" />
          <h3><?php echo $this->data->job_title; if($this->config->use_location == 1) { if($this->data->country_name <> 'COM_JOBBOARD_DB_ANYWHERE_CNAME') echo ' - '.$this->data->city; else echo ' - '.JText::_('WORK_FROM_ANYWHERE');} ?></h3>
          <div <?php if($this->config->appl_job_summary == 1) echo 'id="contleft"'; ?>>
               <div class="controw">
                  <label for="first_name"><?php echo JText::_('FIRSTNAME'); ?><span class="fieldreq"><?php echo $req_marker; ?></span></label>
                  <input class="inputfield " maxlength="20" id="first_name" name="first_name" size="40" value="<?php echo ($this->errors > 0)? $afields->first_name: ''; ?>" type="text" />
               </div>
               <div class="controw">
                  <label for="last_name"><?php echo JText::_('LASTNAME'); ?><span class="fieldreq"><?php echo $req_marker; ?></span></label>
                  <input class="inputfield " maxlength="20" id="last_name" name="last_name" size="40" value="<?php echo ($this->errors > 0)? $afields->last_name: ''; ?>" type="text" />
               </div>
               <div class="controw">
                  <label for="email"><?php echo JText::_('EMAIL_ADDRESS'); ?><span class="fieldreq"><?php echo $req_marker; ?></span></label>
                  <input class="inputfield " maxlength="50" id="email" name="email" size="40" value="<?php echo ($this->errors > 0)? $afields->email: ''; ?>" type="text" />
               </div>
               <div class="controw">
                  <label for="tel"><?php echo JText::_('TELEPHONE'); ?><span class="fieldreq"><?php echo $req_marker; ?></span></label>
                  <input class="inputfield " maxlength="50" id="tel" name="tel" size="40" value="<?php echo ($this->errors > 0)? $afields->tel: ''; ?>" type="text" />
               </div>
               <div class="controw">
                  <label for="title"><?php echo JText::_('CV_RESUME_TITLE'); ?><span class="fieldreq"><?php echo $req_marker; ?></span></label>
                  <input class="inputfield" id="title" name="title" size="40" maxlength="50" value="<?php echo ($this->errors > 0)? $afields->title: ''; ?>" type="text" />
               </div>
               <div class="rowsep">&nbsp;</div>
               <div class="controw">
               <div class="uplrow">
                  <label for="cv"><?php echo JText::_('CV_RESUME'); ?><span class="fieldreq"><?php echo $req_marker; ?></span></label>
                  <input class="inputfield" name="cv" id="cv" type="file" />
               </div>
               <div id="fslabel">
                <small><?php echo JText::_('COM_JOBBOARD_MIN_PERMFORMATS'); ?><br />
                    <span class="right"><?php echo JText::_('COM_JOBBOARD_MAXSIZE').' ' . JobBoardGuestHelper::getMaxFileUploadSize() .JText::_('COM_JOBBOARD_MEGABYTES') ?></span>
               </small>
               </div>
               </div>
               <div class="rowsep">
                  <h4><?php echo JText::_('OPTIONAL') ?></h4>
                  <label for="cover_text"><?php echo JText::_('COVER_NOTE') ?></label> <br /><small><?php echo JText::_('COVER_NOTE_HINT'); ?>:</small>
                  <textarea rows="4" cols="10" id="cover_text" name="cover_text" ><?php echo ($this->errors > 0)? $afields->cover_note: ''; ?></textarea>
               </div>
               <?php if($verify_humans) : ?>
                   <div class="controw clear">
                      <label><?php echo JText::_('COM_JOBBOARD_FORM_CAPTCHA') ?></label> <br />
                      <img class="human_v right mright12p" src="<?php echo JRoute::_('index.php?option=com_jobboard&view=human&format=raw') ?>" alt="<?php echo JText::_('COM_JOBBOARD_FORM_CAPTCHA') ?>" />
                      <a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=member') ?>" class="clear hv_refresh right mright12p"><?php echo JText::_('COM_JOBBOARD_FORM_CAPTCHA_REFRESH') ?></a>  <br />
                      <label class="left clear"><?php echo JText::_('COM_JOBBOARD_FORM_CAPTCHA_CODE') ?></label>
              	      <input class="inputfield" type="text" name="human_ver" value="" />
                      <?php JHTML::_('script', 'human_ver.js', 'components/com_jobboard/js/') ?>
                   </div>
               <?php endif ?>
               <div id="submitArea">
                    <span id="loadr" class="hidel"><!--  --></span>
                    <input id="submit_application" name="submit_application" value="<?php echo JText::_('SUBMIT_APPLICATION') ?>" class="button jbsendbutton" type="submit" />
                    <?php $sel_job='index.php?option=com_jobboard&view=job&id='.$this->job_id ?>
                    <a class="cancel" href="<?php echo JRoute::_($sel_job); ?>"><?php echo JText::_('CANCEL'); ?></a>
               </div>
               <div class="clear">&nbsp;</div>
            </div>
            <?php if($this->config->appl_job_summary == 1) : ?>
            <div id="contright">
                <h3><?php echo JText::_('JOB_SUMMARY'); ?></h3>
                <?php if($this->config->show_applcount == 1) : ?>
                   <div class="jsrow">
                      <?php echo '<span class="summtitle">'.JText::_('NUMBER_OF_APPLICATIONS').':</span><br />'.$this->data->num_applications; ?>
                   </div>
                <?php endif; ?>
                <?php if($this->config->use_location == 1) : ?>
                   <div class="jsrow">
                      <?php $location_string = ($this->data->country_name <> 'COM_JOBBOARD_DB_ANYWHERE_CNAME')? $this->data->city.', '.$this->data->country_name.', '.$this->data->country_region : JText::_('WORK_FROM_ANYWHERE'); ?>
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
                <div class="jsrow <?php if($this->data->expiry_date == "0000-00-00 00:00:00") echo "lrow"; ?>">
                   <?php $this_salary = (strlen($this->data->salary) < 1)? JText::_('NEGOTIABLE') : $this->data->salary; ?>
                   <?php echo '<span class="summtitle">'.JText::_('SALARY').':</span><br /><b>'.$this_salary.'</b>'; ?>
                </div>
                <?php if($this->data->expiry_date <> "0000-00-00 00:00:00"):?>
                   <div class="jsrow lrow">
               		  <?php $exp_date = new JDate($this->data->expiry_date); ?>
                      	<?php echo '<span class="summtitle">'.JText::_('CUTOFF_DATE').':</span><br /><b>'; ?>
                		  <?php switch($this->config->long_date_format) {
                		  	case 0: echo $exp_date->toFormat("%d %b, %Y").'</b>';break;
                		  	case 1: echo $exp_date->toFormat("%b %d, %Y").'</b>';break;
                		  	case 2: echo $exp_date->toFormat("%Y, %b %d").'</b>';break; ?>
                   	      <?php } ?>
                   </div>
                <?php endif; ?>
              </div> <!-- end contright -->
            <?php endif; ?>
            </div>
            <input name="option" value="com_jobboard" type="hidden" />
            <input name="view" value="upload" type="hidden" />
            <input name="form_submit" value="submitted" type="hidden" />
            <input name="job_id" value="<?php echo $this->job_id; ?>" type="hidden" />
            <input name="position" value="<?php echo $this->data->job_title; ?>" type="hidden" />
            <?php if($this->config->use_location == 1) : ?>
              <?php $location_string = ($this->data->country_name <> 'COM_JOBBOARD_DB_ANYWHERE_CNAME')? $this->data->city : JText::_('WORK_ANYWHERE'); ?>
              <input name="city" value="<?php echo $location_string; ?>" type="hidden" />
            <?php else : ?>
              <input name="city" value="" type="hidden" />
            <?php endif; ?>
            <?php echo JHTML::_('form.token'); ?>
      </form>
 <?php endif ?>
<?php echo $this->setstate; ?>                            