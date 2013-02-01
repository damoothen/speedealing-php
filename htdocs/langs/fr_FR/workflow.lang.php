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
		'WorkflowSetup' => 'Configuration du module workflow',
		'WorkflowDesc' => 'Ce module vous permet de modifier le comportement d\'enchainement automatisé. Par défaut, le workflow est ouvert et non imposé. A vous d\'activer les liens automatiques qui vous intéressent.',
		'ThereIsNoWorkflowToModify' => 'Il n\'y a pas de flux workflow modifiable pour les modules que vous avez activés.',
		'descWORKFLOW_PROPAL_AUTOCREATE_ORDER' => 'Créer une commande client automatiquement à la signature d\'une proposition commerciale',
		'descWORKFLOW_PROPAL_AUTOCREATE_INVOICE' => 'Créer une facture client automatiquement à la signature d\'une proposition commerciale',
		'descWORKFLOW_CONTRACT_AUTOCREATE_INVOICE' => 'Créer une facture client automatiquement à la validation d\'un contrat',
		'descWORKFLOW_ORDER_AUTOCREATE_INVOICE' => 'Créer une facture client automatiquement à la cloture d\'une commande client',
		'descWORKFLOW_ORDER_CLASSIFY_BILLED_PROPAL' => 'Classer facturée la proposition commerciale source quand la commande client dérivée est classée à payée',
		'descWORKFLOW_INVOICE_CLASSIFY_BILLED_ORDER' => 'Classer facturée la ou les commandes clients source quand la facture client dérivée est classée à payée'
);
?>