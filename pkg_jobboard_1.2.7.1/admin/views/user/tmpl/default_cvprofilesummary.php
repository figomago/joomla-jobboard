<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
?>

<?php // if($this->context == 'cvprofile') : ?>
  <span>
    <?php echo $this->applicant_name ?>
    <?php if(isset($this->cv_name)) : ?>
       <small>&mdash; <a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=viewcv&pid='.$this->pid.'&sid='.$this->appl_uid.'&jid='.$this->jid) ?>">
            <?php echo JText::_('COM_JOBBOARD_VIEW_CV_RESUME') ?>
        </a>
      </small>
    <?php endif ?>
  </span>
  <div class="centered">
      <?php if($this->is_profile_pic == 1) : ?>
          <?php $randomiser = '?'.rand(1,2500) ?>
          <img src="<?php echo JURI::root().$this->imgthumb_115.$randomiser ?>" alt="<?php echo $this->user_prof_data->name ?>" />
      <?php else : ?>
           <img src="<?php echo JURI::root() ?>components/com_jobboard/images/user_default.jpg" alt="<?php echo JText::_('COM_JOBBOARD_PROFPIC') ?>" />
      <?php endif ?>                                                                                                   
  </div>
<?php // endif ?>
<?php if(!isset($this->user_prof_data) || ($this->user_prof_data->contact_address == '' && $this->user_prof_data->contact_location == '' && $this->user_prof_data->contact_zip == '' && $this->user_prof_data->website_url == '')) : ?>
  <div>&nbsp;</div>
<?php else : ?>
  <div>
      <span class="labeltxt"><?php echo JText::_('COM_JOBBOARD_PHYSICAL_ADDRESS') ?></span>
    	<span class="field txtbreak">
          <?php if($this->user_prof_data->contact_address <> '') echo nl2br($this->user_prof_data->contact_address).'<br />' ?>
          <?php if($this->user_prof_data->contact_location <> '') echo $this->user_prof_data->contact_location.'<br />' ?>
          <?php if($this->user_prof_data->contact_zip <> '') echo $this->user_prof_data->contact_zip ?>
      </span>
  </div>
  <div>
      <span class="label email">&nbsp;</span>
      <span class="txtbreak"><?php echo $this->user_prof_data->email ?></span>
  </div>
  <?php if($this->user_prof_data->website_url <>'') : ?>
    <div>
        <span class="label website">&nbsp;</span>
        <span class="txtbreak"><a href="<?php echo $this->user_prof_data->website_url ?>" target="_blank"><?php echo JText::_('COM_JOBBOARD_URL') ?></a></span>
    </div>
  <?php endif ?>
  <?php if($this->user_prof_data->facebook_url <>'') : ?>
    <div>
        <span class="label facebook">&nbsp;</span>
        <span class="txtbreak"><a href="http://www.facebook.com/<?php echo $this->user_prof_data->facebook_url ?>" target="_blank"><?php echo JText::_('COM_JOBBOARD_FACEBOOK') ?></a></span>
    </div>
  <?php endif ?>
  <?php if($this->user_prof_data->twitter_url <>'') : ?>
    <div>
        <span class="label twitter">&nbsp;</span>
        <span class="txtbreak"><a href="http://www.twitter.com/<?php echo $this->user_prof_data->twitter_url ?>" target="_blank"><?php echo JText::_('COM_JOBBOARD_TWITTER') ?></a></span>
    </div>
  <?php endif ?>
  <?php if($this->user_prof_data->linkedin_url <>'') : ?>
    <div>
        <span class="label linkedin">&nbsp;</span>
        <span class="txtbreak"><a href="http://www.linkedin.com/in/<?php echo $this->user_prof_data->linkedin_url ?>" target="_blank"><?php echo JText::_('COM_JOBBOARD_LINKEDIN') ?></a></span>
    </div>
  <?php endif ?>
  <?php if($this->user_prof_data->contact_phone_1 <>'') : ?>
    <div>
        <span class="labeltxt"><?php echo JText::_('COM_JOBBOARD_TEL') ?></span>
      	<span class="field"><?php echo $this->user_prof_data->contact_phone_1 ?></span>
    </div>
  <?php endif ?>
  <?php if($this->user_prof_data->contact_fax <>'') : ?>
    <div>
        <span class="labeltxt"><?php echo JText::_('COM_JOBBOARD_FAX') ?></span>
      	<span class="field"><?php echo $this->user_prof_data->contact_fax ?></span>
    </div>
  <?php endif ?>
<?php endif ?>