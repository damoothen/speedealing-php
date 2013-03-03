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
		'PHPVersion' => 'Wersja PHP',
		'PHPGD' => 'PHP GD',
		'PHPSupportGD' => 'PHP obsługuje graficzne funkcje GD.',
		'PHPCurl' => 'PHP Curl',
		'PHPSupportCurl' => 'This PHP support CURL functions.',
		'PHPMemcached' => 'PHP Mecached',
		'PHPSupportMemcached' => 'This PHP support Memcached functions.',
		'PHPMemoryLimit' => 'PHP memory',
		'PHPMemoryOK' => 'Maksymalna ilość pamięci sesji PHP to <b>%s</b>. Powinno wystarczyć.',
		'PHPMemoryTooLow' => 'Maksymalna ilość pamięci sesji PHP <b>%s</b> bajtów. To może nie wystarczyć. Zmień w <b>php.ini</b> parametr <b>memory_limit</b> na przynajmniej <b>%s</b> bajtów.',
		'CouchDB' => 'CouchDB',
		'CouchDBVersion' => 'CouchDB version %s',
		'CouchDBProxyPassDescription' => '',
		'ErrorPHPDoesNotSupportGD' => 'Twoja instalacji PHP nie obsługuje funkcji graficznych GD. Nr wykresie będą dostępne.',
		'ErrorPHPDoesNotSupportCurl' => 'Your PHP installation does not support CURL functions. This is necessary to interact with the database.',
		'ErrorFailedToCreateDatabase' => 'Utworzenie bazy danych \'%s\' nie powiodło się.',
		'ErrorFailedToConnectToDatabase' => 'Połączenie z bazą danych \'%s\' nie powiodło się.',
		'ErrorDatabaseVersionTooLow' => 'Database version (%s) too old. Version %s or higher is required.',
		'ErrorPHPVersionTooLow' => 'Wersja PHP zbyt stara. Wymagana wersja to przynajmniej %s.',
		'ErrorCouchDBVersion' => 'CouchDB version (%s) is too old. Version %s or higher is required.',
		'ErrorCouchDBNotUseProxyPass' => '',
		'WarningPHPVersionTooLow' => 'Wersja PHP jest zbyt stara. %s wersji lub więcej oczekuje. Wersja ta powinna umożliwić zainstalowanie ale nie jest obsługiwany.',
		'WarningPHPDoesNotSupportMemcached' => 'Your PHP installation does not support Memcached function.',
		'MemcachedDescription' => 'Activer Memcached necessite l\'installation d\'un serveur Memcached et des lib php-memcached ou php-memcache. Il peut être activer après l\'installation.',
		'Reload' => 'Reload',
		'ReloadIsRequired' => 'Reload is required',
		// Config file
		'ConfFileStatus' => 'Config file',
		'ConfFileCreated' => 'Config file created',
		'ConfFileExists' => 'Plik konfiguracyjny <b>%s</b> istnieje.',
		'ConfFileDoesNotExists' => 'Plik konfiguracyjny <b>%s</b> nie istnieje!',
		'ConfFileDoesNotExistsAndCouldNotBeCreated' => 'Plik konfiguracyjny <b>%s</b> nie istnieje i nie mógł zostać utworzony!',
		'ConfFileIsNotWritable' => 'Plik konfiguracyjny <b>%s</b> nie ma uprawnień do zapisu. Sprawdź uprawnienia. Przy pierwszej instalacji Twój serwer WWW musi posiadać uprawnienia do zapisu tego pliku podczas procesu konfiguracji (Dla systemów uniksowych wystarczy wykonać polecenie "chmod 666").',
		'ConfFileIsWritable' => 'Plik konfiguracyjny <b>%s</b> ma uprawnienia do zapisu.',
		'YouMustCreateWithPermission' => 'Musisz utworzyć plik %s i ustawić mu prawa zapisu dla serwera WWW podczas procesu instalacyjnego.',
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
		'URLRoot' => 'Główny (Root) URL',
		'SpeedealingDatabase' => 'Speedealing Database',
		'ServerAddressDescription' => 'Nazwa lub adres IP serwera baz danych, zazwyczaj \'localhost\' jeśli serwer baz danych znajduje się fizycznie na tej samej maszynie co serwer WWW',
		'ServerPortDescription' => 'Port serwera baz danych. Zostaw puste jeśli nie znasz.',
		'DatabaseServer' => 'Serwer baz danych',
		'DatabaseName' => 'Nazwa bazy danych',
		'Login' => 'Login',
		'AdminLogin' => 'Login do bazy danych. Zostaw puste jeśli korzystasz z połączeń anonimowych',
		'Password' => 'Hasło',
		'AdminPassword' => 'Hasło do bazy danych. Zostaw puste jeśli korzystasz z połączeń anonimowych',
		'SystemIsInstalled' => 'Instalacja zakończona.',
		'WithNoSlashAtTheEnd' => 'Bez znaku ukośnika "/" na końcu',
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