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
		'BoxLastRssInfos' => 'Rss информация',
		'BoxLastProducts' => 'Последно %s продукти / услуги',
		'BoxLastProductsInContract' => 'Последно %s договорените продукти / услуги',
		'BoxLastSupplierBills' => 'Последно доставчика фактури',
		'BoxLastCustomerBills' => 'Последно клиента фактури',
		'BoxOldestUnpaidCustomerBills' => 'Старите неплатен клиента фактури',
		'BoxOldestUnpaidSupplierBills' => 'Старите неплатен доставчика фактури',
		'BoxLastProposals' => 'Последно търговски предложения',
		'BoxLastProspects' => 'Последна промяна перспективи',
		'BoxLastCustomers' => 'Последна промяна клиенти',
		'BoxLastSuppliers' => 'Последна промяна доставчици',
		'BoxLastCustomerOrders' => 'Последни поръчки на клиентите',
		'BoxLastBooks' => 'Последни книги',
		'BoxLastActions' => 'Последните действия',
		'BoxLastContracts' => 'Последно договори',
		'BoxLastContacts' => 'Последно контакти / адреси',
		'BoxLastMembers' => 'Последни членове',
		'BoxCurrentAccounts' => 'Текущите сметки баланс',
		'BoxSalesTurnover' => 'Продажби оборот',
		'BoxTotalUnpaidCustomerBills' => 'Фактури за общата сума на неплатените клиента',
		'BoxTotalUnpaidSuppliersBills' => 'Общо неплатени доставчика фактури',
		'BoxTitleLastBooks' => 'Последните %s записани книги',
		'BoxTitleNbOfCustomers' => 'Брой клиенти',
		'BoxTitleLastRssInfos' => 'Последно %s новини от %s',
		'BoxTitleLastProducts' => 'Последно %s модифицирани продукти / услуги',
		'BoxTitleLastCustomerOrders' => 'Последно %s промяна на поръчки от клиенти',
		'BoxTitleLastSuppliers' => 'Последно %s записва доставчици',
		'BoxTitleLastCustomers' => 'Последните %s записани клиенти',
		'BoxTitleLastModifiedSuppliers' => 'Последно %s промяна доставчици',
		'BoxTitleLastModifiedCustomers' => 'Последните %s модифицирани клиенти',
		'BoxTitleLastCustomersOrProspects' => 'Последните %s модифицирани клиенти или потенциални',
		'BoxTitleLastPropals' => 'Последните %s записани предложения',
		'BoxTitleLastCustomerBills' => 'Последно %s клиента фактури',
		'BoxTitleLastSupplierBills' => 'Последно %s доставчика фактури',
		'BoxTitleLastProspects' => 'Последно %s записва перспективи',
		'BoxTitleLastModifiedProspects' => 'Последно %s промяна на перспективите',
		'BoxTitleLastProductsInContract' => 'Последно продукти %s / услуги в договора',
		'BoxTitleLastModifiedMembers' => 'Последните %s модифицирани членове',
		'BoxTitleOldestUnpaidCustomerBills' => 'Старите %s неплатен клиента фактури',
		'BoxTitleOldestUnpaidSupplierBills' => 'Неплатен старите %s доставчика фактури',
		'BoxTitleCurrentAccounts' => 'Текуща сметка на баланси',
		'BoxTitleSalesTurnover' => 'Продажби оборот',
		'BoxTitleTotalUnpaidCustomerBills' => 'Неплатен клиента фактури',
		'BoxTitleTotalUnpaidSuppliersBills' => 'Неплатен доставчика фактури',
		'BoxTitleLastModifiedContacts' => 'Последно %s модифицирани контакти / адреси',
		'BoxMyLastBookmarks' => 'Последните ми отметки %s',
		'BoxOldestExpiredServices' => 'Най-старият действащ изтекъл услуги',
		'BoxLastExpiredServices' => 'Последни %s най-старите контакти с активни с изтекъл срок на годност услуги',
		'BoxTitleLastActionsToDo' => 'Последно действия %s за вършене',
		'BoxTitleLastContracts' => 'Последните договори %s',
		'BoxTitleLastModifiedDonations' => 'Последно %s промяна дарения',
		'BoxTitleLastModifiedExpenses' => 'Последно %s промяна разходи',
		'BoxGlobalActivity' => 'Активност в световен мащаб (фактури, предложения, заповеди)',
		'FailedToRefreshDataInfoNotUpToDate' => 'Неуспешно RSS поток на опресняване. Последно успешно опресняване дата: %s',
		'LastRefreshDate' => 'Последна промяна дата',
		'NoRecordedBookmarks' => 'Няма отметки определени.',
		'ClickToAdd' => 'Щракнете тук, за да добавите.',
		'NoRecordedCustomers' => 'Няма регистрирани клиенти',
		'NoRecordedContacts' => 'Не са познати контакти',
		'NoActionsToDo' => 'Няма действия за вършене',
		'NoRecordedOrders' => 'Няма регистрирани клиенти поръчки',
		'NoRecordedProposals' => 'Не са записани предложения',
		'NoRecordedInvoices' => 'Няма регистрирани клиенти фактури',
		'NoUnpaidCustomerBills' => 'Няма непогасени клиента фактури',
		'NoRecordedSupplierInvoices' => 'Няма регистрирани доставчика фактури',
		'NoUnpaidSupplierBills' => 'Няма непогасени доставчика фактури',
		'NoModifiedSupplierBills' => 'Няма регистрирани доставчика фактури',
		'NoRecordedProducts' => 'Няма регистрирани продукти / услуги',
		'NoRecordedProspects' => 'Няма регистрирани перспективи',
		'NoContractedProducts' => 'Няма продукти / услуги, договорени',
		'NoRecordedContracts' => 'Няма регистрирани договори',
		'BoxLatestSupplierOrders' => 'Последни поръчки доставчика',
		'BoxTitleLatestSupplierOrders' => '%s новите поръчки доставчика',
		'NoSupplierOrder' => 'Не са познати доставчик за',
);
?>