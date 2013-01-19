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
		'DomainPassword' => 'Passord til domene',
		'YouMustChangePassNextLogon' => 'Passordet for bruker <b>%s</b> i domenet <b>%s</b> må endres.',
		'UserMustChangePassNextLogon' => 'Brukeren må endre passord i domenet %s',
		'LdapUacf_NORMAL_ACCOUNT' => 'Brukerkonto',
		'LdapUacf_DONT_EXPIRE_PASSWORD' => 'Passordet utløper aldri',
		'LdapUacf_ACCOUNTDISABLE' => 'Kontoen er deaktivert i domenet %s',
		'LDAPInformationsForThisContact' => 'Informasjon i LDAP-databasen for denne kontakten',
		'LDAPInformationsForThisUser' => 'Informasjon i LDAP-databasen for denne brukeren',
		'LDAPInformationsForThisGroup' => 'Informasjon i LDAP-databasen for denne gruppen',
		'LDAPInformationsForThisMember' => 'Informasjon i LDAP-databasen for dette medlemmet',
		'LDAPAttribute' => 'LDAP-attributt',
		'LDAPAttributes' => 'LDAP-attributter',
		'LDAPCard' => 'LDAP-kort',
		'LDAPRecordNotFound' => 'Fant ikke posten i LDAP-databasen',
		'LDAPUsers' => 'Brukere i LDAP-databasen',
		'LDAPGroups' => 'Grupper i LDAP-databasen',
		'LDAPFieldStatus' => 'Status',
		'LDAPFieldFirstSubscriptionDate' => 'Første abonnementsdato',
		'LDAPFieldFirstSubscriptionAmount' => 'Første abonnementsbeløp',
		'LDAPFieldLastSubscriptionDate' => 'Siste abonnementsdato',
		'LDAPFieldLastSubscriptionAmount' => 'Siste abonnementsbeløp',
		'SynchronizeSpeedealing2Ldap' => 'Synchronize user (Speedealing -> LDAP)',
		'UserSynchronized' => 'Brukeren er synkronisert',
		'GroupSynchronized' => 'Gruppe synkronisert',
		'MemberSynchronized' => 'Medlem synkronisert',
		'ContactSynchronized' => 'Kontakt synkronisert',
		'ForceSynchronize' => 'Tving synkronisering Speedealing -> LDAP',
		'ErrorFailedToReadLDAP' => 'Kunne ikke lese LDAP-databasen. Sjekk LDAP-modulens innstillinger og databasetilgjengelighet.'
);
?>