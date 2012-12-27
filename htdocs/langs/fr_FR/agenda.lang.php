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
		'Actions' => 'Activités',
		'ActionsArea' => 'Espace événements (évènements et tâches)',
		'Agenda' => 'Agenda',
		'Agendas' => 'Agendas',
		'Calendar' => 'Calendrier',
		'Calendars' => 'Calendriers',
		'LocalAgenda' => 'Calendrier local',
		'AffectedTo' => 'Affecté à',
		'DoneBy' => 'Réalisé par',
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
		'AgendaAutoActionDesc' => 'Définissez dans cet onglet les événements pour lesquels dolibarr créera automatiquement une action dans l\'agenda. Si aucune case n\'est cochée (par défaut), seules les actions manuelles seront incluses dans l\'agenda.',
		'AgendaSetupOtherDesc' => 'Cette page permet de configurer quelques options permettant d\'exporter une vue de votre agenda Dolibarr vers un calendrier externe (thunderbird, google calendar, ...)',
		'AgendaExtSitesDesc' => 'Cette page permet d\'ajouter des sources de calendriers externes pour les visualiser au sein de l\'agenda Dolibarr.',
		'ActionsEvents' => 'Événements pour lesquels Dolibarr doit créer une action dans l\'agenda en automatique.',
		'PropalValidatedInDolibarr' => 'Proposition %s validée',
		'InvoiceValidatedInDolibarr' => 'Facture %s validée',
		'InvoiceBackToDraftInDolibarr' => 'Facture %s repassée en brouillon',
		'OrderValidatedInDolibarr' => 'Commande %s validée',
		'OrderApprovedInDolibarr' => 'Commande %s approuvée',
		'OrderBackToDraftInDolibarr' => 'Commande %s repassée en brouillon',
		'OrderCanceledInDolibarr' => 'Commande %s annulée',
		'InterventionValidatedInDolibarr' => 'Intervention %s validée',
		'ProposalSentByEMail' => 'Proposition commerciale %s envoyée par EMail',
		'OrderSentByEMail' => 'Commande client %s envoyée par EMail',
		'InvoiceSentByEMail' => 'Facture client %s envoyée par EMail',
		'SupplierOrderSentByEMail' => 'Commande fournisseur %s envoyée par EMail',
		'SupplierInvoiceSentByEMail' => 'Facture fournisseur %s envoyée par EMail',
		'ShippingSentByEMail' => 'Bon d\'expédition %s envoyé par EMail',
		'InterventionSentByEMail' => 'Intervention %s envoyée par EMail',
		'NewCompanyToDolibarr' => 'Tiers créé',
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
		'ExportCal' => 'Export calendrier',
		'ExtSites' => 'Import calendriers externes',
		'ExtSitesEnableThisTool' => 'Afficher les calendriers externes sur l\'agenda',
		'ExtSitesNbOfAgenda' => 'Nombre de calendriers',
		'AgendaExtNb' => 'Calendrier no %s',
		'ExtSiteUrlAgenda' => 'Url d\'accès au fichier ical',
		'ExtSiteNoLabel' => 'Aucune description'
);
?>