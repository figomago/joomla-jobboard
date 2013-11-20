
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

	var edPaneSlide, QuestionnaireJson, editInputs, fieldLbl, fieldName, fnameTxt, fieldType, fieldNameVal, fieldDefaultVal, actionEls, defaultsPane, contQ, addBtn, qSorter, newFieldDefaults, submitBtn, windowScroll;
	var QuestionnaireData = {};

	window.addEvent('domready', function() {
		defaultsPane = $('ed_extra');
		contQ = $('fpanel');
		submitBtn = $('save_frm');
		editInputs = $('jbcontent').getElement('div[class=qedwrapper]');

	   	qSorter = new Sortables(contQ, {
	   	   opacity : 0.3,
           constrain: true,
           transition: Fx.Transitions.Elastic.easeOut,
           duration: 450 ,
           revert: { duration: 450, transition: 'elastic:out' },
		   onComplete : recalcElements
		});
		recalcElements();
		setDelActns();
		setActionElements();
		$('title_0').addEvents( {
			'focus' : function() {
				document.qForm.elements["title"].value = this.value;
				document.qForm.elements["name"].value = cleanString(this.value);
			},
			'blur' : function() {
				document.qForm.elements["title"].value = this.value;
				document.qForm.elements["name"].value = cleanString(this.value);
			},
			'keyup' : function() {
				document.qForm.elements["title"].value = this.value;
				document.qForm.elements["name"].value = cleanString(this.value);
			}
		});

		$('description_0').addEvents( {
			'focus' : function() {
				document.qForm.elements["description"].value = this.value;
			},
			'blur' : function() {
				document.qForm.elements["description"].value = this.value;
			},
			'keyup' : function() {
				document.qForm.elements["description"].value = this.value;
			}
		});

		if ($('field_add')) {
			addBtn = $('field_add');
			fieldLbl = editInputs.getElement('input[id=field_label]');
			fieldName = editInputs.getElement('input[id=field_name]');
			fnameTxt = editInputs.getElement('span[id=fnametxt]');
			fieldType = editInputs.getElement('select[id=field_type]');
			fieldNameVal;
	        windowScroll = new Fx.Scroll(window, {
	          transition : Fx.Transitions.Pow.easeInOut
	        });
			edPaneSlide = new Fx.Slide('ed_extra', {
				duration : 450,
				transition : Fx.Transitions.Pow.easeOut
			}).slideIn();

			var fieldTypeVal = fieldType.value;
			setEditPane(defaultsPane, fieldTypeVal);

			fieldLbl.addEvent('keyup', function() {
				fieldNameVal = cleanString(this.value);
				fieldName.setAttribute('value', fieldNameVal);
				fnameTxt.set('text', fieldNameVal);
			});

			addBtn.addEvent('click', function(e) {
				e = new Event(e).stop();
				if (fieldLbl.value != '') {
					var formEls = $$('div.qrow');
					var numEls = formEls.length;
					var insertPos = numEls > 0 ? numEls - 1 : 0;

					var fieldLblVal = fieldLbl.value;
					fieldNameVal = fieldName.value;
					var fieldTypeVal = fieldType.value;
					var fieldDvEl = defaultsPane.getLast().getLast();
					var fieldDvTag = fieldDvEl.tagName.toLowerCase();
					fieldDefaultVal = newFieldDefaults.deflt;
					var newRowObj = {
						"name" : fieldNameVal,
						"label" : fieldLblVal,
						"deflt" : fieldDefaultVal,
						"type" : fieldTypeVal,
                        "restricted" : newFieldDefaults.restricted
					};
					addRow(newRowObj, insertPos);
				}
			});

			fieldType.addEvent('change', function(e) {
				e = new Event(e).stop();
				var fieldTypeVal = fieldType.value;
				setEditPane(defaultsPane, fieldTypeVal);
                windowScroll.toElement('newfheader', 'y');
			});
		}
	});

	var cleanString = function(s) {
		var sVal = s.toLowerCase().trim();
		sVal = sVal.replace(/[^a-zA-Z0-9]+/gi, function(m) {
			return replaceSpace(m)
		});
		sVal = sVal.length > 21 ? sVal.substr(0, 21) : sVal;
		return sVal;
	};

	var setActionElements = function() {
		$$('a.ed').each(function(e) {
			e.addEvents( {
				'click' : function(ed) {
					ed = new Event(ed).stop();
                    actionEls = {};
                    if(qSorter != null) {
                      qSorter.detach();
                    }
                    qSorter = null;
					submitBtn.setAttribute('disabled', true);
					actionEls.edDiv = this.getParent('div.qrow');
					actionEls.rowLbl = actionEls.edDiv.getFirst('label');
                    actionEls.restricted = actionEls.rowLbl.hasClass('restricted')? 1 : 0;
					edPaneSlide.slideOut('vertical');
					editInputs.addClass('opacityPointFour');
					editInputs.getElements('input').each(function(inp) {
						inp.setAttribute('disabled', true);
					});
					editInputs.getElements('select').each(function(inp) {
						inp.setAttribute('disabled', true);
					});
					actionEls.edDiv.addClass('qrowed');
					contQ.getElements('div.qrow').each(function(div){
					    div.addClass('qrowedmode');
					});
					actionEls.rowNme = actionEls.rowLbl.getAttribute('for');
					actionEls.rowTxt = actionEls.rowLbl.get('text');
					actionEls.rowField = actionEls.edDiv.getLast();
                    actionEls.FieldTag = actionEls.rowField.tagName.toLowerCase();
                    if(actionEls.FieldTag == 'span' || actionEls.FieldTag == 'select') {}
                    else {
    					actionEls.descrSpan = new Element('span', {
    						'text' : jbVars.defVal
    					}).setStyles( {
    						'float' : 'right',
    						'margin-top' : '15px',
    						'margin-right' : '10px',
    						'display' : 'inline-block',
                            'font-size': '11px'
    					}).injectAfter(actionEls.rowField);
                    }
                    switch(actionEls.FieldTag) {
                      case 'input' :
					        actionEls.rowField.removeAttribute('disabled');
                      break;
                      case 'select' :
					        actionEls.rowField.removeAttribute('disabled');
                            actionEls.opts = actionEls.rowField.getElements('option');
                            actionEls.defaultOpt =  actionEls.rowField.value;
                            actionEls.selVal =  actionEls.defaultOpt;
                            actionEls.selOpt = actionEls.rowField.getElement('option[value='+actionEls.selVal+']');
                            actionEls.rowInput = new Element('input', {
        						'size' : 15,
        						'class' : 'right',
        						'type' : 'text',
        						'name' : actionEls.rowField.getAttribute('name'),
        						'value' : actionEls.selOpt.text
        					}).addEvents({
                                 'keyup': function(c){
                                      c = new Event(c).stop();
                                      actionEls.selOpt = actionEls.rowField.getElement('option[value='+actionEls.rowField.value+']');
                                      actionEls.selOpt.text = this.value;
                                   },
                                 'blur': function(c){
                                      c = new Event(c).stop();
                                      actionEls.selOpt = actionEls.rowField.getElement('option[value='+actionEls.rowField.value+']');
                                      actionEls.selOpt.text = this.value;
                                   }
                                }).setStyles({'padding': '1px', 'font-size': '11px'}).injectBefore(actionEls.rowField);
                            actionEls.rowDefltBtn = new Element('span', {
                                'class' : 'sdefault',
                                'text' : ' '
                            }).addClass('selected').addClass('right').addEvent('click', function(e){
                                  e = new Event(e).stop();
                                  actionEls.opts.each(function(o){
                                      o.removeAttribute('selected')
                                  });
                                  actionEls.selVal = actionEls.rowField.value;
                                  actionEls.selOpt = actionEls.rowField.getElement('option[value='+actionEls.selVal+']');
                                  actionEls.selOpt.setAttribute('selected', 'selected');
                                  actionEls.rowDefltBtn.addClass('selected');
                              }).injectAfter(actionEls.rowInput);
                             actionEls.rowAddCirc = new Element('span', {
                                 'class' : 'add',
                                 'text' : ' '
                             }).addClass('right').addEvent('click', function(s){
                                  s = new Event(s).stop();
                                  actionEls.opts = actionEls.rowField.getElements('option');
                                  actionEls.lastOpt = actionEls.rowField.getLast('option');
                                  actionEls.nextOpt = parseInt(actionEls.lastOpt.value) + 1;
                                  actionEls.lastOpt = new Element('option', {
                                      'value' : actionEls.nextOpt,
                                      'text' : jbVars.txtOption+' '+actionEls.nextOpt
                                    }).injectInside(actionEls.rowField);
                                 actionEls.rowField.selectedIndex = actionEls.nextOpt - 1;
                                 actionEls.rowInput.value = actionEls.lastOpt.text;
                                 actionEls.rowDefltBtn.removeClass('selected');
                                }).injectBefore(actionEls.rowInput);
                             actionEls.rowDelCirc = new Element('span', {
                                 'class' : 'cremove',
                                 'text' : ' '
                             }).addClass('right').addEvent('click', function(s){
                                  s = new Event(s).stop();
                                  actionEls.selVal = actionEls.rowField.value;
                                  actionEls.opts = actionEls.rowField.getElements('option');
                                  actionEls.selOpt = actionEls.rowField.getElement('option[value='+actionEls.selVal+']');
                                  if(actionEls.opts.length < 2) return false;
                                  actionEls.changeDefault = actionEls.defaultOpt == actionEls.selVal? true : false;
                                  actionEls.selOpt.removeEvents().destroy();
                                  actionEls.opts = actionEls.rowField.getElements('option');
                                  actionEls.opts[0].setAttribute('selected', 'selected');
                                  actionEls.selVal = actionEls.rowField.value;
                                  actionEls.selOpt = actionEls.rowField.getElement('option[value='+actionEls.selVal+']');
                                  actionEls.rowInput.value = actionEls.selOpt.text;
                                  if(actionEls.changeDefault == true) {
                                    actionEls.rowDefltBtn.addClass('selected');
                                    actionEls.defaultOpt = actionEls.selVal;
                                  } else {
                                    if(actionEls.defaultOpt == actionEls.selVal) {
                                        actionEls.rowDefltBtn.addClass('selected')
                                     } else {
                                        actionEls.rowDefltBtn.removeClass('selected')
                                     }
                                  }
                             }).injectBefore(actionEls.rowAddCirc);
                            actionEls.rowField.addEvent('change', function(s){
                                   s = new Event(s).stop();
                                   actionEls.selVal = actionEls.rowField.value;
                                   actionEls.selOpt = actionEls.rowField.getElement('option[value='+actionEls.selVal+']');
                                   actionEls.rowInput.value = actionEls.selOpt.text;
                                   if(actionEls.defaultOpt == actionEls.selVal){
                                      actionEls.rowDefltBtn.addClass('selected')
                                   } else {
                                      actionEls.rowDefltBtn.removeClass('selected')
                                   }
                                });
                      break;
                      case 'textarea' :
					        actionEls.rowField.removeAttribute('disabled');
                      break;
                      case 'span' :
                            if(actionEls.rowField.hasClass('checkbox'))  {
                               actionEls.rowInput = actionEls.rowField.getParent('div.qrow').getElement('input[type=checkbox]');
                               actionEls.rowInput.removeAttribute('disabled');
                               actionEls.rowInput.addClass('m2em');
                               actionEls.rowInput.addEvent('click',(function(){
                                    if(!this.getAttribute('checked')){
                                       this.setAttribute('checked', 'checked')
                                    } else {
                                       this.removeAttribute('checked')
                                    }
                                 })
                               );
                               actionEls.rowLblTxt = actionEls.rowField.get('text');
                               actionEls.rowField.setStyle('display', 'none');
                               actionEls.rowInputLabel = new Element('input', {
              						'size' : 15,
              						'class' : 'right',
              						'name' : 'cb_label_val',
              						'type' : 'text',
              						'value' : actionEls.rowLblTxt
              					}).setStyles({'padding': '1px', 'font-size': '11px'}).injectAfter(actionEls.rowInput);
                               actionEls.rowLblTxt = null;
                            }

                            if(actionEls.rowField.hasClass('radios'))  {
                              actionEls.rowField.addClass('r5px');
                              actionEls.rowLabels = [];
                              actionEls.rowInputs = [];
                              actionEls.rowFields = actionEls.rowField.getElements('input[type=radio]');
                              actionEls.rowFields.each(function(radio){
                                 var radioVal = radio.getAttribute('value');
                                 radio.removeAttribute('disabled');
                                 var radioLbl = radio.getParent('span.radio').getElement('label');
                                 actionEls.rowLabels[radioVal] = radioLbl.get('text');
                                 actionEls.rowInputs[radioVal] = new Element('input', {
              						'size' : 15,
              						'class' : 'left',
              						'type' : 'text',
              						'name' : radio.getAttribute('name')+'_'+radioVal,
              						'value' : actionEls.rowLabels[radioVal]
              					}).setStyles({'padding': '1px', 'font-size': '11px'}).injectBefore(radio);
                                 radioLbl.destroy();
                                 radio.addEvent('click', function(){
                                   actionEls.rowFields.each(function(r){
                                       r.removeAttribute('defaultchecked');
                                       r.removeAttribute('checked');
                                   });
                                   this.setAttribute('checked', 'checked');
                                 });
                              });
                              actionEls.rowHeight = parseInt(actionEls.edDiv.getStyle('height').replace('px', ''));
                              actionEls.radioHeight = parseInt(actionEls.rowFields[0].getParent('span.radio').getStyle('height').replace('px', ''));
                              actionEls.currRowSlide = new Fx.Slide(actionEls.edDiv.id, {
                  				duration : 350,
                  				transition : Fx.Transitions.Pow.easeOut
                  			}).slideIn('vertical');
                              actionEls.rowAddCirc = new Element('span', {
                                   'class' : 'add',
                                   'text' : ' '
                               }).addClass('right').addEvent('click', function(b){
                                      b = new Event(b).stop();
                                      actionEls.rowFields = actionEls.rowField.getElements('input[type=radio]');
                                      actionEls.rowFields.each(function(r){
                                           r.removeAttribute('defaultchecked');
                                           r.removeAttribute('checked');
                                       });
                                      actionEls.lastRadio = actionEls.rowFields.getLast();
                                      actionEls.nextOpt = parseInt(actionEls.lastRadio.value) + 1;
                                      actionEls.nextTxt = jbVars.txtOption+' '+actionEls.nextOpt;
                                      actionEls.newRadioLbl = actionEls.nextTxt;
                                      actionEls.newRadioName = cleanString(actionEls.nextTxt);
                                      actionEls.newRadioSpan = new Element('span', {
                                           'class' : 'radio'
                                       });
                                       actionEls.newRadioOpt = new Element('input', {
                                           'type' : 'radio',
                                           'class' : 'right',
                                           'name' : actionEls.lastRadio.name,
                                           'id' : actionEls.newRadioName,
                                           'alt' : actionEls.newRadioLbl,
                                           'value' : actionEls.nextOpt
                                       }).addEvent('click', function(){
                                           actionEls.rowFields = actionEls.rowField.getElements('input[type=radio]');
                                           actionEls.rowFields.each(function(r){
                                               r.removeAttribute('defaultchecked');
                                               r.removeAttribute('checked');
                                           });
                                           this.setAttribute('checked', 'checked');
                                         }).injectInside(actionEls.newRadioSpan);
                                       actionEls.rowFields.each(function(r){
                                           r.removeAttribute('defaultchecked');
                                           r.removeAttribute('checked');
                                       });
                                       actionEls.newRadioOpt.setAttribute('checked', 'checked');
                                       actionEls.newRadioOptLbl = new Element('input', {
                      						'size' : 15,
                      						'class' : 'left',
                      						'type' : 'text',
                      						'name' : actionEls.lastRadio.name+'_'+actionEls.nextOpt,
                      						'value' : actionEls.newRadioLbl
                      				}).setStyles({'padding': '1px', 'font-size': '11px'}).injectBefore(actionEls.newRadioOpt);

                                      actionEls.rowHeight += actionEls.radioHeight;
                                      actionEls.edDiv.setStyle('height', actionEls.rowHeight+'px');
                                      actionEls.currRowSlide.slideIn('vertical').chain(function(){
                                             actionEls.newRadioSpan.injectInside(actionEls.rowField);
                                      });
                                 }).injectBefore(actionEls.rowField);
                               actionEls.rowDelCirc = new Element('span', {
                                   'class' : 'cremove',
                                   'text' : ' '
                               }).addClass('right').addEvent('click', function(b){
                                      b = new Event(b).stop();
                                      actionEls.rowFields = actionEls.rowField.getElements('input[type=radio]');
                                      if(actionEls.rowFields.length < 2) return false;
                                      actionEls.rowFields.each(function(radio){
                                          if(radio.getAttribute('checked')!= null || radio.getAttribute('defaultchecked')!= null){
                                             actionEls.deleteRadio = radio.getParent('span.radio');
                                          }
                                      });
                                      actionEls.rowHeight -= actionEls.radioHeight;
                                      actionEls.edDiv.setStyle('height', actionEls.rowHeight+'px');
                                      actionEls.deleteRadio.setStyle('opacity', 0).removeEvents().destroy();
                                      actionEls.currRowSlide.slideIn('vertical');
                                      actionEls.rowFields = actionEls.rowField.getElements('input[type=radio]');
                                      actionEls.rowFields[0].setAttribute('checked', 'checked');
                                 }).injectBefore(actionEls.rowAddCirc);
                             }

                             if (actionEls.rowField.hasClass('datelbl')) {
                                 actionEls.dateInputs = actionEls.edDiv.getElements('input[type=text]');
                                 actionEls.dateRowMonth = actionEls.edDiv.getElement('select');
                                 actionEls.dateLbl = actionEls.dateRowMonth.name.replace('[month]', '').trim();
                                 actionEls.dateRowDay = actionEls.dateInputs[1];
                                 actionEls.dateRowYr = actionEls.dateInputs[0];
                                 actionEls.showDay = actionEls.dateRowDay.hasClass('displnone')? 0 : 1;
                                 actionEls.showMonth = actionEls.dateRowMonth.hasClass('displnone')? 0 : 1;
                                 actionEls.dateFieldLbls = actionEls.edDiv.getElements('span.datelbl');
                                 actionEls.dateRowDay.addEvents({
                                       'keypress': function(d){
                                       if((d.code == 8 || d.code == 46)) {}
                                       else{
                                         if (d.code < 48 || d.code > 57 || this.value.length >= 2) {
                                            d = new Event(d).stop();
                                         }
                                       }
                                    }
                                  });
                                 actionEls.dateRowYr.addEvents({
                                     'keypress': function(y){
                                       if((y.code == 8 || y.code == 46)) {}
                                       else{
                                         if (y.code < 48 || y.code > 57 || this.value.length >= 4) {
                                            y = new Event(y).stop();
                                         }
                                       }
                                    }
                                  });
                                 actionEls.dateRowMonth.removeAttribute('disabled');
                                 actionEls.dateRowDay.removeAttribute('disabled');
                                 actionEls.dateRowYr.removeAttribute('disabled');
                                 actionEls.dateRowDayCb = new Element('input', {
                                    'class': 'subinput',
                					'type' : 'checkbox',
                                    'name' :  actionEls.dateLbl+'_show_d',
                					'value' : 'yes'
                				}).addEvent('click', function(){
                				    if(actionEls.showDay == 0) {
                                        actionEls.showDay = 1;
                                        actionEls.dateRowDay.removeAttribute('disabled');
                                        actionEls.dateRowDayCb.setAttribute('checked', 'checked');
                                        if(!actionEls.dateRowMonthCb.hasAttribute('checked')){
                                            actionEls.dateRowMonthCb.setAttribute('checked', 'checked');
                                            actionEls.dateRowMonthCb.checked = true;
                                            actionEls.dateRowMonth.removeAttribute('disabled');
                                            actionEls.showMonth = 1;
                                        }
                                    } else {
                                        actionEls.showDay = 0;
                                        actionEls.dateRowDay.setAttribute('disabled', 'disabled');
                                        actionEls.dateRowDayCb.removeAttribute('checked');
                                    }
                				}).injectAfter(actionEls.dateFieldLbls[1]);
                                if(actionEls.showDay == 1) {
                                   actionEls.dateRowDayCb.setAttribute('checked', 'checked');
                                }
                                actionEls.dateRowMonthCb = new Element('input', {
                                    'class': 'subinput',
                					'type' : 'checkbox',
                                    'name' :  actionEls.dateLbl+'_show_m',
                					'value' : 'yes'
                				}).addEvent('click', function(){
                				    if(actionEls.showMonth == 0) {
                                        actionEls.showMonth = 1;
                                        actionEls.dateRowMonth.removeAttribute('disabled');
                                        actionEls.dateRowMonthCb.setAttribute('checked', 'checked');
                                        if(!actionEls.dateRowDayCb.hasAttribute('checked')){
                                            actionEls.dateRowDayCb.setAttribute('checked', 'checked');
                                            actionEls.dateRowDayCb.checked = true;
                                            actionEls.dateRowDay.removeAttribute('disabled');
                                            actionEls.showDay = 1;
                                        }
                                    } else {
                                        actionEls.showMonth = 0;
                                        actionEls.dateRowMonth.setAttribute('disabled', 'disabled');
                                        actionEls.dateRowMonthCb.removeAttribute('checked');
                                        if(actionEls.dateRowDayCb.hasAttribute('checked')){
                                            actionEls.dateRowDayCb.removeAttribute('checked');
                                            actionEls.dateRowDayCb.checked = false;
                                            actionEls.dateRowDay.setAttribute('disabled', 'disabled');
                                            actionEls.showDay = 0;
                                        }
                                    }
                				}).injectAfter(actionEls.dateFieldLbls[0]);

                                if(actionEls.showMonth == 1) {
                                  actionEls.dateRowMonthCb.setAttribute('checked', 'checked');
                                }
                                if(actionEls.dateRowDay.hasClass('displnone'))
                                    actionEls.dateRowDay.removeClass('displnone');
                                if(actionEls.dateFieldLbls[2].hasClass('displnone'))
                                    actionEls.dateFieldLbls[2].removeClass('displnone');
                                if(actionEls.dateRowMonth.hasClass('displnone'))
                                    actionEls.dateRowMonth.removeClass('displnone');
                                if(actionEls.dateFieldLbls[1].hasClass('displnone'))
                                    actionEls.dateFieldLbls[1].removeClass('displnone');
                                if(actionEls.showDay == 0) {
                                    actionEls.dateRowDay.setAttribute('disabled', 'disabled');
                                    actionEls.dateRowDayCb.removeAttribute('checked');
                                }
                                if(actionEls.showMonth == 0) {
                                   actionEls.dateRowMonth.setAttribute('disabled', 'disabled');
                                   actionEls.dateRowMonthCb.removeAttribute('checked');
                                }
                             }
                      break;
                    }
					actionElsRowActnbtns = contQ.getElements('div.qrow');
                    actionElsRowActnbtns.each(function(divRow){
                        if(divRow.id == actionEls.edDiv.id) {
                            divRow.getElements('a').each(function(a){
                                 a.setStyle('display', 'none')
                            })
                        } else {
                            divRow.getElements('a').each(function(a){
                                 a.setStyle('visibility', 'hidden')
                            })
                        }
                    });
					actionEls.lblInp = new Element('input', {
						'size' : 40,
						'class' : 'left',
						'type' : 'text',
						'name' : 'tmplbl',
						'value' : actionEls.rowTxt
					}).addClass('first');
                    actionEls.lockUnlock =  new Element('span', {
						'class' : 'restrictedactns',
                        'title' : jbVars.txtAdmOnlyQ,
						'text' : ' '
					}).addEvent(
                         'click' , function(l) {
                           l = new Event(l).stop();
                           actionEls.restricted = actionEls.restricted == 0? 1 : 0;
                           if(actionEls.restricted == 0 && !this.hasClass('public')) {
                             this.addClass('public')
                           }
                           if(actionEls.restricted == 1 && this.hasClass('public')) {
                             this.removeClass('public')
                           }
                         }
					);
					actionEls.rowLbl.destroy();
					actionEls.lblInp.injectBefore(actionEls.edDiv.getFirst());
                    if(actionEls.restricted == 0){
                      actionEls.lockUnlock.addClass('public')
                    }
					actionEls.rowSave = new Element('a', {
						'class' : 'btn-grn save',
						'text' : jbVars.txtDone
					}).addEvent('click', function(e) {
						e = new Event(e).stop();
						actionEls.rowTxt = actionEls.lblInp.value;
						actionEls.lblInp.destroy();
                        if(actionEls.FieldTag == 'span' || actionEls.FieldTag == 'select') {}
                        else {
						    actionEls.descrSpan.destroy();
                        }
						actionEls.newRowLbl = new Element('label', {
							'for' : actionEls.rowNme,
							'text' : actionEls.rowTxt
						}).injectBefore(actionEls.edDiv.getFirst());
                        if(actionEls.restricted == 1 ) {
                           actionEls.newRowLbl.addClass('restricted')
                        }
                        switch(actionEls.FieldTag) {
                          case 'input' :
    					        actionEls.rowField.setAttribute('disabled', true);
                          break;
                          case 'select' :
    					        actionEls.rowField.removeEvents().setAttribute('disabled', true);
                                actionEls.rowInput.removeEvents().destroy();
                                actionEls.rowDefltBtn.removeEvents().destroy();
                                actionEls.rowAddCirc.removeEvents().destroy();
                                actionEls.rowDelCirc.removeEvents().destroy();
                                actionEls.defaultOpt =  null;
                                actionEls.selVal =  null;
                                actionEls.selOpt = null;
                          break;
                          case 'textarea' :
    					        actionEls.rowField.setAttribute('disabled', true);
                          break;
                          case 'span' :
                               if(actionEls.rowField.hasClass('checkbox'))  {
                                 actionEls.rowInput.removeEvents().setAttribute('disabled', true);
                                 actionEls.rowInput.removeClass('m2em');
                                 actionEls.rowField.set('text', actionEls.rowInputLabel.value);
                                 actionEls.rowInputLabel.destroy();
                                 actionEls.rowField.setStyle('display', 'inline');
                               }

                               if(actionEls.rowField.hasClass('radios'))  {
                                  actionEls.rowField.removeClass('r5px');
                                  actionEls.rowFields = actionEls.rowField.getElements('input[type=radio]');
                                  actionEls.rowFields.each(function(radio){
                                     radio.setAttribute('disabled', true);
                                     radio.removeEvents();
                                     var radioVal = radio.getAttribute('value');
                                     var radioInp = radio.getParent('span.radio').getElement('input[type=text]');
                                     actionEls.rowLabels[radioVal] = radioInp.value;
                                     radioInp.destroy();
                                     actionEls.newRadioLbl = new Element('label', {
              							'for' : actionEls.inputSafeName+'_'+radio.value,
              							'text' : actionEls.rowLabels[radioVal]
              					   }).injectBefore(radio);
                                  });
                                  actionEls.rowLabels = null;
                                  actionEls.rowAddCirc.removeEvents().destroy();
                                  actionEls.rowDelCirc.removeEvents().destroy();
                             }

                             if (actionEls.rowField.hasClass('datelbl')) {
                                 if(actionEls.showMonth == 0) {
                                     actionEls.dateRowMonth.addClass('displnone');
                                     actionEls.dateFieldLbls[1].addClass('displnone');
                                     actionEls.showDay == 0;
                                 }
                                 if(actionEls.showDay == 0) {
                                     actionEls.dateRowDay.addClass('displnone');
                                     actionEls.dateFieldLbls[2].addClass('displnone');
                                 }
                                 actionEls.dateRowDayCb.removeEvents().destroy();
                                 actionEls.dateRowMonthCb.removeEvents().destroy();
                                 actionEls.dateRowDay.setAttribute('disabled', 'disabled');
                                 actionEls.dateRowMonth.setAttribute('disabled', 'disabled');
                                 actionEls.dateRowYr.value = actionEls.dateRowYr.value.trim();
                                 actionEls.dateRowYr.setAttribute('disabled', 'disabled');
                             }
                          break;
                        }
                        actionEls.lockUnlock.removeEvents().destroy();
						actionEls.edDiv.removeClass('qrowed');
                        contQ.getElements('div.qrow').each(function(div){
    					    div.removeClass('qrowedmode');
    					});
						submitBtn.removeAttribute('disabled');
						edPaneSlide.slideIn('vertical');
						editInputs.removeClass('opacityPointFour');
						editInputs.getElements('input').each(function(inp) {
							inp.removeAttribute('disabled');
						});
						editInputs.getElements('select').each(function(inp) {
							inp.removeAttribute('disabled');
						});
						actionEls.rowSave.removeEvents().destroy();
                        actionElsRowActnbtns.each(function(divRow){
                        if(divRow.id == actionEls.edDiv.id) {
                            divRow.getElements('a').each(function(a){
                                 a.setStyle('display', 'inline-block')
                            })
                          } else {
                            divRow.getElements('a').each(function(a){
                                 a.setStyle('visibility', 'visible')
                            })
                          }
                        });
                        actionElsRowActnbtns = null;
						qSorter = new Sortables(contQ, {
                    	   	   opacity : 0.3,
                               constrain: true,
                               transition: Fx.Transitions.Elastic.easeOut,
                               duration: 450 ,
                               revert: { duration: 450, transition: 'elastic:out' },
                    		   onComplete : recalcElements
                    		});
						recalcElements();
                        actionEls = null;
					}).injectAfter(actionEls.edDiv.getFirst());
                    actionEls.lockUnlock.injectBefore(actionEls.rowSave);
				}
			});
		});
	};

	var setDelActns = function() {
		$$('a.del').each(function(e) {
			e.addEvents( {
				'click' : function(e) {
					e = new Event(e).stop();
                    var frmRowEl = this.getParent().getLast();
                    var frmRowTag = frmRowEl.tagName.toLowerCase();
                    var frmRow;
                    if(frmRowTag == 'span')  {
                      if(frmRowEl.hasClass('checkbox')) {
                         frmRow =  frmRowEl.getParent('div.qrow').getElement('input[type=checkbox]').name;
                      }
                      if(frmRowEl.hasClass('radios')) {
                        frmRow =  frmRowEl.getFirst('span.radio').getElement('input[type=radio]').name;
                      }
                    } else
  					     frmRow = frmRowEl.name;
					document.qForm.elements["task"].value = 'delqrow';
					document.qForm.elements["name"].value = frmRow;
					document.qForm.submit();
				}
			});
		});
	};

	var replaceSpace = function(match) {
		if (match == " " || match == "&nbsp;")
			return "_";
		else
			return '';
	};

	var recalcElements = function() {
		var formEls = $$('div.qrow');
		var numEls = formEls.length;
		var currEl, currLbl, currInput;
		QuestionnaireData.fields = [];
		var elsObj = {};

		if (numEls > 0) {
			for (row = 0; row < numEls; row++) {
				currEl = formEls[row];
                var currLblEl = currEl.getFirst('label');
				currLbl = currLblEl.get('text');
				currInput = currEl.getLast();
                var restricted = !currLblEl.hasClass('restricted') ? 0 : 1;
				var currTag = currInput.tagName.toLowerCase();
				var currVal, currName;
				if(currTag != 'span') currName = currInput.getAttribute('name').trim();
				if (currTag == 'input') {
					currTag = currInput.getAttribute('type').trim();
					switch (currTag) {
					case 'checkbox' :
                    break;
                    case 'text' :
                        currVal = currInput.value.trim();
					default:
					;break;
					}
				}
                if (currTag == 'textarea') currVal = currInput.value.trim();
				if (currTag == 'select') {
				    var selectedOpt;
				    currVal = {};
                    var optObj = {};
                    var optObjects = [];
                    currVal.defaultOpt = 1;
                    currInput.getElements('option').each(function(o){
                           optObj = {};
                           optObj.value = o.getAttribute('value');
                           optObj.label = o.text;
                           optObjects.push(optObj);

                           if(!o.getAttribute('selected')){}
                           else {currVal.defaultOpt = optObj.value};
                        });
                    currVal.multiple = !currInput.getAttribute('multiple')? 0 : 1;
                    currVal.options = optObjects;
                    optObj = null;
				}
                if (currTag == 'span' && currInput.hasClass('checkbox')) {
                         currVal = {};
                         var currCB = currInput.getParent('div.qrow').getElement('input[type=checkbox]');
						 currVal.value = !currCB.getAttribute('checked') ? 0 : 1;
                         currVal.label = currInput.get('text');
                         currName = currCB.getAttribute('name').trim();
                         currTag = 'checkbox';
                }

                if (currTag == 'span' && currInput.hasClass('radios')) {
				    var selectedRadio;
				    currVal = {};
                    var radioObj = {};
                    var radioObjects = [];
                    currVal.defaultOpt = 1;
                    currInput.getElements('input[type=radio]').each(function(o){
                           radioObj = {};
                           radioObj.value = o.getAttribute('value');
                           radioObj.label = o.getParent('span').getElement('label').get('text');
                           radioObj.id = o.id;
                           radioObjects.push(radioObj);

                           if(!o.getAttribute('checked')){}
                           else {currVal.defaultOpt = radioObj.value};
                        });
                    currTag = 'radio';
                    currVal.options = radioObjects;
                    currName = currInput.getFirst('span.radio').getElement('input[type=radio]').getAttribute('name');
                    radioObj = null;
				}
                if (currTag == 'span' && currInput.hasClass('datelbl')) {

                     currVal = {};
                     var dateRow = currInput.getParent('div.qrow');
                     var dateTxts = dateRow.getElements('input[type=text]');
                     var dateSel = dateRow.getElement('select');
                     currName = dateSel.name.replace('[month]', '').trim();
                     var dateRowDay = dateTxts[1];
                     currVal.showDay = dateRowDay.hasClass('displnone')? 0 : 1;
                     currVal.showMonth = dateSel.hasClass('displnone')? 0 : 1;
                     currVal.defaultDay = dateRowDay.value;
                     currVal.defaultMonth = dateSel.value;
                     currVal.defaultYear = dateTxts[0].value;
                     currTag = 'date';
                     dateRow = null;
                     dateRowDay = null;
                     dateRowMonth = null;
                }
				elsObj = {
					"name" : currName,
					"label" : currLbl,
					"deflt" : currVal,
					"type" : currTag,
                    "restricted" : restricted
				};
				QuestionnaireData.fields.push(elsObj);
			}
			QuestionnaireJson = JSON.encode(QuestionnaireData);
			document.qForm.elements["fields"].value = QuestionnaireJson;
		} else {
		  qSorter.detach();
          qSorter = null;
          document.qForm.elements["title"].value = $('title_0').value;
          document.qForm.elements["description"].value = $('description_0').value;
          document.qForm.elements["name"].value = cleanString(document.qForm.elements["title"].value);
		}
	};

	var setEditPane = function(el, fType) {
        var paneDefsCont = el.getElement('span[class=cont]');
        newFieldDefaults = {};
        newFieldDefaults.restricted = 0;
        newFieldDefaults.type = fType;
        paneDefsCont.set('html', '');
        var paneAdmOnlyChk = new Element('input', {
             'type' : 'checkbox',
             'name' : 'admins_only',
             'id' : 'admins_only',
             'value' : 'yes'
        });
        var paneAdmOnlyLbl = new Element('label', {
             'class' : 'l15',
             'for' : 'admins_only',
             'text' : jbVars.txtAdmOnly
        });
		edPaneSlide.slideOut('vertical');
        var pHeight = 38;
        if(fType != 'select' || fType != 'radio')  {
        	defaultsPane.getElements('input[type=text]').each(
    			function(inp) {
    				inp.value = '';
    			});
        }
		switch (fType) {
		case 'checkbox':
             var paneDefaultLbl = new Element('label', {
                 'for' : 'default_val',
                 'text' : jbVars.txtSubLbl
             });
             var paneDefEl = new Element('select', {
                 'name' : 'default_val',
                 'id' : 'default_val'
             }).addEvent('change', function(){
                 newFieldDefaults.deflt.value = this.value;
             });
             var paneDefSelOpt1 = new Element('option', {
                 'text' : jbVars.txtUnhecked,
                 'value' : '0'
             }).injectInside(paneDefEl);
             var paneDefSelOpt2 = new Element('option', {
                 'text' : jbVars.txtChecked,
                 'value' : '1'
             }).injectAfter(paneDefSelOpt1);
              var paneDefInp = new Element('input', {
                 'type' : 'text',
                 'name' : 'default_val_lbl',
                 'id' : 'default_val_lbl',
                 'value' : ''
             }).addEvent(
                    'keyup', function(){
                      newFieldDefaults.deflt.label = this.value;
                });
                newFieldDefaults.deflt = {};
                newFieldDefaults.deflt.value = 0;
                newFieldDefaults.deflt.label = '';
			break;
		case 'radio':
            pHeight = 64;
             var paneRadios = new Element('span', {
                 'class' : 'radios',
                 'id' : 'default_radios'
             });
             var radio1Lbl = jbVars.txtOption+' 1';
             var radio1Name = cleanString(radio1Lbl);
             var radio1Span = new Element('span', {
                 'class' : 'radio'
             }).injectInside(paneRadios);
             var radioOpt1 = new Element('input', {
                 'type' : 'radio',
                 'class' : 'right',
                 'name' : 'radio_value',
                 'id' : radio1Name,
                 'checked' : 'checked',
                 'disabled' : 'disabled',
                 'alt' : radio1Lbl,
                 'value' : '1'
             }).injectInside(radio1Span);
             var radioOpt1Lbl = new Element('label', {
                 'for' : radio1Name,
                 'text' : radio1Lbl
             }).injectBefore(radioOpt1);
             var paneDefEl = new Element('select', {
                 'class' : 'selopts',
                 'name' : 'default_val',
                 'id' : 'default_val'
             });
             var paneDefSelOpt1 = new Element('option', {
                 'selected' : 'selected',
                 'text' : jbVars.txtOption+' 1',
                 'value' : '1'
             }).injectInside(paneDefEl);
             var paneAddCirc = new Element('span', {
                 'class' : 'add',
                 'text' : ' '
             });
             var paneDelCirc = new Element('span', {
                 'class' : 'cremove',
                 'text' : ' '
             });
             var paneDefCirc = new Element('span', {
                 'id' : 'odefault',
                 'class' : 'sdefault',
                 'text' : ' '
             }).addClass('selected');
             var optValue = new Element('input', {
                 'type' : 'text',
                 'name' : 'opt_val',
                 'id' : 'opt_val',
                 'value' : jbVars.txtOption+' 1'
             });
             newFieldDefaults.deflt = {};
			break;
		case 'text':
             var paneDefaultLbl = new Element('label', {
                 'for' : 'default_val',
                 'text' : jbVars.defVal
             });
             var paneDefEl = new Element('input', {
                 'type' : 'text',
                 'name' : 'default_val',
                 'id' : 'default_val',
                 'value' : ''
             }).addEvents({
                   'keyup' : function(){
                     newFieldDefaults.deflt = this.value;
                   },
                   'blur' : function(){
                     newFieldDefaults.deflt = this.value;
                   }
               });
            var paneReqdChk = new Element('input', {
                 'type' : 'checkbox',
                 'name' : 'required',
                 'id' : 'required',
                 'value' : 'yes'
            });
            var paneReqdLbl = new Element('label', {
                 'class' : 'l15',
                 'for' : 'required',
                 'text' : jbVars.txtReqd
            }).injectBefore(paneReqdChk);
            newFieldDefaults.deflt = '';
			break;
		case 'textarea':
             var paneDefaultLbl = new Element('label', {
                 'for' : 'default_val',
                 'text' : jbVars.defVal
             });
             var paneDefEl = new Element('input', {
                 'type' : 'text',
                 'name' : 'default_val',
                 'id' : 'default_val',
                 'value' : ''
             }).addEvents({
                   'keyup' : function(){
                     newFieldDefaults.deflt = this.value;
                   },
                   'blur' : function(){
                     newFieldDefaults.deflt = this.value;
                   }
               });
            var paneReqdChk = new Element('input', {
                 'type' : 'checkbox',
                 'name' : 'required',
                 'id' : 'required',
                 'value' : 'yes'
            });
            var paneReqdLbl = new Element('label', {
                 'class' : 'l15',
                 'for' : 'required',
                 'text' : jbVars.txtReqd
            }).injectBefore(paneReqdChk);
            newFieldDefaults.deflt = 0;
			break;
		case 'select':
             var paneDefEl = new Element('select', {
                 'class' : 'selopts',
                 'name' : 'default_val',
                 'id' : 'default_val'
             });
             var paneDefSelOpt1 = new Element('option', {
                 'selected' : 'selected',
                 'text' : jbVars.txtOption+' 1',
                 'value' : '1'
             }).injectInside(paneDefEl);
             var paneAddCirc = new Element('span', {
                 'class' : 'add',
                 'text' : ' '
             });
             var paneDelCirc = new Element('span', {
                 'class' : 'cremove',
                 'text' : ' '
             });
             var paneDefCirc = new Element('span', {
                 'id' : 'odefault',
                 'class' : 'sdefault',
                 'text' : ' '
             }).addClass('selected');
             var optValue = new Element('input', {
                 'type' : 'text',
                 'name' : 'opt_val',
                 'id' : 'opt_val',
                 'value' : jbVars.txtOption+' 1'
             });
             var paneMultiChk = new Element('input', {
             'type' : 'checkbox',
             'name' : 'multiple',
             'id' : 'multiple',
             'value' : 'yes'
              }).addEvent('click', function(){
                  newFieldDefaults.deflt.multiple = (newFieldDefaults.deflt.multiple == 0)? 1 : 0;
              });
              var paneMultiLbl = new Element('label', {
                   'class' : 'l15',
                   'for' : 'admins_only',
                   'text' : jbVars.txtMulti
              });
             newFieldDefaults.deflt = {};
             break;
		case 'date':
             var dayBox = new Element('input',{
                 'type': 'text',
                 'size': 3,
                 'name': 'default_day',
                 'value': parseInt(jbVars.currDay) < 9? '0'+jbVars.currDay : jbVars.currDay
             }).addEvents({
                   'keypress': function(d){
                     if(this.hasClass('error')) this.removeClass('error');
                     this.value = this.value.replace('.', '');
                     if((d.code == 8 || d.code == 46)) {}
                         else{
                           if (d.code < 48 || d.code > 57 || this.value.length >= 2) {
                              d = new Event(d).stop();
                              this.addClass('error');
                           }
                         }
                    },
                   'keyup' : function(){
                     newFieldDefaults.deflt.defaultDay = this.value;
                   },
                   'blur' : function(){
                     newFieldDefaults.deflt.defaultDay = this.value;
                   }
               });
             var monthSel = new Element('select',{
                 'name': 'default_month'
             }).addEvent('change', function(){
                  newFieldDefaults.deflt.defaultMonth = this.value;
             });
             var currMon = parseInt(jbVars.currMonth);
             for(m=0; m<12; m++) {
                 var theMonth = m+1;
                 var paddedMo = m < 9? '0'+theMonth : theMonth;
                 var newMo = new Element('option', {
                     'text' : jbVars.months[m],
                     'value' : paddedMo
                 });
                 if(theMonth == currMon)
                    newMo.setAttribute('selected', 'selected');
                 newMo.injectInside(monthSel);
             }
             var paneDefEl = new Element('input', {
                 'size' : 4,
                 'type' : 'text',
                 'name' : 'default_year',
                 'id' : 'default_year',
                 'value' : jbVars.currYear
             }).addEvents({
                   'keypress' : function(y){
                     if((y.code == 8 || y.code == 46)) {}
                     else{
                       if (y.code < 48 || y.code > 57 || this.value.length >= 4) {
                          y = new Event(y).stop();
                       }
                     }
                   },
                   'keyup' : function(y){
                     newFieldDefaults.deflt.defaultYear = this.value;
                   },
                   'blur' : function(){
                     newFieldDefaults.deflt.defaultYear = this.value;
                   }
               });
            var paneReqdChk = new Element('input', {
                 'type' : 'checkbox',
                 'name' : 'required',
                 'id' : 'required',
                 'value' : 'yes'
            });
            var paneReqdLbl = new Element('label', {
                 'class' : 'l15',
                 'for' : 'required',
                 'text' : jbVars.txtReqd
            }).injectBefore(paneReqdChk);
             var paneDefaultLbl = new Element('label', {
                 'for' : 'default_val',
                 'text' : jbVars.txtIncl+':'
             });
            newFieldDefaults.deflt = {};
			break;
		}
		edPaneSlide.chain(function() {
		    el.setStyle('height', pHeight+'px');
			el.getElement('span[class=hdr]').set('html',
					jbVars.fieldType + ' : ' + fType + ' | ');
            paneDefEl.injectInside(paneDefsCont);
            if(fType == 'select' || fType == 'radio') {}
            else paneDefaultLbl.injectBefore(paneDefEl);
            if(fType == 'checkbox') paneDefInp.injectBefore(paneDefEl);
            paneAdmOnlyChk.injectAfter(paneDefEl).addEvent('click', function(){
                 newFieldDefaults.restricted = (this.checked == true)? 1 : 0;
              });
            paneAdmOnlyLbl.injectBefore(paneAdmOnlyChk);
            if(fType == 'date') {
               newFieldDefaults.deflt.showDay = 1;
               newFieldDefaults.deflt.showMonth = 1;
               newFieldDefaults.deflt.defaultDay = jbVars.currDay;
               newFieldDefaults.deflt.defaultMonth = jbVars.currMonth;
               newFieldDefaults.deflt.defaultYear = jbVars.currYear;
               dayBox.injectBefore(paneDefEl);
               monthSel.injectAfter(dayBox);
               var dayChk = new Element('input', {
                    'class': 'subinput',
					'type' : 'checkbox',
					'name' : 'show_day',
					'value' : 'yes'
				}).addEvent('click', function(){
				    if(newFieldDefaults.deflt.showDay == 0) {
                        newFieldDefaults.deflt.showDay = 1;
                        dayBox.removeAttribute('disabled');
                        dayChk.setAttribute('checked', 'checked');
                        if(!monthChk.hasAttribute('checked') && monthSel.hasAttribute('disabled')){
                            monthChk.setAttribute('checked', 'checked');
                            monthChk.checked = true;
                            monthSel.removeAttribute('disabled');
                            newFieldDefaults.deflt.showMonth = 1;
                        }
                    } else {
                        newFieldDefaults.deflt.showDay = 0;
                        dayBox.setAttribute('disabled', 'disabled');
                        dayChk.removeAttribute('checked');
                    }
				}).injectBefore(monthSel);
                dayChk.setAttribute('checked', 'checked');
                var dayChkLbl = new Element('span', {
                	'class' : 'checkbox',
                    'text': jbVars.txtInclday
    			}).injectBefore(dayBox);
               var monthChk = new Element('input', {
                    'class': 'subinput',
					'type' : 'checkbox',
					'name' : 'show_month',
					'value' : 'yes'
				}).addEvent('click', function(){
				    if(newFieldDefaults.deflt.showMonth == 0) {
                        newFieldDefaults.deflt.showMonth = 1;
                        monthSel.removeAttribute('disabled');
                        monthChk.setAttribute('checked', 'checked');
                    } else {
                        newFieldDefaults.deflt.showMonth = 0;
                        monthSel.setAttribute('disabled', 'disabled');
                        newFieldDefaults.deflt.showDay = 0;
                        monthChk.removeAttribute('checked');
                        if(dayChk.hasAttribute('checked') && !dayBox.hasAttribute('disabled')){
                            dayChk.removeAttribute('checked');
                            dayChk.checked = false;
                            dayBox.setAttribute('disabled', 'disabled');
                            newFieldDefaults.deflt.showDay = 0;
                        }
                    }
				}).injectBefore(paneDefEl);
                monthChk.setAttribute('checked', 'checked');
                var monthChkLbl = new Element('span', {
                	'class' : 'checkbox',
                    'text': jbVars.txtInclmonth
    			}).injectBefore(monthSel);
                var yearBoxLbl = new Element('span', {
                	'class' : 'checkbox',
                    'text': jbVars.txtYear
    			}).injectBefore(paneDefEl);
            }
            if(fType == 'select') {
               newFieldDefaults.deflt.defaultOpt = 1;
               newFieldDefaults.deflt.multiple = 0;
               var selOpts = [];
               var optionObj = {value: newFieldDefaults.deflt.defaultOpt, label: jbVars.txtOption+' 1'};
               selOpts.push(optionObj);
               newFieldDefaults.deflt.options = selOpts;
               var currDefSel = paneDefsCont.getElement('select[id=default_val]');
               var currDefOpt = currDefSel.getElement('option[value='+currDefSel.value+']');
               currDefSel.addEvent('change', function(s){
                     s = new Event(s).stop();
                     var optsTxt = paneDefsCont.getElement('input[id=opt_val]');
                     currDefOpt = currDefSel.getElement('option[value='+currDefSel.value+']');
                     optsTxt.value = currDefOpt.text;
                     if(newFieldDefaults.deflt.defaultOpt == currDefSel.value){
                        var defaultSelector = paneDefsCont.getElement('span[id=odefault]');
                        defaultSelector.addClass('selected')
                     } else {
                        var defaultSelector = paneDefsCont.getElement('span[id=odefault]');
                        defaultSelector.removeClass('selected')
                     }
               });
               paneAddCirc.addEvent('click', function(c){
                    c = new Event(c).stop();
                    currDefOpt.removeAttribute('selected');
                    var optsTxt = paneDefsCont.getElement('input[id=opt_val]');
                    var lastOpt = currDefSel.getLast('option');
                    var nextOpt = parseInt(lastOpt.value) + 1;
                    var nextTxt = jbVars.txtOption+' '+nextOpt;
                    var newOpt = new Element('option', {
                       'selected' : 'selected',
                       'text' : nextTxt,
                       'value' : nextOpt
                   }).injectInside(currDefSel);
                   optsTxt.value =  nextTxt;
                   optionObj = {value: nextOpt, label:nextTxt};
                   newFieldDefaults.deflt.options[nextOpt] = optionObj;
                   var currDefltBtn = paneDefsCont.getElement('span[id=odefault]');
                   currDefltBtn.removeClass('selected');
                   var delBtn = paneDefsCont.getElement('span[class=cremove]');
                   var currOptsCount = currDefSel.getElements('option').length;
                   if(currOptsCount == 1)
                    delBtn.setStyle('visibility', 'hidden');
                   if(currOptsCount > 1)
                    delBtn.setStyle('visibility', 'visible');
               }).injectBefore(paneAdmOnlyLbl);
               optValue.addEvents({
                     'keyup': function(c){
                          c = new Event(c).stop();
                          currDefOpt = currDefSel.getElement('option[value='+currDefSel.value+']');
                          currDefOpt.text = this.value;
                          newFieldDefaults.deflt.options[currDefSel.value].label = this.value;
                       },
                     'blur': function(c){
                          c = new Event(c).stop();
                          currDefOpt = currDefSel.getElement('option[value='+currDefSel.value+']');
                          currDefOpt.text = this.value;
                          newFieldDefaults.deflt.options[currDefSel.value].label = this.value;
                       }
                   }).injectBefore(paneAddCirc);
               paneDelCirc.addEvents({
                   'load' : function(l) {
                      l = new Event(l).stop();
                      var currOptsCount = currDefSel.getElements('option').length;
                      if(currOptsCount == 1)
                        l.setStyle('visibility', 'hidden');
                   },
                   'click': function(c){
                      c = new Event(c).stop();
                      var currOptsCount = currDefSel.getElements('option').length;
                      if(currOptsCount == 1) {
                        this.setStyle('visibility', 'hidden');
                        return;
                      }
                      var optsTxt = paneDefsCont.getElement('input[id=opt_val]');
                      currDefOpt = currDefSel.getElement('option[value='+currDefSel.value+']');
                      newFieldDefaults.deflt.options[currDefSel.value] = null;
                      currDefOpt.destroy();
                      currDefOpt = currDefSel.getElement('option[value='+currDefSel.value+']');
                      optsTxt.value =  currDefOpt.text;
                    }
                   }).injectAfter(paneAddCirc);
               paneDefCirc.addEvent('click', function(e){
                      e = new Event(e).stop();
                      newFieldDefaults.deflt.defaultOpt = currDefSel.value;
                      var defaultSelector = paneDefsCont.getElement('span[id=odefault]');
                      defaultSelector.addClass('selected');
                  }).injectAfter(paneDefEl);
               paneMultiChk.injectBefore(paneAdmOnlyLbl);
               paneMultiLbl.injectBefore(paneMultiChk);
            }

            if(fType == 'radio') {
               newFieldDefaults.deflt.defaultOpt = 1;
               var selOpts = [];
               var optionObj = {value: newFieldDefaults.deflt.defaultOpt, label: jbVars.txtOption+' 1', id: cleanString(jbVars.txtOption+' 1')};
               selOpts.push(optionObj);
               newFieldDefaults.deflt.options = selOpts;
               var currDefSel = paneDefsCont.getElement('select[id=default_val]');
               var currDefOpt = currDefSel.getElement('option[value='+currDefSel.value+']');
               var currDefRadio = paneRadios.getElement('input[value='+currDefSel.value+']');
               var currDefRadioLbl = currDefRadio.getParent('span').getElement('label');
               var paneRadioInputs = paneRadios.getElements('input[type=radio]');
               currDefSel.addEvent('change', function(s){
                     s = new Event(s).stop();
                     var optsTxt = paneDefsCont.getElement('input[id=opt_val]');
                     currDefOpt = currDefSel.getElement('option[value='+currDefSel.value+']');
                     paneRadioInputs = paneRadios.getElements('input[type=radio]');
                     paneRadioInputs.each(function(r){
                        r.removeAttribute('defaultchecked');
                        r.removeAttribute('checked');
                     });
                     currDefRadio = paneRadios.getElement('input[value='+currDefSel.value+']');
                     currDefRadio.set('checked', 'checked');
                     optsTxt.value = currDefOpt.text;
                     if(newFieldDefaults.deflt.defaultOpt == currDefSel.value){
                        var defaultSelector = paneDefsCont.getElement('span[id=odefault]');
                        defaultSelector.addClass('selected')
                     } else {
                        var defaultSelector = paneDefsCont.getElement('span[id=odefault]');
                        defaultSelector.removeClass('selected')
                     }
               });
               paneAddCirc.addEvent('click', function(c){
                    c = new Event(c).stop();
                    currDefOpt.removeAttribute('selected');
                    var optsTxt = paneDefsCont.getElement('input[id=opt_val]');
                    var lastOpt = currDefSel.getLast('option');
                    var nextOpt = parseInt(lastOpt.value) + 1;
                    var nextTxt = jbVars.txtOption+' '+nextOpt;
                    var newOpt = new Element('option', {
                       'selected' : 'selected',
                       'text' : nextTxt,
                       'value' : nextOpt
                   }).injectInside(currDefSel);
                   optsTxt.value =  nextTxt;
                   var currDefltBtn = paneDefsCont.getElement('span[id=odefault]');
                   currDefltBtn.removeClass('selected');
                   var delBtn = paneDefsCont.getElement('span[class=cremove]');
                   var currOptsCount = currDefSel.getElements('option').length;
                   if(currOptsCount == 1)
                    delBtn.setStyle('visibility', 'hidden');
                   if(currOptsCount > 1)
                    delBtn.setStyle('visibility', 'visible');
                   paneRadioInputs = paneRadios.getElements('input[type=radio]');
                   paneRadioInputs.each(function(r){
                        r.removeAttribute('defaultchecked');
                        r.removeAttribute('checked');
                     });
                   var newRadioLbl = nextTxt;
                   var newRadioName = cleanString(nextTxt);
                   var newRadioSpan = new Element('span', {
                       'class' : 'radio'
                   });
                   var newRadioOpt = new Element('input', {
                       'type' : 'radio',
                       'class' : 'right',
                       'name' : 'radio_value',
                       'checked' : 'checked',
                       'disabled' : 'disabled',
                       'id' : newRadioName,
                       'alt' : newRadioLbl,
                       'value' : nextOpt
                   }).injectInside(newRadioSpan);
                   var newRadioOptLbl = new Element('label', {
                       'for' : newRadioName,
                       'text' : newRadioLbl
                   }).injectBefore(newRadioOpt);
                   var radioHeight = parseInt(paneRadios.getFirst('span.radio').getStyle('height').replace('px', ''));
                   pHeight += radioHeight + 5;
                   el.setStyle('height', pHeight+'px');
                   edPaneSlide.slideIn('vertical').chain(function(){
                        newRadioSpan.injectInside(paneRadios)
                   });
                   optionObj = {value: nextOpt, label:nextTxt, id:newRadioName };
                   newFieldDefaults.deflt.options[nextOpt] = optionObj;
               }).injectBefore(paneAdmOnlyLbl);
               optValue.addEvents({
                     'keyup': function(c){
                          c = new Event(c).stop();
                          currDefOpt = currDefSel.getElement('option[value='+currDefSel.value+']');
                          currDefRadio = paneRadios.getElement('input[value='+currDefSel.value+']');
                          currDefRadioLbl = currDefRadio.getParent('span').getElement('label');
                          currDefOpt.text = this.value;
                          currDefRadioLbl.set('text', this.value);
                          newFieldDefaults.deflt.options[currDefSel.value].label = this.value;
                       },
                     'blur': function(c){
                          c = new Event(c).stop();
                          currDefOpt = currDefSel.getElement('option[value='+currDefSel.value+']');
                          currDefRadio = paneRadios.getElement('input[value='+currDefSel.value+']');
                          currDefRadioLbl = currDefRadio.getParent('span').getElement('label');
                          currDefOpt.text = this.value;
                          currDefRadioLbl.set('text', this.value);
                          newFieldDefaults.deflt.options[currDefSel.value].label = this.value;
                       }
                   }).injectBefore(paneAddCirc)
               paneDelCirc.addEvents({
                   'load' : function(l) {
                      l = new Event(l).stop();
                      var currOptsCount = currDefSel.getElements('option').length;
                      if(currOptsCount == 1)
                        l.setStyle('visibility', 'hidden');
                   },
                   'click': function(){
                      var currOptsCount = currDefSel.getElements('option').length;
                      if(currOptsCount == 1) {
                        this.setStyle('visibility', 'hidden');
                        return;
                      }
                      var optsTxt = paneDefsCont.getElement('input[id=opt_val]');
                      currDefOpt = currDefSel.getElement('option[value='+currDefSel.value+']');
                      currDefRadio = paneRadios.getElement('input[value='+currDefSel.value+']');
                      newFieldDefaults.deflt.options[currDefSel.value] = null;
                      currDefOpt.destroy();
                      var radioHeight = parseInt(paneRadios.getFirst('span.radio').getStyle('height').replace('px', ''));
                      pHeight -= radioHeight + 5;
                      el.setStyle('height', pHeight+'px');
                      edPaneSlide.slideIn('vertical').chain( function(){
                         currDefRadio.getParent('span').destroy();
                         paneRadioInputs = paneRadios.getElements('input[type=radio]');
                         paneRadioInputs.each(function(r){
                              r.removeAttribute('defaultchecked');
                              r.removeAttribute('checked');
                           });
                         currDefRadio = paneRadios.getElement('input[value='+currDefSel.value+']');
                         currDefRadio.set('checked', 'checked');
                      });
                      currDefOpt = currDefSel.getElement('option[value='+currDefSel.value+']');
                      optsTxt.value =  currDefOpt.text;
                    }
                   }).injectAfter(paneAddCirc);
               paneDefCirc.addEvent('click', function(e){
                      e = new Event(e).stop();
                      newFieldDefaults.deflt.defaultOpt = currDefSel.value;
                      var defaultSelector = paneDefsCont.getElement('span[id=odefault]');
                      defaultSelector.addClass('selected');
                  }).injectAfter(paneDefEl);

                 paneRadios.injectInside(defaultsPane);
            }
			this.slideIn('vertical');
		});
	};

	var addRow = function(obj, ctr) {
        if(obj.type == 'date'){
          if(parseInt(obj.deflt.defaultDay) > 31){
            return defaultsPane.getElement('input[name=default_day]').addClass('error').focus();
          }
          if(parseInt(obj.deflt.defaultMonth) == 2 && obj.deflt.defaultDay > 28) {
            defaultsPane.getElement('input[name=default_day]').addClass('error').focus();
            defaultsPane.getElement('select[name=default_month]').addEvent('click', function(){if(this.hasClass('error')) this.removeClass('error')}).addClass('error');
            return;
          }
        }
	    if(qSorter != null) {
          qSorter.detach();
        }
        qSorter = null;
		var elInp;
		switch (obj.type) {
		case 'checkbox':
			if (obj.deflt.value == 1) {
				elInp = new Element('input', {
					'type' : 'checkbox',
					'name' : obj.name,
					'id' : obj.name,
					'value' : 'yes',
					'disabled' : 'disabled',
					'checked' : 'checked'
				});
			} else if (obj.deflt.value == 0) {
				elInp = new Element('input', {
					'type' : 'checkbox',
					'name' : obj.name,
					'id' : obj.name,
					'disabled' : 'disabled',
					'value' : 'yes'
				});
			}
            elLbl = new Element('span', {
            	'class' : 'checkbox',
                'text': obj.deflt.label
			});
			break;
		case 'radio':
            Array.prototype.clean = function() {
                for (var i = 0; i < this.length; i++) {
                  if (this[i] == null) {
                    this.splice(i, 1);
                    i--;
                  }
                }
                return this;
            };
            obj.deflt.options.clean();
    		elInp = new Element('span', {
    			'class' : 'radios'
    		});
            var elOpts;
            var hgT = 0;
            obj.deflt.options.each(function(option) {
               hgT += 1;
               var radioSpan =  new Element('span', {
        			'class' : 'radio'
        		}).injectInside(elInp);
               var radioLbl =  new Element('label', {
        			'for' : obj.name+'_'+hgT,
                    'text' : option.label
        		}).injectInside(radioSpan);
               var defOpt = (option.value == obj.deflt.defaultOpt)? true : false;
               if(option != null){
                 if(defOpt == true) {
                    elOpts = new Element('input', {
            			'type' : 'radio',
            			'name' : obj.name,
                        'disabled' : 'disabled',
            			'id' : obj.name+'_'+hgT,
            			'value' : hgT,
            			'checked' : 'checked'
            		}).injectInside(radioSpan);
                 } else {
                    elOpts = new Element('input', {
            			'type' : 'radio',
                        'disabled' : 'disabled',
            			'name' : obj.name,
            			'id' : obj.name+'_'+hgT,
            			'value' : hgT
            		}).injectInside(radioSpan);
                 }
               }
            });
            elOpts = null;
			break;
		case 'text':
			elInp = new Element('input', {
				'type' : 'text',
				'name' : obj.name,
				'id' : obj.name,
				'disabled' : 'disabled',
				'value' : obj.deflt
			});
			break;
		case 'textarea':
			elInp = new Element('textarea', {
				'name' : obj.name,
				'id' : obj.name,
				'rows' : 1,
				'disabled' : 'disabled',
				'value' : obj.deflt
			});
			break;
		case 'select':
            Array.prototype.clean = function() {
                for (var i = 0; i < this.length; i++) {
                  if (this[i] == null) {
                    this.splice(i, 1);
                    i--;
                  }
                }
                return this;
            };
            obj.deflt.options.clean();
            if(obj.deflt.multiple == 1) {
                elInp = new Element('select', {
    				'name' : obj.name,
    				'multiple' : 'multiple',
    				'disabled' : 'disabled',
    				'id' : obj.name
    			});
            } else {
                elInp = new Element('select', {
    				'name' : obj.name,
    				'disabled' : 'disabled',
    				'id' : obj.name
    			});
            }
            var elOpts;
            if(obj.deflt.multiple == 1) var Mcount = 1;
            obj.deflt.options.each(function(option) {
               var defOpt = (option.value == obj.deflt.defaultOpt)? true : false;
               if(option != null){
                 elOpts = new Element('option', {
                       'text' : option.label,
                       'value' : option.value
                   });
                 if(defOpt == true) {
                   elOpts.setAttribute('selected', 'selected');
                 }
                 if(obj.deflt.multiple == 1) Mcount += 1;
               }
               elOpts.injectInside(elInp);
            });
            elOpts = null;
			break;
		case 'date':
			var newYearBox = new Element('input', {
			    'size': 4,
				'type' : 'text',
				'name' : obj.name+'[year]',
			   /*	'id' : obj.name+'[year]', */
				'disabled' : 'disabled',
				'value' : obj.deflt.defaultYear
			}).addEvent('keypress', function(y){
                 if((y.code == 8 || y.code == 46)) {}
                 else{
                   if (y.code < 48 || y.code > 57 || this.value.length >= 4) {
                      y = new Event(y).stop();
                   }
                 }
              });
             var newMonthSel = new Element('select',{
				 'name' : obj.name+'[month]',
				/* 'id' : obj.name+'[month]', */
    			 'disabled' : 'disabled'
             });
             var currMon = parseInt(obj.deflt.defaultMonth);
             for(m=0; m<12; m++) {
                 var theMonth = m+1;
                 var paddedMo = m < 9? '0'+theMonth : theMonth;
                 var newMo = new Element('option', {
                     'text' : jbVars.months[m],
                     'value' : paddedMo
                 });
                 if(theMonth == currMon)
                    newMo.setAttribute('selected', 'selected');
                 newMo.injectInside(newMonthSel);
             }
             var elInp = new Element('input',{
                 'type': 'text',
                 'size': 3,
    			 'disabled' : 'disabled',
                /* 'id': obj.name+'[day]', */
                 'name': obj.name+'[day]',
                 'value': obj.deflt.defaultDay
             }).addEvent('keypress', function(d){
                   if((d.code == 8 || d.code == 46)) {}
                   else{
                     if (d.code < 48 || d.code > 57 || this.value.length >= 2) {
                        d = new Event(d).stop();
                     }
                   }
                });
			break;
		}

		var lnkDel = new Element('a', {
			'class' : "del",
			'href' : '#',
			'title' : jbVars.txtDel
		}).addEvent('click', function(e) {
			e = new Event(e).stop();
			this.getParent('div').destroy();
			recalcElements();
		});
		var lnkEdit = new Element('a', {
			'class' : "ed",
			'href' : '#',
			'title' : jbVars.txtEdit
		});
		var newQRow = new Element('div', {
			'id' : 'qrow-' + ctr,
			'class' : 'qrow'
		});
		var qRowLbl = new Element('label', {
			'for' : obj.name
		}).set('text', obj.label).injectInside(newQRow);
        if(obj.restricted == 1)
            qRowLbl.addClass('restricted');
		lnkDel.injectInside(newQRow);
		lnkEdit.injectInside(newQRow);
		elInp.injectInside(newQRow);
        if(obj.type == 'radio') {
           newQRow.setStyle('min-height', 21*hgT)
        }
        if(obj.type == 'checkbox') {
          elLbl.injectAfter(elInp);
        }
        if(obj.type == 'select') {
          if(obj.deflt.multiple == 1)
            newQRow.setStyle('min-height', 18*Mcount)
        }
        if(obj.type == 'date') {
          newYearBox.injectBefore(elInp);
          newMonthSel.injectAfter(newYearBox);
          var newDayLbl = new Element('span', {
            'class' : 'datelbl',
            'text': jbVars.txtInclday
          }).injectAfter(elInp);
          var newMonthLbl = new Element('span', {
            'class' : 'datelbl',
            'text': jbVars.txtInclmonth
          }).injectAfter(newMonthSel);
          var newYearLbl = new Element('span', {
            'class' : 'datelbl',
            'text': jbVars.txtYear
          }).injectAfter(newYearBox);
          if(obj.deflt.showDay == 0){
              newDayLbl.addClass('displnone');
              elInp.addClass('displnone');
          }
          if(obj.deflt.showMonth == 0){
              newMonthLbl.addClass('displnone');
              newMonthSel.addClass('displnone');
          }
        }
        editInputs.getElements('input[type=text]').each(function(inp) {
            inp.value = '';
        });
        editInputs.getElement('span[id=fnametxt]').set('text', '');
        newQRow.injectInside(contQ);
		setActionElements();
		QuestionnaireData.fields.push(obj);
		QuestionnaireJson = JSON.encode(QuestionnaireData);
		document.qForm.elements["fields"].value = QuestionnaireJson;
		QuestionnaireJson = null;

        qSorter = new Sortables(contQ, {
	   	   opacity : 0.3,
           constrain: true,
           transition: Fx.Transitions.Elastic.easeOut,
           duration: 450 ,
           revert: { duration: 450, transition: 'elastic:out' },
		   onComplete : recalcElements
	    });
        windowScroll.toElement('fpanel', 'y').chain(function(){
		    newQRow.highlight('#FFFFBF');
        });
	};