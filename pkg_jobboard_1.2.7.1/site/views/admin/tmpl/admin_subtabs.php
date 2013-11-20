<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
?>
<form  id="frmAddJob" method="post" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=edjob&jid=0&Itemid='.$this->itemid)?>" >
  <ul id="subtabs">
      <?php if($this->user_auth['apply_to_jobs'] == 1) : ?>
        <?php if($this->user_auth['show_modeswitch'] == 1) : ?>
        	<li>
        		<a class="btn btn-blk" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&curr_dash=0') ?>">
        			<span class="jbcnav_text"><?php echo JText::_('COM_JOBBOARD_USERMODE') ?></span>
        		</a>
        	</li>
        <?php endif ?>
      <?php endif ?>
      <li>
        <?php if($this->user_auth['post_jobs'] == 1) : ?>
            <input class="btn btn-orng" type="submit" value="<?php echo JText::_('COM_JOBBOARD_ADDJOB') ?>" />
            <input type="hidden" name="option" value="com_jobboard" />
            <input type="hidden" name="view" value="admin" />
            <input type="hidden" name="task" value="edjob" />
            <input type="hidden" name="jid" value="0" />
            <input type="hidden" name="Itemid" value="<?php echo $this->itemid ?>" />
            <?php echo JHTML::_('form.token'); ?>
        <?php else : ?>
          <span class="btn btn-grey">
    			<span class="jbcnav_text"><?php echo JText::_('COM_JOBBOARD_ADDJOB') ?></span>
    	     </span>
        <?php endif ?>
  	 </li>
  </ul>
</form>