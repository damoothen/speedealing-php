<?php
/* Copyright (C) 2007-2008 Jeremie Ollivier     <jeremie.o@laposte.net>
 * Copyright (C) 2009-2011 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2011      Juanjo Menent 		<jmenent@2byte.es>
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

// This file initializes more variables to already initialized variables with main.inc.php
// So include of this file must be always done after include to main.inc.php

$conf_db_type = $dolibarr_main_db_type;

// Parametres de connexion a la base
$conf_db_host = $dolibarr_main_db_host;
$conf_db_user = $dolibarr_main_db_user;
$conf_db_pass = $dolibarr_main_db_pass;
$conf_db_base = $dolibarr_main_db_name;

// Identifiant unique correspondant au tiers generique pour la vente
$conf_fksoc = (! empty($_SESSION["CASHDESK_ID_THIRDPARTY"]))?$_SESSION["CASHDESK_ID_THIRDPARTY"]:($conf->global->CASHDESK_ID_THIRDPARTY>0?$conf->global->CASHDESK_ID_THIRDPARTY:0);
// Identifiant unique correspondant a l'entrepot a utiliser
$conf_fkentrepot = (! empty($_SESSION["CASHDESK_ID_WAREHOUSE"]))?$_SESSION["CASHDESK_ID_WAREHOUSE"]:($conf->global->CASHDESK_ID_WAREHOUSE>0?$conf->global->CASHDESK_ID_WAREHOUSE:0);

// Identifiant unique correspondant au compte caisse / liquide
$conf_fkaccount_cash = (! empty($_SESSION["CASHDESK_ID_BANKACCOUNT_CASH"]))?$_SESSION["CASHDESK_ID_BANKACCOUNT_CASH"]:($conf->global->CASHDESK_ID_BANKACCOUNT_CASH>0?$conf->global->CASHDESK_ID_BANKACCOUNT_CASH:0);
// Identifiant unique correspondant au compte cheque
$conf_fkaccount_cheque = (! empty($_SESSION["CASHDESK_ID_BANKACCOUNT_CHEQUE"]))?$_SESSION["CASHDESK_ID_BANKACCOUNT_CHEQUE"]:($conf->global->CASHDESK_ID_BANKACCOUNT_CHEQUE>0?$conf->global->CASHDESK_ID_BANKACCOUNT_CHEQUE:0);
// Identifiant unique correspondant au compte cb
$conf_fkaccount_cb = (! empty($_SESSION["CASHDESK_ID_BANKACCOUNT_CB"]))?$_SESSION["CASHDESK_ID_BANKACCOUNT_CB"]:($conf->global->CASHDESK_ID_BANKACCOUNT_CB>0?$conf->global->CASHDESK_ID_BANKACCOUNT_CB:0);
//var_dump($_SESSION);


// View parameters
$conf_taille_listes = (empty($conf->global->PRODUIT_LIMIT_SIZE)?500:$conf->global->PRODUIT_LIMIT_SIZE);	// Nombre max de lignes a afficher dans les listes
$conf_nbr_car_listes = 60;	// Nombre max de caracteres par ligne dans les listes

// Add hidden option to force decrease of stock whatever is user setup
if (! empty($conf->global->CASHDESK_FORCE_STOCK_ON_BILL)) $conf->global->STOCK_CALCULATE_ON_BILL=1;

?>
