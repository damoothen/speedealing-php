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
		'DomainPassword' => 'Senha do Domínio',
		'YouMustChangePassNextLogon' => 'A senha de <b>%s</b> ao domínio <b>%s</b> deve de ser modificada.',
		'UserMustChangePassNextLogon' => 'O usuário deve alterar de senha na próxima login',
		'LdapUacf_NORMAL_ACCOUNT' => 'Conta de Usuário',
		'LdapUacf_DONT_EXPIRE_PASSWORD' => 'A senha não caduca',
		'LdapUacf_ACCOUNTDISABLE' => 'A conta está desativada ao domínio',
		'LDAPInformationsForThisContact' => 'Informação da base de dados LDAP deste contato',
		'LDAPInformationsForThisUser' => 'Informação da base de dados LDAP deste usuário',
		'LDAPInformationsForThisGroup' => 'Informação da base de dados LDAP deste grupo',
		'LDAPInformationsForThisMember' => 'Informação da base de dados LDAP deste membro',
		'LDAPAttribute' => 'Atributo LDAP',
		'LDAPAttributes' => 'Atributos LDAP',
		'LDAPCard' => 'Ficha LDAP',
		'LDAPRecordNotFound' => 'Registo não encontrado na base de dados LDAP',
		'LDAPUsers' => 'Usuário na base de dados LDAP',
		'LDAPGroups' => 'Grupos na base de dados LDAP',
		'LDAPFieldStatus' => 'Estatuto',
		'LDAPFieldFirstSubscriptionDate' => 'Data primeira adesão',
		'LDAPFieldFirstSubscriptionAmount' => 'Valor da Primeira Adesão',
		'LDAPFieldLastSubscriptionDate' => 'Data da Última Adesão',
		'LDAPFieldLastSubscriptionAmount' => 'Valor Última Adesão',
		'SynchronizeDolibarr2Ldap' => 'Sincronizar usuário (Dolibarr -> LDAP)',
		'UserSynchronized' => 'Usuário Sincronizado',
		'GroupSynchronized' => 'Group synchronized',
		'MemberSynchronized' => 'Member synchronized',
		'ContactSynchronized' => 'Contact synchronized',
		'ForceSynchronize' => 'forçar sincronização Dolibarr -> LDAP',
		'ErrorFailedToReadLDAP' => 'Erro na leitura do anuário LDAP. Verificar a configuração do módulo LDAP e a acessibilidade do anuário.'
);
?>