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
		'WorkflowSetup' => 'Workflow-modul opsætning',
		'WorkflowDesc' => 'Dette modul desinged at ændre adfærd automatiske handlinger i anvendelse. Som standard er workflow åbnet (du laver ting i rækkefølge, du ønsker). Du kan aktiveret automatiske handlinger, som du er interessant i.',
		'ThereIsNoWorkflowToModify' => 'Der er ingen workflow kan du ændre til modul du har aktiveret.',
		'descWORKFLOW_PROPAL_AUTOCREATE_ORDER' => 'Opret en kundeordre automatisk efter en kommerciel forslag er underskrevet',
		'descWORKFLOW_PROPAL_AUTOCREATE_INVOICE' => 'Opret en kundefaktura automatisk efter en kommerciel forslag er underskrevet',
		'descWORKFLOW_CONTRACT_AUTOCREATE_INVOICE' => 'Opret en kundefaktura automatisk efter en kontrakt er valideret',
		'descWORKFLOW_ORDER_AUTOCREATE_INVOICE' => 'Opret en kundefaktura automatisk efter en kundeordre er lukket',
		'descWORKFLOW_ORDER_CLASSIFY_BILLED_PROPAL' => 'Classify linked source proposal to billed when customer order is set to paid',
		'descWORKFLOW_INVOICE_CLASSIFY_BILLED_ORDER' => 'Classify linked source customer order to billed when customer invoice is set to paid'
);
?>