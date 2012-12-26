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
		'WorkflowSetup' => 'Workflow module setup',
		'WorkflowDesc' => 'This module is desinged to modify the behaviour of automatic actions into application. By default, workflow is opened (you make thing in order you want). You can enabled automatic actions that you are interesting in.',
		'ThereIsNoWorkflowToModify' => 'There is no workflow you can modify for module you have activated.',
		'descWORKFLOW_PROPAL_AUTOCREATE_ORDER' => 'Create a customer order automatically after a commercial proposal is signed',
		'descWORKFLOW_PROPAL_AUTOCREATE_INVOICE' => 'Create a customer invoice automatically after a commercial proposal is signed',
		'descWORKFLOW_CONTRACT_AUTOCREATE_INVOICE' => 'Create a customer invoice automatically after a contract is validated',
		'descWORKFLOW_ORDER_AUTOCREATE_INVOICE' => 'Create a customer invoice automatically after a customer order is closed',
		'descWORKFLOW_ORDER_CLASSIFY_BILLED_PROPAL' => 'Classify linked source proposal to billed when customer order is set to paid',
		'descWORKFLOW_INVOICE_CLASSIFY_BILLED_ORDER' => 'Classify linked source customer order to billed when customer invoice is set to paid',
);
?>