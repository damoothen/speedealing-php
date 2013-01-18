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
		'DomainPassword' => 'Mot de passe du domaine',
		'YouMustChangePassNextLogon' => 'Le mot de passe de <b>%s</b> sur le domaine <b>%s</b> doit être modifié.',
		'UserMustChangePassNextLogon' => 'L\'utilisateur doit modifier son mot de passe sur le domaine %s',
		'LdapUacf_NORMAL_ACCOUNT' => 'Compte utilisateur',
		'LdapUacf_DONT_EXPIRE_PASSWORD' => 'Le mot de passe n\'expire jamais',
		'LdapUacf_ACCOUNTDISABLE' => 'Le compte est désactivé sur le domaine %s',
		'LDAPInformationsForThisContact' => 'Informations en base LDAP pour ce contact',
		'LDAPInformationsForThisUser' => 'Informations en base LDAP pour cet utilisateur',
		'LDAPInformationsForThisGroup' => 'Informations en base LDAP pour ce groupe',
		'LDAPInformationsForThisMember' => 'Informations en base LDAP pour ce membre',
		'LDAPAttribute' => 'Attribut LDAP',
		'LDAPAttributes' => 'Attributs LDAP',
		'LDAPCard' => 'Fiche LDAP',
		'LDAPRecordNotFound' => 'Enregistrement non trouvé dans la base LDAP',
		'LDAPUsers' => 'Utilisateurs en base LDAP',
		'LDAPGroups' => 'Groupes en base LDAP',
		'LDAPFieldStatus' => 'Statut',
		'LDAPFieldFirstSubscriptionDate' => 'Date première adhésion',
		'LDAPFieldFirstSubscriptionAmount' => 'Montant première adhésion',
		'LDAPFieldLastSubscriptionDate' => 'Date dernière adhésion',
		'LDAPFieldLastSubscriptionAmount' => 'Montant dernière adhésion',
		'SynchronizeSpeedealing2Ldap' => 'Synchronize user (Speedealing -> LDAP)',
		'UserSynchronized' => 'Utilisateur synchronisé',
		'GroupSynchronized' => 'Groupe synchronisé',
		'MemberSynchronized' => 'Adhérent synchronisé',
		'ContactSynchronized' => 'Contact synchronisé',
		'ForceSynchronize' => 'Forcer synchro Dolibarr -> LDAP',
		'ErrorFailedToReadLDAP' => 'Échec de la lecture de l\'annuaire LDAP. Vérifier la configuration du module LDAP et l\'accessibilité de l\'annuaire.'
);
?>