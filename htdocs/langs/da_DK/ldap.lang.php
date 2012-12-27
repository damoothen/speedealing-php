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
		'DomainPassword' => 'Password for domæne',
		'YouMustChangePassNextLogon' => 'Adgangskode for <b>bruger %s</b> på det <b>domæne %s</b> skal ændres.',
		'UserMustChangePassNextLogon' => 'Brugeren skal skifte adgangskode på domænet %s',
		'LdapUacf_NORMAL_ACCOUNT' => 'Brugerkonto',
		'LdapUacf_DONT_EXPIRE_PASSWORD' => 'Password aldrig udløber',
		'LdapUacf_ACCOUNTDISABLE' => 'Kontoen er deaktiveret i domænet %s',
		'LDAPInformationsForThisContact' => 'Oplysninger i LDAP database for denne kontakt',
		'LDAPInformationsForThisUser' => 'Oplysninger i LDAP database for denne bruger',
		'LDAPInformationsForThisGroup' => 'Oplysninger i LDAP database for denne gruppe',
		'LDAPInformationsForThisMember' => 'Oplysninger i LDAP database for dette medlem',
		'LDAPAttribute' => 'LDAP-attribut',
		'LDAPAttributes' => 'LDAP attributter',
		'LDAPCard' => 'LDAP-kort',
		'LDAPRecordNotFound' => 'Optag ikke findes i LDAP database',
		'LDAPUsers' => 'Brugere i LDAP database',
		'LDAPGroups' => 'Grupper i LDAP database',
		'LDAPFieldStatus' => 'Status',
		'LDAPFieldFirstSubscriptionDate' => 'Første abonnement dato',
		'LDAPFieldFirstSubscriptionAmount' => 'Fist abonnement beløb',
		'LDAPFieldLastSubscriptionDate' => 'Seneste abonnement dato',
		'LDAPFieldLastSubscriptionAmount' => 'Seneste abonnement beløb',
		'SynchronizeDolibarr2Ldap' => 'Synkronisering bruger (Dolibarr -> LDAP)',
		'UserSynchronized' => 'Bruger synkroniseres',
		'GroupSynchronized' => 'Gruppen synkroniseres',
		'MemberSynchronized' => 'Medlem synkroniseres',
		'ContactSynchronized' => 'Kontakt synkroniseres',
		'ForceSynchronize' => 'Force synkronisering Dolibarr -> LDAP',
		'ErrorFailedToReadLDAP' => 'Kunne ikke læse LDAP database. Check LDAP modul opsætning og database tilgængelighed.'
);
?>