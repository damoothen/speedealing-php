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
		'MemcachedServer' => 'Servidor Memcached',
		'MemcachedSetup' => 'Configuración de uso de Memcached',
		'MemcachedDesc' => 'Configure aquí las propiedades del servidor Memcached. Automáticamente Speedealing las usará para aumentar la velocidad de los procesos (usando la caché para las traducciones, por ejemplo)',
		'SizeOfCache' => 'Tamaño de la caché',
		'ItemsInCache' => 'Número de objetos en la caché',
		'NumberOfCacheInsert' => 'Número de cache en escritura',
		'NumberOfCacheRead' => 'Número de caché en lectura (correctos/intentos totales)',
		'FlushCache' => 'Nivel de caché',
		'Flushed' => 'Nivelado de caché',
		'InformationsOnCacheServer' => 'Información y estadísticas acerca del servidor de Memcached',
		'FailedToReadServer' => 'Fallo al leer el servidor Memcached',
		'ConfigureParametersFirst' => 'Configure primero el servidor...',
		'MemcachedClient' => 'Funciones cliente PHP <b>%s</b>',
		'MemcachedClientBothAvailable' => 'Ambas funciones cliente PHP están disponibles. Speedealing usará la función cliente <b>%s</b>',
		'OnlyClientAvailable' => 'Al menos una de las dos librerías cliente PHP está disponible. Speedealing la usará.',
		'ClientNotFound' => 'Su PHP debe soportar funcionalidades Memcached  (No se han encontrado ni Memcached ni versión cliente Memcache). Este módulo no puede funcionar. Mire documentación online para obtener más información.',
		'PrefixForKeysInCache' => 'Prefijo de claves Speedealing en el servidor de caché',
		'ServerSetup' => 'Configuración del servidor',
		'ServerStatistics' => 'Estadísticas del servidor',
		'CacheBrowser' => 'Caché del navegador',
		'WarningStatsForAllServer' => 'Atención, las estadísticas del servidor memcached incluyen todas las aplicaciones que usen este servidor, no solamente de Speedealing'
);
?>