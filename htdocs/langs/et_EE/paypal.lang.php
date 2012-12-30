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
		'PaypalSetup' => 'PayPal moodul setup',
		'PaypalDesc' => 'See moodul pakkumise leheküljed, mis võimaldab makse <a href="http://www.paypal.com" target="_blank">PayPal</a> kliendid. Seda saab kasutada tasuta makse või makse teatud Dolibarr objekti (arve, et ...)',
		'PaypalOrCBDoPayment' => 'Maksta krediitkaardiga või Paypal',
		'PaypalDoPayment' => 'Maksa Paypal',
		'PaypalCBDoPayment' => 'Maksa krediitkaardi',
		'PAYPAL_API_SANDBOX' => 'Režiim test / liivakast',
		'PAYPAL_API_USER' => 'API kasutajanimi',
		'PAYPAL_API_PASSWORD' => 'API parooli',
		'PAYPAL_API_SIGNATURE' => 'API allkiri',
		'PAYPAL_API_INTEGRAL_OR_PAYPALONLY' => 'Paku makse &quot;lahutamatu&quot; (krediitkaart + Paypal) või &quot;Paypal&quot; ainult',
		'PAYPAL_CSS_URL' => 'Optionnal Url CSS style sheet tasumise lehele',
		'ThisIsTransactionId' => 'See on id tehing: <b>%s</b>',
		'PAYPAL_ADD_PAYMENT_URL' => 'Lisa URL Paypal makse, kui saadate dokumendi mail',
		'PAYPAL_IPN_MAIL_ADDRESS' => 'E-posti aadress instant tasumise teate (IPN)',
		'PredefinedMailContentLink' => 'You can click on the secure link below to make your payment via PayPal\n\n%s\n\n',
		'YouAreCurrentlyInSandboxMode' => 'Sa oled praegu &quot;liivakasti&quot; režiimis'
);
?>