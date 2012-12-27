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
		'PaypalSetup' => 'PayPal-modul installation',
		'PaypalDesc' => 'Denna modul erbjuder sidor för att möjliggöra betalning på <a href="http://www.paypal.com" target="_blank">PayPal</a> av kunder. Detta kan användas för en fri betalning eller en betalning på en viss Dolibarr objekt (faktura, beställning, ...)',
		'PaypalOrCBDoPayment' => 'Betala med kreditkort eller Paypal',
		'PaypalDoPayment' => 'Betala med Paypal',
		'PaypalCBDoPayment' => 'Betala med kreditkort',
		'PAYPAL_API_SANDBOX' => 'Läge test / sandlåda',
		'PAYPAL_API_USER' => 'API användarnamn',
		'PAYPAL_API_PASSWORD' => 'API-lösenord',
		'PAYPAL_API_SIGNATURE' => 'API signatur',
		'PAYPAL_API_INTEGRAL_OR_PAYPALONLY' => 'Erbjuder betalning &quot;integrerad&quot; (Kreditkort + Paypal) eller &quot;Paypal&quot; endast',
		'PAYPAL_CSS_URL' => 'Optionnal Url av CSS-formatmall om betalning sidan',
		'ThisIsTransactionId' => 'Detta är id transaktion: <b>%s</b>',
		'PAYPAL_ADD_PAYMENT_URL' => 'Lägg till URL Paypal betalning när du skickar ett dokument per post',
		'PAYPAL_IPN_MAIL_ADDRESS' => 'E-postadress att omedelbart anmälan av betalning (IPN)',
		'PredefinedMailContentLink' => 'You can click on the secure link below to make your payment via PayPal\n\n%s\n\n',
		'YouAreCurrentlyInSandboxMode' => 'Du är för närvarande i &quot;sandlåda&quot;-läget'
);
?>