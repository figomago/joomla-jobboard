<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access'); 
?>
<div id="authWrapper">                                                                                              
        <a class="right logout" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=member&iview=logout&Itemid='.$this->itemid)?>"><?php echo JText::_('COM_JOBBOARD_LOGOUT') ?></a><span class="right mright10 jbFontSize11"><?php echo $this->user->name ?></span>
</div>
<ul id="tabs">
    <li class="home">
		<a class="admin" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&Itemid='.$this->itemid) ?>">
			<span class="jbcnav_text">&nbsp;</span>
		</a>
	</li>
	<li class="jobs_tab">
		<a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=jobs&jmode=0&Itemid='.$this->itemid) ?>">
			<span class="jbcnav_text <?php if($this->context == 'jobs') echo 'active' ?>"><?php echo JText::_('COM_JOBBOARD_ADMJOBS') ?></span>
		</a>
	</li>
	<li class="inv_tab">
		<a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=invites&Itemid='.$this->itemid) ?>">
			<span class="jbcnav_text <?php if($this->context == 'invites') echo 'active' ?>"><?php echo JText::_('COM_JOBBOARD_MYINVITES_SHORT') ?></span>
		</a>
	</li>
	<li class="prosrch_tab">
		<a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=cvsrch&f_reset=1&Itemid='.$this->itemid) ?>">
			<span class="jbcnav_text <?php if($this->context == 'cvsrch') echo 'active' ?>"><?php echo JText::_('COM_JOBBOARD_CVSEARCH') ?></span>
		</a>
	</li>
	<li class="qnaires_tab">
		<a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=qlist&Itemid='.$this->itemid) ?>">
			<span class="jbcnav_text <?php if($this->context == 'questionnaires') echo 'active' ?>"><?php echo JText::_('COM_JOBBOARD_ADMQUESTIONNAIRES') ?></span>
		</a>
	</li>
	<li class="settings_tab">
		<a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=prof&tab=5&Itemid='.$this->itemid) ?>">
			<span class="jbcnav_text <?php if($this->context == 'settings') echo 'active' ?>"><?php echo JText::_('COM_JOBBOARD_SETTINGS') ?></span>
		</a>
	</li>
</ul>