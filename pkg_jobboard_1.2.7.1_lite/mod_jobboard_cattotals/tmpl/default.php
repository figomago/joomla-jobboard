<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */
?>
<table class="tbljoblist">
  <tbody>
    <tr>
	
      <th><?php echo JText::_('MOD_JOBBOARD_CATTOTALS_CATEGORY'); ?></th>
      <th><?php echo JText::_('MOD_JOBBOARD_CATTOTALS_POSITIONS'); ?></th>
    </tr>
    <?php foreach ($categories as $cat) : ?>
      <?php $link = 'index.php?option=com_jobboard&view=list&selcat='.$cat->id; ?>
        <tr>
          <td style="width: 79%;font-weight:bold"><a href="<?php echo JRoute::_($link); ?>"><?php echo $cat->type; ?></a></td>
          <td style="width: 20%"><?php echo $cat->total; ?></td>
        </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<br class="clear" />