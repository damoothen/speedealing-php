/*
* $Workfile: jquery.scrollabletab.js $
* $Revision: 7 $
* $Modtime: 8/11/10 16:03 $
* $Author: Aamir.afridi $
*
* jQuery.ScrollableTab - Scrolling multiple tabs.
*
* @copyright (c) 2010 Astun Technology
* @date Created: 28/08/2010
* @author Aamir Afridi
* @version 2.0
*/
(function(a){a.xui||(a.xui={});var h=a.extend({},a.ui.tabs.prototype),p=h._create,k=h._update;a.xui.tabs=a.extend(h,{options:a.extend({},h.options,{scrollable:!1,closable:!1,animationSpeed:500}),_create:function(){var j=this.options;p.apply(this);if(j.scrollable){var i=this.element,h=i.wrap("<div></div>").parent().addClass("ui-scrollable-tabs ui-widget-content ui-corner-all"),b=this.element.find(".ui-tabs-nav:first").removeClass("ui-corner-all"),d=a('<ol class="ui-helper-reset ui-helper-clearfix ui-tabs-nav-arrows"></ol>').prependTo(h), f=a('<li class="ui-tabs-arrow-previous ui-state-default" title="Previous"><a href="#"><span class="ui-icon ui-icon-carat-1-w">Previous tab</span></a></li>').prependTo(d),g=a('<li class="ui-tabs-arrow-next ui-state-default" title="Next"><a href="#"><span class="ui-icon ui-icon-carat-1-e">Next tab</span></a></li>').appendTo(d);a.fn.reverse=[].reverse;var m=function(e){var a={};a.right=0!=e.next("li").length?e.next("li")[0].offsetLeft+15:e[0].offsetLeft+e.outerWidth(!0);a.right=a.right+b[0].offsetLeft> d.width();a.left=e[0].offsetLeft+b[0].offsetLeft<0+(f.is(":visible")?f.outerWidth():0);return a},n=function(e,a){if("none"!=e)if("left"==e){f.show("fade");0==a.next("li").length&&g.hide("fade");var c=0,c=0!=a.next("li").length?a.next("li")[0].offsetLeft:a[0].offsetLeft+a.outerWidth(!0),c=d.width()-c,c=c-(0==a.next("li").length?1:g.outerWidth());b.animate({"margin-left":c+"px"},j.animationSpeed)}else g.show("fade"),0==a.prev("li").length&&f.hide("fade"),c=0,c=0==a.prev("li").length?2:f.outerWidth()+ 2,c=-1*(a[0].offsetLeft-c),b.animate({"margin-left":c},j.animationSpeed)},l=function(a){return a?a[0].offsetLeft+a.outerWidth(!0):b.find("li:last")[0].offsetLeft+b.find("li:last").outerWidth(!0)},o=function(){l()>d.width()?g.show("fade"):(g.hide("fade"),f.hide("fade"),b.css("margin-left",0))},k=function(e){j.closable&&(e||b.addClass("ui-tabs-closable").find("li")).each(function(){var e=a(this).addClass("stHasCloseBtn");a(this).append(a("<span/>").addClass("ui-state-default ui-corner-all ui-tabs-close").hover(function(){a(this).toggleClass("ui-state-hover")}).append(a("<span/>").addClass("ui-icon ui-icon-circle-close").html("Close").attr("title", "Close this tab").click(function(){i.tabs("remove",e.prevAll("li").length)})))})};a.fn.refreshTabs=function(){var a=b.find("li.ui-tabs-selected");i.trigger("tabsselect",[{tab:a.find("a")}]);l()>d.width()?(0!=a.next("li").length&&g.show("fade"),a=b.find("li:last"),a=a[0].offsetLeft+a.outerWidth(!0),a=d.width()-b[0].offsetLeft-a,1<a&&(b.css("margin-left",b[0].offsetLeft+a-1),g.hide("fade"))):(g.hide("fade"),f.hide("fade"),b.css("margin-left",0))};(function(){k();o();g.click(function(){var a=b.find("li.ui-tabs-selected").next("li"); a.length&&a.find("a").trigger("click");return!1});f.click(function(){var a=b.find("li.ui-tabs-selected").prev("li");a.length&&a.find("a").trigger("click");return!1});i.bind("tabsselect",function(b,f){var c=a(f.tab).parent();0==c.next("li").length&&g.hide("fade");var d=m(c);n(d.right?"left":d.left?"right":"none",c)}).bind("tabsadd",function(b,d){a(d.tab).parent().find("a").trigger("click")}).bind("tabsremove",function(){j.closable&&(1==i.tabs("length")&&(i.find("li .ui-tabs-close").hide(),b.removeClass("ui-tabs-closable")), l()<d.width()?(g.hide("fade"),f.hide("fade")):(m(b.find("li:last")).right||n("left",b.find("li:last")),o()))})})()}return this},_update:function(){console.log(arguments);k.apply(this)}});a.widget("xui.tabs",a.xui.tabs)})(jQuery);

/*
* animated progressbar
* http://www.script-tutorials.com/animated-jquery-progressbar/
*/
jQuery.fn.anim_progressbar=function(f){var g={start:new Date,finish:(new Date).setTime((new Date).getTime()+6E4),interval:100},d=jQuery.extend(g,f),a=this;return this.each(function(){var f=d.finish-d.start;$(a).children(".pbar").progressbar();var g=setInterval(function(){var b=d.finish-new Date,c=new Date-d.start,e=parseInt(b/864E5),h=parseInt((b-864E5*e)/36E5),i=parseInt((b-864E5*e-36E5*h)/6E4),b=parseInt((b-864E5*e-6E4*i-36E5*h)/1E3),c=0<c?100*(c/f):0;$(a).children(".percent").html("<b>"+c.toFixed(1)+ "%</b>");$(a).children(".elapsed").html(e+" days "+h+"h:"+i+"m:"+b+"s</b>");$(a).children(".pbar").children(".ui-progressbar-value").css("width",c+"%");100<=c&&(clearInterval(g),$(a).children(".percent").html("<b>100%</b>"),$(a).children(".elapsed").html("Finished"))},d.interval)})};

/*
 * jQuery UI Touch Punch 0.2.2
 *
 * Copyright 2011, Dave Furfero
 * Dual licensed under the MIT or GPL Version 2 licenses.
 *
 * Depends:
 *  jquery.ui.widget.js
 *  jquery.ui.mouse.js
 */
(function(b){b.support.touch="ontouchend" in document;if(!b.support.touch){return;}var c=b.ui.mouse.prototype,e=c._mouseInit,a;function d(g,h){if(g.originalEvent.touches.length>1){return;}g.preventDefault();var i=g.originalEvent.changedTouches[0],f=document.createEvent("MouseEvents");f.initMouseEvent(h,true,true,window,1,i.screenX,i.screenY,i.clientX,i.clientY,false,false,false,false,0,null);g.target.dispatchEvent(f);}c._touchStart=function(g){var f=this;if(a||!f._mouseCapture(g.originalEvent.changedTouches[0])){return;}a=true;f._touchMoved=false;d(g,"mouseover");d(g,"mousemove");d(g,"mousedown");};c._touchMove=function(f){if(!a){return;}this._touchMoved=true;d(f,"mousemove");};c._touchEnd=function(f){if(!a){return;}d(f,"mouseup");d(f,"mouseout");if(!this._touchMoved){d(f,"click");}a=false;};c._mouseInit=function(){var f=this;f.element.bind("touchstart",b.proxy(f,"_touchStart")).bind("touchmove",b.proxy(f,"_touchMove")).bind("touchend",b.proxy(f,"_touchEnd"));e.call(f);};})(jQuery);
