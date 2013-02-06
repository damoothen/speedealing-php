<?php
/* Copyright (C) 2013	Regis Houssin	<regis.houssin@capnetworks.com>
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

$memcached = array(
		'CHARSET' => 'UTF-8',
		'MemcachedServer' => 'Serveur Memcached',
		'MemcachedSetup' => 'Configuration d\'utilisation d\'un serveur Memcached',
		'MemcachedDesc' => 'Renseigner ici les coordonnées du serveur Memcached. Automatiquement, Speedealing l\'utilisera pour réduire les temps de traitements (mise en cache des traduction par exemple).',
		'SizeOfCache' => 'Taille du cache',
		'ItemsInCache' => 'Nombre d\'objets dans le cache',
		'NumberOfCacheInsert' => 'Nombre d\'écriture d\'objets dans le cache',
		'NumberOfCacheRead' => 'Nombre de lecture du cache (succès/total tentatives)',
		'FlushCache' => 'Vider le cache',
		'Flushed' => 'Cache purgé',
		'InformationsOnCacheServer' => 'Informations et statistiques sur le serveur Memcached',
		'FailedToReadServer' => 'Echec de l\'interrogation du serveur Memcached',
		'ConfigureParametersFirst' => 'Configurer le serveur d\'abord...',
		'MemcachedClient' => 'Couche cliente PHP <b>%s</b>',
		'MemcachedClientBothAvailable' => 'Les 2 couches clients PHP pour le serveur Memcached sont disponibles. Speedealing utilisera les fonctions clients <b>%s</b>.',
		'OnlyClientAvailable' => 'Au moins une des 2 couches clients du serveur Memcached est disponible. Speedealing l\'utilisera.',
		'ClientNotFound' => 'Votre PHP doit supporter les fonctions clientes d\'accès au serveur Memcached (Ni le client PHP Memcached, ni le client PHP Memcache n\'a été trouvé). Ce module ne peut donc être opérationnel. Voir la doc en ligne pour plus d\'information.',
		'PrefixForKeysInCache' => 'Prefix des clés Speedealing utilisées dans le cache serveur',
		'ServerSetup' => 'Configuration serveur',
		'ServerStatistics' => 'Statistiques serveur',
		'CacheBrowser' => 'Navigateur cache',
		'WarningStatsForAllServer' => 'Attention, les statistiques du serveur memcached intègrent toutes les applications utilisant ce serveur et pas seulement Speedealing.'
);
?>