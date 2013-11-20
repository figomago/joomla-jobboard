<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');

?>
<?php $editor = & JFactory :: getEditor(); ?>
<h3><?php echo JText::_('COM_JOBBOARD_GENERAL_CONFIG');?></h3>
<div style="width:100%">
		<table class="admintable config left">
			<tr>
				<td class="configsectionhead" colspan="2"><?php echo JText::_('COM_JOBBOARD_NOTIFICATION_SETTINGS');?></td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_ORGANISATION_NAME');?>
				</td>
				<td>
					<input class="text_area" type="text" name="organisation" id="organisation" size="50" maxlength="50" value="<?php echo $this->row->organisation;?>" />
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_SEND').' <small>('.JText::_('COM_JOBBOARD_ADM').')</small> '. JText::_('COM_JOBBOARD_NOTIFICATIONS_TO');?>
				</td>
				<td>
					<input class="text_area" type="text" name="from_mail" id="from_mail" size="50" maxlength="50" value="<?php echo $this->row->from_mail;?>" />
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_REPLY_TO').' <small>('.JText::_('COM_JOBBOARD_FOR_SITE_USERS').')</small>';?>
				</td>
				<td>
					<input class="text_area" type="text" name="reply_to" id="reply_to" size="50" maxlength="50" value="<?php echo $this->row->reply_to;?>" />
				</td>
			</tr>
			<tr>
				<td class="configsectionhead" colspan="2"><?php echo JText::_('COM_JOBBOARD_DEFAULT_NOTIFICATION_SETTINGS');?></td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_NOTIF_DEPT_ADMIN');?>
				</td>
				<td>
                	<input type="radio" name="dept_notify_admin" id="dept_notify_admin0" value="0" <?php if($this->row->dept_notify_admin == 0) echo 'checked="checked"'; ?> class="inputbox" />
                	<label for="dept_notify_admin0"><?php echo JText::_('COM_JOBBOARD_JNO'); ?></label>
                	<input type="radio" name="dept_notify_admin" id="dept_notify_admin1" value="1" <?php if($this->row->dept_notify_admin == 1) echo 'checked="checked"'; ?> class="inputbox" />
                	<label for="dept_notify_admin1"><?php echo JText::_('COM_JOBBOARD_JYES'); ?></label>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_NOTIF_DEPT_CONTACT');?>
				</td>
				<td>
                	<input type="radio" name="dept_notify_contact" id="dept_notify_contact0" value="0" <?php if($this->row->dept_notify_contact == 0) echo 'checked="checked"'; ?> class="inputbox" />
                	<label for="dept_notify_contact0"><?php echo JText::_('COM_JOBBOARD_JNO'); ?></label>
                	<input type="radio" name="dept_notify_contact" id="dept_notify_contact1" value="1" <?php if($this->row->dept_notify_contact == 1) echo 'checked="checked"'; ?> class="inputbox" />
                	<label for="dept_notify_contact1"><?php echo JText::_('COM_JOBBOARD_JYES'); ?></label>
				</td>
			</tr>
				<tr>
					<td class="configsectionhead" colspan="2"><?php echo JText::_('COM_JOBBOARD_UPL_SETTINGS');?></td>
				</tr>
				<tr>
					<td align="right" class="key">
						<?php echo JText::_('COM_JOBBOARD_UPL_MAXSIZE');?>
					</td>
					<td>
                        <?php $mb_string = JText::_('COM_JOBBOARD_MEGABYTES') ?>
						<select name="max_filesize" id="max_filesize" title="<?php echo JText::_('COM_JOBBOARD_UPL_MAXSIZE')?>">
	                        <option value="1" <?php if($this->row->max_filesize == 1) echo 'selected="selected"'; ?>>1 <?php echo $mb_string ?></option>
	                        <option value="2" <?php if($this->row->max_filesize == 2) echo 'selected="selected"'; ?>>2 <?php echo $mb_string ?></option>
	                        <option value="3" <?php if($this->row->max_filesize == 3) echo 'selected="selected"'; ?>>3 <?php echo $mb_string ?></option>
	                        <option value="4" <?php if($this->row->max_filesize == 4) echo 'selected="selected"'; ?>>4 <?php echo $mb_string ?></option>
	                        <option value="5" <?php if($this->row->max_filesize == 5) echo 'selected="selected"'; ?>>5 <?php echo $mb_string ?></option>
	                        <option value="6" <?php if($this->row->max_filesize == 6) echo 'selected="selected"'; ?>>6 <?php echo $mb_string ?></option>
	                        <option value="7" <?php if($this->row->max_filesize == 7) echo 'selected="selected"'; ?>>7 <?php echo $mb_string ?></option>
	                        <option value="8" <?php if($this->row->max_filesize == 8) echo 'selected="selected"'; ?>>8 <?php echo $mb_string ?></option>
	                        <option value="9" <?php if($this->row->max_filesize == 9) echo 'selected="selected"'; ?>>9 <?php echo $mb_string ?></option>
	                        <option value="10" <?php if($this->row->max_filesize == 10) echo 'selected="selected"'; ?>>10 <?php echo $mb_string ?></option>
	                        <option value="15" <?php if($this->row->max_filesize == 15) echo 'selected="selected"'; ?>>15 <?php echo $mb_string ?></option>
	                        <option value="20" <?php if($this->row->max_filesize == 20) echo 'selected="selected"'; ?>>20 <?php echo $mb_string ?></option>
	                    </select>
                    </td>
                </tr>
				<tr>
					<td class="configsectionhead" colspan="2"><?php echo JText::_('COM_JOBBOARD_DATE_FORMATS');?></td>
				</tr>
				<tr>
					<td align="right" class="key">
						<?php echo JText::_('COM_JOBBOARD_SHORT_DATE_FORMAT');?>
					</td>
					<td>
						<select name="short_date_format" id="short_date_format" title="<?php echo JText::_('COM_JOBBOARD_SHORT_DATE_FORMAT')?>">
	                        <option value="0" <?php if($this->row->short_date_format == 0) echo 'selected="selected"'; ?>><?php echo JText::_('COM_JOBBOARD_SHORT_DATE0'); ?></option>
	                        <option value="1" <?php if($this->row->short_date_format == 1) echo 'selected="selected"'; ?>><?php echo JText::_('COM_JOBBOARD_SHORT_DATE1'); ?></option>
	                        <option value="2" <?php if($this->row->short_date_format == 2) echo 'selected="selected"'; ?>><?php echo JText::_('COM_JOBBOARD_SHORT_DATE2'); ?></option>
	                        <option value="3" <?php if($this->row->short_date_format == 3) echo 'selected="selected"'; ?>><?php echo JText::_('COM_JOBBOARD_SHORT_DATE3'); ?></option>
	                    </select>
                    </td>
                  </tr>
				<tr>
					<td align="right" class="key">
						<?php echo JText::_('COM_JOBBOARD_DATE_SEPARATOR');?>
					</td>
					<td>
						<select name="date_separator" id="date_separator" title="<?php echo JText::_('COM_JOBBOARD_DATE_SEPARATOR')?>">
	                        <option value="0" <?php if($this->row->date_separator == 0) echo 'selected="selected"'; ?>><?php echo JText::_('COM_JOBBOARD_DATESEP0'); ?></option>
	                        <option value="1" <?php if($this->row->date_separator == 1) echo 'selected="selected"'; ?>><?php echo JText::_('COM_JOBBOARD_DATESEP1'); ?></option>
	                        <option value="2" <?php if($this->row->date_separator == 2) echo 'selected="selected"'; ?>><?php echo JText::_('COM_JOBBOARD_DATESEP2'); ?></option>
	                        <option value="3" <?php if($this->row->date_separator == 3) echo 'selected="selected"'; ?>><?php echo JText::_('COM_JOBBOARD_DATESEP3'); ?></option>
	                    </select>
                    </td>
				</tr>
				<tr>
					<td align="right" class="key">
						<?php echo JText::_('COM_JOBBOARD_LONG_DATE_FORMAT');?>
					</td>
					<td>
						<select name="long_date_format" id="long_date_format" title="<?php echo JText::_('COM_JOBBOARD_LONG_DATE_FORMAT')?>">
	                        <option value="0" <?php if($this->row->long_date_format == 0) echo 'selected="selected"'; ?>><?php echo JText::_('COM_JOBBOARD_LONG_DATE0'); ?></option>
	                        <option value="1" <?php if($this->row->long_date_format == 1) echo 'selected="selected"'; ?>><?php echo JText::_('COM_JOBBOARD_LONG_DATE1'); ?></option>
	                        <option value="2" <?php if($this->row->long_date_format == 2) echo 'selected="selected"'; ?>><?php echo JText::_('COM_JOBBOARD_LONG_DATE2'); ?></option>
	                    </select>
                    </td>
				</tr>
    			<tr>
    				<td align="right" class="key">
    					<?php echo JText::_('COM_JOBBOARD_NOTIF_DEPT_CONTACT');?>
    				</td>
    				<td>
                    	<input type="radio" name="admin_show_backlink" id="admin_show_backlink0" value="0" <?php if($this->row->admin_show_backlink == 0) echo 'checked="checked"'; ?> class="inputbox" />
                    	<label for="admin_show_backlink0"><?php echo JText::_('COM_JOBBOARD_JNO'); ?></label>
                    	<input type="radio" name="admin_show_backlink" id="admin_show_backlink1" value="1" <?php if($this->row->admin_show_backlink == 1) echo 'checked="checked"'; ?> class="inputbox" />
                    	<label for="admin_show_backlink1"><?php echo JText::_('COM_JOBBOARD_JYES'); ?></label>
    				</td>
    			</tr>
				<tr>
					<td class="configsectionhead" colspan="2"><?php echo JText::_('COM_JOBBOARD_FOOTER_BACKLINKS');?></td>
				</tr>
    			<tr>
    				<td align="right" class="key">
    					<?php echo JText::_('COM_JOBBOARD_FOOTER_BACKLINKS_ADMIN_SHOW');?>
    				</td>
    				<td>
                    	<input type="radio" name="admin_show_backlink" id="admin_show_backlink0" value="0" <?php if($this->row->admin_show_backlink == 0) echo 'checked="checked"'; ?> class="inputbox" />
                    	<label for="admin_show_backlink0"><?php echo JText::_('COM_JOBBOARD_JNO'); ?></label>
                    	<input type="radio" name="admin_show_backlink" id="admin_show_backlink1" value="1" <?php if($this->row->admin_show_backlink == 1) echo 'checked="checked"'; ?> class="inputbox" />
                    	<label for="admin_show_backlink1"><?php echo JText::_('COM_JOBBOARD_JYES'); ?></label>
    				</td>
    			</tr>
    			<tr>
    				<td align="right" class="key">
    					<?php echo JText::_('COM_JOBBOARD_FOOTER_BACKLINKS_FRONT_SHOW');?>
    				</td>
    				<td>
                    	<input type="radio" name="user_show_backlink" id="user_show_backlink0" value="0" <?php if($this->row->user_show_backlink == 0) echo 'checked="checked"'; ?> class="inputbox" />
                    	<label for="user_show_backlink0"><?php echo JText::_('COM_JOBBOARD_JNO'); ?></label>
                    	<input type="radio" name="user_show_backlink" id="user_show_backlink1" value="1" <?php if($this->row->user_show_backlink == 1) echo 'checked="checked"'; ?> class="inputbox" />
                    	<label for="user_show_backlink1"><?php echo JText::_('COM_JOBBOARD_JYES'); ?></label>
    				</td>
    			</tr>
		</table>
		<table class="admintable config right">
    			<tr>
    				<td class="configsectionhead" colspan="2"><?php echo JText::_('COM_JOBBOARD_FRONTEND');?>
    				</td>
                </tr>
    			<tr>
    				<td class="configsectionhead" colspan="2"><?php echo JText::_('COM_JOBBOARD_FRONTEND_JOBLIMIT');?>
                        <br /><small>(<?php echo JText::_('COM_JOBBOARD_FRONTEND_JOBLIMIT_NOTE');?>)</small>
    				</td>
                </tr>
                <tr>
    				<td>
					    <input name="home_jobs_limit" id="home_jobs_limit" type="text" value="<?php echo $this->row->home_jobs_limit; ?>" />
    				</td>
    			</tr>
    			<tr>
    				<td class="configsectionhead" colspan="2"><?php echo JText::_('COM_JOBBOARD_FRONTEND_INTRO_TITLE');?>
    				</td>
                </tr>
                <tr>
    				<td>
					    <input name="home_intro_title" id="home_intro_title" type="text" value="<?php echo $this->row->home_intro_title; ?>" />
    				</td>
    			</tr>
				<tr>
					<td class="configsectionhead" colspan="2"><?php echo JText::_('COM_JOBBOARD_FRONTEND_INTRO');?></td>
				</tr>
				<tr>
    			  <td>
                      <?php echo $editor->display('home_intro', ($this->row->home_intro == '')? '' : $this->row->home_intro, null, null, '60', '20', false);  ?>
                  </td>
				</tr>
		</table>
   </div>

<?php $getHomeIntro = $editor->getContent('home_intro'); ?>
<script language="javascript" type="text/javascript">

  Joomla.submitbutton = function(pressbutton)
  {
    var form = document.adminForm;
    if (pressbutton == 'save' || pressbutton == 'apply' )
      {
        var text = <?php echo $getHomeIntro; ?>
        text = encHtml(text);
        <?php echo $editor->save( 'home_intro' ); ?>
        submitform( pressbutton );
        return;
      }
  }

function encHtml(h){encdHtml=escape(h);encdHtml=encdHtml.replace(/\//g,"%2F");encdHtml=encdHtml.replace(/\?/g,"%3F");encdHtml=encdHtml.replace(/=/g,"%3D");encdHtml=encdHtml.replace(/&/g,"%26");encdHtml=encdHtml.replace(/@/g,"%40");return encdHtml;
}
</script>