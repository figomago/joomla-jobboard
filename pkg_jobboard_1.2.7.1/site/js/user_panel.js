
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

window.addEvent("domready",function(){if(typeof(jbVars)!="object"||jbVars.userTab!=2){var a=$("usericon");var b=a.getElement("p[class=caption]");if(profilePicPresent==1){a.addEvent("mouseover",function(c){c=new Event(c).stop();b.setStyle("visibility","visible")});a.addEvent("mouseout",function(c){c=new Event(c).stop();b.setStyle("visibility","hidden")})}else{b.setStyle("visibility","visible");b.getElement("a").set("text","Upload")}}});