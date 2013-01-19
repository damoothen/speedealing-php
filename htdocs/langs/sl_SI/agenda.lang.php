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
		'Actions' => 'Aktivnosti',
		'ActionsArea' => 'Področje aktivnosti (dogodki in naloge)',
		'Agenda' => 'Program',
		'Agendas' => 'Programi',
		'Calendar' => 'Koledar',
		'Calendars' => 'Koledarji',
		'LocalAgenda' => 'Lokalni koledar',
		'AffectedTo' => 'Se nanaša na',
		'DoneBy' => 'Izdelal',
		'Event' => 'Event',
		'Events' => 'Dogodki',
		'MyEvents' => 'Moji dogodki',
		'OtherEvents' => 'Drugi dogodki',
		'ListOfActions' => 'Seznam dogodkov',
		'Location' => 'Lokacija',
		'EventOnFullDay' => 'Dogodek na zaseden dan',
		'SearchAnAction' => 'Iskanje aktivnosti/naloge',
		'MenuToDoActions' => 'Vse nedokončane aktivnosti',
		'MenuDoneActions' => 'Vse prekinjene aktivnosti',
		'MenuToDoMyActions' => 'Moje nedokončane aktivnosti',
		'MenuDoneMyActions' => 'Moje prekinjene aktivnosti',
		'ListOfEvents' => 'Seznam Dolibarr dogodkov',
		'ActionsAskedBy' => 'Aktivnost vnesel',
		'ActionsToDoBy' => 'Aktivnost se nanaša na',
		'ActionsDoneBy' => 'Aktivnost izvedel',
		'AllMyActions' => 'Vse moje aktivnosti/naloge',
		'AllActions' => 'Vse aktivnosti/naloge',
		'ViewList' => 'Gelj seznam',
		'ViewCal' => 'Glej koledar',
		'ViewDay' => 'Dnevni pogled',
		'ViewWeek' => 'Tedenski pogled',
		'ViewWithPredefinedFilters' => 'Glej z prednastavljenimi filtri',
		'AutoActions' => 'Avtomatska izpolnitev programa',
		'AgendaAutoActionDesc' => 'Tukaj definirajte dogodke, za katere želite, da Dolibarr avtomatsko kreira aktivnost v programu. Če ni označeno ničesar (privzeto), bodo v program vključene samo ročno vnesene aktivnosti.',
		'AgendaSetupOtherDesc' => 'Ta stran omogoča konfiguracijo drugih parametrov modula za programe.',
		'AgendaExtSitesDesc' => 'Ta stran omogoča konfiguracijo zunanjih koledarjev',
		'ActionsEvents' => 'Dogodki, za katere bo Dolibarr avtomatsko kreiral aktivnost v programu',
		'PropalValidatedInSpeedealing' => 'Proposal %s validated',
		'InvoiceValidatedInSpeedealing' => 'Invoice %s validated',
		'InvoiceBackToDraftInSpeedealing' => 'Invoice %s go back to draft status',
		'OrderValidatedInSpeedealing' => 'Order %s validated',
		'OrderApprovedInSpeedealing' => 'Order %s approved',
		'OrderBackToDraftInSpeedealing' => 'Order %s go back to draft status',
		'OrderCanceledInSpeedealing' => 'Order %s canceled',
		'InterventionValidatedInSpeedealing' => 'Intervention %s validated',
		'ProposalSentByEMail' => 'Komercialna ponudba %s poslana po elektronski pošti',
		'OrderSentByEMail' => 'Naročilo kupca %s poslano po elektronski pošti',
		'InvoiceSentByEMail' => 'Račun kupcu %s poslan po elektronski pošti',
		'SupplierOrderSentByEMail' => 'Naročilo dobavitelju %s poslano po elektronski pošti',
		'SupplierInvoiceSentByEMail' => 'Račun odbavitelja %s poslan po elektronski pošti',
		'ShippingSentByEMail' => 'Pošiljka %s poslana po EMailu',
		'InterventionSentByEMail' => 'Intervencija %s poslana po EMailu',
		'NewCompanyToSpeedealing' => 'Third party created',
		'DateActionPlannedStart' => 'Planiran začetni datum',
		'DateActionPlannedEnd' => 'Planiran končni datum',
		'DateActionDoneStart' => 'Realen začetni datum',
		'DateActionDoneEnd' => 'Realen končni datum',
		'DateActionStart' => 'Začetni datum',
		'DateActionEnd' => 'Končni datum',
		'AgendaUrlOptions1' => 'V filtriran izhod lahko dodate tudi naslednje parametre:',
		'AgendaUrlOptions2' => '<b>login=%s</b> za omejitev izhoda na aktivnosti, ki se nanašajo, ali jih je naredil uporabnik <b>%s</b>.',
		'AgendaUrlOptions3' => '<b>logina=%s</b> za omejitev izhoda na aktivnosti, ki jih je naredil uporabnik <b>%s</b>.',
		'AgendaUrlOptions4' => '<b>logint=%s</b> za omejitev izhoda na aktivnosti, ki se nanašajo na uporabnika <b>%s</b>.',
		'AgendaUrlOptions5' => '<b>logind=%s</b> za omejitev izhoda na aktivnosti uporabnika <b>%s</b>.',
		'AgendaShowBirthdayEvents' => 'Prikaži rojstni dan kontakta',
		'AgendaHideBirthdayEvents' => 'Skrij rojstni dan kontakta',
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
		'ExportCal' => 'Izvoz koledar',
		'ExtSites' => 'Zunanji koledarji',
		'ExtSitesEnableThisTool' => 'Kako prenesti zunanji koledar v urnik',
		'ExtSitesNbOfAgenda' => 'Število koledarjev',
		'AgendaExtNb' => 'Koledar št. %s',
		'ExtSiteUrlAgenda' => 'URL za dostop do .ical datoteke',
		'ExtSiteNoLabel' => 'Ni opisa'
);
?>