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

$install = array(
		'CHARSET' => 'UTF-8',
		// Welcome step
		'SpeedealingSetup' => 'Configuración Speedealing',
		'Welcome' => 'Bienvenido',
		'WelcomeTitle' => 'Bienvenido a Speedealing',
		'WelcomeDescription' => 'Instalación Speedealing',
		'LanguageDescription' => 'Idioma usado en Estados Unidos',
		'InstallTypeTitle' => 'Tipo de instalación',
		'InstallType' => 'Tipo de instalación',
		'InstallTypeDescription' => 'Escoja su tipo de instalación',
		'InstallTypeServer' => 'Instalación tipo servidor',
		'InstallTypeServerDescription' => '',
		'InstallTypeClient' => 'Instalación tipo cliente',
		'InstallTypeClientDescription' => '',
		// Prerequisite step
		'Prerequisites' => 'Prerrequisitos',
		'PrerequisitesTitle' => 'Comprobando prerrequisitos',
		'PrerequisitesDescription' => 'La aplicación requiere unos prerrequisitos en su sistema para funcionar correctamente.',
		'MoreInformation' => 'Más información',
		'PHPVersion' => 'Versión PHP',
		'PHPGD' => 'PHP GD',
		'PHPSupportGD' => 'Este PHP soporta las funciones gráficas GD.',
		'PHPCurl' => 'PHP Curl',
		'PHPSupportCurl' => 'Este PHP soporta las funciones CURL',
		'PHPMemcached' => 'PHP Memcached',
		'PHPSupportMemcached' => 'Este PHP soporta las funciones Memcached',
		'PHPMemoryLimit' => 'Memoria PHP',
		'PHPMemoryOK' => 'Su memoria máxima de sesión PHP esta definida a <b>%s</b>. Esto debería ser suficiente.',
		'PHPMemoryTooLow' => 'Su memoria máxima de sesión PHP está definida en <b>%s</b> bytes. Esto es muy poco. Se recomienda modificar el parámetro <b>memory_limit</b> de su archivo <b>php.ini</b> a por lo menos <b>%s</b> bytes.',
		'CouchDB' => 'CouchDB',
		'CouchDBVersion' => 'Versión %s de CouchDB',
		'CouchDBProxyPassDescription' => '',
		'ErrorPHPDoesNotSupportGD' => 'Este PHP no soporta las funciones gráficas GD. Ningún gráfico estará disponible.',
		'ErrorPHPDoesNotSupportCurl' => 'Su instalación PHP no soporta las funciones CURL. Son necesarias para interactuar con la base de datos.',
		'ErrorFailedToCreateDatabase' => 'Error al crear la base de datos \'%s\'.',
		'ErrorFailedToConnectToDatabase' => 'Error de conexión a la base de datos \'%s\'.',
		'ErrorDatabaseVersionTooLow' => 'Versión de la base de datos (%s) demasiado antigua. Se requiere versión %s o superior.',
		'ErrorPHPVersionTooLow' => 'Versión de PHP demasiado antigua. Se requiere versión %s o superior.',
		'ErrorCouchDBVersion' => 'La versión (%s) de CouchDB es demasiado antigua. Es necesaria una versión %s o superior.',
		'ErrorCouchDBNotUseProxyPass' => '',
		'WarningPHPVersionTooLow' => 'Versión de PHP demasiado antigua. Se recomienda version %s o superior. Puede usar esta versión, pero no es compatible.',
		'WarningPHPDoesNotSupportMemcached' => 'Su PHP no soporta la función Memcached',
		'MemcachedDescription' => 'Activer Memcached necessite l\'installation d\'un serveur Memcached et des lib php-memcached ou php-memcache. Il peut être activer après l\'installation.',
		'Reload' => 'Recargar',
		'ReloadIsRequired' => 'Es necesario recargar',
		// Config file
		'ConfFileStatus' => 'Archivo de configuración',
		'ConfFileCreated' => 'Archivo de configuración creado',
		'ConfFileExists' => 'El archivo de configuración <b>%s</b> existe.',
		'ConfFileDoesNotExists' => '¡El archivo de configuración <b>%s</b> no existe!',
		'ConfFileDoesNotExistsAndCouldNotBeCreated' => '¡El archivo de configuración <b>%s</b> no existe y no se ha creado!',
		'ConfFileIsNotWritable' => 'El archivo <b>%s</b> no es modificable. Para una primera instalación, modifique sus permisos. El servidor Web debe tener el derecho a escribir en este archivo durante la configuración ("chmod 666" por ejemplo sobre un SO compatible UNIX).',
		'ConfFileIsWritable' => 'El archivo <b>%s</b> es modificable.',
		'YouMustCreateWithPermission' => 'Debe crear un archivo %s y darle los derechos de escritura al servidor web durante el proceso de instalación.',
		// User sync
		'UserSyncCreated' => 'El usuario de replicación ha sido creado.',
		// Database
		'DatabaseCreated' => 'La base de datos ha sido creada.',
		'WarningDatabaseAlreadyExists' => 'La base de datos \'%s\' ya existe.',
		// SuperAdmin
		'AdminCreated' => 'Ha sido creado el superadmin.',
		// User
		'UserCreated' => 'Ha sido creado el usuario.',
		// Lock file
		'LockFileCreated' => 'El archivo de bloqueo ha sido creado.',
		'LockFileCouldNotBeCreated' => 'No ha podido crearse el archivo de bloqueo.',
		'URLRoot' => 'URL Raíz',
		'SpeedealingDatabase' => 'Speedealing Database',
		'ServerAddressDescription' => 'Nombre o dirección IP del servidor de base de datos, generalmente \'localhost\' cuando el servidor se encuentra en la misma máquina que el servidor web',
		'ServerPortDescription' => 'Puerto del servidor de la base de datos. Dejar en blanco si lo desconoce.',
		'DatabaseServer' => 'Servidor de la base de datos',
		'DatabaseName' => 'Nombre de la base de datos',
		'Login' => 'Usuario',
		'AdminLogin' => 'Usuario del administrador de la base de datos Speedealing. Deje vacío si se conecta en anonymous',
		'Password' => 'Contraseña',
		'AdminPassword' => 'Contraseña del administrador de la base de datos Speedealing. Deje vacío si se conecta en anonymous',
		'SystemIsInstalled' => 'Se está instalando su sistema.',
		'WithNoSlashAtTheEnd' => 'Sin el signo "/" al final',
		'ServerPortCouchdbDescription' => 'Port du serveur. Défaut 5984.',
		'ServerAddressCouchdbDescription' => 'Nom FQDN du serveur de base de données, \'localhost.localdomain\' quand le serveur est installé sur la même machine que le serveur web',
		'DatabaseCouchdbUserDescription' => 'Login du super administrateur ayant tous les droits sur le serveur CouchDB ou l\'administrateur propriétaire de la base si la base et son compte d\'accès existent déjà (comme lorsque vous êtes chez un hébergeur).<br><br><div class="alert-box info">Cet utilisateur/mot de passe sera l\'administrateur pour se connecter à Speedealing.</div>',
		'ServerAddressMemcachedDesc' => 'Nom ou adresse ip du serveur memcached, généralement \'localhost\' quand le serveur est installé sur la même machine que le serveur web',
		'ServerPortMemcachedDesc' => 'Port du serveur memcached. Défaut : 11211',
		'FailedToCreateAdminLogin' => 'Echec de la création du compte administrateur Speedealing.',
		'Install' => 'Instalación',
		'InstallTitle' => 'Instalación Speedealing',
		'InstallDescription' => 'Descripción instalación',
		'SystemSetup' => 'Configuración del sistema',
		'Database' => 'Base de datos',
		'Security' => 'Seguridad',
		'Start' => 'Inicio',
		// Upgrade
		'UpgradeOk' => 'La actualización se ha completado',
		'NewInstalledVersion' => 'Su nueva versión es %s',
		'NeedUpgrade' => '¡Nueva versión de Speedealing!',
		'WarningUpgrade' => 'La versión instalada es %s, debe actualizar a %s<br>Contacte con su administrador, por favor.'
);
?>