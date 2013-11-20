<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');

?>

<div>
<br class="clear" />
<h2><?php echo JText::_('COM_JOBBOARD_JOBAPPLICATIONS') ?></h2>
  <form id="applFrm" name="applFrm" method="post" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=saveappl') ?>" >
    <label for="cvprofile"><?php echo JText::_('COM_JOBBOARD_SELAPPLCV') ?></label>
    <select name="cvprofile">
      <?php foreach ($this->profdata as $cv) : ?>
          <option value="<?php echo $cv->id ?>"><?php echo $cv->profile_name ?></option>
      <?php endforeach ?>
    </select>
    <input type="hidden" name="option" value="com_jobboard" />
    <input type="hidden" name="view"  value="user" />
    <input type="hidden" name="task" value="saveappl" />
    <input type="hidden" name="jobid" value="<?php echo $this->jobid ?>" />
    <input type="hidden" name="catid" value="<?php echo $this->catid ?>" />
    <div id="btn_container_footer">
        <span class="btn"><input class="button" type="submit" value="<?php echo JText::_('COM_JOBBOARD_TXTSAVE') ?>" name="commit" /></span>
    </div>
    <?php echo JHTML::_('form.token'); ?>
  </form>
</div>
<?php // echo '<pre>'.print_r($this->profdata, true).'</pre>'; ?>
