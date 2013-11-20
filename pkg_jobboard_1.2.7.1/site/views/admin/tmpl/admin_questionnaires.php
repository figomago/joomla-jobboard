<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
$document = & JFactory::getDocument();
$document->setTitle(JText::_('COM_JOBBOARD').': '.JText::_('COM_JOBBOARD_ADMQUESTIONNAIRES'));
?>
<?php if(!$this->is_modal) : ?>
  <?php  $modal_params = array('handler'=> 'iframe', 'size' => array( 'x' => 640, 'y' => 480), 'sizeLoading'=>array( 'x' => 250, 'y' => 150), 'overlay'=>false, 'classWindow'=>'jobboardmodal'); ?>
  <?php JobBoardBehaviorHelper::modal('a.jobbrdmodal', $modal_params); ?>
<?php endif ?>
<?php JHTML::_('stylesheet', 'pagination.css', 'components/com_jobboard/css/') ?>

<div>
  <br class="clear" />
  <h2><?php echo JText::_('COM_JOBBOARD_QNAIRES') ?>
  <?php if($this->user_auth['manage_questionnaires'] == 1 && $this->user_auth['create_questionnaires'] == 1) : ?>
      <a class="btn btn-grn right" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=edq&qid=0&'.JUtility::getToken().'=1')?>"><?php echo JText::_('COM_JOBBOARD_ADDQNAIRE') ?></a>
  <?php endif ?>
  </h2>
   <?php $numrows = count($this->data) ?>
   <?php if($numrows < 1) : ?>
     <p><?php echo JText::_('COM_JOBBOARD_NOQNAIRES') ?></p>
   <?php else : ?>
    <ul>
         <?php $incr = 0 ?>
         <?php foreach($this->data as $row) : ?>
          <li class="list-item">
              <span class="list-content">
                <strong><a class="jobbrdmodal" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=viewq&qid='.$row->qid.'&'.JUtility::getToken().'=1&tmpl=component') ?>"><?php echo $row->title ?></a></strong>
                <small class="stardate grey"><?php echo JText::_('COM_JOBBOARD_ENT_ID') ?> : <strong><?php echo $row->id ?></strong></small>
              </span>
                <ul class="row-actions">
                    <?php if(count($row->fields)  > 0) :  ?>
                      <?php foreach($row->fields as $field) : ?>
                        <li class="ttag">
                          <span class="show-like first">
                              <?php echo $field->name ?><span class="grey">&nbsp;<?php echo '('.$field->type.')' ?></span>
                          </span>
                        </li>
                      <?php endforeach ?>
                    <?php else : ?>
                        <li>
                           <?php echo JText::_('COM_JOBBOARD_QNOFIELDS') ?>
                        </li>
                    <?php endif ?>
                </ul>
                <?php if($this->user_auth['manage_questionnaires'] == 1) : ?>
                  <form id="frmDel-<?php echo $incr ?>" method="post" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=delq&qid='.$row->qid)?>" >
                    <span class="right noclear mtopminus10 block">
                       <input class="btn-red" type="submit" value="<?php echo JText::_('COM_JOBBOARD_DELETE') ?>" />
                       <input type="hidden" name="option" value="com_jobboard" />
                       <input type="hidden" name="view" value="admin" />
                       <input type="hidden" name="task" value="delq" />
                       <input type="hidden" name="qid" value="<?php echo $row->qid ?>" />
                       <?php echo JHTML::_('form.token'); ?>
                       <a class="btn-grn noclear" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=edq&qid='.$row->qid.'&'.JUtility::getToken().'=1')?>"><?php echo JText::_('COM_JOBBOARD_EDIT') ?></a>
                    </span>
                  </form>
                <?php endif ?>
              </li>
              <?php $incr += 1 ?>
          <?php endforeach ?>
        </ul>
    <?php endif ?>
  <?php if($numrows > 0) : ?>
    <div class="clear">&nbsp;</div>
    <span class="jbRescounter"><?php echo $this->results_count; ?></span><span class="jbPagination"><?php echo $this->pagination->getPagesLinks() ?><!--  --></span>
    <div class="clear">&nbsp;</div>
  <?php endif ?>
</div>