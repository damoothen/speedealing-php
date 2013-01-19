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
		'PaypalSetup' => 'PayPal modul beállítása',
		'PaypalDesc' => 'Ez a modul ajánlat oldalakat, hogy fizetést <a href="http://www.paypal.com" target="_blank">PayPal</a> ügyfelek. Ezt fel lehet használni a szabad fizetés vagy a fizetés egy adott Speedealing objektum (számla, megrendelés, ...)',
		'PaypalOrCBDoPayment' => 'Fizessen hitelkártyával vagy Paypal',
		'PaypalDoPayment' => 'Fizetés Paypal',
		'PaypalCBDoPayment' => 'Fizess bankkártyával',
		'PAYPAL_API_SANDBOX' => 'Üzemmódban végzett vizsgálat / homokozó',
		'PAYPAL_API_USER' => 'API felhasználónév',
		'PAYPAL_API_PASSWORD' => 'API jelszó',
		'PAYPAL_API_SIGNATURE' => 'API aláírás',
		'PAYPAL_API_INTEGRAL_OR_PAYPALONLY' => 'Ajánlat fizetés &quot;szerves&quot; (hitelkártya + Paypal) vagy a &quot;Paypal&quot; csak',
		'PAYPAL_CSS_URL' => 'Optionnal Url a CSS stíluslap a fizetési oldalon',
		'ThisIsTransactionId' => 'Ez a tranzakció id: <b>%s</b>',
		'PAYPAL_ADD_PAYMENT_URL' => 'Add az url a Paypal fizetési amikor a dokumentumot postán',
		'PAYPAL_IPN_MAIL_ADDRESS' => 'E-mail cím az azonnali értesítést a fizetés (IPN)',
		'PredefinedMailContentLink' => 'You can click on the secure link below to make your payment via PayPal\n\n%s\n\n',
		'YouAreCurrentlyInSandboxMode' => 'Ön jelenleg a &quot;sandbox&quot; mód'
);
?>