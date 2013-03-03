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
		'PHPVersion' => 'PHPのバージョン',
		'PHPGD' => 'PHP GD',
		'PHPSupportGD' => 'このPHPのサポートGDグラフィック機能。',
		'PHPCurl' => 'PHP Curl',
		'PHPSupportCurl' => 'This PHP support CURL functions.',
		'PHPMemcached' => 'PHP Mecached',
		'PHPSupportMemcached' => 'This PHP support Memcached functions.',
		'PHPMemoryLimit' => 'PHP memory',
		'PHPMemoryOK' => 'あなたのPHPの最大のセッションメモリは<b>%s</b>に設定されています。これは十分なはずです。',
		'PHPMemoryTooLow' => 'あなたのPHPの最大セッション·メモリーが<b>%s</b>バイトに設定されています。これはあまりにも低くする必要があります。少なくとも<b>%s</b>バイト<b>にmemory_limit</b>パラメータを設定するには、php.ini <b>を</b>変更してください。',
		'CouchDB' => 'CouchDB',
		'CouchDBVersion' => 'CouchDB version %s',
		'CouchDBProxyPassDescription' => '',
		'ErrorPHPDoesNotSupportGD' => 'PHPのインストールはグラフィカル関数GDをサポートしていません。ないグラフは利用できません。',
		'ErrorPHPDoesNotSupportCurl' => 'Your PHP installation does not support CURL functions. This is necessary to interact with the database.',
		'ErrorFailedToCreateDatabase' => 'データベース %s を作成できませんでした。',
		'ErrorFailedToConnectToDatabase' => 'データベース %s への接続に失敗しました。',
		'ErrorDatabaseVersionTooLow' => 'Database version (%s) too old. Version %s or higher is required.',
		'ErrorPHPVersionTooLow' => 'あまりにも古いPHPバージョン。バージョン%sが必要です。',
		'ErrorCouchDBVersion' => 'CouchDB version (%s) is too old. Version %s or higher is required.',
		'ErrorCouchDBNotUseProxyPass' => '',
		'WarningPHPVersionTooLow' => 'あまりにも古いPHPバージョン。バージョンの%s以上が期待されている。このバージョンは、インストール許可する必要がありますが、サポートされていません。',
		'WarningPHPDoesNotSupportMemcached' => 'Your PHP installation does not support Memcached function.',
		'MemcachedDescription' => 'Activer Memcached necessite l\'installation d\'un serveur Memcached et des lib php-memcached ou php-memcache. Il peut être activer après l\'installation.',
		'Reload' => 'Reload',
		'ReloadIsRequired' => 'Reload is required',
		// Config file
		'ConfFileStatus' => 'Config file',
		'ConfFileCreated' => 'Config file created',
		'ConfFileExists' => 'コンフィギュレーションファイル<b>%sが</b>存在し<b>ています</b> 。',
		'ConfFileDoesNotExists' => 'コンフィギュレーションファイル<b>%sは</b>存在しません！',
		'ConfFileDoesNotExistsAndCouldNotBeCreated' => 'コンフィギュレーションファイル<b>%sが</b>存在しないと作成できませんでした！',
		'ConfFileIsNotWritable' => '設定ファイルの<b>%sが</b>書き込み可能<b>では</b>ありません。権限を確認してください。最初のインストールについては、Webサーバーは、（OSのようなUnix上の例のために&quot;chmod 666&quot;）のコンフィギュレーションプロセス中にこのファイルに書き込むことができるように付与する必要があります。',
		'ConfFileIsWritable' => 'コンフィギュレーションファイルの<b>%sは</b>書き込み可能です。',
		'YouMustCreateWithPermission' => 'あなたは、ファイル%sを作成し、インストールプロセス中にWebサーバのためにそれへの書き込み権限を設定する必要があります。',
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
		'URLRoot' => 'URLのルート',
		'SpeedealingDatabase' => 'Speedealing Database',
		'ServerAddressDescription' => '通常、データベースサーバー、データベースサーバーがWebサーバーよりも、同じサーバー上でホストされている \'localhost\'のための名前またはIPアドレス',
		'ServerPortDescription' => 'データベース·サーバーのポート。不明の場合は、空の保管してください。',
		'DatabaseServer' => 'データベース·サーバー',
		'DatabaseName' => 'データベース名',
		'Login' => 'ログイン',
		'AdminLogin' => 'Speedealingデータベース所有者のログイン。',
		'Password' => 'パスワード',
		'AdminPassword' => 'Speedealingデータベースの所有者のパスワード。',
		'SystemIsInstalled' => 'このインストールは完了です。',
		'WithNoSlashAtTheEnd' => '末尾のスラッシュ&quot;/&quot;なし',
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