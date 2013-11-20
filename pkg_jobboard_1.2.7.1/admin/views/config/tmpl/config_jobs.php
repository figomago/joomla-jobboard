<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
?>
<div style="width:100%">
		<h3><?php echo JText::_('COM_JOBBOARD_CFG_JOBS');?></h3>
		<table class="admintable config left">
			<tr>
				<td class="configsectionhead" colspan="2"><?php echo JText::_('COM_JOBBOARD_DEFAULT_JOB_SETTINGS');?></td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_DEFAULT_DEPT');?>
				</td>
				<td>
					<select name="default_dept" id="default_dept">
                       <?php foreach($this->depts as $dept) : ?>
                        <option value="<?php echo $dept->id; ?>" <?php if($dept->id == $this->row->default_dept) echo 'selected="selected"'; ?>><?php echo $dept->name; ?></option>
                       <?php endforeach; ?>
                    </select>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_DEFAULT_JOBTYPE');?>
				</td>
				<td>
					<select name="default_jobtype" id="default_jobtype">
                       <?php foreach($this->jobtypes as $jobtype) : ?>
                        <option value="<?php echo $jobtype->id; ?>" <?php if($jobtype->id == $this->row->default_jobtype) echo 'selected="selected"'; ?>><?php echo $jobtype->type; ?></option>
                       <?php endforeach; ?>
                    </select>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_DEFAULT_CAREERLEVEL');?>
				</td>
				<td>
					<select name="default_career" id="default_career">
                       <?php foreach($this->careers as $career) : ?>
                        <option value="<?php echo $career->id; ?>" <?php if($career->id == $this->row->default_career) echo 'selected="selected"'; ?>><?php echo $career->description; ?></option>
                       <?php endforeach; ?>
                    </select>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_DEFAULT_EDUCATIONLEVEL');?>
				</td>
				<td>
					<select name="default_edu" id="default_edu">
                       <?php foreach($this->edu as $education) : ?>
                        <option value="<?php echo $education->id; ?>" <?php if($education->id == $this->row->default_edu) echo 'selected="selected"'; ?>><?php echo $education->level; ?></option>
                       <?php endforeach; ?>
                    </select>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_DEFAULT_CATEGORY');?>
				</td>
				<td>
					<select name="default_category" id="default_category">
                       <?php foreach($this->categories as $category) : ?>
                        <option value="<?php echo $category->id; ?>" <?php if($category->id == $this->row->default_category) echo 'selected="selected"'; ?>><?php echo $category->type; ?></option>
                       <?php endforeach; ?>
                    </select>
				</td>
			</tr>
			<tr>
				<td class="configsectionhead" colspan="2"><?php echo JText::_('COM_JOBBOARD_JOB_APPL');?></td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_ALLOW_UNSOLICITED');?>
				</td>
				<td>
                	<input type="radio" name="allow_unsolicited" id="allow_unsolicited0" value="0" <?php if($this->row->allow_unsolicited == 0) echo 'checked="checked"'; ?> class="inputbox" />
                	<label for="allow_unsolicited0"><?php echo JText::_('COM_JOBBOARD_JNO'); ?></label>
                	<input type="radio" name="allow_unsolicited" id="allow_unsolicited1" value="1" <?php if($this->row->allow_unsolicited == 1) echo 'checked="checked"'; ?> class="inputbox" />
                	<label for="allow_unsolicited1"><?php echo JText::_('COM_JOBBOARD_JYES'); ?></label>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_ALLOW_APPLICATIONS');?>
				</td>
				<td>
                	<input type="radio" name="allow_applications" id="allow_applications0" value="0" <?php if($this->row->allow_applications == 0) echo 'checked="checked"'; ?> class="inputbox" />
                	<label for="allow_applications0"><?php echo JText::_('COM_JOBBOARD_JNO'); ?></label>
                	<input type="radio" name="allow_applications" id="allow_applications1" value="1" <?php if($this->row->allow_applications == 1) echo 'checked="checked"'; ?> class="inputbox" />
                	<label for="allow_applications1"><?php echo JText::_('COM_JOBBOARD_JYES'); ?></label>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_ALLOW_ONCEOFF_APPLICATIONS');?>
				</td>
				<td>
                	<input type="radio" name="allow_once_off_applications" id="allow_once_off_applications0" value="0" <?php if($this->row->allow_once_off_applications == 0) echo 'checked="checked"'; ?> class="inputbox" />
                	<label for="allow_once_off_applications0"><?php echo JText::_('COM_JOBBOARD_JNO'); ?></label>
                	<input type="radio" name="allow_once_off_applications" id="allow_once_off_applications1" value="1" <?php if($this->row->allow_once_off_applications == 1) echo 'checked="checked"'; ?> class="inputbox" />
                	<label for="allow_once_off_applications1"><?php echo JText::_('COM_JOBBOARD_JYES'); ?></label>
				</td>
			</tr>
            <tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_EMAIL_ATTACHMENTS');?>
				</td>
				<td>
                	<input type="radio" name="email_cvattach" id="email_cvattach0" value="0" <?php if($this->row->email_cvattach == 0) echo 'checked="checked"'; ?> class="inputbox" />
                	<label for="email_cvattach0"><?php echo JText::_('COM_JOBBOARD_JNO'); ?></label>
                	<input type="radio" name="email_cvattach" id="email_cvattach1" value="1" <?php if($this->row->email_cvattach == 1) echo 'checked="checked"'; ?> class="inputbox" />
                	<label for="email_cvattach1"><?php echo JText::_('COM_JOBBOARD_JYES'); ?></label>
				</td>
			</tr>
			<tr>
				<td class="configsectionhead" colspan="2"><?php echo JText::_('COM_JOBBOARD_DEFAULT_LISTING_SETTINGS');?></td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_DEFAULT_ELAPSED_DAYS');?>
				</td>
				<td>
					<select name="default_post_range" id="default_post_range" title="<?php echo JText::_('COM_JOBBOARD_DAYS')?>">
                        <option value="0" <?php if($this->row->default_post_range == 0) echo 'selected="selected"'; ?>><?php echo 0; ?></option>
                        <option value="1" <?php if($this->row->default_post_range == 1) echo 'selected="selected"'; ?>><?php echo 1; ?></option>
                        <option value="2" <?php if($this->row->default_post_range == 2) echo 'selected="selected"'; ?>><?php echo 2; ?></option>
                        <option value="3" <?php if($this->row->default_post_range == 3) echo 'selected="selected"'; ?>><?php echo 3; ?></option>
                        <option value="7" <?php if($this->row->default_post_range == 7) echo 'selected="selected"'; ?>><?php echo 7; ?></option>
                        <option value="30" <?php if($this->row->default_post_range == 30) echo 'selected="selected"'; ?>><?php echo 30; ?></option>
                        <option value="60" <?php if($this->row->default_post_range == 60) echo 'selected="selected"'; ?>><?php echo 60; ?></option>
                    </select>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_LIST_LAYOUT_DEFLT');?>
				</td>
				<td>
					<select name="default_list_layout" id="default_list_layout" title="<?php echo JText::_('COM_JOBBOARD_DAYS')?>">
                        <option value="0" <?php if($this->row->default_list_layout == 0) echo 'selected="selected"'; ?>><?php echo JText::_('COM_JOBBOARD_LIST_LAYOUT_LIST'); ?></option>
                        <option value="1" <?php if($this->row->default_list_layout == 1) echo 'selected="selected"'; ?>><?php echo JText::_('COM_JOBBOARD_LIST_LAYOUT_TABLE'); ?></option>
                    </select>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_LIST_FEATURE_DAYS');?>
				</td>
				<td>
					<input name="feature_length" id="feature_length" type="text" value="<?php echo $this->row->feature_length ?>" />
				</td>
			</tr>
  			<tr>
  				<td align="right" class="key">
  					<?php echo JText::_('COM_JOBBOARD_RSS_FEEDS_ENABLE');?>
  				</td>
  				<td>
                  	<input type="radio" name="show_rss" id="show_rss0" value="0" <?php if($this->row->show_rss == 0) echo 'checked="checked"'; ?> class="inputbox" />
                  	<label for="show_rss0"><?php echo JText::_('COM_JOBBOARD_JNO'); ?></label>
                  	<input type="radio" name="show_rss" id="show_rss1" value="1" <?php if($this->row->show_rss == 1) echo 'checked="checked"'; ?> class="inputbox" />
                  	<label for="show_rss1"><?php echo JText::_('COM_JOBBOARD_JYES'); ?></label>
  				</td>
  			</tr>
    		<tr>
    			<td class="configsectionhead" colspan="2"><?php echo JText::_('COM_JOBBOARD_LOCAION_SETTINGS');?></td>
    		</tr>
  		    <tr>
      			<td align="right" class="key">
      				<?php echo JText::_('COM_JOBBOARD_SHOW_JOBLOCATION');?>
      			</td>
      			<td>
                     	<input type="radio" name="use_location" id="use_location0" value="0" <?php if($this->row->use_location == 0) echo 'checked="checked"'; ?> class="inputbox" />
                     	<label for="use_location0"><?php echo JText::_('JNO'); ?></label>
                     	<input type="radio" name="use_location" id="use_location1" value="1" <?php if($this->row->use_location == 1) echo 'checked="checked"'; ?> class="inputbox" />
                     	<label for="use_location1"><?php echo JText::_('JYES'); ?></label>
      			</td>
      		</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_DEFAULT_LOC');?>
				</td>
				<td>
					<input class="text_area" type="text" name="default_city" id="default_city" size="50" maxlength="50" value="<?php echo $this->row->default_city;?>" />
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_DEFAULT_COUNTRY');?>
				</td>
				<td>
					<select name="default_country" id="default_country">
                       <?php foreach($this->countries as $country) : ?>
                        <option value="<?php echo $country->country_id; ?>" <?php if($country->country_id == $this->row->default_country) echo 'selected="selected"'; ?>><?php echo $country->country_name; ?></option>
                       <?php endforeach; ?>
                    </select>
				</td>
			</tr>
				<tr>
					<td align="right" class="key">
						<?php echo JText::_('COM_JOBBOARD_DISTANCE_UNIT_MEASURE');?>
					</td>
					<td>
						<select name="distance_unit" id="distance_unit" title="<?php echo JText::_('COM_JOBBOARD_DISTANCE_UNIT')?>">
	                        <option value="0" <?php if($this->row->distance_unit == 0) echo 'selected="selected"'; ?>><?php echo JText::_('COM_JOBBOARD_DISTANCE_UNIT_KILOMETERS'); ?> (<?php echo JText::_('COM_JOBBOARD_DISTANCE_UNIT_KM'); ?>)</option>
	                        <option value="1" <?php if($this->row->distance_unit == 1) echo 'selected="selected"'; ?>><?php echo JText::_('COM_JOBBOARD_DISTANCE_UNIT_MILES'); ?> (<?php echo JText::_('COM_JOBBOARD_DISTANCE_UNIT_MI'); ?>)</option>
	                    </select>
                    </td>
                </tr>
				<tr>
					<td align="right" class="key">
						<?php echo JText::_('COM_JOBBOARD_DEFAULT_DIST_RADIUS');?>
					</td>
					<td>
						<select name="default_distance" id="default_distance" title="<?php echo JText::_('COM_JOBBOARD_DEFAULT_DIST_RADIUS')?>">
                            <?php foreach($this->dist_array as $dist) : ?>
	                            <option value="<?php echo $dist ?>" <?php if($this->row->default_distance == $dist) echo 'selected="selected"'; ?>><?php echo $dist ?></option>
                            <?php endforeach ?>
	                    </select>
                    </td>
                </tr>
  		    <tr>
      			<td align="right" class="key">
      				<?php echo JText::_('COM_JOBBOARD_SHOWMAPS');?>
      			</td>
      			<td>
                   	<input type="radio" name="enable_post_maps" id="enable_post_maps0" value="0" <?php if($this->row->enable_post_maps == 0) echo 'checked="checked"'; ?> class="inputbox" />
                   	<label for="enable_post_maps0"><?php echo JText::_('JNO'); ?></label>
                   	<input type="radio" name="enable_post_maps" id="enable_post_maps1" value="1" <?php if($this->row->enable_post_maps == 1) echo 'checked="checked"'; ?> class="inputbox" />
                   	<label for="enable_post_maps1"><?php echo JText::_('JYES'); ?></label>
      			</td>
      		</tr>
		</table>
		<table class="admintable config right">
				<tr>
					<td class="configsectionhead" colspan="2"><?php echo JText::_('COM_JOBBOARD_SOCSHARING_SETTINGS');?></td>
				</tr>
				<tr>
					<td align="right" class="key">
						<?php echo JText::_('COM_JOBBOARD_SHARE_SOCIAL');?>
					</td>
					<td>
	                	<input type="radio" name="show_social" id="show_social0" value="0" <?php if($this->row->show_social == 0) echo 'checked="checked"'; ?> class="inputbox" />
	                	<label for="show_social0"><?php echo JText::_('COM_JOBBOARD_JNO'); ?></label>
	                	<input type="radio" name="show_social" id="show_social1" value="1" <?php if($this->row->show_social == 1) echo 'checked="checked"'; ?> class="inputbox" />
	                	<label for="show_social1"><?php echo JText::_('COM_JOBBOARD_JYES'); ?></label>
					</td>
				</tr>
				<tr>
					<td align="right" class="key">
						<?php echo JText::_('COM_JOBBOARD_SOCIAL_ICON_STYLE');?>
					</td>
					<td>
	                	<select name="social_icon_style">
                                <option value="0" <?php if($this->row->social_icon_style == 0){echo 'selected="selected"'; }  ?>><?php echo JText::_('COM_JOBBOARD_SOCIAL_ICONS_SMALL'); ?></option>
                                <option value="1" <?php if($this->row->social_icon_style == 1){echo 'selected="selected"'; }  ?>><?php echo JText::_('COM_JOBBOARD_SOCIAL_ICONS_COUNTERS'); ?></option>
                        </select>
					</td>
				</tr>	
				<tr>
					<td align="right" class="key">
						<?php echo JText::_('COM_JOBBOARD_SHARE_EMAIL');?>
					</td>
					<td>
	                	<input type="radio" name="send_tofriend" id="send_tofriend0" value="0" <?php if($this->row->send_tofriend == 0) echo 'checked="checked"'; ?> class="inputbox" />
	                	<label for="send_tofriend0"><?php echo JText::_('COM_JOBBOARD_JNO'); ?></label>
	                	<input type="radio" name="send_tofriend" id="send_tofriend1" value="1" <?php if($this->row->send_tofriend == 1) echo 'checked="checked"'; ?> class="inputbox" />
	                	<label for="send_tofriend1"><?php echo JText::_('COM_JOBBOARD_JYES'); ?></label>
					</td>
				</tr>	
				<tr>
					<td class="configsectionhead" colspan="2"><?php echo JText::_('COM_JOBBOARD_JOBCTR_SETTINGS');?></td>
				</tr>
				<tr>
					<td align="right" class="key">
						<?php echo JText::_('COM_JOBBOARD_SHOW_APPLCOUNT');?>
					</td>
					<td>
	                	<input type="radio" name="show_applcount" id="show_applcount0" value="0" <?php if($this->row->show_applcount == 0) echo 'checked="checked"'; ?> class="inputbox" />
	                	<label for="show_applcount0"><?php echo JText::_('COM_JOBBOARD_JNO'); ?></label>
	                	<input type="radio" name="show_applcount" id="show_applcount1" value="1" <?php if($this->row->show_applcount == 1) echo 'checked="checked"'; ?> class="inputbox" />
	                	<label for="show_applcount1"><?php echo JText::_('COM_JOBBOARD_JYES'); ?></label>
					</td>
				</tr>	
				<tr>
					<td align="right" class="key">
						<?php echo JText::_('COM_JOBBOARD_SHOW_VIEWCOUNT');?>
					</td>
					<td>
	                	<input type="radio" name="show_viewcount" id="show_viewcount0" value="0" <?php if($this->row->show_viewcount == 0) echo 'checked="checked"'; ?> class="inputbox" />
	                	<label for="show_viewcount0"><?php echo JText::_('COM_JOBBOARD_JNO'); ?></label>
	                	<input type="radio" name="show_viewcount" id="show_viewcount1" value="1" <?php if($this->row->show_viewcount == 1) echo 'checked="checked"'; ?> class="inputbox" />
	                	<label for="show_viewcount1"><?php echo JText::_('COM_JOBBOARD_JYES'); ?></label>
					</td>
				</tr>
				<tr>
					<td class="configsectionhead" colspan="2"><?php echo JText::_('COM_JOBBOARD_JOBSUMM_SETTINGS');?><br /><small><?php echo ' <i>('.JText::_('COM_JOBBOARD_SHOW_JOBSUMM_ONVIEW').':)</i>'; ?></small></td>
				</tr>
				<tr>
					<td align="right" class="key">
						<?php echo JText::_('COM_JOBBOARD_SHOW_JOBSUMM');?>
					</td>
					<td>
	                	<input type="radio" name="show_job_summary" id="show_job_summary0" value="0" <?php if($this->row->show_job_summary == 0) echo 'checked="checked"'; ?> class="inputbox" />
	                	<label for="show_job_summary0"><?php echo JText::_('COM_JOBBOARD_JNO'); ?></label>
	                	<input type="radio" name="show_job_summary" id="show_job_summary1" value="1" <?php if($this->row->show_job_summary == 1) echo 'checked="checked"'; ?> class="inputbox" />
	                	<label for="show_job_summary1"><?php echo JText::_('COM_JOBBOARD_JYES'); ?></label>
					</td>
				</tr>
				<tr>
					<td align="right" class="key">
						<?php echo JText::_('COM_JOBBOARD_APPL_SHOW_JOBSUMM');?>
					</td>
					<td>
	                	<input type="radio" name="appl_job_summary" id="appl_job_summary0" value="0" <?php if($this->row->appl_job_summary == 0) echo 'checked="checked"'; ?> class="inputbox" />
	                	<label for="appl_job_summary0"><?php echo JText::_('COM_JOBBOARD_JNO'); ?></label>
	                	<input type="radio" name="appl_job_summary" id="appl_job_summary1" value="1" <?php if($this->row->appl_job_summary == 1) echo 'checked="checked"'; ?> class="inputbox" />
	                	<label for="appl_job_summary1"><?php echo JText::_('COM_JOBBOARD_JYES'); ?></label>
					</td>
				</tr>
				<tr>
					<td align="right" class="key">
						<?php echo JText::_('COM_JOBBOARD_SHARE_SHOW_JOBSUMM');?>
					</td>
					<td>
	                	<input type="radio" name="sharing_job_summary" id="sharing_job_summary0" value="0" <?php if($this->row->sharing_job_summary == 0) echo 'checked="checked"'; ?> class="inputbox" />
	                	<label for="sharing_job_summary0"><?php echo JText::_('COM_JOBBOARD_JNO'); ?></label>
	                	<input type="radio" name="sharing_job_summary" id="sharing_job_summary1" value="1" <?php if($this->row->sharing_job_summary == 1) echo 'checked="checked"'; ?> class="inputbox" />
	                	<label for="sharing_job_summary1"><?php echo JText::_('COM_JOBBOARD_JYES'); ?></label>
					</td>
				</tr>
				<tr>
					<td class="configsectionhead" colspan="2"><?php echo JText::_('COM_JOBBOARD_JOBCOLOR_SETTINGS');?></td>
				</tr>
				<tr>
					<td align="right" class="key">
						<?php echo JText::_('COM_JOBBOARD_SHOW_JOBCOLORING');?>
					</td>
					<td>
	                	<input type="radio" name="jobtype_coloring" id="jobtype_coloring0" value="0" <?php if($this->row->jobtype_coloring == 0) echo 'checked="checked"'; ?> class="inputbox" />
	                	<label for="jobtype_coloring0"><?php echo JText::_('COM_JOBBOARD_JNO'); ?></label>
	                	<input type="radio" name="jobtype_coloring" id="jobtype_coloring1" value="1" <?php if($this->row->jobtype_coloring == 1) echo 'checked="checked"'; ?> class="inputbox" />
	                	<label for="jobtype_coloring1"><?php echo JText::_('COM_JOBBOARD_JYES'); ?></label>
					</td>
				</tr>
		</table>
   </div>
