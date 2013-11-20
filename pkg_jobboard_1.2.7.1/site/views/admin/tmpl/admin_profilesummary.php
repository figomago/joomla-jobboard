<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');

?>
<?php if($this->layout_style == 2)  : ?>
  <div>
      <?php echo $this->loadTemplate('sidebar'); ?>
  </div>
<?php endif ?>
<?php if($this->is_profile_pic == true && $this->context == 'cvprofile') : ?>
  <div class="centered">
      <img src="<?php echo $this->imgthumb_115.'?'.rand(1,1000) ?>" alt="<?php echo $this->user_prof_data->name ?>" />
      <p class="caption">
        <span>
            <a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=prof&tab=2') ?>"><?php echo JText::_('COM_JOBBOARD_EDIT') ?></a>
        </span>
    </p>
  </div>
<?php endif ?>
<?php if(!isset($this->user_prof_data) || ($this->user_prof_data->contact_address == '' && $this->user_prof_data->contact_location == '' && $this->user_prof_data->contact_zip == '' && $this->user_prof_data->website_url == '')) : ?>
  <div><?php echo JText::_('COM_JOBBOARD_NO_CONTACT_DETAILS') ?></div>
<?php else : ?>
  <div>
      <label><?php echo JText::_('COM_JOBBOARD_PHYSICAL_ADDRESS') ?></label>
    	<span class="field txtbreak">
          <?php if($this->user_prof_data->contact_address <> '') echo nl2br($this->user_prof_data->contact_address).'<br />' ?>
          <?php if($this->user_prof_data->contact_location <> '') echo $this->user_prof_data->contact_location.'<br />' ?>
          <?php if($this->user_prof_data->contact_zip <> '') echo $this->user_prof_data->contact_zip ?>
      </span>
  </div>
  <div>
      <label class="txtbreak email"><?php echo $this->user_prof_data->email ?></label>
  </div>
  <?php if($this->user_prof_data->website_url <>'') : ?>
    <div>
        <label class="website"><a rel="<?php echo $this->user_prof_data->website_url ?>" href="<?php echo $this->user_prof_data->website_url ?>" target="_blank"><?php echo JText::_('COM_JOBBOARD_URL') ?></a></label>
    </div>
  <?php endif ?>
  <?php if($this->user_prof_data->facebook_url <>'') : ?>
    <div>
        <label class="facebook"><a rel="http://www.facebook.com/<?php echo $this->user_prof_data->facebook_url ?>" href="http://www.facebook.com/<?php echo $this->user_prof_data->facebook_url ?>" target="_blank"><?php echo JText::_('COM_JOBBOARD_FACEBOOK') ?></a></label>
    </div>
  <?php endif ?>
  <?php if($this->user_prof_data->twitter_url <>'') : ?>
    <div>
        <label class="twitter"><a rel="http://www.twitter.com/<?php echo $this->user_prof_data->twitter_url ?>" href="http://www.twitter.com/<?php echo $this->user_prof_data->twitter_url ?>" target="_blank"><?php echo JText::_('COM_JOBBOARD_TWITTER') ?></a></label>
    </div>
  <?php endif ?>
  <?php if($this->user_prof_data->linkedin_url <>'') : ?>
    <div>
        <label class="linkedin"><a rel="http://www.linkedin.com/in/<?php echo $this->user_prof_data->linkedin_url ?>" href="http://www.linkedin.com/in/<?php echo $this->user_prof_data->linkedin_url ?>" target="_blank"><?php echo JText::_('COM_JOBBOARD_LINKEDIN') ?></a></label>
    </div>
  <?php endif ?>
  <?php if($this->user_prof_data->contact_phone_1 <>'') : ?>
    <div>
        <label><?php echo JText::_('COM_JOBBOARD_TEL') ?></label>
      	<span class="field"><?php echo $this->user_prof_data->contact_phone_1 ?></span>
    </div>
  <?php endif ?>
  <?php if($this->user_prof_data->contact_fax <>'') : ?>
    <div>
        <label><?php echo JText::_('COM_JOBBOARD_FAX') ?></label>
      	<span class="field"><?php echo $this->user_prof_data->contact_fax ?></span>
    </div>
  <?php endif ?>
<?php endif ?>
  <div>
      <label>&nbsp;</label>
    	<span class="field">&#8593;&nbsp;<a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=prof') ?>"><?php echo JText::_('COM_JOBBOARD_EDIT') ?></a></span>
  </div>