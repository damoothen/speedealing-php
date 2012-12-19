<?php
/* Copyright (C) 2001-2002	Rodolphe Quiedeville	<rodolphe@quiedeville.org>
 * Copyright (C) 2006-2009	Laurent Destailleur		<eldy@users.sourceforge.net>
 * Copyright (C) 2012		Regis Houssin			<regis@dolibarr.fr>
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
 *     	\file       htdocs/public/paypal/paymentko.php
 *		\ingroup    paypal
 *		\brief      File to show page after a failed payment.
 *                  This page is called by paypal with url provided to payal competed with parameter TOKEN=xxx
 *                  This token can be used to get more informations.
 *		\author	    Laurent Destailleur
 */

define("NOLOGIN",1);		// This means this output page does not require to be logged.
define("NOCSRFCHECK",1);	// We accept to go on this page from external web site.

// For MultiCompany module
$entity=(! empty($_GET['entity']) ? (int) $_GET['entity'] : (! empty($_POST['entity']) ? (int) $_POST['entity'] : 1));
if (is_int($entity))
{
	define("DOLENTITY", $entity);
}

require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/paypal/lib/paypal.lib.php';
require_once DOL_DOCUMENT_ROOT.'/paypal/lib/paypalfunctions.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/company.lib.php';

// Security check
if (empty($conf->paypal->enabled)) accessforbidden('',1,1,1);

$langs->load("main");
$langs->load("other");
$langs->load("dict");
$langs->load("bills");
$langs->load("companies");
$langs->load("paybox");
$langs->load("paypal");


/*
 * Actions
 */




/*
 * View
 */

dol_syslog("Callback url when a PayPal payment was canceled. query_string=".$_SERVER["QUERY_STRING"]);

llxHeaderPaypal($langs->trans("PaymentForm"));


// Show ko message
print '<span id="dolpaymentspan"></span>'."\n";
print '<div id="dolpaymentdiv" align="center">'."\n";
print $langs->trans("YourPaymentHasNotBeenRecorded")."<br>";

$PAYPALTOKEN=GETPOST('TOKEN');
if (empty($PAYPALTOKEN)) $PAYPALTOKEN=GETPOST('token');
$PAYPALFULLTAG=GETPOST('FULLTAG');
if (empty($PAYPALFULLTAG)) $PAYPALFULLTAG=GETPOST('fulltag');

if (! empty($conf->global->PAYPAL_MESSAGE_KO)) print $conf->global->PAYPAL_MESSAGE_KO;
print "\n</div>\n";


html_print_paypal_footer($mysoc,$langs);


$db->close();

llxFooterPaypal();
?>
