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
		'SynchronizeSpeedealing2Ldap' => 'Synchronize user (Speedealing -> LDAP)',
		'UserSynchronized' => 'Пользователь синхронизирован',
		'GroupSynchronized' => 'Группа синхронизирована',
		'MemberSynchronized' => 'Участник синхронизирован',
		'ContactSynchronized' => 'Контакт синхронизирован',
		'ForceSynchronize' => 'Принудительная синхронизация Speedealing -> LDAP',
		'ErrorFailedToReadLDAP' => 'Не удалось прочитать базу данных LDAP. Проверьте настройку модуля LDAP  и доступность базы данных.'
);
?>