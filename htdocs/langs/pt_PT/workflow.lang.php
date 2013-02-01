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
		'WorkflowSetup' => 'Configuração do módulo de Workflow',
		'WorkflowDesc' => 'Este módulo é desenhado para modificar o comportamento de acções automáticas na aplicação. Por padrão, o fluxo de trabalho é aberto (você fazer a coisa de forma que você quiser). Você pode habilitar ações automáticas .',
		'ThereIsNoWorkflowToModify' => 'Não há fluxo de trabalho você pode modificar para o módulo que você tenha ativado.',
		'descWORKFLOW_PROPAL_AUTOCREATE_ORDER' => 'Criar um pedido do cliente automaticamente, após uma proposta comercial ser assinada',
		'descWORKFLOW_PROPAL_AUTOCREATE_INVOICE' => 'Criar uma factura de cliente automaticamente, após uma proposta comercial ser assinada',
		'descWORKFLOW_CONTRACT_AUTOCREATE_INVOICE' => 'Criar uma factura de cliente automaticamente depois de um contrato ser validado',
		'descWORKFLOW_ORDER_AUTOCREATE_INVOICE' => 'Criar uma factura de cliente automaticamente após um pedido do cliente estar fechado',
		'descWORKFLOW_ORDER_CLASSIFY_BILLED_PROPAL' => 'Classify linked source proposal to billed when customer order is set to paid',
		'descWORKFLOW_INVOICE_CLASSIFY_BILLED_ORDER' => 'Classify linked source customer order to billed when customer invoice is set to paid'
);
?>