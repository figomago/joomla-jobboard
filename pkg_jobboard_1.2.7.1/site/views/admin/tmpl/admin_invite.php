<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
$document = & JFactory::getDocument();
$document->setTitle(JText::_('COM_JOBBOARD_INVITE_HEADING').': '.$this->candidate_name);
?>
<?php if(!$this->is_modal) : ?>
  <?php  $modal_params = array('handler'=> 'iframe', 'size' => array( 'x' => 640, 'y' => 480), 'sizeLoading'=>array( 'x' => 250, 'y' => 150), 'overlay'=>false, 'classWindow'=>'jobboardmodal'); ?>
  <?php JobBoardBehaviorHelper::modal('a.jobbrdmodal', $modal_params); ?>
<?php endif ?>
<?php JHTML::_('stylesheet', 'pagination.css', 'components/com_jobboard/css/') ?>

<div>
  <br class="clear" />
  <div class="left mleft50">
    <span class="left"><strong><?php echo JText::_('COM_JOBBOARD_TXTINVITE') ?> <a class="jobbrdmodal" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=viewcv&pid='.$this->cpid.'&sid='.$this->sid.'&s_mode=1&Itemid='.$this->itemid.'&tmpl=component') ?>"><?php echo $this->candidate_name ?></a></strong>:&nbsp;&nbsp;<?php echo JText::_('COM_JOBBOARD_INVITE_MESSAGE') ?></span>
    <?php if($this->prof['is_profile_pic'] == true) : ?>
        <?php $randomiser = '?'.rand(1,2500) ?>
        <img class="left mtop10 clearleft" src="<?php echo $this->prof['imgthumb'].$randomiser ?>" alt="<?php echo $this->candidate_name ?>" />
    <?php else : ?>
         <img class="left mtop10 clearleft" src="components/com_jobboard/images/user_default.jpg" alt="<?php echo $this->candidate_name ?>" />
    <?php endif ?>
    <textarea class="left noclear mleft10" rows="2" cols="20" id="message" name="message">&nbsp;</textarea>
  </div>
  <span class="jbPagination clear"><strong><?php echo JText::_('COM_JOBBOARD_ADM_INVITE_SELECT_JOB') ?> | </strong><?php echo $this->results_count; ?></span>
  <ul>
   <?php $numrows = count($this->data) ?>
   <?php if($numrows < 1) : ?>
     <li><?php echo JText::_('COM_JOBBOARD_ENT_NOJOBS') ?></li>
   <?php else : ?>
       <?php $incr = 0 ?>
       <?php foreach($this->data as $row) : ?>
        <li class="list-item">
            <span class="list-content">
                <?php echo JText::_('JOB_TITLE') ?> : <a class="jobbrdmodal bold" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=job&id='.$row->id.'&tmpl=component') ?>"><?php echo $row->job_title ?> <?php if($this->use_location == 1) {$location = ($row->city == '')? JText::_("WORK_ANYWHERE") : $row->city; echo ' <small class="fnrml">('.$location.')</small>';} ?></a>
                <small class="stardate grey"><?php echo JText::_('COM_JOBBOARD_ENT_ID') ?>: <strong><?php echo $row->id ?></strong> | <?php echo JText::_('COM_JOBBOARD_ENT_REF') ?>: <strong><?php echo $row->ref_num == null? JText::_('COM_JOBBOARD_ENT_NONE') : $row->ref_num ?></strong></small>
            </span>
              <ul class="row-actions">
                  <li>
                    <span class="grey">
                        <?php echo JText::_('POSTED') ?> : <?php echo JHTML::_('date', $row->post_date, $this->day_format.' '.$this->month_short_format.', '.$this->year_format) ?>
                    </span>
                  </li>
              </ul>
              <?php $has_applied = JobBoardInviteHelper::getApplId(array('uid'=>$this->sid, 'jid'=>$row->id))> 0? true : false; ?>              
              <?php if(!JobBoardInviteHelper::hasInvite($this->sid, $row->id) && !$has_applied) : ?>
                <form  id="frmInvite_<?php echo $incr ?>" class="frmInvite" method="post" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=invite&jid='.$row->id.'&Itemid='.$this->itemid)?>" >
                      <input class="btn-grn right" type="submit" name="submit" value="<?php echo JText::_('COM_JOBBOARD_TXTINVITE').' '.$this->candidate_name ?>" />
                      <input type="hidden" name="option" value="com_jobboard" />
                      <input type="hidden" name="view" value="admin" />
                      <input type="hidden" name="task" value="invite" />
                      <input type="hidden" name="jid" value="<?php echo $row->id ?>" />
                      <input type="hidden" name="sid" value="<?php echo $this->sid ?>" />
                      <input type="hidden" name="cpid" value="<?php echo $this->cpid ?>" />
                      <input type="hidden" name="message" value="" />
                      <input type="hidden" name="Itemid" value="<?php echo $this->itemid ?>" />
                      <?php echo JHTML::_('form.token'); ?>
                 </form>
              <?php elseif($has_applied) : ?>
                <span class="right small"><?php echo JText::sprintf('COM_JOBBOARD_ADM_INVITEE_APPLIED', $this->candidate_name) ?></span>
              <?php else : ?>
                <span class="right small"><?php echo JText::sprintf('COM_JOBBOARD_INVITE_EXISTS', $this->candidate_name) ?></span>
              <?php endif ?>
        </li>
            <?php $incr += 1 ?>
        <?php endforeach ?>
    <?php endif ?>
  </ul>
  <?php if($numrows > 0) : ?>
    <div class="clear">&nbsp;</div>
    <span class="jbRescounter"><!--  --><?php echo $this->results_count; ?></span><span class="jbPagination"><!--  --><?php echo $this->pagination->getPagesLinks() ?></span>
    <div class="clear">&nbsp;</div>
  <?php endif ?>
</div>
<script type="text/javascript">
  window.addEvent('domready', function() {
      $$('.frmInvite').each(function(invite){
           invite.elements['submit'].addEvent('click', function(e){
                invite.elements['message'].value = $('message').value;
           });
      });
  });
</script>