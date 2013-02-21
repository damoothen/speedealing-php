// Copyright (C) 2005-2010 Laurent Destailleur  <eldy@users.sourceforge.net>
// Copyright (C) 2005-2012 Regis Houssin        <regis.houssin@capnetworks.com>
//
// This program is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 3 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program. If not, see <http://www.gnu.org/licenses/>.
// or see http://www.gnu.org/

//
// \file       htdocs/core/js/lib_head.js
// \brief      File that include javascript functions (included if option use_javascript activated)
//

// Returns an object given an id
function getObjectFromID(id){
	var theObject;
	if(document.getElementById)
		theObject=document.getElementById(id);
	else
		theObject=document.all[id];
	return theObject;
}

// This Function returns the top position of an object
function getTop(theitem){
	var offsetTrail = theitem;
	var offsetTop = 0;
	while (offsetTrail) {
		offsetTop += offsetTrail.offsetTop;
		offsetTrail = offsetTrail.offsetParent;
	}
	if (navigator.userAgent.indexOf("Mac") != -1 && typeof document.body.leftMargin != "undefined") 
		offsetLeft += document.body.TopMargin;
	return offsetTop;
}

// This Function returns the left position of an object
function getLeft(theitem){
	var offsetTrail = theitem;
	var offsetLeft = 0;
	while (offsetTrail) {
		offsetLeft += offsetTrail.offsetLeft;
		offsetTrail = offsetTrail.offsetParent;
	}
	if (navigator.userAgent.indexOf("Mac") != -1 && typeof document.body.leftMargin != "undefined") 
		offsetLeft += document.body.leftMargin;
	return offsetLeft;
}


// Create XMLHttpRequest object and load url
// Used by calendar or other ajax processes
// Return req built or false if error
function loadXMLDoc(url,readyStateFunction,async) 
{
	// req must be defined by caller with
	// var req = false;
 
	// branch for native XMLHttpRequest object (Mozilla, Safari...)
	if (window.XMLHttpRequest)
	{
		req = new XMLHttpRequest();
		
	// if (req.overrideMimeType) {
	// req.overrideMimeType('text/xml');
	// }
	}
	// branch for IE/Windows ActiveX version
	else if (window.ActiveXObject)
	{
		try
		{
			req = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e)
		{
			try {
				req = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) {}
		} 
	}

	// If XMLHttpRequestObject req is ok, call URL
	if (! req)
	{
		alert('Cannot create XMLHTTP instance');
		return false;
	}

	if (readyStateFunction) req.onreadystatechange = readyStateFunction;
	// Exemple of function for readyStateFuncyion:
	// function ()
	// {
	// if ( (req.readyState == 4) && (req.status == 200) ) {
	// if (req.responseText == 1) { newStatus = 'AAA'; }
	// if (req.responseText == 0) { newStatus = 'BBB'; }
	// if (currentStatus != newStatus) {
	// if (newStatus == "AAA") { obj.innerHTML = 'AAA'; }
	// else { obj.innerHTML = 'BBB'; }
	// currentStatus = newStatus;
	// }
	// }
	// }
	req.open("GET", url, async);
	req.send(null);
	return req;
}

// To hide/show select Boxes with IE6 (and only IE6 because IE6 has a bug and
// not put popup completely on the front)
function hideSelectBoxes() {
	var brsVersion = parseInt(window.navigator.appVersion.charAt(0), 10);
	if (brsVersion <= 6 && window.navigator.userAgent.indexOf("MSIE 6") > -1) 
	{  
		for(var i = 0; i < document.all.length; i++) 
		{
			if(document.all[i].tagName)
				if(document.all[i].tagName == "SELECT")
					document.all[i].style.visibility="hidden";
		}
	}
}
function displaySelectBoxes() {
	var brsVersion = parseInt(window.navigator.appVersion.charAt(0), 10);
	if (brsVersion <= 6 && window.navigator.userAgent.indexOf("MSIE 6") > -1) 
	{  
		for(var i = 0; i < document.all.length; i++) 
		{
			if(document.all[i].tagName)
				if(document.all[i].tagName == "SELECT")
					document.all[i].style.visibility="visible";
		}
	}
}



/*
 * ================================================================= 
 * Function:
 * formatDate (javascript object Date(), format) Purpose: Returns a date in the
 * output format specified. The format string can use the following tags: Field |
 * Tags -------------+------------------------------- Year | yyyy (4 digits), yy
 * (2 digits) Month | MM (2 digits) Day of Month | dd (2 digits) Hour (1-12) |
 * hh (2 digits) Hour (0-23) | HH (2 digits) Minute | mm (2 digits) Second | ss
 * (2 digits) Author: Laurent Destailleur Author: Matelli (see
 * http://matelli.fr/showcases/patchs-dolibarr/update-date-input-in-action-form.html)
 * Licence: GPL
 * ==================================================================
 */
function formatDate(date,format)
{
	// alert('formatDate date='+date+' format='+format);
	
	// Force parametres en chaine
	format=format+"";
	
	var result="";

	var year=date.getYear()+"";
	if (year.length < 4) {
		year=""+(year-0+1900);
	}
	var month=date.getMonth()+1;
	var day=date.getDate();
	var hour=date.getHours();
	var minute=date.getMinutes();
	var seconde=date.getSeconds();

	var i=0;
	while (i < format.length)
	{
		c=format.charAt(i);	// Recupere char du format
		substr="";
		j=i;
		while ((format.charAt(j)==c) && (j < format.length))	// Recupere char
		// successif
		// identiques
		{
			substr += format.charAt(j++);
		}

		// alert('substr='+substr);
		if (substr == 'yyyy')      {
			result=result+year;
		}
		else if (substr == 'yy')   {
			result=result+year.substring(2,4);
		}
		else if (substr == 'M')    {
			result=result+month;
		}
		else if (substr == 'MM')   {
			result=result+(month<1||month>9?"":"0")+month;
		}
		else if (substr == 'd')    {
			result=result+day;
		}
		else if (substr == 'dd')   {
			result=result+(day<1||day>9?"":"0")+day;
		}
		else if (substr == 'hh')   {
			if (hour > 12) hour-=12;
			result=result+(hour<0||hour>9?"":"0")+hour;
		}
		else if (substr == 'HH')   {
			result=result+(hour<0||hour>9?"":"0")+hour;
		}
		else if (substr == 'mm')   {
			result=result+(minute<0||minute>9?"":"0")+minute;
		}
		else if (substr == 'ss')   {
			result=result+(seconde<0||seconde>9?"":"0")+seconde;
		}
		else {
			result=result+substr;
		}
		
		i+=substr.length;
	}

	// alert(result);
	return result;
}


/*
 * ================================================================= 
 * Function:
 * getDateFromFormat(date_string, format_string) Purpose: This function takes a
 * date string and a format string. It parses the date string with format and it
 * returns the date as a javascript Date() object. If date does not match
 * format, it returns 0. The format string can use the following tags: 
 * Field        | Tags
 * -------------+-----------------------------------
 * Year         | yyyy (4 digits), yy (2 digits) 
 * Month        | MM (2 digits) 
 * Day of Month | dd (2 digits) 
 * Hour (1-12)  | hh (2 digits) 
 * Hour (0-23)  | HH (2 digits) 
 * Minute       | mm (2 digits) 
 * Second       | ss (2 digits)
 * Author: Laurent Destailleur 
 * Licence: GPL
 * ==================================================================
 */
function getDateFromFormat(val,format)
{
	// alert('getDateFromFormat val='+val+' format='+format);

	// Force parametres en chaine
	val=val+"";
	format=format+"";

	if (val == '') return 0;
	
	var now=new Date();
	var year=now.getYear();
	if (year.length < 4) {
		year=""+(year-0+1900);
	}
	var month=now.getMonth()+1;
	var day=now.getDate();
	var hour=now.getHours();
	var minute=now.getMinutes();
	var seconde=now.getSeconds();

	var i=0;
	var d=0;    // -d- follows the date string while -i- follows the format
	// string

	while (i < format.length)
	{
		c=format.charAt(i);	// Recupere char du format
		substr="";
		j=i;
		while ((format.charAt(j)==c) && (j < format.length))	// Recupere char
		// successif
		// identiques
		{
			substr += format.charAt(j++);
		}

		// alert('substr='+substr);
		if (substr == "yyyy") year=getIntegerInString(val,d,4,4); 
		if (substr == "yy")   year=""+(getIntegerInString(val,d,2,2)-0+1900); 
		if (substr == "MM" ||substr == "M") 
		{ 
			month=getIntegerInString(val,d,1,2); 
			d -= 2- month.length; 
		} 
		if (substr == "dd") 
		{ 
			day=getIntegerInString(val,d,1,2); 
			d -= 2- day.length; 
		} 
		if (substr == "HH" ||substr == "hh" ) 
		{ 
			hour=getIntegerInString(val,d,1,2); 
			d -= 2- hour.length; 
		} 
		if (substr == "mm"){ 
			minute=getIntegerInString(val,d,1,2); 
			d -= 2- minute.length; 
		} 
		if (substr == "ss") 
		{ 
			seconde=getIntegerInString(val,d,1,2); 
			d -= 2- seconde.length; 
		} 
	
		i+=substr.length;
		d+=substr.length;
	}
	
	// Check if format param are ok
	if (year==null||year<1) {
		return 0;
	}
	if (month==null||(month<1)||(month>12)) {
		return 0;
	}
	if (day==null||(day<1)||(day>31)) {
		return 0;
	}
	if (hour==null||(hour<0)||(hour>24)) {
		return 0;
	}
	if (minute==null||(minute<0)||(minute>60)) {
		return 0;
	}
	if (seconde==null||(seconde<0)||(seconde>60)) {
		return 0;
	}
		
	// alert(year+' '+month+' '+day+' '+hour+' '+minute+' '+seconde);
	var newdate=new Date(year,month-1,day,hour,minute,seconde);

	return newdate;
}

/*
 * ================================================================= 
 * Function:
 * stringIsInteger(string) 
 * Purpose: Return true if string is an integer
 * ==================================================================
 */
function stringIsInteger(str)
{
	var digits="1234567890";
	for (var i=0; i < str.length; i++)
	{
		if (digits.indexOf(str.charAt(i))==-1)
		{
			return false;
		}
	}
	return true;
}

/*
 * ================================================================= 
 * Function:
 * getIntegerInString(string,pos,minlength,maxlength) 
 * Purpose: Return part of string from position i that is integer
 * ==================================================================
 */
function getIntegerInString(str,i,minlength,maxlength)
{
	for (var x=maxlength; x>=minlength; x--)
	{
		var substr=str.substring(i,i+x);
		if (substr.length < minlength) {
			return null;
		}
		if (stringIsInteger(substr)) {
			return substr;
		}
	}
	return null;
}


/*
 * ================================================================= 
 * Purpose:
 * Clean string to have it url encoded 
 * Input: s 
 * Author: Laurent Destailleur
 * Licence: GPL
 * ==================================================================
 */
function urlencode(s) {
	news=s;
	news=news.replace(/\+/gi,'%2B');
	news=news.replace(/&/gi,'%26');
	return news;
}


/*
 * ================================================================= 
 * Purpose: Show a popup HTML page. 
 * Input:   url,title 
 * Author:  Laurent Destailleur 
 * Licence: GPL 
 * ==================================================================
 */
function newpopup(url,title) {
	var argv = newpopup.arguments;
	var argc = newpopup.arguments.length;
	tmp=url;
	var l = (argc > 2) ? argv[2] : 600;
	var h = (argc > 3) ? argv[3] : 400;
	var wfeatures="directories=0,menubar=0,status=0,resizable=0,scrollbars=1,toolbar=0,width="+l+",height="+h+",left=" + eval("(screen.width - l)/2") + ",top=" + eval("(screen.height - h)/2");
	fen=window.open(tmp,title,wfeatures);
	return false;
}


/*
 * ================================================================= 
 * Purpose:
 * Applique un delai avant execution. Used for autocompletion of companies.
 * Input:   funct, delay 
 * Author:  Regis Houssin 
 * Licence: GPL
 * ==================================================================
 */
function ac_delay(funct,delay) {
	// delay before start of action
	setTimeout(funct,delay);
}


/*
 * ================================================================= 
 * Purpose:
 * Clean values of a "Sortable.serialize". Used by drag and drop.
 * Input:   expr 
 * Author:  Regis Houssin 
 * Licence: GPL
 * ==================================================================
 */
function cleanSerialize(expr) {
	if (typeof(expr) != 'string') return '';
	var reg = new RegExp("(&)", "g");
	var reg2 = new RegExp("[^A-Z0-9,]", "g");
	var liste1 = expr.replace(reg, ",");
	var liste = liste1.replace(reg2, "");
	return liste;
}


/*
 * ================================================================= 
 * Purpose: Display a temporary message in input text fields (For showing help message on
 *          input field).
 * Input:   fieldId
 * Input:   message
 * Author:  Regis Houssin 
 * Licence: GPL
 * ==================================================================
 */
function displayMessage(fieldId,message) {
	var textbox = document.getElementById(fieldId);
	if (textbox.value == '') {
		textbox.style.color = 'grey';
		textbox.value = message;
	}
}

/*
 * ================================================================= 
 * Purpose: Hide a temporary message in input text fields (For showing help message on
 *          input field). 
 * Input:   fiedId 
 * Input:   message 
 * Author:  Regis Houssin
 * Licence: GPL
 * ==================================================================
 */
function hideMessage(fieldId,message) {
	var textbox = document.getElementById(fieldId);
	textbox.style.color = 'black';
	if (textbox.value == message) textbox.value = '';
}

/*
 * 
 */
function setConstant(url, code, input, entity) {
	$.get( url, {
		action: "set",
		name: code,
		entity: entity
	},
	function() {
		$("#set_" + code).hide();
		$("#del_" + code).show();
		$.each(input, function(type, data) {
			// Enable another element
			if (type == "disabled") {
				$.each(data, function(key, value) {
					$("#" + value).removeAttr("disabled");
					if ($("#" + value).hasClass("butActionRefused") == true) {
						$("#" + value).removeClass("butActionRefused");
						$("#" + value).addClass("butAction");
					}
				});
			// Show another element
			} else if (type == "showhide" || type == "show") {
				$.each(data, function(key, value) {
					$("#" + value).show();
				});
			// Set another constant
			} else if (type == "set") {
				$.each(data, function(key, value) {
					$("#set_" + key).hide();
					$("#del_" + key).show();
					$.get( url, {
						action: "set",
						name: key,
						value: value,
						entity: entity
					});
				});
			}
		});
	});
}

/*
 * 
 */
function delConstant(url, code, input, entity) {
	$.get( url, {
		action: "del",
		name: code,
		entity: entity
	},
	function() {
		$("#del_" + code).hide();
		$("#set_" + code).show();
		$.each(input, function(type, data) {
			// Disable another element
			if (type == "disabled") {
				$.each(data, function(key, value) {
					$("#" + value).attr("disabled", true);
					if ($("#" + value).hasClass("butAction") == true) {
						$("#" + value).removeClass("butAction");
						$("#" + value).addClass("butActionRefused");
					}
				});
			// Hide another element
			} else if (type == "showhide" || type == "hide") {
				$.each(data, function(key, value) {
					$("#" + value).hide();
				});
			// Delete another constant
			} else if (type == "del") {
				$.each(data, function(key, value) {
					$("#del_" + value).hide();
					$("#set_" + value).show();
					$.get( url, {
						action: "del",
						name: value,
						entity: entity
					});
				});
			}
		});
	});
}

/*
 * 
 */
function confirmConstantAction(action, url, code, input, box, entity, yesButton, noButton) {
	$("#confirm_" + code)
	.attr("title", box.title)
	.html(box.content)
	.dialog({
		resizable: false,
		height: 170,
		width: 500,
		modal: true,
		buttons: [
		{
			text : yesButton,
			click : function() {
				if (action == "set") {
					setConstant(url, code, input, entity);
				} else if (action == "del") {
					delConstant(url, code, input, entity);
				}
				// Close dialog
				$(this).dialog("close");
				// Execute another function
				if (box.function) {
					var fnName = box.function;
					if (window.hasOwnProperty(fnName)) {
						window[fnName]();
					}
				}
			}
		},
		{
			text : noButton,
			click : function() {
				$(this).dialog("close");
			}
		}
		]
	});
}

/*
 * box actions (show/hide, remove)
 */
prth_box_actions = {
	init: function() {
		$('.box_actions').each(function() {
			$(this).append('<span class="bAct_hide"><img src="theme/blank.gif" class="bAct_x" alt="" /></span>');
			$(this).append('<span class="bAct_toggle"><img src="theme/blank.gif" class="bAct_minus" alt="" /></span>');
			$(this).find('.bAct_hide').on('click', function() {
				$(this).closest('.box_c').fadeOut('slow',function() {
					$(this).remove();
				});
			});
			$(this).find('.bAct_toggle').on('click', function() {
				if( $(this).closest('.box_c_heading').next('.box_c_content').is(':visible') ) {
					$(this).closest('.box_c_heading').next('.box_c_content').slideUp('slow',function() {
					});
					$(this).html('<img src="theme/blank.gif" class="bAct_plus" alt="" />');
				} else {
					$(this).closest('.box_c_heading').next('.box_c_content').slideDown('slow',function() {
					});
					$(this).html('<img src="theme/blank.gif" class="bAct_minus" alt="" />');
				}
			});
		});
	}
};

//* jQuery tools tabs
/*prth_tabs = {
	init: function() {
		$(".tabs").flowtabs(".box_c_content > .tab_pane");
	}
};*/

//* infinite tabs (jQuery UI tabs)
prth_infinite_tabs = {
	init: function() {
		$(".ui_tabs").tabs({
			scrollable: true
		});
	}
};

/* 
 * Timer for delayed keyup function
 */
(function($){
	$.widget("ui.onDelayedKeyup", {
		_init : function() {
			var self = this;
			$(this.element).bind('keyup input', function() {
				if(typeof(window['inputTimeout']) != "undefined"){
					window.clearTimeout(inputTimeout);
				}  
				var handler = self.options.handler;
				window['inputTimeout'] = window.setTimeout(function() {
					handler.call(self.element)
				}, self.options.delay);
			});
		},
		options: {
			handler: $.noop(),
			delay: 500
		}
	});
})(jQuery);

/*
 * jQuery Autocomplete plugin 1.2.2
 * Copyright (c) 2009 Jörn Zaefferer
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 * With small modifications by Alfonso Gómez-Arzola.
 * See changelog for details.
 *
 */

;(function($){$.fn.extend({sautocomplete:function(urlOrData,options){var isUrl=typeof urlOrData=="string";options=$.extend({},$.Autocompleter.defaults,{url:isUrl?urlOrData:null,data:isUrl?null:urlOrData,delay:isUrl?$.Autocompleter.defaults.delay:10,max:options&&!options.scroll?10:150},options);options.highlight=options.highlight||function(value){return value};options.formatMatch=options.formatMatch||options.formatItem;return this.each(function(){new $.Autocompleter(this,options)})},result:function(handler){return this.bind("result",handler)},search:function(handler){return this.trigger("search",[handler])},flushCache:function(){return this.trigger("flushCache")},setOptions:function(options){return this.trigger("setOptions",[options])},unautocomplete:function(){return this.trigger("unautocomplete")}});$.Autocompleter=function(input,options){var KEY={UP:38,DOWN:40,DEL:46,TAB:9,RETURN:13,ESC:27,COMMA:188,PAGEUP:33,PAGEDOWN:34,BACKSPACE:8};var globalFailure=null;if(options.failure!=null&&typeof options.failure=="function"){globalFailure=options.failure}var $input=$(input).attr("autocomplete","off").addClass(options.inputClass);var timeout;var previousValue="";var cache=$.Autocompleter.Cache(options);var hasFocus=0;var lastKeyPressCode;var config={mouseDownOnSelect:false};var select=$.Autocompleter.Select(options,input,selectCurrent,config);var blockSubmit;$.browser.opera&&$(input.form).bind("submit.autocomplete",function(){if(blockSubmit){blockSubmit=false;return false}});$input.bind(($.browser.opera?"keypress":"keydown")+".autocomplete",function(event){hasFocus=1;lastKeyPressCode=event.keyCode;switch(event.keyCode){case KEY.UP:if(select.visible()){event.preventDefault();select.prev()}else{onChange(0,true)}break;case KEY.DOWN:if(select.visible()){event.preventDefault();select.next()}else{onChange(0,true)}break;case KEY.PAGEUP:if(select.visible()){event.preventDefault();select.pageUp()}else{onChange(0,true)}break;case KEY.PAGEDOWN:if(select.visible()){event.preventDefault();select.pageDown()}else{onChange(0,true)}break;case options.multiple&&$.trim(options.multipleSeparator)==","&&KEY.COMMA:case KEY.TAB:case KEY.RETURN:if(selectCurrent()){event.preventDefault();blockSubmit=true;return false}break;case KEY.ESC:select.hide();break;default:clearTimeout(timeout);timeout=setTimeout(onChange,options.delay);break}}).focus(function(){hasFocus++}).blur(function(){hasFocus=0;if(!config.mouseDownOnSelect){hideResults()}}).click(function(){if(options.clickFire){if(!select.visible()){onChange(0,true)}}else{if(hasFocus++>1&&!select.visible()){onChange(0,true)}}}).bind("search",function(){var fn=(arguments.length>1)?arguments[1]:null;function findValueCallback(q,data){var result;if(data&&data.length){for(var i=0;i<data.length;i++){if(data[i].result.toLowerCase()==q.toLowerCase()){result=data[i];break}}}if(typeof fn=="function")fn(result);else $input.trigger("result",result&&[result.data,result.value])}$.each(trimWords($input.val()),function(i,value){request(value,findValueCallback,findValueCallback)})}).bind("flushCache",function(){cache.flush()}).bind("setOptions",function(){$.extend(true,options,arguments[1]);if("data"in arguments[1])cache.populate()}).bind("unautocomplete",function(){select.unbind();$input.unbind();$(input.form).unbind(".autocomplete")});function selectCurrent(){var selected=select.selected();if(!selected)return false;var v=selected.result;previousValue=v;if(options.multiple){var words=trimWords($input.val());if(words.length>1){var seperator=options.multipleSeparator.length;var cursorAt=$(input).selection().start;var wordAt,progress=0;$.each(words,function(i,word){progress+=word.length;if(cursorAt<=progress){wordAt=i;return false}progress+=seperator});words[wordAt]=v;v=words.join(options.multipleSeparator)}v+=options.multipleSeparator}$input.val(v);hideResultsNow();$input.trigger("result",[selected.data,selected.value]);return true}function onChange(crap,skipPrevCheck){if(lastKeyPressCode==KEY.DEL){select.hide();return}var currentValue=$input.val();if(!skipPrevCheck&&currentValue==previousValue)return;previousValue=currentValue;currentValue=lastWord(currentValue);if(currentValue.length>=options.minChars){$input.addClass(options.loadingClass);if(!options.matchCase)currentValue=currentValue.toLowerCase();request(currentValue,receiveData,hideResultsNow)}else{stopLoading();select.hide()}};function trimWords(value){if(!value)return[""];if(!options.multiple)return[$.trim(value)];return $.map(value.split(options.multipleSeparator),function(word){return $.trim(value).length?$.trim(word):null})}function lastWord(value){if(!options.multiple)return value;var words=trimWords(value);if(words.length==1)return words[0];var cursorAt=$(input).selection().start;if(cursorAt==value.length){words=trimWords(value)}else{words=trimWords(value.replace(value.substring(cursorAt),""))}return words[words.length-1]}function autoFill(q,sValue){if(options.autoFill&&(lastWord($input.val()).toLowerCase()==q.toLowerCase())&&lastKeyPressCode!=KEY.BACKSPACE){$input.val($input.val()+sValue.substring(lastWord(previousValue).length));$(input).selection(previousValue.length,previousValue.length+sValue.length)}};function hideResults(){clearTimeout(timeout);timeout=setTimeout(hideResultsNow,200)};function hideResultsNow(){var wasVisible=select.visible();select.hide();clearTimeout(timeout);stopLoading();if(options.mustMatch){$input.search(function(result){if(!result){if(options.multiple){var words=trimWords($input.val()).slice(0,-1);$input.val(words.join(options.multipleSeparator)+(words.length?options.multipleSeparator:""))}else{$input.val("");$input.trigger("result",null)}}})}};function receiveData(q,data){if(data&&data.length&&hasFocus){stopLoading();select.display(data,q);autoFill(q,data[0].value);select.show()}else{hideResultsNow()}};function request(term,success,failure){if(!options.matchCase)term=term.toLowerCase();var data=cache.load(term);if(data&&data.length){success(term,data)}else if((typeof options.url=="string")&&(options.url.length>0)){var extraParams={timestamp:+new Date()};$.each(options.extraParams,function(key,param){extraParams[key]=typeof param=="function"?param():param});$.ajax({mode:"abort",port:"autocomplete"+input.name,dataType:options.dataType,url:options.url,data:$.extend({q:lastWord(term),limit:options.max},extraParams),success:function(data){var parsed=options.parse&&options.parse(data)||parse(data);cache.add(term,parsed);success(term,parsed)}})}else{select.emptyList();if(globalFailure!=null){globalFailure()}else{failure(term)}}};function parse(data){var parsed=[];var rows=data.split("\n");for(var i=0;i<rows.length;i++){var row=$.trim(rows[i]);if(row){row=row.split("|");parsed[parsed.length]={data:row,value:row[0],result:options.formatResult&&options.formatResult(row,row[0])||row[0]}}}return parsed};function stopLoading(){$input.removeClass(options.loadingClass)}};$.Autocompleter.defaults={inputClass:"ac_input",resultsClass:"ac_results",loadingClass:"ac_loading",minChars:1,delay:400,matchCase:false,matchSubset:true,matchContains:false,cacheLength:100,max:1000,mustMatch:false,extraParams:{},selectFirst:true,formatItem:function(row){return row[0]},formatMatch:null,autoFill:false,width:0,multiple:false,multipleSeparator:" ",inputFocus:true,clickFire:false,highlight:function(value,term){return value.replace(new RegExp("(?![^&;]+;)(?!<[^<>]*)("+term.replace(/([\^\$\(\)\[\]\{\}\*\.\+\?\|\\])/gi,"\\$1")+")(?![^<>]*>)(?![^&;]+;)","gi"),"<strong>$1</strong>")},scroll:true,scrollHeight:180,scrollJumpPosition:true};$.Autocompleter.Cache=function(options){var data={};var length=0;function matchSubset(s,sub){if(!options.matchCase)s=s.toLowerCase();var i=s.indexOf(sub);if(options.matchContains=="word"){i=s.toLowerCase().search("\\b"+sub.toLowerCase())}if(i==-1)return false;return i==0||options.matchContains};function add(q,value){if(length>options.cacheLength){flush()}if(!data[q]){length++}data[q]=value}function populate(){if(!options.data)return false;var stMatchSets={},nullData=0;if(!options.url)options.cacheLength=1;stMatchSets[""]=[];for(var i=0,ol=options.data.length;i<ol;i++){var rawValue=options.data[i];rawValue=(typeof rawValue=="string")?[rawValue]:rawValue;var value=options.formatMatch(rawValue,i+1,options.data.length);if(value===false)continue;var firstChar=value.charAt(0).toLowerCase();if(!stMatchSets[firstChar])stMatchSets[firstChar]=[];var row={value:value,data:rawValue,result:options.formatResult&&options.formatResult(rawValue)||value};stMatchSets[firstChar].push(row);if(nullData++<options.max){stMatchSets[""].push(row)}};$.each(stMatchSets,function(i,value){options.cacheLength++;add(i,value)})}setTimeout(populate,25);function flush(){data={};length=0}return{flush:flush,add:add,populate:populate,load:function(q){if(!options.cacheLength||!length)return null;if(!options.url&&options.matchContains){var csub=[];for(var k in data){if(k.length>0){var c=data[k];$.each(c,function(i,x){if(matchSubset(x.value,q)){csub.push(x)}})}}return csub}else if(data[q]){return data[q]}else if(options.matchSubset){for(var i=q.length-1;i>=options.minChars;i--){var c=data[q.substr(0,i)];if(c){var csub=[];$.each(c,function(i,x){if(matchSubset(x.value,q)){csub[csub.length]=x}});return csub}}}return null}}};$.Autocompleter.Select=function(options,input,select,config){var CLASSES={ACTIVE:"ac_over"};var listItems,active=-1,data,term="",needsInit=true,element,list;function init(){if(!needsInit)return;element=$("<div/>").hide().addClass(options.resultsClass).css("position","absolute").appendTo(document.body).hover(function(event){if($(this).is(":visible")){input.focus()}config.mouseDownOnSelect=false});list=$("<ul/>").appendTo(element).mouseover(function(event){if(target(event).nodeName&&target(event).nodeName.toUpperCase()=='LI'){active=$("li",list).removeClass(CLASSES.ACTIVE).index(target(event));$(target(event)).addClass(CLASSES.ACTIVE)}}).click(function(event){$(target(event)).addClass(CLASSES.ACTIVE);select();if(options.inputFocus)input.focus();return false}).mousedown(function(){config.mouseDownOnSelect=true}).mouseup(function(){config.mouseDownOnSelect=false});if(options.width>0)element.css("width",options.width);needsInit=false}function target(event){var element=event.target;while(element&&element.tagName!="LI")element=element.parentNode;if(!element)return[];return element}function moveSelect(step){listItems.slice(active,active+1).removeClass(CLASSES.ACTIVE);movePosition(step);var activeItem=listItems.slice(active,active+1).addClass(CLASSES.ACTIVE);if(options.scroll){var offset=0;listItems.slice(0,active).each(function(){offset+=this.offsetHeight});if((offset+activeItem[0].offsetHeight-list.scrollTop())>list[0].clientHeight){list.scrollTop(offset+activeItem[0].offsetHeight-list.innerHeight())}else if(offset<list.scrollTop()){list.scrollTop(offset)}}};function movePosition(step){if(options.scrollJumpPosition||(!options.scrollJumpPosition&&!((step<0&&active==0)||(step>0&&active==listItems.size()-1)))){active+=step;if(active<0){active=listItems.size()-1}else if(active>=listItems.size()){active=0}}}function limitNumberOfItems(available){return options.max&&options.max<available?options.max:available}function fillList(){list.empty();var max=limitNumberOfItems(data.length);for(var i=0;i<max;i++){if(!data[i])continue;var formatted=options.formatItem(data[i].data,i+1,max,data[i].value,term);if(formatted===false)continue;var li=$("<li/>").html(options.highlight(formatted,term)).addClass(i%2==0?"ac_even":"ac_odd").appendTo(list)[0];$.data(li,"ac_data",data[i])}listItems=list.find("li");if(options.selectFirst){listItems.slice(0,1).addClass(CLASSES.ACTIVE);active=0}if($.fn.bgiframe)list.bgiframe()}return{display:function(d,q){init();data=d;term=q;fillList()},next:function(){moveSelect(1)},prev:function(){moveSelect(-1)},pageUp:function(){if(active!=0&&active-8<0){moveSelect(-active)}else{moveSelect(-8)}},pageDown:function(){if(active!=listItems.size()-1&&active+8>listItems.size()){moveSelect(listItems.size()-1-active)}else{moveSelect(8)}},hide:function(){element&&element.hide();listItems&&listItems.removeClass(CLASSES.ACTIVE);active=-1},visible:function(){return element&&element.is(":visible")},current:function(){return this.visible()&&(listItems.filter("."+CLASSES.ACTIVE)[0]||options.selectFirst&&listItems[0])},show:function(){var offset=$(input).offset();element.css({width:typeof options.width=="string"||options.width>0?options.width:$(input).width(),top:offset.top+input.offsetHeight,left:offset.left}).show();if(options.scroll){list.scrollTop(0);list.css({maxHeight:options.scrollHeight,overflow:'auto'});if($.browser.msie&&typeof document.body.style.maxHeight==="undefined"){var listHeight=0;listItems.each(function(){listHeight+=this.offsetHeight});var scrollbarsVisible=listHeight>options.scrollHeight;list.css('height',scrollbarsVisible?options.scrollHeight:listHeight);if(!scrollbarsVisible){listItems.width(list.width()-parseInt(listItems.css("padding-left"))-parseInt(listItems.css("padding-right")))}}}},selected:function(){var selected=listItems&&listItems.filter("."+CLASSES.ACTIVE).removeClass(CLASSES.ACTIVE);return selected&&selected.length&&$.data(selected[0],"ac_data")},emptyList:function(){list&&list.empty()},unbind:function(){element&&element.remove()}}};$.fn.selection=function(start,end){if(start!==undefined){return this.each(function(){if(this.createTextRange){var selRange=this.createTextRange();if(end===undefined||start==end){selRange.move("character",start);selRange.select()}else{selRange.collapse(true);selRange.moveStart("character",start);selRange.moveEnd("character",end);selRange.select()}}else if(this.setSelectionRange){this.setSelectionRange(start,end)}else if(this.selectionStart){this.selectionStart=start;this.selectionEnd=end}})}var field=this[0];if(field.createTextRange){var range=document.selection.createRange(),orig=field.value,teststring="<->",textLength=range.text.length;range.text=teststring;var caretAt=field.value.indexOf(teststring);field.value=orig;this.selection(caretAt,caretAt+textLength);return{start:caretAt,end:caretAt+textLength}}else if(field.selectionStart!==undefined){return{start:field.selectionStart,end:field.selectionEnd}}}})(jQuery);