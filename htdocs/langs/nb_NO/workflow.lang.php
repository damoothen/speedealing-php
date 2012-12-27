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
		'WorkflowSetup' => 'Arbeidsflyt modulen oppsett',
		'WorkflowDesc' => 'Denne modulen er konstruert for å endre oppførselen til automatiske handlinger inn i programmet. Som standard er arbeidsflyten åpnet (du gjør ting i rekkefølge du vil). Du kan aktivere automatiske handlinger som du er interessant i.',
		'ThereIsNoWorkflowToModify' => 'Det er ingen arbeidsflyt kan du endre for modul du har aktivert.',
		'descWORKFLOW_PROPAL_AUTOCREATE_ORDER' => 'Lag en kundeordre automatisk etter en kommersiell forslag er signert',
		'descWORKFLOW_PROPAL_AUTOCREATE_INVOICE' => 'Lag en kunde faktura automatisk etter en kommersiell forslag er signert',
		'descWORKFLOW_CONTRACT_AUTOCREATE_INVOICE' => 'Lag en kunde faktura automatisk etter en kontrakt er validert',
		'descWORKFLOW_ORDER_AUTOCREATE_INVOICE' => 'Lag en kunde faktura automatisk etter en kundeordre er stengt',
		'descWORKFLOW_ORDER_CLASSIFY_BILLED_PROPAL' => 'Classify linked source proposal to billed when customer order is set to paid',
		'descWORKFLOW_INVOICE_CLASSIFY_BILLED_ORDER' => 'Classify linked source customer order to billed when customer invoice is set to paid'
);
?>