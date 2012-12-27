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
		'PaypalSetup' => 'PayPal konfiguracji modułu',
		'PaypalDesc' => 'Ta oferta moduł stron, umożliwiających płatności w <a href="http://www.paypal.com" target="_blank">systemie PayPal</a> przez klientów. Może to być wykorzystywane do bezpłatnego opłatę lub wpłaty na konkretnego obiektu Dolibarr (faktura, zamówienie, ...)',
		'PaypalOrCBDoPayment' => 'Zapłać kartą kredytową lub poprzez system Paypal',
		'PaypalDoPayment' => 'Zapłać z PayPal',
		'PaypalCBDoPayment' => 'Płatności kartą kredytową',
		'PAYPAL_API_SANDBOX' => 'Tryb testu / sandbox',
		'PAYPAL_API_USER' => 'API użytkownika',
		'PAYPAL_API_PASSWORD' => 'API hasło',
		'PAYPAL_API_SIGNATURE' => 'Podpis API',
		'PAYPAL_API_INTEGRAL_OR_PAYPALONLY' => 'Oferta płatności &quot;integralnej&quot; (Karta kredytowa + Paypal) lub &quot;Paypal&quot; tylko',
		'PAYPAL_CSS_URL' => 'Opcjonalnej Url arkusza stylów CSS na stronie płatności',
		'ThisIsTransactionId' => 'Jest to id transakcji: <b>%s</b>',
		'PAYPAL_ADD_PAYMENT_URL' => 'Dodaj url płatności PayPal podczas wysyłania dokumentów pocztą',
		'PAYPAL_IPN_MAIL_ADDRESS' => 'Adres e-mail do natychmiastowego powiadamiania o płatności (BPP)',
		'PredefinedMailContentLink' => 'You can click on the secure link below to make your payment via PayPal\n\n%s\n\n',
		'YouAreCurrentlyInSandboxMode' => 'Jesteś obecnie w trybie &quot;sandbox&quot;'
);
?>