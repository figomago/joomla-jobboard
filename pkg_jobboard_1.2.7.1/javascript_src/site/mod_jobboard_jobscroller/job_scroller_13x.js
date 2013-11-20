
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

var Scroller = new Class({
	setOptions: function(options) {
		this.options = Object.extend({
			speed: 600,
			delay: 5000,
            transition: Fx.Transitions.Quint.easeIn,
			onComplete: Class.empty,
			onStart: Class.empty
		}, options || {});
	},
	initialize: function(el,options){
		this.setOptions(options);
		this.el = $(el);
		this.items = this.el.getElements('li');
		var w = this.el.getSize().x;
		var h = 0;
    	this.items.each(function(li,index) {
    		h += li.getSize().y;
    	});

		this.el.setStyles({
			position: 'relative',
			top: 10,
			left: 0,
			width: w,
			height: h
		});

		this.fx = new Fx.Morph(this.el,{
		    duration: this.options.speed,
            transition:  this.options.transition,
            onStart: function() {
    			var i = (this.current==0)?this.items.length:this.current;
                this.items[i-1].fade('out');
            }.bind(this),
            onComplete:function() {
    			var i = (this.current==0)?this.items.length:this.current;
    			this.items[i-1].injectInside(this.el);
                this.items.each(function(itm){
                  itm.fade('in');
                })
    			this.el.setStyles({
    				left:0,
    				top:0
    			});
    		}.bind(this)
        });
		this.current = 0;
		this.next();
	},

	pause: function() {
	    $clear(mytimer);
	    mytimer = null;
	},
	resume: function() {
	    if (mytimer == null) {
	    this.next();
	    }
	},
	next: function() {
		this.current++;
		if (this.current >= this.items.length) this.current = 0;
		var pos = this.items[this.current];
		this.fx.start({
			top: -pos.offsetTop,
			left: -pos.offsetLeft
		});
		mytimer = this.next.bind(this).delay(this.options.delay+this.options.speed);
	}
});

var mytimer = null;

window.addEvent('domready', function() {
   var dscroll = new Scroller('ScrollerVertical', {
      speed : 600, delay : 5000});
   if($('stop_scroll')) {
        $('stop_scroll').addEvent('click', function() {
    		$('start_scroll_cont').style.display='block';
    		$('stop_scroll_cont').style.display='none';
    		dscroll.pause();
    	});
    }
   if($('start_scroll')) {
        $('start_scroll').addEvent('click', function() {
    		$('stop_scroll_cont').style.display='block';
    		$('start_scroll_cont').style.display='none';
    		dscroll.resume();
    	});
    }
});