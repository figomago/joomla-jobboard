<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');

$option='com_jobboard';

$newjob = $this->newjob;
$editor = & JFactory :: getEditor();
$job_types = array('COM_JOBBOARD_DB_JFULLTIME', 'COM_JOBBOARD_DB_JCONTRACT', 'COM_JOBBOARD_DB_JPARTTIME', 'COM_JOBBOARD_DB_JTEMP', 'COM_JOBBOARD_DB_JINTERN', 'COM_JOBBOARD_DB_JOTHER');
?>
<?php if(!$this->is_modal) : ?>
  <?php  $modal_params = array('handler'=> 'iframe', 'size' => array( 'x' => 640, 'y' => 480), 'sizeLoading'=>array( 'x' => 250, 'y' => 150), 'overlay'=>false, 'classWindow'=>'jobboardmodal'); ?>
  <?php JobBoardBehaviorHelper::modal('a.jobbrdmodal', $modal_params); ?>
<?php endif ?>
<?php if($this->config->use_location == 1) : ?>
   <?php JHTML::_('stylesheet', 'job.css', 'administrator/components/com_jobboard/css/') ?>
<?php endif ?>
<?php JobBoardBehaviorHelper::tooltip(); ?>

  <?php if($newjob) : ?>
      <?php $this->job_post->id = 0 ; ?>
      <?php $this->job_post->published = 1 ; ?>
      <?php $this->job_post->featured = 0 ; ?>
      <?php $this->job_post->job_title = '' ; ?>
      <?php $this->job_post->department =intval($this->config->default_dept) ; ?>
      <?php $this->job_post->city = $this->config->default_city ; ?>
      <?php $this->job_post->geo_latitude = '' ; ?>
      <?php $this->job_post->geo_longitude = '' ; ?>
      <?php $this->job_post->geo_state_province = '' ; ?>
      <?php $this->job_post->country = $this->config->default_country ; ?>
      <?php $this->job_post->category = $this->config->default_category ; ?>
      <?php $this->job_post->jobtype = $this->config->default_jobtype ; ?>
      <?php $this->job_post->career_level = $this->config->default_career ; ?>
      <?php $this->job_post->education = $this->config->default_edu ; ?>
      <?php $this->job_post->positions = 1 ; ?>
      <?php $this->job_post->salary = '' ; ?>
      <?php $this->job_post->ref_num = '' ; ?>
      <?php $this->job_post->job_tags = '' ; ?>
      <?php $this->job_post->description = '' ; ?>
      <?php $this->job_post->duties = '' ; ?>
  <?php endif; ?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div style="width: 44%; float: left">
    <fieldset class="adminform">
    <legend><?php echo JText::_('COM_JOBBOARD_PUBOPTIONS');?></legend>
		<table class="admintable">
            <?php if(!$newjob) : ?>
    			<tr>
    				<td align="right" class="key">
    					<?php echo JText::_('COM_JOBBOARD_JOB_ID');?>
    				</td>
    				<td>
    					<b><?php echo $this->job_post->id; ?></b>
    				</td>
    			</tr>
    		<?php endif; ?>
    			<tr>
    				<td align="right" class="key">
    					<?php echo JText::_('COM_JOBBOARD_JOBREF');?>
    				</td>
    				<td>
					    <input name="ref_num" id="ref_num" type="text" value="<?php echo $this->job_post->ref_num; ?>" />
    				</td>
    			</tr>
    			<tr>
    				<td align="right" class="key">
    					<?php echo JText::_('COM_JOBBOARD_LISTING_ACTIVE');?>
    				</td>                        &nbsp;
    				<td>
                        <select name="published">
                                <option value="0" <?php if($this->job_post->published == 0){echo 'selected="selected"'; }  ?>><?php echo JText::_('COM_JOBBOARD_JNO'); ?></option>
                                <option value="1" <?php if($this->job_post->published == 1){echo 'selected="selected"'; }  ?>><?php echo JText::_('COM_JOBBOARD_JYES'); ?></option>
                        </select>
                    </td>
                </tr>
    			<tr>
    				<td align="right" class="key">
    					<?php echo JText::_('COM_JOBBOARD_FEATURED');?>
    				</td>
    				<td>
    					<input name="featured" type="checkbox" id="featured" value="yes"<?php if($this->job_post->featured == 1) echo ' checked="checked"' ?> />
    				</td>
                </tr>
    			<tr>
    				<td align="right" class="key">
    					<?php echo JText::_('COM_JOBBOARD_ASSIGNEDQNAIRE');?>
    				</td>
    				<td>
                      <select name="questionnaire_id" id="questionnaire_id">
                         <option value="0" <?php if(!$newjob){if($this->job_post->questionnaire_id == 0) echo 'selected="selected"';} ?>><?php echo JText::_('COM_JOBBOARD_NOASSIGNEDQNAIRE');?> </option>
                         <?php if(count($this->questionnaires) > 0) :?>
                           <?php foreach($this->questionnaires as $jq) : ?>
                                <option value="<?php echo $jq['id'] ?>" <?php if(!$newjob){if($this->job_post->questionnaire_id == $jq['id']) echo 'selected="selected"';} ?>> <?php echo $jq['title'] ?>&nbsp;(<?php echo $jq['name'] ?>)</option>
                           <?php endforeach ?>
                         <?php endif ?>
                      </select> <br />
                      <a id="q-preview" class="right jobbrdmodal" href="#" ><?php echo JText::_('COM_JOBBOARD_PREVIEW_QNAIRE');?></a>
                      <?php JHTML::_('script', 'job_edit_qnaire.js', 'administrator/components/com_jobboard/js/') ?>
                      <script type="text/javascript">
                           window.addEvent('domready', function(){
                               Tandolin.questionnairePreview = new Tandolin.JobQuestionnaire();
                           });
                      </script>
    				</td>
                </tr>
            </table>
    </fieldset>
	<fieldset class="adminform">
    <legend><?php echo JText::_('COM_JOBBOARD_JOBSUMMARY');?></legend>
		<table class="admintable">
            <?php if(!$newjob) : ?>
    			<tr>
    				<td align="right" class="key">
    					<?php echo JText::_('COM_JOBBOARD_POSTDATE');?>
    				</td>
    				<td>
    					<!-- <?php echo JHTML::_('date', $this->job_post->post_date, JText::_('%a, %d %b %Y at %H:%M')); ?> -->
    					<?php echo JHTML::_('date', $this->job_post->post_date, $this->long_day_format).' ';?>
    					<?php switch($this->config->long_date_format) {
    						 case 0: echo JHTML::_('date', $this->job_post->post_date, $this->day_format.' '.$this->month_short_format.', '.$this->year_format); break;
    						 case 1: echo JHTML::_('date', $this->job_post->post_date, $this->month_short_format.' '.$this->day_format.', '.$this->year_format); break;
    						 case 2: echo JHTML::_('date', $this->job_post->post_date, $this->year_format.', '.$this->day_format.' '.$this->month_short_format); break;?>
    					<?php }; ?>
                        <?php if(version_compare( JVERSION, '1.6.0', 'ge' ))  : ?>
        					<?php echo ' '.JText::_('COM_JOBBOARD_DATE_TIMEAT').' '.JHTML::_('date', $this->job_post->post_date, JText::_('COM_JOBBOARD_TIME'));?>
                        <?php else : ?>
        					<?php echo ' '.JText::_('COM_JOBBOARD_DATE_TIMEAT').' '.JHTML::_('date', $this->job_post->post_date, '%H:%M');?>
                        <?php endif ?>
                        <span class="right editlinktip hasTip mright3em" title="<?php echo JText::_( 'COM_JOBBOARD_JOB_REPOST_TIP' );?>" >
							<strong>&#63;</strong>
						</span>
                        <input class="right" name="repost" type="button" value="&#171;&nbsp;<?php echo JText::_( 'COM_JOBBOARD_JOB_REPOST' );?>" onclick="javascript: repostJob();"  />
    				</td>
    			</tr>
            <?php endif; ?>
    			<tr>
    				<td align="right" class="key">
    					<?php echo JText::_('COM_JOBBOARD_EXPDATE');?>
    				</td>
    				<td>
                        <?php if(!$newjob) : ?>
    						<?php echo JHTML::_('calendar', $this->job_post->expiry_date, 'expiry_date', 'expiry_date', '%Y-%m-%d %H:%M:%S'); ?>
    					<?php else :?>
    						<?php echo JHTML::_('calendar', '0000-00-00 00:00:00', 'expiry_date', 'expiry_date', '%Y-%m-%d %H:%M:%S'); ?>
    					<?php endif;?>
						<input id="noexp" type="button" style="margin-left: 5px" name="noexp" value="&#171;&nbsp;<?php echo JText::_('COM_JOBBOARD_SET_NOEXPIRY'); ?>" onclick="javascript: setValButton('noexp');" />
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_JOBBOARD_ZERO_FOR_NOEXPIRY' );?>" >
							<strong>&#63;</strong>
						</span>
    				</td>
    			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_JOB_TITLE');?>
				</td>
				<td>
					<textarea name="job_title" id="job_title" rows="3" cols="25" ><?php echo $this->job_post->job_title; ?></textarea>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_JOB_CAT');?>
				</td>
				<td>
                    <select name="category" id="category">
                        <?php foreach($this->categories as $category) : ?>
                            <?php if($category->enabled = 1 && $category->id > 1) : ?>
                                <option value="<?php echo $category->id ?>" <?php if($category->id == $this->job_post->category){echo 'selected="selected"'; }  ?>><?php echo $category->type; ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_NUM_POSITIONS');?>
				</td>
				<td>
                    <?php $num_jobpositions = (is_numeric($this->job_post->positions) && $this->job_post->positions > 0)? $this->job_post->positions : 1; ?>
					<input name="positions" id="positions" type="text" value="<?php echo $num_jobpositions; ?>" />
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_DEPARTMENT');?>
				</td>
				<td>
                <select name="department" id="department">
                        <?php foreach($this->departments as $department) : ?>
                            <option value="<?php echo $department->id ?>" <?php if($department->id == $this->job_post->department){echo 'selected="selected"'; $job_department = $department;}  ?>><?php echo $department->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                    &nbsp;&nbsp;<a href="index.php?option=com_jobboard&amp;view=departments"><?php echo JText::_('COM_JOBBOARD_EDIT_DEPTS') ?></a>
                </td>
			</tr>
			<?php if($this->config->use_location) : ?>
    			<tr>
    				<td align="right" class="key">
    					<?php echo JText::_('COM_JOBBOARD_LOCATION');?>
    				</td>
    				<td>
    					<input name="city" id="city" type="text" onkeyup="if (event.keyCode==13) vMapTrigger.click();" value="<?php echo $this->job_post->city; ?>" />
    					<input id="anyloc" type="button" name="anyloc" value="&#171;&nbsp;<?php echo JText::_('COM_JOBBOARD_WORK_ANYWHERE'); ?>" onclick="javascript: setValButton('anyw');" />
    					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_JOBBOARD_WORK_ANYWHERE_DESC' );?>" >
    						<strong>&#63;</strong>
    					</span>
                         <input type="hidden" name="geo_latitude" value="<?php echo $this->job_post->geo_latitude ?>" />
                         <input type="hidden" name="geo_longitude" value="<?php echo $this->job_post->geo_longitude ?>" />
                         <input type="hidden" id="geo_state_province" name="geo_state_province" value="<?php echo $this->job_post->geo_state_province ?>" />
                    </td>
    			</tr>
                <tr>
    				<td align="right" class="key" id="loc_row">
    					&nbsp;
    				</td>
    				<td>
                        <?php $map_trigger_lbl = $this->job_post->geo_latitude == ''? 'COM_JOBBOARD_FINDONMAP' :  'COM_JOBBOARD_VIEWONMAP' ?>
                        <?php if($this->maps_online) : ?>
                          <a id="maploc" href="#"><?php echo JText::_($map_trigger_lbl);  ?></a>
                        <?php else : ?>
                            <span class="small bold red"><?php echo JText::_('COM_JOBBOARD_MAPSOFFLINE') ?></span>
                        <?php endif ?>
                       <div>
                              <div id="map_instructions" class="clear">
                                <small><?php echo JText::sprintf('COM_JOBBOARD_MAP_INSTRUCTNS', '"'.JText::_($map_trigger_lbl).'"')  ?></small>
                              </div>
                              <div class="clear">&nbsp;</div>
                              <span id="calc_loc" class="left"><span>&nbsp;</span><span class="second">&nbsp;</span></span>
                              <?php if($this->maps_online) : ?>
                              <div id="job_map" class="">&nbsp;
                              </div>
                              <?php JHTML::_('script', 'job_edit_class.js', 'administrator/components/com_jobboard/js/') ?>
                              <script type="text/javascript">

                                  window.addEvent('domready', function(){
                                      Tandolin.job.lat = <?php echo ($this->job_post->geo_latitude == '')? '"'.''.'";' : $this->job_post->geo_latitude.';' ?>
                                      Tandolin.job.lng = <?php echo ($this->job_post->geo_longitude == '')? '"'.''.'";' : $this->job_post->geo_longitude.';' ?>
                                      Tandolin.job.province = <?php echo ($this->job_post->geo_state_province == '')? '"'.''.'";' : '"'.$this->job_post->geo_state_province .'";' ?>
                                      Tandolin.job.title = <?php echo $newjob? '"'.''.'";' : '"'.$this->job_post->job_title .'";' ?>

                                      mapDiv = document.getElementById('job_map');
                                      vMapTrigger = document.getElementById('maploc');

                                      jobMap = new Tandolin.JobBoardMap(mapDiv.id, 'adminForm', {lat: Tandolin.job.lat, lng: Tandolin.job.lng, subRegion: Tandolin.job.province, trigger: vMapTrigger.id, lblPresentCoords: presentCoords, title: Tandolin.job.title});

                                      mapSlide = new Fx.Slide('job_map', {
                                        duration: 380
                                        }).slideOut();

                                      mapOpen = false;

                                      windowScroll = new Fx.Scroll(window,{
                                        transition: Fx.Transitions.Quad.easeInOut
                                      });

                                      focusOn = false;
                                      mapInstrctns = document.getElementById('map_instructions');

                                      document.getElementById('country').addEvent('change', function(){
                                          infoSpans.each(function(span){
                                               span.set('html', '&nbsp;')
                                          });

                                         document.getElementById('city').value = jobMap.currLocnString = jobMap.lat = jobMap.lng ='';

                                         jobForm.elements['geo_latitude'].value =  jobForm.elements['geo_longitude'].value =  jobForm.elements['geo_state_province'].value =  '';

                                         if(this.value != 266)
                                            jobMap.geocode(this.getElement('option[value='+this.value+']').text, true);
                                         else jobMap.refreshMap();

                                      });

                                      vMapTrigger.addEvent('click', function(e){
                                            e = new Event(e).stop();
                                            this.removeClass('green');
                                            infoSpans.each(function(span){
                                                 span.set('html', '&nbsp;')
                                            });
                                            if(mapOpen != true){
                                                revealMap();
                                            } else {
                                                jobMap.refreshMap();
                                            }
                                        });

                                      <?php if($newjob == true) echo 'jobMap.refreshMap();' ?>
                                });

                                function revealMap(){
                                  mapDiv.removeClass('dispnone');
                                  windowScroll.toElement('loc_row', 'y').chain(function(){
                                      mapSlide.slideIn().chain(function(){
                                          if(mapOpen != true){
                                             jobMap.refreshMap();
                                             mapOpen = true;
                                          }
                                        });
                                    }).chain(function(){
                                      // instrHideShow.reveal();
                                    });
                                };
                          	</script>
                            <?php endif ?>
                          </div>
    				</td>
                </tr>
    			<tr>
    				<td align="right" class="key">
    					<?php echo JText::_('COM_JOBBOARD_COUNTRY');?>
    				</td>
    				<td>
                        <select name="country" id="country">
                        	<?php foreach($this->countries as $country) : ?>
                            	<?php if($country->country_id == 266 ) :?>
                                	<option value="<?php echo $country->country_id ?>" <?php if($country->country_id == $this->job_post->country){echo 'selected="selected"';}  ?>><?php echo JText::_($country->country_name); ?></option>
                                <?php else: ?>
                                	<option value="<?php echo $country->country_id ?>" <?php if($country->country_id == $this->job_post->country){echo 'selected="selected"';}  ?>><?php echo $country->country_name; ?></option>
                                <?php endif;?>
                            <?php endforeach; ?>
                        </select>
                    </td>
    			</tr>
            <?php else : ?>
                <tr>
                    <td>
                        <input name="city" id="city" type="hidden" value="<?php echo $this->job_post->city; ?>" />
                        <input name="country" id="country" type="hidden" value="<?php echo $this->job_post->country; ?>" />
                    </td>
                </tr>
            <?php endif ?>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_CAREER_LEVEL');?>
				</td>
				<td>
                    <select name="career_level" id="career_level">
                        <?php foreach($this->careers as $career) : ?>
                            <option value="<?php echo $career->id ?>" <?php if($career->id == $this->job_post->career_level){echo 'selected="selected"';}  ?>><?php echo $career->description; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_DESIRED_ED');?>
				</td>
				<td>
                    <select name="education_level" id="education_level">
                        <?php foreach($this->education as $ed) : ?>
                            <option value="<?php echo $ed->id ?>" <?php if($ed->id == $this->job_post->education){echo 'selected="selected"';}  ?>><?php echo $ed->level; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_JOB_TYPE');?>
				</td>
				<td>
                    <select name="job_type" id="job_type">
                    <?php if($newjob) $this->job_post->job_type='COM_JOBBOARD_DB_JFULLTIME'; ?>
                        <?php foreach($job_types as $job_type) : ?>
                            <option value="<?php echo $job_type ?>" <?php if($job_type == $this->job_post->job_type){echo 'selected="selected"';}  ?>><?php echo JText::_($job_type); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_('COM_JOBBOARD_SALARY');?>
				</td>
				<td>
                    <input name="salary" id="salary" type="text" value="<?php echo $this->job_post->salary; ?>" /><small><?php echo ' ('.JText::_('COM_JOBBOARD_EXAMPLE_ABBR').' '.JText::_('COM_JOBBOARD_SAL_EG').') - '.JText::_('COM_JOBBOARD_BLANK_IF_NEG'); ?></small>
                </td>
			</tr>
        </table><br />

    		<legend><?php echo JText::_('COM_JOBBOARD_SKILLS').'/'.JText::_('COM_JOBBOARD_KEYWDS');?></legend>
    		<table class="admintable">
    			<tr>
    				<td align="right" class="key">
    					<?php echo JText::_('COM_JOBBOARD_SKILLS').'/'.JText::_('COM_JOBBOARD_KEYWDS').' <br /><small>('.JText::_('COM_JOBBOARD_COMMA_SEP').')</small>';?>
    				</td>
    				<td>
    					<input size="60" name="job_tags" id="job_tags" type="text" value="<?php echo $this->job_post->job_tags; ?>" />
    				</td>
    			</tr>
    		</table><br />
        <?php if(!$newjob) : ?>
    		<legend><?php echo JText::_('COM_JOBBOARD_JOB_POSTSTATS');?></legend>
    		<table class="admintable">
    			<tr>
    				<td align="right" class="key">
    					<?php echo JText::_('COM_JOBBOARD_APPL_SUBMITTED');?>
    				</td>
    				<td>
    					<?php echo $this->job_post->num_applications; ?>&nbsp;<?php echo JText::_('COM_JOBBOARD_TOTL_APPL_SHORT');?>
                        <?php if(!empty($this->applicants['user_appls'])) : ?>
                          &nbsp;&bull;&nbsp;<?php echo $this->applicants['user_appls']?> <?php echo JText::_('COM_JOBBOARD_APPL_TYPE_REG_SHORT');?>
                        <?php endif ?>
                        <?php if(!empty($this->applicants['site_appls'])) : ?>
                          &nbsp;&bull;&nbsp;<?php echo $this->applicants['site_appls']?> <?php echo JText::_('COM_JOBBOARD_APPL_TYPE_SITE_SHORT');?>
                        <?php endif ?>
    				</td>
    			</tr>
    			<tr>
    				<td align="right" class="key">
    					<?php echo JText::_('COM_JOBBOARD_JOB_POST_VIEWS');?>
    				</td>
    				<td>
    					<?php echo $this->job_post->hits; ?>
    				</td>
    			</tr>
    		</table>
        <?php endif; ?>
	</fieldset>
    </div>
    <div style="width: 55%; float: right; clear: none">
     <fieldset>
     <legend><?php echo JText::_('COM_JOBBOARD_JOB_SPEC');?></legend>
		<table class="admintable">
			<tr>
				<td align="left" class="key" style="text-align:left">
					<?php echo JText::_('COM_JOBBOARD_JOB_DESC');?>
				</td>
				<td>
                    &nbsp;&nbsp;
                </td>
			</tr>
            <tr>
				<td>
                    <?php echo $editor->display('job_description', ($this->job_post->description == '')? '' : htmlspecialchars($this->job_post->description, ENT_QUOTES), '480', '150', '60', '20', true);  ?>
                </td>
            </tr>
         </table>
     </fieldset>
     <fieldset>
     <legend><?php echo JText::_('COM_JOBBOARD_OPTIONL');?></legend>
		<table class="admintable">
			<tr>
				<td align="left" class="key" style="text-align:left">
					<?php echo JText::_('COM_JOBBOARD_DUTIES');?>
				</td>
				<td>
                    &nbsp;&nbsp;
                </td>
			</tr>
            <tr>
				<td>
                    <?php echo $editor->display('duties', ($this->job_post->duties == '')? '' : htmlspecialchars($this->job_post->duties, ENT_QUOTES), '480', '150', '60', '20', false);  ?>
                </td>
            </tr>
         </table>
     </fieldset>
    </div>
	<input type="hidden" name="id" value="<?php echo $this->job_post->id;?>" />
	<input type="hidden" name="option" value="<?php echo $option;?>" />
	<input type="hidden" name="view" value="<?php echo JRequest::getVar('view',''); ?>" />
	<input type="hidden" name="task" value="<?php echo JRequest::getVar('task',''); ?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>

<?php if($this->config->use_location == 1) : ?>
     <?php $anwhere_js = "
          jobForm.elements['geo_latitude'].value = jobForm.elements['geo_longitude'].value = jobForm.elements['geo_state_province'].value =  '';
                      ";
          $anywhere_dom_js = $this->maps_online? "
          vMapTrigger.removeClass('green');
          infoSpans.each(function(span){
               span.set('html', '&nbsp;')
          });

          jobMap.lat = '';
          jobMap.lng = '';
          jobMap.currLocnString = '';
          jobMap.refreshMap();" : "";              ?>
<?php endif; ?>
<?php $getJobdesc = $editor->getContent('job_description'); ?>
<?php $getJobduties = $editor->getContent('duties'); ?>
<?php if(!version_compare( JVERSION, '1.6.0', 'ge' )) : ?>
    <script type="text/javascript">
       var Joomla = Joomla || {};
       Joomla.submitbutton = submitform;
    </script>
<?php endif ?>
<script type="text/javascript">
   function selectSetVal(selectName, val) {
	  eval('selectObject = document.' + selectName + ';');
	  for(idx = 0; idx < selectObject.length; idx++) {
	   if(selectObject[idx].value == val)
	     selectObject.selectedIndex = idx;
	   }
  }
   function setValButton(b)
   {
	   var form = document.adminForm;
	   if(b == 'anyw') {
	 	  form.city.value = '';
	 	  selectSetVal('adminForm.country', 266);
    	  <?php if($this->config->use_location == 1 ) echo $anwhere_js.$anywhere_dom_js ?>
	 	  return;
	   }
	   if(b == 'noexp') {
	 	  form.expiry_date.value = "0000-00-00 00:00:00";
	 	  return;
	   }
   }

   function repostJob(){
	  var form = document.adminForm;
      if(form.country.value == 266) {
          document.getElementById('city').set('text', '');
    	  form.elements['geo_latitude'].value =  '';
    	  form.elements['geo_longitude'].value =  '';
    	  form.elements['geo_state_province'].value =  '';
        }
       text = <?php echo $getJobdesc; ?>
       text = encHtml(text);
       <?php echo $editor->save( 'job_description' ); ?>
       text = <?php echo $getJobduties; ?>
       text = encHtml(text);
       <?php echo $editor->save( 'duties' ); ?>
       submitform('repost');
   }

  Joomla.submitbutton = function(pressbutton)
  {
  var form = document.adminForm;
  // check we are saving/updating the job application
  if (document.adminForm.job_title.value.trim() == "" && pressbutton != 'close') {
	  alert( '<?php echo JText::_('COM_JOBBOARD_JTITLE_REQD', true); ?>' );
      return;
  }

  if (pressbutton == 'save' || pressbutton == 'apply' )
    {
      if(form.country.value == 266) {
          document.getElementById('city').set('text', '');
    	  form.elements['geo_latitude'].value =  '';
    	  form.elements['geo_longitude'].value =  '';
    	  form.elements['geo_state_province'].value =  '';
        }
      text = <?php echo $getJobdesc; ?>
      text = encHtml(text);
      <?php echo $editor->save( 'job_description' ); ?>
      text = <?php echo $getJobduties; ?>
      text = encHtml(text);
      <?php echo $editor->save( 'duties' ); ?>
      submitform( pressbutton );
      return;
    }
    else {
      submitform( pressbutton );
      return;
    }
  }

    function encHtml(h) {
    	 encodedHtml = escape(h);
    	 encodedHtml = encodedHtml.replace(/\\/g,"%2F"); // backslash
    	 encodedHtml = encodedHtml.replace(/\?/g,"%3F"); //?
    	 encodedHtml = encodedHtml.replace(/=/g,"%3D");  //Equal sign
    	 encodedHtml = encodedHtml.replace(/&/g,"%26");  //Ampersand
    	 encodedHtml = encodedHtml.replace(/@/g,"%40");  //Commercial at
    	 encodedHtml = encodedHtml.replace(/_/g,"%5F");  //Horizontal bar (underscore)
    	 return encodedHtml;
  }
</script>
 <?php echo $this->jb_render; ?>