<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
$editor = & JFactory :: getEditor();
?>
<?php JHTML::_('stylesheet', 'files.css', 'administrator/components/com_jobboard/css/') ?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div style="width: 44%; float: left">
	<fieldset class="adminform">
    <legend><?php echo JText::_('COM_JOBBOARD_JOB_IN_QUESTION');?></legend>
		<table class="admintable">
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_JOB_TITLE');?>
				</td>
				<td>
					<a href="<?php echo JFilterOutput::ampReplace('index.php?option=com_jobboard&view=jobs&task=edit&cid[]='.$this->row->job_id); ?>"><strong> <?php echo $this->row->job_title; ?></strong> </a>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_JOB_ID');?>
				</td>
				<td>
					<a href="<?php echo JFilterOutput::ampReplace('index.php?option=com_jobboard&view=jobs&task=edit&cid[]='.$this->row->job_id); ?>"><strong> <?php echo $this->row->job_id; ?></strong> </a>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_POSTDATE');?>
				</td>
				<td>
					<?php echo JHTML::_('date', $this->row->post_date, $this->long_day_format).' ';?>
    				<?php switch($this->config->long_date_format) {
    					 case 0: echo JHTML::_('date', $this->row->post_date, $this->day_format.' '.$this->month_long_format.', '.$this->year_format); break;
    					 case 1: echo JHTML::_('date', $this->row->post_date, $this->month_long_format.' '.$this->day_format.', '.$this->year_format); break;
    					 case 2: echo JHTML::_('date', $this->row->post_date, $this->year_format.', '.$this->day_format.' '.$this->month_long_format); break;?>
    				<?php }; ?> 
				</td>
			</tr>
        </table><br />
		<legend><?php echo JText::_('COM_JOBBOARD_APPLICANT_DETAILS');?></legend>
		<table class="admintable">
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_APPLICTN_DATE');?>
				</td>
				<td>
					<?php echo JHTML::_('date', $this->row->request_date, $this->long_day_format).' ';?>
    				<?php switch($this->config->long_date_format) {
    					 case 0: echo JHTML::_('date', $this->row->request_date, $this->day_format.' '.$this->month_long_format.', '.$this->year_format); break;
    					 case 1: echo JHTML::_('date', $this->row->request_date, $this->month_long_format.' '.$this->day_format.', '.$this->year_format); break;
    					 case 2: echo JHTML::_('date', $this->row->request_date, $this->year_format.', '.$this->day_format.' '.$this->month_long_format); break;?>
    				<?php }; ?>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_FIRST_NAME');?>
				</td>
				<td>
					<?php echo $this->row->first_name; ?>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_LAST_NAME');?>
				</td>
				<td>
					<?php echo $this->row->last_name; ?>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_EMAIL');?>
				</td>
				<td>
					<input name="email" value="<?php echo $this->row->email;?>" type="text" size="40" />
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_TEL');?>
				</td>
				<td>
					<input name="tel" value="<?php echo $this->row->tel;?>" type="text" size="40"  />
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_COVER_NOTE');?>
				</td>
				<td>
                    <textarea name="cover_note" rows="5" cols="30"><?php echo $this->row->cover_note; ?></textarea>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_ATTACHMENT');?>
				</td>
				<td>
                    <?php $filetype = !empty($this->row->filetype)? explode('/', $this->row->filetype) : array('', 'blank') ?>
                    <span class="filesrc <?php echo $filetype[1] ?>">
					    <a href="<?php echo JFilterOutput::ampReplace('index.php?option=com_jobboard&view=applicants&task=getucvfile&file='.$this->row->id.'&dmode=1&'.JUtility::getToken().'=1') ?>"><strong><?php echo $this->row->title; ?></strong>&nbsp;&nbsp;
                          <?php if($filetype[1] == 'blank') : ?>
                              <img src="components/com_jobboard/images/box_download.png" alt="<?php echo JText::_('COM_JOBBOARD_DNL_CV') ?>" />
                          <?php endif ?>
                          <?php echo $this->row->filename; ?>
                        </a>
                    </span>
				</td>
			</tr>
		</table>
	</fieldset></div>
    <div style="width: 55%; float: right; clear: none">
     <fieldset>
     <legend><?php echo JText::_('COM_JOBBOARD_APPLICATION_DETAILS');?></legend>
		<table class="admintable">
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_APPLICATION_STATUS');?>
				</td>
				<td>
                    <select name="status">
                        <?php foreach($this->statuses as $status) : ?>
                            <option value="<?php echo $status->id ?>" <?php if($status->id == $this->row->status){echo 'selected="selected"';}  ?>><?php echo $status->status_description; ?></option>
                        <?php endforeach; ?>
                    </select>
                    &nbsp;&nbsp;<a href="<?php echo JFilterOutput::ampReplace('index.php?option=com_jobboard&amp;&view=statuses') ?>"><?php echo JText::_('COM_JOBBOARD_ED_STATUS_CHOICES') ?></a>
                </td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_LAST_UPDATED');?>
				</td>
				<td>
                    <?php if($this->row->last_updated == '0000-00-00 00:00:00') : ?>
                        <?php echo JText::_('COM_JOBBOARD_NEVER'); ?>
                    <?php else : ?>
                        <?php echo JHTML::_('date', $this->row->last_updated, $this->long_day_format).' ';?>
        				<?php switch($this->config->long_date_format) {
        					 case 0: echo JHTML::_('date', $this->row->last_updated, $this->day_format.' '.$this->month_long_format.', '.$this->year_format); break;
        					 case 1: echo JHTML::_('date', $this->row->last_updated, $this->month_long_format.' '.$this->day_format.', '.$this->year_format); break;
        					 case 2: echo JHTML::_('date', $this->row->last_updated, $this->year_format.', '.$this->day_format.' '.$this->month_long_format); break;?>
        				<?php }; ?>
                    <?php endif; ?>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_TARGET_DEPT');?>
				</td>
				<td>
                    <select name="department">
                        <?php foreach($this->departments as $department) : ?>
                            <option value="<?php echo $department->id ?>" <?php if($department->id == $this->row->department){echo 'selected="selected"'; $job_department = $department;}  ?>><?php echo $department->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                    &nbsp;&nbsp;<a href="index.php?option=com_jobboard&amp;&view=departments"><?php echo JText::_('COM_JOBBOARD_EDIT_DEPTS') ?></a>
                </td>
			</tr>
         </table>
     </fieldset>
     <fieldset>
     <legend><?php echo JText::_('COM_JOBBOARD_INTERNAL_USE');?></legend>
         <table>
			<tr>
				<td align="right" class="key" style="text-align:left">
					<strong><?php echo JText::_('COM_JOBBOARD_NOTES');?></strong><?php echo ' (<span style="color: red">'.JText::_('COM_JOBBOARD_BACKOFFICE_ONLY').'</span>) '; ?>
				</td>
            </tr><tr>
				<td>
                    <?php echo $editor->display('admin_notes', ($this->row->admin_notes == '')? '' : $this->row->admin_notes, '495', '150', '60', '20', true);  ?>
                </td>
			</tr>
         </table>
     </fieldset>
    </div>
	<input type="hidden" name="id" value="<?php echo $this->row->id;?>" />
	<input type="hidden" name="job_id" value="<?php echo $this->row->job_id;?>" />
	<input type="hidden" name="first_name" value="<?php echo $this->row->first_name; ?>" />
	<input type="hidden" name="last_name" value="<?php echo $this->row->last_name; ?>" />
	<input type="hidden" name="title" value="<?php echo $this->row->job_title; ?>" />
	<input type="hidden" name="option" value="<?php echo 'com_jobboard';?>" />
	<input type="hidden" name="view" value="<?php echo JRequest::getVar('view',''); ?>" />
	<input type="hidden" name="task" value="<?php echo JRequest::getVar('task',''); ?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>
<?php $getAdmninnote = $editor->getContent('admin_notes'); ?>
<?php if(!version_compare( JVERSION, '1.6.0', 'ge' )) : ?>
    <script type="text/javascript">
       var Joomla = Joomla || {};
       Joomla.submitbutton = submitform;
    </script>
<?php endif ?>
<script language="javascript" type="text/javascript">
Joomla.submitbutton = function(pressbutton)
  {
  var form = document.adminForm;
  // check we are saving/updating the job application
  if (document.adminForm.title.value.trim() == "") {
			alert( '<?php echo JText::_('COM_JOBBOARD_JOB_TITLREQ', true); ?>' );
  }
  if (pressbutton == 'save' || pressbutton == 'apply' )
    {
      text = <?php echo $getAdmninnote; ?>;
      text = encHtml(text);
      <?php echo $editor->save( 'admin_notes' ); ?>;
      submitform( pressbutton );
      return;
    } else {
      submitform( pressbutton );
      return;
    }
  }
function encHtml(h){encdHtml=escape(h);encdHtml=encdHtml.replace(/\//g,"%2F");encdHtml=encdHtml.replace(/\?/g,"%3F");encdHtml=encdHtml.replace(/=/g,"%3D");encdHtml=encdHtml.replace(/&/g,"%26");encdHtml=encdHtml.replace(/@/g,"%40");return encdHtml;
}
</script>
<?php echo $this->jb_render; ?>