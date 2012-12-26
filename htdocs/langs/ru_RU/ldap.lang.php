<?php
/* Copyright (C) 2012	Regis Houssin	<regis@dolibarr.fr>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

$ldap = array(
		'CHARSET' => 'UTF-8',
		'DomainPassword' => 'Пароль домена',
		'YouMustChangePassNextLogon' => 'Пароль <b>пользователя %s</b> на <b>домене %s</b> должен быть изменен.',
		'UserMustChangePassNextLogon' => 'Пользователь должен изменить пароль на домене %s',
		'LdapUacf_NORMAL_ACCOUNT' => 'Учетная запись пользователя',
		'LdapUacf_DONT_EXPIRE_PASSWORD' => 'Солк действия пароля не ограничен',
		'LdapUacf_ACCOUNTDISABLE' => 'Аккаунт отключен в домене %s',
		'LDAPInformationsForThisContact' => 'Информация в базе данных LDAP для этого контакта',
		'LDAPInformationsForThisUser' => 'Информация в базе данных LDAP для этого пользователя',
		'LDAPInformationsForThisGroup' => 'Информация в базе данных LDAP для этой группы',
		'LDAPInformationsForThisMember' => 'Информация в базе данных LDAP для этого участника',
		'LDAPAttribute' => 'Атрибут LDAP',
		'LDAPAttributes' => 'Атрибуты LDAP',
		'LDAPCard' => 'Карточка LDAP',
		'LDAPRecordNotFound' => 'Запись в базе данных LDAP не найдена',
		'LDAPUsers' => 'Пользователи в базе данных LDAP',
		'LDAPGroups' => 'Группы в базе данных LDAP',
		'LDAPFieldStatus' => 'Статус',
		'LDAPFieldFirstSubscriptionDate' => 'Дата первой подписки',
		'LDAPFieldFirstSubscriptionAmount' => 'Размер первой подписки',
		'LDAPFieldLastSubscriptionDate' => 'Дата последней подписки',
		'LDAPFieldLastSubscriptionAmount' => 'Размер последней подписки',
		'SynchronizeDolibarr2Ldap' => 'Синхронизация пользователя (Dolibarr -> LDAP)',
		'UserSynchronized' => 'Пользователь синхронизирован',
		'ForceSynchronize' => 'Принудительная синхронизация Dolibarr -> LDAP',
		'ErrorFailedToReadLDAP' => 'Не удалось прочитать базу данных LDAP. Проверьте настройку модуля LDAP  и доступность базы данных.',
		'GroupSynchronized' => 'Группа синхронизирована',
		'MemberSynchronized' => 'Участник синхронизирован',
		'ContactSynchronized' => 'Контакт синхронизирован',
);
?>