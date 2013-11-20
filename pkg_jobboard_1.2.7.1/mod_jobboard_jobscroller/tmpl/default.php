<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */
?>
<?php if($show_stopstart == 1) $view_limit += 40; ?>
<div id="JobsTicker" style="height: <?php echo $view_limit+31 ?>px !important">
  <?php if($show_stopstart == 1) :?>
    <div id="controller">
          <div id="stop_scroll_cont">
          <a id="stop_scroll"><img alt="<?php echo JText::_('MOD_JOBBOARD_JOBSCROLLER_STOP_SCROLL') ?>" src="<?php echo JURI::base() ?>modules/mod_jobboard_jobscroller/img/stop.png" width="14" height="14" /></a>&nbsp;&nbsp;<?php echo JText::_('MOD_JOBBOARD_JOBSCROLLER_STOP_SCROLL') ?>
          </div>   
          <div id="start_scroll_cont">
          <a id="start_scroll"><img alt="<?php echo JText::_('MOD_JOBBOARD_JOBSCROLLER_START_SCROLL') ?>" src="<?php echo JURI::base() ?>modules/mod_jobboard_jobscroller/img/play.png" width="14" height="14" /></a>&nbsp;&nbsp;<?php echo JText::_('MOD_JOBBOARD_JOBSCROLLER_START_SCROLL') ?>
          </div>
     </div>
   <?php endif; ?>
  <div id="scrollerWrapper">
    <ul id="ScrollerVertical">
        <?php foreach ($scroll_jobs as $job) : ?>
          <?php $link = JRoute::_('index.php?option=com_jobboard&view=job&id='.$job->id); ?>
              <li><a href="<?php echo JRoute::_($link); ?>"><?php echo $job->job_title; if($use_location == true) echo ' &mdash; '.$job->city; ?></a></li>
        <?php endforeach; ?>
    </ul>
  </div>
</div>
<?php $jobs_link = JRoute::_('index.php?option=com_jobboard&view=list&limitstart=0'); ?>
<p class="jlink"><a href="<?php echo JRoute::_($jobs_link); ?>"><?php echo JText::_('MOD_JOBBOARD_JOBSCROLLER_VIEW_LIST'); ?>&nbsp;<strong>&rarr;</strong></a></p>
<br class="clear" />