
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

var Tandolin=Tandolin||{};Tandolin.job=Tandolin.job||{};Tandolin.JobBoardMap=new Class({Implements:[Options,Events],options:{lat:"",lng:"",subRegion:"",trigger:"",lblPresentCoords:"Present coordinates",title:""},initialize:function(a,c,b){this.setOptions(b);this.mapContainer=$(a);this.formName=c;this.lat=this.options.lat;this.lng=this.options.lng;this.marker="";this.map=null;this.geocoder=null;this.custAddress=false;this.hasLocation=false;this.addressData=[];this.currentAddress;this.currLocnString="";if(typeof(google)!=="undefined"){this.initScript()}},mapOptions:{zoom:1,center:"",disableDoubleClickZoom:true,draggableCursor:"pointer",draggingCursor:"move",mapTypeId:null},initScript:function(){this.loadMap()},loadMap:function(){this.hasLocation=false;this.mapOptions.center=new google.maps.LatLng(0,0);this.mapOptions.mapTypeId=google.maps.MapTypeId.ROADMAP;this.geocoder=new google.maps.Geocoder();if(this.lat!=""&&this.lng!=""){this.mapOptions.center=new google.maps.LatLng(this.lat,this.lng);this.hasLocation=true;document.forms[this.formName].elements.geo_latitude.value=this.mapOptions.center.lat();document.forms[this.formName].elements.geo_longitude.value=this.mapOptions.center.lng();this.reverseGeocode(this.mapOptions.center,true)}else{this.hasLocation=false;this.mapOptions.center=new google.maps.LatLng(0,0)}this.map=new google.maps.Map(this.mapContainer,this.mapOptions);this.marker="";if(!this.hasLocation){this.map.setZoom(1)}else{this.map.setZoom(9)}google.maps.event.addListener(this.map,"click",function(a){$(this.options.trigger).removeClass("green");this.reverseGeocode(a.latLng,true)}.bind(this))},reverseGeocode:function(b,a){this.geocoder=new google.maps.Geocoder();if(this.geocoder){this.geocoder.geocode({latLng:b},function(i,h){if(h==google.maps.GeocoderStatus.OK){var l;var d=[];this.addressData=[];var e="";var c="";var f=i[0]["address_components"];for(var g in f){var k=f[g]["types"];if(typeOf(k)=="array"&&(k!=="null"||k!=="undefined")){if(k.contains("political")){if(k.contains("locality")){this.addressData.push(f[g]["long_name"]);this.currLocnString=f[g]["long_name"]}if(k.contains("administrative_area_level_1")){d.province1=f[g]["long_name"]}else{if(!k.contains("administrative_area_level_1")&&k.contains("administrative_area_level_2")){d.province2=f[g]["long_name"]}else{if(!k.contains("administrative_area_level_1")&&!k.contains("administrative_area_level_2")&&k.contains("administrative_area_level_3")){d.province3=f[g]["long_name"]}}}}if(k.contains("postal_code")){this.addressData.push(f[g]["long_name"])}if(k.contains("country")){if(f[g]["long_name"]=="United Kingdom"){f[g]["long_name"]="UK"}this.addressData.push(f[g]["long_name"])}}}if("province1" in d){this.addressData.push(d.province1);c=d.province1}else{if("province2" in d){this.addressData.push(d.province2);c=d.province2}else{if("province3" in d){this.addressData.push(d.province3);c=d.province3}}}l=this.addressData.join(", ");if(this.currLocnString!=""){document.forms[this.formName].elements.city.value=this.currLocnString}document.forms[this.formName].elements.geo_state_province.value=c;infoSpans[0].set("html",this.options.lblPresentCoords+": ");infoSpans[1].set("html",l);if(a==true){this.placeMarker(b)}$(this.options.trigger).addClass("green")}}.bind(this))}},placeMarker:function(a){if(this.marker==""){this.marker=new google.maps.Marker({position:this.mapOptions.center,map:this.map,title:this.options.title})}this.marker.setPosition(a);this.map.setCenter(a);if((a.lat()!="")&&(a.lng()!="")){document.forms[this.formName].elements.geo_latitude.value=a.lat();this.lat=a.lat();document.forms[this.formName].elements.geo_longitude.value=a.lng();this.lng=a.lng()}},geocode:function(b,a){this.geocoder=new google.maps.Geocoder();if(this.geocoder){this.geocoder.geocode({address:b},function(d,c){if(c==google.maps.GeocoderStatus.OK){this.placeMarker(d[0].geometry.location);this.reverseGeocode(d[0].geometry.location,false);if(a==true){if(this.map.getZoom()!=4){this.map.setZoom(4)}}else{if(this.map.getZoom()!=9){this.map.setZoom(9)}}if(!this.hasLocation){this.hasLocation=true}}}.bind(this))}},refreshMap:function(){var b=document.forms[this.formName].elements.city;var a=document.forms[this.formName].elements.country;if(b.value.length>0){this.custAddress=true;this.currentAddress=b.value+",+"+a.getElement("option[value="+a.value+"]").text;if((b.value.toLowerCase()!=this.currLocnString.toLowerCase())||this.currLocnString.length<1){this.geocode(this.currentAddress)}}else{if(typeOf(this.marker)=="object"){this.marker.setMap(null);this.marker="";this.map.setZoom(1);document.forms[this.formName].elements.geo_latitude.value="";document.forms[this.formName].elements.geo_longitude.value="";document.forms[this.formName].elements.geo_state_province.value=""}}}});