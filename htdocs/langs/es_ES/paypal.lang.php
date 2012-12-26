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
		'PaypalSetup' => 'Configuración del módulo PayPal',
		'PaypalDesc' => 'Este módulo ofrece una página  de pago a través del proveedor <a href="http://www.paypal.com" target="_blank">Paypal</a> para realizar cualquier pago o un pago en relación con un objeto Dolibarr (facturas, pedidos ...)',
		'PaypalOrCBDoPayment' => 'Continuar el pago mediante tarjeta o Paypal',
		'PaypalDoPayment' => 'Continuar el pago mediante Paypal',
		'PaypalCBDoPayment' => 'Continuar el pago mediante tarjeta',
		'PAYPAL_API_SANDBOX' => 'Modo de pruebas (sandbox)',
		'PAYPAL_API_USER' => 'Nombre usuario API',
		'PAYPAL_API_PASSWORD' => 'Contraseña usuario API',
		'PAYPAL_API_SIGNATURE' => 'Firma API',
		'PAYPAL_API_INTEGRAL_OR_PAYPALONLY' => 'Proponer pago integral (Tarjeta+Paypal) o sólo Paypal',
		'PAYPAL_CSS_URL' => 'Url opcional de la hoja de estilo CSS de la página de pago',
		'ThisIsTransactionId' => 'Identificador de la transacción: <b>%s</b>',
		'PAYPAL_ADD_PAYMENT_URL' => 'Añadir la url del pago Paypal al enviar un documento por e-mail',
		'PAYPAL_IPN_MAIL_ADDRESS' => 'Dirección e-mail para las notificaciones instantáneas de pago (IPN)',
		'PredefinedMailContentLink' => 'Puede hacer clic en el enlace seguro de abajo para realizar su pago a través de PayPal\n\n%s\n\n',
		'YouAreCurrentlyInSandboxMode' => 'Actualmente se encuentra en modo "sandbox"',
);
?>