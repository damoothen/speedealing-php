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
		'DomainPassword' => 'Jelszó a domain-hez',
		'YouMustChangePassNextLogon' => '<b>%s</b> felhasználó jelszavát a <b>%s</b> domain-ben meg kell változtatni.',
		'UserMustChangePassNextLogon' => '%s domainben a felhasználónak meg kell változtatnia a jelszavát.',
		'LdapUacf_NORMAL_ACCOUNT' => 'Felhasználói fiók',
		'LdapUacf_DONT_EXPIRE_PASSWORD' => 'A jelszó sosem jár le',
		'LdapUacf_ACCOUNTDISABLE' => '%s domainben a fiók deaktiválva van',
		'LDAPInformationsForThisContact' => 'Információ a kapcsolatról az LDAP adatbázisban',
		'LDAPInformationsForThisUser' => 'Információ a felhasználóról az LDAP adatbázisban',
		'LDAPInformationsForThisGroup' => 'Információ a csoportról az LDAP adatbázisban',
		'LDAPInformationsForThisMember' => 'Információ a tagról az LDAP adatbázisban',
		'LDAPAttribute' => 'LDAP attributum',
		'LDAPAttributes' => 'LDAP attributumok',
		'LDAPCard' => 'LDAP kártya',
		'LDAPRecordNotFound' => 'Rekord nem található az LDAP adatbázisban',
		'LDAPUsers' => 'Felhasználók az LDAP adatbázisban',
		'LDAPGroups' => 'Csoportok az LDAP adatbázisban',
		'LDAPFieldStatus' => 'Állapot',
		'LDAPFieldFirstSubscriptionDate' => 'Első feliratkozási dátum',
		'LDAPFieldFirstSubscriptionAmount' => 'Első feliratkozási mennyiség',
		'LDAPFieldLastSubscriptionDate' => 'Utolsó feliratkozási dátum',
		'LDAPFieldLastSubscriptionAmount' => 'Utolsó feliratkozási mennyiség',
		'SynchronizeSpeedealing2Ldap' => 'Synchronize user (Speedealing -> LDAP)',
		'UserSynchronized' => 'Felhasználó szinkronizálva',
		'GroupSynchronized' => 'Csoport szinkronizálva',
		'MemberSynchronized' => 'Tag szinkronizálva',
		'ContactSynchronized' => 'Kapcsolat szinkronizálva',
		'ForceSynchronize' => 'Eröltetett szinkronizáció Speedealing -> LDAP',
		'ErrorFailedToReadLDAP' => 'Nem sikerült olvasni az LDAP adatbázist. Ellenőrizze az LDAP modul beállítását és az adatbázis hozzáférhetőségét.'
);
?>