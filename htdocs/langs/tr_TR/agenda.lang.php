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

$agenda = array(
		'CHARSET' => 'UTF-8',
		'Actions' => 'Eylemler',
		'ActionsArea' => 'Eylem alanı (Olaylar ve görevler)',
		'Agenda' => 'Gündem',
		'Agendas' => 'Gündemler',
		'Calendar' => 'Takvim',
		'Calendars' => 'Takvimler',
		'LocalAgenda' => 'Yerel takvim',
		'AffectedTo' => 'Etkilenen',
		'DoneBy' => 'Tarafından yapıldı',
		'Events' => 'Etkinlikler',
		'MyEvents' => 'Etkinliklerim',
		'OtherEvents' => 'Diğer etkinlikler',
		'ListOfActions' => 'Etkinlikler listesi',
		'Location' => 'Konum',
		'EventOnFullDay' => 'Tam gün etkinliği',
		'SearchAnAction' => 'Bir eylem/görev ara',
		'MenuToDoActions' => 'Tüm tamamlanmamış eylemler',
		'MenuDoneActions' => 'Tüm sonlandırılan eylemler',
		'MenuToDoMyActions' => 'Sonlandırılmayan eylemlerim',
		'MenuDoneMyActions' => 'Sonlandırılan eylemlerim',
		'ListOfEvents' => 'Dolibarr eylemleri listesi',
		'ActionsAskedBy' => 'Eylemi bildiren',
		'ActionsToDoBy' => 'Eylemlerden etkilenen',
		'ActionsDoneBy' => 'Eylemleri yapan',
		'AllMyActions' => 'Tüm eylemlerim/görevlerim',
		'AllActions' => 'Tüm eylemler/görevler',
		'ViewList' => 'Liste görünümü',
		'ViewCal' => 'Ay görünümü',
		'ViewDay' => 'Gün görünümü',
		'ViewWeek' => 'Hafta görünümü',
		'ViewWithPredefinedFilters' => 'Öntanımlı süzgeçler ile görünüm',
		'AutoActions' => 'Gündemin otomatik doldurulması',
		'AgendaAutoActionDesc' => 'Burada Dolibarr\'ın otomatik olarak gündemde oluşturmasını istediğiniz olayları tanımlayın. İşaretli bir şey yoksa (varsayılan olarak) sadece el ile girilen eylemler gündeme dahil edilecektir.',
		'AgendaSetupOtherDesc' => 'Bu sayfa Dolibarr eylemlerinin dış bir takvime aktarılması için seçenekler sağlar. (thunderbird, google calendar, ...)',
		'AgendaExtSitesDesc' => 'Bu sayfa takvimlerin dış kaynaklarında Dolibarr gündemindeki etkinliklerinin görünmesini sağlar.',
		'ActionsEvents' => 'Dolibarr\'ın otomatik olarak gündemde bir etkinlik oluşturacağı eylemler',
		'PropalValidatedInDolibarr' => '%s Teklifi doğrulandı',
		'InvoiceValidatedInDolibarr' => '%s Faturası doğrulandı',
		'InvoiceBackToDraftInDolibarr' => '%s Faturasını taslak durumuna geri götür',
		'OrderValidatedInDolibarr' => '%s Siparişi doğrulandı',
		'OrderApprovedInDolibarr' => '%s Siparişi onayladı',
		'OrderBackToDraftInDolibarr' => '%s Siparişini taslak durumuna geri götür',
		'OrderCanceledInDolibarr' => '%s Siparişi iptal edildi',
		'InterventionValidatedInDolibarr' => '%s Müdahalesi doğrulandı',
		'ProposalSentByEMail' => '%s Ticari teklifi Eposta ile gönderildi',
		'OrderSentByEMail' => '%s Müşteri siparişi Eposta ile gönderildi',
		'InvoiceSentByEMail' => '%s Müşteri faturası Eposta ile gönderildi',
		'SupplierOrderSentByEMail' => '%s Tedarikçi siparişi Eposta ile gönderildi',
		'SupplierInvoiceSentByEMail' => '%s Tedarikçi faturası Eposta ile gönderildi',
		'ShippingSentByEMail' => '%s Sevkiyatı Eposta ile gönderildi',
		'InterventionSentByEMail' => '%s Müdahalesi Eposta ile gönderildi',
		'NewCompanyToDolibarr' => 'Üçüncü parti oluşturuldu',
		'DateActionPlannedStart' => 'Planlanan başlangıç tarihi',
		'DateActionPlannedEnd' => 'Planlanan bitiş tarihi',
		'DateActionDoneStart' => 'Gerçek başlangıç tarihi',
		'DateActionDoneEnd' => 'Gerçek bitiş tarihi',
		'DateActionStart' => 'Başlangıç tarihi',
		'DateActionEnd' => 'Bitiş tarihi',
		'AgendaUrlOptions1' => 'Süzgeç çıktısına ayrıca aşağıdaki parametreleri ekleyebilirsiniz:',
		'AgendaUrlOptions2' => 'Eylem çıktılarını eylem, oluşturan, eylemden etkilenen ya da eylemi yapan kullanıcı <b>login=%s</b> sınırlayacak kullanıcı <b>%s</b>.',
		'AgendaUrlOptions3' => 'Çıktıyı kullanıcı <b>%s</b> tarafından oluşturulan etkinliklerle sınırlamak için<b>logina=%s</b>.',
		'AgendaUrlOptions4' => 'Çıktıyı kullanıcı <b>%s</b> tarafından etkilenen etkinliklerle sınırlamak için<b>logint=%s</b>.',
		'AgendaUrlOptions5' => 'Çıktıyı kullanıcı<b>%s</b> tarafından yapılan etkinliklerle sınırlamak için<b>logind=%s</b>.',
		'AgendaShowBirthdayEvents' => 'Kişilerin doğum günlerini göster',
		'AgendaHideBirthdayEvents' => 'Kişilerin doğum günlerini gizle',
		'Event' => 'Événement',
		'Activities' => 'Tâches/activités',
		'NewActions' => 'Nouvelles<br>actions',
		'DoActions' => 'Actions<br>en cours',
		'SumMyActions' => 'Actions réalisées<br>par moi cette année',
		'SumActions' => 'Actions au total<br>cette année',
		'DateEchAction' => 'Date d\'échéance',
		'StatusActionTooLate' => 'Action en retard',
		'MyTasks' => 'Mes tâches',
		'MyDelegatedTasks' => 'Mes tâches déléguées',
		'ProdPlanning' => 'Planning de production',
		// External Sites ical
		'ExportCal' => 'Dışaaktarım takvimi',
		'ExtSites' => 'Dış takvimleri içeaktar',
		'ExtSitesEnableThisTool' => 'Dış takvimleri gündemde göster',
		'ExtSitesNbOfAgenda' => 'Takvimlerin sayısı',
		'AgendaExtNb' => 'Takvim sayısı %s',
		'ExtSiteUrlAgenda' => '.ical dosyasına erişmek için URL',
		'ExtSiteNoLabel' => 'Tanımlama yok'
);
?>