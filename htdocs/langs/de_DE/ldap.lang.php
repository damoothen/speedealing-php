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
		'DomainPassword' => 'Domain-Passwort',
		'YouMustChangePassNextLogon' => 'Bitte ändern Sie das Passwort für Benutzer <b>%s</b> auf der Domain <b>%s</b> bei Ihrer nächsten Anmeldung.',
		'UserMustChangePassNextLogon' => 'Der Benutzer muss das Passwort für Domäne %s bei der nächsten Anmeldung ändern.',
		'LdapUacf_NORMAL_ACCOUNT' => 'Benutzerkonto',
		'LdapUacf_DONT_EXPIRE_PASSWORD' => 'Kennwort läuft nie ab',
		'LdapUacf_ACCOUNTDISABLE' => 'Konto ist deaktiviert für Domäne %s',
		'LDAPInformationsForThisContact' => 'Informationen in der LDAP-Datenbank für diesen Kontakt',
		'LDAPInformationsForThisUser' => 'Informationen in der LDAP-Datenbank für diesen Benutzer',
		'LDAPInformationsForThisGroup' => 'Informationen in der LDAP-Datenbank für diese Gruppe',
		'LDAPInformationsForThisMember' => 'Informationen in der LDAP-Datenbank für dieses Mitglied',
		'LDAPAttribute' => 'LDAP-Attribut',
		'LDAPAttributes' => 'LDAP-Attribute',
		'LDAPCard' => 'LDAP-Karte',
		'LDAPRecordNotFound' => 'LDAP-Datenbankeintrag nicht gefunden',
		'LDAPUsers' => 'Benutzer in LDAP-Datenbank',
		'LDAPGroups' => 'Gruppen in der LDAP-Datenbank',
		'LDAPFieldStatus' => 'Status',
		'LDAPFieldFirstSubscriptionDate' => 'Datum der Erstmitgliedschaft',
		'LDAPFieldFirstSubscriptionAmount' => 'Höhe des ersten Mitgliedsbeitrags',
		'LDAPFieldLastSubscriptionDate' => 'Datum der letzten Mitgliedschaft',
		'LDAPFieldLastSubscriptionAmount' => 'Höhe des letzten Mitgliedsbeitrags',
		'SynchronizeSpeedealing2Ldap' => 'Synchronize user (Speedealing -> LDAP)',
		'UserSynchronized' => 'Benutzer synchronisiert',
		'GroupSynchronized' => 'Gruppe synchronisiert',
		'MemberSynchronized' => 'Mitglied synchronisiert',
		'ContactSynchronized' => 'Kontakt synchronisiert',
		'ForceSynchronize' => 'Erzwinge Synchronisation  Speedealing -> LDAP',
		'ErrorFailedToReadLDAP' => 'Fehler beim Lesen der LDAP-Datenbank. Überprüfen Sie die Verfügbarkeit der Datenbank sowie die entsprechenden Moduleinstellungen.'
);
?>