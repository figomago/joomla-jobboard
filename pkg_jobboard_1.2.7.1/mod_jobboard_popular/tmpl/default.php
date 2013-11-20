<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */
?>
<table class="tbljoblist">
  <tbody>
    <tr>
      <th><?php echo JText::_('MOD_JOBBOARD_POPULAR_JOBTITLE'); ?></th> <th><?php echo JText::_('MOD_JOBBOARD_POPULAR_VIEWS'); ?></th>
    </tr>
    <?php foreach ($top_five as $job) : ?>
      <?php $link = 'index.php?option=com_jobboard&view=job&id='.$job->id; ?>
        <tr>
          <td style="font-weight:bold"><a href="<?php echo JRoute::_($link); ?>"><?php echo $job->job_title.' &mdash; '.$job->city; ?></a></td>
          <td><?php echo $job->hits; ?></td>
        </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php $jobs_link = 'index.php?option=com_jobboard&view=list&limitstart=0'; ?>
<p class="jlink"><a href="<?php echo JRoute::_($jobs_link); ?>"><?php echo JText::_('MOD_JOBBOARD_POPULAR_VIEWLIST'); ?>&nbsp;<strong>&rarr;</strong></a></p>
<br class="clear" />