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
		'PaypalSetup' => 'PayPal-modul opsætning',
		'PaypalDesc' => 'Dette modul tilbyder sider til at tillade betaling på <a href="http://www.paypal.com" target="_blank">PayPal</a> af kunderne. Dette kan bruges til en gratis betaling eller til en betaling på en bestemt Speedealing objekt (faktura, ordre, ...)',
		'PaypalOrCBDoPayment' => 'Betal med kreditkort eller Paypal',
		'PaypalDoPayment' => 'Betal med Paypal',
		'PaypalCBDoPayment' => 'Betal med kreditkort',
		'PAYPAL_API_SANDBOX' => 'Mode test / sandkasse',
		'PAYPAL_API_USER' => 'API brugernavn',
		'PAYPAL_API_PASSWORD' => 'API kodeord',
		'PAYPAL_API_SIGNATURE' => 'API signatur',
		'PAYPAL_API_INTEGRAL_OR_PAYPALONLY' => 'Tilbyder betaling &quot;integreret&quot; (kreditkort + Paypal) eller &quot;Paypal&quot; kun',
		'PAYPAL_CSS_URL' => 'Valgfrie Url af CSS stylesheet på betalingssiden',
		'ThisIsTransactionId' => 'Dette er id af transaktionen: <b>%s</b>',
		'PAYPAL_ADD_PAYMENT_URL' => 'Tilsæt url Paypal betaling, når du sender et dokument med posten',
		'PAYPAL_IPN_MAIL_ADDRESS' => 'E-mail-adresse til øjeblikkelig meddelelse om betaling (IPN)',
		'PredefinedMailContentLink' => 'You can click on the secure link below to make your payment via PayPal\n\n%s\n\n',
		'YouAreCurrentlyInSandboxMode' => 'Du er i øjeblikket i &quot;sandbox&quot; mode'
);
?>