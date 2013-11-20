<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
$user = & JFactory::getUser() ;
if($this->config->allow_unsolicited == 0 || !$user->get('guest')){
  $app = & JFactory::getApplication();
  return $app->redirect('index.php?option=com_jobboard&Itemid='.$this->itemid, JText::_('UNSOLAPPL_NOT_ALLOWED'), 'error');
}
?>
<!-- CSS -->
<?php JHTML::_('stylesheet', 'base.css', 'components/com_jobboard/css/') ?>
<?php JHTML::_('stylesheet', 'unsolicited_cv.css', 'components/com_jobboard/css/') ?>
<?php JHTML::_('stylesheet', 'login_popup.css', 'components/com_jobboard/css/') ?>
<!-- End CSS -->

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
   <?php $path = 'index.php?option=com_jobboard&view=upload&task=uload'; ?>
<form method="post" action="<?php echo JRoute::_($path); ?>" id="applFRM" name="applFRM" enctype="multipart/form-data">
    <div id="aplpwrapper">
        <?php echo JText::_('UPLOAD_CV_RESUME'); ?>
        <h3><?php echo JText::_('UNSOLICITED_SUBMISSION'); ?></h3>
        <div id="contleft">
           <p><strong><?php echo JText::_('NOTE') ?>: </strong><?php echo JText::_('UNSOLICITED_CV_NOTIFICATION'); ?></p>
           <div class="controw">
              <label for="first_name"><?php echo JText::_('FIRSTNAME'); ?><span class="fieldreq"><?php echo $req_marker; ?></span></label>
              <input class="inputfield " maxlength="60" id="first_name" name="first_name" value="<?php echo ($this->errors > 0)? $this->fields->first_name: ''; ?>" type="text" />
           </div>
           <div class="controw">
              <label for="last_name"><?php echo JText::_('LASTNAME'); ?><span class="fieldreq"><?php echo $req_marker; ?></span></label>
              <input class="inputfield " maxlength="60" id="last_name" name="last_name" value="<?php echo ($this->errors > 0)? $this->fields->last_name: ''; ?>" type="text" />
           </div>
           <div class="controw">
              <label for="email"><?php echo JText::_('EMAIL_ADDRESS'); ?><span class="fieldreq"><?php echo $req_marker; ?></span></label>
              <input class="inputfield " maxlength="60" id="email" name="email" value="<?php echo ($this->errors > 0)? $this->fields->email: ''; ?>" type="text" />
           </div>
           <div class="controw">
              <label for="tel"><?php echo JText::_('TELEPHONE'); ?><span class="fieldreq"><?php echo $req_marker; ?></span></label>
              <input class="inputfield " maxlength="50" id="tel" name="tel" value="<?php echo ($this->errors > 0)? $this->fields->tel: ''; ?>" type="text" />
           </div>
           <div class="controw">
              <label for="title"><?php echo JText::_('CV_RESUME_TITLE'); ?><span class="fieldreq"><?php echo $req_marker; ?></span></label>
              <input class="inputfield " id="title" name="title" maxlength="50" value="<?php echo ($this->errors > 0)? $this->fields->title: ''; ?>" type="text" />
           </div>
           <div class="rowsep">&nbsp;</div>
           <div class="controw">
              <div class="uplrow">
                <label for="cv"><?php echo JText::_('CV_RESUME'); ?><span class="fieldreq"><?php echo $req_marker; ?></span></label>
                <input class="inputfield " name="cv" id="cv" type="file" />
              </div>
              <div id="fslabel">
                <small class="right"><?php echo JText::_('COM_JOBBOARD_MIN_PERMFORMATS'); ?><br />
                    <span class="right"><?php echo JText::_('COM_JOBBOARD_MAXSIZE').' ' . JobBoardGuestHelper::getMaxFileUploadSize() . JText::_('COM_JOBBOARD_MEGABYTES') ?></span>
               </small>
              </div>
           </div>
           <div class="rowsep"> <h4><?php echo JText::_('OPTIONAL') ?></h4>
              <label for="cover_text"><?php echo JText::_('COVER_NOTE') ?></label> <br /><small><?php echo JText::_('COVER_NOTE_HINT'); ?>:</small>
              <textarea rows="4" cols="10" id="cover_text" name="cover_text"><?php echo ($this->errors > 0)? $this->fields->cover_note: ''; ?></textarea>
           </div>
           <?php if($verify_humans) : ?>
               <div class="controw">
                    <label><?php echo JText::_('COM_JOBBOARD_FORM_CAPTCHA') ?></label> <br />
                    <img class="human_v right mright12p" src="<?php echo JRoute::_('index.php?option=com_jobboard&view=human&format=raw') ?>" alt="<?php echo JText::_('COM_JOBBOARD_FORM_CAPTCHA') ?>" />
                    <a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=member') ?>" class="clear hv_refresh right mright12p"><?php echo JText::_('COM_JOBBOARD_FORM_CAPTCHA_REFRESH') ?></a>  <br />
                    <label class="left clear"><?php echo JText::_('COM_JOBBOARD_FORM_CAPTCHA_CODE') ?></label>
            	      <input class="inputfield" type="text" name="human_ver" value="" />
                    <?php JHTML::_('script', 'human_ver.js', 'components/com_jobboard/js/') ?>
               </div>
           <?php endif ?>
           <div class="btnarea">
                <span id="loadr" class="hidel"><!--  --></span>
                <input id="submit_application" name="submit_application" value="<?php echo JText::_('SUBMIT_APPLICATION') ?>" class="button jbsendbutton" type="submit" />
                <?php $show_list='index.php?option=com_jobboard&view=list'; ?><a class="mleft10" href="<?php echo JRoute::_($show_list); ?>"><?php echo JText::_('BACK'); ?></a>
           </div>
        </div>
    </div>
    <input name="form_submit" value="submitted" type="hidden" />
    <?php echo JHTML::_('form.token'); ?>
</form>
 <?php echo $this->setstate; ?>
