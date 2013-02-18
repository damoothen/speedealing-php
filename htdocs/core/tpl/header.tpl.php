<?php
/* Copyright (C) 2010-2013	Regis Houssin	<regis.houssin@capnetworks.com>
 * Copyright (C) 2011-2013	Herve Prot		<herve.prot@symeos.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */
?>

<!-- Header -->
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<meta name="robots" content="noindex,nofollow" />
	<meta name="author" content="Speedealing Development Team" />

	<title><?php echo $title; ?></title>
	<base href="<?php echo $base_href; ?>" />

	<link rel="top" title="<?php echo $langs->trans("Home"); ?>" href="<?php echo $base_href; ?>">
	<link rel="copyright" title="GNU General Public License" href="http://www.gnu.org/copyleft/gpl.html#SEC1">
	<link rel="author" title="Speedealing Development Team" href="http://www.speedealing.com">

	<!-- For all browsers -->
	<link rel="stylesheet" href="theme/symeos/css/reset.css?v=1">
	<link rel="stylesheet" href="theme/symeos/css/style.css?v=1">
	<link rel="stylesheet" href="theme/symeos/css/colors.css?v=1">
	<link rel="stylesheet" media="print" href="theme/symeos/css/print.css?v=1">

	<!-- For progressively larger displays -->
	<link rel="stylesheet" media="only all and (min-width: 480px)" href="theme/symeos/css/480.css?v=1">
	<link rel="stylesheet" media="only all and (min-width: 768px)" href="theme/symeos/css/768.css?v=1">
	<link rel="stylesheet" media="only all and (min-width: 992px)" href="theme/symeos/css/992.css?v=1">
	<link rel="stylesheet" media="only all and (min-width: 1200px)" href="theme/symeos/css/1200.css?v=1">

	<!-- For Retina displays -->
	<link rel="stylesheet" media="only all and (-webkit-min-device-pixel-ratio: 1.5), only screen and (-o-min-device-pixel-ratio: 3/2), only screen and (min-device-pixel-ratio: 1.5)" href="theme/symeos/css/2x.css?v=1">

	<!-- Symeos -->
	<link rel="stylesheet" href="theme/symeos/css/symeos.css?v=1">

	<!-- Webfonts -->
	<!--<link href='http://fonts.googleapis.com/css?family=Open+Sans:300' rel='stylesheet' type='text/css'>-->

	<!-- Additional styles -->
	<link rel="stylesheet" href="theme/symeos/css/styles/agenda.css?v=1">
	<link rel="stylesheet" href="theme/symeos/css/styles/dashboard.css?v=1">
	<link rel="stylesheet" href="theme/symeos/css/styles/form.css?v=1">
	<link rel="stylesheet" href="theme/symeos/css/styles/modal.css?v=1">
	<link rel="stylesheet" href="theme/symeos/css/styles/progress-slider.css?v=1">
	<link rel="stylesheet" href="theme/symeos/css/styles/switches.css?v=1">
	<link rel="stylesheet" href="theme/symeos/css/styles/table.css?v=1">
	<link rel="stylesheet" href="theme/symeos/css/styles/calendars.css?v=1">

	<!-- DataTables --><!-- jquery UI -->
	<link rel="stylesheet" href="includes/jquery/css/Aristo/Aristo.css" media="all" />

	<!-- JavaScript at bottom except for Modernizr -->
	<script src="includes/js/modernizr.custom.js"></script>

	<!-- For Modern Browsers -->
	<link rel="shortcut icon" href="favicon.png">
	<!-- For everything else -->
	<link rel="shortcut icon" href="favicon.ico">
	<!--<link rel="shortcut icon" type="image/x-icon" href="favicon.ico"/> -->
	<!-- For retina screens -->
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="apple-touch-icon-retina.png">
	<!-- For iPad 1-->
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="apple-touch-icon-ipad.png">
	<!-- For iPhone 3G, iPod Touch and Android -->
	<link rel="apple-touch-icon-precomposed" href="apple-touch-icon.png">

	<!-- iOS web-app metas -->
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">

	<!-- Startup image for web apps -->
	<!--<link rel="apple-touch-startup-image" href="theme/developr/html/img/splash/ipad-landscape.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)">
	<link rel="apple-touch-startup-image" href="theme/developr/html/img/splash/ipad-portrait.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)">
	<link rel="apple-touch-startup-image" href="theme/developr/html/img/splash/iphone.png" media="screen and (max-device-width: 320px)">-->

	<!-- Microsoft clear type rendering -->
	<meta http-equiv="cleartype" content="on">

	<!-- IE9 Pinned Sites: http://msdn.microsoft.com/en-us/library/gg131029.aspx -->
	<meta name="application-name" content="Developr Admin Skin">
	<meta name="msapplication-tooltip" content="Cross-platform admin template.">
	<meta name="msapplication-starturl" content="http://www.display-inline.fr/demo/developr">
	<!-- These custom tasks are examples, you need to edit them to show actual pages -->
	<meta name="msapplication-task" content="name=Agenda;action-uri=http://www.display-inline.fr/demo/developr/html/agenda.html;icon-uri=http://www.display-inline.fr/demo/developr/html/img/favicons/favicon.ico">
	<base name="msapplication-task" content="name=My profile;action-uri=http://www.display-inline.fr/demo/developr/html/profile.html;icon-uri=http://www.display-inline.fr/demo/developr/html/img/favicons/favicon.ico">

	<!-- Includes for JQuery (Ajax library) -->

	<!-- jQuery FileUpload -->
	<link rel="stylesheet" type="text/css" href="includes/jquery/plugins/fileupload/css/jquery.fileupload-ui.css" />
	<!-- jQuery Datatables -->
	<!-- <link rel="stylesheet" type="text/css" href="'.DOL_URL_ROOT.'/includes/jquery/plugins/datatables/media/css/jquery.dataTables.css" /> -->
	<!-- <link rel="stylesheet" type="text/css" href="'.DOL_URL_ROOT.'/includes/jquery/plugins/datatables/media/css/jquery.dataTables_jui.css" /> -->
	<link rel="stylesheet" type="text/css" href="includes/jquery/plugins/datatables/extras/ColReorder/media/css/ColReorder.css" />
	<!-- print '<link rel="stylesheet" type="text/css" href="'.DOL_URL_ROOT.'/includes/jquery/plugins/datatables/extras/ColVis/media/css/ColVis.css" /> -->
	<!-- print '<link rel="stylesheet" type="text/css" href="'.DOL_URL_ROOT.'/includes/jquery/plugins/datatables/extras/ColVis/media/css/ColVisAlt.css" /> -->
	<!-- print '<link rel="stylesheet" type="text/css" href="'.DOL_URL_ROOT.'/includes/jquery/plugins/datatables/extras/TableTools/media/css/TableTools.css" /> -->
	<link rel="stylesheet" type="text/css" href="includes/jquery/plugins/datatables/extras/AutoFill/media/css/AutoFill.css" />
	<!-- jQuery Multiselect -->
	<!-- print '<link rel="stylesheet" type="text/css" href="'.DOL_URL_ROOT.'/includes/jquery/plugins/multiselect/css/ui.multiselect.css" /> -->
	<!-- jQuery Wysiwyg -->
	<link rel="stylesheet" type="text/css" href="includes/jquery/plugins/wysiwyg/css/jquery.wysiwyg.css" />
	<!-- jQuery Taghandler -->
	<link rel="stylesheet" href="includes/jquery/plugins/tagHandler/css/jquery.taghandler.css" media="all" />

	<!-- Includes for modules or specific pages-->
	<link rel="stylesheet" type="text/css" title="default" href="<?php echo $theme; ?>">

	<!-- CSS forced by modules -->

	<!-- CSS forced by pages -->

	<!-- JQuery. Must be before other includes -->
	<script type="text/javascript" src="includes/jquery/js/jquery-latest.min.js"></script>
	<script type="text/javascript" src="includes/jquery/js/jquery-ui-latest.custom.min.js"></script>
	<script type="text/javascript" src="includes/jquery/plugins/globalize/lib/globalize.js"></script>
	<script type="text/javascript" src="includes/jquery/plugins/globalize/lib/cultures/globalize.cultures.js"></script>

	<!-- NOLOGIN section (obsolete?) -->

	<!-- jQuery jeditable -->
	<script type="text/javascript" src="includes/jquery/plugins/jeditable/jquery.jeditable.min.js"></script>
	<script type="text/javascript" src="includes/jquery/plugins/jeditable/jquery.jeditable.ui-datepicker.js"></script>
	<script type="text/javascript" src="includes/jquery/plugins/jeditable/jquery.jeditable.ui-autocomplete.js"></script>
	<script type="text/javascript" src="includes/jquery/plugins/jeditable/jquery.jeditable.wysiwyg.js"></script>
	<script type="text/javascript" src="includes/jquery/plugins/wysiwyg/jquery.wysiwyg.js"></script>
	<script type="text/javascript">
		var urlSaveInPlace = 'core/ajax/saveinplace.php';
		var urlAddInPlace = 'core/ajax/addinplace.php';
		var tagSaveInPlace = 'core/ajax/savetaghandler.php';
		var urlLoadInPlace = 'core/ajax/loadinplace.php';
		var tagLoadInPlace = 'core/ajax/loadtaghandler.php';
		var tooltipInPlace = '<?php echo $langs->transnoentities('ClickToEdit'); ?>';
		var placeholderInPlace = '<?php echo $langs->trans('ClickToEdit'); ?>';
		var cancelInPlace = '<?php echo $langs->trans('Cancel'); ?>';
		var submitInPlace = '<?php echo $langs->trans('Ok'); ?>';
		var indicatorInPlace = '<img src="theme/<?php echo $conf->theme; ?>/img/working.gif">';
		var ckeditorConfig = '<?php echo dol_buildpath('/theme/' . $conf->theme . '/ckeditor/config.js', 1); ?>';
	</script>
	<script type="text/javascript" src="core/js/editinplace.js"></script>
	<script type="text/javascript" src="includes/jquery/plugins/jeditable/jquery.jeditable.ckeditor.js"></script>
	<!-- jQuery File Upload -->
	<script type="text/javascript" src="includes/jquery/plugins/template/tmpl.min.js"></script>
	<!-- <script type="text/javascript" src="includes/jquery/plugins/fileupload/js/jquery.iframe-transport.js"></script> -->
	<script type="text/javascript" src="includes/jquery/plugins/fileupload/js/jquery.fileupload.js"></script>
	<script type="text/javascript" src="includes/jquery/plugins/fileupload/js/jquery.fileupload-fp.js"></script>
	<script type="text/javascript" src="includes/jquery/plugins/fileupload/js/jquery.fileupload-ui.js"></script>
	<script type="text/javascript" src="includes/jquery/plugins/fileupload/js/jquery.fileupload-jui.js"></script>
	<!-- jQuery Timepicker -->
	<script type="text/javascript" src="includes/jquery/plugins/timepicker/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="includes/jquery/plugins/timepicker/localization/jquery-ui-timepicker-<?php echo substr($langs->getDefaultLang(), 0, 2); ?>.js"></script>
	<!-- jQuery Other -->
	<script type="text/javascript" src="includes/js/jquery.inputmask.js"></script>
	<script type="text/javascript" src="includes/js/jquery.inputmask.extentions.js"></script>
	<script type="text/javascript" src="includes/jquery/plugins/spinner/ui.spinner.min.js"></script>
	<script type="text/javascript" src="includes/jquery/plugins/tagHandler/js/jquery.taghandler.min.js"></script>

	<!-- END NOLOGIN -->

	<!-- jQuery DataTables -->
	<script type="text/javascript" src="includes/jquery/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
	<!-- <script type="text/javascript" src="includes/jquery/plugins/datatables/js/dataTables.plugins.js"></script> -->
	<script type="text/javascript" src="includes/jquery/plugins/datatables/extras/ColReorder/media/js/ColReorder.min.js"></script>
	<script type="text/javascript" src="includes/jquery/plugins/datatables/extras/ColVis/media/js/ColVis.min.js"></script>
	<script type="text/javascript" src="includes/jquery/plugins/datatables/extras/TableTools/media/js/TableTools.min.js"></script>
	<script type="text/javascript" src="includes/jquery/plugins/datatables/extras/AutoFill/media/js/AutoFill.min.js"></script>
	<script type="text/javascript" src="includes/jquery/plugins/datatables/extras/AjaxReload/media/js/fnReloadAjax.js"></script>
	<script type="text/javascript" src="includes/jquery/plugins/datatables/extras/DataTables-Editable/media/js/jquery.dataTables.editable.js"></script>
	<!-- <script type="text/javascript" src="includes/jquery/plugins/datatables/js/initXHR.js"></script> -->
	<!-- <script type="text/javascript" src="includes/jquery/plugins/datatables/js/searchColumns.js"></script> -->
	<!-- <script type="text/javascript" src="includes/jquery/plugins/datatables/js/ZeroClipboard.js"></script> -->
	<!-- jQuery Multiselect -->
	<!-- <script type="text/javascript" src="includes/jquery/plugins/multiselect/js/ui.multiselect.js"></script> -->

	<!-- HighChart -->
	<script type="text/javascript" src="includes/jquery/plugins/highcharts/js/highcharts.js"></script>
	<!-- Highstock -->
	<script type="text/javascript" src="includes/jquery/plugins/highstock/js/highstock.js"></script>
	<script type="text/javascript" src="includes/jquery/plugins/highcharts/js/themes/symeos.js"></script>

	<!-- CKEditor -->
	<script type="text/javascript">var CKEDITOR_BASEPATH = '<?php echo DOL_URL_ROOT; ?>/includes/ckeditor/';</script>
	<script type="text/javascript" src="includes/ckeditor/ckeditor_basic.js"></script>

	<!-- BEGIN THEME -->
	<script type="text/javascript" src="includes/js/jquery.ui.extend.js"></script>
	<script type="text/javascript" src="includes/jquery/plugins/qtip2/jquery.qtip.min.js"></script>
	<!-- <script type="text/javascript" src="includes/lib/fullcalendar/fullcalendar.min.js"></script> -->
	<script type="text/javascript" src="includes/lib/stepy/js/jquery.stepy.min.js"></script>
	<script type="text/javascript" src="includes/lib/validate/jquery.validate.min.js"></script>
	<script type="text/javascript" src="includes/lib/validate/localization/messages_<?php echo substr($langs->getDefaultLang(), 0, 2); ?>.js"></script>
	<!-- <script type="text/javascript" src="includes/js/jquery.list.min.js"></script> -->
	<script type="text/javascript" src="includes/js/jquery.rwd-table.js"></script>
	<!-- END THEME -->

	<!-- jQuery Datepicker default options -->
	<script type="text/javascript" src="core/js/datepicker.js.php?lang=<?php echo $langs->defaultlang; ?>"></script>

	<!-- tooltips -->
	<link rel="stylesheet" href="includes/jquery/plugins/qtip2/jquery.qtip.min.css" />
	<!-- chosen (select element extended) -->
	<!-- <link rel="stylesheet" href="includes/jquery/plugins/chosen/chosen.css" media="all" /> -->
	<!-- datatables -->
	<link rel="stylesheet" href="includes/jquery/plugins/datatables/css/demo_table_jui.css" media="all" />

	<!-- main styles -->
	<link rel="stylesheet" href="theme/eldy/style.css" />
