<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');

$editor = & JFactory::getEditor();
?>
<?php if(!$this->is_modal) : ?>
  <?php  $modal_params = array('handler'=> 'iframe', 'size' => array( 'x' => 640, 'y' => 480), 'sizeLoading'=>array( 'x' => 250, 'y' => 150), 'overlay'=>false, 'classWindow'=>'jobboardmodal'); ?>
  <?php JobBoardBehaviorHelper::modal('a.jobbrdmodal', $modal_params); ?>
<?php endif ?>
<?php JHTML::_('stylesheet', 'user.css', 'administrator/components/com_jobboard/css/') ?>
<?php JHTML::_('stylesheet', 'applicant.css', 'administrator/components/com_jobboard/css/') ?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div style="width: 54%; float: left">
    <h3><?php echo JText::_('COM_JOBBOARD_JOB_IN_QUESTION');?></h3>
		<table class="admintable" style="width: 99%">
          <tr class="widecol">
            <td>
			<?php JHTML::_('behavior.tooltip'); ?>
              <span class="clearfix"><?php echo JText::_('COM_JOBBOARD_JOB_TITLE') ?> : <a class="jobbrdmodal" href="<?php echo JURI::root().JFilterOutput::ampReplace('index.php?option=com_jobboard&view=job&id='.$this->jid.'&tmpl=component') ?>"><strong><?php  echo $this->job_title ?></strong></a></span>
              <span class="clearfix"><?php echo JText::_('COM_JOBBOARD_CV_OR_RESUME') ?> :
                <a class="jobbrdmodal" href="<?php echo JFilterOutput::ampReplace('index.php?option=com_jobboard&view=user&pid='.$this->pid.'&sid='.$this->appl_uid.'&jid='.$this->jid.'&tmpl=component') ?>">
                    <strong><?php echo $this->cv_name ?></strong>
                </a>
              </span>
                 <?php $appl_status = $this->appl_data['status_id'] ?>
                 <label class="clearfix" for="status"><?php echo JText::_('COM_JOBBOARD_APPLICATION_STATUS') ?></label>
                 <select id="status" name="status">
                      <?php foreach($this->statuses as $status) : ?>
                          <option value="<?php echo $status->id ?>" <?php if($status->id == $appl_status){echo 'selected="selected"';}  ?>><?php echo $status->status_description; ?></option>
                      <?php endforeach; ?>
                 </select>
                 <label><?php echo JText::_('COM_JOBBOARD_LAST_UPDATED') ?></label>
                 <span class="right mtop10">
                     <?php echo $this->appl_data['last_modified'] == '0000-00-00 00:00:00'? JText::_('COM_JOBBOARD_NEVER') : JHTML::_('date', $this->appl_data['last_modified'], $this->day_format.' '.$this->month_long_format.', '.$this->year_format) ?>
                 </span>
                 <label class="clearfix" for="admin_notes">
                  	<?php echo JText::_('COM_JOBBOARD_NOTES'); ?> (<small><?php echo JText::_('COM_JOBBOARD_INTERNAL_USE');?>, <?php echo JText::_('COM_JOBBOARD_BACKOFFICE_ONLY');?></small>)
                  </label>
                  <span class="clearfix">&nbsp;</span>
                   <?php echo $editor->display('admin_notes', ($this->appl_data['admin_notes'] == '')? '' : htmlspecialchars($this->appl_data['admin_notes'], ENT_QUOTES), '99%', '150', '60', '20', false);  ?>
                 <?php if($this->qid > 0) : ?>
                 <small class="clearfix ptop10"><?php echo JText::_('COM_JOBBOARD_APPLQTIP') ?></small>
                  <div class="formpanel clearfix" id="fpanel">
                    <?php $ctr = 0 ?>
                    <?php if(isset($this->fields)) foreach($this->fields as $field) : ?>
                          <div id="qrow-<?php echo $ctr ?>" class="qrow w96 clear viewing<?php if($field->restricted == 1) echo ' white' ?>" <?php if($field->type == 'radio') echo 'style="min-height:'.(21*count($field->deflt->options)).'px"'; ?><?php if($field->type == 'select') if($field->deflt->multiple == 1) echo 'style="min-height:'.(18*count($field->deflt->options)).'px"'; ?>>
                            <label class="flabel<?php if($field->restricted == 1) echo ' restricted' ?>"><?php echo $field->label ?></label>
                            <?php if($field->type == 'text') : ?>
                               <input type="text" name="<?php echo $field->name ?>" id="<?php echo $field->name ?>" value="<?php echo $this->q_answers[$field->name] ?>" <?php if($field->restricted == 0) echo 'disabled="disabled"' ?> />
                            <?php endif ?>
                            <?php if($field->type == 'checkbox') : ?>
                               <input type="<?php echo $field->type ?>" name="<?php echo $field->name ?>" id="<?php echo $field->name ?>" value="yes" <?php if($this->q_answers[$field->name] == 1) echo 'checked="checked"' ?> <?php if($field->restricted == 0) echo 'disabled="disabled"' ?> />
                               <span class="checkbox"><?php echo $field->deflt->label ?></span>
                            <?php endif ?>
                            <?php if($field->type == 'radio') : ?>
                               <span class="radios">
                                    <?php if(count($field->deflt->options >= 1)) : ?>
                                        <?php foreach($field->deflt->options as $opt) : ?>
                                          <span class="radio">
                                              <label for="<?php echo $opt->id ?>"><?php echo $opt->label ?></label>
                                              <input type="radio" id="<?php echo $opt->id ?>" value="<?php echo $opt->value ?>" name="<?php echo $field->name ?>" <?php if($opt->value == $this->q_answers[$field->name]) echo 'checked="checked"'; ?> <?php if($field->restricted == 0) echo 'disabled="disabled"' ?> />
                                          </span>
                                        <?php endforeach ?>
                                   <?php endif ?>
                               </span>
                            <?php endif ?>
                            <?php if($field->type == 'select') : ?>
                               <select name="<?php echo $field->name ?>" id="<?php echo $field->name ?>" <?php if($field->deflt->multiple == 1) echo 'multiple="multiple"' ?> <?php if($field->restricted == 0) echo 'disabled="disabled"' ?>>
                                   <?php if(count($field->deflt->options >= 1)) : ?>
                                        <?php foreach($field->deflt->options as $opt) : ?>
                                              <option value="<?php echo $opt->value ?>" <?php if($opt->value == $this->q_answers[$field->name]) echo 'selected="selected"'; ?>><?php echo $opt->label ?></option>
                                        <?php endforeach ?>
                                   <?php endif ?>
                               </select>
                            <?php endif ?>
                            <?php if($field->type == 'textarea') : ?>
                               <textarea name="<?php echo $field->name ?>" id="<?php echo $field->name ?>" rows="1" cols="25" <?php if($field->restricted == 0) echo 'disabled="disabled"' ?>><?php echo $this->q_answers[$field->name] ?></textarea>
                            <?php endif ?>
                            <?php if($field->type == 'date') : ?>
                                <?php $field_date = explode('-', $this->q_answers[$field->name]) ?>
                                <input <?php if($field->restricted == 0) echo 'disabled="disabled"' ?> name="<?php echo $field->name ?>[year]" size="4" type="text" value="<?php echo sprintf("%04d", $field_date[0]) ?>" />
                                <span class="datelbl"><?php echo JText::_('COM_JOBBOARD_QYR') ?></span>
                                <select <?php if($field->deflt->showMonth == 0) echo ' class="displnone"' ?> <?php if($field->restricted == 0) echo 'disabled="disabled"' ?> name="<?php echo $field->name ?>[month]">
                                    <?php $curmonth_nr = intval($field_date[1])  ?>
                                    <?php for($i = 1; $i < 13; $i++) : ?>
                                      <?php $month_leading = sprintf("%02d",$i) ?>
                                      <?php $month_string =  '2000-'.$month_leading.'-01' ?>
                                      <option value="<?php echo $month_leading ?>" <?php if($curmonth_nr == $i) echo 'selected="selected"'; ?>><?php echo JHTML::_('date', $month_string, $this->month_long_format) ?></option>
                                    <?php endfor; ?>
                                </select>
                                <span class="datelbl<?php if($field->deflt->showMonth == 0) echo ' displnone' ?>"><?php echo JText::_('COM_JOBBOARD_QMO') ?></span>
                                <input <?php if($field->deflt->showDay == 0) echo ' class="displnone"' ?> name="<?php echo $field->name ?>[day]" <?php if($field->restricted == 0) echo 'disabled="disabled"' ?> size="3" type="text" value="<?php echo sprintf("%02d", $field_date[2]) ?>" />
                                <span class="datelbl<?php if($field->deflt->showDay == 0) echo ' displnone' ?>"><?php echo JText::_('COM_JOBBOARD_QDAY') ?></span>
                            <?php endif ?>
                          </div>
                          <?php $ctr += 1 ?>
                    <?php endforeach ?>
                  </div>
                  <?php endif ?>
               </td>
            </tr>
        </table>
    </div>
    <div style="width: 43%; float: right; clear: none">
     <fieldset>
     <legend><?php echo JText::_('COM_JOBBOARD_APPLICANT_DETAILS');?></legend>
		<table class="admintable">
          <tr class="narrowcol">
              <td>
                  <?php echo $this->loadTemplate('cvprofilesummary'); ?>
              </td>
          </tr>
        </table>
     </fieldset>
    </div>
	<input type="hidden" name="option" value="<?php echo 'com_jobboard';?>" />
	<input type="hidden" name="view" value="<?php echo JRequest::getVar('view',''); ?>" />
    <input type="hidden" name="task" value="saveappl" />
    <input type="hidden" name="aid" value="<?php echo $this->aid ?>" />
    <input type="hidden" name="jid" value="<?php echo $this->jid ?>" />
    <input type="hidden" name="qid" value="<?php echo $this->qid ?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>
<?php $getAdmninnote = $editor->getContent('admin_notes'); ?>
<?php if(!version_compare( JVERSION, '1.6.0', 'ge' )) : ?>
    <script type="text/javascript">
       var Joomla = Joomla || {};
       Joomla.submitbutton = submitform;
    </script>
<?php endif ?>
<script language="javascript" type="text/javascript">
Joomla.submitbutton = function(pressbutton)
  {
  var form = document.adminForm;
  if (pressbutton == 'saveappl' || pressbutton == 'applyappl' )
    {
      text = <?php echo $getAdmninnote; ?>;
      text = encHtml(text);
      <?php echo $editor->save( 'admin_notes' ); ?>;
      submitform( pressbutton );
      return;
    }
    else {
      submitform( pressbutton );
      return;
    }
  }
function encHtml(h){encdHtml=escape(h);encdHtml=encdHtml.replace(/\//g,"%2F");encdHtml=encdHtml.replace(/\?/g,"%3F");encdHtml=encdHtml.replace(/=/g,"%3D");encdHtml=encdHtml.replace(/&/g,"%26");encdHtml=encdHtml.replace(/@/g,"%40");return encdHtml;
}
</script>
 <?php echo $this->jb_render; ?>