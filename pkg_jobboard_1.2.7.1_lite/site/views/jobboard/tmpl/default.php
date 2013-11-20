<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
$app=JFactory::getApplication();
$itemid = JRequest::getInt('Itemid');
$user = & JFactory::getUser();
?>

<!-- Style sheets -->
<?php JHTML::_('stylesheet', 'base.css', 'components/com_jobboard/css/') ?>
<?php JHTML::_('stylesheet', 'list_view_common.css', 'components/com_jobboard/css/') ?>
<?php JHTML::_('stylesheet', 'home_layout.css', 'components/com_jobboard/css/') ?>
<?php if($this->config->jobtype_coloring == 1) :?>
    <?php JHTML::_('stylesheet', 'job_colors.css', 'components/com_jobboard/css/') ?>
<?php endif ?>
<!-- End Style sheets -->

<?php $sortlink = JRoute::_('index.php?option=com_jobboard&view=list&Itemid='.$itemid); ?>
<?php $document =& JFactory::getDocument(); ?>
<?php $daterange = $this->daterange; ?>
<?php $params =& $app->getParams('com_content'); ?>
<?php $selcat = $this->selcat; ?>
<?php $seldesc = ''; ?>
<!-- feed prefix (SEF dependent) -->
<?php $test_link = JRoute::_('index.php?option=com_jobboard', 1, false); ?>
<?php $test_rsult = strstr($test_link, 'option=com_jobboard') ?>
<?php $feedPrefix = (empty($test_rsult))? '?': '&amp;' ?>
<?php $allow_reg = JobBoardHelper::allowRegistration() ?>
<div id="loginWrapper">
    <?php if($this->config->allow_unsolicited) : ?>
       <?php $unsolicited_link = 'index.php?option=com_jobboard&view=unsolicited&Itemid='.$itemid; ?>
       <script type="text/javascript">uslnk = <?php echo '"'.JRoute::_($unsolicited_link).'";' ?></script>
       <button class="button" id="topSubmitCV" ><?php echo JText::_('SUBMIT_CV_RESUME');?></button>
    <?php endif; ?>
</div>
<br class="clear" />

<?php $link = 'index.php?option=com_jobboard&view=list&Itemid='.$itemid; ?>
<form id="category_list" name="category_list" method="post" action="<?php echo JRoute::_($link); ?>">
    <?php $all_jobs = 'index.php?option=com_jobboard&view=list&selcat=1&daterange=&jobsearch=&keysrch=&locsrch=&Itemid='.$itemid; ?>
      <div id="srchInputs">
    		<div class="filterset">
                <label for="jobsearch">
                    <small><?php echo JText::_('JOB_TITLE');?>&nbsp;</small>
                </label>
                <br />
                <input class="inputfield " type="text" name="jobsearch" value="<?php echo $this->jobsearch; ?>" id="jobsearch" />
            </div>
    		<div class="filterset">
                <label for="keysrch">
                    <small><?php echo JText::_('SKILLS_KNOWLEDGE_ETC');?>&nbsp;</small>
                </label>
                <br />
                <input class="inputfield " type="text" name="keysrch" value="<?php echo $this->keysrch ?>" id="keysrch" />
            </div>
    		<?php if($this->config->use_location == 1) : ?>
    			<div class="filterset">
                    <label for="locsrch">
                        <small><?php echo JText::_('LOCATION');?>&nbsp;</small>
                    </label>
                    <br />
                    <input class="inputfield " type="text" name="locsrch" value="<?php echo $this->locsrch ?>" id="locsrch" />
                    <span><?php echo JText::_('COM_JOBBORARD_TXTSRCH_RADIUS') ?></span>
                    <select id="srchRadius" class="radius" name="radius">
                          <?php $radius_option_count = count($this->radii) ?>
                          <?php for($r=0; $r<$radius_option_count; $r++)  : ?>
      					    <option <?php if($this->sel_distance == $this->radii[$r]) echo 'selected="selected"' ?> value="<?php echo $this->radii[$r] ?>"><?php echo $this->radii[$r].' '.$this->dist_symbol ?></option>
                          <?php endfor ?>
          			</select>
                </div>
    		<?php endif; ?>
    		<div class="filterset submit">
                <input class="button filterSub" type="submit" id="filtrsubmt" value="<?php echo JText::_('SHOW_JOBS');?>" />
                <span id="loadr" class="hidel"><!--  --></span>
            </div>
            <br class="clear" />
            <div id="jbrdadvsrch" class="jbdispnone">
              <label><?php echo JText::_('COM_JOBBOARD_ADVANCED_FILTERS') ?>
                <br class="clear" />
              </label>
              <select name="selcat" id="fcats" class="inputfield jbrd-fleft">
                  <?php foreach($this->categories as $cat) : ?>
                    <option class="catitem" value="<?php echo $cat->id; ?>" <?php if($cat->id == $this->selcat) {$selcat = $cat->id; $seldesc = $cat->type; echo ' selected="selected"';}?>>
                        <?php echo $cat->type;?>
                    </option>
                  <?php endforeach; ?>
                  <?php if($this->rss_on) : ?>
                    <?php  $feed_title = $seldesc.' '.JText::_('FEED'); ?>
                    <?php  $rss = array('type' => 'application/rss+xml', 'title' => $feed_title.' (RSS)' ); ?>
                    <?php  $atom = array('type' => 'application/atom+xml', 'title' => $feed_title. ' (Atom)' ); ?>
                    <?php $all_cat_feedlink = JRoute::_('index.php?option=com_jobboard&view=rss&selcat=1'); ?>
                    <?php $feedlink = 'index.php?option=com_jobboard&view=rss&selcat='.$selcat.'&Itemid='.$itemid; ?>
                    <!-- add the header links -->
                     <?php $document->addHeadLink(JRoute::_($feedlink.'&type=rss').$feedPrefix.'format=feed', 'alternate', 'rel', $rss); $document->addHeadLink(JRoute::_($feedlink.'&type=atom').$feedPrefix.'format=feed', 'alternate', 'rel', $atom); ?>
                  <?php endif; ?>
                  <?php $document->setTitle($params->get('page_title')); ?>
               </select>
               <label for="daterange" id="drcapt">
                    <?php echo JText::_('JOBS_FROM') ?>
               </label>
               <select id="daterange" name="daterange" class="inputfield jbrd-fleft jbrd-mleft5">
                  <option class="catitem" value="0" <?php if($daterange == 0) echo ' selected="selected"';?>>
                      <?php echo JText::_('ALL_POST_DATES');?>
                  </option>
                  <option class="catitem" value="1" <?php if($daterange == 1) echo ' selected="selected"';?>>
                      <?php echo JText::_('TODAY');?>
                  </option>
                  <option class="catitem" value="2" <?php if($daterange == 2) echo ' selected="selected"';?>>
                      <?php echo JText::_('YESTERDAY');?>
                  </option>
                  <option class="catitem" value="3" <?php if($daterange == 3) echo ' selected="selected"';?>>
                      <?php echo JText::_('LAST_3_DAYS');?>
                  </option>
                  <option class="catitem" value="7" <?php if($daterange == 7) echo ' selected="selected"';?>>
                      <?php echo JText::_('LAST_7_DAYS');?>
                  </option>
                  <option class="catitem" value="14" <?php if($daterange == 14) echo ' selected="selected"';?>>
                      <?php echo JText::_('LAST_14_DAYS');?>
                  </option>
                  <option class="catitem" value="30" <?php if($daterange == 30) echo ' selected="selected"';?>>
                      <?php echo JText::_('LAST_30_DAYS');?>
                  </option>
                  <option class="catitem" value="60" <?php if($daterange == 60) echo ' selected="selected"';?>>
                      <?php echo JText::_('LAST_60_DAYS');?>
                  </option>
               </select>
               <div class="clear">&nbsp;</div>
               <label>
                <strong><?php echo JText::_('COM_JOBBOARD_ENT_REF') ?></strong>&nbsp;#&nbsp;
               </label>
               <input type="text" id="ref_num" value="" name="ref_num" maxlength="150" />
               <div class="clear">&nbsp;</div>
               <div class="clear">
                  <label>
                    <strong><?php echo JText::_('JOB_TYPE') ?></strong>
                  </label>
                  <a id="clearJtypeFilters" <?php if(empty($this->filter_job_type)) echo 'class="hidel"' ?>><?php echo JText::_('COM_JOBBOARD_RESET_FILTERS') ?></a>
                  <div id="jtCboxes" class="checkRow">
                    <?php $num_jobtypes = count($this->jobtypes);  ?>
                    <?php for($jt=0; $jt<$num_jobtypes; $jt++)  : ?>
                       <input type="checkbox" id="job_type<?php echo $jt ?>" name="filter_job_type[]" value="<?php echo $jt ?>" <?php if(in_array($jt, $this->filter_job_type)) echo 'checked="checked"' ?> /><label for="job_type<?php echo $jt ?>"><?php echo JText::_($this->jobtypes[$jt]) ?></label>
                    <?php endfor ?>
                  </div>
               </div>
               <div class="clear">&nbsp;</div>
               <div id="clCboxes" class="clear">
                  <label><strong><?php echo JText::_('CAREER_LEVEL') ?></strong></label><a id="clearClevelFilters" <?php if(empty($this->filter_careerlvl)) echo 'class="hidel"' ?>><?php echo JText::_('COM_JOBBOARD_RESET_FILTERS') ?></a>
                  <div class="checkRow">
                    <div class="jbdcolumn">
                      <?php $num_clevels = count($this->jobcareerlvls); $cl_count = 0; $multicol = $num_clevels > 4? true : false; ?>
                       <?php foreach($this->jobcareerlvls as $clevel)  : ?>
                         <input type="checkbox" id="careerlvl<?php echo $clevel['id'] ?>" name="filter_careerlvl[]" value="<?php echo $clevel['id'] ?>" <?php if(in_array($clevel['id'], $this->filter_careerlvl)) echo 'checked="checked"' ?> /><label for="careerlvl<?php echo $clevel['id'] ?>"><?php echo $clevel['description'] ?></label>
                         <br />
                       <?php if($multicol == true && $cl_count == floor($num_clevels/2)-1 )  :?>
                         </div><div class="jbdcolumn">
                       <?php endif ?>
                       <?php if($cl_count == ($num_clevels-1)) :?>
                         </div>
                       <?php endif ?>
                       <?php $cl_count++ ?>
                    <?php endforeach ?>
                  </div>
               </div>
               <div class="clear">&nbsp;</div>
               <div id="elCboxes" class="clear">
                  <label><strong><?php echo JText::_('EDUCATION') ?></strong></label><a id="clearElevelFilters" <?php if(empty($this->filter_edulevel)) echo 'class="hidel"' ?>><?php echo JText::_('COM_JOBBOARD_RESET_FILTERS') ?></a>
                  <div class="checkRow">
                    <div class="jbdcolumn">
                      <?php $num_elevels = count($this->jobedlvls); $el_count = 0; $multicol = $num_elevels > 4? true : false; ?>
                       <?php foreach($this->jobedlvls as $elevel)  : ?>
                         <input type="checkbox" id="edulevel<?php echo $elevel['id'] ?>" name="filter_edulevel[]" value="<?php echo $elevel['id'] ?>" <?php if(in_array($elevel['id'], $this->filter_edulevel)) echo 'checked="checked"' ?> /><label for="edulevel<?php echo $elevel['id'] ?>"><?php echo $elevel['level'] ?></label>
                         <br />
                       <?php if($multicol == true && $el_count == floor($num_elevels/2)-1 )  :?>
                         </div><div class="jbdcolumn">
                       <?php endif ?>
                       <?php if($el_count == ($num_elevels-1)) :?>
                         </div>
                       <?php endif ?>
                       <?php $el_count++ ?>
                    <?php endforeach ?>
                  </div>
               </div>
               <input class="button filterSub jbrd-fright jbrd-mtop50" id="filtersub_b" type="submit" value="<?php echo JText::_('SHOW_JOBS');?>" />
            </div> <!-- #jbrdadvsrch -->
            <a id="advsrch" href="#" class="jbrd-fright closed" ><?php echo JText::_('COM_JOBBOARD_ADVANCED_SEARCH') ?></a>
      </div><!-- #srchInputs -->
      <?php if ($selcat <> 1 || $daterange <> 0 || $this->country_id <> 0) : ?>
        <div id="keywd_info" class="filterset">
            <?php if($this->country_id == 0) : ?>
              <?php if ($selcat <> 1 && $daterange < 1) echo $seldesc ?>
              <?php if ($selcat <> 1 && $daterange > 0): echo $seldesc; ?> (<?php echo strtolower(JText::sprintf('COM_JOBBOARD_PAST_N_DAYS', $daterange)); ?>)<?php endif; ?>
              <?php if ($selcat == 1 && $daterange > 0) echo JText::sprintf('COM_JOBBOARD_PAST_N_DAYS', $daterange) ?>
            <?php elseif($this->country_id <> 0) : ?>
              <?php $filter_countryname = JobBoardHelper::getCountryName($this->country_id); ?>
              <?php if($this->country_id == 266) $filter_countryname = JText::_($filter_countryname); ?>
              <?php if ($selcat <> 1 && $daterange < 1) : echo $seldesc; ?> (<?php echo $filter_countryname; ?>)<?php endif; ?>
              <?php if ($selcat <> 1 && $daterange > 0) : echo $seldesc; ?> (<?php echo strtolower(JText::sprintf('COM_JOBBOARD_PAST_N_DAYS', $daterange)).' - '.$filter_countryname; ?>)<?php endif; ?>
              <?php if ($selcat == 1 && $daterange > 0) : echo JText::sprintf('COM_JOBBOARD_PAST_N_DAYS', $daterange); ?> (<?php echo $filter_countryname; ?>)<?php endif; ?>
              <?php if ($selcat == 1 && $daterange < 1)  echo ucfirst($filter_countryname); ?>
            <?php endif ?>
            <?php if ($selcat <> 1) : ?>: <a id="jall" href="<?php echo JRoute::_($all_jobs); ?>" class="JobLink"><?php echo JText::_('VIEW_ALL_JOBS'); ?></a><?php endif; ?>
        </div>
      <?php endif; ?>
      <div id="srch_info" class="filterset clear">
          <?php $reset_keywords = 'index.php?option=com_jobboard&view=list&task=reset_keywds&Itemid='.$itemid; ?>
          <?php if (strlen($this->locsrch) > 0 && $this->geo_address <> '') echo JText::_('COM_JOBBOARD_JOBS_NEAR').' '.ucfirst($this->geo_address).' &bull; ';  ?><a id="reset_keywds" href="<?php echo JRoute::_($reset_keywords); ?>" class="JobLink<?php if (strlen($this->jobsearch) == 0 && strlen($this->keysrch) == 0 && strlen($this->locsrch) == 0) echo ' jbhidden' ?>"><?php echo JText::_('COM_JOBBOARD_RESET_KEYWDS'); ?></a><br class="clear" />
          <?php if(!empty($this->filter_job_type) || !empty($this->filter_careerlvl) || !empty($this->filter_edulevel) || $this->country_id > 0 || $this->daterange > 0) : ?>
           <?php echo JText::_('COM_JOBBOARD_ADVANCED_FILTERS_ACTIVE'); ?>&nbsp;&bull;&nbsp;<a id="reset_advfilters" href="#"><?php echo JText::_('COM_JOBBOARD_RESET_ACTIVE_FILTERS'); ?></a>
          <?php endif ?>
      </div>
      <input type="hidden" name="layout" value="<?php echo $this->layout ?>" />
      <input type="hidden" name="switch_layout" value="0" />
      <input type="hidden" id="country_id" name="country_id" value="<?php echo $this->country_id ?>" />
      <?php if($this->config->use_location <> 1) : ?>
         <input class="inputfield" type="hidden" name="locsrch" value="<?php echo $this->locsrch ?>" id="locsrch" />
      <?php endif; ?>
      <input class="inputfield" type="hidden" name="cb_reset" value="0" id="cb_reset" />
      <?php echo JHTML::_('form.token'); ?>
</form>
      <div id="jobtable">
        <?php $tbl_view_link = 'index.php?option=com_jobboard&view=list&Itemid='.$itemid; ?>
        <div class="pagination" >&nbsp;</div>
        <div class="home-panel first pright20">
          <h3><?php echo $this->intro['home_intro_title'] ?></h3>
          <?php echo $this->intro['home_intro'] ?>
        </div>
        <br class="clear" />
        <div class="snapshot">           
            <div class="subcontent" id="content-categories">
                <?php $count_categories = count($this->intro_cats) ?>
                <?php if($count_categories > 0) : ?>
                <?php $categ_link = 'index.php?option=com_jobboard&view=list&selcat=' ?>
                    <?php $item_limit = 10 ?>
                    <?php $first_view_items = ($count_categories > $item_limit)? $item_limit : $count_categories; ?>
                        <ul class="categories-itemcol">
                          <?php for($c=0; $c<$first_view_items; $c++) : ?>
                            <?php $link = $categ_link.$this->intro_cats[$c]['id'].'&Itemid='.$itemid; ?>
        				      <li>
                                <a href="<?php echo JRoute::_($link) ?>"><?php echo $this->intro_cats[$c]['name'] ?></a>
                                <span class="jobCount"> (<?php echo $this->intro_cats[$c]['total'] ?>)</span>
                            </li>
                          <?php endfor ?>
                        </ul>
                    <?php if($count_categories > $item_limit) : ?>
                      <?php $col_items = $all_iems = 0; $col_num = 2; ?>
                      <?php for($c=$first_view_items; $c<$count_categories; $c++) : ?>
                          <?php if($col_items == 0) : ?>
                              <ul class="categories-itemcol<?php if($col_num%2 === 1) echo ' clear' ?>">
                          <?php endif ?>
                          <?php $link = $categ_link.$this->intro_cats[$c]['id'].'&Itemid='.$itemid; ?>
      				      <li>
                              <a href="<?php echo JRoute::_($link) ?>"><?php echo $this->intro_cats[$c]['name'] ?></a>
                              <span class="jobCount"> (<?php echo $this->intro_cats[$c]['total'] ?>)</span>
                          </li>
                          <?php $col_items += 1; $all_iems += 1 ?>
                          <?php if($col_items == $first_view_items || ($count_categories - $all_iems <= $first_view_items)) : ?>
                            </ul>
                            <?php $col_num += 1 ?>
                            <?php $col_items = 0; ?>
                          <?php endif ?>
                      <?php endfor ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="clear browseall">
            <a class="right mright20 JobLink" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=list&selcat=1&limitstart=0'); ?>">&rarr;&nbsp;<?php echo JText::_('COM_JOBBOARD_ENT_BROWSE_JOBS');?></a>
        </div>
        <?php if($this->rss_on) : ?>
          <div id="feedarea" class="clear">
             <?php echo '<b>'.JText::_('RSS'). ' </b>' .JText::_('FEED'); ?>: <a class="feedicon" href="<?php echo JRoute::_($feedlink).$feedPrefix.'format=feed' ?>"><?php echo $seldesc; ?></a>
          </div>
        <?php endif; ?>
      </div>
<?php echo $this->setstate; ?>