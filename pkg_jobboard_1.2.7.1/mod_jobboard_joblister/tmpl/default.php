<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');
?>
<?php if(is_int(strpos($browser->getBrowser(), 'msie')) && intval($browser->getVersion()) < 7) : ?>
  <div class="ListerWrapper" id="ListerWrapper">
        <div class="ListerContent">
          <p><?php echo JText::_('MOD_JOBBOARD_LISTER_BOWSER_NOTSUPPORTED') ?></p>
        </div>
  </div>
<?php else : ?>
 <div class="ListerWrapper" id="ListerWrapper">
      <div class="ListerContent">
        <ul><li>&nbsp;</li>
        </ul>
        <div class="clear hidden" id="ListerControls">
          <span class="next"><?php echo JText::_('MOD_JOBBOARD_LISTER_BTN_NEXT') ?></span>
          <span class="pageInfo"><strong><span>1</span></strong> <?php echo JText::_('MOD_JOBBOARD_LISTER_TXTOF') ?> <strong><span><!--  --></span></strong></span>
          <span class="prev disabled"><?php echo JText::_('MOD_JOBBOARD_LISTER_BTN_PREV') ?></span>
          <span class="totJobs"><?php echo JText::_('MOD_JOBBOARD_LISTER_TOTAL') ?> <strong><span><!-- --></span></strong> <a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=list&limitstart=0') ?>"> &rarr; <?php echo JText::_('MOD_JOBBOARD_LISTER_VIEW_ALL') ?></a></span>
        </div>
        <div class="loading dispnone">
          <img height="32" width="32" alt="Loading" src="<?php echo JURI::base(true) ?>/modules/mod_jobboard_joblister/img/loader.gif" />
        </div>
        <br class="clear" />
    </div>
 </div>
 <form id="pagiForm" action="<?php echo JFilterOutput::ampReplace(JURI::root().'index.php?option=com_jobboard&task=json&format=json') ?>" method="post">
   <input type="hidden" name="view" value="extlist" />
   <input type="hidden" name="format" value="json" />
   <input type="hidden" name="limitstart" value="0" />
   <input type="hidden" name="limit" value="<?php echo $limit ?>" />
   <input type="hidden" name="genurl" value="<?php echo JRoute::_('index.php?option=com_jobboard&view=job&id=') ?>" />
 </form>

<script type="text/javascript">
    var Tandolin = Tandolin || {};
    window.addEvent('domready', function(){

          Tandolin.moduleLister = Tandolin.moduleLister || { };

          Tandolin.moduleLister.baseUrl = <?php echo '"'.JURI::base(true).'/";' ?>
          Tandolin.moduleLister.passengers = {limitstart: 0, limit: 0, <?php echo '"'.JUtility::getToken().'"' ?>: 1};

          Tandolin.moduleLister.engine = new TandolinJobLister({
                baseUrl: Tandolin.moduleLister.baseUrl,               
                passengers: Tandolin.moduleLister.passengers,
                pagiForm : 'pagiForm',
                listWrap : 'ListerWrapper',
                controlSection: 'ListerControls',
                fetchError : <?php echo '"'.JText::_('MOD_JOBBOARD_LISTER_FETCH_ERR').'"' ?>
          });

          Tandolin.moduleLister.engine.fetchJobs();
    });
</script>
<?php endif ?>