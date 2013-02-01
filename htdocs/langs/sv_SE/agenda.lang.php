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

$agenda = array(
		'CHARSET' => 'UTF-8',
		'Actions' => 'Åtgärder',
		'ActionsArea' => 'Åtgärder område (händelser och uppgifter)',
		'Agenda' => 'Agenda',
		'Agendas' => 'Dagordningar',
		'Calendar' => 'Kalender',
		'Calendars' => 'Kalendrar',
		'LocalAgenda' => 'Lokal kalender',
		'AffectedTo' => 'Påverkas i',
		'DoneBy' => 'Utfärdat av',
		'Event' => 'Event',
		'Events' => 'Evenemang',
		'MyEvents' => 'Mina aktiviteter',
		'OtherEvents' => 'Övriga händelser',
		'ListOfActions' => 'Lista över evenemang',
		'Location' => 'Läge',
		'EventOnFullDay' => 'Händelse heldag',
		'SearchAnAction' => 'Sök en handling / uppgift',
		'MenuToDoActions' => 'Alla ofullständiga handlingar',
		'MenuDoneActions' => 'Alla avslutade åtgärder',
		'MenuToDoMyActions' => 'Min ofullständiga handlingar',
		'MenuDoneMyActions' => 'Min avslutas åtgärder',
		'ListOfEvents' => 'Lista över Speedealing händelser',
		'ActionsAskedBy' => 'Åtgärder som registrerats av',
		'ActionsToDoBy' => 'Åtgärder påverkas',
		'ActionsDoneBy' => 'Åtgärder som utförs av',
		'AllMyActions' => 'Alla mina handlingar och uppgifter',
		'AllActions' => 'Alla åtgärder / uppgifter',
		'ViewList' => 'Visa lista',
		'ViewCal' => 'Visa kalender',
		'ViewDay' => 'Dagsvy',
		'ViewWeek' => 'Veckovy',
		'ViewWithPredefinedFilters' => 'Visa med fördefinierade filter',
		'AutoActions' => 'Automatisk fyllning av dagordning',
		'AgendaAutoActionDesc' => 'Här definierar du händelser som du vill Speedealing att automatiskt skapa en talan i dagordningen. Om ingenting är markerad (som standard), kommer endast manuella, skall ingå i dagordningen.',
		'AgendaSetupOtherDesc' => 'Denna sida tillåter dig att ändra andra parametrar i dagordningen modul.',
		'AgendaExtSitesDesc' => 'Den här sidan gör det möjligt att deklarera externa kalendrar för att se sina evenemang i Speedealing agenda.',
		'ActionsEvents' => 'Händelser som Speedealing kommer att skapa en talan i agenda automatiskt',
		'PropalValidatedInSpeedealing' => 'Proposal %s validated',
		'InvoiceValidatedInSpeedealing' => 'Invoice %s validated',
		'InvoiceBackToDraftInSpeedealing' => 'Invoice %s go back to draft status',
		'OrderValidatedInSpeedealing' => 'Order %s validated',
		'OrderApprovedInSpeedealing' => 'Order %s approved',
		'OrderBackToDraftInSpeedealing' => 'Order %s go back to draft status',
		'OrderCanceledInSpeedealing' => 'Order %s canceled',
		'InterventionValidatedInSpeedealing' => 'Intervention %s validated',
		'ProposalSentByEMail' => 'Kommersiella förslag %s via e-post',
		'OrderSentByEMail' => 'Kundorderprojekt %s via e-post',
		'InvoiceSentByEMail' => 'Kundfaktura %s via e-post',
		'SupplierOrderSentByEMail' => 'Leverantör beställa %s via e-post',
		'SupplierInvoiceSentByEMail' => 'Leverantörsfaktura %s via e-post',
		'ShippingSentByEMail' => 'Frakt %s via e-post',
		'InterventionSentByEMail' => 'Intervention %s via e-post',
		'NewCompanyToSpeedealing' => 'Third party created',
		'DateActionPlannedStart' => 'Planerat startdatum',
		'DateActionPlannedEnd' => 'Planerat slutdatum',
		'DateActionDoneStart' => 'Real startdatum',
		'DateActionDoneEnd' => 'Real slutdatum',
		'DateActionStart' => 'Startdatum',
		'DateActionEnd' => 'Slutdatum',
		'AgendaUrlOptions1' => 'Du kan också lägga till följande parametrar för att filtrera utgång:',
		'AgendaUrlOptions2' => '<b>login = %s</b> att begränsa produktionen till åtgärder inrättade av, påverkas eller göras av användaren <b>%s.</b>',
		'AgendaUrlOptions3' => '<b>logina = %s</b> att begränsa produktionen till åtgärder som skapats av användaren <b>%s.</b>',
		'AgendaUrlOptions4' => '<b>logint = %s</b> att begränsa produktionen till handlande påverkade användarnas <b>%s.</b>',
		'AgendaUrlOptions5' => '<b>logind = %s</b> att begränsa produktionen till åtgärder som utförts av användaren <b>%s.</b>',
		'AgendaShowBirthdayEvents' => 'Visa födelsedag kontakter',
		'AgendaHideBirthdayEvents' => 'Dölj födelsedag kontakter',
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
		'ExportCal' => 'Export kalender',
		'ExtSites' => 'Importera externa kalendrar',
		'ExtSitesEnableThisTool' => 'Visa externa kalendrar till dagordning',
		'ExtSitesNbOfAgenda' => 'Antal kalendrar',
		'AgendaExtNb' => 'Kalender nb %s',
		'ExtSiteUrlAgenda' => 'URL att komma åt. Ical-fil',
		'ExtSiteNoLabel' => 'Ingen beskrivning'
);
?>