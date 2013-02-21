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
		'SpeedealingSetup' => 'Speedealing Setup',
		'Welcome' => 'Welcome',
		'WelcomeTitle' => 'Welcome to Speedealing',
		'WelcomeDescription' => 'Speedealing install',
		'LanguageDescription' => 'Language used on United States',
		'InstallTypeTitle' => 'Install type',
		'InstallType' => 'Install type',
		'InstallTypeDescription' => 'Choose your install type',
		'InstallTypeServer' => 'Install type server',
		'InstallTypeServerDescription' => '',
		'InstallTypeClient' => 'Install type client',
		'InstallTypeClientDescription' => '',
		// Prerequisite step
		'Prerequisites' => 'Prerequisites',
		'PrerequisitesTitle' => 'Checking prerequisites',
		'PrerequisitesDescription' => 'The application requires a few prerequisites on your system to function properly.',
		'MoreInformation' => 'More information',
		'PHPVersion' => 'Versión PHP',
		'PHPGD' => 'PHP GD',
		'PHPSupportGD' => 'Este PHP soporta las funciones gráficas GD.',
		'PHPCurl' => 'PHP Curl',
		'PHPSupportCurl' => 'This PHP support CURL functions.',
		'PHPMemcached' => 'PHP Mecached',
		'PHPSupportMemcached' => 'This PHP support Memcached functions.',
		'PHPMemoryLimit' => 'PHP memory',
		'PHPMemoryOK' => 'Su memoria máxima de sesión PHP esta definida a <b>%s</b>. Esto debería ser suficiente.',
		'PHPMemoryTooLow' => 'Su memoria máxima de sesión PHP está definida en <b>%s</b> bytes. Esto es muy poco. Se recomienda modificar el parámetro <b>memory_limit</b> de su archivo <b>php.ini</b> a por lo menos <b>%s</b> bytes.',
		'CouchDB' => 'CouchDB',
		'CouchDBVersion' => 'CouchDB version %s',
		'CouchDBProxyPassDescription' => '',
		'ErrorPHPDoesNotSupportGD' => 'Este PHP no soporta las funciones gráficas GD. Ningún gráfico estará disponible.',
		'ErrorPHPDoesNotSupportCurl' => 'Your PHP installation does not support CURL functions. This is necessary to interact with the database.',
		'ErrorFailedToCreateDatabase' => 'Error al crear la base de datos \'%s\'.',
		'ErrorFailedToConnectToDatabase' => 'Error de conexión a la base de datos \'%s\'.',
		'ErrorDatabaseVersionTooLow' => 'Versión de la base de datos (%s) demasiado antigua. Se requiere versión %s o superior.',
		'ErrorPHPVersionTooLow' => 'Versión de PHP demasiado antigua. Se requiere versión %s o superior.',
		'ErrorCouchDBVersion' => 'CouchDB version (%s) is too old. Version %s or higher is required.',
		'ErrorCouchDBNotUseProxyPass' => '',
		'WarningPHPVersionTooLow' => 'Versión de PHP demasiado antigua. Se recomienda version %s o superior. Puede usar esta versión, pero no es compatible.',
		'WarningPHPDoesNotSupportMemcached' => 'Your PHP installation does not support Memcached function.',
		'MemcachedDescription' => 'Activer Memcached necessite l\'installation d\'un serveur Memcached et des lib php-memcached ou php-memcache. Il peut être activer après l\'installation.',
		'Reload' => 'Reload',
		'ReloadIsRequired' => 'Reload is required',
		// Config file
		'ConfFileStatus' => 'Config file',
		'ConfFileCreated' => 'Config file created',
		'ConfFileExists' => 'El archivo de configuración <b>%s</b> existe.',
		'ConfFileDoesNotExists' => '¡El archivo de configuración <b>%s</b> no existe!',
		'ConfFileDoesNotExistsAndCouldNotBeCreated' => '¡El archivo de configuración <b>%s</b> no existe y no se ha creado!',
		'ConfFileIsNotWritable' => 'El archivo <b>%s</b> no es modificable. Para una primera instalación, modifique sus permisos. El servidor Web debe tener el derecho a escribir en este archivo durante la configuración ("chmod 666" por ejemplo sobre un SO compatible UNIX).',
		'ConfFileIsWritable' => 'El archivo <b>%s</b> es modificable.',
		'YouMustCreateWithPermission' => 'Debe crear un archivo %s y darle los derechos de escritura al servidor web durante el proceso de instalación.',
		// User sync
		'UserSyncCreated' => 'The replication user was created.',
		// Database
		'DatabaseCreated' => 'The database was created.',
		'WarningDatabaseAlreadyExists' => 'The database \'%s\' already exists.',
		// SuperAdmin
		'AdminCreated' => 'The superadmin was created.',
		// User
		'UserCreated' => 'The user was created.',
		// Lock file
		'LockFileCreated' => 'The lock file was created.',
		'LockFileCouldNotBeCreated' => 'The lock file could not be created.',
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
		'Install' => 'Install',
		'InstallTitle' => 'Speedealing install',
		'InstallDescription' => 'Install desc',
		'SystemSetup' => 'System setup',
		'Database' => 'Database',
		'Security' => 'Security',
		'Start' => 'Start',
		// Upgrade
		'UpgradeOk' => 'Upgrade is ok !',
		'NewInstalledVersion' => 'Your new version is %s',
		'NeedUpgrade' => 'New Speedealing version !',
		'WarningUpgrade' => 'Installed version is %s, you must upgrade to %s. <br>Please contact your administrator.'
);
?>