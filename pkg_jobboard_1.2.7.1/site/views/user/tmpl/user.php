<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
?>

<?php JHTML::_('stylesheet', 'user.css', 'components/com_jobboard/css/'); ?>
<?php JHTML::_('stylesheet', 'style_user.css', 'components/com_jobboard/css/'); ?>
<?php JHTML::_('stylesheet', 'layout_'.$this->layout_style.'col.css', 'components/com_jobboard/css/'); ?>       
<div id="jbcnav">
  <div id="topwrap">
      <?php echo $this->loadTemplate('tabs'); ?>
      <div id="upanel" class="clear">
          <?php echo $this->loadTemplate('upanel'); ?>
      </div>
  </div>
      <?php echo $this->loadTemplate('subtabs'); ?>
</div>
<div id="jbcontent" class="clear">
    <?php echo $this->loadTemplate($this->context); ?>
</div>
<?php if($this->layout_style == 3)  : ?>
  <div id="jbcsidebar">
      <?php echo $this->loadTemplate('sidebar'); ?>
  </div>
<?php endif ?>
<div class="clear"> &nbsp;</div>
<?php echo $this->setstate; ?>