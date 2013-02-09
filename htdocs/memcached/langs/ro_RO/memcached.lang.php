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
		'MemcachedServer' => 'Server Memcached  ',
		'MemcachedSetup' => 'Configurare utilizare Memcached  ',
		'MemcachedDesc' => 'Intraduceti aici coordonatele  a severului Memcached . Automat, Speedealing va utiliza pentru a reduce viteza de procesare ( punerea in cache a traducerilor, de exemplu)',
		'SizeOfCache' => 'Marimea cache-ului',
		'ItemsInCache' => 'Număr obiecte în cache',
		'NumberOfCacheInsert' => 'Număr de scrieri obiecte în cache',
		'NumberOfCacheRead' => 'Număr citiri din cache ( succes /total tentative )',
		'FlushCache' => 'Goleşte cache - ul',
		'Flushed' => 'Cache gol',
		'InformationsOnCacheServer' => 'Informaţii şi statistici despre  serverul Memcached  ',
		'FailedToReadServer' => 'Eşec de interograre a serverului  Memcached  ',
		'ConfigureParametersFirst' => 'Configuraţi serverul mai intâi ...',
		'MemcachedClient' => 'Versiune PHP client  <b>%s</b>',
		'MemcachedClientBothAvailable' => 'Două versiunii  client PHP  a serverului Memcached sunt  disponibile. Speedealing va utiliza versiunea  client <b>%s</b>.',
		'OnlyClientAvailable' => 'Cel puţin una din cele versiuni client a serverului Memcached  sunt disponibile. Speedealing  o va utiliza.',
		'ClientNotFound' => 'PHP-ul dvs trebuie să suporte funţionalitatea clienţilor de accesa serverul  Memcached.(Nici  Memcached, nici versiunea Memcache a clientului n-a fost găsită).Acest modul nu poate fi deci operational. Vezi documentaţia online pentru mai multe informaţii.',
		'PrefixForKeysInCache' => 'Prefix chei Speedealing utilizate în cache -ul serverului',
		'ServerSetup' => 'Configurare server',
		'ServerStatistics' => 'Statistici server',
		'CacheBrowser' => 'Cache browser',
		'WarningStatsForAllServer' => 'Atenţie, statisticile serverului Memcached  includ toate aplicatiile ce folosesc serverul , nu numai Speedealing.'
);
?>