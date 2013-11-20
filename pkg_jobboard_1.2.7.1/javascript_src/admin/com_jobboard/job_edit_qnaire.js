
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

var Tandolin = Tandolin || {};

Tandolin.JobQuestionnaire = new Class({
    Implements: [Options, Events],
    options: {
           previewBase: 'index.php?option=com_jobboard&view=questionnaire&id=',
           previewFormat: 'component',
           qId: 'questionnaire_id',
           previewLnk : 'q-preview',
           hiddenClass : 'hidden',
           nullUrl: '#'
    },

    initialize: function(options){
        this.setOptions(options);
        this.qSelector = $(this.options.qId);
        this.selQ = this.qSelector.value;
        this.previewLnk = $(this.options.previewLnk);
        this.targetUrl = null;
        this.initScript();
    },

    initScript: function(){
        this.setEvListener();
    	this.setQlink();
    },

    setQlink: function(){
      this.selQ = this.qSelector.value;

       if(parseInt(this.selQ) > 0) {
         this.targetUrl = this.options.previewBase+this.selQ+'&tmpl='+this.options.previewFormat;
         this.previewLnk.setAttribute('href', this.targetUrl);
         if(this.previewLnk.hasClass(this.options.hiddenClass))
           this.previewLnk.removeClass(this.options.hiddenClass);

       } else {
         this.targetUrl = this.options.nullUrl;
         this.previewLnk.setAttribute('href', this.targetUrl);
         if(!this.previewLnk.hasClass(this.options.hiddenClass))
           this.previewLnk.addClass(this.options.hiddenClass);
       }
    },

    setEvListener : function(){
      this.qSelector.addEvent('change', function(){
        this.setQlink()
      }.bind(this))
    }

});
