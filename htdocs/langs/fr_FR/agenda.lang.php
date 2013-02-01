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
		'Actions' => 'Activités',
		'ActionsArea' => 'Espace événements (évènements et tâches)',
		'Agenda' => 'Agenda',
		'Agendas' => 'Agendas',
		'Calendar' => 'Calendrier',
		'Calendars' => 'Calendriers',
		'LocalAgenda' => 'Calendrier local',
		'AffectedTo' => 'Affecté à',
		'DoneBy' => 'Réalisé par',
		'Event' => 'Événement',
		'Events' => 'Événements',
		'MyEvents' => 'Mes événements',
		'OtherEvents' => 'Autres événements',
		'ListOfActions' => 'Liste des actions',
		'Location' => 'Lieu',
		'EventOnFullDay' => 'Événement sur la journée',
		'SearchAnAction' => 'Rechercher un événement/tâche',
		'MenuToDoActions' => 'Les événem. incomplets',
		'MenuDoneActions' => 'Les événem. terminés',
		'MenuToDoMyActions' => 'Mes événem. incomplets',
		'MenuDoneMyActions' => 'Mes événem. terminés',
		'ListOfEvents' => 'Liste des événements',
		'ActionsAskedBy' => 'Action enregistrés par',
		'ActionsToDoBy' => 'Action affectés à',
		'ActionsDoneBy' => 'Action réalisés par',
		'AllMyActions' => 'Toutes mes actions',
		'AllActions' => 'Toutes les actions',
		'ViewList' => 'Vue liste',
		'ViewCal' => 'Vue mois',
		'ViewDay' => 'Vue jour',
		'ViewWeek' => 'Vue semaine',
		'ViewWithPredefinedFilters' => 'Vues avec filtres prédéfinis',
		'AutoActions' => 'Alimentation automatique de l\'agenda',
		'AgendaAutoActionDesc' => 'Définissez dans cet onglet les événements pour lesquels speedealing créera automatiquement une action dans l\'agenda. Si aucune case n\'est cochée (par défaut), seules les actions manuelles seront incluses dans l\'agenda.',
		'AgendaSetupOtherDesc' => 'Cette page permet de configurer quelques options permettant d\'exporter une vue de votre agenda Speedealing vers un calendrier externe (thunderbird, google calendar, ...)',
		'AgendaExtSitesDesc' => 'Cette page permet d\'ajouter des sources de calendriers externes pour les visualiser au sein de l\'agenda Speedealing.',
		'ActionsEvents' => 'Événements pour lesquels Speedealing doit créer une action dans l\'agenda en automatique.',
		'PropalValidatedInSpeedealing' => 'Proposal %s validated',
		'InvoiceValidatedInSpeedealing' => 'Invoice %s validated',
		'InvoiceBackToDraftInSpeedealing' => 'Invoice %s go back to draft status',
		'OrderValidatedInSpeedealing' => 'Order %s validated',
		'OrderApprovedInSpeedealing' => 'Order %s approved',
		'OrderBackToDraftInSpeedealing' => 'Order %s go back to draft status',
		'OrderCanceledInSpeedealing' => 'Order %s canceled',
		'InterventionValidatedInSpeedealing' => 'Intervention %s validated',
		'ProposalSentByEMail' => 'Proposition commerciale %s envoyée par EMail',
		'OrderSentByEMail' => 'Commande client %s envoyée par EMail',
		'InvoiceSentByEMail' => 'Facture client %s envoyée par EMail',
		'SupplierOrderSentByEMail' => 'Commande fournisseur %s envoyée par EMail',
		'SupplierInvoiceSentByEMail' => 'Facture fournisseur %s envoyée par EMail',
		'ShippingSentByEMail' => 'Bon d\'expédition %s envoyé par EMail',
		'InterventionSentByEMail' => 'Intervention %s envoyée par EMail',
		'NewCompanyToSpeedealing' => 'Third party created',
		'DateActionPlannedStart' => 'Date début réalisation prévue',
		'DateActionPlannedEnd' => 'Date fin réalisation prévue',
		'DateActionDoneStart' => 'Date début réalisation réelle',
		'DateActionDoneEnd' => 'Date fin réalisation réelle',
		'DateActionStart' => 'Date début',
		'DateActionEnd' => 'Date fin',
		'AgendaUrlOptions1' => 'Vous pouvez aussi ajouter les paramètres suivants pour filtrer les réponses:',
		'AgendaUrlOptions2' => '<b>login=%s</b> pour limiter l\'export aux actions créées, affectées ou réalisées par l\'utilisateur <b>%s</b>.',
		'AgendaUrlOptions3' => '<b>logina=%s</b> pour limiter l\'export aux actions créées par l\'utilisateur <b>%s</b>.',
		'AgendaUrlOptions4' => '<b>logint=%s</b> pour limiter l\'export aux actions affectées à l\'utilisateur <b>%s</b>.',
		'AgendaUrlOptions5' => '<b>logind=%s</b> pour limiter l\'export aux actions réalisées par l\'utilisateur <b>%s</b>.',
		'AgendaShowBirthdayEvents' => 'Afficher anniversaires contacts',
		'AgendaHideBirthdayEvents' => 'Cacher anniversaires contacts',
		'Activities' => 'Tâches/activités',
		'NewActions' => 'Mes nouvelles<br>actions',
		'DoActions' => 'Mes actions<br>en cours',
		'SumMyActions' => 'Mes actions réalisées<br>cette année',
		'SumActions' => 'Actions au total<br>cette année',
		'DateEchAction' => 'Date d\'échéance',
		'StatusActionTooLate' => 'Action en retard',
		'MyTasks' => 'Mes tâches',
		'MyDelegatedTasks' => 'Mes tâches déléguées',
		'ProdPlanning' => 'Planning de production',
		// External Sites ical
		'ExportCal' => 'Export calendrier',
		'ExtSites' => 'Import calendriers externes',
		'ExtSitesEnableThisTool' => 'Afficher les calendriers externes sur l\'agenda',
		'ExtSitesNbOfAgenda' => 'Nombre de calendriers',
		'AgendaExtNb' => 'Calendrier no %s',
		'ExtSiteUrlAgenda' => 'Url d\'accès au fichier ical',
		'ExtSiteNoLabel' => 'Aucune description'
);
?>