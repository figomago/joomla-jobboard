
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

var TandolinJobLister = new Class({
   Implements: [Options, Events],
    	options: {
         total : 0,
         currPage : 0,
         numPages : 0,
         minHeight: null,
         limitEl : 'limitstart',
         listLimitEl : 'limit',
         limitNext : false,
         baseUrl : null,
         pagiForm : null,
         listWrap : null,
         controlSection: null,
         fetchError : '',
         passengers: {}
    	},
    	initialize: function(options){
    	  this.setOptions(options);
          this.pagiForm = $(this.options.pagiForm);
          this.listWrap = $(this.options.listWrap);
          this.controlSection = $(this.options.controlSection);
    	  this.limitEl = this.pagiForm.elements[this.options.limitEl];
    	  this.listLimitEl = this.pagiForm.elements[this.options.listLimitEl];
          this.url = this.pagiForm.getAttribute('action');
          this.listEl = this.listWrap.getElement('ul');
          this.passengers = this.options.passengers;
          this.currPage = this.options.currPage;
          this.total = this.options.total;
          this.numPages = this.options.numPages;
          this.minHeight = this.options.minHeight;
    	  this.begin();
    	},
        begin: function() {
            this.limit = parseInt(this.listLimitEl.value);
            this.limitstart = parseInt(this.limitEl.value);
            this.setPagingBtns();
            this.setPaginInfoEls();
        },
        setPagingBtns: function(){
            this.fwdBtn = this.controlSection.getElement('.next').addEvent('click', function(){
                if(this.currPage <= this.numPages && this.limitNext !== true && this.numPages > 1) {
                    this.limitstart = parseInt(this.limitEl.value) + 1;
                    this.fetchJobs();
                    if(this.backBtn.hasClass('disabled') && this.limitstart > 0)
                        this.backBtn.removeClass('disabled');
                }
            }.bind(this));

            this.backBtn = this.controlSection.getElement('.prev');

            this.backBtn.addEvent('click', function(){
                this.limitstart = parseInt(this.limitEl.value) - 1;
                if(this.limitstart >= 0) {
                    this.fetchJobs();
                    this.limitNext =  false;

                    if(this.fwdBtn.hasClass('disabled'))  {
                        this.fwdBtn.removeClass('disabled');
                    }
                }
                if(!this.backBtn.hasClass('disabled') && this.limitstart == 0)  {
                    this.backBtn.addClass('disabled');
                }
            }.bind(this));
        },
        setPaginInfoEls: function(){
            this.pageInfo = this.controlSection.getElement('.pageInfo');
            this.pagerSpans = this.pageInfo.getElements('span');
            this.totalsSpan = this.controlSection.getElement('.totJobs').getElement('span');
            this.pageLoading = this.listWrap.getElement('.loading');
        },
        fetchJobs: function (){
            this.passengers.limitstart = this.limitstart;
            this.passengers.limit = this.limit;
            var _jsonRequest = new Request.JSON({url:this.url,
               link: 'ignore',
               noCache: true,
               data : this.passengers,

               onRequest: function() {
                   this.pageLoading.removeClass('dispnone');
                   this.listEl.set('html', '');
               }.bind(this),

               onProgress : function(){
                    //this.listEl.set('html', '')
               }.bind(this),

      		 onComplete: function(responseJSON){
                  this.pageLoading.addClass('dispnone');
                  if(!responseJSON){
                     var _li = new Element('li').injectInside(this.listEl);
                     var _span = new Element('span', {
                       text: this.options.fetchError
                       }
                     ).injectInside(_li);
                  } else {
                    if(responseJSON.data.length <= 0) {
                     var _li = new Element('li').injectInside(this.listEl);
                     var _span = new Element('span', {
                       text: 'No jobs found'
                       }
                     ).injectInside(_li);
                    } else{
                      this.limitEl.value = responseJSON.pagination.limitstart;
                      this.currPage = responseJSON.pagination.curr_page;

                      if(this.currPage == this.numPages){
                         if(!this.fwdBtn.hasClass('disabled'))
                            this.fwdBtn.addClass('disabled');
                         this.limitNext = true;
                      }

                      if(this.limitstart == 0 && this.numPages == 0) {
                        this.numPages = responseJSON.pagination.num_pages;
                      }
                                                   
                      if(this.numPages < 2)
                          this.fwdBtn.addClass('disabled');

                      for(job=0; job<responseJSON.data.length; job++) {
                        this.injectRow(responseJSON.data[job].id, responseJSON.data[job].job_title);
                      }

                      if(this.minHeight == null) {
                       this.minHeight = this.listEl.getSize().y;
                       if(this.minHeight > 100) {
                          this.listEl.setStyle('min-height', this.minHeight);
                        }
                      }

                     if(this.controlSection.hasClass('hidden')) {
                        this.controlSection.removeClass('hidden');
                     }

                     this.pagerSpans[0].set('text', responseJSON.pagination.curr_page);
                     this.pagerSpans[1].set('text', responseJSON.pagination.num_pages);
                     this.totalsSpan.set('text', responseJSON.pagination.total);
                   }
                 }
    		   }.bind(this)
    		 }).send();
          },
          injectRow: function(id, title) {
             var _link = this.pagiForm.elements['genurl'].value + id;
             var _li = new Element('li').injectInside(this.listEl);
             var _a = new Element('a', {href: _link, title: title, text: title}).injectInside(_li);
          }
 });
