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
		'DomainPassword' => 'Etki alanı parolası',
		'YouMustChangePassNextLogon' => '<b>%s</b> Etki alanındaki <b>%s</b> kullanıcısının parolası değiştirilmelidir.',
		'UserMustChangePassNextLogon' => 'Kullanıcı, %s etki alanındaki  parolasını değiştirmelidir',
		'LdapUacf_NORMAL_ACCOUNT' => 'Kullanıcı hesabı',
		'LdapUacf_DONT_EXPIRE_PASSWORD' => 'Parola süresi asla sona ermez',
		'LdapUacf_ACCOUNTDISABLE' => 'Hesap, %s etki alanında engellidir',
		'LDAPInformationsForThisContact' => 'Bu kişi için LDAP veritabanındaki bilgi',
		'LDAPInformationsForThisUser' => 'Bu kullanıcı için LDAP veritabanındaki bilgi',
		'LDAPInformationsForThisGroup' => 'Bu grup için LDAP veritabanındaki bilgi',
		'LDAPInformationsForThisMember' => 'Bu üye için LDAP veritabanındaki bilgi',
		'LDAPAttribute' => 'LDAP özniteliği',
		'LDAPAttributes' => 'LDAP öznitelikleri',
		'LDAPCard' => 'LDAP kartı',
		'LDAPRecordNotFound' => 'Kayıt LDAP veritabanında bulunamadı',
		'LDAPUsers' => 'LDAP veritabanındaki kullanıcılar',
		'LDAPGroups' => 'LDAP veritabanındaki fruplar',
		'LDAPFieldStatus' => 'Durum',
		'LDAPFieldFirstSubscriptionDate' => 'İlk abonelik tarihi',
		'LDAPFieldFirstSubscriptionAmount' => 'İlk abonelik tutarı',
		'LDAPFieldLastSubscriptionDate' => 'Son abonelik tarihi',
		'LDAPFieldLastSubscriptionAmount' => 'Son abonelik tutarı',
		'SynchronizeDolibarr2Ldap' => 'Senkronize kullanıcı (Dolibarr -> LDAP)',
		'UserSynchronized' => 'Kullanıcı senkronize edildi',
		'GroupSynchronized' => 'Grup senkronize edildi',
		'MemberSynchronized' => 'Üye senkronize edildi',
		'ContactSynchronized' => 'Kişi senkronize edildi',
		'ForceSynchronize' => 'Dolibarr -> LDAP senkronizyona zorla',
		'ErrorFailedToReadLDAP' => 'LDAP veritabanı okunamadı. LDAP modülü kurulumunu ve veritabanı erişilebilirliğini denetleyin.',
);
?>