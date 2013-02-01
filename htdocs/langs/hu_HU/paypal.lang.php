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