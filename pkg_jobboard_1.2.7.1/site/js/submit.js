
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

window.addEvent("domready",function(){$("submit_application").addEvent("click",function(a){a.stop();this.disabled=1;$("loadr").removeClass("hidel");this.setStyles({background:"#ccc",color:"#ccc","border-color":"#ddd"});this.form.submit()})});