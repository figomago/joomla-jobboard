<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');

?>
<?php JHTML::_('script', 'createcvstep_one.js', 'components/com_jobboard/js/') ?>
<label for="profile_name"><?php echo JText::_('COM_JOBBOARD_PROFILENAME') ?><span class="reqd">*</span></label>
    <input type="text" size="35" name="profile_name" id="profile_name" value="<?php if($this->profileid <> 0) echo $this->data['profile_name'] ?>" />
    <label for="available_dd"><?php echo JText::_('COM_JOBBOARD_AVAILSTART') ?></label>
    <select id="available_yyyy" name="available_yyyy" tabindex="50">
      <?php for($i = 0; $i < 3; $i++) : ?>
        <option value="<?php echo $curyear +$i ?>" <?php if($i == 0) echo 'selected="selected"'; ?>><?php echo $curyear +$i ?></option>
      <?php endfor; ?>
    </select>
    <select id="available_mm" name="available_mm" tabindex="40">
      <?php for($i = 1; $i < 13; $i++) : ?>
        <?php $month_leading = sprintf("%02d",$i) ?>
        <?php $month_string =  $curyear.'-'.$month_leading.'-'.$curday ?>
        <option value="<?php echo $month_leading ?>" <?php if($curmonth_nr == $i) echo 'selected="selected"'; ?>><?php echo JHTML::_('date', $month_string, 'F') ?></option>
      <?php endfor; ?>
    </select>
    <select id="available_dd" name="available_dd" tabindex="30">
        <?php for($i = 1; $i <= $lastday; $i++) : ?>
            <?php $day_leading = sprintf("%02d",$i) ?>
            <option value="<?php echo $day_leading ?>" <?php if($curday == $i) echo 'selected="selected"'; ?>><?php echo $day_leading ?></option>
        <?php endfor; ?>
    </select>
    <label for="job_type"><?php echo JText::_('COM_JOBBOARD_JOBTYPESOUGHT') ?></label>
    <select id="job_type" name="job_type">
    <option value="0" <?php if($this->profileid <> 0 && $this->data['job_type'] == 0) echo 'selected="selected"'  ?>><?php echo JText::_('COM_JOBBOARD_ANYJOBTYPE') ?></option>
    <option value="COM_JOBBOARD_DB_JFULLTIME" <?php if($this->profileid <> 0 && $this->data['job_type'] == 'COM_JOBBOARD_DB_JFULLTIME') echo 'selected="selected"'  ?>><?php echo JText::_('COM_JOBBOARD_DB_JFULLTIME') ?></option>
    <option value="COM_JOBBOARD_DB_JCONTRACT" <?php if($this->profileid <> 0 && $this->data['job_type'] == 'COM_JOBBOARD_DB_JCONTRACT') echo 'selected="selected"'  ?>><?php echo JText::_('COM_JOBBOARD_DB_JCONTRACT') ?></option>
    <option value="COM_JOBBOARD_DB_JPARTTIME" <?php if($this->profileid <> 0 && $this->data['job_type'] == 'COM_JOBBOARD_DB_JPARTTIME') echo 'selected="selected"'  ?>><?php echo JText::_('COM_JOBBOARD_DB_JPARTTIME') ?></option>
    <option value="COM_JOBBOARD_DB_JTEMP" <?php if($this->profileid <> 0 && $this->data['job_type'] == 'COM_JOBBOARD_DB_JTEMP') echo 'selected="selected"'  ?>><?php echo JText::_('COM_JOBBOARD_DB_JTEMP') ?></option>
    <option value="COM_JOBBOARD_DB_JINTERN" <?php if($this->profileid <> 0 && $this->data['job_type'] == 'COM_JOBBOARD_DB_JINTERN') echo 'selected="selected"'  ?>><?php echo JText::_('COM_JOBBOARD_DB_JINTERN') ?></option>
    <option value="COM_JOBBOARD_DB_JOTHER" <?php if($this->profileid <> 0 && $this->data['job_type'] == 'COM_JOBBOARD_DB_JOTHER') echo 'selected="selected"'  ?>><?php echo JText::_('COM_JOBBOARD_DB_JOTHER') ?></option>
    </select>
        <div class="clear">&nbsp;</div>
        <span class="frmheading"><?php echo JText::_('COM_JOBBOARD_UPLOADSDESCR') ?>  <small class="right fnrml"><?php echo JText::_('COM_JOBBOARD_MAXSIZE').' ' . $this->config->max_filesize .JText::_('COM_JOBBOARD_MEGABYTESEACH') ?></small></span>
        <div id="filerow[1]" class="filerow">
          <label for="filetitle[1]"><?php echo JText::_('TITLE') ?></label>
          <input type="text" size="55" name="filetitle[1]" id="filetitle[1]" />
          <label class="midlabel" for="file[1]"><?php echo JText::_('COM_JOBBOARD_FILETOUPLOAD') ?></label>
          <input class="inputfield " maxlength="96" name="file[1]" id="file[1]" size="35" type="file" />
        </div>
        <input type="hidden" name="file_count" id="file_count" value="<?php echo $this->file_count ?>" />
        <div id="filesfooter">
              <span><?php echo JText::_('COM_JOBBOARD_PERMFORMATS') ?></span>
              <a id="newfile" href="#"><?php echo JText::_('COM_JOBBOARD_TXTADDFILE') ?></a>
        </div>