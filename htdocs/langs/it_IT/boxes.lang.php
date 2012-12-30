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
		'BoxLastRssInfos' => 'Informazioni RSS',
		'BoxLastProducts' => 'Ultimi prodotti/servizi',
		'BoxProductsAlertStock' => 'Products in stock alert',
		'BoxLastProductsInContract' => 'Ultimi prodotti/servizi contrattati',
		'BoxLastSupplierBills' => 'Ultime fatture fornitore',
		'BoxLastCustomerBills' => 'Ultime fatture attive',
		'BoxOldestUnpaidCustomerBills' => 'Fatture attive non pagate più vecchie',
		'BoxOldestUnpaidSupplierBills' => 'Fatture fornitori non pagate più vecchie',
		'BoxLastProposals' => 'Ultime proposte commerciali',
		'BoxLastProspects' => 'Ultimi potenziali clienti',
		'BoxLastCustomers' => 'Ultimi clienti',
		'BoxLastSuppliers' => 'Ultimi fornitori',
		'BoxLastCustomerOrders' => 'Ultimi ordini dei clienti',
		'BoxLastBooks' => 'Ultimi libri',
		'BoxLastActions' => 'Ultime azioni',
		'BoxLastContracts' => 'Ultimi contratti',
		'BoxLastContacts' => 'Ultimi contatti/indirizzi',
		'BoxLastMembers' => 'Ultimi membri',
		'BoxCurrentAccounts' => 'Saldo conti correnti',
		'BoxSalesTurnover' => 'Fatturato',
		'BoxTotalUnpaidCustomerBills' => 'Totale fatture attive non pagate',
		'BoxTotalUnpaidSuppliersBills' => 'Totale fatture fornitore non pagate',
		'BoxTitleLastBooks' => 'Ultimi %s libri registrati',
		'BoxTitleNbOfCustomers' => 'Numero clienti',
		'BoxTitleLastRssInfos' => 'Ultime %s notizie da %s',
		'BoxTitleLastProducts' => 'Ultimi %s prodotti/servizi modificati',
		'BoxTitleProductsAlertStock' => 'Products in stock alert',
		'BoxTitleLastCustomerOrders' => 'Ultimi %s ordini dei clienti modificati',
		'BoxTitleLastSuppliers' => 'Ultimi %s fornitori registrati',
		'BoxTitleLastCustomers' => 'Ultimi %s clienti registrati',
		'BoxTitleLastModifiedSuppliers' => 'Ultimi %s fornitori modificati',
		'BoxTitleLastModifiedCustomers' => 'Ultima %s clienti modificati',
		'BoxTitleLastCustomersOrProspects' => 'Ultimi %s clienti o potenziali clienti registrati',
		'BoxTitleLastPropals' => 'Ultime %s proposte registrate',
		'BoxTitleLastCustomerBills' => 'Ultime %s fatture attive',
		'BoxTitleLastSupplierBills' => 'Ultime %s fatture fornitori',
		'BoxTitleLastProspects' => 'Ultimi %s potenziali clienti registrati',
		'BoxTitleLastModifiedProspects' => 'Ultimi %s potenziali clienti modificati',
		'BoxTitleLastProductsInContract' => 'Ultimi %s prodotti/servizi a contratto',
		'BoxTitleLastModifiedMembers' => 'Ultimi %s membri modificati',
		'BoxTitleOldestUnpaidCustomerBills' => 'Le %s fatture attive non pagate più vecchie',
		'BoxTitleOldestUnpaidSupplierBills' => 'Le %s fatture fornitori non pagate più vecchie',
		'BoxTitleCurrentAccounts' => 'Saldo conti correnti',
		'BoxTitleSalesTurnover' => 'Fatturato',
		'BoxTitleTotalUnpaidCustomerBills' => 'Fatture attive non pagate',
		'BoxTitleTotalUnpaidSuppliersBills' => 'Fatture fornitore non pagate',
		'BoxTitleLastModifiedContacts' => 'Ultimi %s contatti/indirizzi registrati',
		'BoxMyLastBookmarks' => 'Ultimi %s segnalibri',
		'BoxOldestExpiredServices' => 'Servizi scaduti attivi più vecchi',
		'BoxLastExpiredServices' => 'Ultimi %s più vecchi contatti con servizi scaduti attivi',
		'BoxTitleLastActionsToDo' => 'Ultime %s azioni da fare',
		'BoxTitleLastContracts' => 'Ultimi %s contratti',
		'BoxTitleLastModifiedDonations' => 'Ultime %s donazioni modificate',
		'BoxTitleLastModifiedExpenses' => 'Ultime %s spese modificate',
		'BoxGlobalActivity' => 'Global activity (invoices, proposals, orders)',
		'FailedToRefreshDataInfoNotUpToDate' => 'Impossibile aggiornare il feed RSS. Ultimo aggiornamento riuscito: %s',
		'LastRefreshDate' => 'Data dell\'ultimo aggiornamento',
		'NoRecordedBookmarks' => 'Nessun segnalibro salvato. Clicca <a href="%s"> qui </a> per aggiungere nuovi segnalibri.',
		'ClickToAdd' => 'Clicca qui per aggiungere',
		'NoRecordedCustomers' => 'Nessun cliente registrato',
		'NoRecordedContacts' => 'Nessun contatto registrato',
		'NoActionsToDo' => 'Nessuna azione da fare',
		'NoRecordedOrders' => 'Nessun ordine cliente registrato',
		'NoRecordedProposals' => 'Nessuna proposta registrata',
		'NoRecordedInvoices' => 'Nessuna fattura attiva registrata',
		'NoUnpaidCustomerBills' => 'Nessuna fattura attiva non pagata',
		'NoRecordedSupplierInvoices' => 'Nessuna fattura fornitore registrata',
		'NoUnpaidSupplierBills' => 'Nessuna fattura fornitore non pagata',
		'NoModifiedSupplierBills' => 'Nessuna fattura fornitore registrata',
		'NoRecordedProducts' => 'Nessun prodotto/servizio registrato',
		'NoRecordedProspects' => 'Nessun potenziale cliente registrato',
		'NoContractedProducts' => 'Nessun prodotto/servizio a contratto',
		'NoRecordedContracts' => 'Nessun contratto registrato',
		// Latest supplier orders
		'BoxLatestSupplierOrders' => 'Latest supplier orders',
		'BoxTitleLatestSupplierOrders' => '%s latest supplier orders',
		'NoSupplierOrder' => 'No recorded supplier order'
);
?>