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
		<legend><?php echo JText::_('COM_JOBBOARD_EDITMSG');?></legend>
		<table class="admintable">
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_TYPE');?>
				</td>
				<td>
					<?php echo $this->row->type;?>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_SUBJECT');?>
				</td>
				<td>
					<input class="text_area" type="text" name="subject" id="subject" size="100" maxlength="250" value="<?php echo $this->row->subject;?>" />
				</td>
			</tr>
			<tr>
				<td valign="top" align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_BODY');?>
				</td>
				<td>
					<textarea cols="110" rows="10" name="body" id="body"><?php echo $this->row->body;?></textarea>
				</td>
			</tr>
			<tr>
				<td valign="top" align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_AVAILABLE_SUBS');?>
				</td>
				<td>
					<table>
                    <?php switch($this->row->type) {
                     case 'adminnew' : ?>
						<tr>
							<td>[jobtitle]</td>
							<td><?php echo JText::_('COM_JOBBOARD_JOB_TITLE');?></td>
						</tr>
						<tr>
							<td>[department]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_JOB_DEPT');?></td>
						</tr>
						<tr>
							<td>[location]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_JOB_LOCN');?></td>
						</tr>
						<tr>
							<td>[status]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_JOBPUBSTATUS');?></td>
						</tr>
						<tr>
							<td>[appladmin]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_ENTRY_EDITOR');?></td>
						</tr>
                     <?php break; ?>
                     <?php case 'adminnew_application' : ?>
						<tr>
							<td>[jobtitle]</td>
							<td><?php echo JText::_('COM_JOBBOARD_JOB_TITLE');?></td>
						</tr>
						<tr>
							<td>[applname]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_APPLNAME');?></td>
						</tr>
						<tr>
							<td>[applsurname]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_APPL_LNAME');?></td>
						</tr>
						<tr>
							<td>[department]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_JOB_DEPT');?></td>
						</tr>
						<tr>
							<td>[jobid]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_JOB_ID');?></td>
						</tr>
						<tr>
							<td>[applstatus]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_JOB_APPLSTATUS');?></td>
						</tr>
						<tr>
							<td>[appladmin]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_ENTRY_EDITOR');?></td>
						</tr>
						<tr>
							<td>[appltitle]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_CV_TITLE');?></td>
						</tr>
						<tr>
							<td>[applcovernote]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_COVERNOTE');?></td>
						</tr>
                     <?php break; ?>
                     <?php case 'adminsms' : ?>
						<tr>
							<td>[jobtitle]</td>
							<td><?php echo JText::_('COM_JOBBOARD_JOB_TITLE');?></td>
						</tr>
						<tr>
							<td>[location]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_JOB_LOCN');?></td>
						</tr>
						<tr>
							<td>[fromname]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_ORGANISATION');?></td>
						</tr>
                        <?php break; ?>
                     <?php case 'adminupdate' : ?>
						<tr>
							<td>[jobtitle]</td>
							<td><?php echo JText::_('COM_JOBBOARD_JOB_TITLE');?></td>
						</tr>
						<tr>
							<td>[jobid]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_JOB_ID');?></td>
						</tr>
						<tr>
							<td>[department]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_JOB_DEPT');?></td>
						</tr>
						<tr>
							<td>[location]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_JOB_LOCN');?></td>
						</tr>
						<tr>
							<td>[status]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_JOBPUBSTATUS');?></td>
						</tr>
						<tr>
							<td>[appladmin]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_ENTRY_EDITOR');?></td>
						</tr>
                        <?php break; ?>
                     <?php case 'adminupdate_application' : ?>
						<tr>
							<td>[toname]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_RECEIP_NAME');?></td>
						</tr>
						<tr>
							<td>[tosurname]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_RECEIP_SURNAME');?></td>
						</tr>
						<tr>
							<td>[applstatus]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_APPL_STATUS');?></td>
						</tr>
						<tr>
							<td>[jobtitle]</td>
							<td><?php echo JText::_('COM_JOBBOARD_JOB_TITLE');?></td>
						</tr>
						<tr>
							<td>[jobid]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_JOB_ID');?></td>
						</tr>
						<tr>
							<td>[department]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_JOB_DEPT');?></td>
						</tr>
						<tr>
							<td>[location]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_JOB_LOCN');?></td>
						</tr>
						<tr>
							<td>[appladmin]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_ENTRY_EDITOR');?></td>
						</tr>
                     <?php break; ?>
                     <?php case 'adminvite' : ?>
						<tr>
							<td>[jobtitle]</td>
							<td><?php echo JText::_('COM_JOBBOARD_JOB_TITLE');?></td>
						</tr>
						<tr>
							<td>[toname]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_RECEIP_NAME');?></td>
						</tr>
						<tr>
							<td>[fromname]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_ORGANISATION');?></td>
						</tr>
						<tr>
							<td>[link]</td>
							<td><?php echo JText::_('COM_JOBBOARD_INVITE_LINK');?></td>
						</tr>
                     <?php break; ?>
                     <?php case 'unsolicitednew' : ?>
						<tr>
							<td>[toname]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_EML_REC_NAME');?></td>
						</tr>
						<tr>
							<td>[cvtitle]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_CV_TITLE');?></td>
						</tr>
						<tr>
							<td>[fromname]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_ORGANISATION');?></td>
						</tr>
                        <?php break; ?>
                     <?php case 'userapproved' : ?>
						<tr>
							<td>[toname]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_EML_REC_NAME');?></td>
						</tr>
						<tr>
							<td>[jobtitle]</td>
							<td><?php echo JText::_('COM_JOBBOARD_JOB_TITLE');?></td>
						</tr>
						<tr>
							<td>[fromname]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_ORGANISATION');?></td>
						</tr>
                        <?php break; ?>
                     <?php case 'usernew' : ?>
						<tr>
							<td>[toname]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_EML_REC_NAME');?></td>
						</tr>
						<tr>
							<td>[jobtitle]</td>
							<td><?php echo JText::_('COM_JOBBOARD_JOB_TITLE');?></td>
						</tr>
						<tr>
							<td>[location]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_JOB_LOCN');?></td>
						</tr>
						<tr>
							<td>[fromname]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_ORGANISATION');?></td>
						</tr>
                        <?php break; ?>
                     <?php case 'userrejected' : ?>
						<tr>
							<td>[toname]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_EML_REC_NAME');?></td>
						</tr>
						<tr>
							<td>[jobtitle]</td>
							<td><?php echo JText::_('COM_JOBBOARD_JOB_TITLE');?></td>
						</tr>
						<tr>
							<td>[fromname]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_ORGANISATION');?></td>
						</tr>
                        <?php break; ?>
                     <?php case 'usersms' : ?>
						<tr>
							<td>[jobtitle]</td>
							<td><?php echo JText::_('COM_JOBBOARD_JOB_TITLE');?></td>
						</tr>
						<tr>
							<td>[location]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_JOB_LOCN');?></td>
						</tr>
						<tr>
							<td>[fromname]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_ORGANISATION');?></td>
						</tr>
                        <?php break; ?>
                     <?php case 'userinvite' : ?>
						<tr>
							<td>[toname]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_RECEIP_NAME');?></td>
						</tr>
						<tr>
							<td>[fromname]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_ORGANISATION');?></td>
						</tr>
						<tr>
							<td>[jobtitle]</td>
							<td><?php echo JText::_('COM_JOBBOARD_JOB_TITLE');?></td>
						</tr>
						<tr>
							<td>[message]</td>
							<td><?php echo JText::_('COM_JOBBOARD_INVITE_MSG');?></td>
						</tr>
						<tr>
							<td>[cvprofile]</td>
							<td><?php echo JText::_('COM_JOBBOARD_INVITE_CV');?></td>
						</tr>
						<tr>
							<td>[link]</td>
							<td><?php echo JText::_('COM_JOBBOARD_INVITE_LINK');?></td>
						</tr>
                     <?php break; ?>
                     <?php case 'adminupdate_unsolicited' : ?>
						<tr>
							<td>[toname]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_EML_REC_NAME');?></td>
						</tr>
						<tr>
							<td>[tosurname]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_RECEIP_SURNAME');?></td>
						</tr>
						<tr>
							<td>[applicantid]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_UNSOLICITED_APPL_ID');?></td>
						</tr>
						<tr>
							<td>[appladmin]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_ENTRY_EDITOR');?></td>
						</tr>
                        <?php break; ?>
                     <?php case 'adminnew_unsolicited' : ?>
						<tr>
							<td>[toname]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_EML_REC_NAME');?></td>
						</tr>
						<tr>
							<td>[tosurname]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_RECEIP_SURNAME');?></td>
						</tr>
						<tr>
							<td>[cvtitle]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_CV_TITLE');?></td>
						</tr>
						<tr>
							<td>[fromname]</td>
							<td><?php echo JText::_('COM_JOBBOARD_SUB_ORGANISATION');?></td>
						</tr>
                        <?php break; ?>
                    <?php } ?>
					</table>
				</td>
			</tr>
		</table>
	</fieldset>
	<input type="hidden" name="id" value="<?php echo $this->row->id;?>" />
	<input type="hidden" name="type" value="<?php echo $this->row->type;?>" />
	<input type="hidden" name="option" value="<?php echo $option;?>" />
	<input type="hidden" name="view" value="<?php echo JRequest::getVar('view',''); ?>" />
	<input type="hidden" name="task" value="<?php echo JRequest::getVar('task',''); ?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>

 <?php echo $this->jb_render; ?>