
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

var TandolinHumanVerify = new Class({
      Implements: [Options, Events],
      options: {
          verifyImg : '.human_v',
          refreshLnk : 'hv_refresh',
          hideClass : 'jbhidden'
      },
      initialize:function(options){
         this.setOptions(options);
         this.verifyImgs = $$(this.options.verifyImg);
         this.refreshing;
         this.launch();
      },
      launch: function(){

         if(this.verifyImgs.length > 0) {
           this.refreshing = 0;
           this.verifyImgs.each(function(img){
               this.setRefresh(img);
           }.bind(this));
         }

      },
      setRefresh : function(img) {
         var _contextForm =  img.getParent('form');
         var _refreshLnk = _contextForm.getElement('a.'+this.options.refreshLnk+'');
         if(_refreshLnk) {
             var _imgSource = img.getAttribute('src');
             _refreshLnk.addEvent('click', function(e){
                e.stop();
                if(this.refreshing == 0) {
                  this.refreshing = 1;
                  _refreshLnk.addClass(this.options.hideClass);
                  this.changeImage(_imgSource + Math.random());
                  (function(){
                        _refreshLnk.removeClass(this.options.hideClass);
                        this.refreshing = 0;
                  }.bind(this)).delay(1500);
                }
             }.bind(this));
         }
      },
      changeImage : function(newSrc){
        this.verifyImgs.each(function(img){
             img.src = newSrc;
        });
      }
  });

  window.addEvent('domready', function() {
          var Tandolin = Tandolin || {};
          Tandolin.Captcha = Tandolin.Captcha || {};
          Tandolin.Captcha.Instance = new TandolinHumanVerify();
  });