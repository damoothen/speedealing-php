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
		'WorkflowSetup' => 'Fluxul de lucru modul de configurare',
		'WorkflowDesc' => 'Acest modul este placut in aceleasi nuante pentru a modifica comportamentul de acţiuni automate în aplicare. În mod implicit, fluxul de lucru este deschis (a face ceva în ordinea dorită). Puteţi activat acţiuni automate pe care le sunt interesante inch',
		'ThereIsNoWorkflowToModify' => 'Nu există nici un flux de lucru se poate modifica pentru modulul care le-aţi activat.',
		'descWORKFLOW_PROPAL_AUTOCREATE_ORDER' => 'Crearea unui ordin de client în mod automat, după o propunere comercială este semnat',
		'descWORKFLOW_PROPAL_AUTOCREATE_INVOICE' => 'Creaţi o factură client în mod automat, după o propunere comercială este semnat',
		'descWORKFLOW_CONTRACT_AUTOCREATE_INVOICE' => 'Creaţi o factură client în mod automat după ce un contract este validat',
		'descWORKFLOW_ORDER_AUTOCREATE_INVOICE' => 'Creaţi o factură client în mod automat, după un ordin de client este închis',
		'descWORKFLOW_ORDER_CLASSIFY_BILLED_PROPAL' => 'Classify linked source proposal to billed when customer order is set to paid',
		'descWORKFLOW_INVOICE_CLASSIFY_BILLED_ORDER' => 'Classify linked source customer order to billed when customer invoice is set to paid'
);
?>