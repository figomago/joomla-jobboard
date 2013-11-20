<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

defined('_JEXEC') or die('Restricted access');   

jimport('joomla.installer.installer');

   // Load Jobboard language file
   $lang = &JFactory::getLanguage();
   $lang->load('com_jobboard');
   $src = $this->parent->getPath('source');
   $db = & JFactory::getDBO();

   $status = new JObject();
   $status->modules = array();
   $status->plugins = array();

  /*******************************
  * Modules
  ******************************/

  if(version_compare( JVERSION, '1.6.0', 'ge' )) {
     $modules = &$this->manifest->xpath('modules/module');
     $children = $modules;
     $is_go = true;
  } else {
     $modules = &$this->manifest->getElementByPath('modules');
     $is_go = is_a($modules, 'JSimpleXMLElement') && count($modules->children())? true : false;
     if($is_go)
        $children = $modules->children();
  }
  if ($is_go) {

  	foreach ($children as $module)
  	{
  	    set_time_limit(0);

        if(version_compare( JVERSION, '1.6.0', 'ge' )) {
    		$mname		= 'mod_'.$module->getAttribute('module');
            $mclient    = $module->getAttribute('client');
    	  $query = "SELECT ".$db->nameQuote("extension_id")."
            FROM ".$db->nameQuote("#__extensions")."
            WHERE ".$db->nameQuote("type")." = ".$db->Quote("module")."
             AND ".$db->nameQuote("element")." = ".$db->Quote($mname).";";
        } else {
    		$mname		= 'mod_'.$module->attributes('module');
            $mclient    = $module->attributes('client');
    	  $query = "SELECT ".$db->nameQuote("id")."
            FROM ".$db->nameQuote("#__modules")."
            WHERE ".$db->nameQuote("module")." = ".$db->Quote($mname).";";
        }

  		$db->setQuery($query);
  		$db->query();

    	$modules = $db->loadResultArray();
    	if (count($modules)) {
    		foreach ($modules as $module) {
    			$installer = new JInstaller;
    			$result = $installer->uninstall('module', $module, 0);
    		}
    	}
    	$status->modules[] = array ('name'=>$mname, 'client'=>$mclient, 'result'=>$result);
  	}
  }

   /*******************************
   * Plugins
   * *****************************/

  if(version_compare( JVERSION, '1.6.0', 'ge' )) {
     $plugins = &$this->manifest->xpath('plugins/plugin');
     $children = $plugins;
     $is_go = true;
  } else {
     $plugins = &$this->manifest->getElementByPath('plugins');
     $is_go = is_a($plugins, 'JSimpleXMLElement') && count($plugins->children())? true : false;
     if($is_go)
        $children = $plugins->children();
  }
  if ($is_go) {

  	foreach ($children as $plugin)
  	{
  	    set_time_limit(0);

        if(version_compare( JVERSION, '1.6.0', 'ge' )) {
    		$pname		= $plugin->getAttribute('plugin');
    		$pgroup		= $plugin->getAttribute('group');
    	  $query = "SELECT ".$db->nameQuote("extension_id")."
            FROM ".$db->nameQuote("#__extensions")."
            WHERE ".$db->nameQuote("type")." = ".$db->Quote("plugin")."
             AND ".$db->nameQuote("element")." = ".$db->Quote($pname)."
             AND ".$db->nameQuote("folder")." = ".$db->Quote($pgroup).";";
        } else {
    		$pname		= 'plg_'.$plugin->attributes('plugin');
    		$pgroup		= $plugin->attributes('group');
    	  $query = "SELECT ".$db->nameQuote("id")."
            FROM ".$db->nameQuote("#__plugins")."
            WHERE ".$db->nameQuote("element")." = ".$db->Quote($pname)."
             AND ".$db->nameQuote("folder")." = ".$db->Quote($pgroup).";";
        }

        $db->setQuery($query);
    	$plugins = $db->loadResultArray();
    	if (count($plugins)) {
    		foreach ($plugins as $plugin) {
    			$installer = new JInstaller;
    			$result = $installer->uninstall('plugin', $plugin, 0);
    		}
    	}
    	$status->plugins[] = array ('name'=>$pname, 'group'=>$pgroup, 'result'=>$result);
  	}
  }

   /*******************************
   * Multi-languge (JoomFish)
   * *****************************/

  if (JFolder::exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomfish'.DS.'contentelements')){

  	if(version_compare( JVERSION, '1.6.0', 'ge' )) {
  		$elements = &$this->manifest->xpath('joomfish/defn');
  		foreach ($elements as $element) {
  			if(JFile::exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomfish'.DS.'contentelements'.DS.$element->data()))
			JFile::delete(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomfish'.DS.'contentelements'.DS.$element->data());
  		}
  	}
  	else {
  		$elements = &$this->manifest->getElementByPath('joomfish');
  		if (is_a($elements, 'JSimpleXMLElement') && count($elements->children())) {
  			foreach ($elements->children() as $element) {
  				if(JFile::exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomfish'.DS.'contentelements'.DS.$element->data()))
    			JFile::delete(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomfish'.DS.'contentelements'.DS.$element->data());
  			}
  		}
  	}

  }
  ?>

  <?php $rows = 0; ?>
  <h2><?php echo 'Job Board ' . JText::_('COM_JOBBOARD_ININSTALL_RESLT'); ?></h2>
  <table class="adminlist">
  	<thead>
  		<tr>
  			<th class="title" colspan="2"><?php echo JText::_('COM_JOBBOARD_EXT'); ?></th>
  			<th width="30%"><?php echo JText::_('COM_JOBBOARD_INSTALL_STATUS'); ?></th>
  		</tr>
  	</thead>
  	<tfoot>
  		<tr>
  			<td colspan="3"></td>
  		</tr>
  	</tfoot>
  	<tbody>
  		<tr class="row0">
  			<td class="key" colspan="2"><?php echo JText::_('COM_JOBBOARD_COMP'); ?></td>
  			<td><strong>&nbsp;</strong></td>
  		</tr>
  		<?php if (count($status->modules)): ?>
  		<tr>
  			<th><?php echo JText::_('COM_JOBBOARD_MODULE'); ?></th>
  			<th><?php echo JText::_('COM_JOBBOARD_CLIENT'); ?></th>
  			<th></th>
  		</tr>
  		<?php foreach ($status->modules as $module): ?>
      		<tr class="row<?php echo (++ $rows % 2); ?>">
      			<td class="key"><?php echo $module['name']; ?></td>
      			<td class="key"><?php echo ucfirst($module['client']); ?></td>
      			<td>
                    <strong>
                        <?php echo ($module['result'])?JText::_('COM_JOBBOARD_REMOVED_TRUE'):JText::_('COM_JOBBOARD_REMOVED_FALSE'); ?>
                    </strong>
                  </td>
      		</tr>
  		<?php endforeach; ?>
  		<?php endif; ?>
  		<?php if (count($status->plugins)): ?>
    		<tr>
    			<th><?php echo JText::_('COM_JOBBOARD_PLUGIN'); ?></th>
    			<th><?php echo JText::_('COM_JOBBOARD_PLG_GRP'); ?></th>
    			<th></th>
    		</tr>
  		<?php foreach ($status->plugins as $plugin): ?>
      		<tr class="row<?php echo (++ $rows % 2); ?>">
      			<td class="key"><?php echo ucfirst($plugin['name']); ?></td>
      			<td class="key"><?php echo ucfirst($plugin['group']); ?></td>
      			<td>
                    <strong>
                        <?php echo ($plugin['result'])?JText::_('COM_JOBBOARD_REMOVED_TRUE'):JText::_('COM_JOBBOARD_REMOVED_FALSE'); ?>
                    </strong>
                </td>
      		</tr>
  		<?php endforeach; ?>
  		<?php endif; ?>
  	</tbody>
  </table>

