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

$agenda = array(
		'CHARSET' => 'UTF-8',
		'Actions' => 'Esdeveniments',
		'ActionsArea' => 'Àrea d\'esdeveniments (accions i tasques)',
		'Agenda' => 'Agenda',
		'Agendas' => 'Agendes',
		'Calendar' => 'Calendari',
		'Calendars' => 'Calendaris',
		'LocalAgenda' => 'Calendari local',
		'AffectedTo' => 'Assignada a',
		'DoneBy' => 'Realitzat per',
		'Event' => 'Event',
		'Events' => 'Esdeveniments',
		'MyEvents' => 'Els meus events',
		'OtherEvents' => 'Altres events',
		'ListOfActions' => 'Llista d\'esdeveniments',
		'Location' => 'Localització',
		'EventOnFullDay' => 'Esdeveniment per tot el dia',
		'SearchAnAction' => 'Cercar un esdeveniment/tasca',
		'MenuToDoActions' => 'Esdeveniments incomplets',
		'MenuDoneActions' => 'Esdeveniments acabats',
		'MenuToDoMyActions' => 'Els meus esdeveniments incomplets',
		'MenuDoneMyActions' => 'Els meus esdeveniments acabats',
		'ListOfEvents' => 'Llistat d\'esdeveniments Dolibarr',
		'ActionsAskedBy' => 'Esdeveniments registrats per',
		'ActionsToDoBy' => 'Esdeveniments assignats a',
		'ActionsDoneBy' => 'Esdeveniments realitzats per',
		'AllMyActions' => 'Tots els meus esdeveniments/tasques',
		'AllActions' => 'Tots els esdeveniments/tasques',
		'ViewList' => 'Vista llistat',
		'ViewCal' => 'Vista mensual',
		'ViewDay' => 'Vista diària',
		'ViewWeek' => 'Vista setmanal',
		'ViewWithPredefinedFilters' => 'Veure amb els filtres predefinits',
		'AutoActions' => 'Inclusió automàtica a l\'agenda',
		'AgendaAutoActionDesc' => 'Indiqueu en aquesta pestanya els esdeveniments per els que desitja que Dolibarr creu automàticament una acció a l\'agenda. Si no es marca cap cas (per defecte), només les accions manuals s\'han d\'incloure en l\'agenda.',
		'AgendaSetupOtherDesc' => 'Aquesta pàgina permet configurar algunes opcions que permeten exportar una vista de la seva agenda Dolibar a un calendari extern (thunderbird, google calendar, ...)',
		'AgendaExtSitesDesc' => 'Aquesta pàgina permet configurar calendaris externs per a la seva visualització en l\'agenda de Dolibarr.',
		'ActionsEvents' => 'Esdeveniments per a què Dolibarr crei una acció de forma automàtica',
		'PropalValidatedInSpeedealing' => 'Proposal %s validated',
		'InvoiceValidatedInSpeedealing' => 'Invoice %s validated',
		'InvoiceBackToDraftInSpeedealing' => 'Invoice %s go back to draft status',
		'OrderValidatedInSpeedealing' => 'Order %s validated',
		'OrderApprovedInSpeedealing' => 'Order %s approved',
		'OrderBackToDraftInSpeedealing' => 'Order %s go back to draft status',
		'OrderCanceledInSpeedealing' => 'Order %s canceled',
		'InterventionValidatedInSpeedealing' => 'Intervention %s validated',
		'ProposalSentByEMail' => 'Pressupost %s enviat per e-mail',
		'OrderSentByEMail' => 'Comanda de client %s enviada per e-mail',
		'InvoiceSentByEMail' => 'Factura a client %s enviada per e-mail',
		'SupplierOrderSentByEMail' => 'Comanda a proveïdor %s enviada per e-mail',
		'SupplierInvoiceSentByEMail' => 'Factura de proveïdor %s enviada per e-mail',
		'ShippingSentByEMail' => 'Expedició %s enviada per e-mail',
		'InterventionSentByEMail' => 'Intervenció %s enviada per e-mail',
		'NewCompanyToSpeedealing' => 'Third party created',
		'DateActionPlannedStart' => 'Data d\'inici prevista',
		'DateActionPlannedEnd' => 'Data fi prevista',
		'DateActionDoneStart' => 'Data real d\'inici',
		'DateActionDoneEnd' => 'Data real de finalització',
		'DateActionStart' => 'Data d\'inici',
		'DateActionEnd' => 'Data finalització',
		'AgendaUrlOptions1' => 'Podeu també afegir aquests paràmetres al filtre de sortida:',
		'AgendaUrlOptions2' => '<b>login=%s</b> per a restringir insercions a accions creades, que afectin o realitzades per l\'usuari <b>%s</b>.',
		'AgendaUrlOptions3' => '<b>logina=%s</b> per a restringir insercions a accciones creades per l\'usuari <b>%s</b>.',
		'AgendaUrlOptions4' => '<b>logint=%s</b> per a restringir insercions a accions que afectin a l\'usuari <b>%s</b>.',
		'AgendaUrlOptions5' => '<b>logind=%s</b> per a restringir insercions a accions realitzades per l\'usuari <b>%s</b>.',
		'AgendaShowBirthdayEvents' => 'Mostra aniversari dels contactes',
		'AgendaHideBirthdayEvents' => 'Amaga aniversari dels contacte',
		'Activities' => 'Activities',
		'NewActions' => 'News<br>Actions',
		'DoActions' => 'Actions<br>in progress',
		'SumMyActions' => 'Actions done<br>by me this year',
		'SumActions' => 'Actions in total<br>this year',
		'DateEchAction' => 'Deadline',
		'StatusActionTooLate' => 'Action delay',
		'MyTasks' => 'My tasks',
		'MyDelegatedTasks' => 'My delegated tasks',
		'ProdPlanning' => 'Planning of production',
		// External Sites ical
		'ExportCal' => 'Exportar calendari',
		'ExtSites' => 'Calendaris externs',
		'ExtSitesEnableThisTool' => 'Mostrar calendaris externs a l\'agenda',
		'ExtSitesNbOfAgenda' => 'Nombre de calendaris',
		'AgendaExtNb' => 'Calendari nº %s',
		'ExtSiteUrlAgenda' => 'Url d\'accés a l\'arxiu. ical',
		'ExtSiteNoLabel' => 'Sense descripció'
);
?>