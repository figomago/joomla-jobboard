<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
$option='com_jobboard';             
?>
<?php if(!$this->is_modal) : ?>
  <?php  $modal_params = array('handler'=> 'iframe', 'size' => array( 'x' => 640, 'y' => 480), 'sizeLoading'=>array( 'x' => 250, 'y' => 150), 'overlay'=>false, 'classWindow'=>'jobboardmodal'); ?>
  <?php JobBoardBehaviorHelper::modal('a.jobbrdmodal', $modal_params); ?>
<?php endif ?>
<form action="index.php?option=com_jobboard&amp;view=applicants" method="post" name="adminForm" id="adminForm" >
	<fieldset id="filter-bar">
    	<table width="100%">
    		<tr>
    			<td>
    				<b><?php echo JText::_('COM_JOBBOARD_MANAGE_APPLICANTS');?></b>
    			</td>
    		</tr>
    		<tr>
    			<td align="left">
    				<label for="search"><?php echo JText::_('COM_JOBBOARD_SEARCH');?>:&nbsp;</label>
                    <input type="text" name="search" value="<?php echo $this->search ?>" id="search" />
    				<input type="submit" value="<?php echo JText::_('COM_JOBBOARD_GO');?>" />
    			    <button type="button" class="button" onclick="document.id('search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_RESET'); ?></button>
    			</td>
                <td align="right">
                    <label for="applicant_grp"><?php echo JText::_('COM_JOBBOARD_APPL_TYPES');?> :</label>
                    <select name="applicant_grp" id="applicant_grp" class="inputbox" onchange="changeApplGrp(this.value);return null;">
        				<option value="1"<?php if($this->vcontext == 1) echo ' selected="selected"' ?>><?php echo JText::_('COM_JOBBOARD_APPL_TYPE_REG');?></option>
        				<option value="2"<?php if($this->vcontext == 2) echo ' selected="selected"' ?>><?php echo JText::_('COM_JOBBOARD_APPL_TYPE_SITE');?></option>
        				<option value="3"<?php if($this->vcontext == 3) echo ' selected="selected"' ?>><?php echo JText::_('COM_JOBBOARD_APPL_TYPE_UNSOL');?></option>
        			</select>
                </td>
    		</tr>
    	</table>
    </fieldset>
	<p> </p>
	<table class="adminlist">
		<thead>
			<tr>
				<th width="50"><?php echo JHTML::_('grid.sort', 'ID', 'id', $this->lists['orderDirection'], $this->lists['order']); ?></th>
				<th width="20">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->rows); ?>);" />
				</th>
				<th><?php echo JHTML::_('grid.sort', JText::_('COM_JOBBOARD_NAME'), 'first_name', $this->lists['orderDirection'], $this->lists['order']); ?></th>
				<th><?php echo JHTML::_('grid.sort', JText::_('COM_JOBBOARD_POSITION_IN_QUESTION'), 'job_title', $this->lists['orderDirection'], $this->lists['order']); ?></th>
				<th><?php echo JHTML::_('grid.sort', JText::_('COM_JOBBOARD_CV_OR_RESUME'), 'filename', $this->lists['orderDirection'], $this->lists['order']); ?></th>
				<th><?php echo JHTML::_('grid.sort', JText::_('COM_JOBBOARD_DEPARTMENT'), 'department', $this->lists['orderDirection'], $this->lists['order']); ?></th>
				<th><?php echo JHTML::_('grid.sort', JText::_('COM_JOBBOARD_APPLICATION_DATE'), 'request_date', $this->lists['orderDirection'], $this->lists['order']); ?></th>
				<th><?php echo JHTML::_('grid.sort', JText::_('COM_JOBBOARD_APPLICATION_STATUS'), 'status', $this->lists['orderDirection'], $this->lists['order']); ?></th>
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
        			$user_link = JFilterOutput::ampReplace('index.php?option=com_jobboard&view=user&sid='.$row->user_id.'&pid='.$row->cvid.'&jid='.$row->job_id.'&tmpl=component');
        			$applicant_link = JFilterOutput::ampReplace('index.php?option=com_jobboard&view=applicants&task=edappl&aid='.$row->id.'&sid='.$row->user_id.'&pid='.$row->cvid.'&qid='.$row->qid.'&jid='.$row->job_id);
        			$link = JFilterOutput::ampReplace('index.php?option=com_jobboard&view=applicants&task=edit&cid[]=' . $row->id);
        			?>

        			<tr class="<?php echo "row$k"; ?>">
        				<td align="center">
        					<?php echo $row->id; ?>
        				</td>
        				<td>
        					<?php echo $checked; ?>
        				</td>
        				<td>
        					<a href="<?php echo $applicant_link; ?>"><?php echo $row->name; ?></a>
        				</td>
        				<td align="center">
        					<a href="<?php echo JFilterOutput::ampReplace('index.php?option=com_jobboard&view=jobs&task=edit&cid[]='.$row->job_id); ?>"><?php echo $row->job_title; ?></a>
        				</td>
        				<td align="center">
          					<a class="jobbrdmodal" href="<?php echo $user_link; ?>"><?php echo $row->profile_name; ?> </a>
                        </td>
        				<td align="center">
        					<?php echo $row->department; ?>
        				</td>
        				<td align="center">
        					<?php echo JHTML::_('date', $row->request_date, $this->long_day_format).' ';?>
            				<?php switch($this->config->long_date_format) {
            					 case 0: echo JHTML::_('date', $row->request_date, $this->day_format.' '.$this->month_long_format.', '.$this->year_format); break;
            					 case 1: echo JHTML::_('date', $row->request_date, $this->month_long_format.' '.$this->day_format.', '.$this->year_format); break;
            					 case 2: echo JHTML::_('date', $row->request_date, $this->year_format.', '.$this->day_format.' '.$this->month_long_format); break;?>
            				<?php }; ?>
        				</td>
        				<td align="center">
                          <?php foreach ($this->statuses AS $status) : ?>
                              <?php if($status->id == $row->status) echo $status->status_description; ?>
                          <?php endforeach; ?>
        				</td>
        			</tr>

      			<?php  $k = 1 - $k;  }	?>
        </tbody>
	</table>
	<input type="hidden" name="option" value="<?php echo $option; ?>" />
    <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['orderDirection']; ?>" />
	<input type="hidden" name="view" value="<?php echo JRequest::getVar('view',''); ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="vcontext" value="1" />
    <?php echo JHTML::_('form.token'); ?>
</form>
<script type="text/javascript">
     var frm = document.forms['adminForm'];
     var changeApplGrp = function(grp){
        switch(parseInt(grp)){
          case 1 :
            frm.elements['view'].value = 'applicants';
          break;
          case 2 :
            frm.elements['view'].value = 'applicants';
          break;
          case 3 :
            frm.elements['view'].value = 'unsolicited';
          break;
        }
        frm.elements['vcontext'].value = grp;
        frm.submit();
     };
</script>
 <?php echo $this->jb_render; ?>
