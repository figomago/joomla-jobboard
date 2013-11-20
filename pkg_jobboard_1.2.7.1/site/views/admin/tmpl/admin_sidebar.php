<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');

?>
<?php $newline = '<br />'?>
<ul id="">

<?php switch($this->context){
   case 'addcv': ?>
	<li class="helpbox bluebox">
    <?php switch($this->step){
        case 1:  ?>
            <?php echo JText::sprintf('COM_JOBBOARD_ADDCVTIP1', $newline, $newline, $newline); ?>
        <?php break; ?>
        <?php case 2:  ?>
            <?php echo JText::_('COM_JOBBOARD_ADDCVTIP2'); ?>
        <?php break; ?>
        <?php case 3:  ?>
            <?php echo JText::_('COM_JOBBOARD_ADDCVTIP3'); ?>
        <?php break; ?>
        <?php case 4:  ?>
            <?php if($this->linkedin_imported == 1) :?>
                <?php echo JText::_('COM_JOBBOARD_LIPROFILETIP'); ?>
            <?php else : ?>
                <?php echo JText::_('COM_JOBBOARD_ADDCVTIP4'); ?>
            <?php endif ?>
        <?php break; ?>
     <?php } ?>
    <?php break; ?>
   <?php } ?>
	</li>
    <?php if($this->layout_style == 3)  : ?>
    	<li class="sbheading"><span><?php echo JText::_('COM_JOBBOARD_MSGS'); ?>  </span></li>
    	<li class="text">
    		<?php echo JText::_('COM_JOBBOARD_NONEWMSG'); ?>
    	</li>
    <?php endif ?>
</ul>