<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

if(version_compare( JVERSION, '1.6.0', 'ge' )){
  require_once(JPATH_SITE.DS.'libraries'.DS.'joomla'.DS.'html'.DS.'html'.DS.'behavior.php');

  class JobBoardBehaviorHelper extends JHtmlBehavior
  {
  	public static function modal($selector = 'a.jobbrdmodal', $params = array())
  	{
  		$document = JFactory::getDocument();

  		// Load the necessary files if they haven't yet been loaded
  		if (!isset(self::$loaded[__METHOD__]))
  		{
  			// Include MooTools framework
  			self::framework();

  			// Load the javascript and css
  			JHtml::_('script', 'system/modal.js', true, true);
  			JHtml::_('stylesheet', 'system/modal.css', array(), true);
  		}

  		$sig = md5(serialize(array($selector, $params)));
        if (isset(self::$loaded[__METHOD__][$sig]))
  		{
  			return;
  		}

  		// Setup options object
  		$opt['ajaxOptions']		= (isset($params['ajaxOptions']) && (is_array($params['ajaxOptions']))) ? $params['ajaxOptions'] : null;
  		$opt['handler']			= (isset($params['handler'])) ? $params['handler'] : null;
  		$opt['fullScreen']		= (isset($params['fullScreen'])) ? (bool) $params['fullScreen'] : null;
  		$opt['parseSecure']		= (isset($params['parseSecure'])) ? (bool) $params['parseSecure'] : null;
  		$opt['closable']		= (isset($params['closable'])) ? (bool) $params['closable'] : null;
  		$opt['closeBtn']		= (isset($params['closeBtn'])) ? (bool) $params['closeBtn'] : null;
  		$opt['iframePreload']	= (isset($params['iframePreload'])) ? (bool) $params['iframePreload'] : null;
  		$opt['iframeOptions']	= (isset($params['iframeOptions']) && (is_array($params['iframeOptions']))) ? $params['iframeOptions'] : null;
  		$opt['size']			= (isset($params['size']) && (is_array($params['size']))) ? $params['size'] : null;
  		$opt['shadow']			= (isset($params['shadow'])) ? $params['shadow'] : null;
  		$opt['overlay']			= (isset($params['overlay'])) ? $params['overlay'] : null;
  		$opt['onOpen']			= (isset($params['onOpen'])) ? $params['onOpen'] : null;
  		$opt['onClose']			= (isset($params['onClose'])) ? $params['onClose'] : null;
  		$opt['onUpdate']		= (isset($params['onUpdate'])) ? $params['onUpdate'] : null;
  		$opt['onResize']		= (isset($params['onResize'])) ? $params['onResize'] : null;
  		$opt['onMove']			= (isset($params['onMove'])) ? $params['onMove'] : null;
  		$opt['onShow']			= (isset($params['onShow'])) ? $params['onShow'] : null;
  		$opt['onHide']			= (isset($params['onHide'])) ? $params['onHide'] : null;

  		$options = self::_getJSObject($opt);

        jimport('joomla.environment.browser');
          $browser =& JBrowser::getInstance();
          $_dom_listener = (is_int(strpos($browser->getBrowser(), 'msie')))? 'load' : 'domready';
  		// Attach modal behavior to document
  		$document
  			->addScriptDeclaration(
  			"
  		window.addEvent('".$_dom_listener."', function() {

  			SqueezeBox.initialize(" . $options . ");
              $$('" . $selector . "').each(function(el) {
              el.addEvent('click', function(e) {
                 e.stop();
                 SqueezeBox.fromElement(el);
              });
           });
  		});"
  		);

         // Set static array
  	    self::$loaded[__METHOD__][$sig] = true;
  		return;
  	}

    public static function tooltip($selector = '.hasTip', $params = array())
  	{
        $sig = md5(serialize(array($selector, $params)));
  		if (isset(self::$loaded[__METHOD__][$sig]))
  		{
  		  return;
  		}

  		// Include MooTools framework
  		self::framework(true);


  		// Setup options object
  		$opt['maxTitleChars']	= (isset($params['maxTitleChars']) && ($params['maxTitleChars'])) ? (int) $params['maxTitleChars'] : 50;
  		// offsets needs an array in the format: array('x'=>20, 'y'=>30)
  		$opt['offset']			= (isset($params['offset']) && (is_array($params['offset']))) ? $params['offset'] : null;
  		if (!isset($opt['offset']))
  		{
  			// Supporting offsets parameter which was working in mootools 1.2 (Joomla!1.5)
  			$opt['offset']		= (isset($params['offsets']) && (is_array($params['offsets']))) ? $params['offsets'] : null;
  		}
  		$opt['showDelay']		= (isset($params['showDelay'])) ? (int) $params['showDelay'] : null;
  		$opt['hideDelay']		= (isset($params['hideDelay'])) ? (int) $params['hideDelay'] : null;
  		$opt['className']		= (isset($params['className'])) ? $params['className'] : null;
  		$opt['fixed']			= (isset($params['fixed']) && ($params['fixed'])) ? true : false;
  		$opt['onShow']			= (isset($params['onShow'])) ? '\\' . $params['onShow'] : null;
  		$opt['onHide']			= (isset($params['onHide'])) ? '\\' . $params['onHide'] : null;

  		$options = self::_getJSObject($opt);

        jimport('joomla.environment.browser');
        $browser =& JBrowser::getInstance();
        $_dom_listener = (is_int(strpos($browser->getBrowser(), 'msie')))? 'load' : 'domready';

  		// Attach tooltips to document
  		JFactory::getDocument()->addScriptDeclaration(
  			"window.addEvent('".$_dom_listener."', function() {
  			$$('".$selector."').each(function(el) {
  				var title = el.get('title');
  				if (title) {
  					var parts = title.split('::', 2);
  					el.store('tip:title', parts[0]);
  					el.store('tip:text', parts[1]);
  				}
  			});
  			var JTooltips = new Tips($$('$selector'), $options);
  		});"
  		);

        // Set static array
        self::$loaded[__METHOD__][$sig] = true;

  		return;
  	}
  }
} else {
  class JobBoardBehaviorHelper extends JHtmlBehavior
  {
  	public function modal($selector = 'a.jobbrdmodal', $params = array())
  	{
          static $modals;
      	static $included;

  		$document = JFactory::getDocument();

    		// Load the necessary files if they haven't yet been loaded
    		if (!isset($included)) {

    			// Load the javascript and css
    			JHTML::script('modal.js');
    			JHTML::stylesheet('modal.css');

    			$included = true;
    		}

  		$sig = md5(serialize(array($selector, $params)));
          if (isset($modals[$sig]) && ($modals[$sig])) {
  			return;
  		}

  		// Setup options object
  		$opt['ajaxOptions']		= (isset($params['ajaxOptions']) && (is_array($params['ajaxOptions']))) ? $params['ajaxOptions'] : null;
  		$opt['handler']			= (isset($params['handler'])) ? $params['handler'] : null;
  		$opt['fullScreen']		= (isset($params['fullScreen'])) ? (bool) $params['fullScreen'] : null;
  		$opt['parseSecure']		= (isset($params['parseSecure'])) ? (bool) $params['parseSecure'] : null;
  		$opt['closable']		= (isset($params['closable'])) ? (bool) $params['closable'] : null;
  		$opt['closeBtn']		= (isset($params['closeBtn'])) ? (bool) $params['closeBtn'] : null;
  		$opt['iframePreload']	= (isset($params['iframePreload'])) ? (bool) $params['iframePreload'] : null;
  		$opt['iframeOptions']	= (isset($params['iframeOptions']) && (is_array($params['iframeOptions']))) ? $params['iframeOptions'] : null;
  		$opt['size']			= (isset($params['size']) && (is_array($params['size']))) ? $params['size'] : null;
  		$opt['shadow']			= (isset($params['shadow'])) ? $params['shadow'] : null;
  		$opt['overlay']			= (isset($params['overlay'])) ? $params['overlay'] : null;
  		$opt['onOpen']			= (isset($params['onOpen'])) ? $params['onOpen'] : null;
  		$opt['onClose']			= (isset($params['onClose'])) ? $params['onClose'] : null;
  		$opt['onUpdate']		= (isset($params['onUpdate'])) ? $params['onUpdate'] : null;
  		$opt['onResize']		= (isset($params['onResize'])) ? $params['onResize'] : null;
  		$opt['onMove']			= (isset($params['onMove'])) ? $params['onMove'] : null;
  		$opt['onShow']			= (isset($params['onShow'])) ? $params['onShow'] : null;
  		$opt['onHide']			= (isset($params['onHide'])) ? $params['onHide'] : null;

  		$options = self::_getJSObject($opt);

          jimport('joomla.environment.browser');
          $browser =& JBrowser::getInstance();
          $_dom_listener = (is_int(strpos($browser->getBrowser(), 'msie')))? 'load' : 'domready';
  		// Attach modal behavior to document
  		$document
  			->addScriptDeclaration(
  			"
  		window.addEvent('".$_dom_listener."', function() {

  			SqueezeBox.initialize(" . $options . ");
              $$('" . $selector . "').each(function(el) {
              el.addEvent('click', function(e) {
                 e.stop();
                 SqueezeBox.fromElement(el);
              });
           });
  		});"
  		);
  		// Set static array
  		$modals[$sig] = true;

  		return;
  	}

    	public function tooltip($selector = '.hasTip', $params = array())
    	{
          $sig = md5(serialize(array($selector, $params)));
          static $tips;

    		if (!isset($tips)) {
    			$tips = array();
    		}

  		// Include mootools framework
  		self::mootools();
          if (isset($tips[$sig]) && ($tips[$sig])) {
  			return;
  		}

    		// Setup options object
    		$opt['maxTitleChars']	= (isset($params['maxTitleChars']) && ($params['maxTitleChars'])) ? (int) $params['maxTitleChars'] : 50;
    		// offsets needs an array in the format: array('x'=>20, 'y'=>30)
    		$opt['offset']			= (isset($params['offset']) && (is_array($params['offset']))) ? $params['offset'] : null;
    		if (!isset($opt['offset']))
    		{
    			// Supporting offsets parameter which was working in mootools 1.2 (Joomla!1.5)
    			$opt['offset']		= (isset($params['offsets']) && (is_array($params['offsets']))) ? $params['offsets'] : null;
    		}
    		$opt['showDelay']		= (isset($params['showDelay'])) ? (int) $params['showDelay'] : null;
    		$opt['hideDelay']		= (isset($params['hideDelay'])) ? (int) $params['hideDelay'] : null;
    		$opt['className']		= (isset($params['className'])) ? $params['className'] : null;
    		$opt['fixed']			= (isset($params['fixed']) && ($params['fixed'])) ? true : false;
    		$opt['onShow']			= (isset($params['onShow'])) ? '\\' . $params['onShow'] : null;
    		$opt['onHide']			= (isset($params['onHide'])) ? '\\' . $params['onHide'] : null;

    		$options = self::_getJSObject($opt);

          jimport('joomla.environment.browser');
          $browser =& JBrowser::getInstance();
          $_dom_listener = (is_int(strpos($browser->getBrowser(), 'msie')))? 'load' : 'domready';

    		// Attach tooltips to document
    		JFactory::getDocument()->addScriptDeclaration(
    			"window.addEvent('".$_dom_listener."', function() {
    			$$('".$selector."').each(function(el) {
    				var title = el.get('title');
    				if (title) {
    					var parts = title.split('::', 2);  
    					el.store('tip:title', parts[0]);
    					el.store('tip:text', ' ');
    				}
    			});
    			var JTooltips = new Tips($$('$selector'), $options);
    		});"
    		);

          // Set static array
          $tips[$sig] = true;
    		return;
    	}
  }
}
?>