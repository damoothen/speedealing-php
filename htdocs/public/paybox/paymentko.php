<?php
/* Copyright (C) 2001-2002 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2006-2009 Laurent Destailleur  <eldy@users.sourceforge.net>
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
 *     	\file       htdocs/public/paybox/paymentko.php
 *		\ingroup    paybox
 *		\brief      File to show page after a failed payment
 *		\author	    Laurent Destailleur
 */

define("NOLOGIN",1);		// This means this output page does not require to be logged.
define("NOCSRFCHECK",1);	// We accept to go on this page from external web site.

require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/paybox/lib/paybox.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/company.lib.php';

// Security check
if (empty($conf->paybox->enabled)) accessforbidden('',1,1,1);

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

dol_syslog("Callback url when a PayBox payment was canceled. query_string=".$_SERVER["QUERY_STRING"]);

llxHeaderPayBox($langs->trans("PaymentForm"));


// Show message
print '<span id="dolpaymentspan"></span>'."\n";
print '<div id="dolpaymentdiv" align="center">'."\n";

print $langs->trans("YourPaymentHasNotBeenRecorded")."<br>\n";

if (! empty($conf->global->PAYBOX_MESSAGE_KO)) print $conf->global->PAYBOX_MESSAGE_KO;

print "\n</div>\n";


html_print_paybox_footer($mysoc,$langs);

$db->close();

llxFooterPayBox();
?>
