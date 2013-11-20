<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');
$document = & JFactory::getDocument();
?>
<div id="user-profile-header">
    <ul id="ptabs">
        <li <?php if($this->currtab == 1) echo 'class="active"' ?>><a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=prof&tab=1&Itemid='.$this->itemid) ?>"><?php echo JText::_('COM_JOBBOARD_PROFILE') ?></a></li>
        <li <?php if($this->currtab == 2) echo 'class="active"' ?>><a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=prof&tab=2&Itemid='.$this->itemid) ?>"><?php echo JText::_('COM_JOBBOARD_PICTURE') ?></a></li>
        <li <?php if($this->currtab == 5) echo 'class="active"' ?>><a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=prof&tab=5&Itemid='.$this->itemid) ?>"><?php echo JText::_('COM_JOBBOARD_SETTINGS') ?></a></li>
    </ul>
</div>
<div id="user-profile-content">
    <form enctype="multipart/form-data" id="userForm" name="userForm" action="<?php echo JRoute::_('index.php?option=com_jobboard&view=user&task=prof&Itemid='.$this->itemid) ?>" method="post">
    <?php switch($this->currtab) {
	case 1 : ?>
        <?php $document->setTitle(JText::_('COM_JOBBOARD').': '.JText::_('COM_JOBBOARD_MYPROFILE')); ?>
        <div class="fieldrow">
          <label for="name"><?php echo JText::_('COM_JOBBOARD_ENT_NAME') ?></label>
          <div class="input">
            <input id="name" maxlength="20" name="name" size="20" type="text" value="<?php echo $this->data->name ?>" />
            <p class="user-block small"><?php echo JText::_('COM_JOBBOARD_TXTUSERNAME') ?>: <strong><?php echo $this->data->username ?></strong></p>
          </div>
        </div>
        <div class="fieldrow">
          <label for="email"><?php echo JText::_('COM_JOBBOARD_EMAIL') ?></label>
          <div class="input">
            <input id="email" name="email" size="30" type="text" value="<?php echo $this->data->email ?>" />
            <small class="msg"><!--  --></small>
            <br class="clear" />
            <hr />
          </div>
        </div>
        <div class="fieldrow">
          <label for="contact_address"><?php echo JText::_('COM_JOBBOARD_PHYSICAL_ADDRESS') ?></label>
          <div class="input">
            <textarea cols="40" id="contact_address" name="contact_address" rows="3"><?php echo $this->data->contact_address ?></textarea>
            <p class="user-block">
              <?php echo JText::_('COM_JOBBOARD_TIP_USE_ENTER') ?>
            </p>
          </div>
        </div>
        <div class="fieldrow">
          <label for="contact_location"><?php echo JText::_('LOCATION') ?></label>
          <div class="input">
            <input id="contact_location" name="contact_location" size="30" type="text" value="<?php if($this->data->contact_location == '') echo $this->config->default_city; else echo $this->data->contact_location; ?>" />
            <p class="user-block"><?php echo JText::_('COM_JOBBOARD_TIP_LOCATION') ?></p>
          </div>
        </div>
        <div class="fieldrow">
          <label for="contact_zip"><?php echo JText::_('COM_JOBBOARD_ZIPCODE') ?></label>
          <div class="input">
            <input id="contact_zip" name="contact_zip" size="30" type="text" value="<?php echo $this->data->contact_zip ?>" />
            <br class="clear" />
          </div>
        </div>
        <div class="fieldrow">
          <label for="contact_country"><?php echo JText::_('COM_JOBBOARD_TXTCOUNTRY') ?></label>
          <div class="input">
            <select id="contact_country" name="contact_country">
              <option value=""><?php echo JText::_('COM_JOBBOARD_PROFILE_SELECT_COUNTRY') ?> ...&nbsp;</option>
                <?php $default_country = ($this->data->contact_country == 0)? $this->config->default_country : $this->data->contact_country; ?>
            	<?php foreach($this->countries as $country) : ?>
                	<?php if($country->country_id == 266 ) :?>
                    <?php else: ?>
                    	<option value="<?php echo $country->country_id ?>" <?php if($country->country_id == $default_country){echo 'selected="selected"';}  ?>><?php echo $country->country_name; ?></option>
                    <?php endif;?>
                <?php endforeach ?>
            </select>
            <p class="user-block"><?php echo JText::_('COM_JOBBOARD_TIP_COUNTRY') ?></p>
            <hr />
          </div>
        </div>
        <div class="fieldrow">
          <label for="contact_phone_1"><?php echo JText::_('COM_JOBBOARD_TEL') ?> 1</label>
          <div class="input">
            <input id="contact_phone_1" name="contact_phone_1" size="30" type="text" value="<?php echo $this->data->contact_phone_1 ?>" />
            <br class="clear" />
          </div>
        </div>
        <div class="fieldrow">
          <label for="contact_phone_2"><?php echo JText::_('COM_JOBBOARD_TEL') ?> 2</label>
          <div class="input">
            <input id="contact_phone_2" name="contact_phone_2" size="30" type="text" value="<?php echo $this->data->contact_phone_2 ?>" />
            <br class="clear" />
          </div>
        </div>
        <div class="fieldrow">
          <label for="contact_fax"><?php echo JText::_('COM_JOBBOARD_FAX') ?></label>
          <div class="input">
            <input id="contact_fax" name="contact_fax" size="30" type="text" value="<?php echo $this->data->contact_fax ?>" />
            <br class="clear" />
            <hr />
          </div>
        </div>
        <div class="fieldrow">
          <label for="website_url"><?php echo JText::_('COM_JOBBOARD_URL') ?></label>
          <div class="input">
            <input id="website_url" name="website_url" size="30" type="text" value="<?php echo $this->data->website_url ?>" />
            <p class="user-block">
              <?php echo JText::_('COM_JOBBOARD_TIP_WEBSITE') ?>
            </p>
          </div>
        </div>
        <div class="fieldrow">
          <label for="linkedin_url"><?php echo JText::_('COM_JOBBOARD_LINKEDIN_NAME') ?></label>
          <div class="input">
            <input id="linkedin_url" name="linkedin_url" size="30" type="text" value="<?php echo $this->data->linkedin_url ?>" />
            <p class="user-block">
              <?php echo JText::_('COM_JOBBOARD_TIP_NAMEONLY') ?> <br />(<small><?php echo JText::sprintf('COM_JOBBOARD_TIP_PROFILE_NAME', JText::_('COM_JOBBOARD_LINKEDIN')) ?> '<strong>http://www.linkedin.com/in/<?php echo JText::_('COM_JOBBOARD_TIP_PROFILE_MYNAME') ?></strong>' <?php echo JText::sprintf('COM_JOBBOARD_TIP_PROFILE_NAMEONLY', '<strong>'.JText::_('COM_JOBBOARD_TIP_PROFILE_MYNAME').'</strong>') ?></small>)
            </p>
          </div>
        </div>
        <div class="fieldrow">
          <label for="twitter_url"><?php echo JText::_('COM_JOBBOARD_TWITTER_NAME') ?></label>
          <div class="input">
            <input id="twitter_url" name="twitter_url" size="30" type="text" value="<?php echo $this->data->twitter_url ?>" />
            <p class="user-block">
              <?php echo JText::_('COM_JOBBOARD_TIP_NAMEONLY') ?> <br />(<small><?php echo JText::sprintf('COM_JOBBOARD_TIP_PROFILE_NAME', JText::_('COM_JOBBOARD_TWITTER')) ?> '<strong>http://www.twitter.com/<?php echo JText::_('COM_JOBBOARD_TIP_PROFILE_MYNAME') ?></strong>' <?php echo JText::sprintf('COM_JOBBOARD_TIP_PROFILE_NAMEONLY', '<strong>'.JText::_('COM_JOBBOARD_TIP_PROFILE_MYNAME').'</strong>') ?></small>)
            </p>
          </div>
        </div>
        <div class="fieldrow">
          <label for="facebook_url"><?php echo JText::_('COM_JOBBOARD_FACEBOOK_NAME') ?></label>
          <div class="input">
            <input id="facebook_url" name="facebook_url" size="30" type="text" value="<?php echo $this->data->facebook_url ?>" />
            <p class="user-block">
              <?php echo JText::_('COM_JOBBOARD_TIP_NAMEONLY') ?> <br />(<small><?php echo JText::sprintf('COM_JOBBOARD_TIP_PROFILE_NAME', JText::_('COM_JOBBOARD_FACEBOOK')) ?> '<strong>http://www.facebook.com/<?php echo JText::_('COM_JOBBOARD_TIP_PROFILE_MYNAME') ?></strong>' <?php echo JText::sprintf('COM_JOBBOARD_TIP_PROFILE_NAMEONLY', '<strong>'.JText::_('COM_JOBBOARD_TIP_PROFILE_MYNAME').'</strong>') ?></small>)
            </p>
          </div>
        </div>
    <?php break; ?>
    <?php case 2 : //Profile picture ?>
        <?php $document->setTitle(JText::_('COM_JOBBOARD').': '.JText::_('COM_JOBBOARD_MYPROFILE').' - '.JText::_('COM_JOBBOARD_PICTURE')) ?>
        <?php if(!version_compare(JVERSION,'1.6.0','ge')) : ?>
            <?php JHTML::_('script', 'array_extend.js', 'components/com_jobboard/js/') ?>
        <?php endif ?>
        <?php JHTML::_('script', 'uvumi_crop.js', 'components/com_jobboard/js/') ?>
        <?php JHTML::_('script', 'user_pic.js', 'components/com_jobboard/js/') ?>
        <?php JHTML::_('stylesheet', 'cropper.css', 'components/com_jobboard/css/') ?>
        <div class="fieldrow">
          <div class="input avatar-settings">
            <p class="user-hlp">
            <?php if($this->is_profile_pic == true) :?>
                <?php echo JText::_('COM_JOBBOARD_PICPRESENT') ?>
            <?php else : ?>
                <?php echo JText::_('COM_JOBBOARD_PICABSENT') ?>
            <?php endif ?>
            </p>
            <div class="avwrapper">
                <?php if($this->is_profile_pic == true) : ?>
                    <img alt="<?php echo JText::_('COM_JOBBOARD_PROFPIC') ?>" id="profile-image" class="profile-image<?php if($this->is_early_ie) echo ' ieprof-img' ?>" src="<?php echo $this->imgpath ?>" />
                <?php else : ?>
                    <img alt="<?php echo JText::_('COM_JOBBOARD_PROFPIC') ?>" id="profile-image" class="profile-image" src="components/com_jobboard/images/user_default_l.jpg" />
                <?php endif ?>
            </div>
            <div class="actnswrapper" id="pic-actions">
              <span class="pic-upload">
                <input id="profile-image-file" onchange="handleFileSelected(this)" name="profile-image-file" type="file" />
                <br class="clear" /><?php echo JText::sprintf('COM_JOBBOARD_MAXPIC_SIZE', '1MB') ?>
              </span>
              <br class="clear" />
              <span class="pic-btns<?php if($this->is_profile_pic <> true) echo ' hidden' ?>">
                <a href="<?php echo JRoute::_('index.php?option=com_jobboard&view=image&task=imgdel&Itemid='.$this->itemid.'&'.JUtility::getToken().'=1'); ?>" id="pic-delbtn"><?php echo JText::_('COM_JOBBOARD_DELETE') ?></a>
                <input id="pic-edbtn" name="pic-edbtn" type="button" value="<?php echo JText::_('COM_JOBBOARD_EDIT') ?>" />
                <input id="pic-cropbtn" class="btn-grn hidden" name="pic-cropbtn" type="button" value="<?php echo JFilterOutput::ampReplace(JText::_('COM_JOBBOARD_CROPSAVE')) ?>" />
              </span>
              <?php if($this->is_profile_pic == false) : ?>
                <span class="pic-btns clear">
                  <input id="pic-upld" class="btn-blue"  onclick="submitForm()" name="pic-upld" type="button" value="Upload" />
                </span>
              <?php endif ?>
              <br class="clear" />
              <div id="preview"></div>
            </div>
          </div>
        </div>
        <div class="clear">&nbsp;</div>
        <input type="hidden" id="maxwidth" name="maxwidth" value="300" />
        <input type="hidden" id="maxheight" name="maxheight" value="300" />
        <?php if($this->is_profile_pic == true) : ?>
          <input type="hidden" id="crop_x" name="crop_x" value="0" />
          <input type="hidden" id="crop_y" name="crop_y" value="0" />
          <input type="hidden" id="crop_w" name="crop_w" value="0" />
          <input type="hidden" id="crop_h" name="crop_h" value="0" />
        <?php endif ?>
    <?php break; ?>
    <?php case 3 : ?>


    <?php break; ?>
    <?php case 4 : ?>

    <?php break; ?>
    <?php case 5 : //Settings ?>
        <?php $document->setTitle(JText::_('COM_JOBBOARD').': '.JText::_('COM_JOBBOARD_MYPROFILE').' - '.JText::_('COM_JOBBOARD_SETTINGS')) ?>
        <div class="fieldrow">
          <div class="input">
            <table>
                <tbody>
                    <tr class="settingsRow">
                      <td class="notifyrow">
                        <?php echo JText::_('COM_JOBBOARD_SETTINGS_NOTIFY_HEADING') ?>
                      </td>
                      <td class="cbColumn">
                        <?php echo JText::_('COM_JOBBOARD_EMAIL') ?>
                      </td>
                      <td class="cbColumn">
                        <div class="jbhidden"><?php echo JText::_('COM_JOBBOARD_TEL') ?></div>
                      </td>
                    </tr>
                    <tr>
                        <td class="setrow">
                            <?php echo JText::_('COM_JOBBOARD_SETTINGS_NOTIFY_APPLOK') ?>
                        </td>
                        <td class="cbColumn">
                            <input name="notify_on_appl_accept" type="checkbox" value="yes"<?php if($this->data['accepted'] == 1) echo ' checked="checked"' ?> />
                        </td>
                        <td class="cbColumn">
                            <input class="jbhidden" name="pnotify_on_appl_accept" type="checkbox" value="yes"<?php //if($this->data['accepted'] == 1) echo ' checked="checked"' ?> disabled="disabled" />
                        </td>
                    </tr>
                    <tr>
                        <td class="setrow">
                            <?php echo JText::_('COM_JOBBOARD_SETTINGS_NOTIFY_APPLREJECT') ?>
                        </td>
                        <td class="cbColumn">
                            <input name="notify_on_appl_reject" type="checkbox" value="yes"<?php if($this->data['rejected'] == 1) echo ' checked="checked"' ?> />
                        </td>
                        <td class="cbColumn">
                            <input class="jbhidden" name="pnotify_on_appl_reject" type="checkbox" value="yes"<?php //if($this->data['rejected'] == 1) echo ' checked="checked"' ?> disabled="disabled" />
                        </td>
                    </tr>
                    <tr>
                        <td class="setrow">
                            <?php echo JText::_('COM_JOBBOARD_SETTINGS_NOTIFY_INVITE') ?>
                        </td>
                        <td class="cbColumn">
                            <input name="email_invites" type="checkbox" value="yes"<?php if($this->data['email_invites'] == 1) echo ' checked="checked"' ?> />
                        </td>
                        <td class="cbColumn">
                            <input class="jbhidden" name="email_invites" type="checkbox" value="yes"<?php //if($this->data['email_invites'] == 1) echo ' checked="checked"' ?> disabled="disabled" />
                        </td>
                    </tr>
                    <?php if($this->is_admin == 1) : ?>
                      <tr>
                          <td colspan="3">&nbsp;</td>
                      </tr>
                      <tr class="settingsRow">
                          <td class="notifyrow"><?php echo JText::_('COM_JOBBOARD_SETTINGS_ACCT_HEADING') ?></td>
                          <td class="cbColumn">
                              <?php echo JText::_('COM_JOBBOARD_ENT_JOBSEEKER') ?>
                          </td>
                          <td class="cbColumn">
                              <div><?php echo JText::_('COM_JOBBOARD_ENT_EMPLOYER') ?></div>
                          </td>
                      </tr>
                      <tr>
                          <td class="setrow">
                              <?php echo JText::_('COM_JOBBOARD_SETTINGS_DASH') ?>
                          </td>
                          <td class="cbColumn">
                          	<input type="radio" name="login_dashboard" id="login_dashboard0" value="0" <?php if($this->data['login_dashboard'] == 0) echo 'checked="checked"'; ?> />
                          </td>
                          <td class="cbColumn">
                          	<input type="radio" name="login_dashboard" id="login_dashboard1" value="1"<?php if($this->data['login_dashboard'] == 1 && $this->is_admin == 1) echo ' checked="checked"'; ?><?php if($this->is_admin == 0) echo 'disabled="disabled"'; ?> />
                          </td>
                      </tr>
                      <tr>
                          <td colspan="3">&nbsp;</td>
                      </tr>
                      <tr class="settingsRow">

                          <td class="notifyrow"><?php echo JText::_('COM_JOBBOARD_SETTINGS_ADMIN') ?></td>
                          <td class="cbColumn">
                              <?php echo JText::_('COM_JOBBOARD_ENTYES') ?>
                          </td>
                          <td class="cbColumn">
                              <div><?php echo JText::_('COM_JOBBOARD_ENTNO') ?></div>
                          </td>
                      </tr>
                        <tr>
                            <td class="setrow">
                                <?php echo JText::_('COM_JOBBOARD_SETTINGS_MODESWITCH') ?>
                            </td>
                            <td class="cbColumn">
                            	<input type="radio" name="show_modeswitch" id="show_modeswitch1" value="1"<?php if($this->data['show_modeswitch'] == 1) echo ' checked="checked"'; ?> />
                            </td>
                            <td class="cbColumn">
                            	<input type="radio" name="show_modeswitch" id="show_modeswitch0" value="0" <?php if($this->data['show_modeswitch'] == 0) echo 'checked="checked"'; ?> />
                            </td>
                        </tr>
                        <tr>
                            <td class="setrow">
                               &nbsp;
                            </td>
                            <td colspan="2">
                                <a class="right small" href="<?php echo JRoute::_('index.php?option=com_jobboard&view=admin&Itemid='.$this->itemid); ?>"><?php echo JText::_('COM_JOBBOARD_SETTINGS_GOTO_ADMIN') ?></a>
                            </td>
                        </tr>
                    <?php endif ?>
                </tbody>
            </table>
          </div>
        </div>
    <?php break; ?>
    <?php } ?>
     <div id="frmbtns">
        <input id="settings_save" name="settings_save" class="btn btn-m btn-blue <?php if($this->currtab == 2 && $this->is_profile_pic <> true) echo 'hidden' ?>" type="submit" value="Save" />
      </div>
      <input type="hidden" name="option" value="com_jobboard" />
      <input type="hidden" name="view" value="<?php echo $this->targview ?>" />
      <input type="hidden" id="task" name="task" value="<?php echo $this->task ?>" />
      <input type="hidden" value="<?php echo $this->currtab ?>" name="currtab" id="currtab" />
      <?php echo JHTML::_('form.token'); ?>
    </form>
</div>