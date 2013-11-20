
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

(function(){var c=Array.prototype.slice;Function.from=function(d){return(typeOf(d)=="function")?d:function(){return d}};Array.from=function(d){if(d==null){return[]}return(a.isEnumerable(d)&&typeof d!="string")?(typeof(d)=="array")?d:c.call(d):[d]};Number.from=function(e){var d=parseFloat(e);return isFinite(d)?d:null};String.from=function(d){return d+""};Function.implement({hide:function(){this.$hidden=true;return this},protect:function(){this.$protected=true;return this}});var a=this.Type=function(g,f){if(g){var e=g.toLowerCase();var d=function(h){return(typeOf(h)==e)};a["is"+g]=d;if(f!=null){f.prototype.$family=(function(){return e}).hide();f.type=d}}if(f==null){return null}f.extend(this);f.$constructor=a;f.prototype.$constructor=f;return f};var b=Object.prototype.toString;a.isEnumerable=function(d){return(d!=null&&typeof d.length=="number"&&b.call(d)!="[object Function]")}})();