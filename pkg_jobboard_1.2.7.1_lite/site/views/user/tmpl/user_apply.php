<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');

?>
<?php if(!$this->is_modal) : ?>
  <?php  $modal_params = array('handler'=> 'iframe', 'size' => array( 'x' => 640, 'y' => 480), 'sizeLoading'=>array( 'x' => 250, 'y' => 150), 'overlay'=>false, 'classWindow'=>'jobboardmodal'); ?>
  <?php JobBoardBehaviorHelper::modal('a.jobbrdmodal', $modal_params); ?>
<?php endif ?>
<div>
<br class="clear" />
<h2><?php echo JText::_('COM_JOBBOARD_APPLY_FOR_JOB') ?></h2>
<a class="jobbrdmodal" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=job&id='.$this->jobid.'&tmpl=component') ?>"><?php echo $this->job_title ?></a>
 <?php if(count($this->profdata) > 0) : ?>
  <form id="applFrm" name="applFrm" method="post" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=saveappl') ?>" >
    <label for="cvprofile"><?php echo JText::_('COM_JOBBOARD_SELAPPLCV') ?></label>
    <select name="cvprofile" id="cvprofile">
      <?php foreach ($this->profdata as $cv) : ?>
          <option value="<?php echo $cv->id ?>"><?php echo $cv->profile_name ?></option>
      <?php endforeach ?>
    </select>
    <?php if($this->qid > 0 && isset($this->questionnaire)) : ?>
       <div class="clear">&nbsp;</div>
       <span class="frmheading"><?php echo $this->questionnaire['title'] ?></span>
          <?php if(isset($this->fields)) : ?>
          <?php $ctr = 0 ?>
            <?php foreach($this->fields as $field) : ?>
              <?php if($field->restricted == 0) : ?>
                <div id="qrow-<?php echo $ctr ?>" class="qrow clear transp" <?php if($field->type == 'radio') echo 'style="min-height:'.(21*count($field->deflt->options)).'px"'; ?><?php if($field->type == 'select') if($field->deflt->multiple == 1) echo 'style="min-height:'.(18*count($field->deflt->options)).'px"'; ?>>
                  <label><?php echo $field->label ?></label>
                  <?php if($field->type == 'text') : ?>
                     <input type="text" name="<?php echo $field->name ?>" id="<?php echo $field->name ?>" value="<?php echo $field->deflt ?>"  />
                  <?php endif ?>
                  <?php if($field->type == 'checkbox') : ?>
             <input type="<?php echo $field->type ?>" name="<?php echo $field->name ?>" id="<?php echo $field->name ?>" value="yes" <?php if($field->deflt->value == 1) echo 'checked="checked"' ?> />
             <span class="checkbox"><?php echo $field->deflt->label ?></span>
                  <?php endif ?>
                  <?php if($field->type == 'radio') : ?>
                     <span class="radios">
                          <?php if(count($field->deflt->options >= 1)) : ?>
                              <?php foreach($field->deflt->options as $opt) : ?>
                                <span class="radio">
                                    <label for="<?php echo $opt->id ?>"><?php echo $opt->label ?></label>
                                    <input type="radio" id="<?php echo $opt->id ?>" value="<?php echo $opt->value ?>" name="<?php echo $field->name ?>" <?php if($field->deflt->defaultOpt == $opt->value) echo 'checked="checked"'; ?> />
                                </span>
                              <?php endforeach ?>
                         <?php endif ?>
                     </span>
                  <?php endif ?>
                  <?php if($field->type == 'select') : ?>
                     <select name="<?php echo $field->name ?><?php if($field->deflt->multiple == 1) echo '[]' ?>" id="<?php echo $field->name ?>" <?php if($field->deflt->multiple == 1) echo 'multiple="multiple"' ?>>
                        <?php if(count($field->deflt->options >= 1)) : ?>
                            <?php foreach($field->deflt->options as $opt) : ?>
                                  <option value="<?php echo $opt->value ?>" <?php if($field->deflt->defaultOpt == $opt->value) echo 'selected="selected"'; ?>><?php echo $opt->label ?></option>
                            <?php endforeach ?>
                        <?php endif ?>
                     </select>
                  <?php endif ?>
                  <?php if($field->type == 'textarea') : ?>
                     <textarea name="<?php echo $field->name ?>" id="<?php echo $field->name ?>" rows="1" cols="25"><?php echo $field->deflt ?></textarea>
                  <?php endif ?>

                  <?php if($field->type == 'date') : ?>
                      <input name="<?php echo $field->name ?>[year]" size="4" type="text" value="<?php echo sprintf("%04d", $field->deflt->defaultYear) ?>" />
                      <span class="datelbl"><?php echo JText::_('COM_JOBBOARD_QYR') ?></span>
                      <select <?php if($field->deflt->showMonth == 0) echo ' class="displnone" disabled="disabled"' ?> name="<?php echo $field->name ?>[month]">
                          <?php $curmonth_nr = intval($this->today->toFormat("%m"))  ?>
                          <?php for($i = 1; $i < 13; $i++) : ?>
                            <?php $month_leading = sprintf("%02d",$i) ?>
                            <?php $month_string =  '2000-'.$month_leading.'-01' ?>
                            <option value="<?php echo $month_leading ?>" <?php if($curmonth_nr == $i) echo 'selected="selected"'; ?>><?php echo JHTML::_('date', $month_string, $this->month_long_format) ?></option>
                          <?php endfor; ?>
                      </select>
                      <span class="datelbl<?php if($field->deflt->showMonth == 0) echo ' displnone' ?>"><?php echo JText::_('COM_JOBBOARD_QMO') ?></span>
                      <input <?php if($field->deflt->showDay == 0) echo ' class="displnone" disabled="disabled"' ?> name="<?php echo $field->name ?>[day]" size="3" type="text" value="<?php echo sprintf("%02d", $field->deflt->defaultDay) ?>" />
                      <span class="datelbl<?php if($field->deflt->showDay == 0) echo ' displnone' ?>"><?php echo JText::_('COM_JOBBOARD_QDAY') ?></span>
                 <?php endif ?>
                  </div>
               <?php endif ?>
               <?php $ctr += 1 ?>
            <?php endforeach ?>
          <?php endif ?>
       <input type="hidden" name="questionnaire" value="<?php echo $this->questionnaire['name'] ?>" />
       <input type="hidden" name="qid" value="<?php echo $this->qid ?>" />
    <?php endif ?>
    <input type="hidden" name="option" value="com_jobboard" />
    <input type="hidden" name="view"  value="user" />
    <input type="hidden" name="task" value="saveappl" />
    <input type="hidden" name="jobid" value="<?php echo $this->jobid ?>" />
    <input type="hidden" name="selcat" value="<?php echo $this->selcat ?>" />
    <div id="btn_container_footer">
        <span class="btn"><input class="button" type="submit" value="<?php echo JText::_('SUBMIT_APPLICATION') ?>" /></span>
    </div>
    <?php echo JHTML::_('form.token'); ?>
  </form>
  <?php else : ?>
     <p><?php echo JText::_('COM_JOBBOARD_NO_CVPROFILES') ?></p>
       <a class="btn" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=addcv') ?>">
			<span><?php echo JText::_('COM_JOBBOARD_ADDCVPROFILE') ?></span>
		</a>
  <?php endif ?>
</div>
