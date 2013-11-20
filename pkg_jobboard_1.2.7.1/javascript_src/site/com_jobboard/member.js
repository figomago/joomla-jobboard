
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

var TandolinMemberView = new Class({
      Implements: [Options, Events],
      options: {
          currView : null,
          loginPanel : 'loginpanel',
          regLink : 'signup',
          regPanel : 'regpanel',
          textFieldClass : 'textfield',
          hiddenClass : 'hidden'
      },
      initialize:function(options){
         this.setOptions(options);
         this.currPanelName = this.options.currView == 'login'? this.options.loginPanel : this.options.regPanel;
         this.currPanel = $(this.currPanelName);
         this.regPanel = $(this.options.regPanel);
         this.regLink = $(this.options.regLink);
         this.launch();
      },
      launch: function(){

         if(this.options.currView) {
           if(this.regLink && this.regPanel && (this.currPanel.id == this.options.loginPanel)) {
            this.regLink.addEvent('click', function(e){
                e.stop();
                this.clearEvents(this.currPanel);
                this.currPanel.addClass(this.options.hiddenClass);
                this.currPanel = this.regPanel;
                this.currPanel.removeClass(this.options.hiddenClass);
            }.bind(this));
          }
        }

      },
      clearEvents : function(panel) {
         panel.getElements('input[class="'+this.optionstextFieldClass+'"]').each(function(i){
             i.removeEvents();
         });
      }
   });