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
		'PaypalSetup' => 'PayPal модуль установки',
		'PaypalDesc' => 'Этот модуль предлагает страниц, чтобы выплаты по <a href="http://www.paypal.com" target="_blank">PayPal</a> клиентами. Это может быть использовано для свободного оплаты или оплаты на определенный объект Dolibarr (счет-фактура, заказ, ...)',
		'PaypalOrCBDoPayment' => 'Оплатить с помощью кредитной карты или Paypal',
		'PaypalDoPayment' => 'Оплатить с помощью Paypal',
		'PaypalCBDoPayment' => 'Оплата кредитной картой',
		'PAYPAL_API_SANDBOX' => 'Режим тестирования / песочнице',
		'PAYPAL_API_USER' => 'API имя пользователя',
		'PAYPAL_API_PASSWORD' => 'API пароль',
		'PAYPAL_API_SIGNATURE' => 'API подпись',
		'PAYPAL_API_INTEGRAL_OR_PAYPALONLY' => 'Предложение платежа &quot;Интеграл&quot; (кредитные карточки + Paypal) или &quot;PayPal&quot;, только',
		'PAYPAL_CSS_URL' => 'Optionnal адрес таблицы стилей CSS на странице оплаты',
		'ThisIsTransactionId' => 'Это идентификатор сделки: <b>%s</b>',
		'PAYPAL_ADD_PAYMENT_URL' => 'Добавить адрес Paypal оплата при отправке документа по почте',
		'PAYPAL_IPN_MAIL_ADDRESS' => 'Адрес электронной почты для мгновенного уведомления об оплате (IPN)',
		'YouAreCurrentlyInSandboxMode' => 'Вы в настоящее время в &quot;песочнице&quot; режим',
);
?>