<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
$document = &JFactory::getDocument();
$document->setTitle(JText::_('COM_JOBBOARD_CVPROF').': '.ucfirst($this->cv_data->profile_name));
?>
<div class="widecol">
   <div id="cvpreview">
       <?php if($this->cv_data->id < 1) : ?>
           <p><?php echo JText::_('COM_JOBBOARD_CVPROF_NOTFOUND'); ?></p>
       <?php endif ?>
       <?php $sync_button = "" ?>
       <?php if($this->cv_data->is_linkedin == 1) : ?>
          <?php if($this->li_import_on == 1) : ?>
             <?php $get_li_prof = JRoute::_('index.php?option=com_jobboard&view=user&task=getlinkedinprof'); ?>
             <?php $sync_button = '<a id="btn-li-import" href="'.$get_li_prof.'">&nbsp;</a><span class="li-import">'. JText::_("COM_JOBBOARD_SYNC") .'</span><br class="clear" />'; ?>
          <?php else : ?>
             <?php $sync_button = '' ?>
          <?php endif ?>
          <h2 class="linkedin">&nbsp;</h2>
       <?php endif ?>
       <h2 class="first"><?php echo ucfirst($this->cv_data->profile_name) ?><?php echo $sync_button ?></h2>
       <form id="frmedit_1" method="post" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=editcv&profileid='.$this->cv_data->id) ?>" >
                <input class="btnedit btn-grn" type="submit" value="<?php echo lcfirst(JText::_('COM_JOBBOARD_EDIT')) ?>" />
                <input type="hidden" name="option" value="com_jobboard" />
                <input type="hidden" name="view" value="user" />
                <input type="hidden" name="task" value="editcv" />
                <input type="hidden" name="profileid" value="<?php echo $this->cv_data->id ?>" />
                <input type="hidden" name="emode" value="1" />
                <input type="hidden" name="getdata" value="0" />
                <input type="hidden" name="step" value="1" />
                <?php echo JHTML::_('form.token'); ?>
       </form>
       <p><small><?php echo JText::_('COM_JOBBOARD_AVAILSTART').' '.JHTML::_('date', $this->cv_data->avail_date, $this->day_format.' '.$this->month_short_format.', '.$this->year_format) ?></small></p>
       <br />
       <h4 class="fileshead"><?php echo JText::_('COM_JOBBOARD_CVFILES') ?></h4>
       <?php if(count($this->cv_data->files) < 1) : ?>
            <p><?php echo JText::_('COM_JOBBOARD_NOCVFILES') ?></p>
       <?php else : ?>
            <?php $incr = 1; ?>
            <?php foreach ($this->cv_data->files as $file) : ?>
              <?php $filetype = explode('/', $file->filetype) ?>
              <div class="filerow">
                 <strong><?php echo ($file->filetitle == '')? JText::_('COM_JOBBOARD_FILE_NOTITLE') : $file->filetitle ?></strong>
                 <br />
                 <span class="filesrc <?php echo $filetype[1] ?>">
                    <a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=getfile&fileid='.$file->id.'&profileid='.$this->cv_data->id.'&'.JUtility::getToken().'=1')?>"><?php echo $file->filename ?></a>
                 </span>
                 <br />
                 <small class="creatd"><?php echo 'Created '.JHTML::_('date', $file->create_date, $this->day_format.' '.$this->month_short_format.', '.$this->year_format).' - '.JobBoardHelper::byteConvert($file->filesize) ?></small>
                  <form id="frmRmv_<?php echo $incr ?>" method="post" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=delcvfile&fileid='.$file->id.'&profileid='.$this->cv_data->id)?>" >
                      <input class="delfile" type="submit" value="<?php echo JText::_('COM_JOBBOARD_DELETE') ?>" />
                      <input type="hidden" name="option" value="com_jobboard" />
                      <input type="hidden" name="view" value="user" />
                      <input type="hidden" name="task" value="delcvfile" />
                      <input type="hidden" name="fileid" value="<?php echo $file->id ?>" />
                      <input type="hidden" name="profileid" value="<?php echo $this->cv_data->id ?>" />
                      <input type="hidden" name="getdata" value="0" />
                      <?php echo JHTML::_('form.token'); ?>
                  </form>
              </div>
              <?php $incr += 1 ?>
            <?php endforeach ?>
            <div class="clear">&nbsp;</div>
       <?php endif ?>
       <p>&nbsp;</p>
       <div class="item">
           <h2 id="summary"><?php echo JText::_('COM_JOBBOARD_CVPROF_SUMM') ?></h2>
           <form id="frmedit_2" method="post" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=editcv&profileid='.$this->cv_data->id)?>" >
                          <input class="btnedit btn-grn" type="submit" value="<?php echo lcfirst(JText::_('COM_JOBBOARD_EDIT')) ?>" />
                          <input type="hidden" name="option" value="com_jobboard" />
                          <input type="hidden" name="view" value="user" />
                          <input type="hidden" name="task" value="editcv" />
                          <input type="hidden" name="profileid" value="<?php echo $this->cv_data->id ?>" />
                          <input type="hidden" name="emode" value="1" />
                          <input type="hidden" name="getdata" value="1" />
                          <input type="hidden" name="step" value="3" />
                          <input type="hidden" name="section" value="summary" />
                          <?php echo JHTML::_('form.token'); ?>
           </form>
           <?php if($this->cv_data->summary == "") : ?>
            <p><?php echo JText::_('COM_JOBBOARD_CVPROF_SUMMARY_EMPTY') ?></p>
          <?php else : ?>
            <p><?php echo nl2br($this->cv_data->summary) ?></p>
          <?php endif ?>
       </div>
       <div class="item">
          <h2 id="skills"><?php echo ucfirst(JText::_('COM_JOBBOARD_TXTSKILLS')) ?></h2>
           <form id="frmedit_3" method="post" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=editcv&profileid='.$this->cv_data->id)?>" >
                          <input class="btnedit btn-grn" type="submit" value="<?php echo lcfirst(JText::_('COM_JOBBOARD_EDIT')) ?>" />
                          <input type="hidden" name="option" value="com_jobboard" />
                          <input type="hidden" name="view" value="user" />
                          <input type="hidden" name="task" value="editcv" />
                          <input type="hidden" name="profileid" value="<?php echo $this->cv_data->id ?>" />
                          <input type="hidden" name="emode" value="1" />
                          <input type="hidden" name="getdata" value="1" />
                          <input type="hidden" name="step" value="3" />
                          <input type="hidden" name="section" value="skills" />
                          <?php echo JHTML::_('form.token'); ?>
           </form>
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
           <form id="frmedit_4" method="post" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=editcv&profileid='.$this->cv_data->id)?>" >
                          <input class="btnedit btn-grn" type="submit" value="<?php echo lcfirst(JText::_('COM_JOBBOARD_EDIT')) ?>" />
                          <input type="hidden" name="option" value="com_jobboard" />
                          <input type="hidden" name="view" value="user" />
                          <input type="hidden" name="task" value="editcv" />
                          <input type="hidden" name="profileid" value="<?php echo $this->cv_data->id ?>" />
                          <input type="hidden" name="emode" value="1" />
                          <input type="hidden" name="getdata" value="1" />
                          <input type="hidden" name="step" value="2" />
                          <input type="hidden" name="section" value="employer" />
                          <?php echo JHTML::_('form.token'); ?>
           </form>
          <?php if(count($this->cv_data->employers) < 1) : ?>
            <p><?php echo JText::_('COM_JOBBOARD_CVPROF_EMPL_EMPTY') ?></p>
          <?php else : ?>
            <?php foreach ($this->cv_data->employers as $employer) : ?>
              <div class="boxtitle">
                <h3 class="position-title"><?php echo $employer->job_title ?></h3>
                <h4><?php echo $employer->company_name ?></h4>
              </div>
        	    <p class="period">
                    <?php $e_start_year = ($employer->start_yr == '0000-00-00')? '' : JHTML::_('date', $employer->start_yr, $this->month_short_format.' '.$this->year_format) ?>
                    <?php $e_end_year = ($employer->end_yr == 9999)? '' : JHTML::_('date', $employer->end_yr, $this->month_short_format.' '.$this->year_format) ?>
                    <?php $e_end_year = ($employer->end_yr == '0000-00-00')? '' : JHTML::_('date', $employer->end_yr, $this->month_short_format.' '.$this->year_format) ?>
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
           <form id="frmedit_5" method="post" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=editcv&profileid='.$this->cv_data->id)?>" >
                          <input class="btnedit btn-grn" type="submit" value="<?php echo lcfirst(JText::_('COM_JOBBOARD_EDIT')) ?>" />
                          <input type="hidden" name="option" value="com_jobboard" />
                          <input type="hidden" name="view" value="user" />
                          <input type="hidden" name="task" value="editcv" />
                          <input type="hidden" name="profileid" value="<?php echo $this->cv_data->id ?>" />
                          <input type="hidden" name="emode" value="1" />
                          <input type="hidden" name="getdata" value="1" />
                          <input type="hidden" name="step" value="2" />
                          <input type="hidden" name="section" value="education" />
                          <?php echo JHTML::_('form.token'); ?>
           </form>
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
    <span class="btn">
        <a class="btn-grn button" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=cvprofs')?>"><?php echo JText::_('COM_JOBBOARD_ADM_BACK') ?></a>
    </span>
    <form id="frmDupl" method="post" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=clonecv&profileid='.$this->cv_data->id.'&'.JUtility::getToken().'=1' )?>" >
        <span class="btn">
             <input class="btn-blk" type="submit" value="<?php echo JText::_('COM_JOBBOARD_CLONECV') ?>" />
             <input type="hidden" name="option" value="com_jobboard" />
              <input type="hidden" name="view" value="user" />
              <input type="hidden" name="task" value="clonecv" />
              <input type="hidden" name="profileid" value="<?php echo $this->cv_data->id ?>" />
              <input type="hidden" name="emode" value="1" />
              <input type="hidden" name="getdata" value="0" />
              <input type="hidden" name="islinkedin" value="<?php echo $this->cv_data->is_linkedin ?>" />
              <?php echo JHTML::_('form.token'); ?>
         </span>
    </form>
    <form id="frmDel" method="post" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=delcv&profileid='.$this->cv_data->id )?>" >
        <span class="btn">
             <input class="btn-red" type="submit" value="<?php echo JText::_('COM_JOBBOARD_DELETECV') ?>" />
             <input type="hidden" name="option" value="com_jobboard" />
              <input type="hidden" name="view" value="user" />
              <input type="hidden" name="task" value="delcv" />
              <input type="hidden" name="profileid" value="<?php echo $this->cv_data->id ?>" />
              <input type="hidden" name="emode" value="1" />
              <input type="hidden" name="getdata" value="0" />
              <input type="hidden" name="islinkedin" value="<?php echo $this->cv_data->is_linkedin ?>" />
              <?php echo JHTML::_('form.token'); ?>
         </span>
    </form>
</div>
</div>
<div class="narrowcol">
    <?php echo $this->loadTemplate('profilesummary'); ?>
</div>
<?php if($this->cv_data->is_linkedin == 1) : ?>
<script type="text/javascript">
    window.addEvent('domready', function(e){
       if($('btn-li-import')){
            $('btn-li-import').addEvent('click', function(e){
              e = new Event(e).stop();
              $('btn_container_footer').addClass('hidden');
                var liUri = this.getAttribute('href');
                var wideCol = this.getParent('div').set('html', '');
                var newImport = new Element('h2', {'id': 'li-process'}).set('html', <?php echo '"'.JText::_("COM_JOBBOARD_IMPORTINGLINKEDIN").'"' ?>).inject(wideCol);
                window.location.href = liUri;
            });
          }
    });
</script>
<?php endif ?>