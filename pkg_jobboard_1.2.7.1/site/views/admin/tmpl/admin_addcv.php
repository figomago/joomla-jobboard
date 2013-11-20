<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
$document =& JFactory::getDocument();
$this->data['is_private'] = !isset($this->data['is_private'])? 0 : 1;
?>
<?php $curyear = $this->av_date->toFormat("%Y"); $curmonth = $this->av_date->toFormat("%B"); $curday = $this->av_date->toFormat("%d");?>
<?php $day_str = $curday.'/'.$curmonth.'/'.$curyear; ?>
<?php $curmonth_nr = $this->av_date->toFormat("%m");   ?>
<?php $lastday = date('t',strtotime($day_str)); $get_li_prof = JFilterOutput::ampReplace(JURI::root().'index.php?option=com_jobboard&view=user&task=getlinkedinprof&Itemid='.$this->itemid); ?>
<?php $li_imp_enabled = isset($this->li_import_on)? $this->li_import_on : 0 ?>
<?php $import_button = ($li_imp_enabled == 1 && $this->linkedin_imported == 0 && $this->step == 1 && $this->profileid == 0)? '<a id="btn-li-import" href="'.$get_li_prof.'">&nbsp;</a><span class="li-import">'. JText::_("COM_JOBBOARD_IMPORTLINKEDIN") .'</span>' : '';  ?>
<?php $month_long_format = !version_compare( JVERSION, '1.6.0', 'ge' )? '%B' : 'F' ?>
<?php $month_short_format = !version_compare( JVERSION, '1.6.0', 'ge' )? '%b' : 'M' ?>
<?php $year_format = !version_compare( JVERSION, '1.6.0', 'ge' )? '%Y' : 'Y' ?>

<div class="widecol">
<?php if($this->step == 4 && $this->linkedin_imported == 1) :?>
    <h2><?php echo JText::_('COM_JOBBOARD_IMPORTLINKEDIN') ?></h2>
<?php else: ?>
    <?php if($this->profileid == 0 || $this->editmode == 0) : ?>
        <h2><?php echo JText::sprintf('COM_JOBBOARD_CREATECVHEAD', $this->step).' '.$import_button ?></h2>
        <?php $document->setTitle(JText::sprintf('COM_JOBBOARD_CREATECVHEAD', $this->step)) ?>
    <?php else : ?>
        <h2><?php echo JText::_('COM_JOBBOARD_EDITCVHEAD') ?></h2>
        <?php $cv_title = $this->step == 1? ': '.$this->data['profile_name'] : ''; ?>
        <?php $document->setTitle(JText::_('COM_JOBBOARD_EDITCVHEAD').$cv_title) ?>
    <?php endif ?>
<?php endif ?>
<form enctype="multipart/form-data" id="cvForm" name="cvForm" action="<?php echo JRoute::_('index.php') ?>" method="post">
  <?php switch($this->step) {
  	case 1 : ?>
      <?php JHTML::_('script', 'createcvstep_one.js', 'components/com_jobboard/js/') ?>
  		<label for="profile_name"><?php echo JText::_('COM_JOBBOARD_PROFILENAME') ?><span class="reqd">*</span></label>
          <?php if($this->profileid <> 0) : ?>
              <?php if( $this->data['is_linkedin'] == 1 ) : ?>
                  <span class="plaintext"><?php if($this->profileid <> 0) echo $this->data['profile_name'] ?></span>
                  <input type="hidden" name="profile_name" id="profile_name" value="<?php echo $this->data['profile_name'] ?>" />
              <?php else : ?>
                  <input type="text" size="35" name="profile_name" id="profile_name" value="<?php echo $this->data['profile_name'] ?>" />
              <?php endif ?>
          <?php else : ?>
               <input type="text" size="35" name="profile_name" id="profile_name" value="<?php if($this->profileid <> 0) echo $this->data['profile_name'] ?>" />
          <?php endif ?>
          <label for="available_dd"><?php echo JText::_('COM_JOBBOARD_AVAILSTART') ?></label>
          <select id="available_yyyy" name="available_yyyy" tabindex="50">
            <?php for($i = 0; $i < 3; $i++) : ?>
              <option value="<?php echo $curyear +$i ?>" <?php if($i == 0) echo 'selected="selected"'; ?>><?php echo $curyear +$i ?></option>
            <?php endfor; ?>
          </select>
          <select id="available_mm" name="available_mm" tabindex="40">
            <?php for($i = 1; $i < 13; $i++) : ?>
              <?php $month_leading = sprintf("%02d",$i) ?>
              <?php $month_string =  $curyear.'-'.$month_leading.'-'.$curday ?>
            <option value="<?php echo $month_leading ?>" <?php if($curmonth_nr == $i) echo 'selected="selected"'; ?>><?php echo JHTML::_('date', $month_string, $month_long_format) ?></option>
            <?php endfor; ?>
          </select>
          <select id="available_dd" name="available_dd" tabindex="30">
              <?php for($i = 1; $i <= $lastday; $i++) : ?>
                  <?php $day_leading = sprintf("%02d",$i) ?>
                  <option value="<?php echo $day_leading ?>" <?php if($curday == $i) echo 'selected="selected"'; ?>><?php echo $day_leading ?></option>
              <?php endfor; ?>
          </select>
          <label for="job_type"><?php echo JText::_('COM_JOBBOARD_JOBTYPESOUGHT') ?></label>
          <select id="job_type" name="job_type">
          <option value="0"><?php echo JText::_('COM_JOBBOARD_ANYJOBTYPE') ?></option>
          <option value="COM_JOBBOARD_DB_JFULLTIME" <?php if($this->profileid <> 0 && $this->data['job_type'] == 'COM_JOBBOARD_DB_JFULLTIME') echo 'selected="selected"'  ?>><?php echo JText::_('COM_JOBBOARD_DB_JFULLTIME') ?></option>
          <option value="COM_JOBBOARD_DB_JCONTRACT" <?php if($this->profileid <> 0 && $this->data['job_type'] == 'COM_JOBBOARD_DB_JCONTRACT') echo 'selected="selected"'  ?>><?php echo JText::_('COM_JOBBOARD_DB_JCONTRACT') ?></option>
          <option value="COM_JOBBOARD_DB_JPARTTIME" <?php if($this->profileid <> 0 && $this->data['job_type'] == 'COM_JOBBOARD_DB_JPARTTIME') echo 'selected="selected"'  ?>><?php echo JText::_('COM_JOBBOARD_DB_JPARTTIME') ?></option>
          <option value="COM_JOBBOARD_DB_JTEMP" <?php if($this->profileid <> 0 && $this->data['job_type'] == 'COM_JOBBOARD_DB_JTEMP') echo 'selected="selected"'  ?>><?php echo JText::_('COM_JOBBOARD_DB_JTEMP') ?></option>
          <option value="COM_JOBBOARD_DB_JINTERN" <?php if($this->profileid <> 0 && $this->data['job_type'] == 'COM_JOBBOARD_DB_JINTERN') echo 'selected="selected"'  ?>><?php echo JText::_('COM_JOBBOARD_DB_JINTERN') ?></option>
          <option value="COM_JOBBOARD_DB_JOTHER" <?php if($this->profileid <> 0 && $this->data['job_type'] == 'COM_JOBBOARD_DB_JOTHER') echo 'selected="selected"'  ?>><?php echo JText::_('COM_JOBBOARD_DB_JOTHER') ?></option>
          </select>
          <label for="is_private"><?php echo JText::_('COM_JOBBOARD_CV_MAKE_PRIVATE') ?></label>
          <input type="checkbox" name="is_private" id="is_private" value="yes"<?php if($this->data['is_private'] == 1) echo 'checked="checked"' ?> />
          <div class="clear">&nbsp;</div>
          <span id="filesInfo" class="frmheading"><?php echo JText::_('COM_JOBBOARD_UPLOADSDESCR') ?>  <small class="right fnrml"><?php echo JText::_('COM_JOBBOARD_MAXSIZE').' ' . $this->config->max_filesize .JText::_('COM_JOBBOARD_MEGABYTESEACH') ?></small></span>
          <div id="filerow-1" class="filerow">
            <label ><?php echo JText::_('TITLE') ?></label>
            <input type="text" size="55" name="filetitle[1]" />
            <label class="midlabel"><?php echo JText::_('COM_JOBBOARD_FILETOUPLOAD') ?></label>
            <input type="file" class="inputfield " name="file[1]" />
          </div>
          <input type="hidden" name="file_count" id="file_count" value="<?php echo $this->file_count ?>" />
          <div id="filesfooter">
                <span><?php echo JText::_('COM_JOBBOARD_PERMFORMATS') ?></span>
                <a id="newfile" href="#"><?php echo JText::_('COM_JOBBOARD_TXTADDFILE') ?></a>
          </div>
  <?php break; ?>
  <?php case 2 : ?>
    <?php JHTML::_('script', 'createcvstep_two.js', 'components/com_jobboard/js/') ?>

    <!-- education history-->
    <?php if($this->section <> 'employer') : ?>
      <span class="frmheading"><?php echo JText::_('EDUCATION') ?></span>
    <?php endif ?>
    <?php if(isset($this->edu_data[0]) && $this->section == 'education') : ?>
      <div id="edRow-1" class="qualrow first">
         <label><?php echo JText::_('COM_JOBBOARD_HIGHESTQUAL') ?></label>
            <select name="edtype[1]">
            	<?php foreach($this->ed_levels as $ed_level) : ?>
                    	<option value="<?php echo $ed_level->id ?>" <?php if($this->edu_data[0]->edtype == $ed_level->id) echo 'selected="selected"' ?>><?php echo $ed_level->level; ?></option>
                <?php endforeach; ?>
      	    </select>
        <label><?php echo JText::_('COM_JOBBOARD_QUALNAME') ?></label>
        <input type="text" size="40" name="qual_name[1]" value="<?php echo $this->edu_data[0]->qual_name ?>" />
        <label><?php echo JText::_('COM_JOBBOARD_SCHOOLNAME') ?></label>
        <input type="text" size="40" name="school_name[1]" value="<?php echo $this->edu_data[0]->school_name ?>"/>
        <label><?php echo JText::_('COM_JOBBOARD_TXTCOUNTRY') ?></label>
            <select name="edu_country[1]">
            	<?php foreach($this->countries as $country) : ?>
                	<?php if($country->country_id == 266 ) :?>
                    	<option value="<?php echo $country->country_id ?>" <?php if($country->country_id == $this->edu_data[0]->edu_country){echo 'selected="selected"';}  ?>><?php echo JText::_('COM_JOBBOARD_DB_ANYWHERE_CNAME'); ?></option>
                    <?php else: ?>
                    	<option value="<?php echo $country->country_id ?>" <?php if($country->country_id == $this->edu_data[0]->edu_country){echo 'selected="selected"';}  ?>><?php echo $country->country_name; ?></option>
                    <?php endif;?>
                <?php endforeach; ?>
            </select>
            <label><?php echo JText::_('COM_JOBBOARD_TXTCITY') ?></label>
          <input type="text" size="40" name="ed_location[1]" value="<?php echo $this->edu_data[0]->location?>" />
          <?php $ed_year = JHTML::_('date', $this->edu_data[0]->ed_year, 'Y')?>
    		<label><?php echo JText::_('COM_JOBBOARD_QUALYEAR') ?></label>
            <select name="ed_year[1]" class="infoRight" >
    		  <option value="-1">--</option>
              <?php for($i = 0; $i < 41; $i++) : ?>
                <option value="<?php echo $curyear - $i ?>" <?php if($ed_year == ($curyear - $i)) echo 'selected="selected"' ?> ><?php echo $curyear - $i ?></option>
              <?php endfor; ?>
            </select>
            <input type="hidden" name="edu_id[1]" value="<?php echo $this->edu_data[0]->id ?>" />
        </div>
        <?php unset($this->edu_data[0])?>
        <?php if(count($this->edu_data) > 0) :?>
            <?php $ed_iterator = 2 ?>
            <?php foreach($this->edu_data as $edu) : ?>
              <div id="edRow-<?php echo $ed_iterator ?>" class="qualrow">
                 <span class="qualheading">
                  <?php echo JText::_('EDUCATION').' '.$ed_iterator ?>
                  <a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=deledu&edid='.$edu->id.'&profileid='.$this->profileid.'&'.JUtility::getToken().'=1')?>" class="btn" <?php //if($ed_iterator < $this->quals_count)  echo 'style="visibility:hidden"' ?> >
                      <?php echo JText::_('COM_JOBBOARD_TXTREMOVE') ?>
                  </a>
                 </span>
                 <label><?php echo JText::_('COM_JOBBOARD_TXTTYPE') ?></label>
                    <select name="edtype[<?php echo $ed_iterator ?>]">
                    	<?php foreach($this->ed_levels as $ed_level) : ?>
                            	<option value="<?php echo $ed_level->id ?>" <?php if($edu->edtype == $ed_level->id) echo 'selected="selected"' ?>><?php echo $ed_level->level; ?></option>
                        <?php endforeach; ?>
              	    </select>
                <label><?php echo JText::_('COM_JOBBOARD_QUALNAME') ?></label>
                <input type="text" size="40" name="qual_name[<?php echo $ed_iterator ?>]" value="<?php echo $edu->qual_name ?>" />
                <label><?php echo JText::_('COM_JOBBOARD_SCHOOLNAME') ?></label>
                <input type="text" size="40" name="school_name[<?php echo $ed_iterator ?>]" value="<?php echo $edu->school_name ?>"/>
                <label><?php echo JText::_('COM_JOBBOARD_TXTCOUNTRY') ?></label>
                    <select name="edu_country[<?php echo $ed_iterator ?>]">
                    	<?php foreach($this->countries as $country) : ?>
                        	<?php if($country->country_id == 266 ) :?>
                            	<option value="<?php echo $country->country_id ?>" <?php if($country->country_id == $edu->edu_country){echo 'selected="selected"';}  ?>><?php echo JText::_('COM_JOBBOARD_DB_ANYWHERE_CNAME'); ?></option>
                            <?php else: ?>
                            	<option value="<?php echo $country->country_id ?>" <?php if($country->country_id == $edu->edu_country){echo 'selected="selected"';}  ?>><?php echo $country->country_name; ?></option>
                            <?php endif;?>
                        <?php endforeach; ?>
                    </select>
                    <label><?php echo JText::_('COM_JOBBOARD_TXTCITY') ?></label>
                  <input type="text" size="40" name="ed_location[<?php echo $ed_iterator ?>]" value="<?php echo $edu->location?>" />
                  <?php $ed_year = JHTML::_('date', $edu->ed_year, 'Y')?>
            		<label><?php echo JText::_('COM_JOBBOARD_QUALYEAR') ?></label>
                    <select name="ed_year[<?php echo $ed_iterator ?>]" class="infoRight" >
            		  <option value="-1">--</option>
                      <?php for($i = 0; $i < 41; $i++) : ?>
                        <option value="<?php echo $curyear - $i ?>" <?php if($ed_year == ($curyear - $i)) echo 'selected="selected"' ?> ><?php echo $curyear - $i ?></option>
                      <?php endfor; ?>
                    </select>
                    <input type="hidden" name="edu_id[<?php echo $ed_iterator ?>]" value="<?php echo $edu->id ?>" />
                </div>
                <?php $ed_iterator += 1 ?>
            <?php endforeach ?>
        <?php endif ?>
      <?php else : ?>
          <?php if($this->section == 'education' || $this->section <> 'employer') : ?>
            <div id="edRow-1" class="qualrow first">
               <label><?php echo JText::_('COM_JOBBOARD_HIGHESTQUAL') ?></label>
                  <select name="edtype[1]">
                  	<?php foreach($this->ed_levels as $ed_level) : ?>
                          	<option value="<?php echo $ed_level->id ?>"><?php echo $ed_level->level; ?></option>
                      <?php endforeach; ?>
            	    </select>
              <label><?php echo JText::_('COM_JOBBOARD_QUALNAME') ?></label>
              <input type="text" size="40" name="qual_name[1]" value="" />
              <label><?php echo JText::_('COM_JOBBOARD_SCHOOLNAME') ?></label>
              <input type="text" size="40" name="school_name[1]" value=""/>
              <label><?php echo JText::_('COM_JOBBOARD_TXTCOUNTRY') ?></label>
                  <select name="edu_country[1]">
                  	<?php foreach($this->countries as $country) : ?>
                      	<?php if($country->country_id == 266 ) :?>
                          	<option value="<?php echo $country->country_id ?>" <?php if($country->country_id == $this->config->default_country){echo 'selected="selected"';}  ?>><?php echo JText::_('COM_JOBBOARD_DB_ANYWHERE_CNAME'); ?></option>
                          <?php else: ?>
                          	<option value="<?php echo $country->country_id ?>" <?php if($country->country_id == $this->config->default_country){echo 'selected="selected"';}  ?>><?php echo $country->country_name; ?></option>
                          <?php endif;?>
                      <?php endforeach; ?>
                  </select>
                  <label><?php echo JText::_('COM_JOBBOARD_TXTCITY') ?></label>
                  <input type="text" size="40" name="ed_location[1]" value=""/>
          		<label><?php echo JText::_('COM_JOBBOARD_QUALYEAR') ?></label>
                  <select name="ed_year[1]" class="infoRight" >
          		  <option value="-1">--</option>
                    <?php for($i = 0; $i < 41; $i++) : ?>
                      <option value="<?php echo $curyear - $i ?>" ><?php echo $curyear - $i ?></option>
                    <?php endfor; ?>
                  </select>
              </div>
          <?php endif ?>
      <?php endif?>
      <?php if($this->section <> 'employer') : ?>
        <div id="eddivider" class="clear">&nbsp;</div>
        <input type="hidden" name="quals_count" id="quals_count" value="<?php echo $this->quals_count ?>" />
        <div id="edfooter">
                <a id="newed" href="#"><?php echo JText::_('COM_JOBBOARD_TXTADDQUAL') ?></a>
         </div>
      <?php endif ?>
      <!--end education history-->

      <!--employment history-->
      <?php if($this->section <> 'education') : ?>
          <span class="frmheading"><?php echo JText::_('COM_JOBBOARD_LATESTEMPL') ?></span>
      <?php endif ?>
      <?php if(isset($this->empl_data[0]) && $this->section == 'employer') : ?>
          <div id="employer-1" class="emplrow first">
              <label><?php echo JText::_('COM_JOBBOARD_TXTCOMPANY') ?></label>
              <input type="text" size="40" name="company[1]" value="<?php echo $this->empl_data[0]->company_name ?>" />
              <label><?php echo JText::_('JOB_TITLE') ?></label>
              <input type="text" size="40" name="job_title[1]" value="<?php echo $this->empl_data[0]->job_title ?>" />
              <label><?php echo JText::_('COM_JOBBOARD_TXTCOUNTRY') ?></label>
              <select name="employer_country[1]">
              	<?php foreach($this->countries as $country) : ?>
                  	<?php if($country->country_id == 266 ) :?>
                      	<option value="<?php echo $country->country_id ?>" <?php if($country->country_id ==  $this->empl_data[0]->country_id){echo 'selected="selected"';}  ?>><?php echo JText::_('COM_JOBBOARD_DB_ANYWHERE_CNAME'); ?></option>
                      <?php else: ?>
                      	<option value="<?php echo $country->country_id ?>" <?php if($country->country_id == $this->empl_data[0]->country_id){echo 'selected="selected"';}  ?>><?php echo $country->country_name; ?></option>
                      <?php endif;?>
                  <?php endforeach; ?>
              </select>
              <label><?php echo JText::_('COM_JOBBOARD_TXTCITY') ?></label>
              <input type="text" size="40" name="employer_city[1]" value="<?php echo $this->empl_data[0]->location ?>" />
              <?php $empl_start_year = ($this->empl_data[0]->start_yr <> 0 || $this->empl_data[0]->start_yr <> 9999)? JHTML::_('date', $this->empl_data[0]->start_yr, 'Y') : $this->empl_data[0]->start_yr ?>
              <?php $empl_start_month = ($this->empl_data[0]->start_yr <> 0 || $this->empl_data[0]->start_yr <> 9999)? JHTML::_('date', $this->empl_data[0]->start_yr, 'm') : $this->empl_data[0]->start_yr ?>
              <?php $empl_end_year = ($this->empl_data[0]->end_yr <> 0 || $this->empl_data[0]->end_yr <> 9999)? JHTML::_('date', $this->empl_data[0]->end_yr, 'Y') : $this->empl_data[0]->end_yr ?>
              <?php $empl_end_month = ($this->empl_data[0]->end_yr <> 0 || $this->empl_data[0]->end_yr <> 9999)? JHTML::_('date', $this->empl_data[0]->end_yr, 'm') : $this->empl_data[0]->end_yr ?>
              <select name="endyear[1]" class="infoRight first" tabindex="911">
                <option value="-1" <?php if($this->empl_data[0]->end_yr == 0) echo 'selected="selected"' ?> >--</option>
                <option value="9999" <?php if($this->empl_data[0]->end_yr == 9999) echo 'selected="selected"' ?>><?php echo JText::_('COM_JOBBOARD_TXTPRESENT') ?></option>
                <?php for($i = 0; $i <= 40; $i++) : ?>
                  <option value="<?php echo $curyear - $i ?>" <?php if($empl_end_year == ($curyear - $i)) echo 'selected="selected"' ?> ><?php echo $curyear - $i ?></option>
                <?php endfor; ?>
              </select>
              <select name="endmon[1]">
                <?php for($m = 1; $m < 13; $m++) : ?>
                  <?php $month_leading = sprintf("%02d",$m) ?>
                  <?php $month_string =  $curyear.'-'.$month_leading.'-'.$curday ?>
                  <option value="<?php echo $month_leading ?>" <?php if($empl_end_month == $month_leading) echo 'selected="selected"' ?>><?php echo JHTML::_('date', $month_string, $month_short_format) ?></option>
                <?php endfor; ?>
              </select>
            <label class="rightlabel"><?php echo JText::_('COM_JOBBOARD_END') ?></label>
              <select name="startyear[1]" class="infoRight" tabindex="911">
            <option value="-1">--</option>
                <?php for($i = 0; $i <= 40; $i++) : ?>
                  <option value="<?php echo $curyear - $i ?>" <?php if($empl_start_year == ($curyear - $i)) echo 'selected="selected"' ?> ><?php echo $curyear - $i ?></option>
                <?php endfor; ?>
              </select>
            <select name="startmon[1]" tabindex="40">
              <?php for($m = 1; $m < 13; $m++) : ?>
                  <?php $month_leading = sprintf("%02d",$m) ?>
                  <?php $month_string =  $curyear.'-'.$month_leading.'-'.$curday ?>
                <option value="<?php echo $month_leading ?>" <?php if($empl_start_month == $month_leading) echo 'selected="selected"' ?>><?php echo JHTML::_('date', $month_string, $month_short_format) ?></option>
              <?php endfor; ?>
            </select>
            <label class="label required rightlabel first"><?php echo JText::_('COM_JOBBOARD_START') ?></label>
              <span class="chk_wrapper">
                  <?php echo JText::_('COM_JOBBOARD_ISCURRENT_EMPL') ?><input class="chk_empl" type="checkbox" name="empl_iscurrent[1]" value="yes" checked="checked" />
              </span>
              <input type="hidden" name="empl_id[1]" value="<?php echo $this->empl_data[0]->id ?>" />
          </div>
        <?php unset($this->empl_data[0])?>
        <?php if(count($this->empl_data) > 0) :?>
            <?php $empl_iterator = 2 ?>
            <?php foreach($this->empl_data as $empl) : ?>
                <div id="employer-<?php echo $empl_iterator ?>" class="emplrow">
                    <span class="emplheading">
                      <?php echo JText::_('COM_JOBBOARD_EMPLOYER').' '.$empl_iterator ?>
                      <a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=delemp&empid='.$empl->id.'&profileid='.$this->profileid.'&'.JUtility::getToken().'=1')?>" class="btn" <?php //if($empl_iterator < $this->employer_count) echo 'style="visibility:hidden"' ?> >
                          <?php echo JText::_('COM_JOBBOARD_TXTREMOVE') ?>
                      </a>
                    </span>
                    <label><?php echo JText::_('COM_JOBBOARD_TXTCOMPANY') ?></label>
                    <input type="text" size="40" name="company[<?php echo $empl_iterator ?>]" value="<?php echo $empl->company_name ?>" />
                    <label><?php echo JText::_('JOB_TITLE') ?></label>
                    <input type="text" size="40" name="job_title[<?php echo $empl_iterator ?>]" value="<?php echo $empl->job_title ?>" />
                    <label><?php echo JText::_('COM_JOBBOARD_TXTCOUNTRY') ?></label>
                    <select name="employer_country[<?php echo $empl_iterator ?>]">
                    	<?php foreach($this->countries as $country) : ?>
                        	<?php if($country->country_id == 266 ) :?>
                            	<option value="<?php echo $country->country_id ?>" <?php if($country->country_id ==  $empl->country_id){echo 'selected="selected"';}  ?>><?php echo JText::_('COM_JOBBOARD_DB_ANYWHERE_CNAME'); ?></option>
                            <?php else: ?>
                            	<option value="<?php echo $country->country_id ?>" <?php if($country->country_id == $empl->country_id){echo 'selected="selected"';}  ?>><?php echo $country->country_name; ?></option>
                            <?php endif;?>
                        <?php endforeach; ?>
                    </select>
                    <label><?php echo JText::_('COM_JOBBOARD_TXTCITY') ?></label>
                    <input type="text" size="40" name="employer_city[<?php echo $empl_iterator ?>]" value="<?php echo $empl->location ?>" />
                     <?php $empl_start_year = ($empl->start_yr <> 0 || $empl->start_yr <> 9999)? JHTML::_('date', $empl->start_yr, 'Y') : $empl->start_yr ?>
                     <?php $empl_start_month = ($empl->start_yr <> 0 || $empl->start_yr <> 9999)? JHTML::_('date', $empl->start_yr, 'm') : $empl->start_yr ?>
                     <?php $empl_end_year = ($empl->end_yr <> 0 || $empl->end_yr <> 9999)? JHTML::_('date', $empl->end_yr, 'Y') : $empl->end_yr ?>
                     <?php $empl_end_month = ($empl->end_yr <> 0 || $empl->end_yr <> 9999)? JHTML::_('date', $empl->end_yr, 'm') : $empl->end_yr ?>
                    <select name="endyear[<?php echo $empl_iterator ?>]" class="infoRight first" tabindex="911">
                      <option value="-1" <?php if($empl->start_yr == 0) echo 'selected="selected"' ?>>--</option>
                      <option value="9999" <?php if($empl->start_yr == 9999) echo 'selected="selected"' ?>><?php echo JText::_('COM_JOBBOARD_TXTPRESENT') ?></option>
                      <?php for($i = 0; $i <= 40; $i++) : ?>
                        <option value="<?php echo $curyear - $i ?>" <?php if($empl_end_year == ($curyear - $i)) echo 'selected="selected"' ?> ><?php echo $curyear - $i ?></option>
                      <?php endfor; ?>
                    </select>
                    <select name="endmon[<?php echo $empl_iterator ?>]">
                      <?php for($m = 1; $m < 13; $m++) : ?>
                        <?php $month_leading = sprintf("%02d",$m) ?>
                        <?php $month_string =  $curyear.'-'.$month_leading.'-'.$curday ?>
                        <option value="<?php echo $month_leading ?>" <?php if($empl_end_month == $month_leading) echo 'selected="selected"' ?>><?php echo JHTML::_('date', $month_string, $month_short_format) ?></option>
                      <?php endfor; ?>
                    </select>
                  <label class="rightlabel"><?php echo JText::_('COM_JOBBOARD_END') ?></label>
                  <select name="startyear[<?php echo $empl_iterator ?>]" class="infoRight" tabindex="911">
                  <option value="-1">--</option>
                      <?php for($i = 0; $i <= 40; $i++) : ?>
                        <option value="<?php echo $curyear - $i ?>" <?php if($empl_start_year == ($curyear - $i)) echo 'selected="selected"' ?> ><?php echo $curyear - $i ?></option>
                      <?php endfor; ?>
                  </select>
                  <select name="startmon[<?php echo $empl_iterator ?>]" tabindex="40">
                    <?php for($m = 1; $m < 13; $m++) : ?>
                        <?php $month_leading = sprintf("%02d",$m) ?>
                        <?php $month_string =  $curyear.'-'.$month_leading.'-'.$curday ?>
                      <option value="<?php echo $month_leading ?>" <?php if($empl_start_month == $month_leading) echo 'selected="selected"' ?>><?php echo JHTML::_('date', $month_string, $month_short_format) ?></option>
                    <?php endfor; ?>
                  </select>
                  <label class="label required rightlabel first"><?php echo JText::_('COM_JOBBOARD_START') ?></label>
                  <span class="chk_wrapper">
                      <?php echo JText::_('COM_JOBBOARD_ISCURRENT_EMPL') ?><input class="chk_empl" type="checkbox" name="empl_iscurrent[<?php echo $empl_iterator ?>]" value="yes" <?php if($empl->current == 1) echo 'checked="checked"' ?> />
                  </span>
                    <input type="hidden" name="empl_id[<?php echo $empl_iterator ?>]" value="<?php echo $empl->id ?>" />
                </div>
                <?php $empl_iterator += 1 ?>
            <?php endforeach ?>
        <?php endif ?>
      <?php else : ?>
          <?php if($this->section == 'employer' || $this->section <> 'education') : ?>
            <div id="employer-1" class="emplrow first">
                <label><?php echo JText::_('COM_JOBBOARD_TXTCOMPANY') ?></label>
                <input type="text" size="40" name="company[1]" value="" />
                <label><?php echo JText::_('JOB_TITLE') ?></label>
                <input type="text" size="40" name="job_title[1]" value="" />
                <label><?php echo JText::_('COM_JOBBOARD_TXTCOUNTRY') ?></label>
                <select name="employer_country[1]">
                	<?php foreach($this->countries as $country) : ?>
                    	<?php if($country->country_id == 266 ) :?>
                        	<option value="<?php echo $country->country_id ?>" <?php if($country->country_id == $this->config->default_country){echo 'selected="selected"';}  ?>><?php echo JText::_('COM_JOBBOARD_DB_ANYWHERE_CNAME'); ?></option>
                        <?php else: ?>
                        	<option value="<?php echo $country->country_id ?>" <?php if($country->country_id == $this->config->default_country){echo 'selected="selected"';}  ?>><?php echo $country->country_name; ?></option>
                        <?php endif;?>
                    <?php endforeach; ?>
                </select>
                <label><?php echo JText::_('COM_JOBBOARD_TXTCITY') ?></label>
                <input type="text" size="40" name="employer_city[1]" value="" />
                <select name="endyear[1]" class="infoRight first" tabindex="911">
              <option value="-1" selected="selected">--</option>
                  <option value="9999"><?php echo JText::_('COM_JOBBOARD_TXTPRESENT') ?></option>
                  <?php for($i = 0; $i <= 40; $i++) : ?>
                    <option value="<?php echo $curyear - $i ?>" ><?php echo $curyear - $i ?></option>
                  <?php endfor; ?>
                </select>
              <select name="endmon[1]">
                <?php for($m = 1; $m < 13; $m++) : ?>
                  <?php $month_leading = sprintf("%02d",$m) ?>
                  <?php $month_string =  $curyear.'-'.$month_leading.'-'.$curday ?>
                  <option value="<?php echo $month_leading ?>"><?php echo JHTML::_('date', $month_string, $month_short_format) ?></option>
                <?php endfor; ?>
              </select>
              <label class="rightlabel"><?php echo JText::_('COM_JOBBOARD_END') ?></label>
                <select name="startyear[1]" class="infoRight" tabindex="911">
              <option value="-1">--</option>
                  <?php for($i = 0; $i <= 40; $i++) : ?>
                    <option value="<?php echo $curyear - $i ?>" ><?php echo $curyear - $i ?></option>
                  <?php endfor; ?>
                </select>
                <select name="startmon[1]" tabindex="40">
                  <?php for($m = 1; $m < 13; $m++) : ?>
                      <?php $month_leading = sprintf("%02d",$m) ?>
                      <?php $month_string =  $curyear.'-'.$month_leading.'-'.$curday ?>
                    <option value="<?php echo $month_leading ?>"><?php echo JHTML::_('date', $month_string, $month_short_format) ?></option>
                  <?php endfor; ?>
                </select>
              <label class="label required rightlabel first"><?php echo JText::_('COM_JOBBOARD_START') ?></label>
              <span class="chk_wrapper">
                  <?php echo JText::_('COM_JOBBOARD_ISCURRENT_EMPL') ?><input class="chk_empl" type="checkbox" name="empl_iscurrent[1]" value="1" checked="checked" />
              </span>
            </div>
          <?php endif ?>
      <?php endif ?>
      <?php if($this->section <> 'education') : ?>
        <div id="empdivider" class="clear">&nbsp;</div>
            <input type="hidden" name="employer_count" id="employer_count" value="<?php echo $this->employer_count ?>" />
        <div id="empfooter">
                <a id="newemp" href="#"><?php echo JText::_('COM_JOBBOARD_TXTADDEMPLOYER') ?></a>
        </div>
      <?php endif ?>
      <!--end employment history-->
  <?php break; ?>
  <?php case 3 : ?>
      <?php JHTML::_('script', 'createcvstep_three.js', 'components/com_jobboard/js/') ?>
      <?php if($this->section == 'skills' || $this->editmode == 0) : ?>
          <span class="frmheading"><?php echo ucfirst(JText::_('COM_JOBBOARD_TXTSKILLS')) ?></span>
          <span class="skillstxt"><?php echo JText::sprintf( 'COM_JOBBOARD_TXTSKILLSHEADER', $this->config->max_skills ) ?> <small><?php echo JText::_('COM_JOBBOARD_SPACESONLY') ?></small></span>
          <div id="skillheader">
             <span class="skillname"><?php echo JText::_('COM_JOBBOARD_TXTSKILL') ?></span>
             <span class="skilllastuse"><?php echo JText::_('COM_JOBBOARD_SKILL_LASTUSE') ?></span>
             <span class="skillexpperiod"><?php echo JText::_('COM_JOBBOARD_EXPERIENCE') ?> <small>(<?php echo JText::_('COM_JOBBOARD_SKILL_LU_INMO') ?>)</small></span>
             <span class="skillbtns">&nbsp;</span>
          </div>
          <?php if($this->editmode == 0) : ?>
              <div id="skillrow-1" class="skillwrapper">
                 <span class="skillname">
                      <input type="text" name="skillname[1]" value=""  />
                 </span>
                 <span class="skilllastuse">
                       <select  name="lastused[1]">
                        <option value="0"><?php echo JText::_('COM_JOBBOARD_TXTCURRENT') ?></option>
                        <?php for($i = 0; $i < 18; $i++) : ?>
                          <option value="<?php echo $curyear - $i ?>" ><?php echo $curyear - $i ?></option>
                        <?php endfor; ?>
                     </select>
                 </span>
                 <span class="skillexpperiod">
                       <input type="text" name="experience[1]" value="" />
                 </span>
                 <span class="skillbtns">
                  <a id="remove-1" style="display:none" href="#"><?php echo JText::_('COM_JOBBOARD_TXTREMOVE') ?></a>
                 </span>
              </div>
          <?php endif ?>
          <?php if($this->editmode == 1 && $this->getdata == 1) : ?>
             <?php if(count($this->skills) == 0 && $this->skills_count == 1) : ?>
                <div id="skillrow-1" class="skillwrapper">
                   <span class="skillname">
                        <input type="text" name="skillname[1]" value=""  />
                   </span>
                   <span class="skilllastuse">
                         <select name="lastused[1]">
                          <option value="0"><?php echo JText::_('COM_JOBBOARD_TXTCURRENT') ?></option>
                          <?php for($i = 0; $i < 18; $i++) : ?>
                            <option value="<?php echo $curyear - $i ?>" ><?php echo $curyear - $i ?></option>
                          <?php endfor; ?>
                       </select>
                   </span>
                   <span class="skillexpperiod">
                         <input type="text" name="experience[1]" value="" />
                   </span>
                   <span class="skillbtns">
                    <a id="remove-1" style="display:none" href="#"><?php echo JText::_('COM_JOBBOARD_TXTREMOVE') ?></a>
                   </span>
                </div>
             <?php else : ?>
                 <?php $skill_iterator = 1 ?>
                 <?php foreach($this->skills as $skill) : ?>
                        <div id="skillrow-<?php echo $skill_iterator ?>" class="skillwrapper">
                           <span class="skillname">
                                <input type="text" name="skillname[<?php echo $skill_iterator ?>]" value="<?php echo $skill->skill_name ?>"  />
                           </span>
                           <span class="skilllastuse">
                                 <?php $last_use = $skill->last_use <> '0000-00-00' ? JHTML::_('date', $skill->last_use, 'Y') : 0 ?>
                                 <select  name="lastused[<?php echo $skill_iterator ?>]">
                                  <option value="0" <?php if($last_use == 0) echo 'selected="selected"' ?>><?php echo JText::_('COM_JOBBOARD_TXTCURRENT') ?></option>
                                  <?php for($i = 0; $i < 18; $i++) : ?>
                                    <option value="<?php echo $curyear - $i ?>" <?php if($last_use == ($curyear - $i)) echo 'selected="selected"' ?>><?php echo $curyear - $i ?></option>
                                  <?php endfor; ?>
                               </select>
                           </span>
                           <span class="skillexpperiod">
                                 <input type="text" name="experience[<?php echo $skill_iterator ?>]" value="<?php echo $skill->experience_period ?>" />
                           </span>
                           <span class="skillbtns">
                            <a id="remove-<?php echo $skill_iterator ?>" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=delskill&skillid='.$skill->id.'&profileid='.$this->profileid.'&'.JUtility::getToken().'=1')?>"><?php echo JText::_('COM_JOBBOARD_TXTREMOVE') ?></a>
                           </span>
                        </div>
                        <input type="hidden" name="skill_id[<?php echo $skill_iterator ?>]" value="<?php echo $skill->id ?>" />
                      <?php $skill_iterator += 1 ?>
                 <?php endforeach ?>
             <?php endif ?>
          <?php endif ?>
          <div id="skillsfooter">
                <a id="newskill" href="#"><?php echo JText::_('COM_JOBBOARD_TXTADDSKILL') ?></a>
          </div>
          <input type="hidden" name="skillscount" id="skillscount" value="<?php echo $this->skills_count ?>" />
      <?php endif ?>
      <?php if($this->section == 'summary' || $this->editmode == 0) : ?>
          <span class="frmheading"><?php echo JText::_('COM_JOBBOARD_CVPROF_SUMM') ?></span>
          <div id="cvsummary">
                <p><?php echo JText::_('COM_JOBBOARD_SUMMARYTIP') ?></p>
                <textarea rows="4" cols="10" name="summary"><?php if(isset($this->summary)) echo $this->summary->summary ?></textarea>
          </div>
      <?php endif ?>
  <?php break; ?>
  <?php case 4 : ?>
     <?php if($this->linkedin_imported == 1 && isset($this->linkedin_arr)) :?>
     <div id="cvpreview">
         <?php if($this->linkedin_arr['response'] === true) : ?>
         <?php $linkedin_save_ready = 1 ?>
             <p class="helpbox yellowbox"><?php echo JText::_('COM_JOBBOARD_IMPORTLI_NOTE') ?></p>
             <h2><?php echo $this->linkedin_arr['first-name'].' '.$this->linkedin_arr['last-name'] ?></h2>
             <?php $linkedin_summary = !isset($this->linkedin_arr['summary'])? '' : $this->linkedin_arr['summary']  ?>
             <p><?php echo $linkedin_summary ?></p>
             <input type="hidden" name="profile_summary" value="<?php echo $linkedin_summary ?>" />
             <?php if(is_array($this->linkedin_arr['positions']['position']) && intval($this->linkedin_arr['positions']['@attributes']['total']) > 0 )   :?>
                 <div class="cvblock">
                    <h2><?php echo JText::_('COM_JOBBOARD_EXPERIENCE') ?></h2>
                    <?php $now_str = $curyear.'-'.sprintf("%02d", $curmonth).'-'.$curday; $job_ctr = 0; ?>
                    <?php if(intval($this->linkedin_arr['positions']['@attributes']['total']) == 1 )  : ?>
                        <div class="boxtitle">
                           <h3 class="position-title"><?php echo $this->linkedin_arr['positions']['position']['title'] ?></h3>
                           <h4><?php if(is_array($this->linkedin_arr['positions']['position']['company'])) echo $this->linkedin_arr['positions']['position']['company']['name'] ?></h4>
                        </div>
                  	    <p class="period">
                              <?php $start_year = ""; $end_year = ""; $end_year_str = "" ?>
                              <?php if(isset($this->linkedin_arr['positions']['position']['start-date'])) $start_year =  $this->linkedin_arr['positions']['position']['start-date']['year'].'-'.sprintf("%02d", $this->linkedin_arr['positions']['position']['start-date']['month']).'-01'; else $start_year = '0000-00-00'; ?>
                              <?php if($this->linkedin_arr['positions']['position']['is-current'] && !isset($this->linkedin_arr['positions']['position']['end-date'])) {$end_year_str = $now_str;$end_year = JText::_('COM_JOBBOARD_TXTPRESENT');} ?>
                              <?php if(isset($this->linkedin_arr['positions']['position']['end-date']) && is_array($this->linkedin_arr['positions']['position']['end-date'])) {$end_year_str = $this->linkedin_arr['positions']['position']['end-date']['year'].'-'.sprintf("%02d", $this->linkedin_arr['positions']['position']['end-date']['month']).'-27';$end_year = JHTML::_('date', $end_year_str, 'F Y');} ?>
                  	          <abbr title="<?php echo $start_year ?>" class="dtstart"><?php echo JHTML::_('date', $start_year, 'F Y') ?></abbr>&nbsp;&ndash;&nbsp;<abbr title="<?php echo $end_year_str ?>" class="dtstamp"><?php echo $end_year ?></abbr>
                  	    </p>
                        <input type="hidden" name="employer_name[<?php echo $job_ctr ?>]" id="employer_name[<?php echo $job_ctr ?>]" value="<?php if(is_array($this->linkedin_arr['positions']['position']['company'])) echo $this->linkedin_arr['positions']['position']['company']['name'] ?>" />
                        <input type="hidden" name="job_title[<?php echo $job_ctr ?>]" id="job_title[<?php echo $job_ctr ?>]" value="<?php echo $this->linkedin_arr['positions']['position']['title'] ?>" />
                        <input type="hidden" name="empl_start[<?php echo $job_ctr ?>]" id="empl_start[<?php echo $job_ctr ?>]" value="<?php echo $start_year ?>" />
                        <input type="hidden" name="empl_end[<?php echo $job_ctr ?>]" id="empl_end[<?php echo $job_ctr ?>]" value="<?php echo $end_year_str ?>" />
                        <input type="hidden" name="empl_iscurr[<?php echo $job_ctr ?>]" id="empl_iscurr[<?php echo $job_ctr ?>]" value="<?php echo $this->linkedin_arr['positions']['position']['is-current'] == 'true'? 1 : 0 ?>" />
                    <?php else : ?>
                      <?php foreach($this->linkedin_arr['positions']['position'] as $pos) : ?>
                        <div class="boxtitle">
                           <h3 class="position-title"><?php echo $pos['title'] ?></h3>
                           <h4><?php if(is_array($pos['company'])) echo $pos['company']['name'] ?></h4>
                        </div>
                  	    <p class="period">
                              <?php $start_year = ""; $end_year = ""; $end_year_str = "" ?>
                              <?php if(is_array($pos['start-date'])) $start_year =  $pos['start-date']['year'].'-'.sprintf("%02d", $pos['start-date']['month']).'-01'; else $start_year = '0000-00-00'; ?>
                              <?php if($pos['is-current'] && !isset($pos['end-date'])) {$end_year_str = $now_str;$end_year = JText::_('COM_JOBBOARD_TXTPRESENT');} ?>
                              <?php if(isset($pos['end-date']) && is_array($pos['end-date'])) {$end_year_str = $pos['end-date']['year'].'-'.sprintf("%02d", $pos['end-date']['month']).'-27';$end_year = JHTML::_('date', $end_year_str, 'F Y');} ?>
                  	          <abbr title="<?php echo $start_year ?>" class="dtstart"><?php echo JHTML::_('date', $start_year, 'F Y') ?></abbr>&nbsp;&ndash;&nbsp;<abbr title="<?php echo $end_year_str ?>" class="dtstamp"><?php echo $end_year ?></abbr>
                  	    </p>
                        <input type="hidden" name="employer_name[<?php echo $job_ctr ?>]" id="employer_name[<?php echo $job_ctr ?>]" value="<?php if(is_array($pos['company'])) echo $pos['company']['name'] ?>" />
                        <input type="hidden" name="job_title[<?php echo $job_ctr ?>]" id="job_title[<?php echo $job_ctr ?>]" value="<?php echo $pos['title'] ?>" />
                        <input type="hidden" name="empl_start[<?php echo $job_ctr ?>]" id="empl_start[<?php echo $job_ctr ?>]" value="<?php echo $start_year ?>" />
                        <input type="hidden" name="empl_end[<?php echo $job_ctr ?>]" id="empl_end[<?php echo $job_ctr ?>]" value="<?php echo $end_year_str ?>" />
                        <input type="hidden" name="empl_iscurr[<?php echo $job_ctr ?>]" id="empl_iscurr[<?php echo $job_ctr ?>]" value="<?php echo $pos['is-current'] == 'true'? 1 : 0 ?>" />
                        <?php $job_ctr += 1; ?>
                     <?php endforeach ?>
                   <?php endif ?>
                 </div>
            <?php endif ?>
             <?php if(isset($this->linkedin_arr['educations']['education']) && intval($this->linkedin_arr['educations']['@attributes']['total']) > 0 )   :?>
                 <div class="cvblock">
                    <h2><?php echo JText::_('EDUCATION') ?></h2>
                    <?php $ed_ctr = 0; ?>
                    <?php if(intval($this->linkedin_arr['educations']['@attributes']['total']) == 1) :?>
                          <?php $ed = $this->linkedin_arr['educations']['education'] ?>
                          <div class="boxtitle">
                             <h3 class="titleh3"><?php echo $ed['school-name'] ?></h3>
                             <h4><?php echo $ed['field-of-study'] ?></h4>
                          </div>
                    	    <p class="period">
                                <?php $sep = ''; $start_year = ""; $end_year = ""; ?>
                                <?php if(isset($ed['start-date'])) $start_year =  $ed['start-date']['year'];$sep="&nbsp;&ndash;&nbsp;"; ?>
                                <?php if(isset($ed['end-date'])) $end_year = $ed['end-date']['year']; ?>
                                <?php if($start_year != "") :?>
                    	            <abbr title="<?php echo $start_year ?>" class="dtstart"><?php echo $start_year ?></abbr><?php echo $sep ?>
                                <?php endif ?>
                                <?php if($end_year != "") :?>
                                  <abbr title="<?php echo $end_year ?>" class="dtstamp"><?php echo $end_year ?></abbr>
                                <?php endif ?>
                    	    </p>
                          <input type="hidden" name="school_name[<?php echo $ed_ctr ?>]" id="school_name[<?php echo $ed_ctr ?>]" value="<?php echo $ed['school-name'] ?>" />
                          <input type="hidden" name="qual_name[<?php echo $ed_ctr ?>]" id="qual_name[<?php echo $ed_ctr ?>]" value="<?php echo $ed['field-of-study'] ?>" />
                          <input type="hidden" name="school_start[<?php echo $ed_ctr ?>]" id="school_start[<?php echo $ed_ctr ?>]" value="<?php echo $start_year ?>" />
                          <input type="hidden" name="school_end[<?php echo $ed_ctr ?>]" id="school_end[<?php echo $ed_ctr ?>]" value="<?php echo $end_year ?>" />
                    <?php else : ?>
                        <?php foreach($this->linkedin_arr['educations']['education'] as $ed) : ?>
                          <div class="boxtitle">
                             <h3 class="titleh3"><?php echo $ed['school-name'] ?></h3>
                             <h4><?php echo $ed['field-of-study'] ?></h4>
                          </div>
                    	    <p class="period">
                                <?php $sep = ''; $start_year = ""; $end_year = ""; ?>
                                <?php if(isset($ed['start-date'])) $start_year =  $ed['start-date']['year'];$sep="&nbsp;&ndash;&nbsp;"; ?>
                                <?php if(isset($ed['end-date'])) $end_year = $ed['end-date']['year']; ?>
                                <?php if($start_year != "") :?>
                    	            <abbr title="<?php echo $start_year ?>" class="dtstart"><?php echo $start_year ?></abbr><?php echo $sep ?>
                                <?php endif ?>
                                <?php if($end_year != "") :?>
                                  <abbr title="<?php echo $end_year ?>" class="dtstamp"><?php echo $end_year ?></abbr>
                                <?php endif ?>
                    	    </p>
                          <input type="hidden" name="school_name[<?php echo $ed_ctr ?>]" id="school_name[<?php echo $ed_ctr ?>]" value="<?php echo $ed['school-name'] ?>" />
                          <input type="hidden" name="qual_name[<?php echo $ed_ctr ?>]" id="qual_name[<?php echo $ed_ctr ?>]" value="<?php echo $ed['field-of-study'] ?>" />
                          <input type="hidden" name="school_start[<?php echo $ed_ctr ?>]" id="school_start[<?php echo $ed_ctr ?>]" value="<?php echo $start_year ?>" />
                          <input type="hidden" name="school_end[<?php echo $ed_ctr ?>]" id="school_end[<?php echo $ed_ctr ?>]" value="<?php echo $end_year ?>" />
                          <?php $ed_ctr += 1; ?>
                         <?php endforeach ?>
                    <?php endif ?>
                 </div>
            <?php endif ?>
             <?php if(isset($this->linkedin_arr['skills']['skill'])) : ?>
              <?php if(is_array($this->linkedin_arr['skills']['skill']) && intval($this->linkedin_arr['skills']['@attributes']['total']) > 0 )   :?>
                 <?php $skill_ctr = 0; ?>
                 <?php if(intval($this->linkedin_arr['skills']['@attributes']['total']) == 1) : ?>
                   <div class="cvblock">
                      <?php $skill = $this->linkedin_arr['skills']['skill']  ?>
                      <h2><?php echo JText::_('COM_JOBBOARD_TXTSKILLS') ?></h2>
                        <span class="skillset">
                          <h4><?php echo $skill['skill']['name'] ?></h4>
                        </span>
                        <input type="hidden" name="skillname[<?php echo $skill_ctr ?>]" value="<?php echo $skill['skill']['name'] ?>" />
                   </div>
                 <?php else : ?>
                   <div class="cvblock">
                      <h2><?php echo JText::_('COM_JOBBOARD_TXTSKILLS') ?></h2>
                      <?php foreach($this->linkedin_arr['skills']['skill'] as $skill) : ?>
                        <span class="skillset">
                          <h4><?php echo $skill['skill']['name'] ?></h4>
                        </span>
                        <input type="hidden" name="skillname[<?php echo $skill_ctr ?>]" value="<?php echo $skill['skill']['name'] ?>" />
                        <?php $skill_ctr += 1; ?>
                       <?php endforeach ?>
                   </div>
                 <?php endif ?>

             <?php endif ?>
            <?php endif ?>
         <?php else : ?>
              <?php $linkedin_save_ready = 0 ?>
              <?php $import_button = '<a class="import-li" href="'.$get_li_prof.'">&nbsp;</a><span class="li-import">'. JText::_("COM_JOBBOARD_IMPORTLINKEDIN") .'</span>' ;  ?>
              <p><?php echo JText::_('COM_JOBBOARD_IMPORTLINKEDINCONNECTERR') ?></p>
              <p><?php echo $import_button ?></p>
         <?php endif ?>
      <?php elseif($this->linkedin_imported == 0): ?>

      <?php else : ?>
      <?php endif ?>
      </div> <!--end #cvpreview-->
      <input type="hidden" value="addcv" name="task" />
  <?php break; ?>
  <?php } ?>
  <div id="btn_container_footer">

      <?php if ($this->step == 4 && $linkedin_save_ready == 1) : ?>
          <span class="btn"><input class="button" type="submit" value="<?php echo JText::_('COM_JOBBOARD_TXTSAVE') ?>" name="commit" /></span>
      <?php else: ?>
        <?php if($this->step <> 4 && $this->editmode == 0) : ?>
          <span class="btn"><input class="button" style="margin:0" type="submit" value="<?php echo JText::_('COM_JOBBOARD_TXTNEXT').'&nbsp;&nbsp;'.'&#8594;' ?>" name="commit" /></span>
          <input type="hidden" name="task" value="addcv" />
        <?php elseif($this->editmode == 1) : ?>
          <?php $page_hash = isset($section)? '#'.$this->section : '' ?>
          <span class="btn"><input class="button" style="margin:0" type="submit" value="<?php echo JText::_('COM_JOBBOARD_TXTSAVE') ?>" name="commit" /></span>
          <small><span class="btn"><a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=viewcv&profileid='.$this->profileid.$page_hash) ?>"><?php echo JText::_('COM_JOBBOARD_TXTCANCL') ?></a></span></small>
          <input type="hidden" name="task" value="editcv" />
        <?php endif ?>
      <?php endif ?>
      <?php if($this->step == 1) : ?>
        <?php if($this->editmode == 0) : ?>
          <small><span class="btn"><a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=cvprofs') ?>"><?php echo JText::_('COM_JOBBOARD_TXTCANCL') ?></a></span></small>
        <?php endif ?>
      <?php endif ?>
  </div>
  <input type="hidden" name="option" value="com_jobboard" />
  <input type="hidden" name="view" value="user" />
  <input type="hidden" value="<?php echo $this->step+1 ?>" name="step" id="step" />
  <?php if($this->linkedin_imported == 1 && $this->step == 4) { $this->profileid = 0; $this->editmode = 0; $this->getdata = 0;} ?>
  <input type="hidden" value="<?php echo $this->profileid ?>" name="profileid" id="profileid" />
  <input type="hidden" value="<?php echo $this->editmode ?>" name="emode" id="emode" />
  <input type="hidden" name="getdata" value="<?php echo $this->getdata ?>" />
  <?php if(isset($this->section)) : ?>
    <?php if($this->section <> '') : ?>
        <input type="hidden" name="section" value="<?php echo $this->section ?>" />
    <?php endif ?>
  <?php endif ?>
  <?php echo JHTML::_('form.token'); ?>
</form>
</div>
<div class="narrowcol">
    <?php echo $this->loadTemplate('profilesummary'); ?>
</div>
<div class="clear">&nbsp;</div>