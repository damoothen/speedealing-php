<?php
/* Copyright (C) 2012	Regis Houssin	<regis.houssin@capnetworks.com>
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
		'DomainPassword' => 'ドメインのパスワード',
		'YouMustChangePassNextLogon' => 'ドメイン<b>%s</b>のユーザー<b>%s</b>のパスワードを変更する必要があります。',
		'UserMustChangePassNextLogon' => 'ユーザーは、ドメイン%sにパスワードを変更する必要があります。',
		'LdapUacf_NORMAL_ACCOUNT' => 'ユーザー·アカウント',
		'LdapUacf_DONT_EXPIRE_PASSWORD' => 'パスワードの有効期限が切れることはありません',
		'LdapUacf_ACCOUNTDISABLE' => 'アカウントは、ドメイン%sで無効になっています',
		'LDAPInformationsForThisContact' => 'この連絡先のLDAPデータベース内の情報',
		'LDAPInformationsForThisUser' => 'このユーザーのLDAPデータベース内の情報',
		'LDAPInformationsForThisGroup' => 'このグループのLDAPデータベース内の情報',
		'LDAPInformationsForThisMember' => 'このメンバーのLDAPデータベース内の情報',
		'LDAPAttribute' => 'LDAP属性',
		'LDAPAttributes' => 'LDAP属性',
		'LDAPCard' => 'LDAPカード',
		'LDAPRecordNotFound' => 'レコードは、LDAPデータベースに見つかりませんでした',
		'LDAPUsers' => 'LDAPデータベース内のユーザー',
		'LDAPGroups' => 'LDAPデータベース内のグループ',
		'LDAPFieldStatus' => 'ステータス',
		'LDAPFieldFirstSubscriptionDate' => '最初のサブスクリプションの日付',
		'LDAPFieldFirstSubscriptionAmount' => '最初のサブスクリプションの量',
		'LDAPFieldLastSubscriptionDate' => '最後のサブスクリプションの日付',
		'LDAPFieldLastSubscriptionAmount' => '最後のサブスクリプションの量',
		'SynchronizeSpeedealing2Ldap' => 'Synchronize user (Speedealing -> LDAP)',
		'UserSynchronized' => 'ユーザーの同期',
		'GroupSynchronized' => 'グループは、同期',
		'MemberSynchronized' => 'メンバーは、同期',
		'ContactSynchronized' => '同期に連絡',
		'ForceSynchronize' => 'Speedealingを同期させる力 - &gt; LDAP',
		'ErrorFailedToReadLDAP' => 'LDAPデータベースの読み込みに失敗しました。 LDAPモジュールの設定とデータベースのアクセシビリティをチェックします。'
);
?>