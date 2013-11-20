
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

var lastEdNum; var lastEmplNum;var winScroll;
var edRowContent = '' ;
var emplRowContent = '';
var skillrow = [];
var yearOptions = '';
var monthOptions = '';
var countryOptions = '';
var edLevelOptions = '';
var startYearOptions = '<option selected="selected" value="-1">--</option>\n';
var endYearOptions;
var edYearOptions = '<option selected="selected" value="-1">--</option>\n';
Element.extend({
  fade: function(from, to, duration, remove) {
      new Fx.Style(this, "opacity", {
          duration: duration,
          onComplete: function() {
              if (remove)
                  this.element.remove();
          }
      }).start(from, to);
  }
});
window.addEvent('domready', function() {
         winScroll = new Fx.Scroll(this, {
         wait:false,
         duration: 300,
         transition: Fx.Transitions.Quart.easeInOut
       });
       endYearOptions = '<option selected="selected" value="-1">--</option>\n<option value="9999">'+jbVars.txtPresent+'</option>\n';
       for (var country in jbVars.countries){
         if(jbVars.countries[country].id != null || jbVars.countries[country].id != undefined) {
             if(jbVars.countries[country].id == jbVars.defaultCountry) {
              countryOptions += '<option value="'+ jbVars.countries[country].id +'" selected="selected">'+ jbVars.countries[country].name +'</option>\n';
             } else {
              countryOptions += '<option value="'+ jbVars.countries[country].id +'">'+ jbVars.countries[country].name +'</option>\n';
             }
           }
         }
       for (var edLevel in jbVars.edLevels){
         if(jbVars.edLevels[edLevel].id != null || jbVars.edLevels[edLevel].id != undefined) {
            edLevelOptions += '<option value="'+ jbVars.edLevels[edLevel].id +'">'+ jbVars.edLevels[edLevel].name +'</option>\n';
          }
       }

       for(m=0; m<12; m++) {
           var currMonth = jbVars.months[m] ;
           var paddedMo = m < 9? '0'+(m+1) : m+1;
           monthOptions += '<option value="'+ paddedMo +'">'+ currMonth +'</option>\n';
       }

       for(y=0; y<41; y++) {
           var currYear = jbVars.currYear - y;
           edYearOptions += '<option value="'+ currYear +'">'+ currYear +'</option>\n';
           startYearOptions += '<option value="'+ currYear +'">'+ currYear +'</option>\n';
           endYearOptions += '<option value="'+ currYear +'">'+ currYear +'</option>\n';
       }

    if($('newed')) $('newed').addEvent('click', function(e){
         e = new Event(e).stop();
         if(jbVars.qualNum == (jbVars.maxQuals - 1)) $('newed').setStyle('visibility', 'hidden');
         if(jbVars.qualNum > (jbVars.maxQuals - 1)) return;
         lastEdNum =  jbVars.qualNum;
         jbVars.qualNum += 1;
         var edRemvBtn = '';
         if(jbVars.qualNum > 1)  edRemvBtn = '<a class="btn">'+jbVars.txtRemove+'</a>';
         edRowContent = '<span class="qualheading">'+jbVars.txtEdu+' '+jbVars.qualNum+edRemvBtn+'</span>'+
            '<label>'+jbVars.txtType+'</label>'+
            '<select name="edtype['+jbVars.qualNum+']">'+
                edLevelOptions +
        	' </select>'+
            '<label>'+jbVars.txtQualname+'</label>'+
            '<input type="text" name="qual_name['+jbVars.qualNum+']" size="40" value="" class="clrright" />'+
            '<label>'+jbVars.txtSchoolname+'</label>'+
            '<input type="text" name="school_name['+jbVars.qualNum+']" size="40" value="" class="clrright" />'+
            '<label>'+jbVars.txtCountry+'</label>'+
            '<select name="edu_country['+jbVars.qualNum+']">'+
                countryOptions +
        	'</select>'+
            '<label>'+jbVars.txtCity+'</label>'+
            '<input type="text" name="ed_location['+jbVars.qualNum+']" size="40"  value="" />'+
            '<label>'+jbVars.txtQualyr+'</label>'+
            '<select class="infoRight clrright" name="ed_year['+jbVars.qualNum+']">'+
              edYearOptions+
        	'</select>';
         var edTarg = 'edRow['+lastEdNum+']';
         var eduEls = $$('div.qualrow');
         var edTargEl = eduEls[eduEls.length -1];
         var targElPos = edTargEl.getPosition().y;
         var scrollIncr = edTargEl.getSize().y;

         var newEdRow = new Element('div', {'id': 'edRow-'+jbVars.qualNum,'name': 'edRow['+jbVars.qualNum+']', 'class': 'qualrow'}).set('html', edRowContent).injectAfter(edTargEl).set('opacity', 0.3);

         winScroll.start(0, targElPos+scrollIncr).chain(
                function() {
                  newEdRow.set('opacity', 1);
                }
             );
         var edSpan = newEdRow.getElement('span[class="qualheading"]');
         edSpan.getElement('a').addEvents({
                  mousedown : function(){
                      e = new Event(e).stop();
                      this.getParent('div').set('opacity', 0.6);
                  },
                  mouseup : function(e){
                      e = new Event(e).stop();
                      this.getParent('div').set('opacity', 1);
                  },
                  click : function(e){
                      e = new Event(e).stop();
                      this.getParent('div').destroy();
                      jbVars.qualNum--;
                      winScroll.toElement(edTargEl);
                      checkNumRows(true, 'ed');
                  }
                });

         checkNumRows(false, 'ed');

    }); // newEd click handler

   if($('newemp')) $('newemp').addEvent('click', function(e){
       e = new Event(e).stop();
       if(jbVars.emplNum == (jbVars.maxEmployers - 1)) $('newemp').setStyle('visibility', 'hidden');
       if(jbVars.emplNum > (jbVars.maxEmployers - 1)) return;
       lastEmplNum =  jbVars.emplNum;
       jbVars.emplNum += 1;
       var emplRemvBtn = '';
       if(jbVars.emplNum > 0)  emplRemvBtn = '<a class="btn">'+jbVars.txtRemove+'</a>';
       emplRowContent = '<span class="emplheading">'+jbVars.txtEmployer+' '+jbVars.emplNum+emplRemvBtn+'</span>'+
        '<label>'+jbVars.txtCompany+'</label>'+
        '<input size="40" name="company['+jbVars.emplNum+']" type="text" value="" />'+
        '<label>'+jbVars.txtJobtitle+'</label>'+
        '<input size="40" name="job_title['+jbVars.emplNum+']" type="text" value="" />'+
        '<label>'+jbVars.txtCountry+'</label>'+
        '<select name="employer_country['+jbVars.emplNum+']">'+
            countryOptions +
        '</select>'+
        '<label>'+jbVars.txtCity+'</label>'+
        '<input size="40" name="employer_city['+jbVars.emplNum+']" type="text" value="" />'+
        '<div class="nopaddiv"><select name="startyear['+jbVars.emplNum+']" class="infoRight first" tabindex="911">'+
           endYearOptions+
        '</select>'+
        '<select name="endmon['+jbVars.emplNum+']">'+
           monthOptions+
         '</select>'+
        '<label class="rightlabel">'+jbVars.txtEndyr+'</label>'+
        '<select name="endyear['+jbVars.emplNum+']" class="infoRight" tabindex="912">'+
           startYearOptions+
        '</select>'+
        '<select name="startmon['+jbVars.emplNum+']">'+
           monthOptions+
         '</select>'+
        '<label class="label required rightlabel first">'+jbVars.txtStartyr+'</label>'+
        '<span class="chk_wrapper">'+jbVars.txtEmplCurr+'<input class="chk_empl" type="checkbox" name="empl_iscurrent['+jbVars.emplNum+']" value="0" /></span></div>';

         var emplTarg = 'employer['+lastEmplNum+']';
         var emplEls = $$('div.emplrow');
         var emplTargEl = emplEls[emplEls.length -1];
         var etargElPos = emplTargEl.getPosition().y;
         var escrollIncr = emplTargEl.getSize().y;

         var newEmplRow = new Element('div', {'id': 'employer-'+jbVars.emplNum, 'name': 'employer['+jbVars.emplNum+']', 'class': 'emplrow'}).set('html', emplRowContent).injectAfter(emplTargEl).set('opacity', 0.3);

         var empSpan = newEmplRow.getElement('span[class="emplheading"]');
         empSpan.getElement('a').addEvents({
                  mousedown : function(){
                      e = new Event(e).stop();
                      this.getParent('div').set('opacity', 0.6);
                  },
                  mouseup : function(e){
                      e = new Event(e).stop();
                      this.getParent('div').set('opacity', 1);
                  },
                  click : function(e){
                      e = new Event(e).stop();
                      this.getParent('div').destroy();
                      jbVars.emplNum--;
                      winScroll.toElement(emplTargEl);
                      checkNumRows(true, 'em');
                  }
                });

         winScroll.start(0, etargElPos+escrollIncr).chain(
                function() {
                  newEmplRow.set('opacity', 1);
                }
             );
         checkNumRows(false, 'em');
       });

 });  // newEmp click handler

var checkNumRows = function(r, t){   //r = remove:boolean; t=type (employer or education)
  var edEls = t == 'ed'? $$('div.qualrow') : $$('div.emplrow');
  var numEls = edEls.length;
  var elMax = t == 'ed'?  jbVars.maxQuals : jbVars.maxEmployers;

  if(numEls < elMax) { //show add new button if maximum allowed element number hasnt been reached
    if(t == 'ed') $('newed').setStyle('visibility', 'visible');
    if(t == 'em') $('newemp').setStyle('visibility', 'visible');
  }

  if(t == 'ed') $('quals_count').setAttribute('value', numEls);
  if(t == 'em') $('employer_count').setAttribute('value', numEls);

  if(r == false) {
      if(numEls == 1 || numEls < 2) {
        return;

      } else {
          var deleteTrigger;
          var currEd;

          for (es=2; es < numEls; es++) {

            deleteTrigger = t == 'ed'? edEls[es-1].getElement('span[class="qualheading"]') : edEls[es-1].getElement('span[class="emplheading"]');
            currEd = deleteTrigger.getElement('a');

            if(es < numEls ) {

                currEd.setStyle('visibility', 'hidden');
                currEd.removeEvent('click');
                if(t == 'ed') $('quals_count').setAttribute('value', numEls);
                if(t == 'em') $('employer_count').setAttribute('value', numEls);

            } else {

                currEd.setStyle('visibility', 'visible');
                currEd.addEvents({
                  mousedown : function(){
                      e = new Event(e).stop();
                      this.getParent('div').set('opacity', 0.6);
                  },
                  mouseup : function(e){
                      e = new Event(e).stop();
                      this.getParent('div').set('opacity', 1);
                  },
                  click : function(e){
                      e = new Event(e).stop();
                      this.getParent('div').destroy();
                  }
                });

                if(t == 'ed') $('quals_count').setAttribute('value', numEls);
                if(t == 'em') $('employer_count').setAttribute('value', numEls);
            }
          }
        }
      } else { //remove == true

          var deleteTrigger;
          var currEd;

          deleteTrigger = t == 'ed'? edEls[numEls-1].getElement('span[class="qualheading"]') : edEls[numEls-1].getElement('span[class="emplheading"]');

          if(!deleteTrigger) return;
          currEd = deleteTrigger.getElement('a');

          if(es < numEls ) {
              currEd.setStyle('visibility', 'hidden');
              currEd.removeEvent('click');
              if(t == 'ed') $('quals_count').setAttribute('value', numEls);
              if(t == 'em') $('employer_count').setAttribute('value', numEls);

          } else {
              currEd.setStyle('visibility', 'visible');
              currEd.addEvents({
                  mousedown : function(e){
                      e = new Event(e).stop();
                      this.getParent('div').set('opacity', 0.6);
                  },
                  mouseup : function(e){
                      e = new Event(e).stop();
                      this.getParent('div').set('opacity', 1);
                  },
                  click : function(e){
                      e = new Event(e).stop();
                      this.getParent('div').destroy();
                  }
                });

              if(t == 'ed') $('quals_count').setAttribute('value', numEls);
              if(t == 'em') $('employer_count').setAttribute('value', numEls);
          }
        }
};
var delEdu = function(el){
      var edParentDiv = el.getParent('div');
      var edPrevEl = edParentDiv.getPrevious('div.qualrow');
      edParentDiv.fade(1, 0.3, 600).destroy();
      jbVars.qualNum--;
      winScroll.toElement(edPrevEl);
      checkNumRows(true, 'ed');
};
var delEmployer = function(el){
      var emParentDiv = el.getParent('div');
      var emplPrevEl = emParentDiv.getPrevious('div.emplrow');
      emParentDiv.fade(1, 0.3, 600).destroy();
      jbVars.emplNum--;
      winScroll.toElement(emplPrevEl);
      checkNumRows(true, 'em');
};