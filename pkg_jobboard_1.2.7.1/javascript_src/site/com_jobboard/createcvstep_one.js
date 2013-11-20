
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

var lastFilelNum;
var fileRowContent = '' ;
var filerow = [];
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
       $('newfile').addEvent('click', function(e){
             e = new Event(e).stop();
             if(jbVars.fileNum == (jbVars.maxFiles - 1)) $('newfile').setStyle('visibility', 'hidden');
             if(jbVars.fileNum > (jbVars.maxFiles - 1)) return;
             lastfileNum =  jbVars.fileNum;
             jbVars.fileNum += 1;
             fileRowContent = '<label>'+jbVars.txtTitle+'</label>' +
                    '<input type="text" size="55" name="filetitle['+jbVars.fileNum+']" />' +
                    '<label class="midlabel">'+jbVars.txtUplfile+'</label>' +
                    '<a class="btn" href="#">'+jbVars.txtRemove+'</a>'+
                    '<input class="inputfield " name="file['+jbVars.fileNum+']" type="file" />' ;

             var fileTarg = 'filerow-'+lastfileNum;
             var fileTargEl = $('jbcontent').getElement('div[id='+fileTarg+']');
             var targElPos = fileTargEl.getPosition().y;
             var scrollIncr = fileTargEl.getSize().y;
             var newFileRow = new Element('div', {'id': 'filerow-'+jbVars.fileNum, 'class': 'filerow'}).set('html', fileRowContent).injectAfter(fileTargEl).set('opacity', 0.2);

             newFileRow.getElement('a').addEvents({
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
                      jbVars.fileNum--;
                      winScroll.toElement('filesInfo');
                      checkNumRows(true);
                  }
                });

                winScroll.toElement('filerow-'+jbVars.fileNum).chain(
                function() {
                  newFileRow.set('opacity', 1);
                }
             );         

             checkNumRows(false);
          });
          if($('btn-li-import')){
            $('btn-li-import').addEvent('click', function(e){
              e = new Event(e).stop();
                var liUri = this.getAttribute('href');
                var wideCol = this.getParent('div').set('html', '');
                var newImport = new Element('h2', {'id': 'li-process'}).set('html', jbVars.txtLiImport).inject(wideCol);
                window.location.href = liUri;
            });
          }
});
var checkNumRows = function(r){

  var edEls = $$('div.filerow');
  var numEls = edEls.length;
  var elMax = jbVars.maxFiles;

  if(numEls < elMax) {
    $('newfile').setStyle('visibility', 'visible');
  }

  $('file_count').setAttribute('value', numEls);

  if(r == false) {
      if(numEls == 1 || numEls < 3) {
        return;

      } else {

          var deleteTrigger;
          var currEd;

          for (es=2; es < numEls; es++) {

            deleteTrigger = edEls[es-1];
            currEd = deleteTrigger.getElement('a');

            if(es < numEls ) {

                currEd.setStyle('visibility', 'hidden');
                currEd.removeEvent('click');
                $('file_count').setAttribute('value', numEls);

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
                $('file_count').setAttribute('value', numEls);
            }
          }
        }
      } else {
          if(numEls == 1) return;
          var deleteTrigger;
          var currEd;
          deleteTrigger = edEls[numEls-1];
          if(!deleteTrigger) return;
          currEd = deleteTrigger.getElement('a');

          if(es < numEls ) {
              currEd.setStyle('visibility', 'hidden');
              currEd.removeEvent('click');
              $('file_count').setAttribute('value', numEls);

          } else {
              currEd.setStyle('visibility', 'visible');
              currEd.addEvent('click', function(){
                    this.getParent('div').set('opacity', 0.4).destroy();
              });

              $('file_count').setAttribute('value', numEls);
          }
        }
};