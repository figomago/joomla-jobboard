<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
?>

<div style="width:100%">
		<h3><?php echo JText::_('COM_JOBBOARD_CFG_MAINT');?></h3>
		<table class="admintable config left">
			<tr>
				<td class="configsectionhead" colspan="2"><?php echo JText::_('COM_JOBBOARD_MAINT_TASKS');?></td>
			</tr>
  		    <tr>
      			<td colspan="2">
      				<?php echo JText::_('COM_JOBBOARD_MAINT_LAST_RUN');?>&nbsp;&bull;&nbsp;
                   	<strong>
                        <small><?php echo $this->row->last_maint_run == 0? JText::_('COM_JOBBOARD_NEVER') : JHTML::_('date', $this->row->last_maint_run, JText::_('DATE_FORMAT_LC2')) ?></small>
                    </strong>
      			</td>
      		</tr>
  		    <tr>
      			<td align="right" class="key">
      				<?php echo JText::_('COM_JOBBOARD_MAINT_TASKS_ON');?>
      			</td>
      			<td>
                     	<input type="radio" name="maint_tasks_on" id="maint_tasks_on0" value="0" <?php if($this->row->maint_tasks_on == 0) echo 'checked="checked"'; ?> class="inputbox" />
                     	<label for="maint_tasks_on0"><?php echo JText::_('JNO'); ?></label>
                     	<input type="radio" name="maint_tasks_on" id="maint_tasks_on1" value="1" <?php if($this->row->maint_tasks_on == 1) echo 'checked="checked"'; ?> class="inputbox" />
                     	<label for="maint_tasks_on1"><?php echo JText::_('JYES'); ?></label>
      			</td>
      		</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_MAINT_TASKS_INTERVALS');?>
				</td>
				<td>
            		<select name="maint_tasks_int" id="maint_tasks_int" title="<?php echo JText::_('COM_JOBBOARD_MAINT_TASKS_INTERVALS_GAP')?>">
                        <option value="1" <?php if($this->row->maint_tasks_int == 1) echo 'selected="selected"'; ?>>1</option>
                        <option value="2" <?php if($this->row->maint_tasks_int == 2) echo 'selected="selected"'; ?>>2</option>
                        <option value="3" <?php if($this->row->maint_tasks_int == 3) echo 'selected="selected"'; ?>>3</option>
                        <option value="4" <?php if($this->row->maint_tasks_int == 4) echo 'selected="selected"'; ?>>4</option>
                        <option value="5" <?php if($this->row->maint_tasks_int == 5) echo 'selected="selected"'; ?>>5</option>
                        <option value="6" <?php if($this->row->maint_tasks_int == 6) echo 'selected="selected"'; ?>>6</option>
                        <option value="7" <?php if($this->row->maint_tasks_int == 7) echo 'selected="selected"'; ?>>7</option>
                        <option value="8" <?php if($this->row->maint_tasks_int == 8) echo 'selected="selected"'; ?>>8</option>
                        <option value="9" <?php if($this->row->maint_tasks_int == 9) echo 'selected="selected"'; ?>>9</option>
                        <option value="10" <?php if($this->row->maint_tasks_int == 10) echo 'selected="selected"'; ?>>10</option>
                    </select>
            		<select name="maint_tasks_int_type" id="maint_tasks_int_type" title="<?php echo JText::_('COM_JOBBOARD_MAINT_TASKS_INTERVALS')?>">
                        <option value="0" <?php if($this->row->maint_tasks_int_type == 0) echo 'selected="selected"'; ?>><?php echo JText::_('COM_JOBBOARD_MAINT_TASKS_INT_HOUR') ?></option>
                        <option value="1" <?php if($this->row->maint_tasks_int_type == 1) echo 'selected="selected"'; ?>><?php echo JText::_('COM_JOBBOARD_MAINT_TASKS_INT_DAY') ?></option>
                        <option value="2" <?php if($this->row->maint_tasks_int_type == 2) echo 'selected="selected"'; ?>><?php echo JText::_('COM_JOBBOARD_MAINT_TASKS_INT_WEEK') ?></option>
                        <option value="3" <?php if($this->row->maint_tasks_int_type == 3) echo 'selected="selected"'; ?>><?php echo JText::_('COM_JOBBOARD_MAINT_TASKS_INT_MONTH') ?></option>
                    </select>
				</td>
			</tr>
			<tr>
				<td class="configsectionhead" colspan="2"><?php echo JText::_('COM_JOBBOARD_MAINT_TASKS_RUNON_SCHED');?></td>
			</tr>
  		    <tr>
      			<td align="right" class="key">
      				<?php echo JText::_('COM_JOBBOARD_MAINT_TASKS_DISABLE_EXPIRED');?>
      			</td>
      			<td>
                   	<input type="radio" name="sched_disable_exp" id="sched_disable_exp0" value="0" <?php if($this->row->sched_disable_exp == 0) echo 'checked="checked"'; ?> class="inputbox" />
                   	<label for="sched_disable_exp0"><?php echo JText::_('JNO'); ?></label>
                   	<input type="radio" name="sched_disable_exp" id="sched_disable_exp1" value="1" <?php if($this->row->sched_disable_exp == 1) echo 'checked="checked"'; ?> class="inputbox" />
                   	<label for="sched_disable_exp1"><?php echo JText::_('JYES'); ?></label>
      			</td>
      		</tr>
  		    <tr>
      			<td align="right" class="key">
      				<?php echo JText::sprintf('COM_JOBBOARD_MAINT_TASKS_EXPIRE_FEAT', $this->row->feature_length);?>
      			</td>
      			<td>
                   	<input type="radio" name="sched_expire_feat" id="sched_expire_feat0" value="0" <?php if($this->row->sched_expire_feat == 0) echo 'checked="checked"'; ?> class="inputbox" />
                   	<label for="sched_expire_feat0"><?php echo JText::_('JNO'); ?></label>
                   	<input type="radio" name="sched_expire_feat" id="sched_expire_feat1" value="1" <?php if($this->row->sched_expire_feat == 1) echo 'checked="checked"'; ?> class="inputbox" />
                   	<label for="sched_expire_feat1"><?php echo JText::_('JYES'); ?></label>
      			</td>
      		</tr>
		</table>
		<table class="admintable config right">
			<tr>
				<td class="configsectionhead" colspan="2"><?php echo JText::_('COM_JOBBOARD_MAINT_TASKS_NOTIF_OPTS');?></td>
			</tr>
  		    <tr>
                <td class="key">
      				<?php echo JText::sprintf('COM_JOBBOARD_MAINT_TASKS_NOTIF_MAILTO', '');?>&nbsp;<small><?php echo $this->row->from_mail ?></small>
      			</td>
      			<td>
                   	<input type="radio" name="email_task_results" id="email_task_results0" value="0" <?php if($this->row->email_task_results == 0) echo 'checked="checked"'; ?> class="inputbox" />
                   	<label for="email_task_results0"><?php echo JText::_('JNO'); ?></label>
                   	<input type="radio" name="email_task_results" id="email_task_results1" value="1" <?php if($this->row->email_task_results == 1) echo 'checked="checked"'; ?> class="inputbox" />
                   	<label for="email_task_results1"><?php echo JText::_('JYES'); ?></label>
      			</td>
      		</tr>      
		</table>
</div>
<script language="javascript" type="text/javascript">
  window.addEvent('domready', function(){
       var form = document.adminForm;
       form.elements['restore_zip'].addEvent('click', function(e){
            e.stop();
            form.elements['task'].value = 'restore';
            form.submit();
       });
  });
</script>