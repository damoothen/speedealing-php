<?php
/* Copyright (C) 2012	Regis Houssin	<regis.houssin@capnetworks.com>
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
 */

$paypal = array(
		'CHARSET' => 'UTF-8',
		'PaypalSetup' => 'PayPal modul oppsett',
		'PaypalDesc' => 'Denne modulen tilbyr sider for å tillate betaling på <a href="http://www.paypal.com" target="_blank">PayPal</a> av kunder. Dette kan brukes til en gratis betaling eller for en betaling på en bestemt Speedealing objekt (faktura, ordre, ...)',
		'PaypalOrCBDoPayment' => 'Betal med kredittkort eller Paypal',
		'PaypalDoPayment' => 'Betal med Paypal',
		'PaypalCBDoPayment' => 'Betal med kredittkort',
		'PAYPAL_API_SANDBOX' => 'Mode test / sandkasse',
		'PAYPAL_API_USER' => 'API brukernavn',
		'PAYPAL_API_PASSWORD' => 'API passord',
		'PAYPAL_API_SIGNATURE' => 'API signatur',
		'PAYPAL_API_INTEGRAL_OR_PAYPALONLY' => 'Offer betaling &quot;integrert&quot; (Kredittkort + Paypal) eller &quot;Paypal&quot; bare',
		'PAYPAL_CSS_URL' => 'Optionnal Url av CSS-stilark på betaling side',
		'ThisIsTransactionId' => 'Dette er id av transaksjonen: <b>%s</b>',
		'PAYPAL_ADD_PAYMENT_URL' => 'Legg til url av Paypal betaling når du sender et dokument i posten',
		'PAYPAL_IPN_MAIL_ADDRESS' => 'E-post adressen for øyeblikkelig varsling av betaling (IPN)',
		'PredefinedMailContentLink' => 'You can click on the secure link below to make your payment via PayPal\n\n%s\n\n',
		'YouAreCurrentlyInSandboxMode' => 'Du er for øyeblikket i &quot;sandbox&quot;-modus'
);
?>