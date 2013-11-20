<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
?>
<?php JHTML::_('stylesheet', 'applicant.css', 'administrator/components/com_jobboard/css/') ?>
<form action="index.php" method="post" name="qForm" id="qForm">
  <table class="admintable" style="width: 99%">
      <tr class="widecol">
        <td>
             <?php if($this->qid > 0) : ?>
              <h3><?php echo $this->qtitle ?></h3>
              <small class="clearfix ptop10"><?php echo $this->qdescr ?></small>
              <div class="formpanel clearfix" id="fpanel">
                <?php $ctr = 0 ?>
                <?php if(isset($this->fields)) foreach($this->fields as $field) : ?>
                      <div id="qrow-<?php echo $ctr ?>" class="qrow w96 clear viewing<?php if($field->restricted == 1) echo ' white' ?>" <?php if($field->type == 'radio') echo 'style="min-height:'.(21*count($field->deflt->options)).'px"'; ?><?php if($field->type == 'select') if($field->deflt->multiple == 1) echo 'style="min-height:'.(18*count($field->deflt->options)).'px"'; ?>>
                        <label class="flabel<?php if($field->restricted == 1) echo ' restricted' ?>"><?php echo $field->label ?></label>
                        <?php if($field->type == 'text') : ?>
                           <input type="text" name="<?php echo $field->name ?>" id="<?php echo $field->name ?>" value="<?php echo $field->deflt ?>" disabled="disabled" />
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
                                          <input type="radio" id="<?php echo $opt->id ?>" value="<?php echo $opt->value ?>" name="<?php echo $field->name ?>" <?php if($opt->value == $field->deflt->defaultOpt) echo 'checked="checked"'; ?> disabled="disabled" />
                                      </span>
                                    <?php endforeach ?>
                               <?php endif ?>
                           </span>
                        <?php endif ?>
                        <?php if($field->type == 'select') : ?>
                           <select name="<?php echo $field->name ?>" id="<?php echo $field->name ?>" <?php if($field->deflt->multiple == 1) echo 'multiple="multiple"' ?> disabled="disabled">
                               <?php if(count($field->deflt->options >= 1)) : ?>
                                    <?php foreach($field->deflt->options as $opt) : ?>
                                          <option value="<?php echo $opt->value ?>" <?php if($opt->value == $field->deflt->defaultOpt) echo 'selected="selected"'; ?>><?php echo $opt->label ?></option>
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
                                <?php $curmonth_nr = intval($field->deflt->defaultMonth)  ?>
                                <?php for($i = 1; $i < 13; $i++) : ?>
                                  <?php $month_leading = sprintf("%02d",$i) ?>
                                  <?php $month_string =  '2000-'.$month_leading.'-01' ?>
                                  <option value="<?php echo $month_leading ?>" <?php if($curmonth_nr == $i) echo 'selected="selected"'; ?>><?php echo JHTML::_('date', $field->deflt->defaultMonth, 'F') ?></option>
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
              <?php endif ?>
           </td>
        </tr>
    </table>
</form>