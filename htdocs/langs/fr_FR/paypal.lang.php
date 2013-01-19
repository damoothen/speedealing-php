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

$paypal = array(
		'CHARSET' => 'UTF-8',
		'PaypalSetup' => 'Configuration module PayPal',
		'PaypalDesc' => 'Ce module permet d\'offrir une page de paiement via le prestataire <a href="http://www.paypal.com" target="_blank">Paypal</a> pour réaliser un paiement quelconque ou un paiement par rapport à un objet Speedealing (factures, commande...)',
		'PaypalOrCBDoPayment' => 'Poursuivre le paiement par carte ou par Paypal',
		'PaypalDoPayment' => 'Poursuivre le paiement par Paypal',
		'PaypalCBDoPayment' => 'Poursuivre le paiement par carte',
		'PAYPAL_API_SANDBOX' => 'Mode test/bac à sable (sandbox)',
		'PAYPAL_API_USER' => 'Nom utilisateur API',
		'PAYPAL_API_PASSWORD' => 'Mot de passe utilisateur API',
		'PAYPAL_API_SIGNATURE' => 'Signature API',
		'PAYPAL_API_INTEGRAL_OR_PAYPALONLY' => 'Proposer le paiement intégral (Carte+Paypal) ou Paypal seul',
		'PAYPAL_CSS_URL' => 'Url optionnelle de la feuille de style CSS de la page de paiement',
		'ThisIsTransactionId' => 'Voici l\'identifiant de la transaction: <b>%s</b>',
		'PAYPAL_ADD_PAYMENT_URL' => 'Ajouter l\'url de paiement Paypal lors de l\'envoi d\'un document par mail',
		'PAYPAL_IPN_MAIL_ADDRESS' => 'Adresse e-mail pour les notifications instantanées de paiement (IPN)',
		'PredefinedMailContentLink' => 'Vous pouvez cliquer sur le lien sécurisé ci-dessous pour effectuer votre paiement via Paypal\n\n%s\n\n',
		'YouAreCurrentlyInSandboxMode' => 'Vous êtes actuellement dans le mode "sandbox"'
);
?>