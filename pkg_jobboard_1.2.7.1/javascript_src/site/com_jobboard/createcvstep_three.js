
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

var lastSkillNum;
var skillRowContent = '' ;
var skillrow = [];
var yearOptions = '';
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
    var winScroll = new Fx.Scroll(this, {
      wait:false,
      duration: 350,
      transition: Fx.Transitions.Quart.easeInOut
    });
    if($('newskill') ) {
        checkNumskills();
        $('newskill').addEvent('click', function(e){
           e = new Event(e).stop();
           if(jbVars.skillNum == (jbVars.maxSkills - 1)) this.setStyle('visibility', 'hidden');
           if(jbVars.skillNum > (jbVars.maxSkills - 1)) return;
           lastSkillNum =  jbVars.skillNum;
           jbVars.skillNum += 1;
           for(y=0;y<18;y++) {
               var skillYear = jbVars.currYear - y;
               yearOptions += '<option value="'+ skillYear +'">'+ skillYear +'</option>\n';
           }
           skillRowContent = ''+
                '<span class="skillname">' +
                      '<input type="text" name="skillname['+jbVars.skillNum+']" value="" />' +
                '</span>' +
                '<span class="skilllastuse">' +
                       '<select  name="lastused['+jbVars.skillNum+']">' +
                        '<option value="0">'+jbVars.txtCurrent+'</option>' +
          			     yearOptions +
                       '</select>' +
                 '</span>' +
                 '<span class="skillexpperiod">' +
                       '<input type="text" name="experience['+jbVars.skillNum+']" value="" />' +
                 '</span>' +
                 '<span class="skillbtns">' +
                       '<a id="remove-'+jbVars.skillNum+'" href="#">'+jbVars.txtRemoveSkill+'</a>' +
                 '</span>' ;
           var skillTarg = 'skillrow-'+lastSkillNum;
           var skillEls = $$('div.skillwrapper');
           var skillTargEl = skillEls[skillEls.length -1];
           var targElPos = skillTargEl.getPosition().y;
           var scrollIncr = skillTargEl.getSize().y;

           var newSkillRow = new Element('div', {'id': 'skillrow-'+jbVars.skillNum, 'class': 'skillwrapper'}).set('html', skillRowContent).injectAfter(skillTargEl).set('opacity', 0.3);

           winScroll.start(0, 0.8*targElPos+scrollIncr).chain(
                function() {
                  newSkillRow.set('opacity', 1);
                }
             );
           var deleteTrigger = newSkillRow.getElement('span[class="skillbtns"]');
           deleteTrigger.getElement('a').addEvents({
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
                      jbVars.skillNum--;
                      winScroll.toElement(skillTargEl);
                      checkNumskills();
                  }
                });
          /* deleteTrigger.getElement('a').addEvent('click', function(){
                this.getParent('div').fade(1, 0.3, 600).destroy();
                jbVars.skillNum--;
                winScroll.toElement(skillTargEl);
                checkNumskills();
           });*/
           checkNumskills();
        });
    }
});

var checkNumskills = function(){
  var skillEls = $$('div.skillwrapper');
  var deleteTrigger = skillEls[0].getElement('span[class="skillbtns"]');
  var firstSkill = deleteTrigger.getElement('a');
  var numEls = skillEls.length;
  if(numEls !== 1) {
    if(firstSkill) {
      firstSkill.setStyle('display', 'block');
      firstSkill.addEvents({
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
                  jbVars.skillNum--;
                  winScroll.toElement(skillTargEl);
                  checkNumskills();
              }
      });
      $('skillscount').setAttribute('value', numEls);
    }
  } else {
      firstSkill.setStyle('display', 'none');
      firstSkill.removeEvent('click');
      $('skillscount').setAttribute('value', numEls);
  }
  if(numEls != jbVars.maxSkills) $('newskill').setStyle('visibility', 'visible');
};     