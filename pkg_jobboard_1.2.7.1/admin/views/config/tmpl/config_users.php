<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
?>

<div style="width:100%">
		<h3><?php echo JText::_('COM_JOBBOARD_CFG_USERS');?></h3>
        <table class="admintable" style="width:100%">
            <tr>
				<td class="configsectionhead" colspan="2"><?php echo JText::_('COM_JOBBOARD_USER_GRPS');?>&nbsp;&nbsp;&bull;&nbsp;&nbsp;<small><a href="index.php?option=com_jobboard&amp;view=users"><?php echo JText::_('COM_JOBBOARD_USERS_LINK_USERLIST') ?></a></small></td>
			</tr>
           <tr class="usergrp">
             <th><?php echo JText::_('COM_JOBBOARD_USER_GRP_ID');?></th>
             <th><?php echo JText::_('COM_JOBBOARD_USER_GRP_NAME');?></th>
             <th><?php echo JText::_('COM_JOBBOARD_USER_POST_JOBS');?></th>
             <th><?php echo JText::_('COM_JOBBOARD_USER_MANAGE_JOBS');?></th>
             <th><?php echo JText::_('COM_JOBBOARD_USER_APPLYTO_JOBS');?></th>
             <th><?php echo JText::_('COM_JOBBOARD_USER_MANAGE_APPLICANTS');?></th>
             <th><?php echo JText::_('COM_JOBBOARD_USER_SEARCH_CVS');?></th>
             <th><?php echo JText::_('COM_JOBBOARD_USER_SEARCH_PRIV_CVS');?></th>
             <th><?php echo JText::_('COM_JOBBOARD_USER_CREATE_QNAIRES');?></th>
             <th><?php echo JText::_('COM_JOBBOARD_USER_MANAGE_QNAIRES');?></th>
           </tr>
             <?php $i = 0 ?>
             <?php $img = array('tick.png', 'publish_x.png') ?>
             <?php $groups_mini = array() ?>
             <?php foreach($this->user_groups as $group) : ?>
               <tr class="usergrp">
                   <td>
                      <?php echo $group->id; ?>
                      <input type="hidden" name="group[<?php echo $group->id; ?>][id]" value="<?php echo $group->id; ?>" />
                   </td>
                   <td>
                      <input type="text" name="group[<?php echo $group->id; ?>][group_name]" value="<?php echo $group->group_name?>" />
                   </td>
                   <td align="center">
                      <input type="checkbox" name="group[<?php echo $group->id; ?>][post_jobs]" value="yes" <?php if($group->post_jobs == 1) echo 'checked="checked"' ?>/>
                   </td>
                   <td align="center">
                      <input type="checkbox" name="group[<?php echo $group->id; ?>][manage_jobs]" value="yes" <?php if($group->manage_jobs == 1) echo 'checked="checked"' ?>/>
                   </td>
                   <td align="center">
                      <input type="checkbox" name="group[<?php echo $group->id; ?>][apply_to_jobs]" value="yes" <?php if($group->apply_to_jobs == 1) echo 'checked="checked"' ?>/>
                   </td>
                   <td align="center">
                      <input type="checkbox" name="group[<?php echo $group->id; ?>][manage_applicants]" value="yes" <?php if($group->manage_applicants == 1) echo 'checked="checked"' ?>/>
                   </td>
                   <td align="center">
                      <input type="checkbox" name="group[<?php echo $group->id; ?>][search_cvs]" value="yes" <?php if($group->search_cvs == 1) echo 'checked="checked"' ?>/>
                   </td>
                   <td align="center">
                      <input type="checkbox" name="group[<?php echo $group->id; ?>][search_private_cvs]" value="yes" <?php if($group->search_private_cvs == 1) echo 'checked="checked"' ?>/>
                   </td>
                   <td align="center">
                      <input type="checkbox" name="group[<?php echo $group->id; ?>][create_questionnaires]" value="yes" <?php if($group->create_questionnaires == 1) echo 'checked="checked"' ?>/>
                   </td>
                   <td align="center">
                      <input type="checkbox" name="group[<?php echo $group->id; ?>][manage_questionnaires]" value="yes" <?php if($group->manage_questionnaires == 1) echo 'checked="checked"' ?>/>
                   </td>
               </tr>
               <?php $groups_mini[] = array($group->id, $group->group_name) ?>
             <?php endforeach ?>
        </table>

		<table class="admintable config left">
			<tr>
				<td class="configsectionhead" colspan="2"><?php echo JText::_('COM_JOBBOARD_USER_GRP_DEFAULTS');?></td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_USER_GRP_DEFAULTS_USR');?>
				</td>
				<td>
            		<select name="default_user_grp" id="default_user_grp" title="<?php echo JText::_('COM_JOBBOARD_USER_GRP_DEFAULTS_USR')?>">
                        <?php foreach($groups_mini as $grp) : ?>
                           <option value="<?php echo $grp[0] ?>" <?php if($this->row->default_user_grp == $grp[0]) echo 'selected="selected"'; ?>><?php echo $grp[1] ?></option>
                        <?php endforeach ?>
                    </select>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_USER_GRP_DEFAULTS_EMPL');?>
				</td>
				<td>
            		<select name="default_empl_grp" id="default_empl_grp" title="<?php echo JText::_('COM_JOBBOARD_USER_GRP_DEFAULTS_EMPL')?>">
                        <?php foreach($groups_mini as $grp) : ?>
                           <option value="<?php echo $grp[0] ?>" <?php if($this->row->default_empl_grp == $grp[0]) echo 'selected="selected"'; ?>><?php echo $grp[1] ?></option>
                        <?php endforeach ?>
                    </select>
				</td>
			</tr>
            <tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_USER_FEATURE_JOBS');?>
				</td>
				<td>
                	<input type="radio" name="empl_default_feature" id="empl_default_feature0" value="0" <?php if($this->row->empl_default_feature == 0) echo 'checked="checked"'; ?> class="inputbox" />
                	<label for="empl_default_feature0"><?php echo JText::_('COM_JOBBOARD_JNO'); ?></label>
                	<input type="radio" name="empl_default_feature" id="empl_default_feature1" value="1" <?php if($this->row->empl_default_feature == 1) echo 'checked="checked"'; ?> class="inputbox" />
                	<label for="empl_default_feature1"><?php echo JText::_('COM_JOBBOARD_JYES'); ?></label>
				</td>
			</tr>
            <tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_USER_APPLASTATUSVIEW');?>
				</td>
				<td>
                	<input type="radio" name="user_show_applstatus" id="user_show_applstatus0" value="0" <?php if($this->row->user_show_applstatus == 0) echo 'checked="checked"'; ?> class="inputbox" />
                	<label for="user_show_applstatus0"><?php echo JText::_('COM_JOBBOARD_JNO'); ?></label>
                	<input type="radio" name="user_show_applstatus" id="user_show_applstatus1" value="1" <?php if($this->row->user_show_applstatus == 1) echo 'checked="checked"'; ?> class="inputbox" />
                	<label for="user_show_applstatus1"><?php echo JText::_('COM_JOBBOARD_JYES'); ?></label>
				</td>
			</tr>
			<tr>
				<td class="configsectionhead" colspan="2"><?php echo JText::_('COM_JOBBOARD_USER_PROFILES');?></td>
			</tr>
			<tr>
				<td align="left" class="note" colspan="2">
                    (<?php echo JText::_('COM_JOBBOARD_USER_PROFILES_NOTE');?>)
                </td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_USER_PROFS_MAXFILES');?>
				</td>
				<td>
            		<input name="max_files" id="max_files" type="text" value="<?php echo $this->row->max_files ?>" />
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_USER_PROFS_MAXQUALS');?>
				</td>
				<td>
            		<input name="max_quals" id="max_quals" type="text" value="<?php echo $this->row->max_quals ?>" />
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_USER_PROFS_MAXEMPLS');?>
				</td>
				<td>
            		<input name="max_employers" id="max_employers" type="text" value="<?php echo $this->row->max_employers ?>" />
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_USER_PROFS_MAXSKILLS');?>
				</td>
				<td>
            		<input name="max_skills" id="max_skills" type="text" value="<?php echo $this->row->max_skills ?>" />
				</td>
			</tr>
            <tr>
				<td class="configsectionhead" colspan="2"><?php echo JText::_('COM_JOBBOARD_USER_PROFS_LINKEDIN');?></td>
			</tr>
            <tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_USER_LINKEDIN_ALLOW');?>
				</td>
				<td>
                	<input type="radio" name="allow_linkedin_imports" id="allow_linkedin_imports0" value="0" <?php if($this->row->allow_linkedin_imports == 0) echo 'checked="checked"'; ?> class="inputbox" />
                	<label for="allow_linkedin_imports0"><?php echo JText::_('COM_JOBBOARD_JNO'); ?></label>
                	<input type="radio" name="allow_linkedin_imports" id="allow_linkedin_imports1" value="1" <?php if($this->row->allow_linkedin_imports == 1) echo 'checked="checked"'; ?> class="inputbox" />
                	<label for="allow_linkedin_imports1"><?php echo JText::_('COM_JOBBOARD_JYES'); ?></label>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_USER_LINKEDIN_KEY');?>
				</td>
				<td>
            		<input name="linkedin_key" id="linkedin_key" type="text" value="<?php echo $this->row->linkedin_key ?>" />
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_USER_LINKEDIN_SECRET');?>
				</td>
				<td>
            		<input name="linkedin_secret" id="linkedin_secret" type="text" value="<?php echo $this->row->linkedin_secret ?>" />
				</td>
			</tr>
            <tr>
                <td align="right" class="key"><?php echo JText::_('COM_JOBBOARD_USER_LINKEDIN_NOKEY');?></td>
				<td><small><a href="https://www.linkedin.com/secure/developer" target="blank"><?php echo JText::_('COM_JOBBOARD_USER_LINKEDIN_REGISTER');?></a></small></td>
			</tr>
		</table>
		<table class="admintable config right">
            <tr>
				<td class="configsectionhead" colspan="2"><?php echo JText::_('COM_JOBBOARD_USER_FE_ACCESS');?></td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_FE_ALLOW_REG');?>
				</td>
    				<td>
                    	<input type="radio" name="allow_registration" id="allow_registration0" value="0" <?php if($this->row->allow_registration == 0) echo 'checked="checked"'; ?> class="inputbox" />
                    	<label for="allow_registration0"><?php echo JText::_('COM_JOBBOARD_JNO'); ?></label>
                    	<input type="radio" name="allow_registration" id="allow_registration1" value="1" <?php if($this->row->allow_registration == 1) echo 'checked="checked"'; ?> class="inputbox" />
                    	<label for="allow_registration1"><?php echo JText::_('COM_JOBBOARD_JYES'); ?></label>
    				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_FE_SECURE_LOGIN');?>
				</td>
    				<td>
                    	<input type="radio" name="secure_login" id="secure_login0" value="0" <?php if($this->row->secure_login == 0) echo 'checked="checked"'; ?> class="inputbox" />
                    	<label for="secure_login0"><?php echo JText::_('COM_JOBBOARD_JNO'); ?></label>
                    	<input type="radio" name="secure_login" id="secure_login1" value="1" <?php if($this->row->secure_login == 1) echo 'checked="checked"'; ?> class="inputbox" />
                    	<label for="secure_login1"><?php echo JText::_('COM_JOBBOARD_JYES'); ?></label>
    				</td>
			</tr>
            <tr>
				<td class="configsectionhead" colspan="2"><?php echo JText::_('COM_JOBBOARD_FE_CAPTCHA');?></td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_FE_CAPTCHA_REG');?>
				</td>
    				<td>
                    	<input type="radio" name="captcha_reg" id="captcha_reg0" value="0" <?php if($this->row->captcha_reg == 0) echo 'checked="checked"'; ?> class="inputbox" />
                    	<label for="captcha_reg0"><?php echo JText::_('COM_JOBBOARD_JNO'); ?></label>
                    	<input type="radio" name="captcha_reg" id="captcha_reg1" value="1" <?php if($this->row->captcha_reg == 1) echo 'checked="checked"'; ?> class="inputbox" />
                    	<label for="captcha_reg1"><?php echo JText::_('COM_JOBBOARD_JYES'); ?></label>
    				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_FE_CAPTCHA_LOGIN');?>
                    <br /><small>(<?php echo JText::_('COM_JOBBOARD_FE_CAPTCHA_LOGIN_NOTE');?>)</small>
				</td>
    				<td>
                    	<input type="radio" name="captcha_login" id="captcha_login0" value="0" <?php if($this->row->captcha_login == 0) echo 'checked="checked"'; ?> class="inputbox" />
                    	<label for="captcha_login0"><?php echo JText::_('COM_JOBBOARD_JNO'); ?></label>
                    	<input type="radio" name="captcha_login" id="captcha_login1" value="1" <?php if($this->row->captcha_login == 1) echo 'checked="checked"'; ?> class="inputbox" />
                    	<label for="captcha_login1"><?php echo JText::_('COM_JOBBOARD_JYES'); ?></label>
    				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_FE_CAPTCHA_PUBLIC');?>
                    <br /><small>(<?php echo JText::_('COM_JOBBOARD_FE_CAPTCHA_PUB_IE');?>)</small>
				</td>
    				<td>
                    	<input type="radio" name="captcha_public" id="captcha_public0" value="0" <?php if($this->row->captcha_public == 0) echo 'checked="checked"'; ?> class="inputbox" />
                    	<label for="captcha_public0"><?php echo JText::_('COM_JOBBOARD_JNO'); ?></label>
                    	<input type="radio" name="captcha_public" id="captcha_public1" value="1" <?php if($this->row->captcha_public == 1) echo 'checked="checked"'; ?> class="inputbox" />
                    	<label for="captcha_public1"><?php echo JText::_('COM_JOBBOARD_JYES'); ?></label>
    				</td>
			</tr>
		</table>
</div>
