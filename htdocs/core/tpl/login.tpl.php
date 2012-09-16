<?php
/* Copyright (C) 2009-2010 Regis Houssin 		<regis@dolibarr.fr>
 * Copyright (C) 2011-2012 Laurent Destailleur 	<eldy@users.sourceforge.net>
 * Copyright (C) 2011-2012 Herve 				<herve.prot@symeos.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
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

header('Cache-Control: Public, must-revalidate');
header("Content-type: text/html; charset=" . $conf->file->character_set_client);
?>
﻿<!DOCTYPE html>

<!--[if IEMobile 7]><html class="no-js iem7 oldie linen"><![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html class="no-js ie7 oldie linen" lang="en"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html class="no-js ie8 oldie linen" lang="en"><![endif]-->
<!--[if (IE 9)&!(IEMobile)]><html class="no-js ie9 linen" lang="en"><![endif]-->
<!--[if (gt IE 9)|(gt IEMobile 7)]><!--><html class="no-js linen" lang="en"><!--<![endif]-->

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="robots" content="noindex,nofollow" />
        <meta name="author" content="Speedealing Development Team" />
        <meta charset="utf-8" />
        <base href="http://<?php echo $_SERVER['HTTP_HOST'] . DOL_URL_ROOT; ?>/" />

        <title><?php echo $langs->trans('Login') . ' ' . $title; ?></title>
        <meta name="description" content="">

        <!-- http://davidbcalhoun.com/2010/viewport-metatag -->
        <meta name="HandheldFriendly" content="True">
        <meta name="MobileOptimized" content="320">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

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

        <!-- Additional styles -->
        <link rel="stylesheet" href="theme/symeos/css/styles/form.css?v=1">
        <link rel="stylesheet" href="theme/symeos/css/styles/switches.css?v=1">

        <!-- Login pages styles -->
        <link rel="stylesheet" media="screen" href="theme/symeos/css/login.css?v=1">

        <!-- JavaScript at bottom except for Modernizr -->
        <script src="includes/js/modernizr.custom.js"></script>

        <!-- For Modern Browsers -->
        <link rel="shortcut icon" href="favicon.png">
        <!-- For everything else -->
        <link rel="shortcut icon" href="favicon.ico">
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
        <meta name="msapplication-task" content="name=Agenda;action-uri=http://www.display-inline.fr/demo/developr/agenda.html;icon-uri=http://www.display-inline.fr/demo/developr/img/favicons/favicon.ico">
        <meta name="msapplication-task" content="name=My profile;action-uri=http://www.display-inline.fr/demo/developr/profile.html;icon-uri=http://www.display-inline.fr/demo/developr/img/favicons/favicon.ico">

        <?php
        print '<meta name="viewport" content="width=device-width, initial-scale=1.0" />
                <!-- Foundation framework -->';
        //print '<link rel="stylesheet" href="' . DOL_URL_ROOT . '/theme/pertho_sample/foundation/stylesheets/foundation.css">';

        print '<!-- main styles -->';
        //print '<link rel="stylesheet" href="' . DOL_URL_ROOT . '/theme/pertho_sample/css/style.css" />';
        if (!empty($conf->global->MAIN_HTML_HEADER))
            print $conf->global->MAIN_HTML_HEADER;
        print '<!-- HTTP_USER_AGENT = ' . $_SERVER['HTTP_USER_AGENT'] . ' -->';
        ?>
    </head>
    <body>
        <div id="container">
            <hgroup id="login-title" class="large-margin-bottom">
                <h1 class="login-title-image">Speedealing</h1>
                <h5>&copy; Symeos</h5>
            </hgroup>
            <form name="login" action="<?php echo $php_self; ?>" method="post" id="form-login">
                <input type="hidden" name="token" value="<?php echo $_SESSION['newtoken']; ?>" />
                <input type="hidden" name="loginfunction" value="loginfunction" />
                <!-- Add fields to send local user information -->
                <input type="hidden" name="tz" id="tz" value="" />
                <input type="hidden" name="dst_observed" id="dst_observed" value="" />
                <input type="hidden" name="dst_first" id="dst_first" value="" />
                <input type="hidden" name="dst_second" id="dst_second" value="" />
                <input type="hidden" name="screenwidth" id="screenwidth" value="" />
                <input type="hidden" name="screenheight" id="screenheight" value="" />
                <div class="elVal">
                    <ul class="inputs black-input large">
                        <!-- The autocomplete="off" attributes is the only way to prevent webkit browsers from filling the inputs with yellow -->
                        <li><span class="icon-user mid-margin-right"></span><input type="text" name="username" id="login" value="<?php echo GETPOST('username') ? GETPOST('username') : $login; ?>" class="input-unstyled" placeholder="<?php echo $langs->trans('Login'); ?>" autocomplete="off"></li>
                        <li><span class="icon-lock mid-margin-right"></span><input type="password" name="password" id="pass" value="" class="input-unstyled" placeholder="<?php echo $langs->trans('Password'); ?>" autocomplete="off"></li>
                    </ul>
                    <button type="submit" class="button glossy full-width huge"><?php echo $langs->trans('Connection'); ?></button>
                </div>
            </form>
        </div>

        <?php
        if (!empty($_SESSION['dol_loginmesg'])) {
            ?>
        <center>
            <table width="60%">
                <tr>
                    <td align="center">
                        <div class="error">
                            <?php echo $_SESSION['dol_loginmesg']; ?>
                        </div>
                    </td>
                </tr>
            </table>
        </center>
        <?php
    }
    ?>

    <?php
    if ($main_home) {
        ?>
        <center>
            <table summary="info" cellpadding="0" cellspacing="0" border="0" align="center" width="750">
                <tr>
                    <td align="center"><?php echo $main_home; ?></td>
                </tr>
            </table>
        </center>
        <?php
    }
    ?>

    <?php
    if (!empty($conf->global->MAIN_GOOGLE_AD_CLIENT) && !empty($conf->global->MAIN_GOOGLE_AD_SLOT)) {
        ?>
        <div align="center"><br>
            <script type="text/javascript"><!--
                google_ad_client = "<?php echo $conf->global->MAIN_GOOGLE_AD_CLIENT ?>";
                google_ad_slot = "<?php echo $conf->global->MAIN_GOOGLE_AD_SLOT ?>";
                google_ad_width = <?php echo $conf->global->MAIN_GOOGLE_AD_WIDTH ?>;
                google_ad_height = <?php echo $conf->global->MAIN_GOOGLE_AD_HEIGHT ?>;
                //-->
            </script>
            <script type="text/javascript"
                    src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
            </script>
        </div>
        <?php
    }
    ?>

    <!-- authentication mode = <?php echo $main_authentication ?> -->
    <!-- cookie name used for this session = <?php echo $session_name ?> -->
    <!-- urlfrom in this session = <?php echo $_SESSION["urlfrom"] ?> -->

    <?php if (!empty($conf->global->MAIN_HTML_FOOTER)) print $conf->global->MAIN_HTML_FOOTER; ?>

    <script src="includes/jquery/js/jquery-1.8.1.min.js"></script>
    <script src="includes/lib/validate/jquery.validate.min.js"></script>
    <script src="theme/symeos/js/setup.js"></script>

    <!-- Template functions -->
    <script src="theme/symeos/js/developr.input.js"></script>
    <script src="theme/symeos/js/developr.message.js"></script>
    <script src="theme/symeos/js/developr.notify.js"></script>
    <script src="theme/symeos/js/developr.tooltip.js"></script>

    <script>

        /*
         * How do I hook my login script to this?
         * --------------------------------------
         *
         * This script is meant to be non-obtrusive: if the user has disabled javascript or if an error occurs, the login form
         * works fine without ajax.
         *
         * The only part you need to edit is the login script between the EDIT SECTION tags, which does inputs validation
         * and send data to server. For instance, you may keep the validation and add an AJAX call to the server with the
         * credentials, then redirect to the dashboard or display an error depending on server return.
         *
         * Or if you don't trust AJAX calls, just remove the event.preventDefault() part and let the form be submitted.
         */
        $(document).ready(function() {
            /*
             * JS login effect
             * This script will enable effects for the login page
             */
            // Elements
            var doc = $('html').addClass('js-login'),
            container = $('#container'),
            formLogin = $('#form-login'),

            // If layout is centered
            centered;
			
            // Handle resizing (mostly for debugging)
            function handleLoginResize()
            {
                // Detect mode
                centered = (container.css('position') === 'absolute');

                // Set min-height for mobile layout
                if (!centered)
                {
                    container.css('margin-top', '');
                }
                else
                {
                    if (parseInt(container.css('margin-top'), 10) === 0)
                    {
                        centerForm(false);
                    }
                }
            };

            // Register and first call
            $(window).bind('normalized-resize', handleLoginResize);
            handleLoginResize();

            /*
             * Center function
             * @param boolean animate whether or not to animate the position change
             * @param string|element|array any jQuery selector, DOM element or set of DOM elements which should be ignored
             * @return void
             */
            function centerForm(animate, ignore)
            {
                // If layout is centered
                if (centered)
                {
                    var siblings = formLogin.siblings(),
                    finalSize = formLogin.outerHeight();

                    // Ignored elements
                    if (ignore)
                    {
                        siblings = siblings.not(ignore);
                    }

                    // Get other elements height
                    siblings.each(function(i)
                    {
                        finalSize += $(this).outerHeight(true);
                    });

                    // Setup
                    container[animate ? 'animate' : 'css']({ marginTop: -Math.round(finalSize/2)+'px' });
                }
            };

            // Initial vertical adjust
            centerForm(false);

            /**
             * Function to display error messages
             * @param string message the error to display
             */
            function displayError(message)
            {
                // Show message
                var message = formLogin.message(message, {
                    append: false,
                    arrow: 'bottom',
                    classes: ['red-gradient'],
                    animate: false					// We'll do animation later, we need to know the message height first
                });

                // Vertical centering (where we need the message height)
                centerForm(true, 'fast');

                // Watch for closing and show with effect
                message.bind('endfade', function(event)
                {
                    // This will be called once the message has faded away and is removed
                    centerForm(true, message.get(0));

                }).hide().slideDown('fast');
            }

            /**
             * Function to display loading messages
             * @param string message the message to display
             */
            function displayLoading(message)
            {
                // Show message
                var message = formLogin.message('<strong>'+message+'</strong>', {
                    append: false,
                    arrow: 'bottom',
                    classes: ['blue-gradient', 'align-center'],
                    stripes: true,
                    darkStripes: false,
                    closable: false,
                    animate: false					// We'll do animation later, we need to know the message height first
                });

                // Vertical centering (where we need the message height)
                centerForm(true, 'fast');

                // Watch for closing and show with effect
                message.bind('endfade', function(event)
                {
                    // This will be called once the message has faded away and is removed
                    centerForm(true, message.get(0));

                }).hide().slideDown('fast');
            }

            $(".sl_link").click(function(event){
                $('.l_pane').slideToggle('normal').toggleClass('dn');
                $('.sl_link,.lb_ribbon').children('span').toggle();
                event.preventDefault();
            });

            $("#form-login").validate({
                highlight: function(element) {
                    $(element).closest('.elVal').addClass("form-field error");
                },
                unhighlight: function(element) {
                    $(element).closest('.elVal').removeClass("form-field error");
                },
                rules: {
                    username: "required",
                    password: "required"
                },
                messages: {
                    username: "Please enter your username (type anything)",
                    password: "Please enter a password (type anything)"
                },
                errorPlacement: function(error, element) {
                    error.appendTo( element.closest(".elVal") );
                }
            });

            // What about a notification?
            notify('Bienvenue sur Speedealing', 'Bonne découverte.', {
                autoClose: false,
                delay: 2500,
                icon: 'theme/symeos/img/demo/icon.png'
            });
        });
    </script>
</body>
</html>
<!-- END PHP TEMPLATE -->
