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