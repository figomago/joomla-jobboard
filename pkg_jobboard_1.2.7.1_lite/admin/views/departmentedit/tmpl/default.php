<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');

$option='com_jobboard';             

?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_JOBBOARD_EDIT_DEPT');?></legend>
		<table class="admintable">
        <?php if($this->row->id > 0) : ?>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_ID');?>
				</td>
				<td>
					<?php echo $this->row->id;?>
				</td>
			</tr>
         <?php endif; ?>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_DEPT_NAME');?>
				</td>
				<td>
					<input class="text_area" type="text" name="name" id="name" size="80" maxlength="250" value="<?php echo $this->row->name;?>" />
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_CONTACT_NAME');?>
				</td>
				<td>
					<input class="text_area" type="text" name="contact_name" id="contact_name" size="80" maxlength="250" value="<?php echo $this->row->contact_name;?>" />
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_CONTACT_EMAIL');?>
				</td>
				<td>
					<input class="text_area" type="text" name="contact_email" id="contact_email" size="80" maxlength="250" value="<?php echo $this->row->contact_email;?>" />
				</td>
			</tr>
		</table>
	</fieldset>
    <fieldset class="adminform">
		<legend><?php echo JText::_('COM_JOBBOARD_EML_NOTIFICATIONS');?></legend>
		<table class="admintable">
        <?php if($this->row->id == 0) : ?>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_NOTIFY_JBOARD_ADMIN');?>
				</td>
				<td>
                    <select name="notify_admin" id="notify_admin">
                       <option value="1" <?php if($this->config->dept_notify_admin == 1) echo 'selected="selected"'; ?>><?php echo JText::_('COM_JOBBOARD_JYES'); ?></option>
                       <option value="0" <?php if($this->config->dept_notify_admin == 0) echo 'selected="selected"'; ?>><?php echo JText::_('COM_JOBBOARD_JNO'); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_NOTIFY_DEPT_CONTACT');?>
				</td>
				<td>
                    <select name="notify" id="notify">
                      <option value="1" <?php if($this->config->dept_notify_contact == 1) echo 'selected="selected"'; ?>><?php echo JText::_('COM_JOBBOARD_JYES'); ?></option>
                      <option value="0" <?php if($this->config->dept_notify_contact == 0) echo 'selected="selected"'; ?>><?php echo JText::_('COM_JOBBOARD_JNO'); ?></option>
					</select>
				</td>
			</tr>
            <?php else : ?>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_NOTIFY_JBOARD_ADMIN');?>
				</td>
				<td>
                    <select name="notify_admin" id="notify_admin">
                       <option value="1" <?php if($this->row->notify_admin == 1) echo 'selected="selected"'; ?>><?php echo JText::_('COM_JOBBOARD_JYES'); ?></option>
                       <option value="0" <?php if($this->row->notify_admin == 0) echo 'selected="selected"'; ?>><?php echo JText::_('COM_JOBBOARD_JNO'); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_NOTIFY_DEPT_CONTACT');?>
				</td>
				<td>
                    <select name="notify" id="notify">
                      <option value="1" <?php if($this->row->notify == 1) echo 'selected="selected"'; ?>><?php echo JText::_('COM_JOBBOARD_JYES'); ?></option>
                      <option value="0" <?php if($this->row->notify == 0) echo 'selected="selected"'; ?>><?php echo JText::_('COM_JOBBOARD_JNO'); ?></option>
					</select>
				</td>
			</tr>
        <?php endif; ?>
        </table>
	</fieldset>
    <fieldset class="adminform">
		<legend><?php echo JText::_('COM_JOBBOARD_JOB_APPLICANT_NOTIFICATIONS');?></legend>
		<table class="admintable">
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_NOTIFY_APPL_SUCCESS');?>
				</td>
				<td>
                    <select name="acceptance_notify" id="acceptance_notify">
                       <option value="1" <?php if($this->row->acceptance_notify == 1) echo 'selected="selected"'; ?>><?php echo JText::_('COM_JOBBOARD_JYES'); ?></option>
                       <option value="0" <?php if($this->row->acceptance_notify == 0) echo 'selected="selected"'; ?>><?php echo JText::_('COM_JOBBOARD_JNO'); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_NOTIFY_APPL_FAIL');?>
				</td>
				<td>
                    <select name="rejection_notify" id="rejection_notify">
                      <option value="1" <?php if($this->row->rejection_notify == 1) echo 'selected="selected"'; ?>><?php echo JText::_('COM_JOBBOARD_JYES'); ?></option>
                      <option value="0" <?php if($this->row->rejection_notify == 0) echo 'selected="selected"'; ?>><?php echo JText::_('COM_JOBBOARD_JNO'); ?></option>
					</select>
				</td>
			</tr>
        </table>
	</fieldset>
	<input type="hidden" name="id" value="<?php echo $this->row->id;?>" />
	<input type="hidden" name="option" value="<?php echo $option;?>" />
	<input type="hidden" name="view" value="<?php echo JRequest::getVar('view',''); ?>" />
	<input type="hidden" name="task" value="<?php echo JRequest::getVar('task',''); ?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>
 <?php echo $this->jb_render; ?>
