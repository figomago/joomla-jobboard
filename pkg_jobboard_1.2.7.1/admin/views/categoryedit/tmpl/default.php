<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');

$option='com_jobboard';             

?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_JOBBOARD_EDIT_CATEGORY');?></legend>
		<table class="admintable">
         <?php if($this->row->id == 1): ?>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_ID');?>
				</td>
				<td>
					<?php echo $this->row->id;?>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_ENABLED');?>
				</td>
                <td>
                    <?php echo ($this->row->enabled == 1)?  JText::_('COM_JOBBOARD_JYES') : JText::_('COM_JOBBOARD_JNO'); ?>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_CAT_NAME');?>
				</td>
				<td>
					<input class="text_area" type="text" name="type" id="type" size="80" maxlength="250" value="<?php echo $this->row->type;?>" />
				</td>
			</tr>
         <?php else : ?>
         <?php if($this->row->id > 0): ?>
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
					<?php echo JText::_('COM_JOBBOARD_ENABLED');?>
				</td>
				<td>
         		<?php if($this->row->id == 0) $this->row->enabled = 1;  ?>
                  <select name="enabled" id="enabled">
                     <option value="1" <?php if($this->row->enabled == 1) echo 'selected="selected"'; ?>><?php echo JText::_('COM_JOBBOARD_JYES'); ?></option>
                     <option value="0" <?php if($this->row->enabled == 0) echo 'selected="selected"'; ?>><?php echo JText::_('COM_JOBBOARD_JNO'); ?></option>
				  </select>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_CAT_NAME');?>
				</td>
				<td>
					<input class="text_area" type="text" name="type" id="type" size="80" maxlength="250" value="<?php echo $this->row->type;?>" />
				</td>
			</tr>

         <?php endif; ?>
		</table>
	</fieldset>
	<input type="hidden" name="id" value="<?php echo $this->row->id;?>" />
	<input type="hidden" name="option" value="<?php echo $option;?>" />
	<input type="hidden" name="view" value="<?php echo JRequest::getVar('view',''); ?>" />
	<input type="hidden" name="task" value="<?php echo JRequest::getVar('task',''); ?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>
 <?php echo $this->jb_render; ?>
