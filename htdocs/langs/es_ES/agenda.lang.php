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
		'ActionsArea' => 'Área de eventos (acciones y tareas)',
		'Agenda' => 'Agenda',
		'Agendas' => 'Agendas',
		'Calendar' => 'Calendario',
		'CalendarMeeting' => 'Calendar meeting',
		'Calendars' => 'Calendarios',
		'LocalAgenda' => 'Calendario local',
		'AffectedTo' => 'Asignada a',
		'DoneBy' => 'Realizado por',
		'Event' => 'Event',
		'Events' => 'Eventos',
		'MyEvents' => 'Mis eventos',
		'OtherEvents' => 'Otros eventos',
		'ListOfActions' => 'Listado de eventos',
		'Location' => 'Localización',
		'EventOnFullDay' => 'Evento para todo el día',
		'SearchAnAction' => 'Buscar un evento/tarea',
		'MenuToDoActions' => 'Eventos incompletos',
		'MenuDoneActions' => 'Eventos terminados',
		'MenuToDoMyActions' => 'Mis eventos incompletos',
		'MenuDoneMyActions' => 'Mis eventos terminados',
		'ListOfEvents' => 'Listado de eventos Speedealing',
		'ActionsAskedBy' => 'Eventos registrados por',
		'ActionsToDoBy' => 'Eventos asignados a',
		'ActionsDoneBy' => 'Eventos realizados por',
		'AllMyActions' => 'Todos mis eventos/tareas',
		'AllActions' => 'Todos los eventos/tareas',
		'ViewList' => 'Vista listado',
		'ViewCal' => 'Vista mensual',
		'ViewDay' => 'Vista diaria',
		'ViewWeek' => 'Vista semanal',
		'ViewWithPredefinedFilters' => 'Ver con los filtros predefinidos',
		'AutoActions' => 'Inclusión automática en la agenda',
		'AgendaAutoActionDesc' => 'Indique en esta pestaña los eventos para los que desea que Speedealing cree automáticamente una acción en la agenda. Si no se marca ningún caso (por defecto),  solamente las acciones manuales se incluirán en la agenda.',
		'AgendaSetupOtherDesc' => 'Esta página le permite configurar algunas opciones que permiten exportar una vista de su agenda Dolibar a un calendario externo (thunderbird, google calendar, ...)',
		'AgendaExtSitesDesc' => 'Esta página le permite configurar calendarios externos para su visualización en la agenda de Speedealing.',
		'ActionsEvents' => 'Eventos para que Speedealing cree una acción de forma automática',
		'PropalValidatedInSpeedealing' => 'Proposal %s validated',
		'InvoiceValidatedInSpeedealing' => 'Invoice %s validated',
		'InvoiceBackToDraftInSpeedealing' => 'Invoice %s go back to draft status',
		'OrderValidatedInSpeedealing' => 'Order %s validated',
		'OrderApprovedInSpeedealing' => 'Order %s approved',
		'OrderBackToDraftInSpeedealing' => 'Order %s go back to draft status',
		'OrderCanceledInSpeedealing' => 'Order %s canceled',
		'InterventionValidatedInSpeedealing' => 'Intervention %s validated',
		'ProposalSentByEMail' => 'Presupuesto %s enviado por e-mail',
		'OrderSentByEMail' => 'Pedido de cliente %s enviado por e-mail',
		'InvoiceSentByEMail' => 'Factura a cliente %s enviada por e-mail',
		'SupplierOrderSentByEMail' => 'Pedido a proveedor %s enviada por e-mail',
		'SupplierInvoiceSentByEMail' => 'Factura de proveedor %s enviada por e-mail',
		'ShippingSentByEMail' => 'Expedición %s enviada por e-mail',
		'InterventionSentByEMail' => 'Intervención %s enviada por e-mail',
		'NewCompanyToSpeedealing' => 'Third party created',
		'DateActionPlannedStart' => 'Fecha de inicio prevista',
		'DateActionPlannedEnd' => 'Fecha de fin prevista',
		'DateActionDoneStart' => 'Fecha real de inicio',
		'DateActionDoneEnd' => 'Fecha real de finalización',
		'DateActionStart' => 'Fecha de inicio',
		'DateActionEnd' => 'Fecha finalización',
		'AgendaUrlOptions1' => 'Puede también añadir estos parámetros al filtro de salida:',
		'AgendaUrlOptions2' => '<b>login=%s</b> para restringir inserciones a acciones creadas , que afecten o realizadas por el usuario <b>%s</b>.',
		'AgendaUrlOptions3' => '<b>logina=%s</b> para restringir inserciones a acciones creadas por el usuario <b>%s</b>.',
		'AgendaUrlOptions4' => '<b>logint=%s</b> para restringir inserciones a acciones que afecten al usuario <b>%s</b>.',
		'AgendaUrlOptions5' => '<b>logind=%s</b> para restringir inserciones a acciones realizadas por el usuario <b>%s</b>.',
		'AgendaShowBirthdayEvents' => 'Mostrar cumpleaños de los contactos',
		'AgendaHideBirthdayEvents' => 'Ocultar cumpleaños de los contactos',
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
		'ExportCal' => 'Exportar calendario',
		'ExtSites' => 'Calendarios externos',
		'ExtSitesEnableThisTool' => 'Mostrar calendarios externos en la agenda',
		'ExtSitesNbOfAgenda' => 'Número de calendarios',
		'AgendaExtNb' => 'Calendario nº %s',
		'ExtSiteUrlAgenda' => 'Url de acceso al archivo .ical',
		'ExtSiteNoLabel' => 'Sin descripción'
);
?>