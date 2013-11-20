<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
$document = & JFactory::getDocument();
?>
<?php $document->setTitle(JText::_('COM_JOBBOARD').': '.JText::_('COM_JOBBOARD_MARKED_JOBS')); ?>
<?php if(!$this->is_modal) : ?>
  <?php  $modal_params = array('handler'=> 'iframe', 'size' => array( 'x' => 640, 'y' => 480), 'sizeLoading'=>array( 'x' => 250, 'y' => 150), 'overlay'=>false, 'classWindow'=>'jobboardmodal'); ?>
  <?php JobBoardBehaviorHelper::modal('a.jobbrdmodal', $modal_params); ?>
<?php endif ?>
<?php JHTML::_('stylesheet', 'pagination.css', 'components/com_jobboard/css/') ?>

<div>
  <br class="clear" />
  <h2><?php echo JText::_('COM_JOBBOARD_MARKED_JOBS') ?></h2>
  <span class="jbPagination"><?php echo $this->results_count; ?></span>
  <ul>
   <?php $numrows = count($this->data) ?>
   <?php if($numrows < 1) : ?>
     <li><?php echo JText::_('COM_JOBBOARD_ENT_NONEFOUND') ?></li>
   <?php else : ?>
       <?php $incr = 0 ?>
       <?php foreach($this->data as $row) : ?>
        <li class="list-item">
            <span class="list-content">
              <strong><a class="jobbrdmodal" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=job&id='.$row->job_id.'&tmpl=component') ?>"><?php echo $row->job_title ?></a></strong>
              <span class="bkmrk starred">&nbsp;</span>
              <small class="stardate"><?php echo JHTML::_('date', $row->mark_date, $this->day_format.' '.$this->month_short_format.', '.$this->year_format) ?></small>
            </span>
              <ul class="row-actions">
                  <li>
                    <span>
                        <?php echo JText::_('POSTED') ?> : <?php echo JHTML::_('date', $row->post_date, $this->day_format.' '.$this->month_long_format.', '.$this->year_format) ?>
                    </span>
                  </li>
                  <?php if($row->job_tags  <> '') :  ?>
                    <?php $keywds = explode(',', $row->job_tags) ?>
                    <?php foreach($keywds as $keywd) : ?>
                      <li>
                        <span class="show-like">
                            <?php $jtag_link = 'index.php?option=com_jobboard&view=taglist&keysrch='.trim($keywd).'&Itemid='.$this->itemid; ?>
                            <a title="<?php echo $keywd ?>" href="<?php echo JRoute::_($jtag_link) ?>"><?php echo $keywd ?></a>
                        </span>
                      </li>
                    <?php endforeach ?>
                  <?php else : ?>
                      <li>
                         <?php echo JText::_('COM_JOBBOARD_JOBNOKEYS') ?>
                      </li>
                  <?php endif ?>
              </ul>
              <form id="frmDel_<?php echo $incr ?>" method="post" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=delfav&bid='.$row->id)?>" >
                <span class="right">
                     <input class="btn-red" type="submit" value="<?php echo JText::_('COM_JOBBOARD_LBLUNMARK') ?>" />
                     <input type="hidden" name="option" value="com_jobboard" />
                      <input type="hidden" name="view" value="user" />
                      <input type="hidden" name="task" value="delfav" />
                      <input type="hidden" name="bid" value="<?php echo $row->id ?>" />
                      <?php echo JHTML::_('form.token'); ?>
                </span>
              </form>
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