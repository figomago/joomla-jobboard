<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');

$option='com_jobboard';
?>
<?php JHTML::_('stylesheet', 'config.css', 'administrator/components/com_jobboard/css/') ?>
<div id="cfigoptions">
    <ul class="tabs">
        <li class="general_tab">
    		<a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=config') ?>">
    			<span class="jbcnav_text <?php if($this->section == 'general') echo 'active' ?>"><?php echo JText::_('COM_JOBBOARD_CFG_GENERAL') ?></span>
    		</a>
    	</li>           
    	<li class="jobs_tab">
    		<a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=config&section=jobs') ?>">
    			<span class="jbcnav_text <?php if($this->section == 'jobs') echo 'active' ?>"><?php echo JText::_('COM_JOBBOARD_CFG_JOBS') ?></span>
    		</a>
    	</li>
    	<li class="maintenance_tab">
    		<a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=config&section=maintenance') ?>">
    			<span class="jbcnav_text <?php if($this->section == 'maintenance') echo 'active' ?>"><?php echo JText::_('COM_JOBBOARD_CFG_MAINT') ?></span>
    		</a>
    	</li>
    </ul>
    <span class="right"><a href="http://figomago.wordpress.com" target="_new"><?php echo 'Job Board V. '.$this->row->release_ver; ?></a></span>
</div>
<form action="index.php?option=com_jobboard&amp;view=config&amp;section=<?php echo $this->section ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
    <?php echo $this->loadTemplate($this->section); ?>
	<input type="hidden" name="id" value="<?php echo $this->row->id;?>" />
	<input type="hidden" name="option" value="<?php echo $option;?>" />
	<input type="hidden" name="view" value="<?php echo JRequest::getVar('view',''); ?>" />
	<input type="hidden" name="task" value="<?php echo JRequest::getVar('task',''); ?>" />
	<input type="hidden" name="section" value="<?php echo $this->section;?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>
<div class="clear">&nbsp;</div>
 <?php echo $this->jb_render; ?>
