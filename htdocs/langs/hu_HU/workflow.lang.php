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
		'WorkflowSetup' => 'Workflow modul beállítása',
		'WorkflowDesc' => 'Ez a modul terveztem, hogy módosítsa a viselkedését automatikus intézkedéseket alkalmazhassák. Alapértelmezésben a munkafolyamatok nyitva van (amit csinál, hogy ezt akarod). Tudod használ automatikus intézkedéseket, hogy érdekes be',
		'ThereIsNoWorkflowToModify' => 'Nincs munkafolyamat akkor lehet módosítani a modul aktiválása.',
		'descWORKFLOW_PROPAL_AUTOCREATE_ORDER' => 'Létrehozása után automatikusan az ügyfelek érdekében a kereskedelmi javaslat aláírt',
		'descWORKFLOW_PROPAL_AUTOCREATE_INVOICE' => 'Hozzon létre egy vásárlói számlát után automatikusan egy kereskedelmi javaslat aláírt',
		'descWORKFLOW_CONTRACT_AUTOCREATE_INVOICE' => 'Hozzon létre egy vásárlói számlát után automatikusan érvényesíti a szerződés',
		'descWORKFLOW_ORDER_AUTOCREATE_INVOICE' => 'Hozzon létre egy vásárlói számlát követően automatikusan az ügyfelek érdekében van zárva',
		'descWORKFLOW_ORDER_CLASSIFY_BILLED_PROPAL' => 'Classify linked source proposal to billed when customer order is set to paid',
		'descWORKFLOW_INVOICE_CLASSIFY_BILLED_ORDER' => 'Classify linked source customer order to billed when customer invoice is set to paid'
);
?>