<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>  <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
if(!isset($this->itemid)) $this->itemid = JRequest::getInt('Itemid');
?>
<div id="authWrapper">                                                                                             
   <a class="right logout" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=member&iview=logout&Itemid='.$this->itemid)?>"><?php echo JText::_('COM_JOBBOARD_LOGOUT') ?></a>
</div>
<ul id="tabs" class="clear">
    <li class="home">
		<a class="user" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&Itemid='.$this->itemid) ?>">
			<span class="jbcnav_text">&nbsp;</span>
		</a>
	</li>
    <li class="browse_tab">
		<a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=list&Itemid='.$this->itemid) ?>">
			<span class="jbcnav_text"><?php echo JText::_('COM_JOBBOARD_BROWSEJOBS') ?></span>
		</a>
	</li>
	<li class="boookmark_tab">
		<a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=marked&Itemid='.$this->itemid) ?>">
			<span class="jbcnav_text <?php if($this->context == 'marked') echo 'active' ?>"><?php echo JText::_('COM_JOBBOARD_MARKEDJOBS') ?></span>
		</a>
	</li>
	<li class="invites_tab">
		<a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=invites&Itemid='.$this->itemid) ?>">
			<span class="jbcnav_text <?php if($this->context == 'invites') echo 'active' ?>"><?php echo JText::_('COM_JOBBOARD_MYINVITES_SHORT') ?></span>
		</a>
	</li>
	<li class="appl_tab">
		<a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=appl&Itemid='.$this->itemid) ?>">
			<span class="jbcnav_text <?php if($this->context == 'applications') echo 'active' ?>"><?php echo JText::_('COM_JOBBOARD_MYJOBAPPLICATIONS') ?></span>
		</a>
	</li>
	<li class="cvs_tab">
		<a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=prof&Itemid='.$this->itemid) ?>">
			<span class="jbcnav_text <?php if($this->context == 'profile') echo 'active' ?>"><?php echo JText::_('COM_JOBBOARD_MYPROFILE') ?></span>
		</a>
	</li>
</ul>