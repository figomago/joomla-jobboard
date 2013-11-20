
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

var picWrapper, picInputs, picTxtHolder, currEdTxt, imgCrop, picEdBtn, edMode;

window.addEvent('domready',function(){
   edMode = 0;
   picWrapper = $('user-profile-content');
   picInputs = picWrapper.getElement('div[class=avatar-settings]');
   picTxtHolder = picWrapper.getElement('p[class=user-hlp]');

   if($('pic-edbtn') && profilePicPresent == 1) {
       picEdBtn = $('pic-edbtn');
       picEdBtn.addEvent('click', function(){
           setProfilePicEditor();
           $('pic-cropbtn').removeClass('hidden').addEvent('click', function(e){
             e = new Event(e).stop();
             document.userForm.submit();
           });
           picTxtHolder.set('text', jbVars.txtEditOn);
           edMode = 1;

           //----------------
       });
    }

   if($('pic-delbtn') && profilePicPresent == 1)  {
           $('pic-delbtn').addEvent('click', function(e){
                 e = new Event(e).stop();
                 var dUrl = "index.php?option=com_jobboard&view=image&task=imgdel&"+jbVars.tKn+"=1";   
                 window.location.href = jbaseUrl+dUrl;
           });
   }
   if($('pic-upld') && profilePicPresent == 0) {
         $('pic-upld').addClass('hidden');
   }
    $('settings_save').addEvent('click', function(e){
         e = new Event(e).stop();
         this.form.submit();
    });
});

var updateCropCoords = function(y,x,h,w){
          $('crop_w').setAttribute('value', w);
          $('crop_h').setAttribute('value', h);
          $('crop_x').setAttribute('value', x);
          $('crop_y').setAttribute('value', y);
};
var setProfilePicEditor = function(){

		mgCrop = new uvumiCropper('profile-image',{
    	    mini:{
    			x:140,
    			y:140
    		},
		    maskOpacity:0.7,
		    handleSize:10,
			coordinates:false,
            keepRatio:true,
			preview: "preview",
			downloadButton:false,
			saveButton:false,
            onComplete: updateCropCoords
		});

      //});

      picTxtHolder.setStyle('visibility', 'visible');
      picEdBtn.removeEvents().destroy();
      $('settings_save').removeEvents().addClass('hidden');
  };
var handleFileSelected =  function(el) {
        var btnsWrapper = $('pic-actions');
        if($('pic-upld')) {
          $('pic-upld').removeClass('hidden');
        } else {
          var picInputs = btnsWrapper.getElement('span[class=pic-btns]');
          picInputs.addClass('hidden');
          var span = new Element('span').addClass('pic-btns clear');
          var inp = new Element('input', {
                id: 'pic-upld',
                type: 'button',
                onclick: 'submitForm()',
                name: 'pic-upld',
                value: 'Upload'
              }).addClass('btn-blue').injectInside(span);
         span.inject(picInputs, 'after');
         $('task').setAttribute('value', 'upload');
         $('settings_save').removeEvents().addClass('hidden');
        }
};

var submitForm = function(){
     document.userForm.submit();
};
