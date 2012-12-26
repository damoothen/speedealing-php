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
		'Actions' => 'Actions',
		'ActionsArea' => 'Actions svæði (Viðburðir og verkefni)',
		'Agenda' => 'Dagskrá',
		'Agendas' => 'Dagskrá',
		'Calendar' => 'Calendar',
		'Calendars' => 'Dagatöl',
		'AffectedTo' => 'Áhrifum á',
		'DoneBy' => 'Lokið við',
		'Events' => 'Viðburðir',
		'ListOfActions' => 'Listi yfir atburði',
		'Location' => 'Staðsetning',
		'SearchAnAction' => 'Leita aðgerð / verkefni',
		'MenuToDoActions' => 'Allar ófullnægjandi aðgerðir',
		'MenuDoneActions' => 'Allir ljúka aðgerðum',
		'MenuToDoMyActions' => 'ófullnægjandi aðgerðir mínar',
		'MenuDoneMyActions' => 'Hætta aðgerðum minn',
		'ListOfEvents' => 'Listi yfir Dolibarr viðburðir',
		'ActionsAskedBy' => 'Actions skráð',
		'ActionsToDoBy' => 'Actions áhrif til',
		'ActionsDoneBy' => 'Actions gert með því að',
		'AllMyActions' => 'Allar aðgerðir mínar / verkefni',
		'AllActions' => 'Allar aðgerðir / verkefni',
		'ViewList' => 'Skoða lista',
		'ViewCal' => 'Skoða dagatal',
		'ViewWithPredefinedFilters' => 'View með fyrirfram skilgreindum filters',
		'AutoActions' => 'Sjálfvirk fyllingu dagskrá',
		'AgendaAutoActionDesc' => 'Veldu hér viðburðir sem þú vilt Dolibarr að búa sjálfvirkt til aðgerða á dagskrá. Ef ekkert er valið (sjálfgefið), verður eingöngu handvirk aðgerð að koma fram í dagskrá.',
		'AgendaSetupOtherDesc' => 'Þessi síða leyfir að stilla aðrar breytur græju dagskrá.',
		'ActionsEvents' => 'Viðburðir sem Dolibarr vilja búa til aðgerða á dagskrá sjálfkrafa',
		'PropalValidatedInDolibarr' => 'Tillaga %s  staðfestar',
		'InvoiceValidatedInDolibarr' => 'Invoice %s  staðfestar',
		'OrderValidatedInDolibarr' => 'Panta %s  staðfestar',
		'InterventionValidatedInDolibarr' => 'Intervention %s  staðfestar',
		'NewCompanyToDolibarr' => 'Í þriðja aðila til',
		'DateActionPlannedStart' => 'Fyrirhugaður upphafsdagur',
		'DateActionPlannedEnd' => 'Áætlaðir lokadagur',
		'DateActionDoneStart' => 'Real upphafsdagur',
		'DateActionDoneEnd' => 'Real lokadagur',
		'DateActionStart' => 'Upphafsdagur',
		'DateActionEnd' => 'Lokadagur',
		'AgendaUrlOptions1' => 'Þú getur einnig bætt við eftirfarandi breytur til að sía framleiðsla:',
		'AgendaUrlOptions2' => '<b>login = %s </b> til að takmarka framleiðsla til aðgerða stofnuðum af áhrifum eða gert með <b>notandann %s .</b>',
		'AgendaUrlOptions3' => '<b>logina = %s </b> til að takmarka framleiðsla til aðgerða skapa við <b>notandann %s .</b>',
		'AgendaUrlOptions4' => '<b>logint = %s </b> til að takmarka framleiðsla til aðgerða áhrif til <b>notandi %s .</b>',
		'AgendaUrlOptions5' => '<b>logind = %s </b> til að takmarka framleiðsla til aðgerða gert með <b>notandann %s .</b>',
		'AgendaShowBirthdayEvents' => 'tengiliði Sýna afmæli\'s',
		'AgendaHideBirthdayEvents' => 'tengiliðir Fela afmæli\'s',
		'LocalAgenda' => 'Staðbundin dagbók',
		'MyEvents' => 'Viðburðir mín',
		'OtherEvents' => 'Aðrir viðburðir',
		'EventOnFullDay' => 'Atburður á fullu dag',
		'ViewDay' => 'Dagsskjár',
		'ViewWeek' => 'Vikuskjár',
		'AgendaExtSitesDesc' => 'Þessi síða leyfir þér að lýsa ytri uppsprettur dagatal til að sjá atburði í Dolibarr dagskrá.',
		'InvoiceBackToDraftInDolibarr' => 'Vörureikningi %s fara aftur til drög að stöðu',
		'OrderApprovedInDolibarr' => 'Panta %s samþykkt',
		'OrderBackToDraftInDolibarr' => 'Panta %s fara aftur til drög að stöðu',
		'OrderCanceledInDolibarr' => 'Panta %s niður',
		'ProposalSentByEMail' => 'Verslunarhúsnæði %s tillaga send með tölvupósti',
		'OrderSentByEMail' => 'Viðskiptavinur röð %s send með tölvupósti',
		'InvoiceSentByEMail' => 'Viðskiptavinur vörureikningi %s send með tölvupósti',
		'SupplierOrderSentByEMail' => 'Birgir röð %s send með tölvupósti',
		'SupplierInvoiceSentByEMail' => 'Birgir vörureikningi %s send með tölvupósti',
		'ShippingSentByEMail' => 'Sendingarmáti %s send með tölvupósti',
		'InterventionSentByEMail' => 'Inngrip %s send með tölvupósti',
		'ExportCal' => 'Útflutningur dagbók',
		'ExtSites' => 'Flytja ytri dagatöl',
		'ExtSitesEnableThisTool' => 'Sýna utanaðkomandi dagatöl í dagskrá',
		'ExtSitesNbOfAgenda' => 'Fjöldi dagatal',
		'AgendaExtNb' => 'Dagatal nb %s',
		'ExtSiteUrlAgenda' => 'Slóð til að opna. Kvæmd skrá',
		'ExtSiteNoLabel' => 'Engin lýsing',
);
?>