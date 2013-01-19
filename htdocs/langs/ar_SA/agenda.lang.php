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

$agenda = array(
		'CHARSET' => 'UTF-8',
		'Actions' => 'الإجراءات',
		'ActionsArea' => 'الإجراءات منطقة الأحداث والمهام)',
		'Agenda' => 'جدول الأعمال',
		'Agendas' => 'جداول الأعمال',
		'Calendar' => 'التقويم',
		'Calendars' => 'التقاويم',
		'LocalAgenda' => 'تقويم محلي',
		'AffectedTo' => 'إلى المتضررين',
		'DoneBy' => 'الذي قام به',
		'Event' => 'Event',
		'Events' => 'الأحداث',
		'MyEvents' => 'بلدي أحداث',
		'OtherEvents' => 'غيرها من الأحداث',
		'ListOfActions' => 'قائمة الأحداث',
		'Location' => 'موقع',
		'EventOnFullDay' => 'حدث في يوم كامل',
		'SearchAnAction' => 'البحث عن عمل / المهمة',
		'MenuToDoActions' => 'جميع الأعمال غير مكتملة',
		'MenuDoneActions' => 'أنهت جميع الإجراءات',
		'MenuToDoMyActions' => 'بلدي مكتمل الإجراءات',
		'MenuDoneMyActions' => 'بلدي إنهاء الإجراءات',
		'ListOfEvents' => 'قائمة الأحداث Dolibarr',
		'ActionsAskedBy' => 'الإجراءات التي سجلتها',
		'ActionsToDoBy' => 'الإجراءات التي أثرت على',
		'ActionsDoneBy' => 'الإجراءات التي قامت به',
		'AllMyActions' => 'كل أفعالي / المهام',
		'AllActions' => 'Toutes ليه الإجراءات / المهام',
		'ViewList' => 'وبالنظر إلى قائمة',
		'ViewCal' => 'وبالنظر إلى الجدول الزمني',
		'ViewDay' => 'يوم رأي',
		'ViewWeek' => 'أسبوع رأي',
		'ViewWithPredefinedFilters' => 'وترى مسبقا مع الفلاتر',
		'AutoActions' => 'التلقائي ملء جدول الأعمال',
		'AgendaAutoActionDesc' => 'هنا تعريف الأحداث التي تريد Dolibarr لخلق عمل تلقائيا في جدول الأعمال. إذا لم فحصها (افتراضي) ، إلا دليل الإجراءات التي ستدرج في جدول الأعمال.',
		'AgendaSetupOtherDesc' => 'وتسمح هذه الصفحة لتكوين البارامترات الأخرى من جدول الأعمال وحدة.',
		'AgendaExtSitesDesc' => 'هذه الصفحة تسمح لاعلان مصادر خارجية من التقويمات لمعرفة المناسبات الخاصة بهم في جدول أعمال Dolibarr.',
		'ActionsEvents' => 'الأحداث التي ستخلق Dolibarr عمل تلقائيا في جدول الأعمال',
		'PropalValidatedInSpeedealing' => 'Proposal %s validated',
		'InvoiceValidatedInSpeedealing' => 'Invoice %s validated',
		'InvoiceBackToDraftInSpeedealing' => 'Invoice %s go back to draft status',
		'OrderValidatedInSpeedealing' => 'Order %s validated',
		'OrderApprovedInSpeedealing' => 'Order %s approved',
		'OrderBackToDraftInSpeedealing' => 'Order %s go back to draft status',
		'OrderCanceledInSpeedealing' => 'Order %s canceled',
		'InterventionValidatedInSpeedealing' => 'Intervention %s validated',
		'ProposalSentByEMail' => '%s اقتراح التجارية المرسلة عن طريق البريد الالكتروني',
		'OrderSentByEMail' => '%s النظام العميل ارسال البريد الالكتروني',
		'InvoiceSentByEMail' => '%s فاتورة العميل ارسال البريد الالكتروني',
		'SupplierOrderSentByEMail' => '%s النظام مزود ارسال البريد الالكتروني',
		'SupplierInvoiceSentByEMail' => '%s فاتورة المورد إرسالها عن طريق البريد الالكتروني',
		'ShippingSentByEMail' => '%s الشحن إرسالها عن طريق البريد الالكتروني',
		'InterventionSentByEMail' => '%s تدخل إرسالها عن طريق البريد الالكتروني',
		'NewCompanyToSpeedealing' => 'Third party created',
		'DateActionPlannedStart' => 'تاريخ البدء المخطط',
		'DateActionPlannedEnd' => 'المخطط لها تاريخ انتهاء',
		'DateActionDoneStart' => 'البداية الحقيقية لتاريخ',
		'DateActionDoneEnd' => 'نهاية التاريخ الحقيقي',
		'DateActionStart' => 'تاريخ البدء',
		'DateActionEnd' => 'نهاية التاريخ',
		'AgendaUrlOptions1' => 'يمكنك أيضا إضافة المعايير التالية لترشيح الناتج :',
		'AgendaUrlOptions2' => '<b>login=<b>login=%s</b> لتقييد الانتاج لإجراءات التي أوجدتها ، وأثرت على المستخدم الذي قام به أو <b>%s</b>',
		'AgendaUrlOptions3' => '<b>logina=<b>logina=%s</b> لتقييد الانتاج لإجراءات التي أنشأها مستخدم <b>%s</b>',
		'AgendaUrlOptions4' => '<b>logint=<b>logint=%s</b> لتقييد الانتاج لإجراءات المتضررة لمستخدم <b>%s</b>',
		'AgendaUrlOptions5' => '<b>logind=<b>logind=%s</b> لتقييد الانتاج لإجراءات قامت به المستخدم <b>%s</b>',
		'AgendaShowBirthdayEvents' => 'عيد ميلاد تظهر اتصالات',
		'AgendaHideBirthdayEvents' => 'عيد ميلاد إخفاء اتصالات',
		'Activities' => 'Activities',
		'NewActions' => 'News<br>Actions',
		'DoActions' => 'Actions<br>in progress',
		'SumMyActions' => 'Actions done<br>by me this year',
		'SumActions' => 'Actions in total<br>this year',
		'DateEchAction' => 'Deadline',
		'StatusActionTooLate' => 'Action delay',
		'MyTasks' => 'My tasks',
		'MyDelegatedTasks' => 'My delegated tasks',
		'ProdPlanning' => 'Planning of production',
		// External Sites ical
		'ExportCal' => 'تصدير التقويم',
		'ExtSites' => 'استيراد التقويمات الخارجية',
		'ExtSitesEnableThisTool' => 'عرض التقويمات الخارجية في جدول الأعمال',
		'ExtSitesNbOfAgenda' => 'عدد من التقاويم',
		'AgendaExtNb' => 'تقويم ملحوظة %s',
		'ExtSiteUrlAgenda' => 'URL للوصول. كال ملف',
		'ExtSiteNoLabel' => 'لا يوجد وصف'
);
?>