
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

var TandolinHumanVerify=new Class({Implements:[Options,Events],options:{verifyImg:".human_v",refreshLnk:"hv_refresh",hideClass:"jbhidden"},initialize:function(a){this.setOptions(a);this.verifyImgs=$$(this.options.verifyImg);this.refreshing;this.launch()},launch:function(){if(this.verifyImgs.length>0){this.refreshing=0;this.verifyImgs.each(function(a){this.setRefresh(a)}.bind(this))}},setRefresh:function(a){var c=a.getParent("form");var b=c.getElement("a."+this.options.refreshLnk+"");if(b){var d=a.getAttribute("src");b.addEvent("click",function(f){f.stop();if(this.refreshing==0){this.refreshing=1;b.addClass(this.options.hideClass);this.changeImage(d+Math.random());(function(){b.removeClass(this.options.hideClass);this.refreshing=0}.bind(this)).delay(1500)}}.bind(this))}},changeImage:function(a){this.verifyImgs.each(function(b){b.src=a})}});window.addEvent("domready",function(){var a=a||{};a.Captcha=a.Captcha||{};a.Captcha.Instance=new TandolinHumanVerify()});