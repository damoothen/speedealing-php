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