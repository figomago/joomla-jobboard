<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');

$document = & JFactory::getDocument();
$document->setTitle(JText::_('COM_JOBBOARD_APPLEDIT'));
?>
<?php if(!$this->is_modal) : ?>
  <?php  $modal_params = array('handler'=> 'iframe', 'size' => array( 'x' => 640, 'y' => 480), 'sizeLoading'=>array( 'x' => 250, 'y' => 150), 'overlay'=>false, 'classWindow'=>'jobboardmodal'); ?>
  <?php JobBoardBehaviorHelper::modal('a.jobbrdmodal', $modal_params); ?>
<?php endif ?>
<?php $editor = & JFactory :: getEditor(); ?>
<?php JHTML::_('behavior.tooltip'); ?>
<div class="widecol">
<br class="clear" />

<form id="frmRetrn" method="post" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=appl&jid='.$this->jid)?>" >
          <input class="btn-rnd right" type="submit" value="<?php echo '&larr;&nbsp;'.JText::_('COM_JOBBOARD_ADM_BACK') ?>" />
          <input type="hidden" name="option" value="com_jobboard" />
          <input type="hidden" name="view" value="admin" />
          <input type="hidden" name="task" value="appl" />
          <input type="hidden" name="jid" value="<?php echo $this->jid ?>" />
          <input type="hidden" name="s_context" value="<?php echo $this->s_context ?>" />
          <input type="hidden" name="Itemid" value="<?php echo $this->itemid ?>" />
          <?php echo JHTML::_('form.token'); ?>
</form>
<span><?php echo JText::_('JOB_TITLE') ?> : <a class="jobbrdmodal" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=job&id='.$this->jid.'&tmpl=component') ?>"><strong><?php  echo $this->job_title ?></strong></a></span>
<br class="clear" />
<span><?php echo JText::_('CV_RESUME') ?> :
    <?php if($this->s_context == 'site') : ?>
      <a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=getcvfile&file='.$this->aid.'&'.JUtility::getToken().'=1') ?>">
          <strong><?php  echo $this->user_prof_data['title'] ?></strong>
      </a>
    <?php endif ?>
    <?php if($this->s_context == 'user') : ?>
      <a class="jobbrdmodal" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=viewcv&pid='.$this->pid.'&sid='.$this->appl_uid.'&jid='.$this->jid.'&tmpl=component') ?>">
          <strong><?php echo $this->cv_name ?></strong>
      </a>
    <?php endif ?>
</span>
<div class="clear">&nbsp;</div>
<form id="frmAppl" name="frmAppl" method="post" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=saveappl&aid='.$this->aid)?>" >
   <?php $appl_status = $this->s_context == 'user'? $this->appl_data['status_id'] : $this->appl_data['status'] ?>
   <label for="status"><?php echo JText::_('COM_JOBBOARD_APPLICATION_STATUS') ?></label>
   <select id="status" name="status">
        <?php foreach($this->statuses as $status) : ?>
            <option value="<?php echo $status->id ?>" <?php if($status->id == $appl_status){echo 'selected="selected"';}  ?>><?php echo $status->status_description; ?></option>
        <?php endforeach; ?>
   </select>
   <label><?php echo JText::_('COM_JOBBOARD_LAST_UPDATED') ?></label>
   <span class="right mtop10">

   <?php $appl_lastmod = $this->s_context == 'user'? $this->appl_data['last_modified'] : $this->appl_data['last_updated'] ?>
       <?php echo $appl_lastmod == '0000-00-00 00:00:00'? JText::_('COM_JOBBOARD_NEVER') : JHTML::_('date', $appl_lastmod, $this->day_format.' '.$this->month_short_format.', '.$this->year_format) ?>
   </span>
   <div class="clear">&nbsp;</div>
   <label for="admin_notes">
    	<?php echo JText::_('COM_JOBBOARD_NOTES'); ?> (<small><?php echo JText::_('COM_JOBBOARD_INTERNAL_USE');?>, <?php echo JText::_('COM_JOBBOARD_BACKOFFICE_ONLY');?></small>)
    </label> <br class="clear" />
     <?php echo $editor->display('admin_notes', ($this->appl_data['admin_notes'] == '')? '' : htmlspecialchars($this->appl_data['admin_notes'], ENT_QUOTES), '99%', '150', '60', '20', false);  ?>
   <div class="clear">&nbsp;</div>
   <?php if($this->s_context == 'user') : if($this->qid > 0) : ?>
   <small><?php echo JText::_('COM_JOBBOARD_APPLQTIP') ?></small>
    <div class="formpanel clear" id="fpanel">
      <?php $ctr = 0 ?>
      <?php if(isset($this->fields)) foreach($this->fields as $field) : ?>
            <div id="qrow-<?php echo $ctr ?>" class="qrow w96 clear viewing<?php if($field->restricted == 1) echo ' white' ?>" <?php if($field->type == 'radio') echo 'style="min-height:'.(21*count($field->deflt->options)).'px"'; ?><?php if($field->type == 'select') if($field->deflt->multiple == 1) echo 'style="min-height:'.(18*count($field->deflt->options)).'px"'; ?>>
              <label class="flabel<?php if($field->restricted == 1) echo ' restricted' ?>"<?php if($field->type <> 'radio' && $field->type <> 'date') : ?> for="<?php echo $field->name ?>"<?php endif ?>><?php echo $field->label ?></label>
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
    <div class="clear">&nbsp;</div>
    <?php endif; endif; ?>
    <input class="button right" type="submit" value="<?php echo JText::_('COM_JOBBOARD_TXTSAVE') ?>" onclick="saveAppl(); return false;" />
    <input type="hidden" name="option" value="com_jobboard" />
    <input type="hidden" name="view" value="admin" />
    <input type="hidden" name="task" value="saveappl" />
    <input type="hidden" name="aid" value="<?php echo $this->aid ?>" />
    <input type="hidden" name="jid" value="<?php echo $this->jid ?>" />
    <?php if($this->s_context == 'user') : ?>
        <input type="hidden" name="qid" value="<?php echo $this->qid ?>" />
    <?php endif ?>
    <input type="hidden" name="s_context" value="<?php echo $this->s_context ?>" />
    <input type="hidden" name="Itemid" value="<?php echo $this->itemid ?>" />
    <?php echo JHTML::_('form.token'); ?>
  </form>
</div>
<div class="narrowcol">
    <?php if($this->s_context == 'user') echo $this->loadTemplate('cvprofilesummary'); ?>
    <?php if($this->s_context == 'site') : ?>
      <br class="clear" />
      <div>
          <label><?php echo JText::_('FIRSTNAME') ?></label>
        	<span class="field txtbreak">
              <?php echo $this->user_prof_data['first_name'] ?>
          </span>
      </div>
      <div>
          <label><?php echo JText::_('LASTNAME') ?></label>
        	<span class="field txtbreak">
              <?php echo $this->user_prof_data['last_name'] ?>
          </span>
      </div>
      <div>
          <label><?php echo JText::_('EMAIL_ADDRESS') ?></label>
        	<span class="field txtbreak">
              <?php echo $this->user_prof_data['email'] ?>
          </span>
      </div>
      <div>
          <label><?php echo JText::_('TELEPHONE') ?></label>
        	<span class="field txtbreak">
              <?php echo $this->user_prof_data['tel'] ?>
          </span>
      </div>
      <div>
          <strong><?php echo JText::_('COVER_NOTE') ?></strong>
          <ul>
             <li class="helpbox bluebox"><?php echo $this->user_prof_data['cover_note'] == ''? JText::_('COM_JOBBOARD_ENT_NOTENTERED') : $this->user_prof_data['cover_note'] ?></li>
          </ul>
      </div>
    <?php endif ?>
</div>
<?php $getNotes = $editor->getContent('admin_notes'); ?>
<script type="text/javascript">
    var saveAppl = function(){
        if($('fpanel')) {
           var qForm = $('fpanel');
           qForm.getElements('input').each(function(input){
               input.removeAttribute('disabled')
           });
           qForm.getElements('select').each(function(input){
               input.removeAttribute('disabled')
           });
           qForm.getElements('textarea').each(function(input){
               input.removeAttribute('disabled')
           });
        }
        text = <?php echo $getNotes; ?>
        text = encHtml(text);
        <?php echo $editor->save('admin_notes'); ?>
        document.forms['frmAppl'].submit();
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