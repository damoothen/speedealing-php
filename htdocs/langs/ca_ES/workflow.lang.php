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