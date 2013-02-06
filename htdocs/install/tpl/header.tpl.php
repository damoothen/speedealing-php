<?php
/* Copyright (C) 2013	Regis Houssin	<regis.houssin@capnetworks.com>
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
<!DOCTYPE html>

<!--[if IEMobile 7]><html class="no-js iem7 oldie linen"><![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html class="no-js ie7 oldie linen" lang="en"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html class="no-js ie8 oldie linen" lang="en"><![endif]-->
<!--[if (IE 9)&!(IEMobile)]><html class="no-js ie9 linen" lang="en"><![endif]-->
<!--[if (gt IE 9)|(gt IEMobile 7)]><!--><html class="no-js linen" lang="en"><!--<![endif]-->

<head>
	<meta http-equiv="content-type" content="text/html; charset='UTF-8'">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<meta name="robots" content="noindex,nofollow" />
	<meta name="author" content="Speedealing Development Team" />
	<title><?php echo $langs->trans("SpeedealingSetup"); ?></title>

	<!-- For all browsers -->
	<link rel="stylesheet" href="<?php echo DOL_URL_ROOT; ?>/theme/symeos/css/reset.css?v=1">
	<link rel="stylesheet" href="<?php echo DOL_URL_ROOT; ?>/theme/symeos/css/style.css?v=1">
	<link rel="stylesheet" href="<?php echo DOL_URL_ROOT; ?>/theme/symeos/css/colors.css?v=1">
	<link rel="stylesheet" media="print" href="<?php echo DOL_URL_ROOT; ?>/theme/symeos/css/print.css?v=1">

	<!-- For progressively larger displays -->
	<link rel="stylesheet" media="only all and (min-width: 480px)" href="<?php echo DOL_URL_ROOT; ?>/theme/symeos/css/480.css?v=1">
	<link rel="stylesheet" media="only all and (min-width: 768px)" href="<?php echo DOL_URL_ROOT; ?>/theme/symeos/css/768.css?v=1">
	<link rel="stylesheet" media="only all and (min-width: 992px)" href="<?php echo DOL_URL_ROOT; ?>/theme/symeos/css/992.css?v=1">
	<link rel="stylesheet" media="only all and (min-width: 1200px)" href="<?php echo DOL_URL_ROOT; ?>/theme/symeos/css/1200.css?v=1">

	<!-- For Retina displays -->
	<link rel="stylesheet" media="only all and (-webkit-min-device-pixel-ratio: 1.5), only screen and (-o-min-device-pixel-ratio: 3/2), only screen and (min-device-pixel-ratio: 1.5)" href="<?php echo DOL_URL_ROOT; ?>/theme/symeos/css/2x.css?v=1">

	<!-- Additional styles -->
	<link rel="stylesheet" href="<?php echo DOL_URL_ROOT; ?>/theme/symeos/css/styles/form.css?v=1">
	<link rel="stylesheet" href="<?php echo DOL_URL_ROOT; ?>/theme/symeos/css/styles/switches.css?v=1">
	<link rel="stylesheet" href="<?php echo DOL_URL_ROOT; ?>/theme/symeos/css/styles/progress-slider.css?v=1">

	<!-- jQuery Form Validation -->
	<link rel="stylesheet" href="<?php echo DOL_URL_ROOT; ?>/theme/symeos/js/libs/formValidator/developr.validationEngine.css?v=1">

	<!-- JavaScript at bottom except for Modernizr -->
	<script src="<?php echo DOL_URL_ROOT; ?>/theme/symeos/js/libs/modernizr.custom.js"></script>
</head>

<body class="full-page-wizard">
