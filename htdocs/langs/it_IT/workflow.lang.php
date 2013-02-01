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
		'WorkflowSetup' => 'Impostazioni flusso di lavoro',
		'WorkflowDesc' => 'Questo modulo è progettato per impostare le azioni automatiche dell\'applicazione. Per impostazione predefinita, il flusso di lavoro è aperto (puoi adattarlo alle tue esigenze). Puoi scegliere quali azioni automatiche abilitare.',
		'ThereIsNoWorkflowToModify' => 'Non vi è alcun flusso di lavoro modificabile per il modulo.',
		'descWORKFLOW_PROPAL_AUTOCREATE_ORDER' => 'Creare automaticamente un ordine cliente alla firma di una proposta commerciale',
		'descWORKFLOW_PROPAL_AUTOCREATE_INVOICE' => 'Creare automaticamente una fattura attiva alla firma di una proposta commerciale',
		'descWORKFLOW_CONTRACT_AUTOCREATE_INVOICE' => 'Creare automaticamente una fattura attiva alla convalida del contratto',
		'descWORKFLOW_ORDER_AUTOCREATE_INVOICE' => 'Creare automaticamente una fattura attiva alla chiusura dell\'ordine cliente',
		'descWORKFLOW_ORDER_CLASSIFY_BILLED_PROPAL' => 'Classify linked source proposal to billed when customer order is set to paid',
		'descWORKFLOW_INVOICE_CLASSIFY_BILLED_ORDER' => 'Classify linked source customer order to billed when customer invoice is set to paid'
);
?>