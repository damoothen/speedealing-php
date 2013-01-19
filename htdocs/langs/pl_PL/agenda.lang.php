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
		'Actions' => 'Działania',
		'ActionsArea' => 'Działania obszaru (Zdarzenia i zadania)',
		'Agenda' => 'Porządek obrad',
		'Agendas' => 'Agendas',
		'Calendar' => 'Kalendarz',
		'Calendars' => 'Kalendarze',
		'LocalAgenda' => 'Lokalnym kalendarzem',
		'AffectedTo' => 'Affected do',
		'DoneBy' => 'Sporządzono przez',
		'Event' => 'Event',
		'Events' => 'Wydarzenia',
		'MyEvents' => 'Moje imprezy',
		'OtherEvents' => 'Inne wydarzenia',
		'ListOfActions' => 'Lista wydarzeń',
		'Location' => 'Położenie',
		'EventOnFullDay' => 'Wydarzenie na cały dzień',
		'SearchAnAction' => 'Szukaj działania / zadania',
		'MenuToDoActions' => 'Wszystkich działań niekompletne',
		'MenuDoneActions' => 'Wszystkie działania zakończone',
		'MenuToDoMyActions' => 'Moje działania niekompletne',
		'MenuDoneMyActions' => 'Moje działania zakończone',
		'ListOfEvents' => 'Wykaz imprez Dolibarr',
		'ActionsAskedBy' => 'Akcje zostały zarejestrowane przez',
		'ActionsToDoBy' => 'Akcje przeznaczone na',
		'ActionsDoneBy' => 'Czynności wykonywane przez',
		'AllMyActions' => 'Wszystkie moje działania / zadania',
		'AllActions' => 'Toutes les działania / zadania',
		'ViewList' => 'Pokaż listę',
		'ViewCal' => 'Wyświetl kalendarz',
		'ViewDay' => 'Widok dnia',
		'ViewWeek' => 'Widok tygodnia',
		'ViewWithPredefinedFilters' => 'Widok z predefiniowane filtry',
		'AutoActions' => 'Automatyczne wypełnianie porządku',
		'AgendaAutoActionDesc' => 'Określ tutaj zdarzeń, dla której chcesz Dolibarr aby utworzyć automatycznie działania w porządku. Jeżeli nie jest zaznaczone (domyślnie), tylko podręcznik działań będą uwzględnione w porządku obrad.',
		'AgendaSetupOtherDesc' => 'Ta strona pozwala skonfigurować inne parametry porządku modułu.',
		'AgendaExtSitesDesc' => 'Ta strona pozwala zadeklarować zewnętrznych źródeł kalendarzy, aby zobaczyć swoje imprezy do porządku obrad Dolibarr.',
		'ActionsEvents' => 'Zdarzenia, za które Dolibarr stworzy automatycznie działania w porządku',
		'PropalValidatedInSpeedealing' => 'Proposal %s validated',
		'InvoiceValidatedInSpeedealing' => 'Invoice %s validated',
		'InvoiceBackToDraftInSpeedealing' => 'Invoice %s go back to draft status',
		'OrderValidatedInSpeedealing' => 'Order %s validated',
		'OrderApprovedInSpeedealing' => 'Order %s approved',
		'OrderBackToDraftInSpeedealing' => 'Order %s go back to draft status',
		'OrderCanceledInSpeedealing' => 'Order %s canceled',
		'InterventionValidatedInSpeedealing' => 'Intervention %s validated',
		'ProposalSentByEMail' => 'Commercial %s propozycji przesłanej przez e-mail',
		'OrderSentByEMail' => '%s zamówień klientów wysłane pocztą',
		'InvoiceSentByEMail' => '%s faktur klientów wysłane pocztą',
		'SupplierOrderSentByEMail' => '%s zamówienie dostawca przesłać pocztą elektroniczną',
		'SupplierInvoiceSentByEMail' => '%s faktur dostawca przesłać pocztą elektroniczną',
		'ShippingSentByEMail' => '%s przesyłki wysłane pocztą',
		'InterventionSentByEMail' => '%s interwencyjne wysłane pocztą',
		'NewCompanyToSpeedealing' => 'Third party created',
		'DateActionPlannedStart' => 'Planowana data rozpoczęcia',
		'DateActionPlannedEnd' => 'Planowana data zakończenia',
		'DateActionDoneStart' => 'Real data rozpoczęcia',
		'DateActionDoneEnd' => 'Real data zakończenia',
		'DateActionStart' => 'Data rozpoczęcia',
		'DateActionEnd' => 'Data zakończenia',
		'AgendaUrlOptions1' => 'Możesz także dodać następujące parametry do filtr wyjściowy:',
		'AgendaUrlOptions2' => '<b>login=<b>login= %s,</b> aby ograniczyć wyjścia do działań stworzonych przez wpływ, lub wykonane przez <b>użytkownika %s.</b>',
		'AgendaUrlOptions3' => '<b>logina=<b>logina= %s,</b> aby ograniczyć wyjścia do działań stworzonych przez <b>użytkownika %s.</b>',
		'AgendaUrlOptions4' => '<b>logint=<b>logint= %s,</b> aby ograniczyć wyjścia do działań dotknięte do <b>użytkownika %s.</b>',
		'AgendaUrlOptions5' => '<b>logind=<b>logowanie= %s,</b> aby ograniczyć wyjścia do działań wykonanych przez <b>użytkownika %s.</b>',
		'AgendaShowBirthdayEvents' => 'Pokaż urodziny kontaktów',
		'AgendaHideBirthdayEvents' => 'Ukryj urodzin kontaktów',
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
		'ExportCal' => 'Eksport kalendarza',
		'ExtSites' => 'Importowanie zewnętrznych kalendarzy',
		'ExtSitesEnableThisTool' => 'Pokaż zewnętrznych kalendarzy do porządku obrad',
		'ExtSitesNbOfAgenda' => 'Liczba kalendarzy',
		'AgendaExtNb' => 'Kalendarz nb %s',
		'ExtSiteUrlAgenda' => 'URL dostępu. Plik iCal',
		'ExtSiteNoLabel' => 'Brak opisu'
);
?>