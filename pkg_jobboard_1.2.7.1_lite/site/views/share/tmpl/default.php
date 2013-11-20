<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

  defined('_JEXEC') or die('Restricted access');
?>
<?php $user = & JFactory::getUser() ?>
<!-- CSS -->
<?php JHTML::_('stylesheet', 'base.css', 'components/com_jobboard/css/') ?>
<?php JHTML::_('stylesheet', 'share.css', 'components/com_jobboard/css/') ?>         
<!-- End CSS -->

<?php $verify_login = JobBoardHelper::verifyLogin();  ?>
<?php if(!$this->published) : ?>
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
            <a class="asep right logout" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=member&iview=logout&Itemid='.$itemid)?>"><?php echo JText::_('COM_JOBBOARD_LOGOUT') ?></a>
            <a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&Itemid='.$this->itemid)?>" class="right"><?php echo JText::_('COM_JOBBOARD_MYACCT') ?></a>
        <?php endif ?>
      </div>
<?php else : ?>
  <?php if($this->config->send_tofriend == 0) : ?>
     <?php $app = & JFactory::getApplication();  ?>
     <?php return $app->redirect('index.php?option=com_jobboard&view=job&id='.$this->id.'&Itemid='.$this->itemid, JText::_('SHARING_NOT_ALLOWED'), 'error'); ?>
  <?php endif ?>
  <?php if($this->config->jobtype_coloring == 1) :?>
      <?php JHTML::_('stylesheet', 'job_colors.css', 'components/com_jobboard/css/') ?>
  <?php endif ?>
  <?php if($this->config->jobtype_coloring == 1) :?>
  	<?php $jt_color = '<span class="jobtype '.JobBoardJobHelper::getClass($this->data->job_type).'">'.JText::_($this->data->job_type).'</span>';?>
  <?php else : ?>
  	<?php $jt_color = JText::_($this->data->job_type);?>
  <?php endif; ?>
  <!-- end job coloring -->

  <?php $this->errors = JRequest::getVar('errors', '');?>
  <?php if($this->errors > 0) : ?>
     <?php $app= JFactory::getApplication(); ?>
     <?php $afields = $app->getUserState('com_jobboard.sfields');   ?>
  <?php endif; ?>
  <?php $req_marker = '*'; ?>
  <?php $path = 'index.php?option=com_jobboard&view=job&task=share&Itemid='.$this->itemid; ?>
  <?php $verify_humans = JobBoardHelper::verifyHumans();  ?>
  <div id="aplpwrapper">
      <h3><?php echo JText::_('EMAIL_JOB_POSTING'); ?></h3>
      <div <?php if($this->config->sharing_job_summary == 1) echo 'id="contleft"'; ?>>
    <form method="post" action="<?php echo JRoute::_($path); ?>" id="shareFRM" name="shareFRM" enctype="multipart/form-data">
          <fieldset>
            <span class="legend"><?php echo JText::_('YOUR_DETAILS'); ?></span>
            <div class="controw">
              <label for="sender_email"><?php echo JText::_('YOUR_EMAIL'); ?><span class="fieldreq"><?php echo $req_marker; ?></span></label>
              <?php if(!$user->get('guest')) : ?>
                <span class="value"><?php echo $user->email ?></span >
                <input type="hidden" id="sender_email" name="sender_email" value="<?php echo $user->email ?>" />
              <?php else :?>
                <input class="inputfield " maxlength="60" id="sender_email" name="sender_email" value="<?php echo ($this->errors > 0)? $afields->sender_email: ''; ?>" type="text" />
              <?php endif ?>
            </div>
            <div class="controw">
              <label for="sender_name"><?php echo JText::_('YOUR_FULL_NAME'); ?><span class="fieldreq"><?php echo $req_marker; ?></span></label>
              <?php if(!$user->get('guest')) : ?>
                <span class="value"><?php echo $user->name ?></span >
                <input type="hidden" id="sender_name" name="sender_name" value="<?php echo $user->name ?>" />
              <?php else :?>
                <input class="inputfield " maxlength="60" id="sender_name" name="sender_name" value="<?php echo ($this->errors > 0)? $afields->sender_name: ''; ?>" type="text" />
              <?php endif ?>
            </div>
          </fieldset>
           <div class="rowsep">&nbsp;</div>
          <fieldset>
          <span class="legend"><?php echo JText::_('YOUR_MESSAGE'); ?></span>
            <div class="controw">
              <label for="rec_emails"><?php echo JText::_('TO_EMAIL_ADDRESSES'); ?><span class="fieldreq"><?php echo $req_marker; ?></span></label>
              <small><?php echo JText::_('COMMA_SEPARATE_MULTIPLE_ADDRESSES'); ?></small>
              <textarea class="inputfield " rows="4" cols="10" id="rec_emails" name="rec_emails"><?php echo ($this->errors > 0)? $afields->rec_emails: ''; ?></textarea>
            </div>
            <div class="controw t15">
              <label for="personal_message"><?php echo JText::_('ENTER_BRIEF_MESSAGE'); ?></label>
              <textarea class="inputfield mtop5" rows="6" cols="10" id="personal_message" name="personal_message"><?php echo ($this->errors > 0)? $afields->personal_message: $this->msg; ?></textarea>
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
          </fieldset>
          <div class="btnarea">
              <input name="sendsubmit" value="<?php echo JText::_('SEND_MESSAGE') ?>" class="button jbsendbutton" type="submit" />
              <?php $sel_job='index.php?option=com_jobboard&view=job&id='.$this->job_id.'&Itemid='.$this->itemid; ?><a class="mleft10" href="<?php echo JRoute::_($sel_job); ?>"><?php echo JText::_('CANCEL'); ?></a>
          </div>
        <input name="job_id" value="<?php echo $this->job_id; ?>" type="hidden" />
        <input name="job_title" value="<?php echo $this->data->job_title; ?>" type="hidden" />
        <?php if($this->config->use_location == 1) : ?>
        	<?php $location_string = ($this->data->country_name <> 'DB_ANYWHERE_CNAME')? $this->data->city : JText::_('WORK_ANYWHERE'); ?>
        	<input name="job_city" value="<?php echo $location_string; ?>" type="hidden" />
        <?php else : ?>
        	<input name="job_city" value="" type="hidden">
        <?php endif; ?>
        <input name="job_path" value="<?php echo JRoute::_($sel_job); ?>" type="hidden" />
        <input name="Itemid" value="<?php echo $this->itemid; ?>" type="hidden" /> 
        <?php echo JHTML::_('form.token'); ?>
        </form>
        <div class="clear">&nbsp;</div>
     </div>
     <?php if($this->config->sharing_job_summary == 1) :?>
        <div id="contright">
             <h3><?php echo JText::_('JOB_SUMMARY'); ?></h3>
             <div class="jsrow">
                <?php echo '<span class="summtitle">'.JText::_('JOB_TITLE').':</span><br />'.$this->data->job_title; ?>
             </div>
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
  		          	<?php echo '<span class="summtitle">'.JText::_('APPLY_BEFORE').':</span><br /><b>'; ?>
  			   		  <?php switch($this->config->long_date_format) {
  			   		  	case 0: echo $exp_date->toFormat("%d %b, %Y").'</b>';break;
  			   		  	case 1: echo $exp_date->toFormat("%b %d, %Y").'</b>';break;
  			   		  	case 2: echo $exp_date->toFormat("%Y, %b %d").'</b>';break; ?>
  		       	  <?php } ?>
  		       </div>
  	       <?php endif; ?>
        </div>
       <?php endif; ?>
   </div>
 <?php endif ?>
<?php echo $this->setstate; ?>