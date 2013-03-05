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

$agenda = array(
		'CHARSET' => 'UTF-8',
		'Actions' => 'Eventos',
		'ActionsArea' => 'Área de eventos (Acciones y tareas)',
		'Agenda' => 'Agenda',
		'Agendas' => 'Agendas',
		'Calendar' => 'Calendario',
		'Calendars' => 'Calendarios',
		'LocalAgenda' => 'Calendario local',
		'AffectedTo' => 'Afectado a',
		'DoneBy' => 'Hecho por',
		'Event' => 'Evento',
		'Events' => 'Eventos',
		'MyEvents' => 'Mis eventos',
		'OtherEvents' => 'Otros eventos',
		'ListOfActions' => 'Listado de eventos',
		'Location' => 'Ubicación',
		'EventOnFullDay' => 'Evento en el día completo',
		'SearchAnAction' => 'Buscar un evento/tarea',
		'MenuToDoActions' => 'Todos los eventos incompletos',
		'MenuDoneActions' => 'Todos los eventos terminados',
		'MenuToDoMyActions' => 'Mis eventos incompletos',
		'MenuDoneMyActions' => 'Mis eventos terminados',
		'ListOfEvents' => 'Listado de eventos Speedealing',
		'ActionsAskedBy' => 'Eventos reportados por',
		'ActionsToDoBy' => 'Eventos afectados a',
		'ActionsDoneBy' => 'Eventos hechos por',
		'AllMyActions' => 'Todos mis eventos/tareas',
		'AllActions' => 'Todos los eventos/tareas',
		'ViewList' => 'Vista de lista',
		'ViewCal' => 'Vista mensual',
		'ViewDay' => 'Vista diaria',
		'ViewWeek' => 'Vista semanal',
		'ViewWithPredefinedFilters' => 'Ver con filtros predefinidos',
		'AutoActions' => 'Llenado automático de la agenda',
		'AgendaAutoActionDesc' => 'Defina aquí los eventos en los cuales quiere que Speedealing cree automáticamente un evento en la agenda. Si nada es seleccionado (por defecto), sólo acciones manuales serán incluidas en la agenda.',
		'AgendaSetupOtherDesc' => 'Esta página provee opciones para permitir exportar los eventos de Speedealing en un calendario externo (thunderbird, google calendar, ...)',
		'AgendaExtSitesDesc' => 'Esta página le permite declarar fuentes externas de calendarios para ver sus eventos en la agenda de Speedealing.',
		'ActionsEvents' => 'Eventos para los cuales Speedealing creará una acción en la agenda automáticamente',
		'PropalValidatedInSpeedealing' => 'Propuesta %s validada',
		'InvoiceValidatedInSpeedealing' => 'Factura %s validada',
		'InvoiceBackToDraftInSpeedealing' => 'Factura %s marcada como borrador',
		'OrderValidatedInSpeedealing' => 'Orden %s validada',
		'OrderApprovedInSpeedealing' => 'Orden %s aprovada',
		'OrderBackToDraftInSpeedealing' => 'Orden %s marcada como borrador',
		'OrderCanceledInSpeedealing' => 'Orden %s cancelada',
		'InterventionValidatedInSpeedealing' => 'Intervención %s validada',
		'ProposalSentByEMail' => 'Propuesta comercial %s enviado por EMail',
		'OrderSentByEMail' => 'Orden de cliente %s enviada por EMail',
		'InvoiceSentByEMail' => 'Factura de cliente %s enviada por EMail',
		'SupplierOrderSentByEMail' => 'Orden de proveedor %s enviada por EMail',
		'SupplierInvoiceSentByEMail' => 'Factura de proveedor %s enviada por EMail',
		'ShippingSentByEMail' => 'Envío %s enviado por EMail',
		'InterventionSentByEMail' => 'Intervención %s enviada por EMail',
		'NewCompanyToSpeedealing' => 'Nueva compañía creada',
		'DateActionPlannedStart' => 'Fecha prevista para el inicio',
		'DateActionPlannedEnd' => 'Fecha prevista para el fin',
		'DateActionDoneStart' => 'Fecha de inicio real',
		'DateActionDoneEnd' => 'Fecha de término real',
		'DateActionStart' => 'Fecha de inicio',
		'DateActionEnd' => 'Fecha de término',
		'AgendaUrlOptions1' => 'También puede agregar los siguientes parámetros para filtrar el resultado:',
		'AgendaUrlOptions2' => '<b>login=%s</b> para restringir el resultado de las acciones creadas, afectadas o hechas por el usuario <b>%s</b>.',
		'AgendaUrlOptions3' => '<b>logina=%s</b> para restringir el resultado de las acciones creadas por el usuario <b>%s</b>.',
		'AgendaUrlOptions4' => '<b>logint=%s</b> para restringir el resultado de las acciones afectadas por el usuario <b>%s</b>.',
		'AgendaUrlOptions5' => '<b>logind=%s</b> para restringir el resultado de las acciones hechas por el usuario <b>%s</b>.',
		'AgendaShowBirthdayEvents' => 'Mostrar los cumpleaños de los contactos',
		'AgendaHideBirthdayEvents' => 'Ocultar los cumpleaños de los contactos',
		'Activities' => 'Actividades',
		'NewActions' => 'Noticias<br>Acciones',
		'DoActions' => 'Acciones<br>en progreso',
		'SumMyActions' => 'Acciones hechas<br>por mi este año',
		'SumActions' => 'Acciones en total<br>este año',
		'DateEchAction' => 'Fecha límite',
		'StatusActionTooLate' => 'Retardo de la acción',
		'MyTasks' => 'Mis tareas',
		'MyDelegatedTasks' => 'Mis tareas delegadas',
		'ProdPlanning' => 'Planeación de producción',
		// External Sites ical
		'ExportCal' => 'Exportar calendario',
		'ExtSites' => 'Importar calendarios externos',
		'ExtSitesEnableThisTool' => 'Mostrar calendarios externos en la agenda',
		'ExtSitesNbOfAgenda' => 'Número de calendarios',
		'AgendaExtNb' => 'Calendario nro %s',
		'ExtSiteUrlAgenda' => 'URL para acceder al archivo .ical',
		'ExtSiteNoLabel' => 'Sin descripción'
);
?>