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