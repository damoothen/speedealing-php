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
		'Actions' => 'Toimet',
		'ActionsArea' => 'Toimien alueella (tapahtumat ja tehtävät)',
		'Agenda' => 'Agenda',
		'Agendas' => 'Esityslistat',
		'Calendar' => 'Kalenteri',
		'Calendars' => 'Kalenterit',
		'LocalAgenda' => 'Paikallinen kalenteri',
		'AffectedTo' => 'Vaikuttaa',
		'DoneBy' => 'Tekemiäni',
		'Events' => 'Tapahtumat',
		'MyEvents' => 'My tapahtumia',
		'OtherEvents' => 'Muut tapahtumat',
		'ListOfActions' => 'Luettelo tapahtumista',
		'Location' => 'Sijainti',
		'EventOnFullDay' => 'Tapahtuma on koko päivä',
		'SearchAnAction' => 'Haku toimintasuunnitelma / tehtävä',
		'MenuToDoActions' => 'Kaikki puutteelliset toimet',
		'MenuDoneActions' => 'Kaikki irti toimia',
		'MenuToDoMyActions' => 'Oma puutteellisia toimia',
		'MenuDoneMyActions' => 'Oma irtisanotaan toimia',
		'ListOfEvents' => 'Luettelo Dolibarr tapahtumat',
		'ActionsAskedBy' => 'Toimet kirjattava',
		'ActionsToDoBy' => 'Toimet vaikuttaa',
		'ActionsDoneBy' => 'Toimet tehdään',
		'AllMyActions' => 'Kaikki toimet / tehtävät',
		'AllActions' => 'Toutes les actions / tehtävät',
		'ViewList' => 'Näytä lista',
		'ViewCal' => 'Näytä kalenteri',
		'ViewDay' => 'Päivä näkymä',
		'ViewWeek' => 'Viikkonäkymä',
		'ViewWithPredefinedFilters' => 'Näytä ennalta suodattimet',
		'AutoActions' => 'Automaattinen täyttö Esityslistan',
		'AgendaAutoActionDesc' => 'Määritä tässä tapahtumia joihin haluat Dolibarr luoda automaattisesti toimia asialistalla. Jos mitään ei tarkastettu (oletuksena), vain ruumiillisen toimien on oltava mukana esityslistalla.',
		'AgendaSetupOtherDesc' => 'Tämän sivun avulla määrittää muita muuttujia Esityslistan moduuli.',
		'AgendaExtSitesDesc' => 'Tällä sivulla voit ilmoittaa ulkoisten kalenterien näkemään tapahtumiin otetaan Dolibarr asialistalle.',
		'ActionsEvents' => 'Tapahtumat, joista Dolibarr luo toimia esityslistan automaattisesti',
		'PropalValidatedInDolibarr' => 'Ehdotus validoitava',
		'InvoiceValidatedInDolibarr' => 'Laskun validoitava',
		'InvoiceBackToDraftInDolibarr' => 'Laskun %s palata luonnos tila',
		'OrderValidatedInDolibarr' => 'Tilaa validoitava',
		'OrderApprovedInDolibarr' => 'Tilaa %s hyväksytty',
		'OrderBackToDraftInDolibarr' => 'Tilaa %s palata luonnos tila',
		'OrderCanceledInDolibarr' => 'Tilaus %s peruutettu',
		'InterventionValidatedInDolibarr' => 'Intervention %s validoitu',
		'ProposalSentByEMail' => 'Liiketoimintaehdotukset %s lähetetään sähköpostilla',
		'OrderSentByEMail' => 'Asiakas tilaa %s lähetetään sähköpostilla',
		'InvoiceSentByEMail' => 'Asiakas lasku %s lähetetään sähköpostilla',
		'SupplierOrderSentByEMail' => 'Toimittaja järjestys %s lähetetään sähköpostilla',
		'SupplierInvoiceSentByEMail' => 'Toimittaja lasku %s lähetetään sähköpostilla',
		'ShippingSentByEMail' => 'Toimitus %s lähetetään sähköpostilla',
		'InterventionSentByEMail' => 'Intervention %s lähetetään sähköpostilla',
		'NewCompanyToDolibarr' => 'Kolmannen osapuolen luonut',
		'DateActionPlannedStart' => 'SUUNNITELTU ALKAMISPÄIVÄ',
		'DateActionPlannedEnd' => 'Suunniteltu lopetuspäivämäärää',
		'DateActionDoneStart' => 'Real Aloituspäivästä',
		'DateActionDoneEnd' => 'Real lopetuspäivämäärää',
		'DateActionStart' => 'Aloituspäivämäärä',
		'DateActionEnd' => 'Lopetuspäivä',
		'AgendaUrlOptions1' => 'Voit myös lisätä seuraavat parametrit suodattaa output:',
		'AgendaUrlOptions2' => '<b>login=<b>login= %s</b> rajoittaa tuotannon toimiin luotu, vaikuttaa tavalla tai tehdä <b>käyttäjä %s.</b>',
		'AgendaUrlOptions3' => '<b>logina=<b>logina= %s</b> rajoittaa tuotannon toimiin luotu <b>käyttäjän %s.</b>',
		'AgendaUrlOptions4' => '<b>logint=<b>logint= %s</b> rajoittaa tuotannon toimet vaikuttavat <b>käyttäjän %s.</b>',
		'AgendaUrlOptions5' => '<b>logind=<b>sisäänkirj= %s</b> rajoittaa tuotannon toimet tehdään <b>käyttäjän %s.</b>',
		'AgendaShowBirthdayEvents' => 'Näytä syntymäpäivä yhteyksiä',
		'AgendaHideBirthdayEvents' => 'Piilota syntymäpäivä yhteyksiä',
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
		'ExportCal' => 'Vie kalenteri',
		'ExtSites' => 'Tuo ulkoinen kalenterit',
		'ExtSitesEnableThisTool' => 'Näytä ulkoinen kalenterit otetaan asialistalle',
		'ExtSitesNbOfAgenda' => 'Määrä kalenterit',
		'AgendaExtNb' => 'Kalenteri nb %s',
		'ExtSiteUrlAgenda' => 'URL päästä. ICal-tiedostona',
		'ExtSiteNoLabel' => 'Ei kuvausta'
);
?>