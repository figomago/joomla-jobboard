<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');

$statuses = array('new', 'reviewed', 'scheduled', 'accepted', 'rejected');
$option='com_jobboard'; 
?>

<?php if(!version_compare( JVERSION, '1.6.0', 'ge' )) : ?>
   <?php JHTML::_('stylesheet', 'grid.css', 'administrator/components/com_jobboard/css/') ?>
<?php endif ?>
<form action="index.php" method="post" name="adminForm">
	<table>
		<tr>
			<td>
				<b><?php echo JText::_('COM_JOBBOARD_MANAGE_JOBS');?></b>
			</td>
		</tr>
	</table>
	<table width="100%">
		<tr>
			<td align="left">
				<label for="search"><?php echo JText::_('COM_JOBBOARD_FILTERBYJOB');?>:&nbsp;</label>
                <input type="text" name="search" value="<?php echo $this->search ?>" id="search" />
				<input type="submit" value="<?php echo JText::_('GO');?>" />
    			<button type="button" class="button" onclick="document.id('search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_RESET'); ?></button>
			</td>
		</tr>
	</table>
	<p> </p>
	<table class="adminlist">
		<thead>
			<tr>
				<th width="50"><?php echo JHTML::_('grid.sort', JText::_('COM_JOBBOARD_ID'), 'id', $this->lists['orderDirection'], $this->lists['order']); ?></th>
				<th width="20">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->rows); ?>);" />
				</th>
				<th><?php echo JHTML::_('grid.sort', JText::_('COM_JOBBOARD_JOB_TITLE'), 'job_title', $this->lists['orderDirection'], $this->lists['order']); ?></th>
				<th><?php echo JHTML::_('grid.sort', JText::_('COM_JOBBOARD_JOBREF'), 'ref_num', $this->lists['orderDirection'], $this->lists['order']); ?></th>
				<th><?php echo JHTML::_('grid.sort', JText::_('COM_JOBBOARD_JOB_CAT'), 'category', $this->lists['orderDirection'], $this->lists['order']); ?></th>
				<th><?php echo JHTML::_('grid.sort', JText::_('COM_JOBBOARD_STATUS'), 'published', $this->lists['orderDirection'], $this->lists['order']); ?></th>
				<th><?php echo JHTML::_('grid.sort', JText::_('COM_JOBBOARD_FEATURED'), 'featured', $this->lists['orderDirection'], $this->lists['order']); ?></th>
				<th><?php echo JHTML::_('grid.sort', JText::_('COM_JOBBOARD_JOB_TYPE'), 'job_type', $this->lists['orderDirection'], $this->lists['order']); ?></th>
				<th><?php echo JHTML::_('grid.sort', JText::_('COM_JOBBOARD_POSTED'), 'post_date', $this->lists['orderDirection'], $this->lists['order']); ?></th>
				<th><?php echo JHTML::_('grid.sort', JText::_('COM_JOBBOARD_TOTL_APPL'), 'num_applications', $this->lists['orderDirection'], $this->lists['order']); ?></th>
				<th><?php echo JHTML::_('grid.sort', JText::_('COM_JOBBOARD_TOTL_VIEWS'), 'hits', $this->lists['orderDirection'], $this->lists['order']); ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td class="" colspan="11"><?php echo $this->pagination->getListFooter();?></td>
			</tr>
		</tfoot>
        <tbody>
            <?php $n=count($this->rows) ?>
            <?php if($n < 1) : ?>
                <tr>
                   <td colspan="11"><?php echo JText::_('COM_JOBBOARD_ENT_NONEFOUND') ?></td>
                </tr>
            <?php endif ?>
  		<?php
  		$k = 0;

  		for($i=0; $i<$n; $i++)
  		{
  			$row =& $this->rows[$i];
  			$checked = JHTML::_('grid.id', $i, $row->id);
  			$link = JFilterOutput::ampReplace('index.php?option=' . $option . '&view=jobs&task=edit&cid[]=' . $row->id);
  			?>

  			<tr class="<?php echo "row$k"; ?>">
  				<td align="center">
  					<?php echo $row->id; ?>
  				</td>
  				<td>
  					<?php echo $checked; ?>
  				</td>
  				<td>
  					<a href="<?php echo $link; ?>"><?php echo $row->job_title; ?></a>
  				</td>
  				<td>
  					<a href="<?php echo $link; ?>"><?php echo $row->ref_num; ?></a>
  				</td>
  				<td align="center">
  					<?php echo $row->category; ?>
  				</td>
  				<td align="center">
  					<?php echo JHTML::_('grid.published', $row, $i) ?>
  				</td>
  				<td align="center">
  					<?php echo JobBoardGridHelper::boolean($i, $row->featured, 'feature', 'unfeature'); ?>
  				</td>
  				<td align="center">
  					<?php echo JText::_($row->job_type); ?>
  				</td>
  				<td align="center">
  					<?php echo JHTML::_('date', $row->post_date, $this->long_day_format).' ';?>
      				<?php switch($this->config->long_date_format) {
      					 case 0: echo JHTML::_('date', $row->post_date, $this->day_format.' '.$this->month_short_format.', '.$this->year_format); break;
      					 case 1: echo JHTML::_('date', $row->post_date, $this->month_short_format.' '.$this->day_format.', '.$this->year_format); break;
      					 case 2: echo JHTML::_('date', $row->post_date, $this->year_format.', '.$this->day_format.' '.$this->month_short_format); break;?>
      				<?php }; ?>
  				</td>
  				<td align="center">
  					<b><?php echo $row->num_applications ?></b>
  				</td>
  				<td align="center">
  					<?php echo $row->hits; ?>
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
	<?php echo JHTML::_('form.token'); ?>
</form>
 <?php echo $this->jb_render; ?>
		