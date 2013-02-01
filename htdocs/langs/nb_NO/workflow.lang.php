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