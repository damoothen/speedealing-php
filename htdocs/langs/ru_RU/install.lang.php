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
		'PHPSupportGD' => 'Эта поддержка PHP GD графические функции.',
		'PHPCurl' => 'PHP Curl',
		'PHPSupportCurl' => 'This PHP support CURL functions.',
		'PHPMemcached' => 'PHP Mecached',
		'PHPSupportMemcached' => 'This PHP support Memcached functions.',
		'PHPMemoryLimit' => 'PHP memory',
		'PHPMemoryOK' => 'Ваш PHP макс сессии памяти установлен <b>в %s.</b> Это должно быть достаточно.',
		'PHPMemoryTooLow' => 'Ваш PHP макс сессии памяти установлен <b>в %s</b> байт. Это должно быть слишком низким. Измените свой <b>php.ini</b> установить параметр <b>memory_limit,</b> по крайней <b>мере %s</b> байт.',
		'CouchDB' => 'CouchDB',
		'CouchDBVersion' => 'CouchDB version %s',
		'CouchDBProxyPassDescription' => '',
		'ErrorPHPDoesNotSupportGD' => 'Ваш PHP установки не поддерживает графические функции GD. Нет графике будет иметься в наличии.',
		'ErrorPHPDoesNotSupportCurl' => 'Your PHP installation does not support CURL functions. This is necessary to interact with the database.',
		'ErrorFailedToCreateDatabase' => 'Не удается создать базу данных \' %s\'.',
		'ErrorFailedToConnectToDatabase' => 'Не удалось подключиться к базе данных \' %s\'.',
		'ErrorDatabaseVersionTooLow' => 'Database version (%s) too old. Version %s or higher is required.',
		'ErrorPHPVersionTooLow' => 'PHP версии слишком стар. Версия %s обязательна.',
		'ErrorCouchDBVersion' => 'CouchDB version (%s) is too old. Version %s or higher is required.',
		'ErrorCouchDBNotUseProxyPass' => '',
		'WarningPHPVersionTooLow' => 'PHP версии слишком стар. Версия %s или более не ожидается. Эта версия должна позволить установить, но не поддерживается.',
		'WarningPHPDoesNotSupportMemcached' => 'Your PHP installation does not support Memcached function.',
		'MemcachedDescription' => 'Activer Memcached necessite l\'installation d\'un serveur Memcached et des lib php-memcached ou php-memcache. Il peut être activer après l\'installation.',
		'Reload' => 'Reload',
		'ReloadIsRequired' => 'Reload is required',
		// Config file
		'ConfFileStatus' => 'Config file',
		'ConfFileCreated' => 'Config file created',
		'ConfFileExists' => 'Файл <b>конфигурации %s</b> существует.',
		'ConfFileDoesNotExists' => 'Файл <b>конфигурации %s</b> не существует!',
		'ConfFileDoesNotExistsAndCouldNotBeCreated' => 'Файл <b>конфигурации %s</b> не существует и не может быть создан!',
		'ConfFileIsNotWritable' => 'Файл <b>конфигурации %s</b> не для записи. Проверка разрешений. Для первой установки, Ваш веб-сервер должен быть предоставлен чтобы иметь возможность писать в этом файле конфигурации во время процесса ( "Chmod 666", например, Unix подобные ОС).',
		'ConfFileIsWritable' => 'Файл <b>конфигурации %s</b> на запись.',
		'YouMustCreateWithPermission' => 'Вы должны создать файл %s и установить запись по этому вопросу для веб-сервера во время установки.',
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
		'URLRoot' => 'URL Корневого',
		'SpeedealingDatabase' => 'Speedealing Database',
		'ServerAddressDescription' => 'Имя или IP-адрес сервера баз данных, как правило, \'локальный\', когда сервер базы данных размещается на одном сервере, чем веб-сервер',
		'ServerPortDescription' => 'База данных сервера порт. Держите пустым, если неизвестно.',
		'DatabaseServer' => 'Сервер базы данных',
		'DatabaseName' => 'Название базы данных',
		'Login' => 'Войти',
		'AdminLogin' => 'Логин Speedealing для администратора базы данных. Держите пустым, если вы подключаетесь в анонимном',
		'Password' => 'Пароль',
		'AdminPassword' => 'Пароль Speedealing для администратора базы данных. Держите пустым, если вы подключаетесь в анонимном',
		'SystemIsInstalled' => 'Эта установка будет завершена.',
		'WithNoSlashAtTheEnd' => 'Без черту "/" в конце',
		'ServerPortCouchdbDescription' => 'Port du serveur. Défaut 5984.',
		'ServerAddressCouchdbDescription' => 'Nom FQDN du serveur de base de données, \'localhost.localdomain\' quand le serveur est installé sur la même machine que le serveur web',
		'DatabaseCouchdbUserDescription' => 'Login du super administrateur ayant tous les droits sur le serveur CouchDB ou l\'administrateur propriétaire de la base si la base et son compte d\'accès existent déjà (comme lorsque vous êtes chez un hébergeur).<br><br><div class="alert-box info">Cet utilisateur/mot de passe sera l\'administrateur pour se connecter à Speedealing.</div>',
		'ServerAddressMemcachedDesc' => 'Nom ou adresse ip du serveur memcached, généralement \'localhost\' quand le serveur est installé sur la même machine que le serveur web',
		'ServerPortMemcachedDesc' => 'Port du serveur memcached. Défaut : 11211',
		'FailedToCreateAdminLogin' => 'Echec de la création du compte administrateur Speedealing.',
		// Upgrade
		'UpgradeOk' => 'Обновление прошло успешно',
		'NewInstalledVersion' => 'Ваша новая версия %s',
		'NeedUpgrade' => 'Новая версия Speedealing',
		'WarningUpgrade' => 'Установленная версия %s, вы должны обновиться до версии %s. <br> Пожалуйста, обратитесь к администратору.'
);
?>