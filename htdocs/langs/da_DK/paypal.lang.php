<?php
/* Copyright (C) 2012	Regis Houssin	<regis@dolibarr.fr>
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

$paypal = array(
		'CHARSET' => 'UTF-8',
		'PaypalSetup' => 'PayPal-modul opsætning',
		'PaypalDesc' => 'Dette modul tilbyder sider til at tillade betaling på <a href="http://www.paypal.com" target="_blank">PayPal</a> af kunderne. Dette kan bruges til en gratis betaling eller til en betaling på en bestemt Dolibarr objekt (faktura, ordre, ...)',
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