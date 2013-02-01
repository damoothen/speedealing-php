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
		'PaypalSetup' => '贝宝模块的设置',
		'PaypalDesc' => '该模块提供的网页，允许<a href="http://www.paypal.com" target="_blank">对贝</a>宝支付客户的。这可以使用一个免费的付款或支付上一个特定的Speedealing对象（发票，订单，... ...）',
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
		'PredefinedMailContentLink' => 'You can click on the secure link below to make your payment via PayPal\n\n%s\n\n',
		'YouAreCurrentlyInSandboxMode' => '您目前在“沙箱”模式'
);
?>