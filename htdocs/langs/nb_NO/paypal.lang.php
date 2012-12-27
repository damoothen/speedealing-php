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
		'PaypalSetup' => 'PayPal modul oppsett',
		'PaypalDesc' => 'Denne modulen tilbyr sider for å tillate betaling på <a href="http://www.paypal.com" target="_blank">PayPal</a> av kunder. Dette kan brukes til en gratis betaling eller for en betaling på en bestemt Dolibarr objekt (faktura, ordre, ...)',
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