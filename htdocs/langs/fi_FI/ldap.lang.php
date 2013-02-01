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
		'DomainPassword' => 'Salasana verkkotunnuksen',
		'YouMustChangePassNextLogon' => 'Salasana <b>käyttäjän %s verkkotunnuksen %s</b> on muuttunut.',
		'UserMustChangePassNextLogon' => 'Käyttäjän on muutettava salasana verkkotunnuksen %s',
		'LdapUacf_NORMAL_ACCOUNT' => 'User account',
		'LdapUacf_DONT_EXPIRE_PASSWORD' => 'Salasana ei vanhene',
		'LdapUacf_ACCOUNTDISABLE' => 'Tili on poistettu käytöstä verkkotunnuksen %s',
		'LDAPInformationsForThisContact' => 'Tiedot LDAP-tietokannan tämä yhteystieto',
		'LDAPInformationsForThisUser' => 'Tiedot LDAP-tietokannan tämän käyttäjän',
		'LDAPInformationsForThisGroup' => 'Tiedot LDAP-tietokannan tähän ryhmään',
		'LDAPInformationsForThisMember' => 'Tiedot LDAP-tietokannan tämä jäsen',
		'LDAPAttribute' => 'LDAP-attribuutti',
		'LDAPAttributes' => 'LDAP-attribuutteja',
		'LDAPCard' => 'LDAP-kortti',
		'LDAPRecordNotFound' => 'Kirjataan ei löydy LDAP-tietokanta',
		'LDAPUsers' => 'Käyttäjät LDAP-tietokanta',
		'LDAPGroups' => 'Ryhmät LDAP-tietokanta',
		'LDAPFieldStatus' => 'Tila',
		'LDAPFieldFirstSubscriptionDate' => 'Ensimmäisen tilauksen päivämäärä',
		'LDAPFieldFirstSubscriptionAmount' => 'Fist merkinnän määrästä',
		'LDAPFieldLastSubscriptionDate' => 'Viimeisin tilaus päivämäärän',
		'LDAPFieldLastSubscriptionAmount' => 'Viimeisin tilaus määrä',
		'SynchronizeSpeedealing2Ldap' => 'Synchronize user (Speedealing -> LDAP)',
		'UserSynchronized' => 'Käyttäjän synkronoidaan',
		'GroupSynchronized' => 'Ryhmän synkronoidaan',
		'MemberSynchronized' => 'Jäsen synkronoidaan',
		'ContactSynchronized' => 'Yhteystiedot synkronoidaan',
		'ForceSynchronize' => 'Force synkronointi Speedealing -> LDAP',
		'ErrorFailedToReadLDAP' => 'Lukeminen epäonnistui LDAP-tietokantaan. Tarkista LDAP-moduulin asennus ja tietokannan saavutettavuus.'
);
?>