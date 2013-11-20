
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

var TandolinPublcJob = new Class({
      Implements: [Options, Events],
      options: {
          jobData : {
             'lat' : '',
             'lng' : '',
             'title' : ''
          },
          effects : {
               mapSlide: {},
               windowScroll: {},
               windowLocation: null
          },
          mapSlide: 'jobpost_map',
          mapOptions : {
              'zoom': 6,
              'center': null,
              'mapTypeId': null
          },
          mapDiv: 'jobpostMap',
          vMapTriggers: 'a.map_trigger',
          mapCls : 'map_cls',
          mapSlideEndEl : 'view_map',
          winScrollEndEl : 'sb_lastrow'
      },
      initialize:function(options){
         this.setOptions(options);
         this.jobData = this.options.jobData;
         this.effects = this.options.effects;
         this.mapOptions = this.options.mapOptions;
         this.mapDiv = $(this.options.mapDiv);
         this.mapDivWrapper = this.mapDiv.getParent('div');
         this.mapCls = $(this.options.mapCls);
         this.vMapTriggers = $$(this.options.vMapTriggers);
         this.launch();
      },
      launch: function(){
         this.setEffects();
         this.loadMap();
         this.setMapEvents();

      },
      setEffects: function(){
         this.effects.mapSlide = new Fx.Slide(this.mapDivWrapper.id, {
            duration: 380
          }).slideOut();

         this.effects.windowScroll = new Fx.Scroll(window,{
            offset: { x: 0, y: 10},
            transition: Fx.Transitions.Quad.easeInOut
         });
      },
      loadMap: function(){
         this.center = new google.maps.LatLng(this.jobData['lat'], this.jobData['lng']);
         this.mapOptions.mapTypeId = google.maps.MapTypeId.ROADMAP;
         this.map = new google.maps.Map(this.mapDiv, this.mapOptions);
         this.marker = new google.maps.Marker({position: this.center, map: this.map, title: this.jobData['title']});
         this.showMap(6, 10);
      },
      setMapEvents: function(){
        this.mapCls.addEvent('click', function(e) {
              e.stop();
              this.mapCls.addClass('jbhidden');
              this.map.setZoom(6);
              (function(){this.closeMap()}.bind(this)).delay(500);
          }.bind(this));

          this.vMapTriggers.each(function(trigger){
              this.setOpenTrigger(trigger);
          }.bind(this));
      },
      setOpenTrigger: function(mTrigger){
         mTrigger.addEvent('click', function(t){
          t.stop();
          this.mapDivWrapper.removeClass('jbdispnone');

          this.effects.windowLocation = document.getCoordinates();
          this.effects.windowScroll.toElement(this.options.mapSlideEndEl, 'y').chain(function(){
              this.effects.mapSlide.slideIn().chain(function(){
                  this.showMap(12, 300);
                  this.vMapTriggers.each(function(trig){
                      this.setTriggerState(trig);
                  }.bind(this));
              }.bind(this));
            }.bind(this));
        }.bind(this));
      },
      setTriggerState: function(trig){
         trig.addClass('jbhidden');
         this.mapCls.removeClass('jbhidden');
      },
      showMap : function(zoom, after){
        (function(){ this.map.setZoom(zoom);}.bind(this)).delay(after);
        google.maps.event.trigger(this.map, 'resize');
        this.marker.setPosition(this.center);
        this.map.setCenter(this.center);
      },
      closeMap: function (){
       this.effects.mapSlide.slideOut().chain(function(){
           this.effects.windowScroll.toElement(this.options.winScrollEndEl, 'y');
              this.vMapTriggers.each(function(trig){
                  trig.removeClass('jbhidden');
                }.bind(this));
        }.bind(this));
     }
});