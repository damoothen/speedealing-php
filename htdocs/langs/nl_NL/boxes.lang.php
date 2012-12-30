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
		'BoxLastRssInfos' => 'RSS informatie',
		'BoxLastProducts' => 'Laatste %s producten / diensten',
		'BoxProductsAlertStock' => 'Products in stock alert',
		'BoxLastProductsInContract' => 'Laatste %s gecontracteerde producten / diensten',
		'BoxLastSupplierBills' => 'Laatste leveranciersfacturen',
		'BoxLastCustomerBills' => 'Laatste afnemersfacturen',
		'BoxOldestUnpaidCustomerBills' => 'Oudste onbetaalde afnemersfacturen',
		'BoxOldestUnpaidSupplierBills' => 'Oudste onbetaalde leveranciersfacturen',
		'BoxLastProposals' => 'Laatste offertes',
		'BoxLastProspects' => 'Laatste prospecten',
		'BoxLastCustomers' => 'Laatste afnemers',
		'BoxLastSuppliers' => 'Laatste leveranciers',
		'BoxLastCustomerOrders' => 'Laatste afnemersopdrachten',
		'BoxLastBooks' => 'Laatste boekingen',
		'BoxLastActions' => 'Laatste acties',
		'BoxLastContracts' => 'Laatste contracten',
		'BoxLastContacts' => 'Laatste contacten / adressen',
		'BoxLastMembers' => 'Laatste leden',
		'BoxCurrentAccounts' => 'Saldo op bankrekeningen',
		'BoxSalesTurnover' => 'Omzet',
		'BoxTotalUnpaidCustomerBills' => 'Totaal aantal onbetaalde afnemersfacturen',
		'BoxTotalUnpaidSuppliersBills' => 'Totaal aantal onbetaalde leveranciersfacturen',
		'BoxTitleLastBooks' => 'Laatste %s geregistreerde boekingen',
		'BoxTitleNbOfCustomers' => 'Aantal afnemers',
		'BoxTitleLastRssInfos' => 'Laatste %s nieuws uit %s',
		'BoxTitleLastProducts' => 'Laatste %s gewijzigde producten / diensten',
		'BoxTitleProductsAlertStock' => 'Products in stock alert',
		'BoxTitleLastCustomerOrders' => 'Laatste %s bewerkte afnemersopdrachten',
		'BoxTitleLastSuppliers' => 'Laatste %s geregistreerde leveranciers',
		'BoxTitleLastCustomers' => 'Laatste %s geregistreerde afnemers',
		'BoxTitleLastModifiedSuppliers' => 'Laatste %s bewerkte leveranciers',
		'BoxTitleLastModifiedCustomers' => 'Laatste %s bewerkte afnemers',
		'BoxTitleLastCustomersOrProspects' => 'Laatste %s geregistreerde afnemers of prospecten',
		'BoxTitleLastPropals' => 'Laatste %s geregistreerd offertes',
		'BoxTitleLastCustomerBills' => 'Laatste %s afnemersfacturen',
		'BoxTitleLastSupplierBills' => 'Laatste %s leveranciersfacturen',
		'BoxTitleLastProspects' => 'Laatste %s geregistreerde prospecten',
		'BoxTitleLastModifiedProspects' => 'Laatste %s bewerkte prospecten',
		'BoxTitleLastProductsInContract' => 'Laatste %s producten / diensten in een contract',
		'BoxTitleLastModifiedMembers' => 'Laatst gewijzigd %s leden',
		'BoxTitleOldestUnpaidCustomerBills' => 'Oudste %s onbetaalde afnemersfacturen',
		'BoxTitleOldestUnpaidSupplierBills' => 'Oudste %s onbetaalde leveranciersfacturen',
		'BoxTitleCurrentAccounts' => 'Saldo van de huidige rekening',
		'BoxTitleSalesTurnover' => 'Omzet',
		'BoxTitleTotalUnpaidCustomerBills' => 'Onbetaalde afnemersfacturen',
		'BoxTitleTotalUnpaidSuppliersBills' => 'Onbetaalde leveranciersfacturen',
		'BoxTitleLastModifiedContacts' => 'Laatst gewijzigd %s contacten / adressen',
		'BoxMyLastBookmarks' => 'Mijn laatste %s weblinks',
		'BoxOldestExpiredServices' => 'Oudste actief verlopen diensten',
		'BoxLastExpiredServices' => 'Laatste %s oudste contacten met actieve verlopen diensten',
		'BoxTitleLastActionsToDo' => 'Laatste %s acties te doen',
		'BoxTitleLastContracts' => 'Laatste %s contracten',
		'BoxTitleLastModifiedDonations' => 'Laatst gewijzigd %s donaties',
		'BoxTitleLastModifiedExpenses' => 'Laatst gewijzigd %s kosten',
		'BoxGlobalActivity' => 'Global activity (invoices, proposals, orders)',
		'FailedToRefreshDataInfoNotUpToDate' => 'Bijwerken van de RSS feed is mislukt. Laatste succesvolle bijwerkingsdatum: %s',
		'LastRefreshDate' => 'Laatste bijwerkingsdatum',
		'NoRecordedBookmarks' => 'Geen weblinks ingesteld. Klik <a href="%s">weblinks</a> aan om deze toe te voegen.',
		'ClickToAdd' => 'Klik hier om toe te voegen.',
		'NoRecordedCustomers' => 'Geen geregistreerde afnemers',
		'NoRecordedContacts' => 'Geen geregistreerde contacten',
		'NoActionsToDo' => 'Geen acties te doen',
		'NoRecordedOrders' => 'Geen geregistreerde afnemersopdrachten',
		'NoRecordedProposals' => 'Geen geregistreerde offertes',
		'NoRecordedInvoices' => 'Geen geregistreerde afnemersfacturen',
		'NoUnpaidCustomerBills' => 'Geen onbetaalde afnemersfacturen',
		'NoRecordedSupplierInvoices' => 'Geen geregistreerd leverancierfacturen',
		'NoUnpaidSupplierBills' => 'Geen onbetaalde leverancierfacturen',
		'NoModifiedSupplierBills' => 'Geen geregistreerd leveranciersfacturen',
		'NoRecordedProducts' => 'Geen geregistreerde producten / diensten',
		'NoRecordedProspects' => 'Geen geregistreerde prospecten',
		'NoContractedProducts' => 'Geen gecontracteerde producten / diensten',
		'NoRecordedContracts' => 'Geen geregistreerde contracten',
		// Latest supplier orders
		'BoxLatestSupplierOrders' => 'Latest supplier orders',
		'BoxTitleLatestSupplierOrders' => '%s latest supplier orders',
		'NoSupplierOrder' => 'No recorded supplier order'
);
?>