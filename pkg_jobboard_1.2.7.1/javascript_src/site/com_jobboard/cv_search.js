
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

var TandolinCvSearch = new Class({
      Implements: [Options, Events], 
      options: {
          searchBox : 'cvsrcharea',
          searchFormName : 'cvsrchForm',
          fieldsResetName : 'f_reset',
          refineCont : 'cvr-refine',
          toggleClass : 'cv-ftoggle',
          toggleOpenClass : 'open',
          loaderClassName : 'loadr',
          resultsArea : 'cvprofs',
          hiddenClassName : 'hidden',
          edLinkClassName : 'edfilter',
          singularClassName : 'singular',
          firstContName : 'job_title',
    	  toggleTransition: Fx.Transitions.Quart.easeOut,
    	  toggleDuration: 500
      },
      initialize:function(options){
         this.setOptions(options);
         this.searchBox = $(this.options.searchBox);
         this.refineCont = $(this.options.refineCont);
         this.searchForm = $(this.options.searchFormName);
         this.searchInputs = this.searchBox.getElements('input[type=text]');
         this.resetTrigger = this.searchBox.getElement('a');
         this.loading = this.searchBox.getFirst('span');
         this.resultsArea = $(this.options.resultsArea);
         this.submitBtn = this.searchBox.getElement('input[type=submit]');
         this.sectionEffects = [];
         this.sectionsY = [];
         this.launch();
      },
      launch: function(){

         this.initialStateEmpty = this.inputsEmpty();
         if(!this.initialStateEmpty) {
             this.showReset();
         }

         this.searchInputs.each(function(input){
            this.setInputListeners(input);
         }.bind(this));

         this.submitBtn.addEvent('click', function(e){
              e.stop();
              if(!this.inputsEmpty()){
                this.submitBtn.removeEvents().setAttribute('diabled', 'diabled');
                this.fadeView();
                this.loading.removeClass(this.options.hiddenClassName);
                this.searchForm.submit();
              }
         }.bind(this));

         if(this.refineCont){
            this.refineCont.getElements('span[class='+this.options.toggleClass+']').each(function(section){
              section.addClass(this.options.toggleOpenClass);
              var _sectionSegs = section.id.split('-');
              this.initRefineToggle(_sectionSegs[0]);
              section.addEvent('click', function(e){
                  e.stop();
                  var _isOpen = section.hasClass(this.options.toggleOpenClass);
                  if(_isOpen){
                      this.toggleFilter(_sectionSegs[0], 'c')
                  } else if(!_isOpen){
                      this.toggleFilter(_sectionSegs[0], 'o')
                  }
                  section.toggleClass(this.options.toggleOpenClass);
              }.bind(this))
            }.bind(this))
         }
      },
      inputsEmpty: function(){
         var _isEmpty = true;
         this.searchInputs.each(function(field){
              if(field.value.length != 0)
                _isEmpty = false;
         });
         return _isEmpty;
      },
      setInputListeners: function(field){
        field.addEvents({
             'focus' : function(){
                 this.checkInpStatus(field);
             }.bind(this)
             ,
             'keyup' : function(){
                 this.checkInpStatus(field);
             }.bind(this)
             ,
             'blur' : function(){
                 this.checkInpStatus(field);
             }.bind(this)
        });
      },
      checkInpStatus : function(inp){
         var _inputsEmpty = this.inputsEmpty();
         if(!_inputsEmpty || inp.value.length > 0) this.showReset();
         if(_inputsEmpty) this.hideReset();
      },
      showReset: function(){
         this.resetTrigger.removeClass(this.options.hiddenClassName);
         this.setTrigger();
      },
      hideReset: function(){
         this.resetTrigger.addClass(this.options.hiddenClassName).removeEvents();
      },
      setTrigger: function(){
         this.resetTrigger.addEvent('click', function(e){
            e.stop();
            this.clearInputs();
            this.hideReset();
            if(!this.initialStateEmpty) {
              this.fadeView();
              this.loading.removeClass(this.options.hiddenClassName);
              this.searchForm.elements[this.options.fieldsResetName].value = 1;
              this.searchForm.submit();
            }
         }.bind(this));
      },
      clearInputs : function(){
         this.searchInputs.each(function(field){
              field.value = '';
         });
      },
      fadeView : function(){
          this.hideReset();
          this.searchInputs.each(function(field){
                field.setStyle('opacity', 0.3);
          });
          this.resultsArea.setStyle('opacity', 0.5);
      },
      initRefineToggle: function(sectionName){
          var _section = $(sectionName + '-cont');
          this.sectionsY[sectionName] = _section.getSize().y;

          this.sectionEffects[sectionName] = new Fx.Morph(sectionName+'-cont',{
    			transition: this.options.toggleTransition,
    			duration: this.options.toggleDuration
          });

          var _sectionLinks = _section.getElements('a');
          if(_sectionLinks.length != 0) {
             _sectionLinks.each(function(link){
                link.addEvent('click', function(e){
                   e.stop();
                   var _isEdLink = link.hasClass(this.options.edLinkClassName);
                   var _isClsLink = link.hasClass(sectionName);
                   if(!_isEdLink && !_isClsLink)  {
                       this.clearInputs();
                       this.fadeView();
                       this.loading.removeClass(this.options.hiddenClassName);
                       this.searchForm.elements[sectionName].value = '"'+link.get('text')+'"';
                       this.searchForm.submit();
                   } else {
                      if(_isEdLink){
                         var _edSegments = link.getAttribute('title').split('-');
                         this.fadeView();
                         this.searchForm.elements[sectionName].value = _edSegments[1];
                         this.searchForm.submit();
                      }
                      if(_isClsLink){
                         this.fadeView();
                         var _inGoing = link.getParent('li').getElement('span').get('text');
                         if(link.hasClass(this.options.singularClassName)) {
                           var _jFiltrCont = this.refineCont.getFirst('ul'),
                               _thisContSegs = _jFiltrCont.id.split('-'),
                               _firstLnk = _jFiltrCont.getFirst('li').getFirst('a');
                           if(_firstLnk) {
                              _inGoing = _firstLnk.get('text');
                              console.log( _inGoing);
                               this.searchForm.elements[sectionName].value = '';
                               this.searchForm.elements[_thisContSegs[0]].value = _inGoing;
                           } else {
                               this.searchForm.elements[sectionName].value = _inGoing;
                           }
                         }
                         this.searchForm.elements[sectionName].value = _inGoing;
                         this.searchForm.submit();
                      }
                   }
                }.bind(this))
             }.bind(this))
          }
      },
      toggleFilter: function(name, type)  {
        switch(type)  {
           case 'c' :
             this.sectionEffects[name].start({
                    'height': [this.sectionsY[name],0],
                    'opacity':[1,0]

              });
           break;
           case 'o' :
             this.sectionEffects[name].start({
                    'height':[0, this.sectionsY[name]],
        		    'opacity':[0,1]
              });
            break;
            default:
            ;break;
        }
      }
});

  window.addEvent('domready', function() {
          var Tandolin = Tandolin || {};
          Tandolin.CvSearch = Tandolin.CvSearch || {};
          Tandolin.CvSearch.Instance = new TandolinCvSearch();
  });