<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
$option='com_jobboard';
?>
<?php if(!version_compare( JVERSION, '1.6.0', 'ge' )) : ?>
   <?php JHTML::_('stylesheet', 'grid.css', 'administrator/components/com_jobboard/css/') ?>
<?php endif ?>
<form action="index.php?option=com_jobboard&amp;view=users" method="post" name="adminForm">
	<fieldset id="filter-bar">
      <table width="100%">
		<tr>
			<td align="left">
				<label for="search"><?php echo JText::_('COM_JOBBOARD_USERS_FILTER_NAME');?>:&nbsp;</label>
                <input type="text" name="search" value="<?php echo $this->search ?>" id="search" />
				<input type="submit" class="button" value="<?php echo JText::_('GO');?>" />
			    <button type="button" class="button" onclick="document.id('search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_RESET'); ?></button>
                <span style="float:left"><?php echo JText::_('COM_JOBBOARD_USERS_NOTES');?>&nbsp;&bull;&nbsp;<a href="index.php?option=com_users&amp;view=users"><?php echo JText::_('COM_JOBBOARD_USERS_LINK_JOOMLA_USERLIST'); ?></a></span>
			</td>
           <td align="right">
              <select name="group" id="group" class="inputbox" onchange="this.form.submit()">
				<option value="0"><?php echo JText::_('COM_JOBBOARD_USERS_FILTER_GROUP');?></option>
				<?php echo JHtml::_('select.options', JobBoardUsersHelper::getGroups(), 'id', 'group_name', $this->group);?>
			</select>
           </td>
			<td align="right">
				<input type="button" class="button" name="syncusers" id="syncusers" value="<?php echo JText::_('COM_JOBBOARD_USERS_SYNC');?>"
				onclick="if(window.confirm('<?php echo JText::_('COM_JOBBOARD_USERS_SYNC_CONFIRM');?>')) submitbutton('syncusers');" />
			</td>
        </tr>
        </table>
	</fieldset>
	<p> </p>
	<table class="adminlist">
		<thead>
			<tr>
				<th width="50"><?php echo JHTML::_('grid.sort', JText::_('COM_JOBBOARD_ID'), 'id', $this->lists['orderDirection'], $this->lists['order']); ?></th>
				<th width="20">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->rows); ?>);" />
				</th>
				<th><?php echo JHTML::_('grid.sort', JText::_('COM_JOBBOARD_NAME'), 'name', $this->lists['orderDirection'], $this->lists['order']); ?></th>
				<th><?php echo JHTML::_('grid.sort', JText::_('COM_JOBBOARD_USER_JOOMLA_NAME'), 'username', $this->lists['orderDirection'], $this->lists['order']); ?></th>
				<th><?php echo JHTML::_('grid.sort', JText::_('COM_JOBBOARD_STATUS'), 'published', $this->lists['orderDirection'], $this->lists['order']); ?></th>
				<th><?php echo JHTML::_('grid.sort', JText::_('COM_JOBBOARD_USERS_FEATURE_JOBS'), 'feature_jobs', $this->lists['orderDirection'], $this->lists['order']); ?></th>
				<th><?php echo JHTML::_('grid.sort', JText::_('COM_JOBBOARD_USER_GRP_NAME'), 'group_name', $this->lists['orderDirection'], $this->lists['order']); ?>&nbsp;&nbsp;&rarr;&nbsp;<small><a href="index.php?option=com_jobboard&amp;view=config&amp;section=users"><?php echo JText::_('COM_JOBBOARD_USERS_LINK_EDIT_GRPS') ?></a></small></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td class="" colspan="11"><?php echo $this->pagination->getListFooter();?></td>
			</tr>
		</tfoot>
		<tbody>
    		<?php
    		$k = 0;

    		for($i=0,$n=count($this->rows); $i<$n; $i++)
    		{
    			$row =& $this->rows[$i];
    			$checked = JHTML::_('grid.id', $i, $row->id);
    			?>

    			<tr class="<?php echo "row$k"; ?>">
    				<td align="center">
    					<?php echo $row->id; ?>
    				</td>
    				<td>
    					<?php echo $checked; ?>
    				</td>
    				<td>
    					<?php echo $row->name; ?>
    				</td>
    				<td>
    					<?php echo $row->username; ?>
    				</td>
    				<td align="center">
    					<?php echo JHTML::_('grid.published', $row, $i) ?>
    				</td>
    				<td align="center">
    					<?php // echo JHtml::_('grid.boolean', $i, $row->feature_jobs, 'feature', 'unfeature'); ?>
    					<?php echo JobBoardGridHelper::boolean($i, $row->feature_jobs, 'feature', 'unfeature'); ?>
    				</td>
    				<td align="center">
                      <select name="usergroup[<?php echo $row->group_id ?>]" class="inputbox" onchange="document.id('seluser').value=<?php echo $row->user_id ?>;document.id('selrow').value= this.value;this.form.submit()">
        				<?php echo JHtml::_('select.options', JobBoardUsersHelper::getGroups(), 'id', 'group_name', $row->group_id);?>
        			</select>
    				</td>
    			</tr>
    			<?php
    			$k = 1 - $k;
    		}
    		?>
        </tbody>
	</table>
	<input type="hidden" name="option" value="<?php echo $option; ?>" />
    <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['orderDirection']; ?>" />
	<input type="hidden" name="view" value="<?php echo JRequest::getVar('view',''); ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="selrow" id="selrow" value="0" />
	<input type="hidden" name="seluser" id="seluser" value="0" />
	<?php echo JHTML::_('form.token'); ?>
</form>
 <?php echo $this->jb_render; ?>
		