<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>  <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');  

?>
<ul id="subtabs">
    <?php $has_admin_functions = 0; ?>
    <?php if($this->user_auth['post_jobs'] == 1 || $this->user_auth['post_jobs'] == 1 || $this->user_auth['manage_jobs'] == 1 || $this->user_auth['manage_applicants'] == 1 || $this->user_auth['search_private_cvs'] == 1 || $this->user_auth['create_questionnaires'] == 1 || $this->user_auth['manage_questionnaires'] == 1 || $this->user_auth['manage_departments'] == 1 ) : ?>
        <?php if($this->user_auth['show_modeswitch'] == 1) : ?>
        	<li>
        		<a class="btn btn-blk" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&curr_dash=1') ?>">
        			<span class="jbcnav_text"><?php echo JText::_('COM_JOBBOARD_ADMINMODE') ?></span>
        		</a>
        	</li>
        <?php endif ?>
        <?php $has_admin_functions = 1; ?>
    <?php endif ?>
	<li>
		<a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=cvprofs') ?>">
			<span class="jbcnav_text"><?php echo JText::_('COM_JOBBOARD_MYCVPROFILES') ?></span>
		</a>
	</li>
	<li>
      <?php $this->editmode = isset($this->editmode)? $this->editmode : 0 ?>
      <?php if($this->context == 'addcv' && $this->editmode == 0) : ?>
          <span class="btn btn-grey">
  			<span class="jbcnav_text"><?php echo JText::_('COM_JOBBOARD_ADDCVPROFILE') ?></span>
  	     </span>
      <?php else :?>
  		<a class="btn" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=addcv') ?>">
  			<span class="jbcnav_text"><?php echo JText::_('COM_JOBBOARD_ADDCVPROFILE') ?></span>
  		</a>
      <?php endif ?>
	</li>
</ul>