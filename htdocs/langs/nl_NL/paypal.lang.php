<?php
/* Copyright (C) 2012	Regis Houssin	<regis.houssin@capnetworks.com>
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
		'PaypalSetup' => 'PayPal module setup',
		'PaypalDesc' => 'Deze module biedt om betaling op laten <a href="http://www.paypal.com" target="_blank">PayPal</a> door de klanten. Dit kan gebruikt worden voor een gratis betaling of voor een betaling op een bepaald Dolibarr object (factuur, bestelling, ...)',
		'PaypalOrCBDoPayment' => 'Betalen met credit card of Paypal',
		'PaypalDoPayment' => 'Betalen met Paypal',
		'PaypalCBDoPayment' => 'Betalen met creditcard',
		'PAYPAL_API_SANDBOX' => 'Mode test / zandbak',
		'PAYPAL_API_USER' => 'API gebruikersnaam',
		'PAYPAL_API_PASSWORD' => 'API wachtwoord',
		'PAYPAL_API_SIGNATURE' => 'API handtekening',
		'PAYPAL_API_INTEGRAL_OR_PAYPALONLY' => 'Aanbod betaling "integraal" (Credit card + Paypal) of "Paypal" alleen',
		'PAYPAL_CSS_URL' => ', Eventueel met Url van CSS style sheet op betaalpagina',
		'ThisIsTransactionId' => 'Dit is id van de transactie: <b>%s</b>',
		'PAYPAL_ADD_PAYMENT_URL' => 'Voeg de url van Paypal betaling wanneer u een document verzendt via e-mail',
		'PAYPAL_IPN_MAIL_ADDRESS' => 'E-mail adres voor de directe kennisgeving van de betaling (IPN)',
		'PredefinedMailContentLink' => 'You can click on the secure link below to make your payment via PayPal\n\n%s\n\n',
		'YouAreCurrentlyInSandboxMode' => 'U bevindt zich momenteel in de &quot;sandbox&quot;-modus'
);
?>