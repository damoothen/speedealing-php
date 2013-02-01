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
		'PaypalSetup' => 'PayPal module setup',
		'PaypalDesc' => 'Deze module biedt om betaling op laten <a href="http://www.paypal.com" target="_blank">PayPal</a> door de klanten. Dit kan gebruikt worden voor een gratis betaling of voor een betaling op een bepaald Speedealing object (factuur, bestelling, ...)',
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