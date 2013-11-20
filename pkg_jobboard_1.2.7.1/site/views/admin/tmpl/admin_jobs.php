<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
$document = & JFactory::getDocument();
$document->setTitle(JText::_('COM_JOBBOARD_ADMJOBS_TITLE'));
?>
<?php if(!$this->is_modal) : ?>
  <?php  $modal_params = array('handler'=> 'iframe', 'size' => array( 'x' => 640, 'y' => 480), 'sizeLoading'=>array( 'x' => 250, 'y' => 150), 'overlay'=>false, 'classWindow'=>'jobboardmodal'); ?>
  <?php JobBoardBehaviorHelper::modal('a.jobbrdmodal', $modal_params); ?>
<?php endif ?>
<?php JHTML::_('stylesheet', 'pagination.css', 'components/com_jobboard/css/') ?>

<div>
<br class="clear" />
<h2><?php echo JText::_('COM_JOBBOARD_ADMJOBS_TITLE') ?></h2>
  <span class="jbPagination"><?php echo $this->results_count; ?></span>
  <?php if(!empty($this->jmode) && intval($this->jmode <> '0')) : ?>
    <strong class="clear">
        <?php if($this->jmode == 'active') echo JText::_('COM_JOBBOARD_ADM_ACTIVE_JOBS') ?>
        <?php if($this->jmode == 'inactive') echo JText::_('COM_JOBBOARD_ADM_INACTIVE_JOBS') ?>
        <?php if($this->jmode == 'featured') echo JText::_('COM_JOBBOARD_ADM_FEATURED_JOBS') ?>
    </strong>&nbsp;&bull;&nbsp;
    <a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=jobs&jmode=0&Itemid='.$this->itemid) ?>"><?php echo JText::_('COM_JOBBOARD_ADM_ALLMYJOBS') ?></a>
  <?php endif ?>
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
                <small class="stardate grey"><?php echo JText::_('COM_JOBBOARD_ENT_ID') ?>: <strong><?php echo $row->id ?></strong> | <?php echo JText::_('COM_JOBBOARD_ENT_REF') ?>: <strong><?php echo $row->ref_num == null? JText::_('COM_JOBBOARD_ENT_NONE') : $row->ref_num ?></strong><?php if($row->featured ==1) : ?> | <span class="featured"><?php echo JText::_("COM_JOBBOARD_ENT_FEATURED") ?></span><?php endif ?></small>
            </span><br class="clear mtopminus10" />
              <ul class="row-actions">
                  <li>
                    <span class="grey">
                        <?php echo JText::_('POSTED') ?> : <?php echo JHTML::_('date', $row->post_date, $this->day_format.' '.$this->month_short_format.', '.$this->year_format) ?>
                    </span>
                  </li>
                  <li>
                      <span>
                          <?php echo JText::_('APPLICATIONS_FOR_THIS_POSITION') ?> : <strong><?php echo $row->num_applications ?></strong>
                      </span>
                  </li>
              </ul>
              <?php if($this->user_auth['manage_jobs'] == 1 || $this->user_auth['post_jobs'] == 1) : ?>
                <?php if($this->user_auth['manage_jobs'] == 1) : ?>
                  <a title="<?php echo JText::sprintf('COM_JOBBOARD_ENT_TOGGLE', JText::sprintf('COM_JOBBOARD_ENT_STATUS', '')) ?>" class="yesno<?php if($row->published == 1) echo ' yes' ?> right" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=jobstatus&jid='.$row->id.'&status='.$row->published.'&'.JUtility::getToken().'=1')?>" >&nbsp;</a>
                  <form  id="frmEd-<?php echo $incr ?>" method="post" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=edjob&jid='.$row->id.'&Itemid='.$this->itemid)?>" >
                        <input class="btn-grn right" type="submit" value="<?php echo JText::_('COM_JOBBOARD_EDIT') ?>" />
                        <input type="hidden" name="option" value="com_jobboard" />
                        <input type="hidden" name="view" value="admin" />
                        <input type="hidden" name="task" value="edjob" />
                        <input type="hidden" name="jid" value="<?php echo $row->id ?>" />
                        <input type="hidden" name="Itemid" value="<?php echo $this->itemid ?>" />
                        <?php echo JHTML::_('form.token'); ?>
                   </form>
                 <?php endif ?>
                 <?php if($this->user_auth['post_jobs'] == 1) : ?>
                   <form  id="frmDupl-<?php echo $incr ?>" method="post" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=clonejob&jid='.$row->id.'&Itemid='.$this->itemid)?>" >
                       <input class="btn-blk right" type="submit" value="<?php echo JText::_('COM_JOBBOARD_CLONE') ?>" />
                       <input type="hidden" name="option" value="com_jobboard" />
                        <input type="hidden" name="view" value="admin" />
                        <input type="hidden" name="task" value="clonejob" />
                        <input type="hidden" name="jid" value="<?php echo $row->id ?>" />
                        <input type="hidden" name="Itemid" value="<?php echo $this->itemid ?>" />
                        <?php echo JHTML::_('form.token'); ?>
                   </form>
                 <?php endif ?>
                 <?php if($this->user_auth['manage_jobs'] == 1) : ?>
                   <form  id="frmDel-<?php echo $incr ?>" method="post" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=deljob&jid='.$row->id.'&Itemid='.$this->itemid)?>" >
                       <input class="btn-red right" type="submit" value="<?php echo JText::_('COM_JOBBOARD_DELETE') ?>" />
                       <input type="hidden" name="option" value="com_jobboard" />
                        <input type="hidden" name="view" value="admin" />
                        <input type="hidden" name="task" value="deljob" />
                        <input type="hidden" name="jid" value="<?php echo $row->id ?>" />
                        <input type="hidden" name="Itemid" value="<?php echo $this->itemid ?>" />
                        <?php echo JHTML::_('form.token'); ?>
                   </form>
                 <?php endif ?>
               <?php else : ?>
                  <br class="clear" />
               <?php endif ?>
               <?php if($row->num_applications > 0 && $this->user_auth['manage_applicants'] == 1) : ?>
                 <form  id="frmAppl-<?php echo $incr ?>" method="post" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=appl&jid='.$row->id)?>" >
                    <input class="button left small10" type="submit" value="<?php echo JText::_('COM_JOBBOARD_VIEWAPPLS') ?>" />
                    <input type="hidden" name="option" value="com_jobboard" />
                    <input type="hidden" name="view" value="admin" />
                    <input type="hidden" name="task" value="appl" />
                    <input type="hidden" name="jid" value="<?php echo $row->id ?>" />
                    <input type="hidden" name="Itemid" value="<?php echo $this->itemid ?>" />
                    <?php echo JHTML::_('form.token'); ?>
                 </form>
              <?php endif ?>
        </li>
            <?php $incr += 1 ?>
        <?php endforeach ?>
    <?php endif ?>
  </ul>
  <?php if($numrows > 0) : ?>
    <div class="clear">&nbsp;</div>
    <span class="jbRescounter"><?php echo $this->results_count; ?></span>
    <div class="jbPagination"><?php echo $this->pagination->getPagesLinks() ?><!--  --></div>
    <div class="clear">&nbsp;</div>
  <?php endif ?>
</div>