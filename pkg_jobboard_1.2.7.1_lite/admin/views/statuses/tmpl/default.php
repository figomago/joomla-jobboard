<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');

$option='com_jobboard';             
?>

<b><?php echo JText::_('COM_JOBBOARD_EDIT_STATUSES');?></b><p> </p>

<form action="index.php" method="post" name="adminForm" id="adminForm">
    <fieldset id="filter-bar">
    	<table width="100%">
    		<tr>
    			<td align="left">
    				<label for="search"><?php echo JText::_('COM_JOBBOARD_SEARCH');?>:&nbsp;</label>
                    <input type="text" name="search" value="<?php echo $this->search ?>" id="search" />
    				<input type="submit" value="<?php echo JText::_('COM_JOBBOARD_GO');?>" />
    			    <button type="button" class="button" onclick="document.id('search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_RESET'); ?></button>
    			</td>
    		</tr>
    	</table>
    </fieldset>
	<p> </p>
	<table class="adminlist">
		<thead>
			<tr>
				<th width="20">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->rows); ?>);" />
				</th>
				<th><?php echo JHTML::_('grid.sort', JText::_('COM_JOBBOARD_ID'), 'id', $this->lists['orderDirection'], $this->lists['order']); ?></th>
				<th><?php echo JHTML::_('grid.sort', JText::_('COM_JOBBOARD_DESCRIPTION'), 'status_description', $this->lists['orderDirection'], $this->lists['order']); ?></th>
			</tr>
		</thead> 
		<tfoot>
			<tr>
				<td class="" colspan="11"><?php echo $this->pagination->getListFooter();?></td>
			</tr>
		</tfoot>
		<?php 
		$k = 0;
		for($i=0,$n=count($this->rows); $i<$n; $i++)
		{
			$row =& $this->rows[$i];
			$checked = JHTML::_('grid.id', $i, $row->id);
			$link = JFilterOutput::ampReplace('index.php?option=' . $option . '&view=statuses&task=edit&cid[]=' . $row->id);
			?>
			
			<tr class="<?php echo "row$k"; ?>">
				<td>
					<?php echo $checked; ?>
				</td>
				<td>
					<?php echo $row->id; ?>
				</td>
				<td>
					<a href="<?php echo $link; ?>"><?php echo $row->status_description; ?></a>
				</td>
			</tr>
			<?php 
			$k = 1 - $k;
		}
		?>
	</table>
	<input type="hidden" name="option" value="<?php echo $option; ?>" />
    <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['orderDirection']; ?>" />
	<input type="hidden" name="view" value="<?php echo JRequest::getVar('view',''); ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHTML::_('form.token'); ?>
</form>
 <?php echo $this->jb_render; ?>
		