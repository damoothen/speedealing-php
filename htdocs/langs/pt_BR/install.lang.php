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
		'PHPVersion' => 'Versão PHP',
		'PHPGD' => 'PHP GD',
		'PHPSupportGD' => 'Este suporte PHP GD gráfica funções.',
		'PHPCurl' => 'PHP Curl',
		'PHPSupportCurl' => 'This PHP support CURL functions.',
		'PHPMemcached' => 'PHP Mecached',
		'PHPSupportMemcached' => 'This PHP support Memcached functions.',
		'PHPMemoryLimit' => 'PHP memory',
		'PHPMemoryOK' => 'Seu PHP max sessão memória está definido <b>para %s.</b> Isto deve ser suficiente.',
		'PHPMemoryTooLow' => 'Seu PHP max sessão memória está definido <b>para %s</b> bytes. Isto deve ser muito baixo. Alterar o seu <b>php.inem memory_limit</b> para definir parâmetro para pelo <b>menos %s</b> bytes.',
		'CouchDB' => 'CouchDB',
		'CouchDBVersion' => 'CouchDB version %s',
		'CouchDBProxyPassDescription' => '',
		'ErrorPHPDoesNotSupportGD' => 'Sua instalação PHP não suporta gráficos função GD. Não gráfico estarão disponíveis.',
		'ErrorPHPDoesNotSupportCurl' => 'Your PHP installation does not support CURL functions. This is necessary to interact with the database.',
		'ErrorFailedToCreateDatabase' => 'Erro ao criar a base de dados\' %s\'.',
		'ErrorFailedToConnectToDatabase' => 'Falha ao conectar com o banco de dados\' %s\'.',
		'ErrorDatabaseVersionTooLow' => 'Database version (%s) too old. Version %s or higher is required.',
		'ErrorPHPVersionTooLow' => 'PHP versão muito antiga. Versão %s é requerida.',
		'ErrorCouchDBVersion' => 'CouchDB version (%s) is too old. Version %s or higher is required.',
		'ErrorCouchDBNotUseProxyPass' => '',
		'WarningPHPVersionTooLow' => 'PHP version too old. Version %s or more is expected. This version should allow install but is not supported.',
		'WarningPHPDoesNotSupportMemcached' => 'Your PHP installation does not support Memcached function.',
		'MemcachedDescription' => 'Activer Memcached necessite l\'installation d\'un serveur Memcached et des lib php-memcached ou php-memcache. Il peut être activer après l\'installation.',
		'Reload' => 'Reload',
		'ReloadIsRequired' => 'Reload is required',
		// Config file
		'ConfFileStatus' => 'Config file',
		'ConfFileCreated' => 'Config file created',
		'ConfFileExists' => 'O arquivo de configuração <b>conf.php</b> existe.',
		'ConfFileDoesNotExists' => 'Arquivo de <b>configuração %s</b> não existe!',
		'ConfFileDoesNotExistsAndCouldNotBeCreated' => 'Arquivo de <b>configuração %s</b> não existe e não poderia ser criado!',
		'ConfFileIsNotWritable' => 'O arquivo de configuração <b>conf.php</b> não é passível de escrita, verifique as permissões sff, o seu servidor web tem de ter permissões de escrita neste arquivo cem Linux, chmod 666).',
		'ConfFileIsWritable' => 'O arquivo de configuração <b>conf.php</b> tem as permissões corretas.',
		'YouMustCreateWithPermission' => 'Você deve criar o arquivo %s e definir permissões escrever sobre ele para instalar o servidor web durante o processo.',
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
		'URLRoot' => 'URL de raiz',
		'SpeedealingDatabase' => 'Speedealing Database',
		'ServerAddressDescription' => 'Nome ou endereço IP para o servidor de dados, normalmente \'localhost\' ao banco de dados está hospedado no mesmo servidor que servidor web',
		'ServerPortDescription' => 'Database server port. Mantenha vazio se desconhecido.',
		'DatabaseServer' => 'Database server',
		'DatabaseName' => 'Nome da base de dados',
		'Login' => 'Login',
		'AdminLogin' => 'Login para o administrador da base de dados Speedealing. Deixar em branco se a conexão é feita com anônimo',
		'Password' => 'Password',
		'AdminPassword' => 'Password para o administrador da base de dados Speedealing. Deixar em branco se a conexão é feita com anônimo',
		'SystemIsInstalled' => 'Instalação completa.',
		'WithNoSlashAtTheEnd' => 'Sem a barra "/" no final',
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