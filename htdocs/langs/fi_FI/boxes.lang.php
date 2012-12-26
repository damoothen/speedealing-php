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

$boxes = array(
		'CHARSET' => 'UTF-8',
		'BoxLastRssInfos' => 'Rss tiedot',
		'BoxLastProducts' => 'Uusimmat tuotteet / palvelut',
		'BoxLastProductsInContract' => 'Viimeisin sopimus tuotteet / palvelut',
		'BoxLastSupplierBills' => 'Viimeisin toimittajan laskut',
		'BoxLastCustomerBills' => 'Viimeisin asiakkaan laskut',
		'BoxLastProposals' => 'Viimeisin kaupallinen ehdotuksia',
		'BoxLastProspects' => 'Viimeisin näkymät',
		'BoxLastCustomers' => 'Viimeisin asiakkaille',
		'BoxLastCustomerOrders' => 'Viimeisin asiakkaan tilaukset',
		'BoxLastSuppliers' => 'Viimeisin toimittajat',
		'BoxLastBooks' => 'Uusimmat kirjat',
		'BoxLastActions' => 'Viimeisin toimia',
		'BoxCurrentAccounts' => 'Sekkitilit tasapaino',
		'BoxSalesTurnover' => 'Myynnin liikevaihto',
		'BoxTitleLastBooks' => 'Viimeisin %s kirjataan kirjat',
		'BoxTitleNbOfCustomers' => 'Nombre de asiakas',
		'BoxTitleLastRssInfos' => 'Viimeisin %s uutisia %s',
		'BoxTitleLastProducts' => 'Viimeisin %s muunneltuja tuotteita / palveluita',
		'BoxTitleLastCustomerOrders' => 'Viimeisin %s muutettu asiakkaan tilaukset',
		'BoxTitleLastSuppliers' => 'Viimeisin %s kirjataan toimittajat',
		'BoxTitleLastCustomers' => 'Viimeisin %s kirjataan asiakkaille',
		'BoxTitleLastCustomersOrProspects' => 'Viimeisin %s kirjataan asiakkaita tai näkymät',
		'BoxTitleLastPropals' => 'Viimeisin %s kirjataan ehdotuksia',
		'BoxTitleLastCustomerBills' => 'Viimeisin %s asiakkaan laskut',
		'BoxTitleLastSupplierBills' => 'Viimeisin %s toimittajan laskut',
		'BoxTitleLastProspects' => 'Viimeisin %s kirjataan näkymät',
		'BoxTitleCurrentAccounts' => 'Nykyinen tilin saldot',
		'BoxTitleSalesTurnover' => 'Myynnin liikevaihto',
		'BoxMyLastBookmarks' => 'Viimeinen %s kirjanmerkeistä',
		'FailedToRefreshDataInfoNotUpToDate' => 'Päivitys ei onnistunut RSS muutostilassa. Viimeisin onnistunut virkistystaajuuden päivämäärä: %s',
		'NoRecordedBookmarks' => 'No bookmarks defined. Click <a href=Ei kirjanmerkkejä määritelty. Klikkaa <a href="%s">tästä</a> lisätä kirjanmerkkejä.',
		'NoRecordedCustomers' => 'Ei kirjata asiakkaiden',
		'BoxTitleLastActionsToDo' => 'Viimeisin %s toimet eivät',
		'NoActionsToDo' => 'Mitään toimenpiteitä tehdä',
		'NoRecordedOrders' => 'N: o kirjataan asiakkaan tilaukset',
		'NoRecordedProposals' => 'Ei kirjata ehdotuksia',
		'NoRecordedInvoices' => 'N: o kirjataan asiakkaan laskut',
		'NoRecordedSupplierInvoices' => 'Ei kirjata toimittajan laskut',
		'LastRefreshDate' => 'Viimeisin päivitys päivämäärä',
		'NoRecordedProducts' => 'N: o kirjataan tuotteet / palvelut',
		'NoRecordedProspects' => 'N: o kirjataan näkymät',
		'NoContractedProducts' => 'Ei tuotteita / palveluista',
		'BoxOldestUnpaidCustomerBills' => 'Vanhin palkatonta asiakkaan laskut',
		'BoxOldestUnpaidSupplierBills' => 'Vanhin palkatonta toimittajan laskut',
		'BoxTotalUnpaidCustomerBills' => 'Yhteensä maksamattomia asiakkaan laskut',
		'BoxTotalUnpaidSuppliersBills' => 'Yhteensä maksamattomia toimittajan laskut',
		'BoxTitleOldestUnpaidCustomerBills' => 'Vanhin %s palkatonta asiakkaan laskut',
		'BoxTitleOldestUnpaidSupplierBills' => 'Vanhin %s palkatonta toimittajan laskut',
		'BoxTitleTotalUnpaidCustomerBills' => 'Maksamattomat asiakkaan laskut',
		'BoxTitleTotalUnpaidSuppliersBills' => 'Maksamattomat toimittajan laskut',
		'NoUnpaidCustomerBills' => 'N: o palkatonta asiakkaan laskut',
		'NoUnpaidSupplierBills' => 'N: o palkatonta toimittajan laskut',
		'BoxLastContracts' => 'Edellinen sopimukset',
		'BoxTitleLastProductsInContract' => 'Edellinen %s tuotteita / palveluita sopimuksen',
		'BoxTitleLastContracts' => 'Edellinen %s sopimukset',
		'NoModifiedSupplierBills' => 'Ei kirjattu toimittajan laskut',
		'NoRecordedContracts' => 'Ei kirjattu sopimuksiin',
		'BoxTitleLastModifiedSuppliers' => 'Viimeksi %s muutettu toimittajien',
		'BoxTitleLastModifiedCustomers' => 'Viimeksi %s muutettu asiakkaiden',
		'BoxTitleLastModifiedProspects' => 'Viimeksi %s muutettu tulevaisuudennäkymät',
		'BoxLastContacts' => 'Viimeksi kontaktit / osoitteet',
		'BoxLastMembers' => 'Viimeksi jäsentä',
		'BoxTitleLastModifiedMembers' => 'Viimeksi %s muutettu jäsentä',
		'BoxTitleLastModifiedContacts' => 'Viimeksi %s muutettu kontaktit / osoitteet',
		'BoxOldestExpiredServices' => 'Vanhin aktiivinen päättyi palvelut',
		'BoxLastExpiredServices' => 'Viimeksi %s vanhin yhteydet aktiivisten vanhentuneet palvelut',
		'BoxTitleLastModifiedDonations' => 'Viimeksi %s muutettu lahjoitukset',
		'BoxTitleLastModifiedExpenses' => 'Viimeksi %s muutettu kulut',
		'ClickToAdd' => 'Klikkaa tästä lisätä.',
		'NoRecordedContacts' => 'Ei tallennettuja yhteystietoja',
);
?>