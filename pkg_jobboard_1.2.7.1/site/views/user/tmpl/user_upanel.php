<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');  

?>
<?php JHTML::_('script', 'user_panel.js', 'components/com_jobboard/js/') ?>
<div class="panel_nav">
  <div id="usericon">
    <?php if($this->is_profile_pic == true) : ?>
        <?php $randomiser = '?'.rand(1,2500) ?>
        <img src="<?php echo $this->imgthumb.$randomiser ?>" alt="<?php echo $this->user->name ?>" />
    <?php else : ?>
         <img src="components/com_jobboard/images/user_default.jpg" alt="<?php echo JText::_('COM_JOBBOARD_PROFPIC') ?>" />
    <?php endif ?>
    <p class="caption">
        <span>
            <a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=prof&tab=2') ?>"><?php echo JText::_('COM_JOBBOARD_EDIT') ?></a>
        </span>
    </p>
  </div>
  <ul>
  	<li id="user_information">
  		<a class="user_name" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=prof') ?>"><?php echo $this->user->name ?></a>
  	</li>
  	<li id="user_nav">
    	<a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user') ?>" class="ui_actns" id="user_dashboard_link"><?php echo JText::_('COM_JOBBOARD_OVERVIEW') ?></a>&nbsp;&nbsp;&nbsp;&nbsp;
    	<a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=prof&tab=5') ?>" class="ui_actns" id="user_settings_link"><?php echo JText::_('COM_JOBBOARD_SETTINGS') ?></a>
    	<?php // if($this->layout_style == 2)  : ?>  <!-- messaging: reserved for future release -->
            <!--<a href="#" class="msg">(0)</a>-->
        <?php // endif ?>
    </li>
  </ul>
</div>