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
		'BoxLastRssInfos' => 'RSS情報',
		'BoxLastProducts' => '最後%s製品/サービス',
		'BoxProductsAlertStock' => 'Products in stock alert',
		'BoxLastProductsInContract' => '最後%sは、製品/サービスを契約',
		'BoxLastSupplierBills' => '最後のサプライヤーの請求書',
		'BoxLastCustomerBills' => '最後に、顧客の請求書',
		'BoxOldestUnpaidCustomerBills' => '古い未払いの顧客の請求書',
		'BoxOldestUnpaidSupplierBills' => '古い未払いサプライヤーの請求書',
		'BoxLastProposals' => '最後の商業の提案',
		'BoxLastProspects' => '最終更新の見通し',
		'BoxLastCustomers' => '最終更新日の顧客',
		'BoxLastSuppliers' => '最終更新サプライヤー',
		'BoxLastCustomerOrders' => '最後に、顧客の注文',
		'BoxLastBooks' => '最後の書籍',
		'BoxLastActions' => '最後のアクション',
		'BoxLastContracts' => '最後の契約',
		'BoxLastContacts' => '最後の連絡先/アドレス',
		'BoxLastMembers' => '最後のメンバー',
		'BoxCurrentAccounts' => '当座預金残高',
		'BoxSalesTurnover' => '販売額',
		'BoxTotalUnpaidCustomerBills' => '合計未払いの顧客の請求書',
		'BoxTotalUnpaidSuppliersBills' => '合計未払いの仕入先の請求書',
		'BoxTitleLastBooks' => '最後%s記録された書籍',
		'BoxTitleNbOfCustomers' => 'クライアントの数',
		'BoxTitleLastRssInfos' => '%s %sからの最後のニュース',
		'BoxTitleLastProducts' => '最後%sは、製品/サービスを変更',
		'BoxTitleProductsAlertStock' => 'Products in stock alert',
		'BoxTitleLastCustomerOrders' => '最後%sは、顧客の注文を変更',
		'BoxTitleLastSuppliers' => '最後%sのサプライヤーを記録',
		'BoxTitleLastCustomers' => '最後%s記録された顧客',
		'BoxTitleLastModifiedSuppliers' => '最後%sのサプライヤーを変更',
		'BoxTitleLastModifiedCustomers' => '最後に変更されたお客様の%s',
		'BoxTitleLastCustomersOrProspects' => '最後%s変更顧客や見込み客',
		'BoxTitleLastPropals' => '最後%s記録された提案',
		'BoxTitleLastCustomerBills' => '最後%s顧客の請求書',
		'BoxTitleLastSupplierBills' => '最後%sサプライヤの請求書',
		'BoxTitleLastProspects' => '最後%sは見通しを記録',
		'BoxTitleLastModifiedProspects' => '最後%sは、見通しを修正',
		'BoxTitleLastProductsInContract' => '契約の最後の%s製品/サービス',
		'BoxTitleLastModifiedMembers' => '最後に変更されたメンバー%s',
		'BoxTitleOldestUnpaidCustomerBills' => '古い%s未払いの顧客の請求書',
		'BoxTitleOldestUnpaidSupplierBills' => '古い%s未払いの仕入先の請求書',
		'BoxTitleCurrentAccounts' => '現在のアカウントの残高',
		'BoxTitleSalesTurnover' => '販売額',
		'BoxTitleTotalUnpaidCustomerBills' => '未払いの顧客の請求書',
		'BoxTitleTotalUnpaidSuppliersBills' => '未払いの仕入先の請求書',
		'BoxTitleLastModifiedContacts' => '最後%sは変更された連絡先/アドレス',
		'BoxMyLastBookmarks' => '私の最後の%sブックマーク',
		'BoxOldestExpiredServices' => '最も古いアクティブな期限切れのサービス',
		'BoxLastExpiredServices' => 'アクティブな有効期限が切れたサービスの最後の%s最古の連絡先',
		'BoxTitleLastActionsToDo' => '行うための最後の%sアクション',
		'BoxTitleLastContracts' => '最後%s契約',
		'BoxTitleLastModifiedDonations' => '最後%sは寄付を変更',
		'BoxTitleLastModifiedExpenses' => '最後%sは経費を変更',
		'BoxGlobalActivity' => 'Global activity (invoices, proposals, orders)',
		'FailedToRefreshDataInfoNotUpToDate' => 'リフレッシュRSSフラックスに失敗しました。最後に成功し、リフレッシュした日：%s',
		'LastRefreshDate' => '最後の更新日',
		'NoRecordedBookmarks' => 'ブックマークが定義されていません。',
		'ClickToAdd' => '追加するにはここをクリックしてください。',
		'NoRecordedCustomers' => '記録された顧客がありません',
		'NoRecordedContacts' => '全く記録されたコンタクトません',
		'NoActionsToDo' => 'そうするアクションはありません',
		'NoRecordedOrders' => '無記録された顧客の注文',
		'NoRecordedProposals' => '全く記録された提案はありません',
		'NoRecordedInvoices' => '無記録された顧客の請求書',
		'NoUnpaidCustomerBills' => 'ない未払いの顧客の請求書',
		'NoRecordedSupplierInvoices' => '無記録されたサプライヤーの請求書',
		'NoUnpaidSupplierBills' => 'ない未払いのサプライヤーの請求書',
		'NoModifiedSupplierBills' => '無記録されたサプライヤーの請求書',
		'NoRecordedProducts' => '記録された商品はありません/サービスなし',
		'NoRecordedProspects' => '全く記録された見通しなし',
		'NoContractedProducts' => 'ない製品/サービスは、契約しない',
		'NoRecordedContracts' => '全く記録された契約をしない',
		// Latest supplier orders
		'BoxLatestSupplierOrders' => 'Latest supplier orders',
		'BoxTitleLatestSupplierOrders' => '%s latest supplier orders',
		'NoSupplierOrder' => 'No recorded supplier order'
);
?>