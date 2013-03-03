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
		'MemcachedServer' => 'Memcached server',
		'MemcachedSetup' => 'Memcached usage setup',
		'MemcachedDesc' => 'Set here properties of Memcached server. Automatically, Speedealing will use it to reduce process speed (using cache for language translations for example).',
		'SizeOfCache' => 'Size of cache',
		'ItemsInCache' => 'Number of objects into cache',
		'NumberOfCacheInsert' => 'Number of cache write',
		'NumberOfCacheRead' => 'Number of cache read (success/total tries)',
		'FlushCache' => 'Flush cache',
		'Flushed' => 'Cache flushed',
		'InformationsOnCacheServer' => 'Informations and statistics about the Memcached server',
		'FailedToReadServer' => 'Failed to read the Memcached server',
		'ConfigureParametersFirst' => 'Setup the server first...',
		'MemcachedClient' => 'PHP client functions <b>%s</b>',
		'MemcachedClientBothAvailable' => 'Both PHP client functions ara available. Speedealing will use the <b>%s</b> client functions.',
		'OnlyClientAvailable' => 'At least one of the two PHP client libraries is available. Speedealing will use it.',
		'ClientNotFound' => 'Your PHP must support Memcached client features (Nor the Memcached, nor the Memcache version of client was found). This module can\'t work. See online documentation for more informations.',
		'PrefixForKeysInCache' => 'Prefix of Speedealing keys in server cache',
		'ServerSetup' => 'Server setup',
		'ServerStatistics' => 'Server Statistics',
		'CacheBrowser' => 'Cache browser',
		'WarningStatsForAllServer' => 'Warning, memcached server statistics include all applications using this server, not only Speedealing.'
);
?>