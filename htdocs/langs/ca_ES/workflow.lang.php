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
		'WorkflowSetup' => 'Configuració del mòdul workflow',
		'WorkflowDesc' => 'Aquest mòdul us permet canviar el comportament automatitzat. Per defecte, el workflow és obert i no imposat. Activi els enllaços automàtics que li interessen.',
		'ThereIsNoWorkflowToModify' => 'No hi ha workflow modificable per als mòduls que té activats.',
		'descWORKFLOW_PROPAL_AUTOCREATE_ORDER' => 'Crear una comanda de client automàticament a la signatura d\'un pressupost',
		'descWORKFLOW_PROPAL_AUTOCREATE_INVOICE' => 'Crear una factura a client automàticament a la signatura d\'un pressupost',
		'descWORKFLOW_CONTRACT_AUTOCREATE_INVOICE' => 'Crear una factura a client automàticament a la validació d\'un contracte',
		'descWORKFLOW_ORDER_AUTOCREATE_INVOICE' => 'Crear una factura a client automàticament al tancament d\'una comanda de client',
		'descWORKFLOW_ORDER_CLASSIFY_BILLED_PROPAL' => 'Classificar com facturat el pressupost quan la comanda de client relacionada es classifiqui com pagada',
		'descWORKFLOW_INVOICE_CLASSIFY_BILLED_ORDER' => 'Classificar com facturades les comandes quan la factura relacionada es classifiqui com a pagada'
);
?>