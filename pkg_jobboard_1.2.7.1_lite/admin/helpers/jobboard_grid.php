<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

require_once(JPATH_SITE.DS.'libraries'.DS.'joomla'.DS.'html'.DS.'html'.DS.'grid.php');
class JobBoardGridHelper extends JHtmlGrid
{

	public static function boolean($i, $value, $taskOn = null, $taskOff = null)
	{
		// Load the behavior.
		self::behavior();

		// Build the title.
		$title = ($value) ? JText::_('JYES') : JText::_('JNO');
		$title .= '::' . JText::_('JGLOBAL_CLICK_TO_TOGGLE_STATE');

		// Build the <a> tag.
		$bool = ($value) ? 'true' : 'false';
		$task = ($value) ? $taskOff : $taskOn;
		$toggle = (!$task) ? false : true;

		if ($toggle)
		{
			$html = '<a class="grid_' . $bool . ' hasTip" title="' . $title . '" rel="{id:\'cb' . $i . '\', task:\'' . $task
				. '\'}" href="#toggle"></a>';
		}
		else
		{
			$html = '<a class="grid_' . $bool . '"></a>';
		}

		return $html;
	}
	/**
	 * Method to build the behavior script and add it to the document head.
	 *
	 * @return  void
	 *
	 * @since   11.1
	 */
	public static function behavior()
	{
		static $loaded;

		if (!$loaded)
		{
			// Build the behavior script.
			$js = '
		window.addEvent(\'domready\', function(){
			actions = $$(\'a.move_up\');
			actions.combine($$(\'a.move_down\'));
			actions.combine($$(\'a.grid_true\'));
			actions.combine($$(\'a.grid_false\'));
			actions.combine($$(\'a.grid_trash\'));
			actions.each(function(a){
				a.addEvent(\'click\', function(){
					args = JSON.decode(this.rel);
					listItemTask(args.id, args.task);
				});
			});
			$$(\'input.check-all-toggle\').each(function(el){
				el.addEvent(\'click\', function(){
					if (el.checked) {
						document.id(this.form).getElements(\'input[type=checkbox]\').each(function(i){
							i.checked = true;
						})
					}
					else {
						document.id(this.form).getElements(\'input[type=checkbox]\').each(function(i){
							i.checked = false;
						})
					}
				});
			});
		});';

			// Add the behavior to the document head.
			$document = JFactory::getDocument();
			$document->addScriptDeclaration($js);

			$loaded = true;
		}
	}
}

?>