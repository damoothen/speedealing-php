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
		'Actions' => 'Acties',
		'ActionsArea' => 'Acties gebied (Evenementen en taken)',
		'Agenda' => 'Agenda',
		'Agendas' => 'Agenda\'s',
		'Calendar' => 'Kalender',
		'Calendars' => 'Kalenders',
		'LocalAgenda' => 'Local calendar',
		'AffectedTo' => 'Beïnvloed door',
		'DoneBy' => 'Gedaan door',
		'Events' => 'Evenementen',
		'MyEvents' => 'My events',
		'OtherEvents' => 'Other events',
		'ListOfActions' => 'Lijst met evenementen',
		'Location' => 'Locatie',
		'EventOnFullDay' => 'Event on full day',
		'SearchAnAction' => 'Zoek een actie / taak',
		'MenuToDoActions' => 'Alle onvolledige acties',
		'MenuDoneActions' => 'Alle beëindigde acties',
		'MenuToDoMyActions' => 'Mijn onvolledige acties',
		'MenuDoneMyActions' => 'Mijn beëindigde acties',
		'ListOfEvents' => 'Lijst van Dolibarr evenementen',
		'ActionsAskedBy' => 'Acties door',
		'ActionsToDoBy' => 'Acties beïnvloed door',
		'ActionsDoneBy' => 'Acties gedaan door',
		'AllMyActions' => 'Al mijn acties / taken',
		'AllActions' => 'Alle acties / taken',
		'ViewList' => 'Bekijk de lijst',
		'ViewCal' => 'Bekijk kalender',
		'ViewDay' => 'Day view',
		'ViewWeek' => 'Week view',
		'ViewWithPredefinedFilters' => 'Bekijk met voorgedefinieerde filters',
		'AutoActions' => 'Automatisch invullen van de agenda',
		'AgendaAutoActionDesc' => 'Definieer hier evenementen waarvoor u wilt dat Dolibarr automatisch een actie op de agenda creëert. Als er niets is aangevinkt (standaard), alleen handmatige acties zullen worden opgenomen in de agenda.',
		'AgendaSetupOtherDesc' => 'This page provides options to allow export of your Dolibarr events into an external calendar (thunderbird, google calendar, ...)',
		'AgendaExtSitesDesc' => 'This page allows to declare external sources of calendars to see their events into Dolibarr agenda.',
		'ActionsEvents' => 'Evenementen waarvan Dolibarr automatisch een actie in de agenda zal creëren',
		'PropalValidatedInDolibarr' => 'Voorstel gevalideerd',
		'InvoiceValidatedInDolibarr' => 'Factuur gevalideerd',
		'InvoiceBackToDraftInDolibarr' => 'Invoice %s go back to draft status',
		'OrderValidatedInDolibarr' => 'Bestelling gevalideerd',
		'OrderApprovedInDolibarr' => 'Order %s approved',
		'OrderBackToDraftInDolibarr' => 'Order %s go back to draft status',
		'OrderCanceledInDolibarr' => 'Order %s canceled',
		'InterventionValidatedInDolibarr' => 'Intervention %s validated',
		'ProposalSentByEMail' => 'Commercial proposal %s sent by EMail',
		'OrderSentByEMail' => 'Customer order %s sent by EMail',
		'InvoiceSentByEMail' => 'Customer invoice %s sent by EMail',
		'SupplierOrderSentByEMail' => 'Supplier order %s sent by EMail',
		'SupplierInvoiceSentByEMail' => 'Supplier invoice %s sent by EMail',
		'ShippingSentByEMail' => 'Shipping %s sent by EMail',
		'InterventionSentByEMail' => 'Intervention %s sent by EMail',
		'NewCompanyToDolibarr' => 'Derde partij gemaakt',
		'DateActionPlannedStart' => 'Geplande startdatum',
		'DateActionPlannedEnd' => 'Geplande einddatum',
		'DateActionDoneStart' => 'Werkelijke startdatum',
		'DateActionDoneEnd' => 'Werkelijke einddatum',
		'DateActionStart' => 'Begindatum',
		'DateActionEnd' => 'Einddatum',
		'AgendaUrlOptions1' => 'U kan ook de volgende parameters toevoegen voor de uitkomst van de filter:',
		'AgendaUrlOptions2' => '<b>login=%s</b> om de uitkomst van de acties te beperken:  gemaakt door, beïnvloed door of gedaan door gebruiker <b>%s</b>',
		'AgendaUrlOptions3' => '<b>logina=%s</b> om de uitkomst van de acties te beperken:  gemaakt door gebruiker <b>%s</b>',
		'AgendaUrlOptions4' => '<b>logint=%s</b> to restrict output to actions affected to user <b>%s</b>.',
		'AgendaUrlOptions5' => '<b>logind=%s</b> om de uitkomst van de acties te beperken: gedaan door gebruiker <b>%s</b>',
		'AgendaShowBirthdayEvents' => 'Toon verjaardagen van contacten',
		'AgendaHideBirthdayEvents' => 'Verberg verjaardagen van contacten',
		'Event' => 'Événement',
		'Activities' => 'Tâches/activités',
		'NewActions' => 'Nouvelles<br>actions',
		'DoActions' => 'Actions<br>en cours',
		'SumMyActions' => 'Actions réalisées<br>par moi cette année',
		'SumActions' => 'Actions au total<br>cette année',
		'DateEchAction' => 'Date d\'échéance',
		'StatusActionTooLate' => 'Action en retard',
		'MyTasks' => 'Mes tâches',
		'MyDelegatedTasks' => 'Mes tâches déléguées',
		'ProdPlanning' => 'Planning de production',
		// External Sites ical
		'ExportCal' => 'Export calendar',
		'ExtSites' => 'Import external calendars',
		'ExtSitesEnableThisTool' => 'Show external calendars into agenda',
		'ExtSitesNbOfAgenda' => 'Number of calendars',
		'AgendaExtNb' => 'Calendar nb %s',
		'ExtSiteUrlAgenda' => 'URL to access .ical file',
		'ExtSiteNoLabel' => 'No Description'
);
?>