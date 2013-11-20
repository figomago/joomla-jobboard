
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */


window.addEvent('domready', function() {

  if(typeof(jbVars) != 'object' || jbVars.userTab != 2) {
       var uIcon = $('usericon');
       var thumbTxtPane = uIcon.getElement('p[class=caption]');
       if(profilePicPresent == 1) {

       		uIcon.addEvent('mouseover', function(e){
             		e = new Event(e).stop();
             		thumbTxtPane.setStyle('visibility', 'visible');
       		});
       		uIcon.addEvent('mouseout', function(e){
             		e = new Event(e).stop();
             		thumbTxtPane.setStyle('visibility', 'hidden');
       		});
	} else {
		thumbTxtPane.setStyle('visibility', 'visible');
                thumbTxtPane.getElement('a').set('text', 'Upload');
            }
  }

});
