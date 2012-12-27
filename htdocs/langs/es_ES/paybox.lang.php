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

$paybox = array(
		'CHARSET' => 'UTF-8',
		'PayBoxSetup' => 'Configuración módulo PayBox',
		'PayBoxDesc' => 'Este módulo ofrece una página  de pago a través del proveedor <a href="http://www.paybox.com" target="_blank">Paybox</a> para realizar cualquier pago o un pago en relación con un objeto Dolibarr (facturas, pedidos ...)',
		'FollowingUrlAreAvailableToMakePayments' => 'Las siguientes URL están disponibles para permitir a un cliente efectuar un pago',
		'PaymentForm' => 'Formulario de pago',
		'WelcomeOnPaymentPage' => 'Bienvenido a nuestros servicios de pago en línea',
		'ThisScreenAllowsYouToPay' => 'Esta pantalla le permite hacer su pago en línea destinado a %s.',
		'ThisIsInformationOnPayment' => 'Aquí está la información sobre el pago a realizar',
		'ToComplete' => 'A completar',
		'YourEMail' => 'E-Mail de confirmación de pago',
		'Creditor' => 'Beneficiario',
		'PaymentCode' => 'Código de pago',
		'PayBoxDoPayment' => 'Continuar el pago con tarjeta',
		'YouWillBeRedirectedOnPayBox' => 'Va a ser redirigido a la página segura de  Paybox para indicar su tarjeta de crédito',
		'PleaseBePatient' => 'Espere unos segundos',
		'Continue' => 'Continuar',
		'ToOfferALinkForOnlinePayment' => 'URL de pago %s',
		'ToOfferALinkForOnlinePaymentOnOrder' => 'URL que ofrece una interfaz de pago en línea %s basada en el importe de un pedido de cliente',
		'ToOfferALinkForOnlinePaymentOnInvoice' => 'URL que ofrece una interfaz de pago en línea %s basada en el importe de una factura a client',
		'ToOfferALinkForOnlinePaymentOnContractLine' => 'URL que ofrece una interfaz de pago en línea %s basada en el importe de una línea de contrato',
		'ToOfferALinkForOnlinePaymentOnFreeAmount' => 'URL que ofrece una interfaz de pago en línea %s basada en un importe libre',
		'ToOfferALinkForOnlinePaymentOnMemberSubscription' => 'URL que ofrece una interfaz de pago en línea %s basada en la cotización de un miembro',
		'YouCanAddTagOnUrl' => 'También puede añadir el parámetro url <b>&tag=<i>value</i></b>  para cualquiera de estas direcciones (obligatorio solamente para el pago libre) para ver su propio código de comentario de pago.',
		'SetupPayBoxToHavePaymentCreatedAutomatically' => 'Configure su url PayBox <b>%s</b> para que el pago se cree automáticamente al validar.',
		'YourPaymentHasBeenRecorded' => 'Esta página confirma que su pago se ha registrado correctamente. Gracias.',
		'YourPaymentHasNotBeenRecorded' => 'Su pago no ha sido registrado y la transacción ha sido anulada. Gracias.',
		'AccountParameter' => 'Parámetros de la cuenta',
		'UsageParameter' => 'Parámetros de uso',
		'InformationToFindParameters' => 'Información para encontrar a su configuración de cuenta %s',
		'PAYBOX_CGI_URL_V2' => 'Url del módulo CGI Paybox de pago',
		'VendorName' => 'Nombre del vendedor',
		'CSSUrlForPaymentForm' => 'Url de la hoja de estilo CSS para el formulario de pago',
		'MessageOK' => 'Mensaje en la página de retorno de pago confirmado',
		'MessageKO' => 'Mensaje en la página de retorno de pago cancelado'
);
?>