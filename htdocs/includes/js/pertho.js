/* --------------------------------------------------
    Pertho Admin Panel js configuration file     v1.1
-----------------------------------------------------
:: Global
:: Charts
:: Forms
:: Misc
:: Style Switcher
*/


// ----- Global ----- //

    //* common functions
    prth_common = {
        init: function(){
            if(!is_touch_device){
                //* fixed/fluid width switcher
                prth_width_switch.init();
                //* scroll to top
                $().UItoTop();
            }
            //* sticky notifications (user box)
            prth_sticky_notifications.init();
            //* fancybox with title
			prth_fancyImg.init();
            //* tooltips
            prth_tips.init();
            //* jquery tools tabs
			prth_tabs.init();
            //* infinite tabs (jquery UI tabs)
            prth_infinite_tabs.init();
            //* external links
            prth_external_links.init();
            //alert boxes close button
            prth_alert_boxes.init();
            //* box hide/remove
            prth_box_actions.init();
            //* resize elements on window resize
            var lastWindowHeight = $(window).height();
			var lastWindowWidth = $(window).width();
			$(window).smartresize(function() {
				if($(window).height()!=lastWindowHeight || $(window).width()!=lastWindowWidth){
					lastWindowHeight = $(window).height();
					lastWindowWidth = $(window).width();
					prth_reize_el.init();
				}
			});
            // sticky footer
            prth_stickyFooter.init();
            //placeholder js fallback
            $('input, textarea').placeholder();
        }
    };

	//* main navigation
	prth_main_nav = {
		//* horizontal navigation
		h_nav: function(){
			ddsmoothmenu.init({
				mainmenuid: "smoothmenu_h", //menu DIV id
				orientation: 'h', //Horizontal or vertical menu: Set to "h" or "v"
				classname: 'ddsmoothmenu', //class added to menu's outer DIV
				contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
			})
		},
		//* vertical navigation
		v_nav: function(){
			ddsmoothmenu.init({
				mainmenuid: "smoothmenu_v", //menu DIV id
				orientation: 'v', //Horizontal or vertical menu: Set to "h" or "v"
				classname: 'ddsmoothmenu-v', //class added to menu's outer DIV
				contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
			})
		},
		//* mobile navigation (select element)
		mobile_nav: function(){
			$('.tinyNav > ul').tinyNav({
                'header' : true    
            });
		},
		//* sticky navigation
		sticky_menu: function() {
			$("#smoothmenu_h").stickyPanel({
				topPadding: 0,
				afterDetachCSSClass: "sticky_nav",
				savePanelSpace: true
			})
		},
		//* centered sticky navigation
		sticky_menu_center: function() {
			$("#smoothmenu_h").stickyPanel({
				topPadding: 0,
				afterDetachCSSClass: "sticky_nav_center",
				savePanelSpace: true
			})
		}
	};

	//* adjust elements size after resizing browser or switching between full/"fixed" width
	prth_reize_el = {
		init: function(){
			//* charts
			if($('.chart_flw').length) {
				prth_charts.charts_resize();
			};
			if(typeof dsPlot1 != 'undefined'){
				dsPlot1.replot({ resetAxes: false });
			};
			if(typeof dsPlot2 != 'undefined'){
				dsPlot2.replot();
			};
			if(typeof plPlot1 != 'undefined'){
				plPlot1.replot({ resetAxes: true });
			};
			if(typeof plPlot2 != 'undefined'){
				plPlot2.replot({ resetAxes: true });
			};
			if(typeof plPlot3 != 'undefined'){
				plPlot3.replot({ resetAxes: true });
			};
			if(typeof plPlot4 != 'undefined'){
				plPlot4.replot({ resetAxes: true });
			};
			if(typeof plPlot5 != 'undefined'){
				plPlot5.replot({ resetAxes: true });
			};
			if(typeof wgPlot1 != 'undefined'){
				wgPlot1.replot({ resetAxes: true });
			};
			//* image gallery
			if($('.gallery_list').length) {
				prth_gallery.gallery_resize();
			};
			//* horizontal scrollable
			if($('.h_scrollable').length) {
				prth_h_scrollable.scrollable_adjust();
			};
			//* go to 1 scrollable item (hotizontal scrollable)
			var api_a = $('.h_scrollable').data('scrollable');
			if(typeof api_a != 'undefined'){
				api_a.seekTo(0);
			};
			//* go to 1 scrollable item (gallery scrollable)
			var api_b = $('.gal_scrollable').data('scrollable');
			if(typeof api_b != 'undefined'){
				api_b.seekTo(0);
			};
			//* go to 1 scrollable item (vertical scrollable)
			var api_c = $('.v_scrollable').data('scrollable');
			if(typeof api_c != 'undefined'){
				api_c.seekTo(0);
			};
			//* sidebar divider height
			if( $('#sidebar').length ) {
				if( $('#sidebar').is(':hidden') ) {
					$('.divider').height($('#mainCol').height()).show();
				}
			};
			//* full calendar
			if( $('#calendar').length  ){
				$('#calendar').fullCalendar('render');
			};
			// reinitialize vertical menu
			if( $('#smoothmenu_v').length  ){
				prth_main_nav.v_nav();
			};
			//sticky footer
			prth_stickyFooter.resize();
            // help accordion
            if( $('.st-accordion').lenght || $('st-documentation') ) {
                $('.top_H').each(function(){
                    $(this).height( $(this).children('.top_Ha').outerHeight() );
                })
            }
		}
	};

	//* full/"fixed" width
	prth_width_switch = {
		init: function(){
			if($('body').hasClass('fullW')){
				$('.sw_width').children('img').toggle();
			};
			$('.sw_width').show();
			$('.sw_width').click(function(){
				$('.sw_resizedEL').css('position','relative').append('<img src="img/loader.gif" style="position:absolute;top:50%;left:50%;margin:-16px 0 0 -16px" class="jQloader" />').children('div').css('visibility','hidden');
				if( $(this).children('img.sw_full').is(':visible') ){
					var fullW = $(window).width();
					$('.container > .row').stop().animate({'max-width' : fullW - 40}, 1000, function(){
						$(this).css('max-width','100%')
						prth_reize_el.init();
						$('.jQloader').remove();
						$('.sw_resizedEL').css('position','').children('div').css('visibility','visible');
					});
				} else {
					var fullW = $(window).width() - 40;
					$('.container > .row').css('max-width', fullW);
					$('.container > .row').stop().animate({'max-width' : '980px'}, 1000, function() {
						prth_reize_el.init();
						$('.jQloader').remove();
						$('.sw_resizedEL').css('position','').children('div').css('visibility','visible');
					});
				};
				$(this).children('img').toggle();
			});
		}
	};

	// alert boxes
	prth_alert_boxes = {
		init: function() {
			$('.alert-box a.close').each(function(){
                $(this).attr('href','javascript:void(0)');
            });
			$(".alert-box").on("click", "a.close", function(event) {
				event.preventDefault();
				$(this).closest(".alert-box").fadeOut(function(event){
					$(this).remove();
				});
			});
		}
	};

	// box actions (show/hide, remove)
	prth_box_actions = {
		init: function() {
			$('.box_actions').each(function(){
				$(this).append('<span class="bAct_hide"><img src="theme/blank.gif" class="bAct_x" alt="" /></span>');
				$(this).append('<span class="bAct_toggle"><img src="theme/blank.gif" class="bAct_minus" alt="" /></span>');
				$(this).find('.bAct_hide').on('click', function(){
					$(this).closest('.box_c').fadeOut('slow',function(){
						$(this).remove();
						prth_stickyFooter.resize();
					});
				});
				$(this).find('.bAct_toggle').on('click', function(){
					if( $(this).closest('.box_c_heading').next('.box_c_content').is(':visible') ) {
						$(this).closest('.box_c_heading').next('.box_c_content').slideUp('slow',function(){
							prth_stickyFooter.resize();
						});
						$(this).html('<img src="theme/blank.gif" class="bAct_plus" alt="" />');
					} else {
						$(this).closest('.box_c_heading').next('.box_c_content').slideDown('slow',function(){
							prth_stickyFooter.resize();
						});
						$(this).html('<img src="theme/blank.gif" class="bAct_minus" alt="" />');
					}
				});
			});
		}
	};

	//* fancybox for single images
	//* <a href="url_to_big_image" class="fancyImg" title="Some title"><img src="url_to_small_image" alt="" /></a>
	prth_fancyImg = {
		init: function(){
			$('a.fancyImg').each(function(){
				var validParent = $(this).parent('td').length;
				var thisTitle = $(this).attr('title');
				if( (thisTitle != '') && (validParent != '1') ) {
					$(this).append('<figcaption>'+thisTitle+'</figcaption>');
				}
			});
			$('a.fancyImg').fancybox({
				'overlayOpacity'	: '0.2',
				'transitionIn'		: 'elastic',
				'transitionOut'		: 'fade'
			});
		}
	};

	//* external links
	prth_external_links = {
		init: function() {
			$("a[href^='http']").attr('target','_blank').addClass('external_link');
		}
	};

	//* jQuery tools tabs
	prth_tabs = {
		init: function() {
			$(".tabs").flowtabs(".box_c_content > .tab_pane");
		}
	};

	//* infinite tabs (jQuery UI tabs)
	prth_infinite_tabs = {
		init: function() {
			$(".ui_tabs").tabs({
				scrollable: true
			});
		}
	};

	//* tooltips
	prth_tips = {
		init: function() {
			var shared = {
				style		: {
					classes	: 'ui-tooltip-dark ui-tooltip-rounded'
				},
				show		: { delay: 0 },
				hide		: { delay: 0 }
			};
			if($('.ttip_b').length) {
				$('.ttip_b').qtip( $.extend({}, shared, {
					position	: {
						my		: 'top center',
						at		: 'bottom center',
						viewport: $(window)
					}
				}));
			};
			if($('.ttip_t').length) {
				$('.ttip_t').qtip( $.extend({}, shared, {
					position: {
						my		: 'bottom center',
						at		: 'top center',
						viewport: $(window)
					}
				}));
			};
			if($('.ttip_l').length) {
				$('.ttip_l').qtip( $.extend({}, shared, {
					position: {
						my		: 'right center',
						at		: 'left center',
						viewport: $(window)
					}
				}));
			};
			if($('.ttip_r').length) {
				$('.ttip_r').qtip( $.extend({}, shared, {
					position: {
						my		: 'left center',
						at		: 'right center',
						viewport: $(window)
					}
				}));
			};
		}
	};

	//* vertical scrollable
	prth_v_scrollable = {
		init: function(){
			$(".v_scrollable").scrollable({
				vertical: true,
				keyboard: false,
				touch: false,
				onBeforeSeek: function(evt, index) {
					$(".v_scrollable").stop().animate({ height: $('.page').eq(index).outerHeight()+'px'}, 250);
				}
			}).navigator({
				navi: ".v_navi",
				activeClass: 'current'
			});
			var api = $(".v_scrollable").data("scrollable");
			var fElHeight = $(api.getItemWrap()).find('.page:first').outerHeight();
			$(api.getRoot()).css('height',fElHeight);
		}
	};

	//* horizontal scrollable
	prth_h_scrollable = {
		init: function(){
			prth_h_scrollable.scrollable_adjust();
			$(window).resize(function () {
				prth_h_scrollable.scrollable_adjust();
			});
			$('.h_scrollable').scrollable({
				keyboard:false,
				touch: false,
				onBeforeSeek: function(evt, index) {
					if( $(".h_scrollable").find('.sH_adjust').length > 0 ) {
						$(".h_scrollable").stop().animate({ height: $('.sH_adjust').eq(index).outerHeight()+50+'px'}, 250);
					}
				}
			}).navigator({
				navi: ".h_navi",
				naviItem: 'li',
				activeClass: 'current',
				history: false,
				circular: true
			});
			if( $(".h_scrollable").find('.sH_adjust').length > 0 ) {
				var api = $(".h_scrollable").data("scrollable");
				var fElHeight = $(api.getItemWrap()).find('.sH_adjust:first').outerHeight();
				$(api.getRoot()).css('height',fElHeight + 50);
			}
		},
		//* adjust content width
		scrollable_adjust: function(){
			$('.sH_adjust').each(function(){
				var colWrap =  $(this).closest('.box_c_content').width();
				$(this).css({ 'width' : colWrap });
			})
		}
	};

	//* sticky footer
	prth_stickyFooter = {
		init: function() {
			prth_stickyFooter.resize();
		},
		resize: function() {
                        if($("#sticky-footer-push").height() === "undefined")
                            var docHeight = $(document.body).height();
                        else
                            var docHeight = $(document.body).height() - $("#sticky-footer-push").height();
                        
			if(docHeight < $(window).height()){
				var diff = $(window).height() - docHeight - 10;
				if ($("#sticky-footer-push").length == 0) {
					$('#footer').before('<div id="sticky-footer-push"></div>');
				}
				$("#sticky-footer-push").height(diff - 1);
			} else {
				$("#sticky-footer-push").remove();
			}
		}
	};

// ----- Charts ----- //

	prth_charts = {
		ds_plot1: function(){
			var dsPlot1_data = [
				['2012-03-15',  2314],
				['2012-03-14',  2103],
				['2012-03-13',  2110],
				['2012-03-12',  1804],
				['2012-03-11',  1030],
				['2012-03-10',  1624],
				['2012-03-09',  1400],
				['2012-03-08',  1110],
				['2012-03-07',  1000],
				['2012-03-06',  1200],
				['2012-03-05',  900],
				['2012-03-04',  704],
				['2012-03-03',  580],
				['2012-03-02',  460],
				['2012-03-01',  120]
			];

			dsPlot1 = $.jqplot('ds_plot1', [dsPlot1_data], {
				animate: !$.jqplot.use_excanvas,
				seriesColors: ["#058DC7"],
				title: 'Unique Visitors by Day - March 2012',
				highlighter: { show: true, sizeAdjust: 7.5 , tooltipLocation : 'n' },
				axesDefaults: {
					labelRenderer: $.jqplot.CanvasAxisLabelRenderer
				},
				grid: {
					shadow: false,
					borderWidth: 1.0,
					background: '#fff'
				},
				seriesDefaults: {
					rendererOptions: {
						smooth: true
					}
				},
				axes: {
					xaxis: {
						renderer:$.jqplot.DateAxisRenderer,
						rendererOptions:{
							tickRenderer:$.jqplot.CanvasAxisTickRenderer
						},
						tickOptions:{
							formatString:'%d %b',
							angle: -90,
							fontSize: '10px'
						},
						min: "2012-02-28",
						max: "2012-03-16",
						tickInterval: "3 days"
					},
					yaxis: {
						pad: 0
					}
				}
			});
		},
		ds_plot2: function() {
			var s1 = [200, 600, 700, 1000];
			var s2 = [460, -210, 690, 820];
			var s3 = [-260, -440, 320, 200];
			var ticks = ['May', 'June', 'July', 'August'];

			dsPlot2 = $.jqplot('ds_plot2', [s1, s2, s3], {
				seriesColors: ["#dc0a00","#ff7214","#ffab25","#fdd40d","#f8ff1a","#b4e409","#05da15","#0f98de","#0d5add"],
				seriesDefaults:{
					renderer:$.jqplot.BarRenderer,
					rendererOptions: {fillToZero: true},
					pointLabels: { show: true }
				},
				title: 'Another chart',
				series:[
					{label:'Hotel'},
					{label:'Event Regristration'},
					{label:'Airfare'}
				],
				legend: {
					show: true,
					location: 'nw'
				},
				axes: {
					xaxis: {
						renderer: $.jqplot.CategoryAxisRenderer,
						ticks: ticks
					},
					yaxis: {
						pad: 1.05,
						tickOptions: {formatString: '$%d'}
					}
				},
				grid: {
					shadow: false,
					borderWidth: 1.0,
					background: '#fff'
				}
			});
		},
		pl_plot1: function() {
			var plPlot1_data = [['J.Doe', 42],['M.Lou', 16],['J.Smith', 22],['J.Adams', 35],['M.Johnson', 46]];
			plPlot1 = $.jqplot('pl_plot1', [plPlot1_data], {
				seriesColors: ["#dc0a00","#ff7214","#ffab25","#fdd40d","#b4e409","#05da15","#0f98de","#0d5add"],
				title:'Bar Chart with Custom Colors',
				seriesDefaults:{
					renderer:$.jqplot.BarRenderer,
					rendererOptions: {
						varyBarColor: true,
						barWidth: '32'
					},
					pointLabels: { show: true }
				},
				axes:{
					xaxis:{
						renderer: $.jqplot.CategoryAxisRenderer,
						tickRenderer: $.jqplot.CanvasAxisTickRenderer,
						tickOptions: {angle: -30}
					}
				},
				grid: {
					shadow: false,
					borderWidth: 1.0,
					background: '#fff'
				}
			});
		},
		pl_plot2: function() {
			var s1 = [ [2007, 148000], [2008, 114000], [2009, 133000], [2010, 161000], [2011, 173000] ];
			var s2 = [ [2007, 12800], [2008, 13200], [2009, 12600], [2010, 13100], [2011, 14100] ];
			plPlot2 = $.jqplot("pl_plot2", [s2, s1], {
				animate: true,
				animateReplot: true,
				series:[
					{
						pointLabels: { show: true },
						renderer: $.jqplot.BarRenderer,
						showHighlight: false,
						yaxis: 'y2axis',
						rendererOptions: {
							// Speed up the animation a little bit.
							// This is a number of milliseconds.
							// Default for bar series is 3000.
							animation: {
								speed: 2500
							},
							barWidth: 15,
							barPadding: -15,
							barMargin: 0,
							highlightMouseOver: false
						}
					},
					{
						rendererOptions: {
							// speed up the animation a little bit.
							// This is a number of milliseconds.
							// Default for a line series is 2500.
							animation: {
								speed: 2000
							}
						}
					}
				],
				seriesColors: ["#4393c3","#92c5de"],
				axesDefaults: {
					pad: 0
				},
				axes: {
					xaxis: {
						tickInterval: 1,
						drawMajorGridlines: false,
						drawMinorGridlines: true,
						drawMajorTickMarks: false,
						rendererOptions: {
							tickInset: 0.5,
							minorTicks: 1
						}
					},
					yaxis: {
						tickOptions: {
							formatString: "$%'d",
							angle: 30
						},
						rendererOptions: {
							forceTickAt0: true
						}
					},
					y2axis: {
						tickOptions: {
							formatString: "$%'d"
						},
						rendererOptions: {
							// align the ticks on the y2 axis with the y axis.
							alignTicks: true,
							forceTickAt0: true
						}
					}
				},
				highlighter: {
					show: true,
					showLabel: true,
					tooltipAxes: 'y',
					sizeAdjust: 7.5 ,
					tooltipLocation : 'sw'
				},
				grid: {
					borderWidth: '0',
					shadow: false,
					background: '#fff'
				}
			});
		},
		pl_plot3: function() {
			var plPlot3_data = [
				['Heavy Industry', 12],['Retail', 9], ['Light Industry', 14],
				['Out of home', 16],['Commuting', 7], ['Orientation', 9]
			];
			plPlot3 = $.jqplot ('pl_plot3', [plPlot3_data],
				{
				  seriesDefaults: {
					renderer: $.jqplot.PieRenderer,
					rendererOptions: {
						showDataLabels: true,
						dataLabelNudge: 26,
						dataLabelCenterOn: false
					}
				  },
				  seriesColors: ["#dc0a00","#ff7214","#ffab25","#fdd40d","#f8ff1a","#b4e409","#05da15","#0f98de","#0d5add"],
				  grid: {
					borderWidth: '0',
					shadow: false,
					background: '#fff'
				},
				legend: { show:true, location:'e', marginTop: '15px', border: "none" }
				}
			);
		},
		pl_plot4: function() {
			var plPlot4_data = [
				['Heavy Industry', 12],['Retail', 9], ['Light Industry', 14],
				['Out of home', 16],['Commuting', 7], ['Orientation', 9]
			];
			plPlot4 = $.jqplot ('pl_plot4', [plPlot4_data], {
				seriesDefaults: {
				  renderer: $.jqplot.PieRenderer,
				  rendererOptions: {
					  // Turn off filling of slices.
					  fill: false,
					  showDataLabels: true,
					  // Add a margin to seperate the slices.
					  sliceMargin: 5,
					  // stroke the slices with a little thicker line.
					  lineWidth: 6,
					  dataLabelNudge: 26,
					  dataLabelCenterOn: false,
					  dataLabelFormatString: '%d%%'
				  }
				},
				highlighter: {show: false},
				seriesColors: ["#dc0a00","#ff7214","#ffab25","#fdd40d","#f8ff1a","#b4e409","#05da15","#0f98de","#0d5add"],
				grid: {
					borderWidth: '0',
					shadow: false,
					background: '#fff'
				},
				legend: { show:true, location:'e', marginTop: '15px', border: "none" }
			});
		},
		pl_plot5: function() {
			var d1 = [[0, -10.3], [1, 7.0], [2, 15.7], [3, 0.5], [4, -10.4], [5, 1.1], [6, 13.2],
			[7, 1.8], [8, -4.5], [9, -1.8], [10, 2.0], [11, 3.0], [12, -3.5], [13, -7.4], [14, -11.3]];
			var d2 = [[0, 1.3], [1, 12.8], [2, -8.2], [3, -5.2], [4, 16.4], [5, -5.3], [6, 8.1],
			[7, 15.1], [8, -4.4], [9, 7.8], [10, -1.4], [11, 0.2], [12, 1.3], [13, 11.7], [14, -9.7]];
			plPlot5 = $.jqplot('pl_plot5', [d1, d2], {
				grid: {
					borderWidth: '0',
					shadow: false,
					background: '#fff'
				},
				highlighter: { show: true },
				seriesDefaults: {
					shadowAlpha: 0.1,
					shadowDepth: 2,
					fillToZero: true
				},
				series: [
					{
						color: 'rgba(5,113,176,.6)',
						negativeColor: 'rgba(146,197,222,.6)',
						showMarker: true,
						showLine: true,
						fill: true,
						fillAndStroke: true,
						markerOptions: {
							style: 'filledCircle',
							size: 8
						},
						rendererOptions: {
							smooth: true
						}
					},
					{
						color: 'rgba(202, 0, 32, 0.7)',
						showMarker: true,
						rendererOptions: {
							smooth: true
						},
						markerOptions: {
							style: 'filledSquare',
							size: 8
						}
					}
				],
				axes: {
					xaxis: {
						pad: 1.0,
						tickOptions: {
						  showGridline: false
						}
					},
					yaxis: {
						pad: 1.05
					}
				}
			});
		},
		wg_plot1: function() {
			var wgPlot1_data = [
				['Chrome', 581],
				['Firefox', 410],
				['Safari', 317],
				['Internet Explorer', 267],
				['Opera', 190],
				['Others', 124]
			];
			wgPlot1 = $.jqplot('wg_plot1', [wgPlot1_data], {
				seriesColors: ["#dc0a00","#ff7214","#ffab25","#fdd40d","#f8ff1a","#b4e409","#05da15","#0f98de","#0d5add"],
				title:'Browser Usage Stats',
				seriesDefaults: {
					renderer: $.jqplot.PieRenderer,
					rendererOptions: {
						showDataLabels: true,
						dataLabelNudge: 26,
						dataLabelCenterOn: false,
						highlightMouseOver: false
					}
				},
				grid: {
					borderWidth: '0',
					shadow: false,
					background: '#fff'
				},
				legend: { show:true, location:'e', marginTop: '15px', border: "none" }
			});
		},
		//create image from chart
		makeImage: function() {
			if (!$.jqplot.use_excanvas) {
				$('body').append('<div style="display:none"><div id="img_from_chart_outer" style="padding:10px"><select id="chart_select"><option val="">-- Select chart --</option></select></div></div>');
				$('.chart_flw').each(function(){
					if($(this).attr('title') != ''){
						$('#chart_select').append('<option val="' + $(this).attr('id') + '">' + $(this).attr('title') + '</option>');
						$(this).attr('title','');
					}
				});
				if($('#chart_select').length) {
					$('.image_from_chart').show();
					$('.image_from_chart').click(function(){
							$.fancybox({
								'autoDimensions'	: true,
								'scrolling'			: 'no',
								'href'				: '#img_from_chart_outer',
								onStart				: function() {
									$('#chart_select').change(function(){
										if($(this).children('option:selected').attr('val') != ''){
											var opSelected = $( '#'+$(this).children('option:selected').attr('val') );
											var imgEl = opSelected.jqplotToImageElem();
											var el_width = opSelected.width();
											var el_height = opSelected.height();
											$.fancybox({
												'autoDimensions'	: false,
												'width'				: el_width,
												'height'			: el_height,
												'scrolling'			: 'no',
												'title'				: 'Right Click to Save Image As...',
												'content'			: imgEl,
												onClosed			: function() {
													$('#chart_select option:first').attr('selected', 'selected');
												}
											});
										}
									})
								}
							});
						return false;
					});
				}
			}
		},
		//resize charts width to fit outer box
		charts_resize: function() {
			$('.chart_flw').each(function(){
				if($(this).closest('.inner_block').length){
					var chartWidth = $(this).closest('.inner_block').width()
				} else if($(this).closest('.ui-tabs-panel').length) {
					var chartWidth = $(this).closest('.ui-tabs-panel').width();
				} else {
					var chartWidth = $(this).closest('.box_c_content').width();
				};
				$(this).width(chartWidth);
			})
		}
	};

// ----- Forms ----- //

	//* textarea autosize
	prth_textarea_auto = {
		init: function() {
			$('.auto_expand').autosize();
		}
	};

	//* clear form
	prth_clearForm = {
		init: function() {
			$.fn.clearForm = function() {
				return this.each(function() {
					var type = this.type, tag = this.tagName.toLowerCase();
					if (tag == 'form'){
						return $(':input',this).clearForm();
					}
					if (type == 'text' || type == 'password' || tag == 'textarea'){
						this.value = '';
					}
					else if (type == 'checkbox' || type == 'radio'){
						this.checked = false;
					}
					else if (tag == 'select') {
						this.selectedIndex = -1;
					}
				}).trigger("liszt:updated");
			};
			$('.clear_form').click(function() {
				var clear_btn = $(this);
				$.fancybox(
					'<div class="tac"><p class="sepH_b">Are you sure you want to reset the form?</p><a href="javascript:void(0)" id="clear_yes" class="gh_button small">Yes</a><a href="javascript:void(0)" id="clear_no" class="gh_button small danger">No</a></div>',
					{
						'transitionIn'		: 'elastic',
						'showCloseButton'	: false,
						'overlayOpacity'	: '0',
						'hideOnOverlayClick': false,
						'autoDimensions'	: true,
						//'width'         	: '50%',
						'height'        	: 'auto',
						'onComplete'	:	function() {
							$('#clear_yes').click(function(){
								clear_btn.closest('form').clearForm();
								if(typeof tinyMCE != 'undefined'){
									tinyMCE.activeEditor.setContent(''); //clear tinyMCE content
								};
								$.fancybox.close(); //close modal
							});
							$('#clear_no').click(function(){
								$.fancybox.close(); //close modal
							});
						}
					}
				);
				return false;
			});
		}
	};

	//* form validation
	prth_form_validation = {
		init: function() {
			form_validator = $("form.validate").validate({
				highlight: function(element) {
					$(element).closest('div.elVal').addClass("form-field error");
				},
				unhighlight: function(element) {
					$(element).closest('div.elVal').removeClass("form-field error");
					var errors = form_validator.numberOfInvalids();
					if(errors == 0){
						$('#showMessage').remove();
					}else {
						$('#showMessage .errorsNb').text(errors);
					}
				},
				invalidHandler: function(form, validator) {
					var errors = form_validator.numberOfInvalids();
					//scroll to top
					$('html,body').animate({ scrollTop: $('form.validate').offset().top - 34 }, 'slow');
					//show error message (top sticky nottification)
					$('body').showMessage({
						thisMessage			: ['Your form contains <span class="errorsNb">'+errors+'</span> errors, see details below.'],
						className			: 'fail',
						autoClose			: false
					});
				},
				rules: {
					first_name: "required",
					last_name: "required",
					email: {
						required: true,
						email: true
					},
					password: {
						required: true,
						minlength: 5
					},
					confirm_password: {
						required: true,
						minlength: 5,
						equalTo: "#password"
					},
					gender: {
						required: true
					},
					spam: {
						required: true,
						minlength: 2
					}
				},
				messages: {
					first_name: "Please enter your firstname",
					last_name: "Please enter your lastname",
					password: {
						required: "Please provide a password",
						minlength: "Your password must be at least 5 characters long"
					},
					confirm_password: {
						required: "Please provide a password",
						minlength: "Your password must be at least 5 characters long",
						equalTo: "Passwords must match!"
					},
					email: "Please enter a valid email address",
					gender: {
						required: "Please select your gender"
					},
					spam: {
						required: "Please select type of spam you'd like to receive",
						minlength: "Please select at least two types of spam"
					}
				},
				showErrors: function (errorMap, errorList) {
					this.defaultShowErrors();
				},
				errorPlacement: function(error, element) {
					error.appendTo( element.closest("div.elVal") );
				},
				//submit form
				submitHandler: function(form) {
					var post = $("form.validate").serializeObject();
					$.post('serialize_form.php', post, function(data) {
						$('body').showMessage({
							thisMessage			: ['Data submited:<br/>'+data],
							className			: 'success',
							autoClose			: true,
							delayTime			: 10000
						});
					});
				},
				ignore: ""
			});
		}
	};

	//* wysiwyg editor
	prth_editor = {
		html: function() {
			$('textarea.tinymce').tinymce({
				// Location of TinyMCE script
				script_url 							: 'lib/tiny_mce/tiny_mce.js',
				// General options
				theme 								: "advanced",
				plugins 							: "autoresize,style,table,advhr,advimage,advlink,emotions,inlinepopups,preview,media,contextmenu,paste,fullscreen,noneditable,xhtmlxtras,template,advlist",
				// Theme options
				theme_advanced_buttons1 			: "undo,redo,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,fontselect,fontsizeselect",
				theme_advanced_buttons2 			: "forecolor,backcolor,|,cut,copy,paste,pastetext,|,bullist,numlist,link,image,media,|,code,preview,fullscreen",
				theme_advanced_buttons3 			: "",
				theme_advanced_toolbar_location 	: "top",
				theme_advanced_toolbar_align 		: "left",
				theme_advanced_statusbar_location 	: "bottom",
				theme_advanced_resizing 			: false,
				font_size_style_values 				: "8pt,10px,12pt,14pt,18pt,24pt,36pt",
				init_instance_callback				: function(){
					function resizeWidth() {
						document.getElementById(tinyMCE.activeEditor.id+'_tbl').style.width='100%';
					}
					resizeWidth();
					$(window).resize(function() {
						resizeWidth();
					})
				},
				// file browser - https://github.com/Studio-42/elFinder/wiki/Integration-with-TinyMCE-3.x
				file_browser_callback: function elFinderBrowser(field_name, url, type, win) {
					var cmsURL = 'lib/elfinder/elfinder.php';
					if (cmsURL.indexOf("?") < 0) {
						cmsURL = cmsURL + "?type=" + type;
					}
					else {
						cmsURL = cmsURL + "&type=" + type;
					}
					tinyMCE.activeEditor.windowManager.open({
						file : cmsURL,
						title : 'elFinder 2.0 File Manager',
						width : 900,
						height : 450,
						resizable : "yes",
						inline : "yes",
						popup_css : false,
						close_previous : "no"
					}, {
						window : win,
						input : field_name
					});
					return false;
				}
			});
		}
	};

	//* file upload
	prth_fileUpload = {
		init: function(){
			$("#uploader").pluploadQueue({
				// General settings
				runtimes : 'html5,flash,html4',
				url : 'lib/plupload/upload.php',
				max_file_size : '10mb',
				chunk_size : '1mb',
				unique_names : true,
				// Resize images on clientside if we can
				resize : {width : 320, height : 240, quality : 90},
				// Specify what files to browse for
				filters : [
					{title : "Image files", extensions : "jpg,gif,png"},
					{title : "Zip files", extensions : "zip"}
				],
				// Flash settings
				flash_swf_url : 'lib/plupload/js/plupload.flash.swf'
			});
		}
	};

	//* enchanced select
	prth_chosen_select = {
		init: function(){
			$(".chzn-select").chosen();
		}
	};

	//* datepicker & timepicker
	prth_dp_tp = {
		init: function(){
			$("input#datepicker").datepicker();
			$("input#timepicker").datetimepicker();
			$("#datepicker_inline").datepicker( {inline: true} );
		}
	};

	//* progressbars
	prth_progressbar = {
		init: function(){
			// default mode
			$('#progress1').anim_progressbar();
			// from second #5 till 15
			var iNow = new Date().setTime(new Date().getTime() + 5 * 1000); // now plus 5 secs
			var iEnd = new Date().setTime(new Date().getTime() + 15 * 1000); // now plus 15 secs
			$('#progress2').anim_progressbar({start: iNow, finish: iEnd, interval: 100});
			// we will just set interval of updating to 1 sec
			$('#progress3').anim_progressbar({interval: 1000});
		}
	};

	//* sliders
	prth_sliders = {
		init: function(){
			//default slider
			$( ".ui_slider1" ).slider({
				value:100,
				min: 0,
				max: 500,
				step: 50,
				slide: function( event, ui ) {
					$( ".ui_slider1_val" ).text( "$" + ui.value );
					$( "#ui_slider_default_val" ).val( "$" + ui.value );
				}
			});
			$( ".ui_slider1_val" ).text( "$" + $( ".ui_slider1" ).slider( "value" ) );
			$( "#ui_slider_default_val" ).val( "$" + $( ".ui_slider1" ).slider( "value" ) );
			//range slider
			$( ".ui_slider2" ).slider({
				range: true,
				min: 0,
				max: 500,
				values: [ 75, 300 ],
				slide: function( event, ui ) {
					$( ".ui_slider2_val" ).text( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
					$( "#ui_slider_min_val" ).val( "$" + ui.values[ 0 ] );
					$( "#ui_slider_max_val" ).val( "$" + ui.values[ 1 ] );
				}
			});
			$( ".ui_slider2_val" ).text( "$" + $( ".ui_slider2" ).slider( "values", 0 ) + " - $" + $( ".ui_slider2" ).slider( "values", 1 ) );
			$( "#ui_slider_min_val" ).val( "$" + $( ".ui_slider2" ).slider( "values", 0 ) );
			$( "#ui_slider_max_val" ).val( "$" + $( ".ui_slider2" ).slider( "values", 1 ) );
		}
	};

	//* masked input
	prth_mask_input = {
		init: function() {
			$("#mask_date").inputmask("99/99/9999",{placeholder:"dd/mm/yyyy"});
			$("#mask_phone").inputmask("(999) 999-9999");
			$("#mask_ssn").inputmask("999-99-9999");
			$("#mask_product").inputmask("a*-999-a999",{placeholder:" "});
		}
	};

	//* tag handler
	prth_tag_handler = {
		init: function() {
			$("#array_tag_handler").tagHandler({
				assignedTags: [ 'C', 'Perl', 'PHP' ],
				availableTags: [ 'C', 'C++', 'C#', 'Java', 'Perl', 'PHP', 'Python' ],
				autocomplete: true
			});
			$("#max_tags_tag_handler").tagHandler({
				assignedTags: [ 'Perl' ],
				availableTags: [ 'C', 'C++', 'C#', 'Java', 'Perl', 'PHP', 'Python' ],
				autocomplete: true,
				maxTags:5
			});
		}
	};

	//* spinners
	prth_spinner = {
		init: function() {
			$("#sp_basic").spinner();
			$("#sp_dec").spinner({
				decimals: 2,
				stepping: 0.25
			});
			$("#sp_currency").spinner({
				currency: '$',
				max: 20,
				min: 2
			});
			$("#sp_list").spinner();
			$("#sp_users").spinner({
				format: ' <a href="%(url)">%(title)</a>',
				items: itemList // var 'itemList' set inline in form_extended.php
			});
		}
	};

	//* textarea limiter
	prth_limiter = {
		init: function(){
			$("#txtarea_limit_chars").counter({
				goal: 120
			});
			$("#txtarea_limit_words").counter({
				goal: 20,
				type: 'word'
			});
		}
	};

	//* rating
	prth_rating = {
		init: function() {
			$('#rating_def').raty({ path: "lib/raty/img/", start: 4 });
			$('#rating_half').raty({
				half		: true,
				path		: "lib/raty/img/",
				start		: 2.5
			});
			$('#rating_cancel').raty({
				cancel		: true,
				path		: "lib/raty/img/",
				start		: 1,
				cancelHint	: 'none',
				target		: '#raty_hint'
			});
			$('#rating_custom').raty({
				path		: "lib/raty/img/",
				iconRange	: [
								{ range: 2, on: 'face-a.png', off: 'face-a-off.png' },
								{ range: 3, on: 'face-b.png', off: 'face-b-off.png' },
								{ range: 4, on: 'face-c.png', off: 'face-c-off.png' },
								{ range: 5, on: 'face-d.png', off: 'face-d-off.png' }
							],
				start		: 3
			});
		}
	};

	//* iphone styles checkboxes
	prth_ios_checkboxes = {
		init: function(){
			$('.iRadio_btn').iButton();
			$('#long_tiny').iButton({
				labelOn		: "A really long label"
				,labelOff	: "Tiny"
			});
			$('#css_sized_container').iButton({
				className	: "ibtn_res"
			});
			$('#iButtons_radio :radio').iButton({allowRadioUncheck: true});
		}
	};



// ----- Misc ----- //

	//* datatables
	prth_datatable = {
		dt1: function(){
			$('#dt1').dataTable({
				"aaSorting": [[ 1, "asc" ]],
				"aoColumns": [
					{ "bSortable": false },
					null,
					null,
					{ "sType": "formatted-num" },
					{ "sType": "formatted-num" }
				]
			});
		},
		dt2: function(){
			$('#dt2').dataTable({
				"sPaginationType": "full_numbers",
				"sDom": 'C<"clear">lfrtip'
			});
		},
		ct: function(){
			$('#content_table').dataTable({
				"aaSorting": [[ 2, "asc" ]],
				"aoColumns": [
					{ "bSortable": false },
					{ "sType": "natural" },
					{ "sType": "string" },
					{ "bSortable": false },
					{ "sType": "eu_date" },
					{ "bSortable": false }
				]
			});
		},
		mobile_dt: function(){
			if( $(".mobile_dt1 th").hasClass('chb_col')){
				$(".mobile_dt1 .chb_col").remove()
			};
			$(".mobile_dt1").table({
			   idprefix: "co1-",
			   persist: "essential"
			});
			if( $(".mobile_dt2 th").hasClass('chb_col')){
				$(".mobile_dt2 .chb_col").remove()
			};
			$(".mobile_dt2").table({
			   idprefix: "co2-",
			   persist: "essential"
			});
			if( $(".mobile_dt3 th").hasClass('chb_col')){
				$(".mobile_dt3 .chb_col").remove()
			};
			$(".mobile_dt3").table({
			   idprefix: "co3-",
			   persist: "essential"
			});
		},
		dt_gal: function(){
			$('#dt_gal').dataTable({
				"aaSorting": [[ 2, "asc" ]],
				"aoColumns": [
					{ "bSortable": false },
					{ "bSortable": false },
					{ "sType": "string" },
					{ "sType": "formatted-num" },
					{ "sType": "eu_date" },
					{ "bSortable": false }
				]
			});
		},
		dt_gal_mobile: function(){
			if( $("#dt_gal th").hasClass('chb_col')){
				$("#dt_gal .chb_col").remove()
			};
			$("#dt_gal").table({
			   idprefix: "co1-",
			   persist: "essential"
			});
		},
		dt_actions: function() {
			$('.chSel_all').click(function () {
				$(this).closest('table').find('input[name=row_sel]').attr('checked', this.checked);
			});
			//$('table.dt_act').each(function(){
			//	$(this).before('<div style="clear:both;position:relative;top:5px" class="medium table_act"><a href="javascript:void(0)" class="delete_all_rows">Delete selected rows</a></div>');
			//});
			//$('.delete_all_rows').click( function() {
			//	var target = $(this).closest('div.table_act').next('table.dt_act');
			//	oTable = $('#'+target.attr('id')).dataTable();
			//	$('input[@name=row_sel]:checked', oTable.fnGetNodes()).closest('tr').fadeTo(600, 0, function () {
			//		oTable.fnDeleteRow( this );
			//		$( '.chSel_all', '#'+target.attr('id') ).attr('checked',false);
			//		return false;
			//	});
			//	return false;
			//});
		}
	};

	//* file manager
	prth_fileManager = {
		init: function(){
			$('#explorer').elfinder({
				url : 'lib/elfinder/php/connector.php',
				lang : 'en',
				contextmenu : {
					navbar :
					  ['open', '|', 'info'],
					cwd    :
					  ['reload', 'back', '|', 'info'],
					files  :
					  ['getfile', '|','open', 'quicklook', '|', 'download', '|', 'info']
				},
				allowShortcuts: false
			});
		}
	};

	//* faq/help accordion
	prth_help = {
		init: function(){
			$('#st-accordion').st_accordion({
				oneOpenedItem	: true
			});
		},
		//* faq/help navigation
		navigator: function(){
			$('#st-accordion').closest('.box_c_content').prepend('<select class="st-nav" style="margin-bottom:20px" />');
			$('.st-nav').append('<option value="">--- Help/Faq Content ---</option>');
			$('#st-accordion .top_Ha').each(function(){
				$('.st-nav').append('<option value="'+ $(this).closest('li').attr('id') +'">'+ $(this).contents().filter(function() {return this.nodeType == 3; }).text() +'</option>');
			});
			$('.st-nav').change(function(){
				$('#st-accordion .st-content').each(function(){
					if($(this).is(':visible')){
						$(this).prev('a').click();
					}
				});
				$('#'+$(this).val()).children('a:first').click();
				$(this).children('option:first').selected(true);
			});
		}
	};

	//* documentation accordion
	prth_documentation = {
		init: function(){
			$('#st-documentation').st_accordion({
				oneOpenedItem	: true
			});
			$('#st-documentation a[href^="#"]').click(function(){
				$($(this).attr('href')).children('a:first').click();
			});
		},
		navigator: function(){
			$('<select class="st-nav" style="margin-bottom:13px" />').appendTo('#st-navBox');
			$('.st-nav').append('<option value="">--- Documentation Content ---</option>');
			$('#st-documentation .top_Ha').each(function(){
				$('.st-nav').append('<option value="'+ $(this).closest('li').attr('id') +'">'+ $(this).contents().filter(function() {return this.nodeType == 3; }).text() +'</option>');
			});
			$('.st-nav').change(function(){
				$('#st-documentation .st-content').each(function(){
					if($(this).is(':visible')){
						$(this).prev('a').click();
					}
				});
				$('#'+$(this).val()).children('a:first').click();
				$(this).children('option:first').selected(true);
			})
		}
	};

	//* calendar
	prth_calendar = {
		init: function() {
			var date = new Date();
			var d = date.getDate();
			var m = date.getMonth();
			var y = date.getFullYear();

			var calendar = $('#calendar').fullCalendar({
				header: {
					left: 'prev,next today',
					center: 'title',
					right: 'month,agendaWeek,agendaDay'
				},
				buttonText: {
					prev: '<img src="theme/blank.gif" alt="" class="table_prev" />',
					next: '<img src="theme/blank.gif" alt="" class="table_next" />'
				},
				aspectRatio: 2,
				selectable: true,
				selectHelper: true,
				select: function(start, end, allDay) {
					var title = prompt('Event Title:');
					if (title) {
						calendar.fullCalendar('renderEvent',
							{
								title: title,
								start: start,
								end: end,
								allDay: allDay
							},
							true // make the event "stick"
						);
					}
					calendar.fullCalendar('unselect');
				},
				editable: true,
				theme: false,
				events: [
					{
						title: 'All Day Event',
						start: new Date(y, m, 1),
						color: '#058DC7'
					},
					{
						title: 'Long Event',
						start: new Date(y, m, d-5),
						end: new Date(y, m, d-2),
						color: '#058DC7'
					},
					{
						id: 999,
						title: 'Repeating Event',
						start: new Date(2011, 11, 7, 16, 0),
						allDay: false,
						color: '#058DC7'
					},
					{
						id: 999,
						title: 'Repeating Event',
						start: new Date(y, m, d-16, 16, 0),
						allDay: false,
						color: '#058DC7'
					},
					{
						title: 'Meeting',
						start: new Date(2011, 10, 21, 10, 30),
						end: new Date(2011, 10, 21, 12, 30),
						allDay: false,
						color: '#058DC7'
					},
					{
						title: 'Lunch',
						start: new Date(y, m, d, 12, 0),
						end: new Date(y, m, d, 14, 0),
						allDay: false,
						color: '#058DC7'
					},
					{
						title: 'Birthday Party',
						start: new Date(y, m, d+1, 19, 0),
						end: new Date(y, m, d+1, 22, 30),
						allDay: false,
						color: '#058DC7'
					},
					{
						title: 'Click for Google',
						start: new Date(y, m, 28),
						end: new Date(y, m, 29),
						url: 'http://google.com/',
						color: '#058DC7'
					}
				]
			})
		}
	};

	//* scrollable gallery
	prth_gallery = {
		init: function(){
			$(".img_wrapper").on('mouseenter',function(){
				$(this).append('<span class="img_overlay"></span>');
				$(this).children('.img_overlay').stop().animate({opacity: 1},400);
			}).on('mouseleave',function(){
				$(this).children('.img_overlay').stop().animate({opacity: 0 },200, function(){ $(this).remove() });
			});
			$('a.fancybox').attr('rel', 'gallery').fancybox({
				'overlayOpacity'	: '0.2',
				'transitionIn'	: 'elastic',
				'transitionOut'	: 'fade'
			});
			prth_gallery.gallery_resize();
		},
		gallery_resize: function() {
			var colWrap =  $('.gal_scrollable').closest('.box_c_content').width();
			var colNum = Math.floor(colWrap / 92);
			var colFixed = Math.floor(colWrap / colNum);
			$(".gallery_list").css({ 'width' : colWrap });
			$(".gallery_list li").css({ 'width' : colFixed, 'heigh' : colFixed});
			$('.gal_scrollable').css({ 'height' : Math.ceil(( 12 / colNum))*colFixed + 4 });
		}
	};

	//* letter navigation list
	prth_contacts_list = {
		init: function() {
			$('#contactList').listnav({
				includeAll: true,
				includeOther: false,
				flagDisabled: true,
				showCounts: true //changed to qtip2, not visible on touch devices
			});
		}
	};

	//* user notification bar
	prth_sticky_notifications = {
		init: function() {
			$(".ntf_bar a").click(function(){
				$($(this).attr('href')).sticky();
				return false;
			});
		}
	};

	//* sidebar
	prth_sidebar = {
		init: function(){
			$('#mainCol').prepend('<div class="divider"/>').css('margin-left','250px');
			$('.divider').height($('#sidebar').height() - 30 );
			$('#mainCol .divider').toggle(function(){
				$('.divider').hide();
				$('#sidebar').animate({	opacity: 0 }, 300,
					function(){
						$('#sidebar').hide();
						$('#mainCol').animate({ marginLeft: 30 },500,function(){
							$('.divider').height($('#mainCol').height() - 2).show().addClass('colapsed');
							//sticky footer
							prth_stickyFooter.resize();
						});
						
					}
				);
			},function(){
				$('.divider').hide().removeClass('colapsed');
				$('#mainCol').animate({	marginLeft: 250 }, 500,
					function(){
						var sidebarHeight = $('#sidebar').width(220).show().height();
						$('.divider').height(sidebarHeight - 30).show();
						$('#sidebar').animate({ opacity: 1 },300);
						//sticky footer
						prth_stickyFooter.resize();
					}
				);
			});
		}
	};

	//* nested accordion
	prth_nested_accordion = {
		init: function() {
			// accordion
			$("#nestedAcc").accordion({
				objID: "#acc",
				el: ".h",
				head: "h4, h5",
				next: "div",
				initShow : "div.shown",
				standardExpansible : false
			});
			//expand all sections
			$("#nestedAcc .accordion").expandAll({
				trigger: ".h",
				ref: "h4.h",
				cllpsEl : "div.outer",
				speed: 200,
				oneSwitch : true,
				instantHide: false
			});
			$('.accordion h4 a,.accordion h5 a').append('<img src="theme/blank.gif" />');
		}
	};

	//* sparklines (tiny charts)
	prth_sparkLines = {
		init: function() {
			var mdraw = 0;
			$.fn.sparkline.defaults.common.width = '80px';
			$.fn.sparkline.defaults.common.height = '30px';
			$.fn.sparkline.defaults.common.lineColor = '#2588D4';
			$.fn.sparkline.defaults.common.fillColor = '#EBF4FB';
			$.fn.sparkline.defaults.common.spotColor = '';
			$('.sprk_b').sparkline(sprk_b);
			$('.sprk_c').sparkline(sprk_c,{ type:'bar', barColor:'#50B432' });
			$('.sprk_d').sparkline(sprk_d,{ type:'bar', barColor:'#2588D4' });
			//animated sparkline
			var mrefreshinterval = 500;
			var lastmousex=-1;
			var lastmousey=-1;
			var lastmousetime;
			var mousetravel = 0;
			var mpoints = [];
			var mpoints_max = 30;
			$('html').mousemove(function(e) {
				var mousex = e.pageX;
				var mousey = e.pageY;
				if (lastmousex > -1)
					mousetravel += Math.max( Math.abs(mousex-lastmousex), Math.abs(mousey-lastmousey) );
				lastmousex = mousex;
				lastmousey = mousey;
			});
			mdraw = function() {
				var md = new Date();
				var timenow = md.getTime();
				if (lastmousetime && lastmousetime!=timenow) {
					var pps = Math.round(mousetravel / (timenow - lastmousetime) * 1000);
					mpoints.push(pps);
					if (mpoints.length > mpoints_max)
						mpoints.splice(0,1);
					mousetravel = 0;
					$('.sprk_a').sparkline(mpoints, { width: mpoints.length*2 });
				}
				lastmousetime = timenow;
				mtimer = setTimeout(mdraw, mrefreshinterval);
			}
			var mtimer = setTimeout(mdraw, mrefreshinterval);
			$('.sprkList').css('visibility','visible');
		}
	};

	//* dialog boxes
	prth_dialogs = {
		init: function(){
			//notification with simple countdown (opened on page load)
			var count = 10;
			countdown = setInterval(function(){
				if(count == 10){
					$('body').showMessage({
						thisMessage		: ['Sample successful message that will automatically close in <span class="sMsg_countown">10</span> seconds.'],
						className		: 'success',
						autoClose		: true,
						delayTime		: 10000 //in miliseconds
					});
				}
				if(count == 0){
					clearInterval(countdown);
				}
				$(".sMsg_countown").html(count);
				count--;
			}, 1000);
			$('.t_success').click(function() {
				$('body').showMessage({
					'thisMessage'		: ['This is a sample successful message that will automatically close'],
					className			: 'success',
					displayNavigation	: false,
					autoClose			: true
				});
				return false;
			});
			$('.t_failure').click(function() {
				$('body').showMessage({
					'thisMessage'		: ['This is a sample of an error message that will automatically close (esc key will not work)'],
					className			: 'fail',
					displayNavigation	: false,
					useEsc				: false,
					autoClose			: true
				});
				return false;
			});
			$('.b_alert').click(function() {
				$('body').showMessage({
					thisMessage			: ['This is a sample of a message that appears at bottom and automatically close.'],
					position			: 'bottom',
					displayNavigation	: false,
					autoClose			: true,
					className			: 'alert btm'
				});
				return false;
			});
			$('.b_info').click(function() {
				$('body').showMessage({
					thisMessage			: ['This is generic message one','This is generic message two','This is generic message three'],
					closeText			: 'close me',
					position			: 'bottom',
					escText				: 'esc key or',
					className			: 'info btm',
					autoClose			: false
				});
				return false;
			});
			//sticky boxes
			$(".stck_ntf a").click(function(){
				$($(this).attr('href')).sticky();
				return false;
			});
			//modal boxes
			$("#fd1").fancybox({
				'overlayOpacity'	: '0.2',
				'transitionIn'		: 'elastic',
				'transitionOut'		: 'fade',
				'autoDimensions'	: false,
				'width'         	: '50%',
				'height'        	: 'auto'
			});
			$("#fd2").fancybox({
				href	: 'lib/autocomplete/search_result.php',
				ajax : {
					type			: "POST",
					data			: "search_item=Cambridge"
				},
				'overlayOpacity'	: '0.2',
				'transitionIn'		: 'elastic',
				'transitionOut'		: 'fade'
			});
			$("#fd3").fancybox({
				'overlayOpacity'	: '0.2',
				'transitionIn'		: 'elastic',
				'transitionOut'		: 'fade',
				'onCleanup'			: function() {
					if($('.fd3_pane:first').is(':hidden')){$('.fd3_pane').toggle();$.fancybox.resize();}
					$('.fd3_pane label.error').remove();
				}
			});
			$('.fd3_submit').click(function(){
				var thisName = $('.fd3_name_input').val();
				if (thisName == ''){
					$('.fd3_name_input').after('<label class="error">Please enter your name.</label>');
					$.fancybox.resize();
				}else{
					$('.fd3_pane label.error').remove();
					$('.fd3_name_input').val('');
					$('.fd3_pane').slideToggle('slow');
					$('.fd3_name').text(thisName);
					$.fancybox.resize();
				}
				return false;
			});
		}
	};

	//* rss/atom feed
	prth_rss = {
		init: function(){
			$('#txtUrl').val('http://rss.cnn.com/rss/edition.rss');
			$('#txtCount').val('5');
			$('#chkDate').attr('checked','checked');
			$('#chkDesc').attr('checked','checked');
			$('#divRss').FeedEk({
				FeedUrl 	: 'http://www.theverge.com/rss/index.xml',
				MaxCount 	: 3,
				ShowDesc 	: true,
				ShowPubDate	: true
			});
			$('#changeFeedUrl').click( function(event) {
				event.preventDefault();
				var cnt= 5;
				var showDate=new Boolean();
				showDate=true;
				var showDescription=new Boolean();
				showDescription=true;
				if($('#txtCount').val()!="") cnt=parseInt($('#txtCount').val());
				if (! $('#chkDate').attr('checked')) showDate=false;
				if (! $('#chkDesc').attr('checked')) showDescription=false;
				$('#divRss').FeedEk({
					FeedUrl		: $('#txtUrl').val(),
					MaxCount 	: cnt,
					ShowDesc 	: showDescription,
					ShowPubDate	: showDate
				});
			});
			$('#feedOptions').click(function(event){
				event.preventDefault();
				$('#divSrc').slideToggle();
			})
		}
	};

	//* filterable list
	prth_flist = {
		init: function(){
			var pagingOptions = {};
			var options = {
				valueNames: [ 'sl_name', 'sl_status', 'sl_email' ],
				page: 10,
				plugins: [
					[ 'paging', {
						pagingClass	: "bottomPaging",
						innerWindow	: 1,
						left		: 1,
						right		: 1
					} ]
				]
			};
			var userList = new List('user-list', options);
			$('#filter-online').click(function() {
				$('dl.filter dd').removeClass('active');
				$(this).parent('dd').addClass('active');
				userList.filter(function(values) {
					if (values.sl_status == "online") {
						return true;
					} else {
						return false;
					}
				});
				return false;
			});
			$('#filter-offline').click(function() {
				$('dl.filter dd').removeClass('active');
				$(this).parent('dd').addClass('active');
				userList.filter(function(values) {
					if (values.sl_status == "offline") {
						return true;
					} else {
						return false;
					}
				});
				return false;
			});
			$('#filter-none').click(function() {
				$('dl.filter dd').removeClass('active');
				$(this).parent('dd').addClass('active');
				userList.filter();
				return false;
			});
			$('#user-list').on('click','.sort',function(){
					$('.sort').parent('dd').removeClass('active');
					if($(this).parent('dd').hasClass('active')) {
						$(this).parent('dd').removeClass('active');
					} else {
						$(this).parent('dd').addClass('active');
					}
				}
			)
		}
	};

	//* wizard
	prth_wizard = {
		simple: function(){
			$('#simple_wizard').stepy({
				titleClick	: true
			});
		},
		validation: function(){
			$('#validate_wizard').stepy({
				backLabel	: 'Previous',
				block		: true,
				errorImage	: true,
				nextLabel	: 'Next',
				titleClick	: true,
				validate	: true
			});
			$('#validate_wizard').validate({
				errorPlacement: function(error, element) {
					error.appendTo( element.closest("div.elVal") );
				}, highlight: function(element) {
					$(element).closest('div.elVal').addClass("form-field error");
				}, unhighlight: function(element) {
					$(element).closest('div.elVal').removeClass("form-field error");
				}, rules: {
					'v_username'	: {
						required	: true,
						minlength	: 3
					},
					'v_email'		: 'email',
					'v_newsletter'	: 'required',
					'v_password'	: 'required',
					'v_city'		: 'required',
					'v_country'		: 'required'
				}, messages: {
					'v_username'	: { required:  'Username field is required!' },
					'v_email'		: { email	:  'Invalid e-mail!' },
					'v_newsletter'	: { required:  'Newsletter field is required!' },
					'v_password'	: { required:  'Password field is requerid!' },
					'v_city'		: { required:  'City field is requerid!' },
					'v_country'		: { required:  'Country field is requerid!' }
				},
				ignore				: ':hidden'
			});
		},
		//* add numbers to step titles
		steps_nb: function(){
			$('.stepy-titles').each(function(){
				$(this).children('li').each(function(index){
					var myIndex = index + 1
					$(this).append('<span class="stepNb">'+myIndex+'</span>');
				})
			})
		}
	};


//* style switcher
	prth_style_sw = {
		init: function() {
			$('body').append('<a class="ssw_trigger" href="javascript:void(0)"></a>');
			var defClass = $('body').attr('class');
			$('#resetDefault').click(function(){
				$('body').attr('class', defClass).css({'backgroundColor':'','color':''});
				$('a').not('.button,.gh_button,.ntf_small,#main_nav a,.style_switcher a,.tabs a,.img_info').css('color','');
				$('.ssw_mbColor,.ssw_bColor,.ssw_mColor,.ssw_lColor').hide();
				$('.ssw_bColor span,.ssw_mColor span,.ssw_lColor span').html('');
				$('#background_picker').css('backgroundColor','#f7f7f7');
				$('#mainColor_picker').css('backgroundColor','#222');
				$('#link_picker').css('backgroundColor','#21759b');
				$('.style_item').removeClass('style_active').filter(':first-child').addClass('style_active');
				$(".style_switcher").hide();
				$(".ssw_trigger").removeClass('active');
				return false;
			});
			$(".ssw_trigger").click(function(){
				$(".style_switcher").toggle("fast");
				$(this).toggleClass("active");
				return false;
			});
			var cp_onShow = function (colpkr) {	$(colpkr).fadeIn(500); return false; };
			var cp_onHide = function (colpkr) {	$(colpkr).fadeOut(500); return false; };
			//background color
			$('#background_picker').ColorPicker({
				color: '#eeeeee',
				onShow: cp_onShow,
				onHide: cp_onHide,
				onChange: function (hsb, hex, rgb) {
					$('body,#background_picker').css('backgroundColor', '#' + hex);
				}
			}).css('backgroundColor','#eeeeee');
			//link color
			$('#link_picker').ColorPicker({
				color: '#21759b',
				onShow: cp_onShow,
				onHide: cp_onHide,
				onChange: function (hsb, hex, rgb) {
					$('a').not('.button,.gh_button,.ntf_small,#main_nav a,.style_switcher a,.tabs a,.img_info').css('color', '#' + hex);
					$('#link_picker').css('backgroundColor', '#' + hex);
				}
			}).css('backgroundColor','#21759b');
			//main color
			$('#mainColor_picker').ColorPicker({
				color: '#222',
				onShow: cp_onShow,
				onHide: cp_onHide,
				onChange: function (hsb, hex, rgb) {
					$('body,.slider-content .title').css('color', '#' + hex);
					$('#mainColor_picker').css('backgroundColor', '#' + hex);
				}
			}).css('backgroundColor','#222');
			//patterns & gradients
			$('.style_switcher .style_item').click(function(){
				$(this).closest('div').find('.style_item').removeClass('style_active');
				$(this).addClass('style_active');
				var style_selected = $(this).attr('title');
				if($(this).hasClass('jQptrn')) { $('body').removeClass('ptrn_a ptrn_b ptrn_c ptrn_d ptrn_e').addClass(style_selected); };
				if($(this).hasClass('jQgrdnt')) { $('body').removeClass('grdnt_a grdnt_b grdnt_c grdnt_d grdnt_e').addClass(style_selected); };
				if($(this).hasClass('jQmhover')) { $('body').removeClass('mhover_a mhover_b mhover_c mhover_d mhover_e').addClass(style_selected); };
			});
			$('#remove_pattern').click(function(){
				$('body').removeClass('ptrn_a ptrn_b ptrn_c ptrn_d ptrn_e');
				$('.jQptrn').removeClass('style_active');
				return false;
			});
			$('#showCss').click(function(e){
				var contentStyle = '';
				contentStyle = '<div style="padding:20px"><textarea class="expand" style="height:28px;margin-bottom:20px">';
				contentStyle += '&lt;body class="'+$('body').attr('class')+'"&gt;';
				contentStyle += '</textarea>';
				contentStyle += '<textarea class="expand" style="height:100px">';
				contentStyle += 'body {\n  background-color: '+  $('#background_picker').css('backgroundColor') +';\n  color: '+ $('#mainColor_picker').css('backgroundColor') +';\n}\n';
				contentStyle += 'a { color: '+ $('#link_picker').css('backgroundColor') +' }';
				contentStyle += '</textarea></div>';
				$.fancybox({
					'autoDimensions'	: false,
					'width'				: 380,
					'height'			: 200,
					'overlayOpacity'	: '0.2',
					'scrolling'			: 'no',
					'content'			: contentStyle
				});
				e.preventDefault();
			})
		}
	};
