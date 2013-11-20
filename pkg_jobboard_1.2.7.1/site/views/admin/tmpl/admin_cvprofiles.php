<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');

?>
<?php if(!$this->is_modal) : ?>
  <?php  $modal_params = array('handler'=> 'iframe', 'size' => array( 'x' => 640, 'y' => 480), 'sizeLoading'=>array( 'x' => 250, 'y' => 150), 'overlay'=>false, 'classWindow'=>'jobboardmodal'); ?>
  <?php JobBoardBehaviorHelper::modal('a.jobbrdmodal', $modal_params); ?>
<?php endif ?>
<?php JHTML::_('stylesheet', 'pagination.css', 'components/com_jobboard/css/') ?>
<div id="cvprofs">
<br class="clear" />
<h2><?php echo JText::_('COM_JOBBOARD_CVPROFS'); ?></h2>
<ul id="active_projects_rows" class="manage edit_links with_header projects index_field">
    <?php if(count($this->profiles > 0)) : ?>
        <?php $incr = 1; ?>
        <?php foreach($this->profiles as $profile) : ?>
          <li class="line">
            <ul class="row">
                <li class="buttons">
                  <div class="btn_container">
                    <form id="frmEdit[<?php echo $incr ?>]" method="post" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=viewcv&profileid='.$profile->id)?>" >
                        <span class="btn btn-small"><input class="pedit" type="submit" value="<?php echo JText::_('COM_JOBBOARD_VIEW').'/'.JText::_('COM_JOBBOARD_EDIT') ?>" /></span>
                       <input type="hidden" name="option" value="com_jobboard" />
                        <input type="hidden" name="view" value="user" />
                        <input type="hidden" name="task" value="viewcv" />
                        <input type="hidden" name="profileid" value="<?php echo $profile->id ?>" />
                        <input type="hidden" name="emode" value="1" />
                        <input type="hidden" name="getdata" value="1" />
                        <?php echo JHTML::_('form.token'); ?>
                    </form>
                  </div>
                </li>
                <li class="center">
                  <a class="jobbrdmodal" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=viewcv&profileid='.$profile->id.'&tmpl=component') ?>" class="link <?php if($profile->is_linkedin == 1) echo 'linkedin' ?>">
                    <?php echo $profile->profile_name ?>
                    <div class="meta_info">
                      <?php echo JText::_('COM_JOBBOARD_CVPROF_CREATEDON') ?> <strong><?php echo JHTML::_('date', $profile->created_date, $this->day_format.' '.$this->month_long_format.', '.$this->year_format) ?></strong> | <?php echo JText::_('COM_JOBBOARD_CVPROF_MODON') ?> <strong><?php echo JHTML::_('date', $profile->modified_date, $this->day_format.' '.$this->month_short_format.', '.$this->year_format) ?></strong> | <?php echo JText::_('COM_JOBBOARD_AVAILSTART') ?> <strong><?php echo JHTML::_('date', $profile->avail_date, $this->day_format.' '.$this->month_short_format.', '.$this->year_format) ?></strong>
                    </div>
                  </a>
                </li>
                <li class="buttons">
                  <div class="floatr btn_container">
                    <form id="frmDel[<?php echo $incr ?>]" method="post" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=delcv&profileid='.$profile->id )?>" >
                       <input class="btn-red" type="submit" value="<?php echo JText::_('COM_JOBBOARD_DELETE') ?>" />
                       <input type="hidden" name="option" value="com_jobboard" />
                        <input type="hidden" name="view" value="user" />
                        <input type="hidden" name="task" value="delcv" />
                        <input type="hidden" name="profileid" value="<?php echo $profile->id ?>" />
                        <input type="hidden" name="emode" value="1" />
                        <input type="hidden" name="getdata" value="0" />
                        <input type="hidden" name="islinkedin" value="<?php echo $profile->is_linkedin ?>" />
                        <?php echo JHTML::_('form.token'); ?>
                    </form>
                        <span class="btn btn-small-r"><a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=clonecv&profileid='.$profile->id.'&'.JUtility::getToken().'=1') ?>" class="" ><?php echo JText::_('COM_JOBBOARD_CLONE') ?></a></span>
                  </div>
                </li>
            </ul>
          </li>
          <?php $incr += 1 ?>
        <?php endforeach ?>
    <?php endif ?>
</ul>
</div>
