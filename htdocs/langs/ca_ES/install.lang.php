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
		'PHPVersion' => 'Versió PHP',
		'PHPGD' => 'PHP GD',
		'PHPSupportGD' => 'Aquest PHP suporta les funcions gràfiques GD.',
		'PHPCurl' => 'PHP Curl',
		'PHPSupportCurl' => 'This PHP support CURL functions.',
		'PHPMemcached' => 'PHP Mecached',
		'PHPSupportMemcached' => 'This PHP support Memcached functions.',
		'PHPMemoryLimit' => 'PHP memory',
		'PHPMemoryOK' => 'La seva memòria màxima de sessió PHP aquesta definit a <b>%s</b>. Això hauria de ser suficient.',
		'PHPMemoryTooLow' => 'La seva memòria màxima de sessió PHP està definida a <b>%s</b> bytes. Això és molt poc. Es recomana modificar el paràmetre <b>memory_limit</b> del seu arxiu <b> php.ini</b> a almenys <b>%s</b> octets.',
		'CouchDB' => 'CouchDB',
		'CouchDBVersion' => 'CouchDB version %s',
		'CouchDBProxyPassDescription' => '',
		'ErrorPHPDoesNotSupportGD' => 'Aquest PHP no suporta les funcions gràfiques GD. Cap gràfic estarà disponible.',
		'ErrorPHPDoesNotSupportCurl' => 'Your PHP installation does not support CURL functions. This is necessary to interact with the database.',
		'ErrorFailedToCreateDatabase' => 'Error en crear la base de dades \'%s\'.',
		'ErrorFailedToConnectToDatabase' => 'Error de connexió a la base de dades \'%s\'.',
		'ErrorDatabaseVersionTooLow' => 'Versió de la base de dades (%s) demasiado antigua. massa antiga. Es requereix versió %s o superior.',
		'ErrorPHPVersionTooLow' => 'Versió del PHP massa antiga. Es requereix versió %s o superior.',
		'ErrorCouchDBVersion' => 'CouchDB version (%s) is too old. Version %s or higher is required.',
		'ErrorCouchDBNotUseProxyPass' => '',
		'WarningPHPVersionTooLow' => 'Versió de PHP massa antiga. Es recomana versió %s o superior. Podeu usar aquesta versió, però no és compatible.',
		'WarningPHPDoesNotSupportMemcached' => 'Your PHP installation does not support Memcached function.',
		'MemcachedDescription' => 'Activer Memcached necessite l\'installation d\'un serveur Memcached et des lib php-memcached ou php-memcache. Il peut être activer après l\'installation.',
		'Reload' => 'Reload',
		'ReloadIsRequired' => 'Reload is required',
		// Config file
		'ConfFileStatus' => 'Config file',
		'ConfFileCreated' => 'Config file created',
		'ConfFileExists' => 'L\'arxiu de configuració <b>%s</b> existeix.',
		'ConfFileDoesNotExists' => 'El fitxer de configuració <b>%s</b> no existeix!',
		'ConfFileDoesNotExistsAndCouldNotBeCreated' => 'El fitxer de configuració <b>%s</b> no existeix i no s\'ha creat!',
		'ConfFileIsNotWritable' => 'L\'arxiu <b>%s</b> no és modificable. Per a una primera instal·lació, modifiqui els seus permisos. El servidor web ha de tenir el dret a escriure en aquest fitxer a la configuració ( "chmod 666" per exemple sobre un SO compatible UNIX).',
		'ConfFileIsWritable' => 'L\'arxiu <b>%s</b> és modificable.',
		'YouMustCreateWithPermission' => 'Ha de crear un fitxer %s i donar-li els drets d\'escriptura al servidor web durant el procés d\'instal·lació.',
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
		'URLRoot' => 'URL Arrel',
		'SpeedealingDatabase' => 'Speedealing Database',
		'ServerAddressDescription' => 'Nom o adreça IP del servidor de base de dades, generalment \'localhost\' quan el servidor es troba en la mateixa màquina que el lloc web',
		'ServerPortDescription' => 'Port del servidor de la base de dades. Deixar en blanc si ho desconeix.',
		'DatabaseServer' => 'Servidor de la base de dades',
		'DatabaseName' => 'Nom de la base de dades',
		'Login' => 'Usuari',
		'AdminLogin' => 'Usuari de l\'administrador de la base de dades Speedealing. Deixi buit si es connecta com a anonymous',
		'Password' => 'Contrasenya',
		'AdminPassword' => 'contrasenya de l\'administrador de la base de dades Speedealing. Deixi buit si es connecta com a anonymous',
		'SystemIsInstalled' => 'S\'està instal·lant el seu sistema',
		'WithNoSlashAtTheEnd' => 'Sense el signe "/" al final',
		'ServerPortCouchdbDescription' => 'Port du serveur. Défaut 5984.',
		'ServerAddressCouchdbDescription' => 'Nom FQDN du serveur de base de données, \'localhost.localdomain\' quand le serveur est installé sur la même machine que le serveur web',
		'DatabaseCouchdbUserDescription' => 'Login du super administrateur ayant tous les droits sur le serveur CouchDB ou l\'administrateur propriétaire de la base si la base et son compte d\'accès existent déjà (comme lorsque vous êtes chez un hébergeur).<br><br><div class="alert-box info">Cet utilisateur/mot de passe sera l\'administrateur pour se connecter à Speedealing.</div>',
		'ServerAddressMemcachedDesc' => 'Nom ou adresse ip du serveur memcached, généralement \'localhost\' quand le serveur est installé sur la même machine que le serveur web',
		'ServerPortMemcachedDesc' => 'Port du serveur memcached. Défaut : 11211',
		'FailedToCreateAdminLogin' => 'Echec de la création du compte administrateur Speedealing.',
		// Upgrade
		'UpgradeOk' => 'Upgrade is ok !',
		'NewInstalledVersion' => 'Your new version is %s',
		'NeedUpgrade' => 'New Speedealing version !',
		'WarningUpgrade' => 'Installed version is %s, you must upgrade to %s. <br>Please contact your administrator.'
);
?>