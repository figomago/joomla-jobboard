<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
$document = &JFactory::getDocument();
$document->setTitle(JText::_('COM_JOBBOARD_ADM_OVERVIEW') );    
?>
<div class="ovsummary admin" id="jbovsummary">
    <div class="big_link first">
      <a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=jobs&jmode=active&Itemid='.$this->itemid) ?>">
        <span class="type-smallgrey"><?php echo JText::_('COM_JOBBOARD_ADM_ACTIVE_JOBS') ?></span>
          <span class="type-cost "><?php echo $this->active_jobs ?></span>
          <span class="type-note-black">(&raquo; <?php echo JText::_('COM_JOBBOARD_VIEW') ?>)</span>
        <?php if($this->user_auth['manage_jobs'] == 0) : ?>
          <span class="type-cost ">&nbsp;</span>
          <span class="type-note-black"><?php echo JText::_('COM_JOBBOARD_ADM_FEATURE_NOAUTH') ?></span>
        <?php endif ?>
      </a>
    </div>
    <div class="big_link second">
      <a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=invites&Itemid='.$this->itemid) ?>">
        <span class="type-smallgrey"><?php echo JText::_('COM_JOBBOARD_ADM_JOBSEEKER_INVITES') ?></span>
        <span class="type-cost "><?php echo $this->all_invites ?></span><?php echo JText::sprintf('COM_JOBBOARD_ENT_SENT', '') ?>&nbsp;<span class="type-cost "><?php echo $this->responded_invites ?></span>
        <span class="type-note-black">&nbsp;<?php echo JText::_('COM_JOBBOARD_ADM_INVITE_RESPONSES') ?>&nbsp;(&raquo; <?php echo JText::_('COM_JOBBOARD_VIEW_INVITES') ?>)</span>
      </a>
    </div>
    <div class="big_link first">
      <a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=jobs&jmode=inactive&Itemid='.$this->itemid) ?>">
        <span class="type-smallgrey"><?php echo JText::_('COM_JOBBOARD_ADM_INACTIVE_JOBS') ?></span>
          <span class="type-cost"><?php echo $this->inactive_jobs ?></span>
          <span class="type-note-black">(&raquo; <?php echo JText::_('COM_JOBBOARD_VIEW') ?>)</span>
        <?php if($this->user_auth['manage_jobs'] == 0) : ?>
          <span class="type-cost ">&nbsp;</span>
          <span class="type-note-black"><?php echo JText::_('COM_JOBBOARD_ADM_FEATURE_NOAUTH') ?></span>
        <?php endif ?>
        <?php if($this->user_auth['manage_applicants'] == 0) : ?>
          <br />
          <span class="type-cost ">&nbsp;</span>
          <span class="type-note-black">&nbsp;</span>
        <?php endif ?>
      </a>
    </div>
    <div class="big_link second">
      <a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=jobs&Itemid='.$this->itemid) ?>">
        <span class="type-smallgrey"><?php echo JText::_('COM_JOBBOARD_ADM_JOBSEEKER_APPLS') ?></span>
        <span class="type-cost"><?php echo $this->user_appls+$this->site_appls ?></span> | <?php echo $this->user_appls?> <span class="type-note-black"><?php echo JText::_('COM_JOBBOARD_ADM_RAPPLS') ?></span> - <?php echo $this->site_appls ?> <span class="type-note-black"><?php echo JText::_('COM_JOBBOARD_ADM_SAPPLS') ?></span>
        <span class="type-note-black">(&raquo; <?php echo JText::_('COM_JOBBOARD_VIEW') ?>)</span>
        <?php if($this->user_auth['manage_applicants'] == 0) : ?>
          <br />
          <span class="type-cost ">&nbsp;</span>
          <span class="type-note-black"><?php echo JText::_('COM_JOBBOARD_ADM_FEATURE_NOAUTH') ?></span>
        <?php endif ?>
      </a>
    </div>
    <div class="big_link first bottom">
      <a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=jobs&jmode=featured&Itemid='.$this->itemid) ?>">
        <span class="type-smallgrey"><?php echo JText::_('COM_JOBBOARD_ADM_FEATURED_JOBS') ?></span>
          <span class="type-cost"><?php echo $this->featured_jobs ?></span>
          <span class="type-note-black">(&raquo; <?php echo JText::_('COM_JOBBOARD_VIEW') ?>)</span>
        <?php if($this->user_auth['manage_jobs'] == 0) : ?>
          <span class="type-cost ">&nbsp;</span>
          <span class="type-note-black"><?php echo JText::_('COM_JOBBOARD_ADM_FEATURE_NOAUTH') ?></span>
        <?php endif ?>
      </a>
    </div>
    <div class="big_link second bottom">
      <a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=qlist&Itemid='.$this->itemid) ?>">
        <span class="type-smallgrey"><?php echo JText::_('COM_JOBBOARD_ADM_QNAIRES') ?></span>
        <span class="type-cost"><?php echo $this->questionnaires ?></span>
        <span class="type-note-black">(&raquo; <?php echo JText::_('COM_JOBBOARD_ADM_QNAIRES_VIEW') ?>)</span>
        <?php if($this->user_auth['manage_questionnaires'] == 0) : ?>
          <span class="type-cost ">&nbsp;</span>
          <span class="type-note-black"><?php echo JText::_('COM_JOBBOARD_ADM_FEATURE_NOAUTH') ?></span>
        <?php endif ?>
      </a>
    </div>
  </div>