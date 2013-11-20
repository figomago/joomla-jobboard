/*
Script: inlineEdit.js
	Make all Text elements Inline Editable

Dependancies:
	<Moo.js>, <Utility.js>, <Element.js>, <Function.js>, <Dom.js>, <Array.js>, <String.js>, <Tips.js>, <Event.js>, <Common.js>, <Window.Size.js>

Author:
	Justin Maier, <http://justinmaier.com>

License:
	MIT-style license.
*/

/*
Class: inlineEdit
	Make all the Text element within a specified Element, inline editable.

Note:
	Will only work on h1,h2,h3,h4,h5,li,dt,dd,p and elements given a specified class name.
	Tested in Firefox 2.0, and IE7 on XP SP2.
	
Known Bugs:
	Can't grab style from style sheet. The inline edit input box cant grab styles applied to unclassified elements in the css. a way to counter this is by doing the following:
		(p, .p{font-size:60px;border:0;}
	Must specify a border width of 0 if you dont want the borders to show on the input box.

Arguments: 
	element - The element that contains the Text Elements you want to be inline editable.
	options - an object. See options Below.

Options:
	onStart - Add additon affects to what happens when you start editing (like changing text color);
	onChange - Add additon affects to what happens when you finish editing (like changing text color);

	observingAction - The Action required to begin editing an element. (defaults to 'click')
	
	editingClass - The Class applied to the input/textarea element. (defaults to 'editLine')

	showIndicator - if set to true a tooltip will show that the element is editable.
	indicatorClass - the prefix for your tooltip class name. (defaults to 'editIndicator') 
		the whole tooltip will have as classname: editIndicator-tip
	indicatorText - the text to be displayed in the edit indicator. (defaults to 'editable')

Properties:
	toggle - Toggles Editablity on and off.
	deactivate - Disables inline editing.
	
Example:
	(start code)
	<div id="editarea">
		<h1>I am Justin</h1>
		<p>Hello World</p>
	</div>
	<script>
		var inlineEditor = new inlineEdit('editarea',{indicatorText: 'edit this'}); //Makes text inside 'editarea' inline editable
		inlineEditor.deactivate(); //Deactivates the ability to edit the text
		inlineEditor.toggle(); //Toggles the ability to edit the text (in this situation it would turn it back on since we just turned it off)
	</script>
	(end)
*/
var inlineEdit = new Class({
	getOptions: function(){
		return {
			onStart: function(){},
			onChange: function(){},
			observingAction: 'click',
			editingClass: 'editLine',
			showIndicator: true,
			indicatorClass: 'editIndicator',
			indicatorText: 'editable'
		};
	},
	editableRegion: 'editarea',
	active: false,
	searchFor: null,
	
	initialize: function(element, options){
		this.setOptions(this.getOptions(), options);
		this.editableRegion = element;
		this.searchFor = '#'+this.editableRegion+' h1, #'+this.editableRegion+' h2, #'+this.editableRegion+' h3, #'+this.editableRegion+' h4, #'+this.editableRegion+' h5, #'+this.editableRegion+' li, #'+this.editableRegion+' p, #'+this.editableRegion+' dt, #'+this.editableRegion+' dd, .'+this.editableRegion;
		$each($$(this.searchFor), function(el){
			this.build(el, this.options.observingAction);
		}, this);
		this.buildTips();
		this.active = true;
	},
	
	build: function(el,action){
		el.setStyle('cursor','pointer');
		el.removeEvents(action);
		el.addEvent(action, function(){$$('.'+this.options.indicatorClass+'-tip').each(function(tip){tip.remove();});this.prepareEditor(el);}.bindWithEvent(this));
	},
	
	prepareEditor: function(el){
		this.buildTips();
		this.edit = new Element('input');
		if(el.getTag() == 'p'||el.getTag() == 'li')this.edit = new Element('textarea').setStyle('overflow','auto');
		this.edit.setProperty('value', this.getLineBreaks(el.innerHTML));
		if(!this.edit.value)this.edit.setHTML(this.getLineBreaks(el.innerHTML));//Firefox Fix
		this.edit.setProperty('rel', el.getTag());
		this.edit.addClass(this.options.editingClass+' '+el.className+' '+el.getTag());
		this.setAllStyles(el,this.edit);
		this.edit.addEvent('change', function(event){
			this.onSave(this.edit);
		}.bindWithEvent(this));
		this.edit.addEvent('blur', function(event){
			this.onSave(this.edit);
		}.bindWithEvent(this));
		this.fireEvent('onStart', [this.edit]);
		this.edit.injectBefore(el);
		this.edit.focus();
		el.remove();
	},
	
	onSave: function(el){
		this.newEl = new Element(el.getProperty('rel')).setHTML(this.changeLineBreaks(el.value));
		el.removeClass(this.options.editingClass);
		el.removeClass(el.getProperty('rel'));
		this.newEl.addClass(el.className);
		this.setAllStyles(el,this.newEl);
		this.newEl.injectBefore(el);
		el.remove();
		this.fireEvent('onChange', [this.newEl]);
		$each($$(this.searchFor), function(el){
			this.build(el, this.options.observingAction);
		}, this);
		this.buildTips();
	},
	
	getLineBreaks: function(text) {
		var text = text.trim();
		return text.replace(new RegExp("<br>", "gi"), "\n");
	},
	changeLineBreaks: function(text) {
		var text = text.trim(); 
		return text.replace(/\n/gi, "<br />");
	},
	
	setAllStyles: function(prevel,el){
		var height = 'auto';
		if(el.getProperty('rel'))height = prevel.getStyle('height').toInt()-4+'px';
		if(el.getTag() == 'textarea')height = height.toInt()-2+'px';
		if(el.getProperty('rel') && window.ie )height = prevel.getStyle('height');
		if(prevel.getStyle('font'))el.setStyle('font', prevel.getStyle('font'));
		if(prevel.getStyle('font-size'))el.setStyle('font-size', prevel.getStyle('font-size'));
		if(prevel.getStyle('font-family'))el.setStyle('font-family', prevel.getStyle('font-family'));
		if(prevel.getStyle('font-weight'))el.setStyle('font-weight', prevel.getStyle('font-weight'));
		if(prevel.getStyle('line-height'))el.setStyle('line-height', prevel.getStyle('line-height'));
		if(prevel.getStyle('letter-spacing'))el.setStyle('letter-spacing', prevel.getStyle('letter-spacing'));
		if(prevel.getStyle('list-style'))el.setStyle('list-style', prevel.getStyle('list-style'));
		if(prevel.getStyle('padding'))el.setStyle('padding', prevel.getStyle('padding'));
		if(prevel.getStyle('margin'))el.setStyle('margin', prevel.getStyle('margin'));
		if(prevel.getStyle('height'))el.setStyle('height',height);
		if(prevel.getStyle('width'))el.setStyle('width', prevel.getStyle('width'));
		if(prevel.getStyle('border'))el.setStyle('border', prevel.getStyle('border'));
		if(prevel.getStyle('border-color'))el.setStyle('border-color', prevel.getStyle('border-color'));
		if(prevel.getStyle('border-size'))el.setStyle('border-size', prevel.getStyle('border-size'));
		if(prevel.getStyle('border-left'))el.setStyle('border-left', prevel.getStyle('border-left'));
		if(prevel.getStyle('border-right'))el.setStyle('border-right', prevel.getStyle('border-right'));
		if(prevel.getStyle('border-top'))el.setStyle('border-top', prevel.getStyle('border-top'));
		if(prevel.getStyle('border-bottom'))el.setStyle('height', prevel.getStyle('border-bottom'));
		if(prevel.getStyle('color'))el.setStyle('color', prevel.getStyle('color'));
		if(prevel.getStyle('background'))el.setStyle('background', prevel.getStyle('background'));
	},
	
	buildTips: function(){
		if(this.options.showIndicator == true){
			$$('.'+this.options.indicatorClass+'-tip').each(function(tip){tip.remove();});//Kill Old Tips
			$each($$(this.searchFor), function(el){
				el.setProperty('title',this.options.indicatorText);
			}, this);
			new Tips($$(this.searchFor), {
				className: this.options.indicatorClass,
				offsets: {'x': 16, 'y': 5},
				showDelay: 0,
				hideDelay: 0,
				timeOut: 2000
			});
		}
	},
	
	deactivate: function(){
		$each($$(this.searchFor), function(el){
			var newel = new Element(el.getTag());
			newel.setHTML(el.innerHTML);
			newel.className = el.className;
			newel.setProperty('id',el.id);
			this.setAllStyles(el,newel);
			newel.injectBefore(el);
			el.remove();
		}, this);
		this.active = false;
	},
	
	toggle: function(){
		if(this.active != true){this.initialize(this.editableRegion,this.options);}else{this.deactivate();}
	}

});

inlineEdit.implement(new Events);
inlineEdit.implement(new Options);