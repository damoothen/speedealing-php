/*
*
* jQuery listnav plugin
* Copyright (c) 2009 iHwy, Inc.
* Author: Jack Killpatrick
*
* Version 2.1 (08/09/2009)
* Requires jQuery 1.3.2, jquery 1.2.6 or jquery 1.2.x plus the jquery dimensions plugin
*
* Visit http://www.ihwy.com/labs/jquery-listnav-plugin.aspx for more information.
*
* Dual licensed under the MIT and GPL licenses:
*   http://www.opensource.org/licenses/mit-license.php
*   http://www.gnu.org/licenses/gpl.html
*
*/

(function(b){b.fn.listnav=function(j){var c=b.extend({},b.fn.listnav.defaults,j),e="_,a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z,-".split(","),i=!1;c.prefixes=b.map(c.prefixes,function(b){return b.toLowerCase()});return this.each(function(){function j(){var a,d,e,g,h=0<c.prefixes.length;b(f).children().each(function(){g=b(this);d="";a=b.trim(g.text()).toLowerCase();""!=a&&(h&&(e=a.split(" "),1<e.length&&-1<b.inArray(e[0],c.prefixes)&&(d=e[1].charAt(0),n(d,g,!0))),d=a.charAt(0),n(d,g))})} function n(a,b,c){/\W/.test(a)&&(a="-");isNaN(a)||(a="_");b.addClass("ln-"+a);void 0==g[a]&&(g[a]=0);g[a]++;c||o++}function p(a){if(b(a).hasClass("all"))return o;a=g[b(a).attr("class").split(" ")[0]];return void 0!=a?a:0}function q(){c.showCounts&&h.mouseover(function(){k.css({top:b(".a",d).slice(0,1).offset({margin:!1,border:!0}).top-k.outerHeight({margin:!0})})});c.showCounts&&!is_touch_device&&(b("a",d).each(function(){var a=p(this);0<a&&b(this).addClass("ttip_t").attr("title",a)}),prth_tips.init()); b("a",d).bind(clickEvent,function(){b("a.ln-selected",d).removeClass("ln-selected");var a=b(this).attr("class").split(" ")[0];if(a=="all"){f.children().show();f.children(".ln-no-match").hide();l=true}else{if(l){f.children().hide();l=false}else m!=""&&f.children(".ln-"+m).hide();if(p(this)>0){f.children(".ln-no-match").hide();f.children(".ln-"+a).show()}else f.children(".ln-no-match").show();m=a}b.cookie&&c.cookieName!=null&&b.cookie(c.cookieName,a);b(this).addClass("ln-selected");b(this).blur();if(!i&& c.onClick!=null)c.onClick(a);else i=false;return false})}function r(){for(var a=[],b=1;b<e.length;b++)0==a.length&&a.push('<a class="all" href="#">ALL</a><a class="_" href="#">0-9</a>'),a.push('<a class="'+e[b]+'" href="#">'+("-"==e[b]?"...":e[b].toUpperCase())+"</a>");return'<div class="ln-letters">'+a.join("")+"</div>"+(c.showCounts?'<div class="ln-letter-count" style="display:none; position:absolute; top:0; left:0; width:20px;">0</div>':"")}var h,f,d,k;h=b("#"+this.id+"-nav");f=b(this);var g={}, o=0,l=!0,m="";(function(){h.append(r());d=b(".ln-letters",h).slice(0,1);c.showCounts&&(k=b(".ln-letter-count",h).slice(0,1));j();f.append('<li class="ln-no-match" style="display:none">'+c.noMatchText+"</li>");if(c.flagDisabled)for(var a=0;a<e.length;a++)void 0==g[e[a]]&&b("."+e[a],d).addClass("ln-disabled");q();c.includeAll||f.show();c.includeAll||b(".all",d).remove();c.includeNums||b("._",d).remove();c.includeOther||b(".-",d).remove();b(":last",d).addClass("ln-last");b.cookie&&null!=c.cookieName&& (a=b.cookie(c.cookieName),null!=a&&(c.initLetter=a));if(""!=c.initLetter)i=!0,b("."+c.initLetter.toLowerCase(),d).slice(0,1).click();else if(c.includeAll)b(".all",d).addClass("ln-selected");else for(a=c.includeNums?0:1;a<e.length;a++)if(0<g[e[a]]){i=!0;b("."+e[a],d).slice(0,1).click();break}})()})};b.fn.listnav.defaults={initLetter:"",includeAll:!0,incudeOther:!1,includeNums:!0,flagDisabled:!0,noMatchText:"No matching entries",showCounts:!0,cookieName:null,onClick:null,prefixes:[]}})(jQuery);
