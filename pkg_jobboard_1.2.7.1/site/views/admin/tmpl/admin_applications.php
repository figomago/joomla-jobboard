<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
$document = & JFactory::getDocument();
$document->setTitle(JText::_('COM_JOBBOARD_JOBAPPLICATIONS').' : '.$this->job_title);
?>
<?php if(!$this->is_modal) : ?>
  <?php  $modal_params = array('handler'=> 'iframe', 'size' => array( 'x' => 640, 'y' => 480), 'sizeLoading'=>array( 'x' => 250, 'y' => 150), 'overlay'=>false, 'classWindow'=>'jobboardmodal'); ?>
  <?php JobBoardBehaviorHelper::modal('a.jobbrdmodal', $modal_params); ?>
<?php endif ?>
<?php JHTML::_('stylesheet', 'pagination.css', 'components/com_jobboard/css/') ?>
<div>
<br class="clear" />
<span class="jbPagination"><?php echo $this->results_count; ?></span>
<?php if($this->s_context == 'user') :?>
    <h2><?php echo JText::_('COM_JOBBOARD_RAPPLICATIONSFORJOB') ?></h2>
<?php endif ?>
<?php if($this->s_context == 'site') :?>
    <h2><?php echo JText::_('COM_JOBBOARD_SAPPLICATIONSFORJOB') ?></h2>
<?php endif ?>
<form id="frmRetrn" method="post" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=jobs')?>" >
         <input class="btn-rnd right mtopminus10" type="submit" value="<?php echo '&larr;&nbsp;'.JText::_('COM_JOBBOARD_ADM_BACK') ?>" />
         <input type="hidden" name="option" value="com_jobboard" />
          <input type="hidden" name="view" value="admin" />
          <input type="hidden" name="task" value="jobs" />
          <input type="hidden" name="Itemid" value="<?php echo $this->itemid ?>" />
          <?php echo JHTML::_('form.token'); ?>
</form>
<span><?php echo JText::_('JOB_TITLE') ?> : <a class="jobbrdmodal"  href="<?php echo JRoute::_('index.php?option=com_jobboard&view=job&id='.$this->jid.'&tmpl=component') ?>"><strong><?php  echo $this->job_title ?></strong></a></span>

   <?php if($this->s_context == 'user') :?>
     <?php $numrows = count($this->data) ?>
     <?php if($numrows < 1) : ?>
       <p><?php echo JText::_('COM_JOBBOARD_ENT_NONEFOUND') ?></p>
     <?php else : ?>
         <?php if($this->current_appls['site_appls'] > 0 && !JobBoardApplHelper::jobDisabled($this->jid)) :?>
            <form id="frmAppl" method="post" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=appl&jid='.$this->jid)?>" >
               <input class="btn-blk right mtop5" type="submit" value="<?php echo JText::_('COM_JOBBOARD_VIEW_SAPPLS') ?>" />
               <input type="hidden" name="option" value="com_jobboard" />
                <input type="hidden" name="view" value="admin" />
                <input type="hidden" name="task" value="appl" />
                <input type="hidden" name="s_context" value="site" />
                <input type="hidden" name="jid" value="<?php echo $this->jid ?>" />
                <input type="hidden" name="Itemid" value="<?php echo $this->itemid ?>" />
                <?php echo JHTML::_('form.token'); ?>
           </form>
         <?php endif ?>
         <?php $incr = 0 ?>
         <ul>
         <?php foreach($this->data as $row) : ?>
          <li class="list-item">
              <span class="list-content">
                   <small class="stardate"><?php echo JText::sprintf('COM_JOBBOARD_ENT_STATUS', JText::_('COM_JOBBOARD_JOBAPPLICATION')) ?> : <strong><?php echo $row->status_description ?></strong></small>
              </span>
                <ul class="row-actions">
                    <li>
                      <span>
                          <?php echo JText::_('COM_JOBBOARD_JOBAPPLDATE') ?> : <?php echo JHTML::_('date', $row->applied_on, $this->day_format.' '.$this->month_long_format.', '.$this->year_format) ?>
                      </span>
                    </li>
                    <li>
                      <span>
                         <?php $cv_title = JobBoardApplHelper::getApplCV($row->id)  ?>
                         <?php echo JText::_('CV_RESUME') ?> :
                         <?php if(!empty($cv_title)) :?>
                           <a class="jobbrdmodal" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=viewcv&pid='.$row->cvprof_id.'&sid='.$row->user_id.'&jid='.$this->jid.'&tmpl=component') ?>">
                              <?php echo $cv_title ?>
                           </a>
                         <?php else : ?>
                           <span><?php echo JText::_('COM_JOBBOARD_ADM_INVITE_NOCV') ?></span>
                         <?php endif ?>
                      </span>
                    </li>
                </ul>
                  <?php if($job_disabled = JobBoardApplHelper::jobDisabled($this->jid)) ?>
                  <?php if(!$job_disabled && !empty($cv_title))  : ?>
                    <form id="frmEd-<?php echo $incr ?>" method="post" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=edappl&aid='.$row->id.'&pid='.$row->cvprof_id)?>" >
                         <input class="btn-grn right clrright" type="submit" value="<?php echo JText::_('COM_JOBBOARD_APPLEDIT') ?>" />
                         <input type="hidden" name="option" value="com_jobboard" />
                          <input type="hidden" name="view" value="admin" />
                          <input type="hidden" name="task" value="edappl" />
                          <input type="hidden" name="s_context" value="<?php echo $this->s_context ?>" />
                          <input type="hidden" name="aid" value="<?php echo $row->id ?>" />
                          <input type="hidden" name="pid" value="<?php echo $row->cvprof_id ?>" />
                          <input type="hidden" name="sid" value="<?php echo $row->user_id ?>" />
                          <input type="hidden" name="qid" value="<?php echo $row->qid ?>" />
                          <input type="hidden" name="jid" value="<?php echo $this->jid ?>" />
                          <?php echo JHTML::_('form.token'); ?>
                     </form>
                  <?php else : ?>
                      <?php if($job_disabled) : ?>
                        <span class="right clrright small grey"><?php echo JText::_('COM_JOBBOARD_APPLS_JOB_DISABLED') ?></span>
                      <?php endif ?>
                  <?php endif ?>
          </li>
              <?php $incr += 1 ?>
          <?php endforeach ?>
          </ul>
      <?php endif ?>
    <?php endif ?>
    <?php if($this->s_context == 'site') :?>
     <?php $numrows = count($this->data) ?>
     <?php if($numrows < 1) : ?>
       <p><?php echo JText::_('COM_JOBBOARD_ENT_NONEFOUND') ?></p>
     <?php else : ?>
         <?php if($this->current_appls['user_appls'] > 0) :?>
            <form  id="frmAppl" method="post" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=appl&jid='.$this->jid)?>" >
              <span class="right clear">
                   <input class="btn-blk right mtop5" type="submit" value="<?php echo JText::_('COM_JOBBOARD_VIEW_RAPPLS') ?>" />
                   <input type="hidden" name="option" value="com_jobboard" />
                    <input type="hidden" name="view" value="admin" />
                    <input type="hidden" name="task" value="appl" />
                    <input type="hidden" name="s_context" value="user" />
                    <input type="hidden" name="jid" value="<?php echo $this->jid ?>" />
                    <input type="hidden" name="Itemid" value="<?php echo $this->itemid ?>" />
                    <?php echo JHTML::_('form.token'); ?>
              </span>
            </form>
         <?php endif ?>
         <?php $incr = 0 ?>
         <ul>
         <?php foreach($this->data as $row) : ?>
          <li class="list-item">
              <span class="list-content">
                   <?php echo JText::_('COM_JOBBOARD_APPLICANT_NAME') ?> : <strong><?php echo $row->first_name.' '.$row->last_name ?></strong>
                   <small class="stardate"><?php echo JText::sprintf('COM_JOBBOARD_ENT_STATUS', JText::_('COM_JOBBOARD_JOBAPPLICATION')) ?> : <strong><?php echo $row->status_description ?></strong></small>
                   <span class="applinfo"><?php echo '(email: '.$row->email.' | tel: '.$row->tel.')' ?></span>   
              </span>
                <ul class="row-actions">
                    <li>
                      <span class="grey">
                          <?php echo JText::_('COM_JOBBOARD_JOBAPPLDATE') ?> : <?php echo JHTML::_('date', $row->request_date, $this->day_format.' '.$this->month_long_format.', '.$this->year_format) ?>
                      </span>
                    </li>
                    <li>
                      <span>
                         <?php echo JText::_('CV_RESUME') ?> : <a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=getcvfile&file='.$row->id.'&'.JUtility::getToken().'=1') ?>"><?php echo $row->title ?></a>
                      </span>
                    </li>
                </ul>
          <form id="frmEd-<?php echo $incr ?>" method="post" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=edappl&aid='.$row->id)?>" >
              <span class="right">
                     <input class="btn-grn" type="submit" value="<?php echo JText::_('COM_JOBBOARD_APPLEDIT') ?>" />
                     <input type="hidden" name="option" value="com_jobboard" />
                      <input type="hidden" name="view" value="admin" />
                      <input type="hidden" name="task" value="edappl" />
                      <input type="hidden" name="s_context" value="<?php echo $this->s_context ?>" />
                      <input type="hidden" name="aid" value="<?php echo $row->id ?>" />
                      <input type="hidden" name="jid" value="<?php echo $this->jid ?>" />
                      <?php echo JHTML::_('form.token'); ?>
              </span>
          </form>
              <?php $incr += 1 ?>
          </li>
          <?php endforeach ?>
          </ul>
      <?php endif ?>
    <?php endif ?>

  <?php if($numrows > 0) : ?>
    <div class="clear">&nbsp;</div>
    <span class="jbRescounter"><?php echo $this->results_count; ?></span><span class="jbPagination"><?php echo $this->pagination->getPagesLinks() ?><!--  --></span>
    <div class="clear">&nbsp;</div>
  <?php endif ?>
</div>                                                               