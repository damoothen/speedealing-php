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
		'BoxLastRssInfos' => 'Rss informasjon',
		'BoxLastProducts' => 'Siste produkter/tjenester',
		'BoxProductsAlertStock' => 'Products in stock alert',
		'BoxLastProductsInContract' => 'Siste kontraktsinngåtte produkter/tjenester',
		'BoxLastSupplierBills' => 'Siste leverandørfakturaer',
		'BoxLastCustomerBills' => 'Siste kundefakturaer',
		'BoxOldestUnpaidCustomerBills' => 'Eldste ubetalte kundefakturaer',
		'BoxOldestUnpaidSupplierBills' => 'Eldste ubetalte leverandørfakturaer',
		'BoxLastProposals' => 'Siste tilbuder',
		'BoxLastProspects' => 'Siste prospekter',
		'BoxLastCustomers' => 'Siste kunder',
		'BoxLastSuppliers' => 'Siste leverandører',
		'BoxLastCustomerOrders' => 'Siste kundeordre',
		'BoxLastBooks' => 'Siste bøker',
		'BoxLastActions' => 'Siste handlinger',
		'BoxLastContracts' => 'Siste kontrakter',
		'BoxLastContacts' => 'Siste kontakter / adresser',
		'BoxLastMembers' => 'Siste medlemmer',
		'BoxCurrentAccounts' => 'Gjeldende kontosaldo',
		'BoxSalesTurnover' => 'Omsetning',
		'BoxTotalUnpaidCustomerBills' => 'Totalt utestående kunder',
		'BoxTotalUnpaidSuppliersBills' => 'Totalt utestående leverandører',
		'BoxTitleLastBooks' => 'Siste %s registrerte bøker',
		'BoxTitleNbOfCustomers' => 'Antall kunder',
		'BoxTitleLastRssInfos' => 'Siste %s nyheter fra %s',
		'BoxTitleLastProducts' => 'Siste %s endrede produkter/tjenester',
		'BoxTitleProductsAlertStock' => 'Products in stock alert',
		'BoxTitleLastCustomerOrders' => 'Siste %s endrede kundeordre',
		'BoxTitleLastSuppliers' => 'Siste %s registrerte leverandører',
		'BoxTitleLastCustomers' => 'Siste %s registrerte kunder',
		'BoxTitleLastModifiedSuppliers' => 'Sist endret %s leverandører',
		'BoxTitleLastModifiedCustomers' => 'Sist endret %s kunder',
		'BoxTitleLastCustomersOrProspects' => 'Siste %s registrerte kunder eller prospekter',
		'BoxTitleLastPropals' => 'Siste %s registrerte tilbud',
		'BoxTitleLastCustomerBills' => 'Siste %s kundefakturaer',
		'BoxTitleLastSupplierBills' => 'Siste %s leverandørfakturaer',
		'BoxTitleLastProspects' => 'Siste %s registrerte prospekter',
		'BoxTitleLastModifiedProspects' => 'Sist %s endret utsiktene',
		'BoxTitleLastProductsInContract' => 'Siste %s produkter/tjenerster i kontraketer',
		'BoxTitleLastModifiedMembers' => 'Siste %s endret medlemmer',
		'BoxTitleOldestUnpaidCustomerBills' => 'Eldste %s ubetalte kundefakturaer',
		'BoxTitleOldestUnpaidSupplierBills' => 'Eldste %s ubetalte leverandørfakturaer',
		'BoxTitleCurrentAccounts' => 'Gjeldende kontosaldo',
		'BoxTitleSalesTurnover' => 'Omsetning',
		'BoxTitleTotalUnpaidCustomerBills' => 'Ubetalte kundefakturaer',
		'BoxTitleTotalUnpaidSuppliersBills' => 'Ubetalte leverandørfakturaer',
		'BoxTitleLastModifiedContacts' => 'Siste %s endret kontakter / adresser',
		'BoxMyLastBookmarks' => 'Mine siste %s bokmerker',
		'BoxOldestExpiredServices' => 'Eldste aktive utløpte tjenester',
		'BoxLastExpiredServices' => 'Siste %s eldste kontakter med aktive utløpte tjenester',
		'BoxTitleLastActionsToDo' => 'Siste %s åpne handlinger',
		'BoxTitleLastContracts' => 'Siste %s kontrakter',
		'BoxTitleLastModifiedDonations' => 'Siste %s endret donasjoner',
		'BoxTitleLastModifiedExpenses' => 'Siste %s endret utgifter',
		'BoxGlobalActivity' => 'Global activity (invoices, proposals, orders)',
		'FailedToRefreshDataInfoNotUpToDate' => 'Klarte ikke å oppdatere RSS-strøm. Siste vellykkede oppdatering: %s',
		'LastRefreshDate' => 'Siste oppdatering dato',
		'NoRecordedBookmarks' => 'Ingen bokmerker definert. Trykk <a href="%s">her</a> for å legge til bokmerker.',
		'ClickToAdd' => 'Klikk her for å legge til.',
		'NoRecordedCustomers' => 'Ingen registrerte kunder',
		'NoRecordedContacts' => 'Ingen registrerte kontakter',
		'NoActionsToDo' => 'Ingen åpne handlinger',
		'NoRecordedOrders' => 'Ingen registrerte kunderordre',
		'NoRecordedProposals' => 'Ingen registrerte tilbud',
		'NoRecordedInvoices' => 'Ingen registrerte kundefakturaer',
		'NoUnpaidCustomerBills' => 'Ingen ubetalte kundefakturaer',
		'NoRecordedSupplierInvoices' => 'Ingen registrte leverandørfakturaer',
		'NoUnpaidSupplierBills' => 'Ingen ubetalte leverandørfakturaer',
		'NoModifiedSupplierBills' => 'Ingen registrert leverandørens faktura',
		'NoRecordedProducts' => 'Ingen registrert produkter / tjenester',
		'NoRecordedProspects' => 'Ingen registrert utsikter',
		'NoContractedProducts' => 'Ingen produkter / tjenester innleide',
		'NoRecordedContracts' => 'Ingen registrert kontrakter',
		// Latest supplier orders
		'BoxLatestSupplierOrders' => 'Latest supplier orders',
		'BoxTitleLatestSupplierOrders' => '%s latest supplier orders',
		'NoSupplierOrder' => 'No recorded supplier order'
);
?>