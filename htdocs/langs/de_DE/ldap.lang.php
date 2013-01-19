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