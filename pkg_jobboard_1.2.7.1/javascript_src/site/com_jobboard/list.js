
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

var Tandolin = Tandolin || {};
Tandolin.JobListView = Tandolin.JobListView || {};

var TandolinClassJobList = new Class({
   Implements: [Options, Events],
    	options: {
    	   loginWrapper: 'loginWrapper',
    	   formTop: 'category_list',
           resetKeywds : 'reset_keywds',
           listForm : 'category_list',
           titleBox : 'jobsearch',
           keywdBox : 'keysrch',
           locnBox : 'locsrch',
           orderSelct : 'order_selct',
           sortSelct : 'sort_selct',
           showAllTrigger : 'jall',
           tableViewSwitch : 'tableView',
           listViewSwitch : 'listView',
           srchInfo : 'srch_info',
           keywdInfo : 'keywd_info',
           advSrchTrigger : 'advsrch',
           loading : 'loadr',
           srchRadius: 'srchRadius',
           titleString : '',
           keywdString : '',
           locnString : '',
           closedState : 'closed',
           openState : 'open',
           hiddenName : 'hidel',
           displayNone : 'jbdispnone',
           inputOverlay: 'inputovr',
           submits: 'input.filterSub',
           initFiltersState: [],
           filterTriggers : [],
           subUnsolEl: 'topSubmitCV',
           unsolLnk: '',
           effects : {
              windowScroll : {},
              advFiltersSlide : {}
           },
           advSearch: 'advsrch',
           advSearchCont: 'jbrdadvsrch',
           txtLoading : '',
           txtBasicSrch: '',
           txtAdvSrch: '',
           jobTypeCbs: 'jtCboxes',
           careerLvlCbs: 'clCboxes',
           eduLvlCbs: 'elCboxes',
           jobTypeCbsClearTrigger: 'clearJtypeFilters',
           careerLvlCbsClearTrigger: 'clearClevelFilters',
           eduLvlCbsClearTrigger: 'clearElevelFilters',
           cbResetSectionFlag: 'cb_reset',
           cbResetAllTrigger: 'reset_advfilters'
    	},
    	initialize: function(options){
          this.setOptions(options);
          this.resetKeywds = $(this.options.resetKeywds);
          this.listForm = $(this.options.listForm);
          this.titleBox = $(this.options.titleBox);
          this.keywdBox = $(this.options.keywdBox);
          this.locnBox = $(this.options.locnBox);
          this.orderSelct = $(this.options.orderSelct);
          this.sortSelct = $(this.options.sortSelct);
          this.showAllTrigger = $(this.options.showAllTrigger);
          this.srchInfo = $(this.options.srchInfo);
          this.keywdInfo = $(this.options.keywdInfo);
          this.advSrchTrigger = $(this.options.advSrchTrigger);
          this.loading = $(this.options.loading);
          this.srchRadius = $(this.options.srchRadius);
          this.submits = $$(this.options.submits);
          this.initFiltersState = this.options.initFiltersState;
          this.filterTriggers = this.options.filterTriggers;
          this.effects = this.options.effects;
          this.advSearch = $(this.options.advSearch);
          this.cbResetAllTrigger = $(this.options.cbResetAllTrigger);
          this.subUnsolEl = $(this.options.subUnsolEl);
          this.tableViewSwitch = $(this.options.tableViewSwitch);
          this.listViewSwitch = $(this.options.listViewSwitch);
          this.launch();
        },
        launch : function(){
          if(this.locnBox) {
            this.checkValue(this.locnBox, this.options.locnString);
            this.setEvents(this.locnBox, this.options.locnString);
          }

          this.checkValue(this.titleBox, this.options.titleString);
          this.checkValue(this.keywdBox, this.options.keywdString);

          this.setEvents(this.titleBox, this.options.titleString);
          this.setEvents(this.keywdBox, this.options.keywdString);

          if(this.orderSelct)       { this.setcnLdrs(this.orderSelct); }
          if(this.sortSelct)        { this.setcnLdrs(this.sortSelct); }
          if(this.showAllTrigger)   { this.setckLdrs(this.showAllTrigger); }
          this.setResetTriggerEvt(this.resetKeywds);

          this.submits.each(function(btn){
             this.setGoEvt(btn)
          }.bind(this));

          this.setFx();
          this.setAdvSearchEvts(this.advSearch);

          this.jobTypeFilters = $(this.options.jobTypeCbs).getElements('input[type=checkbox]');
          this.careerLevelFilters = $(this.options.careerLvlCbs).getElements('input[type=checkbox]');
          this.eduLevelFilters = $(this.options.eduLvlCbs).getElements('input[type=checkbox]');

          this.filterTriggers['filter_job_type'] = $(this.options.jobTypeCbsClearTrigger);
          this.setCbClearEvt('filter_job_type');
          this.jobTypeFilters.each(function(cb){
              this.setCbFilterEvts(cb, 'filter_job_type');
          }.bind(this));

          this.filterTriggers['filter_careerlvl'] = $(this.options.careerLvlCbsClearTrigger);
          this.setCbClearEvt('filter_careerlvl');
          this.careerLevelFilters.each(function(cb){
              this.setCbFilterEvts(cb, 'filter_careerlvl');
          }.bind(this));

          this.filterTriggers['filter_edulevel'] = $(this.options.eduLvlCbsClearTrigger);
          this.setCbClearEvt('filter_edulevel');
          this.eduLevelFilters.each(function(cb){
              this.setCbFilterEvts(cb, 'filter_edulevel');
          }.bind(this));

          this.initFiltersState['filter_job_type'] = this.checkCbFilters('filter_job_type[]');
          this.initFiltersState['filter_careerlvl'] = this.checkCbFilters('filter_careerlvl[]');
          this.initFiltersState['filter_edulevel'] = this.checkCbFilters('filter_edulevel[]');

          if(this.cbResetAllTrigger)
            this.cbResetAllTrigger.addEvent('click', function(e){
               e.stop();
               this.listForm.elements['country_id'].value = 0;
               this.listForm.elements['daterange'].value = 0;
               this.clearAllCboxes();
            }.bind(this));

             if(this.subUnsolEl) {
                this.subUnsolEl.addEvent('click', function(e){
                    e.stop();
                    window.location.href = this.options.unsolLnk;
                }.bind(this));
             }

             this.isListLayout = (this.listForm.elements['layout'].value == 'list')? true : false;

             if(this.tableViewSwitch)  {
               this.setckLdrs(this.tableViewSwitch);
               this.tableViewSwitch.addEvent('click', function(e){
                  e.stop();
                  this.switchLayout();
               }.bind(this));
             }

             if(this.listViewSwitch)   {
               this.setckLdrs(this.listViewSwitch);
               this.listViewSwitch.addEvent('click', function(e){
                  e.stop();
                  this.switchLayout();
               }.bind(this));
             }

        },
        setCbFilterEvts: function(cb, filterType){
            cb.addEvent('click', function(){
                if(this.checkCbFilters(filterType+'[]') == true){
                  this.filterTriggers[filterType].removeClass('hidel');
                } else
                      this.filterTriggers[filterType].addClass('hidel');
            }.bind(this));
        },
        setCbClearEvt: function(filterType){
            this.filterTriggers[filterType].addEvent('click', function(e){
             e.stop();
             this.clearCboxes(filterType+'[]');
          }.bind(this));
        },
        checkValue : function (el, str) {
           if(el.value == '' || el.value.length === 0) {
              el.value = str;
              if(el.id == this.options.locnBox) {
                if(this.srchRadius) this.srchRadius.setAttribute('disabled', 'disabled');
              }
            }
            if (el.value.indexOf('(') === 0) {
                 el.addClass(this.options.inputOverlay);
            } else el.removeClass(this.options.inputOverlay);
        },
        setEvents: function(el, str) {
             el.addEvents({
               focus: function(){
                  el.removeClass(this.options.inputOverlay);
                  if(el.value.indexOf('(') === 0) el.value = '';
                  if(el.id == this.options.locnBox) {
                    this.srchRadius.removeAttribute('disabled');
                  }
                 }.bind(this)
             ,
             keyup: function(e){
                if(el.value == '' || el.value.length === 0) {
                  el.value = str;
                  el.blur();
                  el.addClass(this.options.inputOverlay);
                  if(el.id == this.options.locnBox)
                      this.srchRadius.setAttribute('disabled', 'disabled');
                  }
              }.bind(this)
            ,
            blur: function(){
                if(el.value.length < 1){
                    el.value = str;
                    if(el.id == this.options.locnBox) {
                       this.srchRadius.setAttribute('disabled', 'disabled');
                    }
                }
                this.checkValue(el, str);
            }.bind(this)
          });
        },
        setcnLdrs : function(el) {
          el.addEvent('change', function(){
                this.loading.removeClass(this.options.hiddenName);
            }.bind(this));
        },
        setckLdrs : function(el) {
          el.addEvent('click', function(){
                this.loading.removeClass(this.options.hiddenName);
            }.bind(this));
        },
        setResetTriggerEvt :  function(resetKeywds){
          resetKeywds.addEvent('click', function(e){
               e.stop();

               this.launchSubmitFormEvts();
               this.titleBox.value = '';
               this.keywdBox.value = '';

               if(this.locnBox) {
                  this.locnBox.value = '';
                  this.checkValue(this.locnBox, this.options.locnString);
               }

               this.checkValue(this.titleBox, this.options.titleString);
               this.checkValue(this.keywdBox, this.options.keywdString);
               if(this.isListLayout === true)
                this.sortSelct.value = 'date';
               this.listForm.submit();

            }.bind(this));
        },
        setGoEvt: function(btn) {
          btn.addEvent('click', function(){
                this.launchSubmitFormEvts();
              }.bind(this))
        },
        launchSubmitFormEvts: function() {
               this.srchInfo.addClass(this.options.hiddenName);
               if(this.keywdInfo) this.keywdInfo.addClass(this.options.hiddenName);
               this.advSrchTrigger.addClass(this.options.hiddenName);
               this.loading.removeClass(this.options.hiddenName);
        },
        setFx: function(){
           this.effects.windowScroll = new Fx.Scroll(window, {
               transition : Fx.Transitions.Pow.easeInOut
             });

           this.effects.advFiltersSlide = new Fx.Slide(this.options.advSearchCont, {
                  duration: 380
              }).slideOut();
        },
        setAdvSearchEvts: function(el){
            el.addEvent('click', function(e){
                e.stop();
                var _advSearchCont = $(this.options.advSearchCont);
                var _panelClosed = el.hasClass(this.options.closedState)? true : false;
                if(_panelClosed === true) {
                    _advSearchCont.removeClass(this.options.displayNone);
                    (function(){this.effects.windowScroll.toElement(this.options.formTop, 'y'); }.bind(this)).delay(200);
                    this.effects.advFiltersSlide.slideIn().chain(function(){
                        el.text = this.options.txtBasicSrch;
                        el.removeClass(this.options.closedState);
                        el.addClass(this.options.openState);
                    }.bind(this));
                } else if(_panelClosed !== true) {
                  (function(){this.effects.windowScroll.toElement(this.options.loginWrapper, 'y'); }.bind(this)).delay(200);
                   // this.effects.windowScroll.toElement(this.options.loginWrapper, 'y');
                   this.effects. advFiltersSlide.slideOut().chain(function(){
                        el.text = this.options.txtAdvSrch;
                        el.removeClass(this.options.openState);
                        el.addClass(this.options.closedState);
                        _advSearchCont.addClass(this.options.displayNone);
                    }.bind(this));
                }
           }.bind(this));
       },
       checkCbFilters : function(formElName)  {
           var _jCBs = this.listForm.elements[formElName];

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
       pollCboxes : function(cbArray){
             if(!cbArray)
            	 return;
             var _countCbs = cbArray.length;
             if(!_countCbs)
            		cbArray.checked = 0;
          	else {
          		for(var cb = 0; cb < _countCbs; cb++)  {
          			cbArray[cb].checked = 0;
          			cbArray[cb].removeAttribute('checked');
                  }
              }
       },
       clearCboxes : function(formElName)  {
           var _jCBs = this.listForm.elements[formElName];
           if(!_jCBs)
          	 return;
           this.pollCboxes(_jCBs);

           var _cbContext = formElName.substring(0, formElName.length - 2);

           if(this.initFiltersState[_cbContext] == true) {
             this.srchInfo.addClass(this.options.hiddenName);
             if(this.keywdInfo) this.keywdInfo.addClass(this.options.hiddenName);
             this.effects.windowScroll.toElement(this.options.loginWrapper, 'y');
             this.effects.advFiltersSlide.slideOut().chain(function(){
                this.advSearch.text = this.options.txtAdvSrch;
                this.advSearch.removeClass(this.options.openState);
                this.advSearch.addClass(this.options.closedState);
                this.advSearch.addClass(this.options.hiddenName);
                $(this.options.advSearchCont).addClass(this.options.displayNone);
                this.loading.removeClass(this.options.hiddenName);
                this.submits[0].setAttribute('disabled', 'disabled');
                this.submits[1].setAttribute('disabled', 'disabled');
                this.listForm.elements[this.options.cbResetSectionFlag].value = 1;
                this.listForm.submit();
             }.bind(this));
          } else {
            this.filterTriggers[_cbContext].addClass(this.options.hiddenName);
          }
       },
       clearAllCboxes : function()  {
           var _cbAreas = ['filter_job_type[]', 'filter_careerlvl[]', 'filter_edulevel[]'];
           _cbAreas.each(function(cbArea){
             var _jCBs = this.listForm.elements[cbArea];
             if(!_jCBs)
            	 return;
             this.pollCboxes(_jCBs);

           }.bind(this));
           this.loading.removeClass(this.options.hiddenName);
           this.listForm.elements[this.options.cbResetSectionFlag].value = 1;
           this.listForm.submit();
       },
       switchLayout: function(){
         this.listForm.elements['switch_layout'].value = 1;
         this.listForm.submit();
       }

   });

  window.addEvent('domready', function() {
          var Tandolin = Tandolin || {};
          Tandolin.JobListView = Tandolin.JobListView || {};
          Tandolin.JobListView.List = new TandolinClassJobList({
             titleString : titleString,
             keywdString : keywdString,
             locnString : locnString,
             txtLoading : txtLoading,
             unsolLnk: uslnk,
             txtBasicSrch: txtBasicSrch,
             txtAdvSrch: txtAdvSrch
          });
  });
