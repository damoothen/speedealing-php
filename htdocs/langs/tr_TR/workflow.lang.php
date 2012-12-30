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