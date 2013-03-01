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
		'PaypalDesc' => 'This module offer pages to allow payment on <a href="http://www.paypal.com" target="_blank">PayPal</a> by customers. This can be used for a free payment or for a payment on a particular Speedealing object (invoice, order, ...)',
		'PaypalOrCBDoPayment' => 'Pay with credit card or Paypal',
		'PaypalDoPayment' => 'Pay with Paypal',
		'PaypalCBDoPayment' => 'Pay with credit card',
		'PAYPAL_API_SANDBOX' => 'Mode test/sandbox',
		'PAYPAL_API_USER' => 'API username',
		'PAYPAL_API_PASSWORD' => 'API password',
		'PAYPAL_API_SIGNATURE' => 'API signature',
		'PAYPAL_API_INTEGRAL_OR_PAYPALONLY' => 'Offer payment "integral" (Credit card+Paypal) or "Paypal" only',
		'PAYPAL_CSS_URL' => 'Optionnal Url of CSS style sheet on payment page',
		'ThisIsTransactionId' => 'This is id of transaction: <b>%s</b>',
		'PAYPAL_ADD_PAYMENT_URL' => 'Add the url of Paypal payment when you send a document by mail',
		'PAYPAL_IPN_MAIL_ADDRESS' => 'E-mail address for the instant notification of payment (IPN)',
		'PredefinedMailContentLink' => 'You can click on the secure link below to make your payment via PayPal\n\n%s\n\n',
		'YouAreCurrentlyInSandboxMode' => 'You are currently in the "sandbox" mode'
);
?>