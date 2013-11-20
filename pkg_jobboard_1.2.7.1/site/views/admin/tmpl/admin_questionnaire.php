<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
$document = & JFactory::getDocument();
?>

<br class="clear" />
 <?php if(!$this->editing) : // view mode ?>
   <h2><?php if($this->questionnaire['title'] == '') echo JText::_('COM_JOBBOARD_QTITLE'); else echo $this->questionnaire['title'] ?></h2>
   <p><?php if($this->questionnaire['description'] == '') echo JText::_('COM_JOBBOARD_QDESCR'); else echo $this->questionnaire['description'] ?></p>
 <?php else : ?>
   <label for="title_0"><?php echo JText::_('TITLE') ?></label>
   <input class="left" type="text" size="70" id="title_0" name="title_0" value="<?php if($this->questionnaire['title'] == '') echo JText::_('COM_JOBBOARD_QTITLE'); else echo $this->questionnaire['title'] ?>" />
   <br class="clear" />
   <label for="description_0"><?php echo JText::sprintf('COM_JOBBOARD_ENT_DESCR', JText::_('COM_JOBBOARD_QNAIRE')) ?></label>
   <textarea class="left" id="description_0" name="description_0"  cols="32" rows="2" ><?php if($this->questionnaire['description'] == '') echo JText::_('COM_JOBBOARD_QDESCR'); else echo $this->questionnaire['description'] ?></textarea>
   <div class="clear">&nbsp;</div>
 <?php endif ?>
 <?php if($this->editing) : //edit mode ?>
    <?php $document->setTitle(JText::_('COM_JOBBOARD_EDTQNAIRE')); ?>
    <?php JHTML::_('script', 'questionnaire.js', 'components/com_jobboard/js/') ?>
    <span class="newfheader clear" id="newfheader">
         <?php echo JText::_('COM_JOBBOARD_NEWQNAIREFLD') ?><br />
         <small><?php echo JText::_('COM_JOBBOARD_FEDTIP') ?></small>
         <span id="jbmsg">&nbsp;</span>
    </span>
    <div id="ed_extra">
        <span class="hdr"><!--  --></span>
        <span class="cont"><!--  --></span>
    </div>
    <div class="qedwrapper">
        <label for="field_label"><?php echo JText::_('COM_JOBBOARD_FIELDLBL') ?></label>
        <input id="field_label" name="field_label" type="text" value="" />
        <span id="fnametxt">&nbsp;</span>
        <input id="field_name" name="field_name" type="hidden" value="" />
        <input id="field_add" name="field_add" class="btn btn-grn right" type="button" value="&#8595; <?php echo JText::_('COM_JOBBOARD_FIELDADD') ?>" />
        <select id="field_type" name="field_type" class="right">
            <option value="text"><?php echo JText::_('COM_JOBBOARD_ELTEXT') ?></option>
            <option value="checkbox"><?php echo JText::_('COM_JOBBOARD_ELCHK') ?></option>
            <option value="radio"><?php echo JText::_('COM_JOBBOARD_ELRADIO') ?></option>
            <option value="textarea"><?php echo JText::_('COM_JOBBOARD_ELTEXTAREA') ?></option>
            <option value="select"><?php echo JText::_('COM_JOBBOARD_ELSELECT') ?></option>
            <option value="date"><?php echo JText::_('COM_JOBBOARD_ELDTIME') ?></option>
        </select>
        <label for="field_type" class="right"><?php echo JText::_('COM_JOBBOARD_FIELDTYPE') ?></label>
    </div>
  <?php endif ?>
  <?php if($this->editing) : //edit mode ?><small><?php echo JText::_('COM_JOBBOARD_FMOVETIP') ?></small><?php endif ?>
<div class="formpanel clear" id="fpanel">
  <?php $ctr = 0 ?>
  <?php if(isset($this->fields)) foreach($this->fields as $field) : ?>
        <div id="qrow-<?php echo $ctr ?>" class="qrow clear<?php if(!$this->editing) echo ' viewing' ?>" <?php if($field->type == 'radio') echo 'style="min-height:'.(21*count($field->deflt->options)).'px"'; ?><?php if($field->type == 'select') if($field->deflt->multiple == 1) echo 'style="min-height:'.(18*count($field->deflt->options)).'px"'; ?>>
          <label class="flabel<?php if($field->restricted == 1) echo ' restricted' ?>"><?php echo $field->label ?></label>
          <?php if($this->editing) : //edit mode ?>
            <a class="del"  href="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=delqrow')?>" title="delete" >&nbsp;</a>
            <a class="ed" href="#" title="edit">&nbsp;</a>
          <?php endif ?>
          <?php if($field->type == 'text') : ?>
             <input type="text" name="<?php echo $field->name ?>" id="<?php echo $field->name ?>" value="<?php echo $field->deflt ?>"  disabled="disabled" />
          <?php endif ?>
          <?php if($field->type == 'checkbox') : ?>
             <input type="<?php echo $field->type ?>" name="<?php echo $field->name ?>" id="<?php echo $field->name ?>" value="yes" <?php if($field->deflt->value == 1) echo 'checked="checked"' ?> disabled="disabled" />
             <span class="checkbox"><?php echo $field->deflt->label ?></span>
          <?php endif ?>
          <?php if($field->type == 'radio') : ?>
             <span class="radios">
                  <?php if(count($field->deflt->options >= 1)) : ?>
                      <?php foreach($field->deflt->options as $opt) : ?>
                        <span class="radio">
                            <label for="<?php echo $opt->id ?>"><?php echo $opt->label ?></label>
                            <input type="radio" id="<?php echo $opt->id ?>" value="<?php echo $opt->value ?>" name="<?php echo $field->name ?>" <?php if($field->deflt->defaultOpt == $opt->value) echo 'checked="checked"'; ?> disabled="disabled" />
                        </span>
                      <?php endforeach ?>
                 <?php endif ?>
             </span>
          <?php endif ?>
          <?php if($field->type == 'select') : ?>
             <select name="<?php echo $field->name ?>" id="<?php echo $field->name ?>" <?php if($field->deflt->multiple == 1) echo 'multiple="multiple"' ?> disabled="disabled">
                 <?php if(count($field->deflt->options >= 1)) : ?>
                      <?php foreach($field->deflt->options as $opt) : ?>
                            <option value="<?php echo $opt->value ?>" <?php if($field->deflt->defaultOpt == $opt->value) echo 'selected="selected"'; ?>><?php echo $opt->label ?></option>
                      <?php endforeach ?>
                 <?php endif ?>
             </select>
          <?php endif ?>
          <?php if($field->type == 'textarea') : ?>
             <textarea name="<?php echo $field->name ?>" id="<?php echo $field->name ?>" rows="1" cols="25" disabled="disabled"><?php echo $field->deflt ?></textarea>
          <?php endif ?>
          <?php if($field->type == 'date') : ?>
              <input disabled="disabled" name="<?php echo $field->name ?>[year]" size="4" type="text" value="<?php echo sprintf("%04d", $field->deflt->defaultYear) ?>" />
              <span class="datelbl"><?php echo JText::_('COM_JOBBOARD_QYR') ?></span>
              <select <?php if($field->deflt->showMonth == 0) echo ' class="displnone"' ?> disabled="disabled" name="<?php echo $field->name ?>[month]">
                  <?php $curmonth_nr = intval($this->today->toFormat("%m"))  ?>
                  <?php for($i = 1; $i < 13; $i++) : ?>
                    <?php $month_leading = sprintf("%02d",$i) ?>
                    <?php $month_string =  '2000-'.$month_leading.'-01' ?>
                    <option value="<?php echo $month_leading ?>" <?php if($curmonth_nr == $i) echo 'selected="selected"'; ?>><?php echo JHTML::_('date', $month_string, $this->month_long_format) ?></option>
                  <?php endfor; ?>
              </select>
              <span class="datelbl<?php if($field->deflt->showMonth == 0) echo ' displnone' ?>"><?php echo JText::_('COM_JOBBOARD_QMO') ?></span>
              <input <?php if($field->deflt->showDay == 0) echo ' class="displnone"' ?> name="<?php echo $field->name ?>[day]" disabled="disabled" size="3" type="text" value="<?php echo sprintf("%02d", $field->deflt->defaultDay) ?>" />
              <span class="datelbl<?php if($field->deflt->showDay == 0) echo ' displnone' ?>"><?php echo JText::_('COM_JOBBOARD_QDAY') ?></span>
          <?php endif ?>
        </div>
        <?php $ctr += 1 ?>
  <?php endforeach ?>
</div>
<div id="btn_container_footer" class="clear">
  <?php if(!$this->editing) : //view mode ?>
    <?php if($this->user_auth['manage_questionnaires'] == 1 && $this->user_auth['create_questionnaires'] == 1) : ?>
      <span class="btn">
          <a class="btn-grn" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=edq&qid='.$this->qid.'&'.JUtility::getToken().'=1')?>"><?php echo JText::_('COM_JOBBOARD_EDIT') ?></a>
      </span>
      <form id="frmDel" method="post" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=delq&qid='.$this->qid)?>" >
          <span class="btn">
               <input class="btn-red" type="submit" value="<?php echo JText::_('COM_JOBBOARD_DELETE') ?>" />
               <input type="hidden" name="option" value="com_jobboard" />
                <input type="hidden" name="view" value="user" />
                <input type="hidden" name="view" value="admin" />
                <input type="hidden" name="task" value="delq" />
                <input type="hidden" name="qid" value="<?php echo $this->qid ?>" />
                <?php echo JHTML::_('form.token'); ?>
          </span>
      </form>
     <?php endif ?>
  <?php else : //edit mode ?>
  <?php if($this->user_auth['manage_questionnaires'] == 1 || $this->user_auth['create_questionnaires'] == 1) : ?>
      <form id="qForm" name="qForm" method="post" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=saveq&qid='.$this->qid)?>" >
             <input type="hidden" name="option" value="com_jobboard" />
             <input type="hidden" name="view" value="admin" />
             <input type="hidden" name="task" value="saveq" />
             <input type="hidden" name="qid" value="<?php echo $this->qid  ?>" />
             <input type="hidden" name="name" value="<?php echo $this->questionnaire['name']  ?>" />
             <input type="hidden" name="title" value="<?php echo $this->questionnaire['title']  ?>" />
             <input type="hidden" name="description" value="<?php echo $this->questionnaire['description']  ?>" />
             <input type="hidden" name="fields" value="" />
             <input type="submit" id="save_frm" class="button right" value="<?php echo JText::_('COM_JOBBOARD_TXTSAVE').' '.JText::_('COM_JOBBOARD_QNAIRE') ?>" />
             <?php echo JHTML::_('form.token'); ?>
      </form>
    <?php endif ?>
    <?php if($this->qid > 0 && ($this->user_auth['manage_questionnaires'] || 1 && $this->user_auth['create_questionnaires'] == 1)) : ?>
      <form id="frmDel" method="post" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=delq&qid='.$this->qid)?>" >
           <input style="margin-top:0;" class="btn-red" type="submit" value="<?php echo JText::_('COM_JOBBOARD_DELETE').' '.JText::_('COM_JOBBOARD_QNAIRE') ?>" />
           <input type="hidden" name="option" value="com_jobboard" />
            <input type="hidden" name="view" value="admin" />
            <input type="hidden" name="task" value="delq" />
            <input type="hidden" name="qid" value="<?php echo $this->qid ?>" />
            <?php echo JHTML::_('form.token'); ?>
       </form>
    <?php endif ?>
  <?php endif ?>
    <a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=qlist') ?>" class="right"><small>&larr;&nbsp;<?php echo JText::_('COM_JOBBOARD_RETURN_TO_QLIST') ?></small></a>
</div>
<div class="clear">&nbsp;</div>