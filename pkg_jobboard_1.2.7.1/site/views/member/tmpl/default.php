<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
$itemid = JRequest::getInt('Itemid');
$document = & JFactory::getDocument();
$document->setTitle(JText::_('COM_JOBBOARD_LOGIN_REG'));
?>
<?php JHTML::_('stylesheet', 'member.css', 'components/com_jobboard/css/'); ?>
<?php JHTML::_('behavior.mootools'); ?>
<?php JHTML::_('script', 'member.js', 'components/com_jobboard/js/') ?>
<?php $verify_login = JobBoardHelper::verifyLogin();  ?>

<?php if(JobBoardHelper::allowRegistration()) : ?>
  <?php $verify_reg = JobBoardMemberHelper::verifyReg(); ?>
  <div id="regpanel" class="signup-call-out<?php if($this->iview == 'login') echo ' hidden' ?>">
    <div class="profile-signup">
      <h1><?php echo JText::_('COM_JOBBOARD_REG') ?></h1>
      <form id="jjbReg" name="jjbReg" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=member', true, JobBoardHelper::useSecure()); ?>" class="signup signup-btn" method="post">
        <div id="employer-provider">
    	   <input type="radio" tabindex="5" id="isemployer0" name="isemployer" value="0" checked="checked" /> <?php echo JText::sprintf('COM_JOBBOARD_LOOKINGFOR', ' <strong>'.JText::_('COM_JOBBOARD_TXTWORK').'</strong>') ?>
    	   <span class="mleft10"><input type="radio" id="isemployer1" name="isemployer" value="yes" /> <?php echo JText::sprintf('COM_JOBBOARD_IWANTO', " <strong>".JText::_('COM_JOBBOARD_TXTHIRE')."</strong>") ?> </span>
    	</div>
        <div class="holding name">
          <input type="text" value="" id="regfname" name="name" maxlength="20" class="textfield" />
          <span class="holder"><?php echo JText::_('COM_JOBBOARD_TXTNAME') ?></span>
        </div>
        <div class="holding uname">
          <input type="text" value="" id="reguname" name="username" maxlength="20" class="textfield" />
          <span class="holder"><?php echo JText::_('COM_JOBBOARD_TXTUSERNAME') ?></span>
        </div>
        <div class="holding email">
          <input type="text" value="" name="email" class="textfield" />
          <span class="holder"><?php echo JText::_('COM_JOBBOARD_EMAIL') ?></span>
        </div>
        <div class="holding password">
          <input type="password" value="" name="password" class="textfield" />
          <span class="holder"><?php echo JText::_('COM_JOBBOARD_TXTPASSWORD') ?></span>
        </div>
        <div class="holding password">
          <input type="password" value="" name="password2" class="textfield" />
          <span class="holder"><?php echo JText::_('COM_JOBBOARD_TXTPASSWORDCONFIRM') ?></span>
        </div>
        <?php if($verify_reg) : ?>
          <div class="holding verific clear">
            <span class="holder"><?php echo JText::_('COM_JOBBOARD_FORM_CAPTCHA') ?></span>
            <img class="human_v" src="<?php echo JRoute::_('index.php?option=com_jobboard&view=human&format=raw') ?>" alt="<?php echo JText::_('COM_JOBBOARD_FORM_CAPTCHA') ?>" />
            <a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=member') ?>" class="clear hv_refresh"><?php echo JText::_('COM_JOBBOARD_FORM_CAPTCHA_REFRESH') ?></a>
    	      <input type="text" name="human_ver" value="" />
            <span class="holder"><?php echo JText::_('COM_JOBBOARD_FORM_CAPTCHA_CODE') ?></span>
            <?php if(!$verify_login) : ?>
               <?php JHTML::_('script', 'human_ver.js', 'components/com_jobboard/js/') ?>
            <?php endif ?>
          </div>
        <?php endif ?>
        <input type="hidden" name="option" value="com_jobboard" />
        <input type="hidden" name="view" value="member" />
  	    <input type="hidden" name="task" value="signup" />
    	<input type="hidden" name="iview" value="<?php echo $this->iview ?>" />
        <input type="submit" class="promotional submit button" value="<?php echo JText::_('COM_JOBBOARD_REG') ?>" />
        <a href="<?php echo JRoute::_('index.php?option=com_jobboard'); ?>" id="csignup"><?php echo JText::_('CANCEL');?></a><br class="clear" />
        <?php echo JHtml::_('form.token'); ?>
      </form>
    </div>
  </div>
<?php endif ?>
  <div id="loginpanel" class="login-call-out<?php if($this->iview == 'register') echo ' hidden' ?>">
    <div class="profile-signup">
      <h1><?php echo JText::_('COM_JOBBOARD_LOGIN') ?></h1>
      <form id="jjbLogin" name="jjbLogin" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=member', true, JobBoardHelper::useSecure()); ?>" class="signup signup-btn" method="post">
        <div class="holding name">
          <input type="text" value="" name="username" id="username" maxlength="20" class="textfield" />
          <span class="holder"><?php echo JText::_('COM_JOBBOARD_TXTUSERNAME') ?></span>
        </div>
        <div class="holding password">
          <input type="password" value="" name="password" id="password" class="textfield" />
          <span class="holder"><?php echo JText::_('COM_JOBBOARD_TXTPASSWORD') ?></span>
        </div>
        <?php if($verify_login || $this->retries <> 0) : ?>
            <div class="holding verific clear">
              <span class="holder"><?php echo JText::_('COM_JOBBOARD_FORM_CAPTCHA') ?></span>
              <img class="human_v" src="<?php echo JRoute::_('index.php?option=com_jobboard&view=human&format=raw') ?>" alt="<?php echo JText::_('COM_JOBBOARD_FORM_CAPTCHA') ?>" />
              <a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=member') ?>" class="clear hv_refresh"><?php echo JText::_('COM_JOBBOARD_FORM_CAPTCHA_REFRESH') ?></a>
      	      <input type="text" name="human_ver" value="" />
              <span class="holder"><?php echo JText::_('COM_JOBBOARD_FORM_CAPTCHA_CODE') ?></span>
              <?php JHTML::_('script', 'human_ver.js', 'components/com_jobboard/js/') ?>
            </div>
        <?php endif ?>
        <label class="remember">
          <input type="checkbox" name="remember" id="remember" value="yes" />
          <span><?php echo JText::_('COM_JOBBOARD_TXTREMEMBER') ?></span>
        </label>
        <input type="hidden" name="option" value="com_jobboard" />
        <input type="hidden" name="view" value="member" />
  	    <input type="hidden" name="task" value="login" />
  	    <input type="hidden" name="iview" value="<?php echo $this->iview ?>" />
  	    <input type="hidden" name="redirect" value="<?php echo $this->redirect ?>" />
        <input type="submit" class="promotional submit button mpadr" value="<?php echo JText::_('COM_JOBBOARD_LOGIN') ?>" />

        <p class="txtpar clear">
          <?php if(JobBoardHelper::allowRegistration()) : ?>
            <a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=member') ?>" id="signup"><?php echo JText::_('COM_JOBBOARD_TXTREGISTER') ?></a><br class="clear" />
          <?php endif ?>
          <a href="<?php echo JRoute::_('index.php?option='.$this->user_entry_point.'&view=reset'); ?>" class="forgot"><?php echo JText::_('COM_JOBBOARD_TXTRESETPASS') ?></a><br class="clear" />
        </p>
        <?php echo JHtml::_('form.token'); ?>
    </form>
   </div>
 </div>
 <script type="text/javascript">
    window.addEvent('domready', function() {
          var Tandolin = Tandolin || {};
          Tandolin.Member = Tandolin.Member || {};
          Tandolin.Member.Instance = new TandolinMemberView({
             currView : <?php echo '"'.$this->iview.'"' ?>
          });
    });
 </script>
 <div class="clear"> &nbsp;</div>