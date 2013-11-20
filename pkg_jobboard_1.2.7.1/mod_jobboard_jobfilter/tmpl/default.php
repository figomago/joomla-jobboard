<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
?>
<?php JHTML::_('stylesheet', 'style.css', 'modules/mod_jobboard_jobfilter/css/'); ?>
<?php if(is_int(strpos($browser->getBrowser(), 'msie')) && intval($browser->getVersion()) < 7) : ?>
  <div id="filters">
        <div class="filterContent">
          <p><?php echo JText::_('MOD_JOBBOARD_FILTER_BOWSER_NOTSUPPORTED') ?></p>
        </div>
  </div>
<?php else : ?>
  <?php JHTML::_('behavior.mootools'); ?>
  <?php $base_link = 'index.php?option=com_jobboard&view=list&limitstart=0' ?>
  <?php $limit_seg = '&limitstart=0' ?>
  <?php if($locsrch == '') : ?>
    <?php JHTML::_('script', 'mod_jobboard_jobfilter'.$js_seg.'.js', 'modules/mod_jobboard_jobfilter/js/'); ?>

    <div id="filters">
          <?php $count_categories = count($data['MOD_JOBBOARD_FILTER_CATEGORIES']); $total_category_match = $data['MOD_JOBBOARD_FILTER_CATEGORIES'][0]['total'] ?>
          <h6><?php echo JText::_('MOD_JOBBOARD_FILTER_FILTERJOBS').'...' ?></h6>
    		<div class="filterSection">
    			<div class="filterToggle" id="category-filter">
                    <?php $row_state = ($selcat == 1)? 'stateClosed' : 'stateOpen'; ?>
    				<div class="left <?php echo $row_state ?>" id="category-toggle">
                        <span class="foldIcon"><!--  --></span>
                    </div>
                    <span class="loadr hidden"><!--  --></span>
    				<?php echo JText::_('MOD_JOBBOARD_FILTER_CATEGORIES'); ?>
    			</div>
    			<div class="filterContent<?php if($selcat == 1) echo ' dispnone' ?>" id="category-content">
                    <?php $cat_id_seg = '&selcat=' ?>
                    <?php $link = $base_link.$cat_id_seg.$data['MOD_JOBBOARD_FILTER_CATEGORIES'][0][1]; ?>
    				  <a class="bold allc" href="<?php echo JRoute::_($link) ?>"><?php echo JText::sprintf('MOD_JOBBOARD_FILTER_ALL', JText::_('MOD_JOBBOARD_FILTER_CATEGORIES_PL')) ?></a>
                    <?php if($selcat ==1) : ?>
    				    <span class="jobCount">(<?php echo $data['MOD_JOBBOARD_FILTER_CATEGORIES'][0]['total'] ?>)</span>
                    <?php endif ?>
                    <?php if($total_category_match != 0) : ?>
                      <?php $first_view_items = ($count_categories > $limit + 1)? $limit + 1 : $count_categories; ?>
                      <?php if($count_categories > $limit + 1) : ?>
                        <div class="scrollItems" id="category-scrollItems">
                      <?php endif ?>
                      <ul class="filter-catitem">
                          <?php if($selcat == 1) : ?>
                            <?php for($i=1; $i<$first_view_items; $i++) : ?>
                                <?php $link = $base_link.$cat_id_seg.$data['MOD_JOBBOARD_FILTER_CATEGORIES'][$i][1]; ?>
            				      <li>
                                    <a href="<?php echo JRoute::_($link) ?>"><?php echo $data['MOD_JOBBOARD_FILTER_CATEGORIES'][$i]['name'] ?></a>
                                    <span class="jobCount"> (<?php echo $data['MOD_JOBBOARD_FILTER_CATEGORIES'][$i]['total'] ?>)</span>
                                </li>
                            <?php endfor ?>
                          <?php else : ?>
                             <?php foreach($data['MOD_JOBBOARD_FILTER_CATEGORIES'] as $category) : ?>
                                <?php if(array_search($selcat, $category) == 1) : ?>
                                    <?php $link = $base_link.$cat_id_seg.$category[1].$limit_seg; ?>
                				      <li>
                                        <a href="<?php echo JRoute::_($link) ?>"><?php echo $category['name'] ?></a>
                                        <span class="jobCount"> (<?php echo $category['total'] ?>)</span>
                                        <a class="close" href="<?php echo JRoute::_($base_link.$cat_id_seg.$data['MOD_JOBBOARD_FILTER_CATEGORIES'][0][1]) ?>"><!--  --></a>
                                    </li>
                                    <?php break; ?>
                                <?php endif ?>
                             <?php endforeach ?>
                          <?php endif ?>
          				</ul>
                          <?php if($count_categories > $limit + 1) : ?>
                            <?php $page_items = $all_iems = 0 ?>
                            <?php for($c=$first_view_items; $c<$count_categories; $c++) : ?>
                                <?php if($page_items == 0) : ?>
                                    <ul class="filter-catitem">
                                <?php endif ?>
                                <?php $link = $base_link.$cat_id_seg.$data['MOD_JOBBOARD_FILTER_CATEGORIES'][$c][1]; ?>
            				      <li>
                                    <a href="<?php echo JRoute::_($link) ?>"><?php echo $data['MOD_JOBBOARD_FILTER_CATEGORIES'][$c]['name'] ?></a>
                                    <span class="jobCount"> (<?php echo $data['MOD_JOBBOARD_FILTER_CATEGORIES'][$c]['total'] ?>)</span>
                                    <?php if($selcat <> 1 && $data['MOD_JOBBOARD_FILTER_CATEGORIES'][$c]['total'] > 0) : ?>
                                        <a class="close" href="<?php echo JRoute::_($base_link.$cat_id_seg.$data['MOD_JOBBOARD_FILTER_CATEGORIES'][0][1]) ?>"><!--  --></a>
                                    <?php endif ?>
                                </li>
                                <?php $page_items += 1; $all_iems += 1 ?>
                                <?php if($page_items == $first_view_items - 1 || ($count_categories - $all_iems <= $first_view_items)) : ?>
                                  </ul>
                                  <?php $page_items = 0; ?>
                                <?php endif ?>
                            <?php endfor ?>
                            </div> <!-- #category-scrollItems -->
                            <div id="catFiltercontrols" class="clear">
                                <span class="prev disabled"><?php echo JText::_('MOD_JOBBOARD_FILTER_BTN_PREV') ?></span>
                                <span class="next"><?php echo JText::_('MOD_JOBBOARD_FILTER_BTN_NEXT') ?></span>
                            </div>
                            <br class="clear" />
                          <?php endif ?>
                      <?php else : ?>
                          <ul>
                            <li>
                                <p><?php echo JText::_('MOD_JOBBOARD_FILTER_NOJOBS') ?></p>
                            </li>
                            <li><span><!--  --></span></li>
                          </ul>
                      <?php endif ?>
    			</div>
    		</div>
            <?php $link = $base_link; ?>
            <form id="filter-form" name="filter-form" method="post" action="<?php echo JRoute::_($link); ?>">
      		<div class="filterSection">
      			<div class="filterContent" id="keywds-content">
      				<div class="filterItem">
      					<div class="left">
                              <div class="rangeHint"><?php echo JText::_('MOD_JOBBOARD_FILTER_KEYWORDS') ?></div>
      					</div>
      					<div class="right" id="mod-filter-title-srch">
      						<span class="tbLbl"><?php echo JText::_('MOD_JOBBOARD_FILTER_JOB_TITLE') ?></span>
                              <input type="text" value="<?php echo $search ?>" name="jobsearch" />
      					</div>
      					<div class="right clear" id="mod-filter-key-srch">
      						<span class="tbLbl"><?php echo JText::_('MOD_JOBBOARD_FILTER_JOB_TAGS') ?></span>
                              <input type="text" value="<?php echo $keysrch ?>" name="keysrch" />
      					</div>
                          <div class="clear">
                                <span class="clear left"><?php echo JText::_('MOD_JOBBOARD_TAGS_COMMA_SEPARATE'). ' '. JText::_('MOD_JOBBOARD_FILTER_KEYWD_SRCH_HINT') ?></span>
                                <input class="button right mright5 keywd_filter" type="submit" value="<?php echo JText::_('MOD_JOBBOARD_FILTER_GO') ?>" />
                                <a class="close<?php if($keysrch == '' && $search == '') echo ' hidden' ?>" href="<?php echo JRoute::_($base_link) ?>"><!--  --></a>
                          </div>
      					<br class="clear" />
      				</div>
      			</div>
      		</div>
          		<div class="filterSection">
          			<div class="filterToggle" id="jobtype-filter">
                        <?php $row_state = (empty($filter_job_type) || $total_category_match == 0)? 'stateClosed' : 'stateOpen'; ?>
          				<div class="left <?php echo $row_state ?>" id="jobtype-toggle">
                            <span class="foldIcon"><!--  --></span>
                        </div>
                        <span class="loadr hidden"><!--  --></span>
          				<?php echo JText::_('MOD_JOBBOARD_FILTER_JOB_TYPES') ?>
          			</div>
          			<div class="filterContent<?php if(empty($filter_job_type) || $total_category_match == 0) echo ' dispnone' ?>" id="jobtype-content">
          				<a class="bold hidden" href="#"><?php echo JText::sprintf('MOD_JOBBOARD_FILTER_ALL', JText::_('MOD_JOBBOARD_FILTER_JOB_TYPES_PL')) ?></a>
                        <ul>
                              <?php $section_empty = 0 ?>
                              <?php if(empty($data['MOD_JOBBOARD_FILTER_JOB_TYPES'])) : ?>
                                  <?php $section_empty = 1 ?>
                				  <li>
                                      <p><?php echo JText::_('MOD_JOBBOARD_FILTER_NOJOBS') ?></p>
                                  </li>
                              <?php else : ?>
                                <?php foreach($data['MOD_JOBBOARD_FILTER_JOB_TYPES'] as $jtype) : ?>
                				    <li>
                                      <?php $jobtype_index = array_search($jtype['name'], $job_types_arr); ?>
                                      <input type="checkbox" value="<?php echo $jobtype_index ?>" name="filter_job_type[]" id="filter_job_type<?php echo $jobtype_index ?>" <?php if(in_array($jobtype_index, $filter_job_type)) echo 'checked="checked"' ?> />
                                      <?php $jtype['name'] = str_replace('COM_JOBBOARD', 'MOD_JOBBOARD_FILTER', $jtype['name']); ?>
                                      <label for="filter_job_type<?php echo $jobtype_index ?>"><?php echo JText::_($jtype['name']) ?> (<strong><?php echo $jtype['total'] ?></strong>)</label>
                                  </li>
                                <?php endforeach ?>
                              <?php endif ?>
                              <li>
                                <a href="#" class="chk_reset right<?php if(empty($filter_job_type) || $section_empty == 1) echo ' hidden' ?>"><!--  --></a>
                                <input class="button right mright5 chk_filter<?php if($section_empty == 1) echo ' hidden' ?>" type="submit" value="<?php echo JText::_('MOD_JOBBOARD_FILTER_GO') ?>" />
                              </li>
          				</ul>
                        <br class="clear" />
          			</div>
          		</div>
                <div class="filterSection">
          			<div class="filterToggle" id="career-filter">
                        <?php $row_state = (empty($filter_careerlvl) || $total_category_match == 0)? 'stateClosed' : 'stateOpen'; ?>
          				<div class="left <?php echo $row_state ?>" id="career-toggle">
                            <span class="foldIcon"><!--  --></span>
                        </div>
                        <span class="loadr hidden"><!--  --></span>
          				<?php echo JText::_('MOD_JOBBOARD_FILTER_CAREER_LEVELS'); ?>
          			</div>
          			<div class="filterContent<?php if(empty($filter_careerlvl) || $total_category_match == 0) echo ' dispnone' ?>" id="career-content">
          				<a class="bold hidden" href="#"><?php echo JText::sprintf('MOD_JOBBOARD_FILTER_ALL', JText::_('MOD_JOBBOARD_FILTER_CAREER_LEVELS_PL')) ?></a>
                        <ul>
                              <?php $section_empty = 0 ?>
                              <?php if(empty($data['MOD_JOBBOARD_FILTER_CAREER_LEVELS'])) : ?>
                                  <?php $section_empty = 1 ?>
                				  <li>
                                      <p><?php echo JText::_('MOD_JOBBOARD_FILTER_NOJOBS') ?></p>
                                  </li>
                              <?php else : ?>
                                <?php foreach($data['MOD_JOBBOARD_FILTER_CAREER_LEVELS'] as $clevel) : ?>
                				  <li>
                                      <input type="checkbox" value="<?php echo $clevel['id'] ?>" name="filter_careerlvl[]" id="filter_careerlvl<?php echo $clevel['id'] ?>" <?php if(in_array($clevel['id'], $filter_careerlvl)) echo 'checked="checked"' ?> />
                                      <label for="filter_careerlvl<?php echo $clevel['id'] ?>"><?php echo JText::_($clevel['name']) ?> (<strong><?php echo $clevel['total'] ?></strong>)</label>
                                  </li>
                                <?php endforeach ?>
                              <?php endif ?>
                              <li>
                                <a href="#" class="chk_reset right<?php if(empty($filter_careerlvl) || $section_empty == 1) echo ' hidden' ?>"><!--  --></a>
                                <input class="button right mright5 chk_filter<?php if($section_empty == 1) echo ' hidden' ?>" type="submit" value="<?php echo JText::_('MOD_JOBBOARD_FILTER_GO') ?>" />
                              </li>
          				</ul>
                        <br class="clear" />
          			</div>
          		</div>
                <div class="filterSection">
          			<div class="filterToggle" id="education-filter">
                        <?php $row_state = (empty($filter_edulevel) || $total_category_match == 0)? 'stateClosed' : 'stateOpen'; ?>
          				<div class="left <?php echo $row_state ?>" id="education-toggle">
                            <span class="foldIcon"><!--  --></span>
                        </div>
                        <span class="loadr hidden"><!--  --></span>
          				<?php echo JText::_('MOD_JOBBOARD_FILTER_EDUCATION') ?>
          			</div>
          			<div class="filterContent<?php if(empty($filter_edulevel) || $total_category_match == 0) echo ' dispnone' ?>" id="education-content">
          				<a class="bold hidden" href="#"><?php echo JText::sprintf('MOD_JOBBOARD_FILTER_ALL', JText::_('MOD_JOBBOARD_FILTER_EDUCATION_PL')) ?></a>
                        <ul>
                              <?php $section_empty = 0 ?>
                              <?php if(empty($data['MOD_JOBBOARD_FILTER_EDUCATION'])) : ?>
                                  <?php $section_empty = 1 ?>
                				  <li>
                                      <p><?php echo JText::_('MOD_JOBBOARD_FILTER_NOJOBS') ?></p>
                                </li>
                              <?php else : ?>
                                  <?php foreach($data['MOD_JOBBOARD_FILTER_EDUCATION'] as $edlevel) : ?>
                  				  <li>
                                        <input type="checkbox" value="<?php echo $edlevel['id'] ?>" name="filter_edulevel[]" id="filter_edulevel<?php echo $edlevel['id'] ?>" <?php if(in_array($edlevel['id'], $filter_edulevel)) echo 'checked="checked"' ?> />
                                        <label for="filter_edulevel<?php echo $edlevel['id'] ?>"><?php echo JText::_($edlevel['name']) ?> (<strong><?php echo $edlevel['total'] ?></strong>)</label>
                                    </li>
                                  <?php endforeach ?>
                              <?php endif ?>
                              <li>
                                <a href="#" class="chk_reset right<?php if(empty($filter_edulevel) || $section_empty == 1) echo ' hidden' ?>"><!--  --></a>
                                <input class="button right mright5 chk_filter<?php if($section_empty == 1) echo ' hidden' ?>" type="submit" value="<?php echo JText::_('MOD_JOBBOARD_FILTER_GO') ?>" />
                              </li>
          				</ul>
                        <br class="clear" />
          			</div>
          		</div>
          		<div class="filterSection">
          			<div class="filterToggle" id="date-filter">
                          <?php $count_ranges = count($data['MOD_JOBBOARD_FILTER_DATE_RANGE']) ?>
                          <?php $row_state = ($date_range == 0)? 'stateClosed' : 'stateOpen'; ?>
          				<div class="left <?php echo $row_state ?>" id="date-toggle">
                              <span class="foldIcon"><!--  --></span>
                          </div>
                          <span class="loadr hidden"><!--  --></span>
          				<?php echo JText::_('MOD_JOBBOARD_FILTER_DATE_RANGE'); ?>
          			</div>
          			<div class="filterContent<?php if($date_range == 0) echo ' dispnone' ?>" id="date-content">
                          <?php $link = $base_link.'&daterange=0'; ?>
                          <?php if($date_range == 0) : ?>
          				  <span class="bold"><?php echo JText::_('MOD_JOBBOARD_FILTER_ALL_POST_DATES') ?></span>
                            <span class="jobCount"> (<?php echo $data['MOD_JOBBOARD_FILTER_CATEGORIES'][0]['total'] ?>)</span>
                          <?php else : ?>
          				    <a class="hrefSelect bold" title="daterange-0" href="#"><?php echo JText::_('MOD_JOBBOARD_FILTER_ALL_POST_DATES') ?></a>
                          <?php endif ?>
                          <?php if($count_ranges != 0) : ?>
                          <ul class="hrefSelects">
                              <?php if($date_range == 0) : ?>
                                <?php foreach($data['MOD_JOBBOARD_FILTER_DATE_RANGE'] as $range) : ?>
                                    <?php if($range['range'] > 0) : ?>
                                      <?php $link = $base_link.'&daterange='.$range['range']; ?>
                  				      <li>
                                          <a class="hrefSelect" href="#" title="<?php echo 'daterange-'.$range['range'] ?>"><?php echo ($range['range'] > 2)? JText::sprintf($range['name'], $range['range']) : JText::_($range['name']) ?></a>
                                          <span class="jobCount"> (<?php echo $range['total'] ?>)</span>
                                      </li>
                                    <?php endif ?>
                                <?php endforeach ?>
                              <?php else : ?>
                               <?php foreach($data['MOD_JOBBOARD_FILTER_DATE_RANGE'] as $range) : ?>
                                  <?php $range_match = array_keys($range, $date_range); ?>
                                  <?php if(!empty($range_match)) : ?>
                                      <?php $link = $base_link.'&daterange='.$range['range']; ?>
                  				      <li>
                                          <span><?php echo ($range['range'] > 2)? JText::sprintf($range['name'], $range['range']) : JText::_($range['name']) ?></span>
                                          <span class="jobCount"> (<?php echo $range['total'] ?>)</span>
                                          <a class="close" title="daterange-0" href="#"><!--  --></a>
                                      </li>
                                      <?php break; ?>
                                  <?php endif ?>
                               <?php endforeach ?>
                            <?php endif ?>
          				</ul>
                          <?php else : ?>
                            <ul>
                              <li>
                                  <p><?php echo JText::_('MOD_JOBBOARD_FILTER_NOJOBS') ?></p>
                              </li>
                              <li><span><!--  --></span></li>
                            </ul>
                          <?php endif ?>
          			</div>
          		</div>
                  <?php if($use_location == 1) : ?>
                    <div class="filterSection">
            			<div class="filterToggle" id="country-filter">
                            <?php $count_countries = count($data['MOD_JOBBOARD_FILTER_COUNTRIES']) ?>
                            <?php $row_state = ($country_id == 0)? 'stateClosed' : 'stateOpen'; ?>
            				<div class="left <?php echo $row_state ?>" id="country-toggle">
                                <span class="foldIcon"><!--  --></span>
                            </div>
                            <span class="loadr hidden"><!--  --></span>
            				<?php echo JText::_('MOD_JOBBOARD_FILTER_COUNTRIES'); ?>
            			</div>
            			<div class="filterContent<?php if($country_id == 0) echo ' dispnone' ?>" id="country-content">
                            <?php if($country_id == 0) : ?>
            				    <span class="bold"><?php echo JText::sprintf('MOD_JOBBOARD_FILTER_ALL', '') ?></span> <span class="jobCount">(<?php echo $data['MOD_JOBBOARD_FILTER_CATEGORIES'][0]['total'] ?>)</span>
                            <?php else : ?>
            				    <a class="hrefSelect bold" title="country_id-0" href="#"><?php echo JText::sprintf('MOD_JOBBOARD_FILTER_ALL', '') ?></a>
                            <?php endif ?>
                            <?php if($count_countries != 0) : ?>
                            <ul class="hrefSelects">
                                <?php if($country_id == 0) : ?>
                                  <?php for($i=0; $i<$count_countries; $i++) : ?>
                                      <?php $link = $base_link.$cat_id_seg.$data['MOD_JOBBOARD_FILTER_COUNTRIES'][$i]['id']; ?>
                  				      <li> <?php if($data['MOD_JOBBOARD_FILTER_COUNTRIES'][$i]['id'] == 266) $data['MOD_JOBBOARD_FILTER_COUNTRIES'][$i]['name'] = str_replace('COM_JOBBOARD', 'MOD_JOBBOARD_FILTER', $data['MOD_JOBBOARD_FILTER_COUNTRIES'][$i]['name']) ?>
                                          <a class="hrefSelect" title="country_id-<?php echo $data['MOD_JOBBOARD_FILTER_COUNTRIES'][$i]['id'] ?>" href="#"><?php echo ($data['MOD_JOBBOARD_FILTER_COUNTRIES'][$i]['id'] == 266 )? JText::_($data['MOD_JOBBOARD_FILTER_COUNTRIES'][$i]['name']) : $data['MOD_JOBBOARD_FILTER_COUNTRIES'][$i]['name'] ?></a>
                                          <span class="jobCount"> (<?php echo $data['MOD_JOBBOARD_FILTER_COUNTRIES'][$i]['total'] ?>)</span>
                                      </li>
                                  <?php endfor ?>
                                <?php else : ?>
                                   <?php foreach($data['MOD_JOBBOARD_FILTER_COUNTRIES'] as $country) : ?>
                  				      <li>
                                          <span><?php echo ($country['id'] == 266 )? JText::_($country['name']) : $country['name'] ?></span>
                                          <span class="jobCount"> (<?php echo $country['total'] ?>)</span>
                                          <a class="close" title="country_id-0" href="#"><!--  --></a>
                                      </li>
                                   <?php endforeach ?>
                                <?php endif ?>
            				</ul>
                            <?php else : ?>
                                <ul>
                                  <li>
                                      <p><?php echo JText::_('MOD_JOBBOARD_FILTER_NOJOBS') ?></p>
                                  </li>
                                  <li><span><!--  --></span></li>
                                </ul>
                            <?php endif ?>
            			</div>
            		</div>
                  <?php endif ?>
                <input type="hidden" name="daterange" value="<?php echo $date_range ?>" />
                <input type="hidden" name="selcat" value="<?php echo $selcat ?>" />
                <input type="hidden" name="country_id" value="<?php echo $country_id ?>" />
                <input type="hidden" name="cb_reset" value="false" />
                <input type="hidden" name="limitstart" value="0" />
               <?php echo JHTML::_('form.token'); ?>
            </form>
    	</div>
      <form id="texfield_defaults" name="texfield_defaults" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=list'); ?>">
         <input type="hidden" name="jobsearch-default" value="<?php echo $title_deflt ?>" />
         <input type="hidden" name="keysrch-default" value="<?php echo $keysrch_deflt ?>" />
      </form>
  <?php else : ?>
    <div id="filters">
      <form id="keywds-form" name="keywds-form" method="post" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=list'); ?>">  <div class="filterSection">
          <div id="filters">
        			<div class="filterContent" id="keywds-content">
        				<div class="filterItem">
        					<div class="left">
                                <div class="rangeHint"><?php echo JText::_('MOD_JOBBOARD_FILTER_KEYWORDS') ?></div>
        					</div>
        					<div class="right" id="mod-filter-title-srch">
        						<span class="tbLbl"><?php echo JText::_('MOD_JOBBOARD_FILTER_JOB_TITLE') ?></span><input type="text" value="<?php echo $search ?>" maxlength="64" style="width:50%;" name="jobsearch" />
        					</div>
        					<div class="right clear" id="mod-filter-key-srch">
        						<span class="tbLbl"><?php echo JText::_('MOD_JOBBOARD_FILTER_JOB_TAGS') ?></span><input type="text"  value="<?php echo $keysrch ?>" maxlength="64" style="width:50%;" name="keysrch" />
        					</div>
                            <div class="clear">
                                  <span class="clear left"><?php echo JText::_('MOD_JOBBOARD_TAGS_COMMA_SEPARATE'). ' '. JText::_('MOD_JOBBOARD_FILTER_KEYWD_SRCH_HINT') ?></span>
                                  <input class="button right mright5 keywd_filter" type="submit" value="<?php echo JText::_('MOD_JOBBOARD_FILTER_GO') ?>" />
                                  <a class="close<?php if($keysrch == '' && $search == '') echo ' hidden' ?>" href="<?php echo JRoute::_($base_link) ?>"><!--  --></a>
                            </div>
        					<br class="clear" />
        				</div>
        			</div>
        		</div>
            <p>
              <small>
                 <?php echo JText::_('MOD_JOBBOARD_LOCATION_EXCLUDED')  ?><br />
                 <a id="filtr-reset" class="right" href="#"><?php echo JText::_('MOD_JOBBOARD_LOCATION_RESET'); ?></a>
              </small>
            </p>
          </div>
          <?php $app = & JFactory::getApplication(); ?>
          <?php $selcat = $app->getUserStateFromRequest("com_jobboard.list.selcat", 'selcat', 1, 'int'); ?>

          <input type="hidden" name="locsrch" value="<?php echo $locsrch ?>" />
          <input type="hidden" name="selcat" value="<?php echo $selcat ?>" />
          <?php echo JHTML::_('form.token'); ?>
      </form>
      <script type="text/javascript">
        window.addEvent('domready', function() {
            var Tandolin = Tandolin || {};
            Tandolin.BasicFilter = Tandolin.BasicFilter || {};
            Tandolin.BasicFilter.currForm = document.forms['keywds-form'];
            Tandolin.BasicFilter.resetLinks = Tandolin.BasicFilter.currForm.getElements('a');
            Tandolin.BasicFilter.resetLinks[0].addEvent('click', function(e){
               e.stop();
               Tandolin.BasicFilter.currForm.elements['jobsearch'].value = '';
               Tandolin.BasicFilter.currForm.elements['keysrch'].value = '';
               Tandolin.BasicFilter.currForm.submit();
            });
            Tandolin.BasicFilter.resetLinks[1].addEvent('click', function(e){
               e.stop();
               Tandolin.BasicFilter.currForm.elements['locsrch'].value = '';
               Tandolin.BasicFilter.currForm.submit();
            });
          });
      </script>
    </div>
    <div class="clear"><!--  --></div>
  <?php endif ?>
<?php endif ?>