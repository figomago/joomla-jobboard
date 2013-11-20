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
      <th><?php echo JText::_('MOD_JOBBOARD_LATEST_JOBTITLE'); ?></th>
      <th><?php echo JText::_('MOD_JOBBOARD_LATEST_NUMAPPLS'); ?></th>
    </tr>
    <?php foreach ($latest_jobs as $job) : ?>
      <?php $link = 'index.php?option=com_jobboard&view=job&id='.$job->id; ?>
        <tr>
          <td style="width: 79%;font-weight:bold"><a href="<?php echo JRoute::_($link); ?>"><?php echo $job->job_title.' &mdash; '.$job->city; ?></a></td>
          <td style="width: 20%"><?php echo $job->num_applications; ?></td>
        </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php $jobs_link = 'index.php?option=com_jobboard&view=list&limitstart=0'; ?>
<p class="jlink"><a href="<?php echo JRoute::_($jobs_link); ?>"><?php echo JText::_('MOD_JOBBOARD_LATEST_VIEWLIST'); ?>&nbsp;<strong>&rarr;</strong></a></p>
<br class="clear" />