
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

var TandolinJobboardHome = new Class({
      Implements: [Options, Events],
      options: {
          triggerContainer : 'snapshot-tabs',
          containerSegName : 'content',
          containerClassName : 'subcontent',
          displayNoneClassName : 'jbdispnone',
          selectedClassName : 'selected',
          activeClassName : 'active'
      },
      initialize:function(options){
         this.setOptions(options);
         this.triggerContainer = $(this.options.triggerContainer);
         this.triggerLis = this.triggerContainer.getElements('li');
         this.triggerLinks = this.triggerContainer.getElements('a');
         this.launch();
      },
      launch: function(){

         this.contDivs = $$('div.'+this.options.containerClassName);

         this.contDivs.each(function(div){
            div.set('morph', {duration:500, transition:'quint:out', wait:false });
         }.bind(this));

         this.triggerLinks.each(function(link){
           link.addEvent('click', function(e){
                e.stop();
                if(!link.hasClass(this.options.activeClassName)) {

                  this.triggerLis.each(function(li){
                     li.removeClass(this.options.activeClassName)
                  }.bind(this));

                 this.contDivs.each(function(container){
                      container.morph({opacity: 0});
                      container.addClass(this.options.displayNoneClassName);
                  }.bind(this));

                  var _linkSegments = link.getParent('li').id.split('-');
                  var _currContId = this.options.containerSegName+'-'+_linkSegments[1];
                  var _currCont = $(_currContId);
                  var _currLi = link.getParent('li');

                  this.showContainer(_currCont, _currLi);

                }
           }.bind(this));
         }.bind(this));
      },
      showContainer: function(cont, li){
          cont.setStyle('opacity', 0);
          cont.removeClass(this.options.displayNoneClassName);
          this.showIn(cont, li);
      },
      showIn: function(el, li){
          el.morph({opacity: 1});
          li.addClass(this.options.activeClassName);
      }
});

  window.addEvent('domready', function() {
          var Tandolin = Tandolin || {};
          Tandolin.HomeView = Tandolin.HomeView || {};
          Tandolin.HomeView.Home = new TandolinJobboardHome();
  });