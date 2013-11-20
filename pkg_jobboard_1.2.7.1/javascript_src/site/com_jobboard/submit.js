
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

window.addEvent('domready', function() {
	
    $('submit_application').addEvent('click', function(e){
        e.preventDefault();
        this.disabled = 1;
        this.form.submit();
        $('loadr').removeClass('hidel');
        this.setStyles({'background':'#ccc',
        'color': '#ccc',
        'border-color': '#ddd'
        });
    });
});
    