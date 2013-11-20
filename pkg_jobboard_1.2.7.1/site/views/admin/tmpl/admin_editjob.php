<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
$document = & JFactory::getDocument();
$newjob = $this->data->id == 0? true : false;
if($newjob <> true) :
    $document->setTitle(JText::_('COM_JOBBOARD_EDJOB').' - '.JText::_('TITLE').': '.$this->data->job_title);
    if($this->user_auth['manage_jobs'] == 0) :
       $app = &JFactory::getApplication();
       $app->redirect(JRoute::_('index.php?option=com_jobboard&view=admin&Itemid='.$this->itemid), JText::_('COM_JOBBOARD_ADM_NOAUTH'), 'error');
    endif;
else :
    $document->setTitle(JText::_('COM_JOBBOARD_ADDJOB'));
    if($this->user_auth['post_jobs'] == 0) :
       $app = &JFactory::getApplication();
       $app->redirect(JRoute::_('index.php?option=com_jobboard&view=admin&Itemid='.$this->itemid), JText::_('COM_JOBBOARD_ADM_NOAUTH'), 'error');
    endif;
endif;
?>
<?php $editor = & JFactory :: getEditor(); ?>
<?php if(!$this->is_modal) : ?>
  <?php  $modal_params = array('handler'=> 'iframe', 'size' => array( 'x' => 640, 'y' => 480), 'sizeLoading'=>array( 'x' => 250, 'y' => 150), 'overlay'=>false, 'classWindow'=>'jobboardmodal'); ?>
  <?php JobBoardBehaviorHelper::modal('a.jobbrdmodal', $modal_params); ?>
<?php endif ?>
<?php JobBoardBehaviorHelper::tooltip(); ?>
<?php if($newjob) : ?>
    <?php $this->data->published = 1 ; ?>
    <?php $this->data->department =intval($this->config->default_dept) ; ?>
    <?php $this->data->city = $this->config->default_city ; ?>
    <?php $this->data->country = $this->config->default_country ; ?>
    <?php $this->data->category = $this->config->default_category ; ?>
    <?php $this->data->jobtype = $this->config->default_jobtype ; ?>
    <?php $this->data->career_level = $this->config->default_career ; ?>
    <?php $this->data->education = $this->config->default_edu ; ?>
<?php endif; ?>
  <div>
  <br class="clear" />
  <?php if(!$newjob) : ?>
    <h2><?php echo JText::_('COM_JOBBOARD_EDJOB') ?> <small><?php echo JText::_('COM_JOBBOARD_ENT_ID') ?> <?php echo $this->data->id ?></small> </h2>
    <strong class="mright5"><?php echo JText::_('DATE_POSTED') ?></strong>
  	<?php switch($this->config->long_date_format) {
  		 case 0: echo JHTML::_('date', $this->data->post_date, $this->day_format.' '.$this->month_long_format.', '.$this->year_format); break;
  		 case 1: echo JHTML::_('date', $this->data->post_date, $this->month_long_format.' '.$this->day_format.', '.$this->year_format); break;
  		 case 2: echo JHTML::_('date', $this->data->post_date, $this->year_format.', '.$this->day_format.' '.$this->month_long_format); break;?>
  	<?php }; ?>
    <?php if(version_compare( JVERSION, '1.6.0', 'ge' ))  : ?>
      <?php echo ' '.JText::_('COM_JOBBOARD_DATE_TIMEAT').' '.JHTML::_('date', $this->data->post_date, JText::_('COM_JOBBOARD_TIME'));?>
    <?php else : ?>
      <?php echo ' '.JText::_('COM_JOBBOARD_DATE_TIMEAT').' '.JHTML::_('date', $this->data->post_date, '%H:%M');?>
    <?php endif ?>
  <?php endif; ?>
  <?php if(!$newjob) : ?>
     <input class="btn-blue small mtop-0 mleft5" type="button" onclick="javascript: repostJob();" value="<?php echo JText::_('COM_JOBBOARD_JOB_REPOST') ?>" />
     <span class="mleft5 editlinktip hasTip" title="<?php echo JText::_( 'COM_JOBBOARD_JOB_REPOST_TIP' );?>" >
        <strong>&#63;</strong>
     </span>
  <?php endif ?>
  <?php if(!$newjob) :?>
     <?php if($this->user_auth['post_jobs'] == 1) : ?>
       <form  id="frmDuplTop" method="post" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=clonejob&jid='.$this->data->id)?>" >
           <input class="btn-blk right mtop-0" type="submit" value="<?php echo JText::_('COM_JOBBOARD_CLONE') ?>" />
           <input type="hidden" name="option" value="com_jobboard" />
            <input type="hidden" name="view" value="admin" />
            <input type="hidden" name="task" value="clonejob" />
            <input type="hidden" name="jid" value="<?php echo $this->data->id ?>" />
            <input type="hidden" name="Itemid" value="<?php echo $this->itemid ?>" />
            <?php echo JHTML::_('form.token'); ?>
       </form>
     <?php endif ?>
     <form  id="frmDelTop" method="post" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=deljob&jid='.$this->data->id)?>" >
         <input class="btn-red right mtop-0" type="submit" value="<?php echo JText::_('COM_JOBBOARD_DELETE') ?>" />
         <input type="hidden" name="option" value="com_jobboard" />
          <input type="hidden" name="view" value="admin" />
          <input type="hidden" name="task" value="deljob" />
          <input type="hidden" name="jid" value="<?php echo $this->data->id ?>" />
          <input type="hidden" name="Itemid" value="<?php echo $this->itemid ?>" />
          <?php echo JHTML::_('form.token'); ?>
     </form>
   <?php endif ?>

  <form name="jobForm_b" id="jobForm_b"  method="post" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=edjob&jid='.$this->data->id) ?>">
    <label for="job_title">
    	<?php echo JText::_('TITLE');?>
    </label>
    <textarea class="left" name="job_title" id="job_title" rows="1" cols="45" ><?php echo !$newjob? $this->data->job_title : ''; ?></textarea>
    <a class="right jsmall clear" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=jobs&Itemid='.$this->itemid) ?>"><?php echo JText::_('COM_JOBBOARD_TXTCANCL') ?></a>
    <input type="button" class="btn-grn right mtop-0" value="<?php echo JText::_('COM_JOBBOARD_TXTSAVE') ?>" onclick="savejob();" />
      <label for="ref_num" class="left">
      	<?php echo JText::_('COM_JOBBOARD_ENT_REF');?>
      </label>
      <input name="ref_num" id="ref_num" class="left" type="text" value="<?php echo isset($this->data->ref_num)? $this->data->ref_num : ''; ?>" />
    <br class="clear" />
    <label for="questionnaire_id">
    	<?php echo JText::_('COM_JOBBOARD_ASSIGNEDQNAIRE');?>
    </label>
    <select class="right" name="questionnaire_id" id="questionnaire_id">
       <option value="0" <?php if(!$newjob){if($this->data->questionnaire_id == 0) echo 'selected="selected"';} ?>><?php echo JText::_('COM_JOBBOARD_NOASSIGNEDQNAIRE');?> </option>
       <?php if(count($this->questionnaires) > 0) :?>
         <?php foreach($this->questionnaires as $jq) : ?>
              <option value="<?php echo $jq['id'] ?>" <?php if(!$newjob){if($this->data->questionnaire_id == $jq['id']) echo 'selected="selected"';} ?>> <?php echo $jq['title'] ?></option>
         <?php endforeach ?>
       <?php endif ?>
    </select> <br />
    <a id="q-preview" class="right small jobbrdmodal" href="#" ><?php echo JText::_('COM_JOBBOARD_PREVIEW_QNAIRE');?></a>
    <?php JHTML::_('script', 'job_edit_qnaire.js', 'components/com_jobboard/js/') ?>
    <script type="text/javascript">
         window.addEvent('domready', function(){
             Tandolin.fQuestionnairePreview = new Tandolin.JobQuestionnaire();
         });
    </script>
    <div class="clear">&nbsp;</div>
    <div class="filerow">
         <label>
  			<?php echo JText::_('COM_JOBBOARD_LISTING_ACTIVE');?>
  		</label>
          <?php if($this->can_feature == 1) : ?>
            <input name="featured" type="checkbox" id="featured" class="right noclear" value="yes" <?php if($this->data->featured == 1) echo 'checked="checked"' ?>  />
          <?php else: ?>
            <span class="right small mtop10"><?php if($this->data->featured == 1) echo JText::_('COM_JOBBOARD_ENTYES'); else echo JText::_('COM_JOBBOARD_ENTNO') ?></span>
            <input name="featured" type="hidden" id="featured" value="<?php if($this->data->featured == 1) echo 'yes'; else echo 'no' ?>"  />
          <?php endif ?>
          <label class="right noclear mleft10">
          	<?php echo JText::_('COM_JOBBOARD_ENT_FEATURED');?>
          </label>
          <select name="published">
              <option value="0" <?php if($this->data->published == 0){echo 'selected="selected"'; }  ?>><?php echo JText::_('COM_JOBBOARD_ENTNO'); ?></option>
              <option value="1" <?php if($this->data->published == 1){echo 'selected="selected"'; }  ?>><?php echo JText::_('COM_JOBBOARD_ENTYES'); ?></option>
          </select>
    </div>
    <label>
    	<?php echo JText::sprintf('COM_JOBBOARD_ENT_DESCR','');?>
    </label>
    <div class="tinyinput">
     <?php echo $editor->display('description', ($this->data->description == '')? '' : htmlspecialchars($this->data->description, ENT_QUOTES), '94%', '150', '60', '20', false);  ?>
    </div>
    <label>
    	<?php echo JText::_('COM_JOBBOARD_DUTIES');?>
    </label>
    <div class="tinyinput">
          <?php echo $editor->display('duties', ($this->data->duties == '')? '' : htmlspecialchars($this->data->duties, ENT_QUOTES), '94%', '150', '60', '20', false);  ?>
    </div>
    <div class="clear">&nbsp;</div>
    <div id="job_edit_innercont">
        <div class="filerow">
             <label>
      		   <?php echo JText::_('COM_JOBBOARD_EXPDATE');?>
      		</label>
              <span class="editlinktip hasTip right" title="<?php echo JText::_( 'COM_JOBBOARD_ZERO_FOR_NOEXPIRY' );?>" >
                <strong>&#63;</strong>
              </span>
              <input id="noexp" type="button" class="btn btn-grn right mleft5" name="noexp" value="&#171;&nbsp;<?php echo JText::_('COM_JOBBOARD_SET_NOEXPIRY'); ?>" onclick="javascript: setValButton('noexp');" />
              <?php if(!$newjob) : ?>
                <?php echo JHTML::_('calendar', $this->data->expiry_date, 'expiry_date', 'expiry_date', '%Y-%m-%d %H:%M:%S'); ?>
                <?php else :?>
                <?php echo JHTML::_('calendar', '0000-00-00 00:00:00', 'expiry_date', 'expiry_date', '%Y-%m-%d %H:%M:%S'); ?>
              <?php endif;?>
        </div>
        <div class="filerowc">
             <label for="category">
      		   <?php echo JText::_('COM_JOBBOARD_JOB_CAT');?>
      		</label>
              <select name="category" id="category">
                  <?php foreach($this->categories as $category) : ?>
                      <?php if($category->enabled = 1 && $category->id > 1) : ?>
                          <option value="<?php echo $category->id ?>" <?php if($category->id == $this->data->category){echo 'selected="selected"'; }  ?>><?php echo $category->type; ?></option>
                      <?php endif; ?>
                  <?php endforeach; ?>
              </select>
        </div>
        <div class="filerow">
             <label>
      		   <?php echo JText::_('COM_JOBBOARD_NUM_POSITIONS');?>
      	   </label>
             <?php $num_jobpositions = (is_numeric($this->data->positions) && $this->data->positions > 0)? $this->data->positions : 1; ?>
      	   <input name="positions" id="positions" type="text" value="<?php echo $num_jobpositions; ?>" />
        </div>
        <div class="filerowc">
             <label for="category">
      		   <?php echo JText::_('COM_JOBBOARD_DEPARTMENT');?>
      		</label>
            <select name="department" id="department">
                <?php foreach($this->departments as $department) : ?>
                    <option value="<?php echo $department->id ?>" <?php if($department->id == $this->data->department){echo 'selected="selected"'; $job_department = $department;}  ?>><?php echo $department->name; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php if($this->config->use_location) : ?>
          <div id="loc_row" class="filerow">
               <label for="city">
        		   <?php echo JText::_('LOCATION');?>
        	   </label>
             <?php $map_trigger_lbl = $this->data->geo_latitude == ''? 'COM_JOBBOARD_FINDONMAP' :  'COM_JOBBOARD_VIEWONMAP' ?>
              <?php if($this->maps_online) : ?>
                <a id="maploc" href="#"><?php echo JText::_($map_trigger_lbl);  ?></a>
              <?php else : ?>
                <span class="right small mtop10"><?php echo JText::_('COM_JOBBOARD_MAPSOFFLINE') ?></span>
              <?php endif ?>
              <input name="city" id="city" type="text" onkeyup="if (event.keyCode==13) vMapTrigger.click();" value="<?php echo $this->data->city; ?>" />
              <input type="hidden" name="geo_latitude" value="<?php echo $this->data->geo_latitude ?>" />
              <input type="hidden" name="geo_longitude" value="<?php echo $this->data->geo_longitude ?>" />
              <input type="hidden" id="geo_state_province" name="geo_state_province" value="<?php echo $this->data->geo_state_province ?>" />
              <div class="clear">
                    <input id="anyloc" class="btn btn-grn right" type="button" name="anyloc" value="&#171;&nbsp;<?php echo JText::_('WORK_FROM_ANYWHERE'); ?>" onclick="javascript: setValButton('anyw');" />
                    <select name="country" id="country">
                    	<?php foreach($this->countries as $country) : ?>
                        	<?php if($country->country_id == 266 ) :?>
                            	<option value="<?php echo $country->country_id ?>" <?php if($country->country_id == $this->data->country){echo 'selected="selected"';}  ?>><?php echo JText::_($country->country_name); ?></option>
                            <?php else: ?>
                            	<option value="<?php echo $country->country_id ?>" <?php if($country->country_id == $this->data->country){echo 'selected="selected"';}  ?>><?php echo $country->country_name; ?></option>
                            <?php endif;?>
                        <?php endforeach; ?>
                    </select>
              </div>
              <span id="calc_loc" class="left"><span>&nbsp;</span><span class="second">&nbsp;</span></span>
              <div id="map_instructions" class="clear"><?php echo JText::sprintf('COM_JOBBOARD_MAP_INSTRUCTNS', '"'.JText::_($map_trigger_lbl).'"')  ?>
              </div>
              <?php if($this->maps_online) : ?>
                <div id="job_map" class="">&nbsp;
                </div>
                <?php JHTML::_('script', 'job_edit_class.js', 'components/com_jobboard/js/') ?>
                <script type="text/javascript">
                    window.addEvent('domready', function(){
                        Tandolin.job.lat = <?php echo ($this->data->geo_latitude == '')? '"'.''.'";' : $this->data->geo_latitude.';' ?>
                        Tandolin.job.lng = <?php echo ($this->data->geo_longitude == '')? '"'.''.'";' : $this->data->geo_longitude.';' ?>
                        Tandolin.job.province = <?php echo ($this->data->geo_state_province == '')? '"'.''.'";' : '"'.$this->data->geo_state_province .'";' ?>
                        Tandolin.job.title = <?php echo $newjob? '"'.''.'";' : '"'.$this->data->job_title .'";' ?>

                        mapDiv = document.getElementById('job_map');
                        vMapTrigger = document.getElementById('maploc');

                        jobMap = new Tandolin.JobBoardMap(mapDiv.id, 'jobForm_b', {lat: Tandolin.job.lat, lng: Tandolin.job.lng, subRegion: Tandolin.job.province, trigger: vMapTrigger.id, lblPresentCoords: presentCoords, title: Tandolin.job.title});

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
        <?php else : ?>
          <input name="city" id="city" type="hidden" value="<?php echo $this->data->city; ?>" />
          <input name="country" id="country" type="hidden" value="<?php echo $this->data->country; ?>" />
        <?php endif ?>
        <div class="filerowc">
             <label for="career_level">
      		   <?php echo JText::_('CAREER_LEVEL');?>
      	   </label>
              <select name="career_level" id="career_level">
                  <?php foreach($this->careers as $career) : ?>
                      <option value="<?php echo $career->id ?>" <?php if($career->id == $this->data->career_level){echo 'selected="selected"';}  ?>><?php echo $career->description; ?></option>
                  <?php endforeach; ?>
              </select>
        </div>
        <div class="filerow">
             <label for="education">
                <?php echo JText::_('COM_JOBBOARD_DESIRED_ED');?>
      	   </label>
             <select name="education" id="education">
                  <?php foreach($this->education as $ed) : ?>
                      <option value="<?php echo $ed->id ?>" <?php if($ed->id == $this->data->education){echo 'selected="selected"';}  ?>><?php echo $ed->level; ?></option>
                  <?php endforeach; ?>
             </select>
        </div>
        <div class="filerowc">
             <label for="job_type">
      		   <?php echo JText::_('JOB_TYPE');?>
             </label>
              <select name="job_type" id="job_type">
              <?php if($newjob) $this->data->job_type='COM_JOBBOARD_DB_JFULLTIME'; ?>
                  <?php foreach($this->job_types as $job_type) : ?>
                      <option value="<?php echo $job_type ?>" <?php if($job_type == $this->data->job_type){echo 'selected="selected"';}  ?>><?php echo JText::_($job_type); ?></option>
                  <?php endforeach; ?>
              </select>
        </div>
        <div class="filerow">
             <label for="salary">
      			<?php echo JText::_('COM_JOBBOARD_SALARY');?>
      	   </label>
             <input name="salary" id="salary" type="text" value="<?php echo $this->data->salary; ?>" /><small>
             <span class="txt"><?php echo ' ('.JText::_('COM_JOBBOARD_EXAMPLE_ABBR').' '.JText::_('COM_JOBBOARD_SAL_EG').') - '.JText::_('COM_JOBBOARD_BLANK_IF_NEG'); ?></span></small>
        </div>
        <br class="clear"/>
        <div class="filerowc">
             <label for="job_tags">
      			<?php echo JText::_('COM_JOBBOARD_SKILLS').'/'.JText::_('COM_JOBBOARD_KEYWDS').' <br /><small>('.JText::_('COM_JOBBOARD_COMMA_SEP').')</small>';?>
      		</label>
          	<input size="60" name="job_tags" id="job_tags" type="text" value="<?php echo $this->data->job_tags; ?>" />
        </div>
         <?php if(!$newjob) :?>
          <div class="filerowc">
               <label>
            		<?php echo JText::_('COM_JOBBOARD_JOB_POST_VIEWS');?>
               </label>
        		<span class="txt">
        			<?php echo $this->data->hits; ?>
        		</span>
          </div>
        <?php endif ?>
        <div class="filerowc">
            <a class="right jsmall" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=jobs&Itemid='.$this->itemid) ?>"><?php echo JText::_('COM_JOBBOARD_TXTCANCL') ?></a>
            <input type="submit" class="button right" value="<?php echo JText::_('COM_JOBBOARD_TXTSAVE') ?>" onclick="(function(e){e.stop();savejob();})"  />
        </div>
     </div> <!-- end inputs after job duties -->
    <input type="hidden" name="option" value="<?php echo 'com_jobboard'?>" />
    <input type="hidden" name="view" value="admin" />
    <input type="hidden" name="jid" value="<?php echo $this->data->id > 0? $this->data->id : 0 ?>" />
    <input type="hidden" name="task" value="savejob" />
    <input type="hidden" name="Itemid" value="<?php echo $this->itemid ?>" />
    <input name="repost" value="0" type="hidden" />
    <?php echo JHTML::_('form.token'); ?>
  </form>
  <?php if(!$newjob) :?>
      <div class="filerowc">
           <label>
        		<?php echo JText::_('COM_JOBBOARD_APPL_SUBMITTED');?>
           </label>
    		<span class="txt">
    			<?php echo $this->data->num_applications; ?>
    		</span>
           <?php if($this->data->num_applications > 0 && $this->user_auth['manage_applicants'] == 1) : ?>
             <form  id="frmAppl" method="post" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=appl&jid='.$this->data->id)?>" >
                 <input class="button left small10 mleft10" type="submit" value="<?php echo JText::_('COM_JOBBOARD_VIEWAPPLS') ?>" />
                 <input type="hidden" name="option" value="com_jobboard" />
                  <input type="hidden" name="view" value="admin" />
                  <input type="hidden" name="task" value="appl" />
                  <input type="hidden" name="jid" value="<?php echo $this->data->id ?>" />
                  <input type="hidden" name="Itemid" value="<?php echo $this->itemid ?>" />
                  <?php echo JHTML::_('form.token'); ?>
             </form>
          <?php endif ?>
      </div>
     <form  id="frmDelBot" method="post" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=deljob&jid='.$this->data->id)?>" >
         <input class="btn-red left" type="submit" value="<?php echo JText::_('COM_JOBBOARD_DELETE') ?>" />
         <input type="hidden" name="option" value="com_jobboard" />
          <input type="hidden" name="view" value="admin" />
          <input type="hidden" name="task" value="deljob" />
          <input type="hidden" name="jid" value="<?php echo $this->data->id ?>" />
          <input type="hidden" name="Itemid" value="<?php echo $this->itemid ?>" />
          <?php echo JHTML::_('form.token'); ?>
     </form>
     <?php if($this->user_auth['post_jobs'] == 1) : ?>
       <form  id="frmDuplBot" method="post" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=clonejob&jid='.$this->data->id)?>" >
           <input class="btn-blk left" type="submit" value="<?php echo JText::_('COM_JOBBOARD_CLONE') ?>" />
           <input type="hidden" name="option" value="com_jobboard" />
            <input type="hidden" name="view" value="admin" />
            <input type="hidden" name="task" value="clonejob" />
            <input type="hidden" name="jid" value="<?php echo $this->data->id ?>" />
            <input type="hidden" name="Itemid" value="<?php echo $this->itemid ?>" />
            <?php echo JHTML::_('form.token'); ?>
       </form>
   <?php endif ?>
  <?php endif ?>
</div>
<div class="clear">&nbsp;</div>
<?php if($this->config->use_location == 1) : ?>
     <?php $anywhere_js = "
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
<?php $getJobdesc = $editor->getContent('description'); ?>
<?php $getJobduties = $editor->getContent('duties'); ?>
<script type="text/javascript">

   function setValButton(b)
   {
	   if(b == 'anyw') {
	 	  jobForm.city.value = '';
          jobForm.country.value = 266;
          <?php if($this->config->use_location == 1 ) echo $anywhere_js.$anywhere_dom_js ?>

	 	  return;
	   }
	   if(b == 'noexp') {
	 	  jobForm.expiry_date.value = "0000-00-00 00:00:00";
	 	  return;
	   }
   }

   function repostJob(){
       jobForm.repost.value = 1;
       jobForm.published.value = 1;
       savejob();
   }

   var text;
   var savejob = function() {
         if(jobForm.country.value == 266) {
          document.getElementById('city').set('text', '');
    	  jobForm.elements['geo_latitude'].value =  '';
    	  jobForm.elements['geo_longitude'].value =  '';
    	  jobForm.elements['geo_state_province'].value =  '';
        }

        text = <?php echo $getJobdesc; ?>
        text = encHtml(text);
        <?php echo $editor->save( 'description' ); ?>
        text = <?php echo $getJobduties; ?>
        text = encHtml(text);
        <?php echo $editor->save( 'duties' ); ?>

        jobForm.submit();
   };

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