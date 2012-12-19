<?php
/* Copyright (C) 2007-2008 Jeremie Ollivier      <jeremie.o@laposte.net>
 * Copyright (C) 2008-2009 Laurent Destailleur   <eldy@uers.sourceforge.net>
 * Copyright (C) 2009      Regis Houssin         <regis@dolibarr.fr>
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

/**
 *	\file       htdocs/cashdesk/affContenu.php
 *	\ingroup    cashdesk
 *	\brief      Include to show main page for cashdesk module
 */
require_once 'class/Facturation.class.php';

// Si nouvelle vente, reinitialisation des donnees (destruction de l'objet et vidage de la table contenant la liste des articles)
if ( $_GET['id'] == 'NOUV' )
{
	unset($_SESSION['serObjFacturation']);
	unset($_SESSION['poscart']);
}

// Recuperation, s'il existe, de l'objet contenant les infos de la vente en cours ...
if (isset($_SESSION['serObjFacturation']))
{
	$obj_facturation = unserialize($_SESSION['serObjFacturation']);
	unset($_SESSION['serObjFacturation']);
}
else
{
	// ... sinon, c'est une nouvelle vente
	$obj_facturation = new Facturation();
}

print '<div class="liste_articles">';

require ('tpl/liste_articles.tpl.php');

$obj_facturation->prixTotalHt($lst_total_ht);
$obj_facturation->prixTotalTtc($lst_total_ttc);

print '</div>';

print '<div class="principal">';

if ( $_GET['menu'] )
{
	include $_GET['menu'].'.php';
}
else
{
	include 'facturation.php';
}

print '</div>';

$_SESSION['serObjFacturation'] = serialize($obj_facturation);

?>