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