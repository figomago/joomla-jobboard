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
		<legend><?php echo JText::_('COM_JOBBOARD_EDIT_CAREER_LEVEL');?></legend>
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
					<?php echo JText::_('COM_JOBBOARD_DESCRIPTION');?>
				</td>
				<td>
					<input class="text_area" type="text" name="description" id="description" size="80" maxlength="250" value="<?php echo $this->row->description;?>" />
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
