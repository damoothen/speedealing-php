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
		'DomainPassword' => 'Password del dominio',
		'YouMustChangePassNextLogon' => 'La password per l\'utente <b>%s</ b> sul dominio <b>%s</ b> deve essere cambiata.',
		'UserMustChangePassNextLogon' => 'L\'utente deve cambiare password per il dominio %s',
		'LdapUacf_NORMAL_ACCOUNT' => 'Account utente',
		'LdapUacf_DONT_EXPIRE_PASSWORD' => 'Nessuna scadenza password',
		'LdapUacf_ACCOUNTDISABLE' => 'Account disabilitato nel dominio %s',
		'LDAPInformationsForThisContact' => 'Informazioni nel database LDAP per questo contatto',
		'LDAPInformationsForThisUser' => 'Informazioni nel database LDAP per questo utente',
		'LDAPInformationsForThisGroup' => 'Informazioni nel database LDAP per questo gruppo',
		'LDAPInformationsForThisMember' => 'Informazioni nel database LDAP per questo membro',
		'LDAPAttribute' => 'Attributo LDAP',
		'LDAPAttributes' => 'Attributi LDAP',
		'LDAPCard' => 'Scheda LDAP',
		'LDAPRecordNotFound' => 'Il record non è stato trovato in LDAP',
		'LDAPUsers' => 'Utenti nel database LDAP',
		'LDAPGroups' => 'Gruppi in LDAP',
		'LDAPFieldStatus' => 'Stato',
		'LDAPFieldFirstSubscriptionDate' => 'Prima data di sottoscrizione',
		'LDAPFieldFirstSubscriptionAmount' => 'Importo della prima sottoscrizione',
		'LDAPFieldLastSubscriptionDate' => 'Data ultima sottoscrizione',
		'LDAPFieldLastSubscriptionAmount' => 'Importo ultima sottoscrizione',
		'SynchronizeSpeedealing2Ldap' => 'Synchronize user (Speedealing -> LDAP)',
		'UserSynchronized' => 'Utente sincronizzato',
		'GroupSynchronized' => 'Gruppo sincronizzato',
		'MemberSynchronized' => 'Membro sincronizzato',
		'ContactSynchronized' => 'Contatto sincronizzato',
		'ForceSynchronize' => 'Forza la sincronizzazione Dolibarr -> LDAP',
		'ErrorFailedToReadLDAP' => 'Impossibile leggere database LDAP. Controlla la configurazione del modulo di installazione LDAP.'
);
?>