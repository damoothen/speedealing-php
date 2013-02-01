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