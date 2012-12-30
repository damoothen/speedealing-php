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

$boxes = array(
		'CHARSET' => 'UTF-8',
		'BoxLastRssInfos' => 'Rss tiedot',
		'BoxLastProducts' => 'Uusimmat tuotteet / palvelut',
		'BoxProductsAlertStock' => 'Products in stock alert',
		'BoxLastProductsInContract' => 'Viimeisin sopimus tuotteet / palvelut',
		'BoxLastSupplierBills' => 'Viimeisin toimittajan laskut',
		'BoxLastCustomerBills' => 'Viimeisin asiakkaan laskut',
		'BoxOldestUnpaidCustomerBills' => 'Vanhin palkatonta asiakkaan laskut',
		'BoxOldestUnpaidSupplierBills' => 'Vanhin palkatonta toimittajan laskut',
		'BoxLastProposals' => 'Viimeisin kaupallinen ehdotuksia',
		'BoxLastProspects' => 'Viimeisin näkymät',
		'BoxLastCustomers' => 'Viimeisin asiakkaille',
		'BoxLastSuppliers' => 'Viimeisin toimittajat',
		'BoxLastCustomerOrders' => 'Viimeisin asiakkaan tilaukset',
		'BoxLastBooks' => 'Uusimmat kirjat',
		'BoxLastActions' => 'Viimeisin toimia',
		'BoxLastContracts' => 'Edellinen sopimukset',
		'BoxLastContacts' => 'Viimeksi kontaktit / osoitteet',
		'BoxLastMembers' => 'Viimeksi jäsentä',
		'BoxCurrentAccounts' => 'Sekkitilit tasapaino',
		'BoxSalesTurnover' => 'Myynnin liikevaihto',
		'BoxTotalUnpaidCustomerBills' => 'Yhteensä maksamattomia asiakkaan laskut',
		'BoxTotalUnpaidSuppliersBills' => 'Yhteensä maksamattomia toimittajan laskut',
		'BoxTitleLastBooks' => 'Viimeisin %s kirjataan kirjat',
		'BoxTitleNbOfCustomers' => 'Nombre de asiakas',
		'BoxTitleLastRssInfos' => 'Viimeisin %s uutisia %s',
		'BoxTitleLastProducts' => 'Viimeisin %s muunneltuja tuotteita / palveluita',
		'BoxTitleProductsAlertStock' => 'Products in stock alert',
		'BoxTitleLastCustomerOrders' => 'Viimeisin %s muutettu asiakkaan tilaukset',
		'BoxTitleLastSuppliers' => 'Viimeisin %s kirjataan toimittajat',
		'BoxTitleLastCustomers' => 'Viimeisin %s kirjataan asiakkaille',
		'BoxTitleLastModifiedSuppliers' => 'Viimeksi %s muutettu toimittajien',
		'BoxTitleLastModifiedCustomers' => 'Viimeksi %s muutettu asiakkaiden',
		'BoxTitleLastCustomersOrProspects' => 'Viimeisin %s kirjataan asiakkaita tai näkymät',
		'BoxTitleLastPropals' => 'Viimeisin %s kirjataan ehdotuksia',
		'BoxTitleLastCustomerBills' => 'Viimeisin %s asiakkaan laskut',
		'BoxTitleLastSupplierBills' => 'Viimeisin %s toimittajan laskut',
		'BoxTitleLastProspects' => 'Viimeisin %s kirjataan näkymät',
		'BoxTitleLastModifiedProspects' => 'Viimeksi %s muutettu tulevaisuudennäkymät',
		'BoxTitleLastProductsInContract' => 'Edellinen %s tuotteita / palveluita sopimuksen',
		'BoxTitleLastModifiedMembers' => 'Viimeksi %s muutettu jäsentä',
		'BoxTitleOldestUnpaidCustomerBills' => 'Vanhin %s palkatonta asiakkaan laskut',
		'BoxTitleOldestUnpaidSupplierBills' => 'Vanhin %s palkatonta toimittajan laskut',
		'BoxTitleCurrentAccounts' => 'Nykyinen tilin saldot',
		'BoxTitleSalesTurnover' => 'Myynnin liikevaihto',
		'BoxTitleTotalUnpaidCustomerBills' => 'Maksamattomat asiakkaan laskut',
		'BoxTitleTotalUnpaidSuppliersBills' => 'Maksamattomat toimittajan laskut',
		'BoxTitleLastModifiedContacts' => 'Viimeksi %s muutettu kontaktit / osoitteet',
		'BoxMyLastBookmarks' => 'Viimeinen %s kirjanmerkeistä',
		'BoxOldestExpiredServices' => 'Vanhin aktiivinen päättyi palvelut',
		'BoxLastExpiredServices' => 'Viimeksi %s vanhin yhteydet aktiivisten vanhentuneet palvelut',
		'BoxTitleLastActionsToDo' => 'Viimeisin %s toimet eivät',
		'BoxTitleLastContracts' => 'Edellinen %s sopimukset',
		'BoxTitleLastModifiedDonations' => 'Viimeksi %s muutettu lahjoitukset',
		'BoxTitleLastModifiedExpenses' => 'Viimeksi %s muutettu kulut',
		'BoxGlobalActivity' => 'Global activity (invoices, proposals, orders)',
		'FailedToRefreshDataInfoNotUpToDate' => 'Päivitys ei onnistunut RSS muutostilassa. Viimeisin onnistunut virkistystaajuuden päivämäärä: %s',
		'LastRefreshDate' => 'Viimeisin päivitys päivämäärä',
		'NoRecordedBookmarks' => 'No bookmarks defined. Click <a href=Ei kirjanmerkkejä määritelty. Klikkaa <a href="%s">tästä</a> lisätä kirjanmerkkejä.',
		'ClickToAdd' => 'Klikkaa tästä lisätä.',
		'NoRecordedCustomers' => 'Ei kirjata asiakkaiden',
		'NoRecordedContacts' => 'Ei tallennettuja yhteystietoja',
		'NoActionsToDo' => 'Mitään toimenpiteitä tehdä',
		'NoRecordedOrders' => 'N: o kirjataan asiakkaan tilaukset',
		'NoRecordedProposals' => 'Ei kirjata ehdotuksia',
		'NoRecordedInvoices' => 'N: o kirjataan asiakkaan laskut',
		'NoUnpaidCustomerBills' => 'N: o palkatonta asiakkaan laskut',
		'NoRecordedSupplierInvoices' => 'Ei kirjata toimittajan laskut',
		'NoUnpaidSupplierBills' => 'N: o palkatonta toimittajan laskut',
		'NoModifiedSupplierBills' => 'Ei kirjattu toimittajan laskut',
		'NoRecordedProducts' => 'N: o kirjataan tuotteet / palvelut',
		'NoRecordedProspects' => 'N: o kirjataan näkymät',
		'NoContractedProducts' => 'Ei tuotteita / palveluista',
		'NoRecordedContracts' => 'Ei kirjattu sopimuksiin',
		// Latest supplier orders
		'BoxLatestSupplierOrders' => 'Latest supplier orders',
		'BoxTitleLatestSupplierOrders' => '%s latest supplier orders',
		'NoSupplierOrder' => 'No recorded supplier order'
);
?>