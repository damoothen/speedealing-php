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
		'PaypalSetup' => 'PayPal moduuli setup',
		'PaypalDesc' => 'Tämä moduuli tarjoaa sivuja, jotta maksua <a href="http://www.paypal.com" target="_blank">PayPal</a> asiakkaat. Tätä voidaan käyttää ilmaiseksi maksua tai maksun tietystä Speedealing esine (lasku, tilaus, ...)',
		'PaypalOrCBDoPayment' => 'Maksaa luottokortilla tai Paypal',
		'PaypalDoPayment' => 'Maksa Paypal',
		'PaypalCBDoPayment' => 'Maksa luottokortilla',
		'PAYPAL_API_SANDBOX' => 'Tila testi / hiekkalaatikko',
		'PAYPAL_API_USER' => 'API käyttäjätunnus',
		'PAYPAL_API_PASSWORD' => 'API salasana',
		'PAYPAL_API_SIGNATURE' => 'API allekirjoitus',
		'PAYPAL_API_INTEGRAL_OR_PAYPALONLY' => 'Voit maksaa &quot;kiinteä&quot; (luottokortti + Paypal) tai &quot;PayPal&quot; vain',
		'PAYPAL_CSS_URL' => 'Optionnal Url CSS-tyylisivun maksamisesta sivu',
		'ThisIsTransactionId' => 'Tämä on id liiketoimen: <b>%s</b>',
		'PAYPAL_ADD_PAYMENT_URL' => 'Lisää URL Paypal-maksujärjestelmää, kun lähetät asiakirjan postitse',
		'PAYPAL_IPN_MAIL_ADDRESS' => 'Sähköpostiosoite varten instant tiedon maksusta (IPN)',
		'PredefinedMailContentLink' => 'You can click on the secure link below to make your payment via PayPal\n\n%s\n\n',
		'YouAreCurrentlyInSandboxMode' => 'Olet nyt &quot;hiekkalaatikko&quot;-tilassa'
);
?>