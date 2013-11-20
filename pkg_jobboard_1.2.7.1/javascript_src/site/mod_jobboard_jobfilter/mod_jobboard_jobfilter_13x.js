
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */


/**
	SlideItMoo v1.1.1 - Image slider
	(c) 2007-2008 Constantin Boiangiu <http://www.php-help.ro>
	MIT-style license.

	Changes from version 1.0
	- added continuous navigation
	- changed the navigation from Fx.Scroll to Fx.Morph
	- added new parameters: itemsSelector: pass the CSS class for divs
	- itemWidth: for elements with margin/padding pass their width including margin/padding

	Updates ( August 4'th 2009 )
	- added new parameter 'elemsSlide'. When this is set to a value lower that the actual number of elements in HTML, it will slide at once that number of elements when navigation clicked. Default: null

	Changes from version 1.1
	Updates ( Fri, 13'th Jan 2012 ) Figo Mago - figomago.wordpress.com  
	- fixed: slider goes into infinite loop when itemsVisible & elemsSlide both equal 1
	- added: ability to use external slider buttons
	- added: removed continuous content sliding (i.e no sliding backwards past the first slide and no sliding forwards past the last slide
	- modded: removed dependency on third outermost container
    - fixed: setContainersSize: tries to apply new width size to a string (itemContainer)
**/

var MooFlipper = new Class({

	Implements: [Options],
	options: {
		scrollItems: null,
		itemContainer: null,
		itemsVisible:5,
		elemsSlide: null,
		itemsSelector: null,
		itemWidth: null,
		transition: Fx.Transitions.Quart.easeOut,
		duration: 500,
		direction: 1,
		fwdBtn: null,
		backBtn: null,
		mouseWheelNav: false
	},

	initialize: function(options){
		this.setOptions(options);
        if(this.options.itemContainer !== null){
            this.itemContainer = $(this.options.itemContainer);
    		this.elements = this.itemContainer.getElements(this.options.itemsSelector);
    		this.totalElements = this.elements.length;
    		if( this.totalElements <= this.options.itemsVisible ) return;
    		this.elementWidth = this.options.itemWidth || this.elements[0].getSize().x;
    		this.currentElement = 0;
    		this.direction = this.options.direction;
    		this.begin();
        }
	},

	begin: function(){
		this.setContainersSize();
		this.inFx = new Fx.Morph(this.options.itemContainer, {
			transition: this.options.transition,
			duration: this.options.duration
		});

		this.options.fwdBtn.addEvent('click', this.slide.pass(1, this) );
		this.options.backBtn.addEvent('click', this.slide.pass(-1, this) );

		if( this.options.mouseWheelNav){
			this.itemContainer.addEvent('mousewheel', function(ev){
				ev.stop();
				this.slide(-ev.wheel);
			}.bind(this));
		}
	},

	setContainersSize: function(){
	    var _elWidth = this.options.itemsVisible * this.elementWidth;
        this.elements.each(function(el){
          el.setStyle('width', _elWidth);
        });
		this.itemContainer.set({
			styles:{
				'width': this.totalElements * (this.elementWidth + 10)
			}
		});
	},

	slide: function( direction ){

		if(this.started) return;

        switch(direction){
          case -1:
               if(this.currentElement == 0) return;
          break;
          case 1:
               if(this.currentElement == (this.totalElements - 1)) return;
          break;
          default:
          ;break;
        }

		this.direction = direction;
		var currentIndex = this.currentIndex();

		if( this.options.elemsSlide && this.options.elemsSlide>0 && this.endingElem == null ){
			this.endingElem = this.currentElement;
			for(var i = 0; i < this.options.elemsSlide; i++ ){
				this.endingElem += direction;
				if( this.endingElem >= this.totalElements ) this.endingElem = 0;
				if( this.endingElem < 0 ) this.endingElem = this.totalElements-1;
			}
		}

		if( this.direction == -1 ){
			this.rearange();
			this.itemContainer.setStyle('margin-left', -this.elementWidth);
		}

		this.started = true;
		this.inFx.start({
		    'opacity':[0.2,1],
			'margin-left': this.direction == 1 ? -this.elementWidth : 0
		}).chain( function(){
			this.rearange(true);
			if(this.options.elemsSlide){
				if( this.endingElem !== this.currentElement ) this.slide(this.direction);
				else this.endingElem=null;
			}
           if(this.direction == -1) {
             if(this.currentElement == 0) this.options.backBtn.addClass('disabled');
             if(this.options.fwdBtn.hasClass('disabled')) this.options.fwdBtn.removeClass('disabled');
           }
           if(this.direction == 1) {

               if(this.currentElement == (this.totalElements - 1)) {
                  this.options.fwdBtn.addClass('disabled');
                }
               if(this.totalElements > 1) {
                 if(this.options.backBtn.hasClass('disabled')) this.options.backBtn.removeClass('disabled');
               }
           }
		}.bind(this)  );
	},

	rearange: function( rerun ){

		if(rerun) this.started = false;
		if( rerun && this.direction == -1 ) {
			return;
		}
		this.currentElement = this.currentIndex( this.direction );

		this.itemContainer.setStyle('margin-left',0);

		if( this.currentElement == 1 && this.direction == 1 ){
			this.elements[0].injectAfter(this.elements[this.totalElements-1]);
			return;
		}
		if( (this.currentElement == 0 && this.direction ==1) || (this.direction==-1 && this.currentElement == this.totalElements-1) ){
			this.rearrangeElement( this.elements.getLast(), this.direction == 1 ? this.elements[this.totalElements-2] : this.elements[0]);
			return;
		}

		if( this.direction == 1 ){
			this.rearrangeElement( this.elements[this.currentElement-1], this.elements[this.currentElement-2]);
		}
		else{
			this.rearrangeElement( this.elements[this.currentElement], this.elements[this.currentElement+1]);
		}
	},

	rearrangeElement: function( element , indicator ){
		this.direction == 1 ? element.injectAfter(indicator) : element.injectBefore(indicator);
	},

	currentIndex: function(){
		var elemIndex = null;
		switch( this.direction ){
			case 1:
				elemIndex = this.currentElement >= this.totalElements-1 ? 0 : this.currentElement + this.direction;
			break;
			case -1:
				elemIndex = this.currentElement == 0 ? this.totalElements - 1 : this.currentElement + this.direction;
			break;
		}
		return elemIndex;
	}
});

var TandolinJobFilter = new Class({
   Implements: [Options, Events],
    	options: {
         texfieldDefaults : 'texfield_defaults',    // text field defaults element
         keywdsContent : 'keywds-content',
         catLoader : 'category-filter',
         toggleClassName : 'div.filterToggle',
         catFiltercontrols : 'catFiltercontrols',
         cbSections : [],
         effects : {
            reveal : []
         }
    	},
    	initialize: function(options){
    	  this.setOptions(options);
          this.tfDefaults = $(this.options.texfieldDefaults);
          this.tfDefaultsFirst = this.tfDefaults.getFirst('input');
          this.defltTxtFields = [];
          this.defltTxtFields['jobsearch'] = this.tfDefaultsFirst.value;
          this.defltTxtFields['keysrch'] = this.tfDefaultsFirst.getNext().value;
          this.tfDiv = $(this.options.keywdsContent).getFirst('div');
          this.tfActnsDiv = this.tfDiv.getLast('div');
          this.parentForm = this.tfDiv.getParent('form');
          this.tFields = this.tfDiv.getElements('input[type=text]');
          this.catLoader  = $(this.options.catLoader).getLast('span');
          this.tfReset    = this.tfActnsDiv.getLast('a');
          this.cbSections = this.options.cbSections;
          this.effects = this.options.effects;
    	  this.begin();
    	},

        begin: function() {
          this.instFlipper = null;
          this.setTxtFieldEvents(this.tFields, this.tfReset, this.catLoader);
          this.tfActnsDiv.getFirst('input').addEvent('click', function(e){
              e.stop();
              if(this.tFields[0].value.length < 1 && this.tFields[1].value.length < 1 )
                return;
              else {
                this.catLoader.removeClass('hidden');
                this.parentForm.submit();
              }
          }.bind(this));

          $$(this.options.toggleClassName).each(function(section){
              this.initFilterToggle(section)
          }.bind(this));
       },

       setTxtFieldEvents: function(fields,  trigger, loader){
            fields.each(function(f){
                f.addEvents({
                     focus: function() {
                        this.parseTxtFields(fields, trigger)
                     }.bind(this),
                     blur: function() {
                        this.parseTxtFields(fields, trigger)
                     }.bind(this),
                     keyup: function() {
                        this.parseTxtFields(fields, trigger)
                     }.bind(this)
                })
            }.bind(this));

            trigger.addEvent('click', function(e){
                  e.stop();
                  fields.each(function(txt){
                    txt.value = '';
                 });
                 loader.removeClass('hidden');
                 this.parentForm.submit();
            }.bind(this));
         },
        parseTxtFields : function(elArray, resetTrigger) {
              var _el1Value = elArray[0].value,
                  _el2Value = elArray[1].value,
                  _resetHidden = resetTrigger.hasClass('hidden');
              if(_el1Value != '' || _el2Value != '' && _resetHidden){
                 resetTrigger.removeClass('hidden');
              }
              if(_el1Value == '' && _el2Value == '' && !_resetHidden){
                 resetTrigger.addClass('hidden');
              }
        },
        initFilterToggle: function(section){
              var _sectionHeading = section.getFirst('div'),
                        _sectionContent = section.getNext('div'),
                        _contentUl = _sectionContent.getElement('ul');

              if(_contentUl) {
                var _lastLi = _contentUl.getLast('li'),
                        _filterSubmit = _lastLi.getLast();

                if(_filterSubmit.match('.chk_filter[type=submit]')) {
                  this.cbSections[_sectionContent.id] = 0;

                  if(_sectionHeading.hasClass('stateOpen')) {
                    var _sectionLoader = section.getLast('span'),
                            _clearTrigger = _lastLi.getElement('a');
                    this.cbSections[_sectionContent.id] = 1;
                    this.setSubmitEvent(_filterSubmit, _sectionLoader);
                    this.setCbClearEvent(_clearTrigger, _sectionLoader, _sectionContent);
                    this.setCbEvents(_sectionContent, _clearTrigger);
                  }
                } else if(_contentUl.hasClass('hrefSelects') ){
                    if(_sectionHeading.hasClass('stateOpen')) {
                       var _sectionLoader = section.getLast('span');

                       var _headHref = _sectionContent.getFirst('a');
                       var _headHrefSegments = _headHref.getAttribute('title').split('-');
                       _headHref.addEvent('click', function(e){
                           e.stop();
                           this.parentForm.getElement('input[name='+_headHrefSegments[0]+']').value = _headHrefSegments[1];
                           _sectionLoader.removeClass('hidden');
                           this.parentForm.submit();
                        }.bind(this));

                       var _sectionLinks = _contentUl.getElements('a[class=close]');
                       var _sectionSegName;

                       if(_sectionLinks.length > 0) {

                         var _linkSegments = _sectionLinks[0].getAttribute('title').split('-'),
                             _sectionSegName = _linkSegments[0];

                         _sectionLinks.each(function(link){
                           this.setSelectLinkEvents(link, _linkSegments, _sectionLoader);
                         }.bind(this));
                       }

                       _lastLi.getLast().addEvent('click', function(e){
                               e.stop();
                               this.parentForm.getElement('input[name='+_sectionSegName+']').value = 0;
                               _sectionLoader.removeClass('hidden');
                               this.parentForm.submit();
                       }.bind(this));
                    }
                } else if(_contentUl.hasClass('filter-catitem')) {

                            var _sectionLoader = section.getLast('span'),
                                _catDiv = _contentUl.getParent('div'),
                                _catLinks = _catDiv.getElements('a'),
                                _headHref = _sectionContent.getFirst('a');

                           if(_catLinks.length > 0) {
                             _catLinks.each(function(link){
                                  link.addEvent('click', function(){
                                    _sectionLoader.removeClass('hidden');
                                  });
                             });
                           }
                           _headHref.addEvent('click', function(){
                              _sectionLoader.removeClass('hidden');
                            });

                } else {
                     var _sectionLoader = section.getLast('span');
                     _lastLi.getLast().addEvent('click', function(){
                             _sectionLoader.removeClass('hidden');
                     }.bind(this));
                }
              }

              var _elIdSegments = section.id.split('-');
              section.addEvent('click', function(){
                 this.setToggle(_elIdSegments[0]);
              }.bind(this));
            },
            setToggle: function(elIdSegment) {
                var _toggleHead = $(elIdSegment + '-filter'),
                    _toggle = $(elIdSegment + '-toggle'),
                    _section = $(elIdSegment + '-content'),
                    _sectionUl = _section.getElement('ul');

                if(_sectionUl)
                  var _lastLi = _sectionUl.getLast('li');
                else _sectionUl = {};

                var _filterSubmit = _lastLi.getLast(),
                    _hasFilterSubmit = _filterSubmit.match('.chk_filter[type=submit]'),
                    _sectionLoader = _toggleHead.getLast('span'),
                    _isLinkSelector = _sectionUl.hasClass('hrefSelects');

            	if(_toggle.hasClass('stateOpen')) {
            		_toggle.removeClass('stateOpen');
            		_toggle.addClass('stateClosed');

                    if(_hasFilterSubmit == true) {
                        _filterSubmit.removeEvents();
                        _lastLi.getElements('a').removeEvents();
                    } else if(_sectionUl.hasClass('filter-catitem')) {
                       this.instFlipper = null;
                  }
            	}  else {
            		_toggle.addClass('stateOpen');
            		_toggle.removeClass('stateClosed');

                    if(_hasFilterSubmit == true) {
                      var _clearTrigger = _lastLi.getElement('a');
                      this.setSubmitEvent(_filterSubmit, _sectionLoader);
                      this.setCbClearEvent(_clearTrigger, _sectionLoader, _section);
                      this.setCbEvents(_section, _clearTrigger);
                    } else if(_isLinkSelector === true){
                         var _sectionLinks = _sectionUl.getElements('a[class=hrefSelect]');
                         var _sectionSegName;

                         if(_sectionLinks.length > 0) {
                           _sectionLinks.each(function(link){
                                var _linkSegments = link.getAttribute('title').split('-');
                                _sectionSegName = _linkSegments[0];
                                this.setSelectLinkEvents(link, _linkSegments, _sectionLoader);
                           }.bind(this));
                         }

                         _lastLi.getLast().addEvent('click', function(e){
                                 e.stop();
                                 this.parentForm.getElement('input[name='+_sectionSegName+']').value = 0;
                                  _sectionLoader.removeClass('hidden');
                                 this.parentForm.submit();
                         }.bind(this));

                    } else if(_sectionUl.hasClass('filter-catitem')) {
                          var _catDiv = _sectionUl.getParent('div');
                          if(_catDiv.hasClass('scrollItems')){

                      		  var _controlBox = $(this.options.catFiltercontrols),
                                  		_catsPrevBtn = _controlBox.getFirst(),
                                  		_catsNextBtn = _controlBox.getLast(),
                                        _itemWidth = parseInt(_section.getParent('div').getSize().x) - 12,
                                        _catLinks = _catDiv.getElements('a'),
                                        _headHref = _section.getFirst('a');

                        	  this.instFlipper = new MooFlipper({
                        			scrollItems: _section.id,
                        			itemContainer: elIdSegment+'-scrollItems',
                        			itemsVisible:1,
                        			elemsSlide:1,
                        			duration:450,
                            	    itemWidth: _itemWidth,
                        			itemsSelector: '.filter-catitem',
                            		fwdBtn: _catsNextBtn,
                            		backBtn: _catsPrevBtn
                        	 });

                             if(_catLinks.length > 0) {
                               _catLinks.each(function(link){
                                    link.addEvent('click', function(){
                                      _sectionLoader.removeClass('hidden');
                                    });
                               });
                             }
                             _headHref.addEvent('click', function(){
                                _sectionLoader.removeClass('hidden');
                              });
                         }
                    } else {
                        _lastLi.getLast().addEvent('click', function(){
                                _sectionLoader.removeClass('hidden');
                        });
                    }
            	}
            this.activateReveal(elIdSegment, _section);
          },
          activateReveal: function(elIdSegment, section) {
             this.effects.reveal[elIdSegment] = new Fx.Reveal(section).toggle();
          },
          setSelectLinkEvents: function(link, linkSegments, loader){
                link.addEvent('click', function(e){
                   e.stop();
                   this.parentForm.getElement('input[name='+linkSegments[0]+']').value = linkSegments[1];
                   loader.removeClass('hidden');
                   this.parentForm.submit();
                }.bind(this));
          },
          setSubmitEvent : function(button, loader){
              button.addEvent('click', function(click){
                   click.stop();
                   loader.removeClass('hidden');
                   button.removeEvents();
                   button.setAttribute('disabled', 'disabled');
                   this.parentForm.submit();
              }.bind(this));
          },
          setCbClearEvent : function(trigger, loader, section){
              trigger.addEvent('click', function(e){
                   e.stop();
                   this.clearCboxes(section);
                   this.parentForm.getElement('input[name=cb_reset]').value = 1;
                   if(this.cbSections[section.id] == 1){
                      loader.removeClass('hidden');
                      this.parentForm.submit();
                   } else trigger.addClass('hidden');
              }.bind(this));
          },

          clearCboxes : function(section)  {
             var _jCBs = section.getElements('input[type=checkbox]');
             if(!_jCBs)
            	 return;
             var _countCbs = _jCBs.length;
             if(!_countCbs)
            		_jCBs.checked = 0;
          	 else {
          		for(var cb = 0; cb < _countCbs; cb++)  {
          			_jCBs[cb].checked = 0;
          			_jCBs[cb].removeAttribute('checked');
               }
             }
         },

         hasActiveCbFilters : function(section)  {
             var _jCBs = section.getElements('input[type=checkbox]');
             var _cBsActive = false;
             if(!_jCBs)
            	 return;
             var _countCbs = _jCBs.length;
             if(!_countCbs) {
            		_jCBs.checked = 0;
                  _cBsActive = true;
             } else {
          		for(var cb = 0; cb < _countCbs; cb++)
          			if(_jCBs[cb].checked == 1)
                        _cBsActive = true;
              }
           return _cBsActive;
         },

         setCbEvents : function(section, trg){
               var _checkboxes = section.getElements('input[type=checkbox]');
               _checkboxes.each(function(cb){
                 this.runCbLoaderEvt(cb, section, trg);
              }.bind(this));
         },
         runCbLoaderEvt: function(cb, section, trg){
             cb.addEvent('click', function(){
                  if(this.hasActiveCbFilters(section) == true){
                    trg.removeClass('hidden');
                  } else
                    trg.addClass('hidden');
              }.bind(this));
          }
   });

  var Tandolin = Tandolin || {};
  Tandolin.moduleFilter = Tandolin.moduleFilter || {};

  window.addEvent('domready', function(){
       Tandolin.moduleFilter.JobFilter = new TandolinJobFilter();
  });