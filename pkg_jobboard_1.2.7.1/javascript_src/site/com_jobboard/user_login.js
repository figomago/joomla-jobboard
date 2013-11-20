
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

var TandolinLoginBox = new Class({
      Implements: [Options, Events],
      options: {
          loginBox : 'loginPopup',
          loginBoxTogglerClass : 'active',
          loginBoxHideTrigger : 'a#pLoginCancel',
          displayNoneClass : 'jbdispnone',
    	  loginWrapper: 'loginWrapper'
      },
      initialize:function(options){
         this.setOptions(options);
         this.loginWrapper = $(this.options.loginWrapper);
         this.loginBox = $(this.options.loginBox);
         this.launch();
      },
      launch: function(){

         if(this.loginBox) {
           this.loginBoxToggler = this.loginWrapper.getFirst('a');

           this.loginBoxToggler.addEvent('click', function(e){
               e.stop();
               this.loginBoxToggler.toggleClass(this.options.loginBoxTogglerClass);
               this.loginBox.toggleClass(this.options.displayNoneClass);
           }.bind(this));

           $(document.body).addEvent('click', function(e) {
              if(this.loginWrapper && !e.target || !$(e.target).getParents().contains(this.loginWrapper)) {
                 this.hideLoginBox();
              }
            }.bind(this));

           this.loginBox.getElement(this.options.loginBoxHideTrigger).addEvent('click', function(e){
               e.stop();
               this.hideLoginBox();
           }.bind(this));
         }
      },
      hideLoginBox: function(){
         this.loginBox.addClass(this.options.displayNoneClass);
         this.loginBoxToggler.removeClass(this.options.loginBoxTogglerClass);
      }
});

  window.addEvent('domready', function() {
          var Tandolin = Tandolin || {};
          Tandolin.User = Tandolin.User || {};
          Tandolin.User.LoginBox = new TandolinLoginBox();
  });