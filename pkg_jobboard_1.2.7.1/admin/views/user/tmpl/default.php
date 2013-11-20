<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
?>
<?php JHTML::_('stylesheet', 'user.css', 'administrator/components/com_jobboard/css/') ?>
<?php JHTML::_('stylesheet', 'files.css', 'administrator/components/com_jobboard/css/') ?>
<div class="widecol">
   <div id="cvpreview">
       <?php if($this->cv_data->id < 1) : ?>
           <p><?php echo JText::_('COM_JOBBOARD_CVPROF_NOTFOUND'); ?></p>
       <?php endif ?>
       <?php if($this->s_mode == 0) : ?>
         <form id="frmRetrn" method="post" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=appl&jid='.$this->jid)?>" >
            <input class="btn-rnd right" type="submit" value="<?php echo '&larr;&nbsp;'.JText::_('BACK') ?>" />
            <input type="hidden" name="option" value="com_jobboard" />
            <input type="hidden" name="view" value="admin" />
            <input type="hidden" name="task" value="appl" />
            <input type="hidden" name="jid" value="<?php echo $this->jid ?>" />
            <input type="hidden" name="s_context" value="user" />
            <?php echo JHTML::_('form.token'); ?>
         </form>
       <?php endif ?>
       <?php if($this->cv_data->is_linkedin == 1) : ?>
           <h2 class="linkedin">&nbsp;</h2>
       <?php endif ?>
       <?php if($this->s_mode == 0) : ?>
         <strong><?php echo JText::_('COM_JOBBOARD_APPLIEDFOR'); ?>:</strong>
         <h2 class="first"><?php echo $this->job_title ?></h2>
       <?php endif ?>
       <br class="clear"/><strong><?php echo ucfirst($this->cv_data->profile_name) ?></strong>
       <br class="clear"/>
         <form id="frmInvite" method="post" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=jobs')?>" >
            <input class="btn-grn right" type="submit" value="<?php echo JText::_('COM_JOBBOARD_INVITE_TOJOB').'&nbsp;&rarr;' ?>" />
            <input type="hidden" name="option" value="com_jobboard" />
            <input type="hidden" name="view" value="admin" />
            <input type="hidden" name="task" value="jobs" />
            <input type="hidden" name="sid" value="<?php echo $this->cv_data->user_id ?>" />
            <input type="hidden" name="cpid" value="<?php echo $this->cv_data->id ?>" />
            <?php echo JHTML::_('form.token'); ?>
         </form>
       <p><small><?php echo JText::_('COM_JOBBOARD_AVAILSTART').' '.JHTML::_('date', $this->cv_data->avail_date, $this->day_format.' '.$this->month_long_format.', '.$this->year_format) ?></small></p>
       <br />
       <h4 class="fileshead"><?php echo JText::_('COM_JOBBOARD_CVFILES') ?></h4>
       <?php if(count($this->cv_data->files) < 1) : ?>
         <p><?php echo JText::_('COM_JOBBOARD_NOCVFILES') ?></p>
       <?php else : ?>
            <?php foreach ($this->cv_data->files as $file) : ?>
              <?php $incr = 1; ?>
              <?php $filetype = explode('/', $file->filetype) ?>
              <div class="filerow">
                 <strong><?php echo ($file->filetitle == '')? JText::_('COM_JOBBOARD_FILE_NOTITLE') : $file->filetitle ?></strong>
                 <br />
                 <span class="filesrc <?php echo $filetype[1] ?>">
                    <a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=applicants&task=getucvfile&file='.$file->id.'&pid='.$this->cv_data->id.'&uid='.$this->cv_data->user_id.'&'.JUtility::getToken().'=1')?>"><?php echo $file->filename ?></a>
                 </span>
                 <br />
                 <small class="creatd"><?php echo JText::_('COM_JOBBOARD_ENT_CREATED_PLAIN').' '.JHTML::_('date', $file->create_date, $this->day_format.' '.$this->month_long_format.', '.$this->year_format).' - '.JobBoardHelper::byteConvert($file->filesize) ?></small>
              </div>
              <?php $incr += 1 ?>
            <?php endforeach ?>
            <div class="clear">&nbsp;</div>
       <?php endif ?>
       <p>&nbsp;</p>
       <div class="item">
           <h2 id="summary"><?php echo JText::_('COM_JOBBOARD_CVPROF_SUMM') ?></h2>
           <?php if($this->cv_data->summary == "") : ?>
            <p><?php echo JText::_('COM_JOBBOARD_CVPROF_SUMMARY_EMPTY') ?></p>
          <?php else : ?>
            <p><?php echo nl2br($this->cv_data->summary) ?></p>
          <?php endif ?>
       </div>
       <div class="item">
          <h2 id="skills"><?php echo ucfirst(JText::_('COM_JOBBOARD_TXTSKILLS')) ?></h2>
          <?php if(count($this->cv_data->skills) < 1) : ?>
            <p><?php echo JText::_('COM_JOBBOARD_CVPROF_SKILLS_EMPTY') ?></p>
          <?php else : ?>
            <?php foreach ($this->cv_data->skills as $skill) : ?>
              <span class="skillset">
                 <span class="skill"><?php echo $skill->skill_name ?></span>
                 <?php if($skill->experience_period > 0) : ?>
                    <abbr class="dtstamp" title="<?php echo $skill->last_use ?>"><?php echo $skill->experience_period.' '.JText::_('COM_JOBBOARD_MONTHS') ?></abbr>
                 <?php endif ?>
              </span>
            <?php endforeach ?>
          <?php endif ?>
       </div>
       <div class="item">
          <h2 id="employer"><?php echo JText::_('COM_JOBBOARD_EXPERIENCE') ?></h2>
          <?php if(count($this->cv_data->employers) < 1) : ?>
            <p><?php echo JText::_('COM_JOBBOARD_CVPROF_EMPL_EMPTY') ?></p>
          <?php else : ?>
            <?php foreach ($this->cv_data->employers as $employer) : ?>
              <div class="boxtitle">
                <h3 class="position-title"><?php echo $employer->job_title ?></h3>
                <h4><?php echo $employer->company_name ?></h4>
              </div>
        	    <p class="period">
                    <?php $e_start_year = ($employer->start_yr == '0000-00-00')? '' : JHTML::_('date', $employer->start_yr, $this->month_long_format.' '.$this->year_format) ?>
                    <?php $e_end_year = ($employer->end_yr == 9999)? '' : JHTML::_('date', $employer->end_yr, $this->month_long_format.' '.$this->year_format) ?>
                    <?php $e_end_year = ($employer->end_yr == '0000-00-00')? '' : JHTML::_('date', $employer->end_yr, $this->month_long_format.' '.$this->year_format) ?>
                    <?php $empl_stat = ($employer->current == 1)? JText::_('COM_JOBBOARD_TXTPRESENT') : $e_end_year ?>
                    <?php if($e_start_year <> '' && $e_end_year <> '') : ?>
                      <abbr class="dtstart" title="<?php echo $employer->start_yr ?>"><?php echo $e_start_year ?></abbr>&nbsp;&ndash;&nbsp;<abbr class="dtstamp" title="<?php echo $employer->end_yr ?>"><?php echo $empl_stat  ?></abbr>
                    <?php endif ?>
        	    </p>
            <?php endforeach ?>
          <?php endif ?>
       </div>
       <div class="item">
          <h2 id="education"><?php echo JText::_('EDUCATION') ?></h2>
          <?php if(count($this->cv_data->education) < 1) : ?>
            <p><?php echo JText::_('COM_JOBBOARD_CVPROF_EDU_EMPTY') ?></p>
          <?php else : ?>
            <?php foreach ($this->cv_data->education as $edu) : ?>
              <div class="boxtitle">
                 <h3 class="titleh3"><?php echo $edu->school_name ?></h3>
                 <h4><?php echo $edu->qual_name ?></h4>
              </div>
            	<p class="period">
                <?php if($edu->ed_year <> '0000-00-00') : ?>
                   <abbr class="dtstamp" title="<?php echo $edu->ed_year ?>"><?php echo JHTML::_('date', $edu->ed_year, $this->year_format) ?></abbr>
                <?php endif ?>
              </p>
            <?php endforeach ?>
          <?php endif ?>
       </div>
   </div> <!--end #cvpreview-->
<div id="btn_container_footer" class="cvpreview">

</div>
</div>
<div class="narrowcol">
    <?php echo $this->loadTemplate('cvprofilesummary'); ?>
</div>