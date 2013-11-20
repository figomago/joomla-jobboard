<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
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
        <?php $has_applied = JobBoardInviteHelper::getApplId(array('uid'=>$row->user_id, 'jid'=>$row->job_id))> 0? true : false; ?>
        <li class="list-item">
            <span class="list-content">
                 <?php echo JText::_('JOB_TITLE') ?> : <a class="jobbrdmodal" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=job&id='.$row->job_id.'&tmpl=component') ?>"><?php echo $row->job_title ?></a>
                 <small class="stardate">
                   <?php if($has_responded || $has_applied) : ?>
                     <?php echo JText::sprintf('COM_JOBBOARD_ADM_INVITEE_APPLIED', JText::_('COM_JOBBOARD_ENT_USER')) ?>
                   <?php else : ?>
                     <?php echo JText::sprintf('COM_JOBBOARD_ADM_INVITEE_NO_RESP', JText::_('COM_JOBBOARD_ENT_USER')) ?>
                   <?php endif ?>
                 </small>
            </span>
              <ul class="row-actions">
                  <li>
                    <span>
                       <?php if($has_responded || $has_applied) : ?>
                          <?php $response_date = JobBoardInviteHelper::getResponseDate($row->user_id, $row->job_id, $row->qid) ?>
                          <?php echo JText::_('COM_JOBBOARD_JOBAPPLDATE') ?> : <?php echo JHTML::_('date', $response_date, $this->day_format.' '.$this->month_long_format.', '.$this->year_format) ?>
                       <?php else : ?>
                         <?php echo JText::_('COM_JOBBOARD_ADM_INVITE_DATE') ?> : <?php echo JHTML::_('date', $row->create_date, $this->day_format.' '.$this->month_long_format.', '.$this->year_format) ?> <?php echo JText::_('COM_JOBBOARD_ENT_SENT_TO') ?> <strong><?php echo $row->invitee ?></strong>
                       <?php endif ?>
                    </span>
                  </li>
                  <li>
                    <span>
                       <?php $cv_title = JobBoardInviteHelper::getInviteCV($row->id) ?>
                       <?php echo JText::_('CV_RESUME') ?> :
                       <?php if(!empty($cv_title)) :?>
                         <a class="jobbrdmodal" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=viewcv&pid='.$row->cvprof_id.'&sid='.$row->user_id.'&jid=0&s_mode=1&Itemid='.$this->itemid.'&tmpl=component')?>"><?php echo $cv_title ?></a>
                       <?php else : ?>
                         <span><?php echo JText::_('COM_JOBBOARD_ADM_INVITE_NOCV') ?></span>
                       <?php endif ?>
                    </span>
                  </li>
              </ul>
              <?php if(!$has_responded && !empty($cv_title)) : ?>
                <form id="frmResend_<?php echo $incr ?>" method="post" action="<?php  echo JRoute::_('index.php?option=com_jobboard&view=admin&task=invresend&Itemid='.$this->itemid)?>" >
                  <span class="right">
                        <input class="btn-grn" type="submit" value="<?php echo ucfirst(JText::_('COM_JOBBOARD_INVITE_RESEND')) ?>" />
                        <input type="hidden" name="option" value="com_jobboard" />
                        <input type="hidden" name="view" value="admin" />
                        <input type="hidden" name="task" value="invresend" />
                        <input type="hidden" name="jid" value="<?php echo $row->job_id ?>" />
                        <input type="hidden" name="cpid" value="<?php echo $row->cvprof_id ?>" />
                        <input type="hidden" name="sid" value="<?php echo $row->user_id ?>" />
                        <input type="hidden" name="p_mode" value="1" />
                        <?php echo JHTML::_('form.token'); ?>
                  </span>
                </form>
              <?php elseif(!empty($cv_title)) : ?>
              <?php if($this->user_auth['manage_applicants'] == 1) : ?>
                  <?php $invite_params = array('uid'=>$row->user_id , 'jid'=>$row->job_id, 'cpid'=>$row->cvprof_id) ?>
                  <?php $aid = JobBoardInviteHelper::getApplId($invite_params) ?>
                  <form id="frmEdAppl_<?php echo $incr ?>" method="post" action="<?php  echo JRoute::_('index.php?option=com_jobboard&view=admin&task=edappl&aid='.$aid.'&pid='.$row->cvprof_id.'&Itemid='.$this->itemid)?>" >
                    <span class="right">
                          <input class="btn-blk" type="submit" value="<?php echo ucfirst(JText::_('COM_JOBBOARD_APPLEDIT')) ?>" />
                          <input type="hidden" name="option" value="com_jobboard" />
                          <input type="hidden" name="view" value="admin" />
                          <input type="hidden" name="task" value="edappl" />
                          <input type="hidden" name="aid" value="<?php echo $aid ?>" />
                          <input type="hidden" name="jid" value="<?php echo $row->job_id ?>" />
                          <input type="hidden" name="cpid" value="<?php echo $row->cvprof_id ?>" />
                          <input type="hidden" name="sid" value="<?php echo $row->user_id ?>" />
                          <input type="hidden" name="qid" value="<?php echo $row->qid ?>" />
                          <input type="hidden" name="s_context" value="user" />
                          <?php echo JHTML::_('form.token'); ?>
                    </span>
                  </form>
                <?php else : ?>
                    <span class="right small"><?php echo JText::_('COM_JOBBOARD_ADM_FEATURE_NOAUTH') ?></span>
                <?php endif ?>
              <?php endif ?>
        </li>
            <?php $incr += 1 ?>
        <?php endforeach ?>
    <?php endif ?>
  </ul>
  <?php if($numrows > 0) : ?>
    <div class="clear">&nbsp;</div>
    <span class="jbRescounter"><?php echo $this->results_count; ?></span><span class="jbPagination"><?php echo $this->pagination->getPagesLinks() ?></span>
    <div class="clear">&nbsp;</div>
  <?php endif ?>
</div>
