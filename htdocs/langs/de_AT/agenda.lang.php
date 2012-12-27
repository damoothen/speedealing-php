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

$agenda = array(
		'CHARSET' => 'UTF-8',
		'Actions' => 'Aktionen',
		'ActionsArea' => 'Bereich Maßnahmen (Veranstaltungen und Aufgaben)',
		'Agenda' => 'Agenda',
		'Agendas' => 'Tagesordnungen',
		'Calendar' => 'Kalender',
		'Calendars' => 'Kalender',
		'LocalAgenda' => 'Local calendar',
		'AffectedTo' => 'Zugewiesen an',
		'DoneBy' => 'Erldedigt von',
		'Events' => 'Veranstaltungen',
		'MyEvents' => 'My events',
		'OtherEvents' => 'Other events',
		'ListOfActions' => 'Veranstaltungsliste',
		'Location' => 'Ort',
		'EventOnFullDay' => 'Event on full day',
		'SearchAnAction' => 'Suche Maßnahme / Aufgabe',
		'MenuToDoActions' => 'Alle unvollständigen Maßnahmen',
		'MenuDoneActions' => 'Alle abgeschlossenen Maßnahmen',
		'MenuToDoMyActions' => 'Meine offenen Maßnahmen',
		'MenuDoneMyActions' => 'Meine abgeschlossenen Maßnahmen',
		'ListOfEvents' => 'Veranstaltungsliste',
		'ActionsAskedBy' => 'Maßnahmen erbeten von',
		'ActionsToDoBy' => 'Maßnahmen zugewiesen an',
		'ActionsDoneBy' => 'Maßnahmen erledigt von',
		'AllMyActions' => 'Alle meine Maßnahmen / Aufgaben',
		'AllActions' => 'Alle Maßnahmen / Aufgaben',
		'ViewList' => 'Liste anzeigen',
		'ViewCal' => 'Kalender anzeigen',
		'ViewDay' => 'Day view',
		'ViewWeek' => 'Week view',
		'ViewWithPredefinedFilters' => 'Ansicht mit vordefinierten Filtern',
		'AutoActions' => 'Automatische Befüllung der Tagesordnung',
		'AgendaAutoActionDesc' => 'Definieren Sie hier Maßnahmen zur automatischen Übernahme in die Agenda. Ist nichts aktviert (Standard), umfasst die Agenda nur manuell eingetragene Maßnahmen.',
		'AgendaSetupOtherDesc' => 'Diese Seite ermöglicht die Konfiguration anderer Parameter des Tagesordnungsmoduls.',
		'AgendaExtSitesDesc' => 'This page allows to declare external sources of calendars to see their events into Dolibarr agenda.',
		'ActionsEvents' => 'Veranstaltungen zur automatischen Übernahme in die Agenda',
		'PropalValidatedInDolibarr' => 'Angebot freigegeben',
		'InvoiceValidatedInDolibarr' => 'Rechnung freigegeben',
		'InvoiceBackToDraftInDolibarr' => 'Invoice %s go back to draft status',
		'OrderValidatedInDolibarr' => 'Bestellung freigegeben',
		'OrderApprovedInDolibarr' => 'Order %s approved',
		'OrderBackToDraftInDolibarr' => 'Order %s go back to draft status',
		'OrderCanceledInDolibarr' => 'Order %s canceled',
		'InterventionValidatedInDolibarr' => 'Eingriff %s freigegeben',
		'ProposalSentByEMail' => 'Commercial proposal %s sent by EMail',
		'OrderSentByEMail' => 'Customer order %s sent by EMail',
		'InvoiceSentByEMail' => 'Customer invoice %s sent by EMail',
		'SupplierOrderSentByEMail' => 'Supplier order %s sent by EMail',
		'SupplierInvoiceSentByEMail' => 'Supplier invoice %s sent by EMail',
		'ShippingSentByEMail' => 'Shipping %s sent by EMail',
		'InterventionSentByEMail' => 'Intervention %s sent by EMail',
		'NewCompanyToDolibarr' => 'Partner erstellt',
		'DateActionPlannedStart' => 'Geplantes Startdatum',
		'DateActionPlannedEnd' => 'Geplantes Enddatum',
		'DateActionDoneStart' => 'Effektiver Beginn',
		'DateActionDoneEnd' => 'Effektives Ende',
		'DateActionStart' => 'Startdatum',
		'DateActionEnd' => 'Enddatum',
		'AgendaUrlOptions1' => 'Sie können die Ausgabe über folgende Parameter filtern:',
		'AgendaUrlOptions2' => '<b>login=%s</b> begrenzt die Ausgabe auf von Benutzer <b>%s</b> erstellte, betroffene oder erledigte Maßnahmen.',
		'AgendaUrlOptions3' => '<b>logina=%s</b> begrenzt die Ausgabe auf von Benutzer <b>%s</b> erstellte Maßnahmen.',
		'AgendaUrlOptions4' => '<b>logint=%s</b> begrenzt die Ausgabe auf von Benutzer <b>%s</b> betroffene Maßnahmen.',
		'AgendaUrlOptions5' => '<b>logind=%s</b> begrenzt die Ausgabe auf von Benutzer <b>%s</b> erledigte Maßnahmen.',
		'AgendaShowBirthdayEvents' => 'Zeige Geburtstage',
		'AgendaHideBirthdayEvents' => 'Geburtstage ausblenden',
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