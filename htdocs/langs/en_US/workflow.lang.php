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
		'WorkflowSetup' => 'Workflow module setup',
		'WorkflowDesc' => 'This module is desinged to modify the behaviour of automatic actions into application. By default, workflow is opened (you make thing in order you want). You can enabled automatic actions that you are interesting in.',
		'ThereIsNoWorkflowToModify' => 'There is no workflow you can modify for module you have activated.',
		'descWORKFLOW_PROPAL_AUTOCREATE_ORDER' => 'Create a customer order automatically after a commercial proposal is signed',
		'descWORKFLOW_PROPAL_AUTOCREATE_INVOICE' => 'Create a customer invoice automatically after a commercial proposal is signed',
		'descWORKFLOW_CONTRACT_AUTOCREATE_INVOICE' => 'Create a customer invoice automatically after a contract is validated',
		'descWORKFLOW_ORDER_AUTOCREATE_INVOICE' => 'Create a customer invoice automatically after a customer order is closed',
		'descWORKFLOW_ORDER_CLASSIFY_BILLED_PROPAL' => 'Classify linked source proposal to billed when customer order is set to paid',
		'descWORKFLOW_INVOICE_CLASSIFY_BILLED_ORDER' => 'Classify linked source customer order to billed when customer invoice is set to paid'
);
?>