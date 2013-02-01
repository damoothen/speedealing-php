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

$boxes = array(
		'CHARSET' => 'UTF-8',
		'BoxLastRssInfos' => 'RSS info',
		'BoxLastProducts' => 'Last %s toodete / teenuste',
		'BoxProductsAlertStock' => 'Products in stock alert',
		'BoxLastProductsInContract' => 'Last %s tellitud tooteid / teenuseid',
		'BoxLastSupplierBills' => 'Last tarnija arved',
		'BoxLastCustomerBills' => 'Last kliendi arved',
		'BoxOldestUnpaidCustomerBills' => 'Vanemad tasumata kliendi arved',
		'BoxOldestUnpaidSupplierBills' => 'Vanemad tasumata tarnija arved',
		'BoxLastProposals' => 'Last äri ettepanekud',
		'BoxLastProspects' => 'Viimati muudetud väljavaateid',
		'BoxLastCustomers' => 'Viimati muudetud klientidele',
		'BoxLastSuppliers' => 'Viimati muudetud tarnijate',
		'BoxLastCustomerOrders' => 'Last klientide tellimused',
		'BoxLastBooks' => 'Viimased raamatud',
		'BoxLastActions' => 'Viimased tegevused',
		'BoxLastContracts' => 'Viimased lepingud',
		'BoxLastContacts' => 'Last kontaktid / aadressid',
		'BoxLastMembers' => 'Last liikmed',
		'BoxCurrentAccounts' => 'Arvelduskontode saldo',
		'BoxSalesTurnover' => 'Müügikäive',
		'BoxTotalUnpaidCustomerBills' => 'Kokku tasumata kliendi arved',
		'BoxTotalUnpaidSuppliersBills' => 'Kokku tasumata tarnija arved',
		'BoxTitleLastBooks' => 'Last %s salvestatud raamatuid',
		'BoxTitleNbOfCustomers' => 'Klientide arv',
		'BoxTitleLastRssInfos' => 'Last %s uudiseid %s',
		'BoxTitleLastProducts' => 'Last %s muundatud toodete / teenuste',
		'BoxTitleProductsAlertStock' => 'Products in stock alert',
		'BoxTitleLastCustomerOrders' => 'Last %s muuta klientide tellimused',
		'BoxTitleLastSuppliers' => 'Last %s registreerida tarnijate',
		'BoxTitleLastCustomers' => 'Last %s registreerida klientide',
		'BoxTitleLastModifiedSuppliers' => 'Last %s muutmine tarnijate',
		'BoxTitleLastModifiedCustomers' => 'Last %s modifitseeritud kliendid',
		'BoxTitleLastCustomersOrProspects' => 'Last %s muuta klientide või väljavaateid',
		'BoxTitleLastPropals' => 'Last %s salvestatud ettepanekuid',
		'BoxTitleLastCustomerBills' => 'Last %s kliendi arved',
		'BoxTitleLastSupplierBills' => 'Last %s tarnija arved',
		'BoxTitleLastProspects' => 'Last %s salvestatud väljavaated',
		'BoxTitleLastModifiedProspects' => 'Last %s muutmine väljavaateid',
		'BoxTitleLastProductsInContract' => 'Last %s toodete / teenuste leping',
		'BoxTitleLastModifiedMembers' => 'Last %s muutmine liikmed',
		'BoxTitleOldestUnpaidCustomerBills' => 'Vanemad %s tasumata kliendi arved',
		'BoxTitleOldestUnpaidSupplierBills' => 'Vanemad %s tasumata tarnija arved',
		'BoxTitleCurrentAccounts' => 'Praegune konto saldode',
		'BoxTitleSalesTurnover' => 'Müügikäive',
		'BoxTitleTotalUnpaidCustomerBills' => 'Tasumata kliendi arved',
		'BoxTitleTotalUnpaidSuppliersBills' => 'Tasumata tarnija arved',
		'BoxTitleLastModifiedContacts' => 'Last %s muutmine kontaktid / aadressid',
		'BoxMyLastBookmarks' => 'Minu viimane %s järjehoidjad',
		'BoxOldestExpiredServices' => 'Vanemad aktiivne aegunud teenused',
		'BoxLastExpiredServices' => 'Last %s vanim kontaktid aktiivne aegunud teenused',
		'BoxTitleLastActionsToDo' => 'Last %s meetmeid teha',
		'BoxTitleLastContracts' => 'Last %s lepingud',
		'BoxTitleLastModifiedDonations' => 'Last %s muutmine annetusi',
		'BoxTitleLastModifiedExpenses' => 'Last %s muutmine kulud',
		'BoxGlobalActivity' => 'Global activity (invoices, proposals, orders)',
		'FailedToRefreshDataInfoNotUpToDate' => 'Suutnud refresh RSS voog. Last eduka refresh kuupäev: %s',
		'LastRefreshDate' => 'Last refresh kuupäev',
		'NoRecordedBookmarks' => 'Ei järjehoidjaid määratletud.',
		'ClickToAdd' => 'Vajuta siia lisada.',
		'NoRecordedCustomers' => 'Ei salvestatud klientidele',
		'NoRecordedContacts' => 'Ei salvestatud kontaktide',
		'NoActionsToDo' => 'Ei tegevusi teha',
		'NoRecordedOrders' => 'Ei salvestatud kliendi korralduste',
		'NoRecordedProposals' => 'Ei salvestatud ettepanekuid',
		'NoRecordedInvoices' => 'Ei salvestatud kliendi arved',
		'NoUnpaidCustomerBills' => 'Ei tasumata kliendi arved',
		'NoRecordedSupplierInvoices' => 'Ei salvestatud tarnija arved',
		'NoUnpaidSupplierBills' => 'Ei tasumata tarnija arved',
		'NoModifiedSupplierBills' => 'Ei salvestatud tarnija arved',
		'NoRecordedProducts' => 'Ei salvestatud toodete / teenuste',
		'NoRecordedProspects' => 'Ei salvestatud väljavaated',
		'NoContractedProducts' => 'Ei ole tooteid / teenuseid tellitakse',
		'NoRecordedContracts' => 'Ei salvestatud lepingud',
		// Latest supplier orders
		'BoxLatestSupplierOrders' => 'Latest supplier orders',
		'BoxTitleLatestSupplierOrders' => '%s latest supplier orders',
		'NoSupplierOrder' => 'No recorded supplier order'
);
?>