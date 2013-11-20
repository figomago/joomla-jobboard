
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

var Tandolin = Tandolin || {}; Tandolin.job = Tandolin.job || {};

Tandolin.JobBoardMap = new Class({
    Implements: [Options, Events],
    options: {
           lat: '',
           lng: '',
           subRegion: '',
           trigger : '',
           lblPresentCoords : 'Present coordinates',
           title : ''
    },

    initialize: function(container, formName, options){
        this.setOptions(options);
        this.mapContainer = $(container);
        this.formName = formName;
        this.lat = this.options.lat;
        this.lng = this.options.lng;
        this.marker = '';
        this.map = null;
        this.geocoder = null;
        this.custAddress = false;
        this.hasLocation = false;
        this.addressData = [];
        this.currentAddress;
        this.currLocnString = '';
        if(typeof(google) !== 'undefined')
            this.initScript();
    },

    mapOptions: {
        zoom: 1,
        center: '',
        disableDoubleClickZoom: true,
        draggableCursor: 'pointer',
        draggingCursor: 'move',
        mapTypeId: null
    },

    initScript: function(){
    	this.loadMap()
    },

    loadMap: function(){
      this.hasLocation = false;
      this.mapOptions.center = new google.maps.LatLng(0.0, 0.0);
      this.mapOptions.mapTypeId = google.maps.MapTypeId.ROADMAP ;
      this.geocoder = new google.maps.Geocoder();

      if(this.lat != '' && this.lng != '') {
        this.mapOptions.center = new google.maps.LatLng(this.lat, this.lng);
        this.hasLocation = true;
        document.forms[this.formName].elements['geo_latitude'].value =  this.mapOptions.center.lat();
        document.forms[this.formName].elements['geo_longitude'].value =  this.mapOptions.center.lng();
        this.reverseGeocode(this.mapOptions.center, true);
      } else {
        this.hasLocation = false;
        this.mapOptions.center = new google.maps.LatLng(0.0, 0.0);
      }

      this.map = new google.maps.Map(this.mapContainer, this.mapOptions);
      this.marker = '';

  	  if(!this.hasLocation) this.map.setZoom(1); else this.map.setZoom(9);

      google.maps.event.addListener(this.map, 'click', function(e) {
            $(this.options.trigger).removeClass('green');
    		this.reverseGeocode(e.latLng, true);
    	}.bind(this));
    },

    reverseGeocode: function(coordinates, markMap) {
		this.geocoder = new google.maps.Geocoder();
	    if (this.geocoder) {
			this.geocoder.geocode({"latLng": coordinates}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
			     	var address;
				    var province = []; this.addressData = []; var city = ''; var state = '';
				    var address_components = results[0]['address_components'];

                        for ( var j in address_components ) {
    				    	var types = address_components[j]['types'];

                            if(typeOf(types) == 'array' && (types !== 'null' || types !== 'undefined')) {
                              if (types.contains('political') ) {

                                if ( types.contains('locality')) {
                                  this.addressData.push(address_components[j]['long_name']);
                                  this.currLocnString = address_components[j]['long_name'];
                                }

                                if ( types.contains('administrative_area_level_1')) {
                                  province['province1'] = address_components[j]['long_name'];
                                } else {
                                    if(!types.contains('administrative_area_level_1') && types.contains('administrative_area_level_2')){
                                       province['province2'] = address_components[j]['long_name'];
                                    } else {
                                      if(!types.contains('administrative_area_level_1') && !types.contains('administrative_area_level_2') && types.contains('administrative_area_level_3')) {
                                          province['province3'] = address_components[j]['long_name'];
                                       }
                                      }
                                }
                              }
                                if ( types.contains('postal_code')) {
                                  this.addressData.push(address_components[j]['long_name']);
                                }

                                if ( types.contains('country')) {
                                  if(address_components[j]['long_name'] == 'United Kingdom') address_components[j]['long_name'] = 'UK';
                                  this.addressData.push(address_components[j]['long_name']);
                                }
                            }
				    }

                        if('province1'in province) {
                           this.addressData.push(province['province1']);
                           state = province['province1'];
                        } else {
                             if('province2'in province) {
                                 this.addressData.push(province['province2']);
                                 state = province['province2'];
                              } else {
                                    if('province3'in province) {
                                       this.addressData.push(province['province3']);
                                       state = province['province3'];
                                    }
                              }
                        }

                 address = this.addressData.join(", ");

		         if(this.currLocnString != '') {
                    document.forms[this.formName].elements['city'].value = this.currLocnString;
		         }

		         document.forms[this.formName].elements['geo_state_province'].value = state;

                 infoSpans[0].set('html', this.options.lblPresentCoords+': ');
                 infoSpans[1].set('html', address);

                 if(markMap == true) {
                   this.placeMarker(coordinates);
                 }

                 $(this.options.trigger).addClass('green');

			}
		  }.bind(this));
	    }
	},

    placeMarker: function (coordinates) {
		if (this.marker=='') {
			this.marker = new google.maps.Marker({
				position: this.mapOptions.center,
				map: this.map,
				title: this.options.title
			});
		}
		this.marker.setPosition(coordinates);
		this.map.setCenter(coordinates);
		if((coordinates.lat() != '' ) && (coordinates.lng() != '')) {
  				document.forms[this.formName].elements['geo_latitude'].value =  coordinates.lat();
                this.lat = coordinates.lat();
  				document.forms[this.formName].elements['geo_longitude'].value =  coordinates.lng();
                this.lng = coordinates.lng();
		}
	},

    geocode: function(address, zoomOut) {
		this.geocoder = new google.maps.Geocoder();
	    if (this.geocoder) {
			this.geocoder.geocode({"address": address}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
                    this.placeMarker(results[0].geometry.location);
					this.reverseGeocode(results[0].geometry.location, false);

                    if(zoomOut == true) {
                      if(this.map.getZoom() != 4) this.map.setZoom(4);
                    } else if(this.map.getZoom() != 9) this.map.setZoom(9);

					if(!this.hasLocation) this.hasLocation = true;
				}
			}.bind(this));
		}
	},

    refreshMap: function(){
          var currCity = document.forms[this.formName].elements['city'];
          var currCountry = document.forms[this.formName].elements['country'];
          if(currCity.value.length > 0 ) {
          	this.custAddress = true;
          	this.currentAddress = currCity.value+',+'+currCountry.getElement('option[value='+currCountry.value+']').text;
            if((currCity.value.toLowerCase() != this.currLocnString.toLowerCase()) || this.currLocnString.length < 1)
          	    this.geocode(this.currentAddress);
    	  } else if(typeOf(this.marker) == 'object'){
          			this.marker.setMap(null);
          			this.marker = '';
                    this.map.setZoom(1);
      				document.forms[this.formName].elements['geo_latitude'].value =  '';
      				document.forms[this.formName].elements['geo_longitude'].value =  '';
      				document.forms[this.formName].elements['geo_state_province'].value =  '';
  		       }
  }
});
