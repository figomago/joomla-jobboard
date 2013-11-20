<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');
class Com_JobboardInstallerScript {

	function install($parent) {
		//echo '<p>'. JText::_('COM_JOBBOARD_CUSTOM_INSTALL_SCRIPT') . '</p>';
	}

	function uninstall($parent) {
		//echo '<p>'. JText::_('COM_JOBBOARD_CUSTOM_UNINSTALL_SCRIPT') .'</p>';
	}

	function update($parent) {
		//echo '<p>'. JText::_('COM_JOBBOARD_CUSTOM_UPDATE_SCRIPT') .'</p>';
	}

	function preflight($type, $parent) {
		//echo '<p>'. JText::sprintf('COM_JOBBOARD_CUSTOM_PREFLIGHT', $type) .'</p>';
	}

	function postflight($type, $parent) {
		//echo '<p>'. JText::sprintf('COM_JOBBOARD_CUSTOM_POSTFLIGHT', $type) .'</p>';
		// An example of setting a redirect to a new location after the install is completed
		//$parent->getParent()->set('redirect_url', 'index.php?option=com_jobboard&view=config');
	}
}
?>