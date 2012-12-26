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
		'PaypalSetup' => '贝宝模块的设置',
		'PaypalDesc' => '该模块提供的网页，允许<a href="http://www.paypal.com" target="_blank">对贝</a>宝支付客户的。这可以使用一个免费的付款或支付上一个特定的Dolibarr对象（发票，订单，... ...）',
		'PaypalOrCBDoPayment' => '与信用卡或PayPal付款',
		'PaypalDoPayment' => '用PayPal',
		'PaypalCBDoPayment' => '信用卡支付',
		'PAYPAL_API_SANDBOX' => '测试/沙箱模式',
		'PAYPAL_API_USER' => 'API的用户名',
		'PAYPAL_API_PASSWORD' => 'API密码',
		'PAYPAL_API_SIGNATURE' => 'API签名',
		'PAYPAL_API_INTEGRAL_OR_PAYPALONLY' => '优惠“不可分割的”支付（信用卡+贝宝）或“贝宝”只',
		'PAYPAL_CSS_URL' => 'optionnal付款页面的CSS样式表的URL',
		'ThisIsTransactionId' => '这是交易编号<b>：%s</b>',
		'PAYPAL_ADD_PAYMENT_URL' => '当你邮寄一份文件，添加URL Paypal付款',
		'PAYPAL_IPN_MAIL_ADDRESS' => 'E-mail地址，即时付款通知（IPN）',
		'YouAreCurrentlyInSandboxMode' => '您目前在“沙箱”模式',
);
?>