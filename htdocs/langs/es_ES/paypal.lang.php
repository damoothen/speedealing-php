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
		'PaypalSetup' => 'Configuración del módulo PayPal',
		'PaypalDesc' => 'Este módulo ofrece una página  de pago a través del proveedor <a href="http://www.paypal.com" target="_blank">Paypal</a> para realizar cualquier pago o un pago en relación con un objeto Speedealing (facturas, pedidos ...)',
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
		'YouAreCurrentlyInSandboxMode' => 'Actualmente se encuentra en modo "sandbox"'
);
?>