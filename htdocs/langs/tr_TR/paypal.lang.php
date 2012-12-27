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
		'PaypalSetup' => 'PayPal modülü kurulumu',
		'PaypalDesc' => 'Bu modül  <a href="http://www.paypal.com" target="_blank">PayPal</a> üzerinden müşteriler tarafından ödeme yapılmasını sağlar. Bu bir ücretsiz ödeme veya belirli bir Dolibarr nesnesine (fatura, siparş,…) bir ödeme yapmak için kullanılabilir',
		'PaypalOrCBDoPayment' => 'Kredi kartı veya PayPal ile ödeme',
		'PaypalDoPayment' => 'Paypal ile ödeme',
		'PaypalCBDoPayment' => 'Kredi kartı ile ödeme',
		'PAYPAL_API_SANDBOX' => 'Test/sandbox modu',
		'PAYPAL_API_USER' => 'API kullanıcı adı',
		'PAYPAL_API_PASSWORD' => 'API parolası',
		'PAYPAL_API_SIGNATURE' => 'API imzası',
		'PAYPAL_API_INTEGRAL_OR_PAYPALONLY' => '"Dahili" (kredi kartı+paypal) ya da sadece "Paypal" ödemesi sunar',
		'PAYPAL_CSS_URL' => 'Ödeme sayfasında CSS stili çizelgesinin isteğe bağlı URL si URL',
		'ThisIsTransactionId' => 'Bu işlem kimliğidir: <b>%s</b>',
		'PAYPAL_ADD_PAYMENT_URL' => 'Posta yoluyla bir belge gönderdiğinizde, Paypal ödeme url\'sini ekleyin',
		'PAYPAL_IPN_MAIL_ADDRESS' => 'Anında ödeme bildirimi için e-posta adresi (NPI)',
		'PredefinedMailContentLink' => 'Ödemenizi via PayPal\n\n%s\n\n ile yapmak için aşağıdaki güvenli bağlantıya tıklayabilirsiniz',
		'YouAreCurrentlyInSandboxMode' => '"Sandbox" geçerli biçimindesiniz'
);
?>