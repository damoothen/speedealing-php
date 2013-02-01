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

$workflow = array(
		'CHARSET' => 'UTF-8',
		'WorkflowSetup' => 'İş Akışı modülü kurulumu',
		'WorkflowDesc' => 'Bu modül, uygulama içindeki otomatik eylemlerim davranışını değiştirmek için tasarlanmıştır. Varsayılan olarak, iş akışı açıktır (siz istediğiniz şeyi sırayla yaparsınız). İlginç bulduğunuz otomatik eylemleri etkinleştirebilirsiniz.',
		'ThereIsNoWorkflowToModify' => 'Etkinleştirdiğiniz bu modül için değişitirilecek iş akışı yoktur.',
		'descWORKFLOW_PROPAL_AUTOCREATE_ORDER' => 'Ticari bir teklif imzalandıktan sonra otomatik olarak bir müşteri siparişi oluştur',
		'descWORKFLOW_PROPAL_AUTOCREATE_INVOICE' => 'Ticari bir teklif imzalandıktan sonra otomatik olarak bir müşteri faturası oluştur',
		'descWORKFLOW_CONTRACT_AUTOCREATE_INVOICE' => 'Bir sözleşme doğrulandıktan sonra otomatik olarak bir müşteri faturası oluştur',
		'descWORKFLOW_ORDER_AUTOCREATE_INVOICE' => 'Bir müşteri siparişi kapatıldıktan sonra otomatik olarak bir müşteri faturası oluştur',
		'descWORKFLOW_ORDER_CLASSIFY_BILLED_PROPAL' => 'Classify linked source proposal to billed when customer order is set to paid',
		'descWORKFLOW_INVOICE_CLASSIFY_BILLED_ORDER' => 'Classify linked source customer order to billed when customer invoice is set to paid'
);
?>