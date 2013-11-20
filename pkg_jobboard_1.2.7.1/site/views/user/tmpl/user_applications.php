<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
$document = & JFactory::getDocument();
$document->setTitle(JText::_('COM_JOBBOARD').': '.JText::_('COM_JOBBOARD_MYJOBAPPLICATIONS'));
?>
<?php if(!$this->is_modal) : ?>
  <?php  $modal_params = array('handler'=> 'iframe', 'size' => array( 'x' => 640, 'y' => 480), 'sizeLoading'=>array( 'x' => 250, 'y' => 150), 'overlay'=>false, 'classWindow'=>'jobboardmodal'); ?>
  <?php JobBoardBehaviorHelper::modal('a.jobbrdmodal', $modal_params); ?>
<?php endif ?>
<?php JHTML::_('stylesheet', 'pagination.css', 'components/com_jobboard/css/') ?>

<div>
<br class="clear" />
<h2><?php echo JText::_('COM_JOBBOARD_JOBAPPLICATIONS') ?></h2>
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
                 <?php echo JText::_('JOB_TITLE') ?> : <a class="jobbrdmodal" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=job&id='.$row->job_id.'&tmpl=component') ?>"><?php echo $row->job_title ?></a>
                 <small class="stardate">
                    <?php echo JText::sprintf('COM_JOBBOARD_ENT_STATUS', JText::_('COM_JOBBOARD_JOBAPPLICATION')) ?> :
                    <strong><?php if($this->show_status) echo $row->status_description; else echo JText::_('COM_JOBBOARD_APLL_STATUS_RESTRICTED') ?></strong>
                 </small>
            </span>
              <ul class="row-actions">
                  <li>
                    <span>
                        <?php echo JText::_('COM_JOBBOARD_JOBAPPLDATE') ?> : <?php echo JHTML::_('date', $row->applied_on, $this->day_format.' '.$this->month_long_format.', '.$this->year_format) ?>
                    </span>
                  </li>
                  <li>
                    <span>
                       <?php echo JText::_('CV_RESUME') ?> : <a class="jobbrdmodal" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=viewcv&profileid='.$row->cvprof_id.'&tmpl=component')?>"><?php echo $row->profile_name ?></a>
                    </span>
                  </li>
              </ul>
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