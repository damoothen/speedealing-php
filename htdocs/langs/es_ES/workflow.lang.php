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

$workflow = array(
		'CHARSET' => 'UTF-8',
		'WorkflowSetup' => 'Configuración del módulo workflow',
		'WorkflowDesc' => 'Este módulo le permite cambiar el comportamiento automatizado. De forma predeterminada, el workflow está abierto y no impuesto. Active los enlaces automáticos que le interesan.',
		'ThereIsNoWorkflowToModify' => 'No hay workflow modificable para los módulos que tiene activados.',
		'descWORKFLOW_PROPAL_AUTOCREATE_ORDER' => 'Crear un pedido de cliente automáticamente a la firma de un presupuesto',
		'descWORKFLOW_PROPAL_AUTOCREATE_INVOICE' => 'Crear una factura a cliente automáticamente a la firma de un presupuesto',
		'descWORKFLOW_CONTRACT_AUTOCREATE_INVOICE' => 'Crear una factura a cliente automáticamente a la validación de un contrato',
		'descWORKFLOW_ORDER_AUTOCREATE_INVOICE' => 'Crear una factura a cliente automáticamente al cierre de un pedido de cliente',
		'descWORKFLOW_ORDER_CLASSIFY_BILLED_PROPAL' => 'Clasificar como facturado el presupuesto cuando el pedido de cliente relacionado se clasifique como pagado',
		'descWORKFLOW_INVOICE_CLASSIFY_BILLED_ORDER' => 'Clasificar como facturados los pedidos cuando la factura relacionada se clasifique como pagada'
);
?>