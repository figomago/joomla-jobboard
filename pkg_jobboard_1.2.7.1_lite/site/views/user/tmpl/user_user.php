<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>  <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
$document = &JFactory::getDocument();
$document->setTitle(JText::_('COM_JOBBOARD_OVERVIEW') );
?>
<?php if(!$this->is_modal) : ?>
  <?php  $modal_params = array('handler'=> 'iframe', 'size' => array( 'x' => 640, 'y' => 480), 'sizeLoading'=>array( 'x' => 250, 'y' => 150), 'overlay'=>false, 'classWindow'=>'jobboardmodal'); ?>
  <?php JobBoardBehaviorHelper::modal('a.jobbrdmodal', $modal_params); ?>
<?php endif ?>
<div class="ovsummary" id="jbovsummary">
    <h2 class="low"><?php echo JText::_('COM_JOBBOARD_PROFSUMM') ?></h2>
    <?php $numappls = count($this->user_applications) ?>
    <div class="big_link first">
      <a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=appl&Itemid='.$this->itemid) ?>">
        <span class="type-smallgrey"><?php echo JText::_('COM_JOBBOARD_MYJOBAPPLICATIONS') ?></span>
        <span class="type-cost "><?php echo $this->num_applications ?></span>
        <?php if($this->num_applications > 0) : ?>
            <span class="type-note-black">(&raquo; <?php echo JText::_('COM_JOBBOARD_VIEWAPPLS') ?>)</span>
        <?php endif ?>
      </a>
    </div>
    <div class="big_link second">
      <a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=cvprofs&Itemid='.$this->itemid) ?>">
        <span class="type-smallgrey"><?php echo JText::_('COM_JOBBOARD_PROFVIEWS') ?></span>
        <span class="type-cost "><?php echo $this->profile_views ?></span>
        <span class="type-note-black">(&raquo; <?php echo JText::_('COM_JOBBOARD_VIEW') ?>)</span>
      </a>
    </div>
    <div class="big_link first">
      <a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=marked&Itemid='.$this->itemid) ?>">
        <span class="type-smallgrey"><?php echo JText::_('COM_JOBBOARD_MYMARKED') ?></span>
        <span class="type-cost"><?php echo $this->marked_jobs ?></span>
        <?php if($this->marked_jobs > 0) : ?>
            <span class="type-note-black">(&raquo; <?php echo JText::_('COM_JOBBOARD_VIEW') ?>)</span>
        <?php endif ?>
      </a>
    </div>
    <div class="big_link second">
      <a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=invites&Itemid='.$this->itemid) ?>">
        <span class="type-smallgrey"><?php echo JText::_('COM_JOBBOARD_MYINVITES') ?></span>
        <span class="type-cost"><?php echo $this->invites ?></span>
        <?php if($this->invites > 0) : ?>
          <span class="type-note-black">(&raquo; <?php echo JText::_('COM_JOBBOARD_VIEW_INVITES') ?>)</span>
        <?php endif ?>
      </a>
    </div>
    <div class="big_link top last">
      <a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=prof&Itemid='.$this->itemid) ?>">
        <span><?php echo JText::_('COM_JOBBOARD_VIEW_MY_PROFILE') ?> &raquo;</span>
      </a>
    </div>
  </div>
  <div class="ovsummary" id="jbmatchsummary">
    <h2 class="low"><?php echo JText::_('COM_JOBBOARD_LATESTJOBMATCHES') ?></h2>
    <?php $numjobs = !empty($this->matching_jobs)? count($this->matching_jobs) : 0 ?>
    <?php if($numjobs > 0) : ?>
      <?php foreach($this->matching_jobs as $job) : ?>
        <div class="big_link ">
          <a class="title" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=job&id='.$job['id'].'&Itemid='.$this->itemid) ?>"><?php echo $job['job_title'] ?></a>
            <br />
            <span class="keyhead"><?php echo JText::_('COM_JOBBOARD_KEYWDS') ?>:</span>
            <?php if($job['job_tags'] <> '') : ?>
                <?php $job_tags = explode(',', $job['job_tags']); ?>
                <?php $num_tags = count($job_tags) ?>
                <?php for($t=0; $t < $num_tags; $t++) :?>
                    <?php $jtag_link = 'index.php?option=com_jobboard&view=taglist&keysrch='.trim($job_tags[$t]).'&Itemid='.$this->itemid; ?>
                    <a href="<?php echo JRoute::_($jtag_link) ?>" class="keywd"><?php echo $job_tags[$t] ?></a>
                    <?php if($t < $num_tags-1) echo ',' ?>
                <?php endfor ?>
            <?php endif ?>
        </div>
      <?php endforeach ?>
    <?php else : ?>
      <div class="big_link ">
          <?php echo JText::_('COM_JOBBOARD_DASH_NOJOBSMATCH') ?>
      </div>
    <?php endif ?>
    <div class="big_link last">
      <a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=list&Itemid='.$this->itemid) ?>"><?php echo JText::_('COM_JOBBOARD_BROWSEJOBS') ?> &raquo;</a>
    </div>
  </div>
  <div class="ovsummary" id="jbapplsummary">
    <h2 class="low"><?php echo JText::_('COM_JOBBOARD_MYLATESTAPPLICATIONS') ?></h2>
    <?php if($numappls > 0) : ?>
        <?php foreach($this->user_applications as $appl) : ?>
          <div class="big_link first">
            <span class="jobcol">
              <span><?php echo JText::_('JOB_TITLE') ?></span>
              <br />
              <?php $job_title = (strlen($appl['job_title']) > 27) ? substr($appl['job_title'], 0, 27).'...' : $appl['job_title']; ?>
              <a class="jobbrdmodal" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=job&id='.$appl['job_id'].'&tmpl=component') ?>"><?php echo $job_title ?></a>
            </span>
            <span class="cvcol">
              <span><?php echo JText::_('COM_JOBBOARD_CVPROF') ?></span>
              <br />
              <?php $cv_profile = (strlen($appl['profile_name']) > 27) ? substr($appl['profile_name'], 0, 27).'...' : $appl['profile_name']; ?>
              <a class="jobbrdmodal" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=viewcv&profileid='.$appl['cvprof_id'].'&tmpl=component') ?>"><?php echo $cv_profile ?></a>
            </span>
            <span class="applcol">
              <span><?php echo JText::_('COM_JOBBOARD_APPLICATION_STATUS') ?></span>
              <br />
              <span class="applstatus"><?php if($this->show_status) echo ucfirst($appl['status_description']); else echo JText::_('COM_JOBBOARD_APLL_STATUS_RESTRICTED') ?></span>
            </span>
          </div>
      <?php endforeach ?>
      <div class="big_link last">
        <a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=appl&Itemid='.$this->itemid) ?>"><?php echo JText::_('COM_JOBBOARD_SHOWALLAPPLS') ?> &raquo;</a>
      </div>
    <?php else : ?>
      <div class="big_link ">
          <?php echo JText::_('COM_JOBBOARD_DASH_NOJAPPL') ?>
      </div>
    <?php endif ?>
</div>