<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

    defined('_JEXEC') or die('Restricted access');  
    if($this->config->allow_applications == 0) die(JText::_('APPL_NOT_ALLOWED'));
?>

<!--  set job type css class -->
<?php function setJobType($jt) {?>
	<?php switch($jt){
		 case 'DB_JFULLTIME' : 
			 return 'full-time';
		 break;
		 case 'DB_JPARTTIME' : 
			return 'part-time';
		 break;
		 case 'DB_JCONTRACT' : 
			return 'contract';
		 break;
		 case 'DB_JTEMP' : 
			return 'temporary';
		 break;
		 case 'DB_JINTERN' : 
			return 'internship';
		 break;
		 case 'DB_JOTHER' : 
			return 'other';
		 break;
	?>
	<?php }?>
<?php }?>
<?php if($this->config->jobtype_coloring == 1) :?>
	<?php $jt_color = '<span class="jobtype '.setJobType($this->data->job_type).'">'.JText::_($this->data->job_type).'</span>';?>
<?php else : ?>
	<?php $jt_color = JText::_($this->data->job_type);?>
<?php endif; ?>
<!-- end job coloring -->
<?php $layout = JRequest::getVar('lyt', ''); ?>   
<?php if($this->errors > 0) : ?>
   <?php $app= JFactory::getApplication(); ?>
   <?php $afields = $app->getUserState('afields');   ?>
<?php endif; ?>
<?php $req_marker = '*'; ?>
         <?php $path = 'index.php?option=com_jobboard&view=upload'; ?>
		  <form method="post" action="<?php echo JRoute::_($path); ?>" id="applFRM" name="applFRM" enctype="multipart/form-data">
          <div id="aplpwrapper">
              <?php echo JText::_('APPLY_FOR_POSITION'); ?>
              <h3><?php echo $this->data->job_title; if($this->config->use_location == 1) { if($this->data->country_name <> 'DB_ANYWHERE_CNAME') echo ' - '.$this->data->city; else echo ' - '.JText::_('WORK_FROM_ANYWHERE');} ?></h3>
              <div <?php if($this->config->appl_job_summary == 1) echo 'id="contleft"'; ?>>
                 <div class="controw">
                    <label for="first_name"><?php echo JText::_('FIRSTNAME'); ?><span class="fieldreq"><?php echo $req_marker; ?></span></label>
                    <input class="inputfield " maxlength="20" id="first_name" name="first_name" size="50" value="<?php echo ($this->errors > 0)? $afields->first_name: ''; ?>" type="text" />
                 </div>
                 <div class="controw">
                    <label for="last_name"><?php echo JText::_('LASTNAME'); ?><span class="fieldreq"><?php echo $req_marker; ?></span></label>
                    <input class="inputfield " maxlength="20" id="last_name" name="last_name" size="50" value="<?php echo ($this->errors > 0)? $afields->last_name: ''; ?>" type="text" />
                 </div>
                 <div class="controw">
                    <label for="email"><?php echo JText::_('EMAIL_ADDRESS'); ?><span class="fieldreq"><?php echo $req_marker; ?></span></label>
                    <input class="inputfield " maxlength="50" id="email" name="email" size="50" value="<?php echo ($this->errors > 0)? $afields->email: ''; ?>" type="text" />
                 </div>
                 <div class="controw">
                    <label for="tel"><?php echo JText::_('TELEPHONE'); ?><span class="fieldreq"><?php echo $req_marker; ?></span></label>
                    <input class="inputfield " maxlength="50" id="tel" name="tel" size="50" value="<?php echo ($this->errors > 0)? $afields->tel: ''; ?>" type="text" />
                 </div>
                 <div class="controw">
                    <label for="title"><?php echo JText::_('CV_RESUME_TITLE'); ?><span class="fieldreq"><?php echo $req_marker; ?></span></label>
                    <input class="inputfield " id="title" name="title" size="50" maxlength="50" value="<?php echo ($this->errors > 0)? $afields->title: ''; ?>" type="text" />
                 </div>
                 <div class="rowsep">&nbsp;</div>
                 <div class="controw">
                    <div class="uplrow">
                      <label for="cv"><?php echo JText::_('CV_RESUME'); ?><span class="fieldreq"><?php echo $req_marker; ?></span></label>
                      <input class="inputfield " maxlength="199" name="cv" id="cv" size="38" type="file" />
                    </div>
                    <div id="fslabel">
                      <small><strong><?php echo JText::_('NB'); ?>:</strong><?php echo '&nbsp;'.JText::_('UPLOAD_ONLY_FORMATTYPES'); ?></small>
                    </div>
                 </div>
                 <div class="rowsep"> <h4><?php echo JText::_('OPTIONAL') ?></h4>
                    <label for="cover_text"><?php echo JText::_('COVER_NOTE') ?></label> <br /><small><?php echo JText::_('COVER_NOTE_HINT'); ?>:</small>
                    <textarea rows="4" id="cover_text" cols="" name="cover_text" style="float: right; margin-right: 12%; width: 47%;padding-top:5px" ><?php echo ($this->errors > 0)? $afields->cover_note: ''; ?></textarea>
                 </div>
                 <div align="center" style="clear: both; padding-top: 15px">
                      <span id="loadr" class="hidel"></span><input id="submit_application" name="submit_application" value="&nbsp;&nbsp;&nbsp;&nbsp;<?php echo JText::_('SUBMIT_APPLICATION') ?>&nbsp;&nbsp;&nbsp;&nbsp;" class="button" type="submit" />
                      <?php $sel_job='index.php?option=com_jobboard&view=job&id='.$this->id.'&catid='.$this->catid.'&lyt='.$layout; ?>
                      &nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo JRoute::_($sel_job); ?>"><?php echo JText::_('CANCEL'); ?></a>
                 </div>
              </div>
              <?php if($this->config->appl_job_summary == 1) : ?>
              <div id="contright">
	             <small>
	               <h3><?php echo JText::_('JOB_SUMMARY'); ?></h3>
	               <?php if($this->config->show_applcount == 1) : ?>
		               <div class="jsrow">
		                  <?php echo '<span class="summtitle">'.JText::_('NUMBER_OF_APPLICATIONS').':</span><br />'.$this->data->num_applications; ?>
		               </div>
		           <?php endif; ?>
	               <?php if($this->config->use_location == 1) : ?>
		           <div class="jsrow">
		           		<?php $location_string = ($this->data->country_name <> 'DB_ANYWHERE_CNAME')? $this->data->city.', '.$this->data->country_name.', '.$this->data->country_region : JText::_('WORK_FROM_ANYWHERE'); ?>
		           	  	<?php echo '<span class="summtitle">'.JText::_('LOCATION').':</span><br />'.$location_string; ?>
		           </div>
		           <?php endif; ?>
	               <div class="jsrow">
	                  <?php echo '<span class="summtitle">'.JText::_('CAREER_LEVEL').':</span><br />'.$this->data->job_level; ?>
	               </div>
	               <div class="jsrow">
	                  <?php echo '<span class="summtitle">'.JText::_('EDUCATION').':</span><br />'.$this->data->education; ?>
	               </div>
	               <div class="jsrow">
	                  <?php echo '<span class="summtitle">'.JText::_('JOB_TYPE').':</span><br />'.$jt_color; ?>
	               </div>
	               <div class="jsrow">
	                  <?php echo '<span class="summtitle">'.JText::_('POSITIONS').':</span><br />'.$this->data->positions; ?>
	               </div>
	               <div class="jsrow <?php if($this->data->expiry_date == "0000-00-00 00:00:00") echo "lrow"; ?>">
	                  <?php $this_salary = (strlen($this->data->salary) < 1)? JText::_('NEGOTIABLE') : $this->data->salary; ?>
	                  <?php echo '<span class="summtitle">'.JText::_('SALARY').':</span><br /><b>'.$this_salary.'</b>'; ?>
	               </div>
			       <?php if($this->data->expiry_date <> "0000-00-00 00:00:00"):?>
				       <div class="jsrow lrow">
				   		  <?php $exp_date = new JDate($this->data->expiry_date); ?>
				          	<?php echo '<span class="summtitle">'.JText::_('CUTOFF_DATE').':</span><br /><b>'; ?>
					   		  <?php switch($this->config->long_date_format) {
					   		  	case 0: echo $exp_date->toFormat("%d %b, %Y").'</b>';break;
					   		  	case 1: echo $exp_date->toFormat("%b %d, %Y").'</b>';break;
					   		  	case 2: echo $exp_date->toFormat("%Y, %b %d").'</b>';break; ?>					          	
				       	      <?php } ?> 
				       </div>
			       <?php endif; ?>
	             </small>
	            </div>
              <?php endif; ?>
          </div>
		  <input name="form_submit" value="submitted" type="hidden">
		  <input name="job_id" value="<?php echo $this->id; ?>" type="hidden">
		  <input name="position" value="<?php echo $this->data->job_title; ?>" type="hidden"><?php if($this->config->use_location == 1) : ?>
		  	<?php $location_string = ($this->data->country_name <> 'DB_ANYWHERE_CNAME')? $this->data->city : JText::_('WORK_ANYWHERE'); ?>
		  	<input name="city" value="<?php echo $location_string; ?>" type="hidden"> 
		  <?php else : ?> 
		  	<input name="city" value="" type="hidden">
		  <?php endif; ?>
		  <input name="catid" value="<?php echo $this->catid; ?>" type="hidden">
	      <?php echo JHTML::_('form.token'); ?>
		  </form>
 <?php echo $this->setstate; ?>