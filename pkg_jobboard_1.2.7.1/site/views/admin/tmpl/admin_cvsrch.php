<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
$document = &JFactory::getDocument();
$document->setTitle(JText::_('COM_JOBBOARD_CVSEARCH'));
?>
<?php if(!$this->is_modal) : ?>
  <?php  $modal_params = array('handler'=> 'iframe', 'size' => array( 'x' => 640, 'y' => 480), 'sizeLoading'=>array( 'x' => 250, 'y' => 150), 'overlay'=>false, 'classWindow'=>'jobboardmodal'); ?>
  <?php JobBoardBehaviorHelper::modal('a.jobbrdmodal', $modal_params); ?>
<?php endif ?>
<?php JHTML::_('stylesheet', 'cv_search.css', 'components/com_jobboard/css/') ?>
<?php JHTML::_('stylesheet', 'pagination.css', 'components/com_jobboard/css/') ?>

<div id="cvsrch">
  <br class="clear" />
  <form name="cvsrchForm" id="cvsrchForm"  method="post" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=cvsrch&Itemid='.$this->itemid) ?>">
      <div id="cvsrcharea">
            <label for="job_title"><?php echo JText::_('JOB_TITLE') ?></label>
            <input class="left" type="text" id="job_title" name="job_title" value="<?php echo $this->job_title ?>" />
            <label for="skills"><?php echo JText::_('COM_JOBBOARD_TXTSKILLS') ?></label>
            <input class="left" type="text" id="skills" name="skills" value="<?php echo $this->skills ?>" />
            <label for="qualification"><?php echo JText::_('COM_JOBBOARD_ENT_QUAL') ?></label>
            <input type="text" id="qualification" name="qualification" class="left last" value="<?php echo $this->qualification ?>" />
            <a class="clearsrch<?php if($this->job_title == '' && $this->skills == '' && $this->qualification == '') echo ' hidden' ?>" href="#">reset</a>
            <input type="submit" class="search" value="" />
            <span class="loadr hidden"><!--  --></span>
            <br class="clear" />
      </div>
      <input type="hidden" name="f_reset" value="0" />
      <input type="hidden" name="ed_level" value="<?php echo $this->ed_level ?>" />
      <?php echo JHtml::_('form.token'); ?>
  </form>
  <div id="cvprofs"><span class="block clear small"><?php echo JText::_('COM_JOBBOARD_CVP_REFINE_TIP') ?> (<?php echo JText::sprintf('COM_JOBBOARD_CVP_REFINE_TIP_EX', JText::_('COM_JOBBOARD_CVP_REFINE_TIP_EX_PHRASE')) ?>)</span>
      <?php if(empty($this->data) && $this->ed_level >= 0) : ?>
          <?php if($this->query_present == 1) : ?>
               <span class="jbPagination"><?php echo JText::_('COM_JOBBOARD_ENT_NONEFOUND') ?></span>
          <?php endif ?>
      <?php else : ?>
         <?php if($this->ed_level >= 0) : ?>
            <?php $incr = 1; ?>
            <span class="jbPagination"><?php echo $this->results_count; ?></span>
            <div class="clear">&nbsp;</div>
            <div class="refine" id="cvr-refine">
              <strong><?php echo JText::_('COM_JOBBOARD_CVP_REFINE') ?></strong>
              <br class="clear" />
              <span class="cv-ftoggle" id="job_title-toggle"><?php echo JText::_('JOB_TITLE') ?></span>
              <ul class="cvr-filter" id="job_title-cont">
                  <?php if(empty($this->title_filter)) : ?>
                     <li><?php echo JText::_('COM_JOBBOARD_CVP_NOFILTER_TITLE') ?></li>
                  <?php else : ?>
                    <?php $filter_count = 0 ?>
                    <?php foreach($this->title_filter as $filter) : ?>
                      <?php if($filter_count < 10) : ?>
                          <li>
                              <?php $is_query_phrase = JobBoardFindHelper::hasQ($this->job_title) ?>
                              <?php if(strtolower(htmlspecialchars($filter[0])) == strtolower($this->job_title) || $is_query_phrase) : ?>
                                <span><?php echo $filter[0] ?></span>
                                <?php if($is_query_phrase) : ?>
                                  <a class="job_title close" href="#"><!--  --></a>
                                <?php endif ?>
                              <?php else : ?>
                                <a href="#"><?php echo $filter[0] ?></a>
                              <?php endif ?>
                              <span class="grey">(<?php echo $filter[1] ?>)</span>
                          </li>
                        <?php $filter_count += 1 ?>
                      <?php else : ?>
                          <?php break; ?>
                      <?php endif ?>
                    <?php endforeach ?>
                  <?php endif ?>
              </ul>
              <span class="cv-ftoggle" id="skills-toggle"><?php echo JText::_('COM_JOBBOARD_TXTSKILLS') ?></span>
              <ul class="cvr-filter" id="skills-cont">
                  <?php if(empty($this->skill_filter)) : ?>
                     <li><?php echo JText::_('COM_JOBBOARD_CVP_NOFILTER_SKILL') ?></li>
                  <?php else : ?>
                    <?php $filter_count = 0 ?>
                    <?php foreach($this->skill_filter as $filter) : ?>
                      <?php if($filter_count < 10) : ?>
                          <li>
                              <?php $is_query_phrase = JobBoardFindHelper::hasQ($this->skills) ?>
                              <?php if(strtolower(htmlspecialchars($filter[0])) == strtolower($this->skills) || $is_query_phrase) : ?>
                                <span><?php echo $filter[0] ?></span>
                                <?php if($is_query_phrase) : ?>
                                  <a class="skills close singular" href="#"><!--  --></a>
                                <?php endif ?>
                              <?php else : ?>
                                <a href="#"><?php echo $filter[0] ?></a>
                              <?php endif ?>
                              <span class="grey">(<?php echo $filter[1] ?>)</span>
                          </li>
                        <?php $filter_count += 1 ?>
                      <?php else : ?>
                          <?php break; ?>
                      <?php endif ?>
                    <?php endforeach ?>
                  <?php endif ?>
              </ul>
              <span class="cv-ftoggle" id="qualification-toggle"><?php echo JText::_('COM_JOBBOARD_ENT_QUAL') ?></span>
              <ul class="cvr-filter" id="qualification-cont">
                  <?php if(empty($this->qual_filter)) : ?>
                     <li><?php echo JText::_('COM_JOBBOARD_CVP_NOFILTER_QUAL') ?></li>
                  <?php else : ?>
                    <?php $filter_count = 0 ?>
                    <?php foreach($this->qual_filter as $filter) : ?>
                      <?php if($filter_count < 10) : ?>
                          <li>
                              <?php $is_query_phrase = JobBoardFindHelper::hasQ($this->qualification) ?>
                              <?php if(strtolower(htmlspecialchars($filter[0])) == strtolower($this->qualification) || $is_query_phrase) : ?>
                                <span><?php echo $filter[0] ?></span>
                                <?php if($is_query_phrase) : ?>
                                  <a class="qualification close" href="#"><!--  --></a>
                                <?php endif ?>
                              <?php else : ?>
                                <a href="#"><?php echo $filter[0] ?></a>
                              <?php endif ?>
                              <span class="grey">(<?php echo $filter[1] ?>)</span>
                          </li>
                        <?php $filter_count += 1 ?>
                      <?php else : ?>
                          <?php break; ?>
                      <?php endif ?>
                    <?php endforeach ?>
                  <?php endif ?>
              </ul>
              <span class="cv-ftoggle" id="ed_level-toggle"><?php echo JText::_('EDUCATION') ?></span>
              <ul class="cvr-filter" id="ed_level-cont">
                  <?php if(empty($this->ed_levels) || empty($this->ed_matches)) : ?>
                     <li><?php echo JText::_('COM_JOBBOARD_CVP_NOFILTER_ED') ?></li>
                  <?php else : ?>
                    <?php $filter_count = 0 ?>
                    <?php if($this->ed_level == 0) : ?>
                      <?php foreach($this->ed_levels as $filter) : ?>
                        <?php if($filter_count < 10) : ?>
                            <?php $ed_reslts_match = array_keys($this->ed_matches, $filter['id'] ); ?>
                            <?php if(!empty($ed_reslts_match)) : ?>
                                <li><a class="edfilter" title="ed_level-<?php echo $filter['id'] ?>" href="#"><?php echo $filter['level'] ?></a></li>
                            <?php endif ?>
                          <?php $filter_count += 1 ?>
                        <?php else : ?>
                            <?php break; ?>
                        <?php endif ?>
                      <?php endforeach ?>
                    <?php else : ?>
                       <?php foreach($this->ed_levels as $filter) : ?>
                          <?php $ed_match = array_keys($filter, $this->ed_level ); ?>
                          <?php if(!empty($ed_match)) : ?>
                            <li>
                                <span><?php echo $filter['level'] ?></span>
                                <a class="edfilter close" title="ed_level-0" href="#"><!--  --></a>
                            </li>
                          <?php endif ?>
                      <?php endforeach ?>
                   <?php endif ?>
                  <?php endif ?>
              </ul>
              <br class="clear" />
            </div>
          <?php endif ?>
          <?php if(!empty($this->data)) : ?>
            <ul class="refine">
              <?php foreach($this->data as $profile) : ?>
                <li class="line">
                  <ul class="row">
                      <li class="content">
                        <?php if($profile->img['is_profile_pic'] == true) : ?>
                            <?php $randomiser = '?'.rand(1,2500) ?>
                            <img class="left noclear" src="<?php echo $profile->img['imgthumb'].$randomiser ?>" alt="<?php echo $profile->name ?>" />
                        <?php else : ?>
                             <img class="left noclear" src="components/com_jobboard/images/user_default.jpg" alt="<?php echo $profile->name ?>" />
                        <?php endif ?>
                        <span class="left noclear mleft20 link">
                          <?php if($profile->is_private) : ?>
                              <span class="private"><a class="bold" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=viewcv&pid='.$profile->id.'&sid='.$profile->user_id.'&s_mode=1&Itemid='.$this->itemid) ?>"><?php echo $profile->profile_name ?></a><span class="mleft5 mright5">&bull;</span><a class="jobbrdmodal small fnrml" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=viewcv&pid='.$profile->id.'&sid='.$profile->user_id.'&s_mode=1&Itemid='.$this->itemid.'&tmpl=component') ?>"><?php echo JText::_('COM_JOBBOARD_ENT_PREVIEW') ?></a></span>
                          <?php else : ?>
                              <strong><a class="bold" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=viewcv&pid='.$profile->id.'&sid='.$profile->user_id.'&s_mode=1&Itemid='.$this->itemid) ?>"><?php echo $profile->profile_name ?></a><span class="mleft5 mright5">&bull;&nbsp;</span><a class="jobbrdmodal small fnrml" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&task=viewcv&pid='.$profile->id.'&sid='.$profile->user_id.'&s_mode=1&Itemid='.$this->itemid.'&tmpl=component') ?>"><?php echo JText::_('COM_JOBBOARD_ENT_PREVIEW') ?></a></strong>
                          <?php endif ?> <br />
                          <?php if($profile->is_linkedin == 1) : ?>
                               <span class="small linkedin"><!--  --></span> <br />
                          <?php endif ?>
                          <?php if($this->use_location == 1 && !empty($profile->location) && $profile->location['country_id'] <> 0 ) : ?>
                               <span class="small">
                                   <?php echo $profile->location['location'].', '.$profile->location['country_name'] ?>
                              </span> <br />
                          <?php endif ?>
                          <span class="meta_info small">
                            <?php echo JText::_('COM_JOBBOARD_AVAILSTART') ?> <strong><?php echo JHTML::_('date', $profile->avail_date, $this->day_format.' '.$this->month_short_format.', '.$this->year_format) ?></strong>
                          </span>
                        </span>
                        <?php if($profile->user_id <> $this->uid) : ?>
                        <form id="frmInvite-<?php echo $incr ?>" method="post" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=jobs')?>" >
                              <input class="btn-grn mtop30 right" type="submit" value="&larr;&nbsp;<?php echo JText::_('COM_JOBBOARD_INVITE_TOJOB') ?>" />
                              <input type="hidden" name="option" value="com_jobboard" />
                              <input type="hidden" name="view" value="admin" />
                              <input type="hidden" name="task" value="jobs" />
                              <input type="hidden" name="cpid" value="<?php echo $profile->id ?>" />
                              <input type="hidden" name="sid" value="<?php echo $profile->user_id ?>" />
                              <input type="hidden" name="Itemid" value="<?php echo $this->itemid ?>" />
                              <input type="hidden" name="s_mode" value="1" />
                              <?php echo JHTML::_('form.token'); ?>
                          </form>
                        <?php endif ?>
                        <span class="left clearleft"><?php echo $profile->name ?></span>
                      </li>
                  </ul>
                </li>
                <?php $incr += 1 ?>
            <?php endforeach ?>
          </ul>
          <div class="clear">&nbsp;</div>
          <span class="jbRescounter"><?php echo $this->results_count; ?></span><span class="jbPagination"><?php echo $this->pagination->getPagesLinks() ?><!--  --></span>
          <div class="clear">&nbsp;</div>
         <?php endif ?>
      <?php endif ?>
  </div>
</div>