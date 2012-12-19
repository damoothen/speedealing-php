<?php
/* Copyright (C) 2010-2011 Laurent Destailleur  <eldy@users.sourceforge.org>
 * Copyright (C) 2011      Regis Houssin  		<regis@dolibarr.fr>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * \file       htdocs/paypal/lib/paypalfunctions.lib.php
 * \ingroup    paypal
 * \brief      Page with Paypal init var.
 */

if (session_id() == "")
{
    session_start();
    if (ini_get('register_globals'))    // To solve bug in using $_SESSION
    {
        foreach ($_SESSION as $key=>$value)
        {
            if (isset($GLOBALS[$key])) unset($GLOBALS[$key]);
        }
    }
}

// ==================================
// PayPal Express Checkout Module
// ==================================

$API_version="56";

/*
 ' Define the PayPal Redirect URLs.
 '  This is the URL that the buyer is first sent to do authorize payment with their paypal account
 '  change the URL depending if you are testing on the sandbox or the live PayPal site
 '
 ' For the sandbox, the URL is       https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=
 ' For the live site, the URL is        https://www.paypal.com/webscr&cmd=_express-checkout&token=
 */
if (! empty($conf->global->PAYPAL_API_SANDBOX))
{
    $API_Endpoint = "https://api-3t.sandbox.paypal.com/nvp";
    $API_Url = "https://www.sandbox.paypal.com/webscr?cmd=_express-checkout&token=";
}
else
{
    $API_Endpoint = "https://api-3t.paypal.com/nvp";
    $API_Url = "https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=";
}

// Clean parameters
$PAYPAL_API_USER="";
if (! empty($conf->global->PAYPAL_API_USER)) $PAYPAL_API_USER=$conf->global->PAYPAL_API_USER;
$PAYPAL_API_PASSWORD="";
if (! empty($conf->global->PAYPAL_API_PASSWORD)) $PAYPAL_API_PASSWORD=$conf->global->PAYPAL_API_PASSWORD;
$PAYPAL_API_SIGNATURE="";
if (! empty($conf->global->PAYPAL_API_SIGNATURE)) $PAYPAL_API_SIGNATURE=$conf->global->PAYPAL_API_SIGNATURE;
$PAYPAL_API_SANDBOX="";
if (! empty($conf->global->PAYPAL_API_SANDBOX)) $PAYPAL_API_SANDBOX=$conf->global->PAYPAL_API_SANDBOX;

// Proxy
$PROXY_HOST = $conf->global->MAIN_PROXY_HOST;
$PROXY_PORT = $conf->global->MAIN_PROXY_PORT;
$PROXY_USER = $conf->global->MAIN_PROXY_USER;
$PROXY_PASS = $conf->global->MAIN_PROXY_PASS;
$USE_PROXY = empty($conf->global->MAIN_PROXY_USE)?false:true;

?>