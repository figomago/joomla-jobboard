<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
$document = & JFactory::getDocument();
$document->setTitle(JText::_('COM_JOBBOARD_MYINVITES'));
?>
<?php if(!$this->is_modal) : ?>
  <?php  $modal_params = array('handler'=> 'iframe', 'size' => array( 'x' => 640, 'y' => 480), 'sizeLoading'=>array( 'x' => 250, 'y' => 150), 'overlay'=>false, 'classWindow'=>'jobboardmodal'); ?>
  <?php JobBoardBehaviorHelper::modal('a.jobbrdmodal', $modal_params); ?>
<?php endif ?>
<?php JHTML::_('stylesheet', 'pagination.css', 'components/com_jobboard/css/') ?>

<div>
<br class="clear" />
<h2><?php echo JText::_('COM_JOBBOARD_MYINVITES') ?></h2>
<span class="jbPagination"><?php echo $this->results_count; ?></span>
  <ul>
   <?php $numrows = count($this->data) ?>
   <?php if($numrows < 1) : ?>
     <li><?php echo JText::_('COM_JOBBOARD_ENT_NONEFOUND') ?></li>
   <?php else : ?>
       <?php $incr = 0 ?>
       <?php foreach($this->data as $row) : ?>
        <?php $has_responded = JobBoardInviteHelper::hasResponded($row->user_id, $row->job_id); ?>
        <li class="list-item">
            <span class="list-content">
                 <?php echo JText::_('JOB_TITLE') ?> : <a class="jobbrdmodal" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=job&id='.$row->job_id.'&tmpl=component') ?>"><?php echo $row->job_title ?></a>
                 <small class="stardate">
                   <?php if($has_responded) : ?>
                     <?php echo JText::_('COM_JOBBOARD_ALREADYAPPLIED') ?>
                   <?php else : ?>
                     <?php echo JText::sprintf('COM_JOBBOARD_INVITED_BY', $row->inviter) ?>
                   <?php endif ?>
                 </small>
            </span>
              <ul class="row-actions">
                  <li>
                    <span>
                         <?php if($has_responded) : ?>
                            <?php $response_date = JobBoardInviteHelper::getResponseDate($row->user_id, $row->job_id, $row->qid) ?>
                            <?php echo JText::_('COM_JOBBOARD_JOBAPPLDATE') ?> : <?php echo JHTML::_('date', $response_date, $this->day_format.' '.$this->month_long_format.', '.$this->year_format) ?>
                         <?php else : ?>
                           <?php echo JText::_('COM_JOBBOARD_INVITE_DATE') ?> : <?php echo JHTML::_('date', $row->create_date, $this->day_format.' '.$this->month_long_format.', '.$this->year_format) ?>
                         <?php endif ?>
                    </span>
                  </li>
                  <li>
                    <span>
                       <?php $cv_title = JobBoardInviteHelper::getInviteCV($row->id)  ?>
                       <?php echo JText::_('CV_RESUME') ?> :
                       <?php if(!empty($cv_title)) :?>
                          <a class="jobbrdmodal" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=viewcv&profileid='.$row->cvprof_id.'&tmpl=component')?>"><?php echo $cv_title ?></a>
                       <?php else : ?>
                         <span><?php echo JText::_('COM_JOBBOARD_ADM_INVITE_NOCV') ?></span>
                       <?php endif ?>
                    </span>
                  </li>
              </ul>
              <?php if(!$has_responded) : ?>
                <form id="frmApply[<?php echo $incr ?>]" method="post" action="<?php  echo JRoute::_('index.php?option=com_jobboard&view=user&task=apply')?>" >
                  <span class="right">
                        <input class="btn-grn" type="submit" value="<?php echo ucfirst(JText::_('COM_JOBBOARD_APPLY_FOR_JOB')) ?>" />
                        <input type="hidden" name="option" value="com_jobboard" />
                        <input type="hidden" name="view" value="user" />
                        <input type="hidden" name="task" value="apply" />
                        <input type="hidden" name="jid" value="<?php echo $row->job_id ?>" />
                        <input type="hidden" name="cat_id" value="<?php echo $this->cat_id ?>" />
                        <input type="hidden" name="cpid" value="<?php echo $row->cvprof_id ?>" />
                        <input type="hidden" name="qid" value="<?php echo $row->qid ?>" />
                        <input type="hidden" name="p_mode" value="1" />
                        <?php echo JHTML::_('form.token'); ?>
                  </span>
                </form>
              <?php endif ?>
        </li>
            <?php $incr += 1 ?>
        <?php endforeach ?>
    <?php endif ?>
  </ul>
  <?php if($numrows > 0) : ?>
    <div class="clear">&nbsp;</div>
    <span class="jbRescounter"><?php echo $this->results_count; ?></span><span class="jbPagination"><?php echo $this->pagination->getPagesLinks() ?><!--  --></span>
    <div class="clear">&nbsp;</div>
  <?php endif ?>
</div>
<?php // echo '<pre>'.print_r($this->data, true).'</pre>'; ?>
