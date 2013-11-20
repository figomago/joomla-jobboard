<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');  

?>                                                                               
<div class="panel_nav">
  <div id="usericon">
    <?php if($this->is_profile_pic == true) : ?>
        <?php $randomiser = '?'.rand(1,2500) ?>
        <img src="<?php echo $this->imgthumb.$randomiser ?> ?>" alt="Figo Mago" />
    <?php else : ?>
         <img src="components/com_jobboard/images/user_default.jpg" alt="Profile picture" />
    <?php endif ?>
    <p class="caption">
        <span>
            <a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=prof&tab=2') ?>">Edit</a>
        </span>
    </p>
  </div>
  <ul>
  	<li id="user_information">
  		<a class="user_name" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=prof') ?>"><?php echo "Lucky Duck"; //test purposes -  remove before shipping ?></a>
  	</li>
  	<li id="user_nav">
    	<a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user') ?>" class="ui_actns" id="user_dashboard_link"><?php echo JText::_('COM_JOBBOARD_OVERVIEW') ?></a>&nbsp;&nbsp;|&nbsp;
    	<a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=prof&tab=5') ?>" class="ui_actns" id="user_settings_link"><?php echo JText::_('COM_JOBBOARD_SETTINGS') ?></a>&nbsp;&nbsp;|&nbsp;
    	<?php if($this->layout_style == 2)  : ?>
            <a href="#" class="msg">(0)</a>
        <?php endif ?>
    </li>
  </ul>
</div>