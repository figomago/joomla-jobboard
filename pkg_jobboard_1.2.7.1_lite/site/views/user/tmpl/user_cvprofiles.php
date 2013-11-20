<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
$document = &JFactory::getDocument();
$document->setTitle(JText::_('COM_JOBBOARD_CVPROFS'));
?>
<?php JHTML::_('stylesheet', 'pagination.css', 'components/com_jobboard/css/') ?>
<div id="cvprofs">
<br class="clear" />
<h2><?php echo JText::_('COM_JOBBOARD_CVPROFS'); ?></h2>
<span class="jbPagination"><?php echo $this->results_count; ?></span>
<ul class="edit_links">
   <?php $numrows = count($this->profiles) ?>
   <?php if($numrows > 0) : ?>
        <?php $incr = 1; ?>
        <?php foreach($this->profiles as $profile) : ?>
          <li class="line">
            <ul class="row">
                <li class="buttons">
                  <div class="btn_container">
                    <form id="frmDel_<?php echo $incr ?>" method="post" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=delcv&profileid='.$profile->id )?>" >
                        <span class="btn btn-small"><input class="btn-red" type="submit" value="<?php echo JText::_('COM_JOBBOARD_DELETE') ?>" /></span>
                        <input type="hidden" name="option" value="com_jobboard" />
                        <input type="hidden" name="view" value="user" />
                        <input type="hidden" name="task" value="delcv" />
                        <input type="hidden" name="profileid" value="<?php echo $profile->id ?>" />
                        <input type="hidden" name="emode" value="1" />
                        <input type="hidden" name="getdata" value="0" />
                        <input type="hidden" name="islinkedin" value="<?php echo $profile->is_linkedin ?>" />
                        <?php echo JHTML::_('form.token'); ?>
                    </form>
                  </div>
                </li>
                <li class="center">
                  <a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=viewcv&profileid='.$profile->id) ?>" class="link <?php if($profile->is_linkedin == 1) echo 'linkedin' ?>" title="<?php echo ($profile->is_private)? JText::_('COM_JOBBOARD_CVP_NOT_SEARCHABLE') : ''; ?>">
                    <?php if($profile->is_private) : ?>
                        <span class="private"><?php echo $profile->profile_name ?></span>
                    <?php else : ?>
                        <?php echo $profile->profile_name ?>
                    <?php endif ?>
                    <span class="meta_info">
                      <?php if($profile->profile_hits > 0) : ?><?php echo JText::_('COM_JOBBOARD_ENT_VIEWS') ?> <strong><?php echo $profile->profile_hits ?></strong>  |  <?php endif ?><?php echo JText::_('COM_JOBBOARD_CVPROF_MODON') ?> <strong><?php echo JHTML::_('date', $profile->modified_date, $this->day_format.' '.$this->month_short_format.', '.$this->year_format) ?></strong><br />
                      <?php echo JText::_('COM_JOBBOARD_CVPROF_CREATEDON') ?> <strong><?php echo JHTML::_('date', $profile->created_date, $this->day_format.' '.$this->month_short_format.', '.$this->year_format) ?></strong> | <?php echo JText::_('COM_JOBBOARD_AVAILSTART') ?> <strong><?php echo JHTML::_('date', $profile->avail_date, $this->day_format.' '.$this->month_short_format.', '.$this->year_format) ?></strong>
                    </span>
                  </a>
                </li>
                <li class="buttons">
                  <div class="floatr btn_container">
                    <span class="btn btn-small-r mtop5"><a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=clonecv&profileid='.$profile->id.'&'.JUtility::getToken().'=1') ?>"><?php echo JText::_('COM_JOBBOARD_CLONE') ?></a></span>
                    <form id="frmEdit_<?php echo $incr ?>" method="post" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=viewcv&profileid='.$profile->id)?>" >
                        <input class="pedit" type="submit" value="<?php echo JText::_('COM_JOBBOARD_VIEW').'/'.JText::_('COM_JOBBOARD_EDIT') ?>" />
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
            </ul>
          </li>
          <?php $incr += 1 ?>
        <?php endforeach ?>
    <?php else : ?>
        <li>&nbsp;</li>
    <?php endif ?>
</ul>
  <?php if($numrows > 0) : ?>
    <div class="clear">&nbsp;</div>
    <span class="jbRescounter"><?php echo $this->results_count; ?></span><span class="jbPagination"><?php echo $this->pagination->getPagesLinks() ?><!--  --></span>
    <div class="clear">&nbsp;</div>
  <?php endif ?>
</div>