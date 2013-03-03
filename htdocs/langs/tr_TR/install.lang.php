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
		'PHPVersion' => 'PHP Sürümü',
		'PHPGD' => 'PHP GD',
		'PHPSupportGD' => 'Bu PHP GD grafik işlevlerini destekliyor.',
		'PHPCurl' => 'PHP Curl',
		'PHPSupportCurl' => 'This PHP support CURL functions.',
		'PHPMemcached' => 'PHP Mecached',
		'PHPSupportMemcached' => 'This PHP support Memcached functions.',
		'PHPMemoryLimit' => 'PHP memory',
		'PHPMemoryOK' => 'PHP nizin ençok oturum belleği <b>%s</b> olarak ayarlanmış. Bu yeterli olacaktır.',
		'PHPMemoryTooLow' => 'PHP nizin ençok oturum belleği <b>%s</b> bayt olarak ayarlanmış. Bu çok düşük olabilir. <b>php.ini</b> dosyanızdaki <b>memory_limit</b> parametresi ayarını enaz <b>%s</b> bayt olacak şekilde değiştirin.',
		'CouchDB' => 'CouchDB',
		'CouchDBVersion' => 'CouchDB version %s',
		'CouchDBProxyPassDescription' => '',
		'ErrorPHPDoesNotSupportGD' => 'PHP kurulumunuz GD grafik işlevini desteklemiyor. Hiçbir grafik görüntülenemeyecektir.',
		'ErrorPHPDoesNotSupportCurl' => 'Your PHP installation does not support CURL functions. This is necessary to interact with the database.',
		'ErrorFailedToCreateDatabase' => 'Veritabanı \'%s\' oluşturulamadı.',
		'ErrorFailedToConnectToDatabase' => 'Veritabanı \'%s\' e bağlanılamadı.',
		'ErrorDatabaseVersionTooLow' => 'Database version (%s) too old. Version %s or higher is required.',
		'ErrorPHPVersionTooLow' => 'PHP sürümü çok eski. %s Sürümü gereklidir.',
		'ErrorCouchDBVersion' => 'CouchDB version (%s) is too old. Version %s or higher is required.',
		'ErrorCouchDBNotUseProxyPass' => '',
		'WarningPHPVersionTooLow' => 'PHP sürümü çok eski. %s Sürümü ya da daha yükseği gerekiyor. Bu sürüm kuruluma izin verir, ancak desteklenmemektedir.',
		'WarningPHPDoesNotSupportMemcached' => 'Your PHP installation does not support Memcached function.',
		'MemcachedDescription' => 'Activer Memcached necessite l\'installation d\'un serveur Memcached et des lib php-memcached ou php-memcache. Il peut être activer après l\'installation.',
		'Reload' => 'Reload',
		'ReloadIsRequired' => 'Reload is required',
		// Config file
		'ConfFileStatus' => 'Config file',
		'ConfFileCreated' => 'Config file created',
		'ConfFileExists' => 'Yapılandırma dosyası <b>%s</b> var.',
		'ConfFileDoesNotExists' => 'Yapılandırma dosyası <b>%s</b> yok',
		'ConfFileDoesNotExistsAndCouldNotBeCreated' => 'Yapılandırma dosyası <b>%s</b> yoktur ve oluşturulamıyor!',
		'ConfFileIsNotWritable' => 'Yapılandırma dosyası <b>%s</b> yazılabilir değil. Yetkileri kontrol edin. İlk yüklemede web sunucusuna yapılandırma işlemi sırasında bu dosyaya yazabilme hakkının verilmiş olması gerekir ( "örneğin, chmod 666, bir Unix işletim sistemindeki gibi).',
		'ConfFileIsWritable' => 'Yapılandırma dosyası <b>%s</b>  yazılabilir.',
		'YouMustCreateWithPermission' => '%s Dosyasını oluşturmanız ve kurulum sırasında web sunucusunda yazma izinlerini ayarlamanız gerekir.',
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
		'URLRoot' => 'Kök URL',
		'SpeedealingDatabase' => 'Speedealing Database',
		'ServerAddressDescription' => 'Veritabanı sunucusunun adı ya da ip’ si, veritabanı sunucusunun, web tarayıcısı ile aynı sunucuda barındırıldığı durumlarda genellikle \'localhost\' olur',
		'ServerPortDescription' => 'Veritabanı sunucusu bağlantı noktası. Eğer bilinmiyorsa boş tutun.',
		'DatabaseServer' => 'Veritabanı sunucusu',
		'DatabaseName' => 'Veritabanı adı',
		'Login' => 'Giriş',
		'AdminLogin' => 'Speedealing veritabanı sahibi girişi.',
		'Password' => 'Parola',
		'AdminPassword' => 'Speedealing veritabanı sahibi parolası.',
		'SystemIsInstalled' => 'Bu kurulum tamamlandı.',
		'WithNoSlashAtTheEnd' => 'Sonunda taksim olmadan "/"',
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