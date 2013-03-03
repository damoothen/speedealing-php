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
		'PHPVersion' => 'PHP Version',
		'PHPGD' => 'PHP GD',
		'PHPSupportGD' => 'Тази подкрепа PHP GD графични функции.',
		'PHPCurl' => 'PHP Curl',
		'PHPSupportCurl' => 'This PHP support CURL functions.',
		'PHPMemcached' => 'PHP Mecached',
		'PHPSupportMemcached' => 'This PHP support Memcached functions.',
		'PHPMemoryLimit' => 'PHP memory',
		'PHPMemoryOK' => 'PHP макс сесия памет е на <b>%s.</b> Това трябва да бъде достатъчно.',
		'PHPMemoryTooLow' => 'PHP макс сесия памет е настроен да <b>%s</b> байта. Това трябва да бъде прекалено ниско. Промяна на <b>php.ini</b> да настроите параметър <b>memory_limit</b> най-малко <b>%s</b> байта.',
		'CouchDB' => 'CouchDB',
		'CouchDBVersion' => 'CouchDB version %s',
		'CouchDBProxyPassDescription' => '',
		'ErrorPHPDoesNotSupportGD' => 'Вашият PHP инсталация не поддържа графична функция GD. Не графиката ще бъде на разположение.',
		'ErrorPHPDoesNotSupportCurl' => 'Your PHP installation does not support CURL functions. This is necessary to interact with the database.',
		'ErrorFailedToCreateDatabase' => 'Неуспешно създаване на &quot;%s&quot; база данни.',
		'ErrorFailedToConnectToDatabase' => 'Не можа да се свърже с &quot;база данни&quot; %s.',
		'ErrorDatabaseVersionTooLow' => 'Версия на базата от данни (%s) твърде стар. Версия %s или по-висока.',
		'ErrorPHPVersionTooLow' => 'PHP версия твърде стар. Версия %s се изисква.',
		'ErrorCouchDBVersion' => 'CouchDB version (%s) is too old. Version %s or higher is required.',
		'ErrorCouchDBNotUseProxyPass' => '',
		'WarningPHPVersionTooLow' => 'PHP версия твърде стар. Версия %s или повече се очаква. Тази версия би трябвало да позволи инсталиране, но не се поддържа.',
		'WarningPHPDoesNotSupportMemcached' => 'Your PHP installation does not support Memcached function.',
		'MemcachedDescription' => 'Activer Memcached necessite l\'installation d\'un serveur Memcached et des lib php-memcached ou php-memcache. Il peut être activer après l\'installation.',
		'Reload' => 'Reload',
		'ReloadIsRequired' => 'Reload is required',
		// Config file
		'ConfFileStatus' => 'Config file',
		'ConfFileCreated' => 'Config file created',
		'ConfFileExists' => '<b>%s</b> конфигурационен файл съществува.',
		'ConfFileDoesNotExists' => '<b>%s</b> конфигурационен файл не съществува!',
		'ConfFileDoesNotExistsAndCouldNotBeCreated' => '<b>%s</b> конфигурационен файл не съществува и не може да бъде създадена!',
		'ConfFileIsNotWritable' => '<b>%s</b> конфигурационен файл не се записва. Проверете разрешения. За първи път инсталирате вашия уеб сървър трябва да бъде предоставена, за да могат да пишат в този файл по време на процеса на конфигуриране (&quot;коригира 666&quot; за пример на Unix като операционна система).',
		'ConfFileIsWritable' => '<b>%s</b> конфигурационен файл се записва.',
		'YouMustCreateWithPermission' => 'Трябва да създадете файлове %s и права за писане върху нея за уеб сървъра по време на процеса на инсталиране.',
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
		'URLRoot' => 'URL Root',
		'SpeedealingDatabase' => 'Speedealing Database',
		'ServerAddressDescription' => 'Име или IP адрес на сървъра на базата данни, обикновено &quot;Localhost&quot;, когато сървъра на базата данни се хоства на същия сървър от уеб сървър',
		'ServerPortDescription' => 'База данни на порта на сървъра. Дръжте празна, ако са неизвестни.',
		'DatabaseServer' => 'Сървъра на базата данни',
		'DatabaseName' => 'Име на базата данни',
		'Login' => 'Влез',
		'AdminLogin' => 'Влез за база данни Speedealing собственик.',
		'Password' => 'Парола',
		'AdminPassword' => 'Парола за база данни Speedealing собственик.',
		'SystemIsInstalled' => 'Тази инсталация е завършена.',
		'WithNoSlashAtTheEnd' => 'Без наклонена черта &quot;/&quot; в края',
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