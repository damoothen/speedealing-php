<?php
/* Copyright (C) 2007-2008 Jeremie Ollivier    <jeremie.o@laposte.net>
 * Copyright (C) 2011      Laurent Destailleur <eldy@users.sourceforge.net>
 * Copyright (C) 2012      Marcos García       <marcosgdf@gmail.com>
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
include_once DOL_DOCUMENT_ROOT.'/compta/facture/class/facture.class.php';

$langs->load("main");
$langs->load('cashdesk');
header("Content-type: text/html; charset=".$conf->file->character_set_client);

$facid=GETPOST('facid','int');
$object=new Facture($db);
$object->fetch($facid);

?>
<html>
<head>
<title><?php echo $langs->trans('PrintTicket') ?></title>

<style type="text/css">
body {
	font-size: 1.5em;
	position: relative;
}

.entete { /* 		position: relative; */

}

.address { /* 			float: left; */
	font-size: 12px;
}

.date_heure {
	position: absolute;
	top: 0;
	right: 0;
	font-size: 16px;
}

.infos {
	position: relative;
}

.liste_articles {
	width: 100%;
	border-bottom: 1px solid #000;
	text-align: center;
}

.liste_articles tr.titres th {
	border-bottom: 1px solid #000;
}

.liste_articles td.total {
	text-align: right;
}

.totaux {
	margin-top: 20px;
	width: 30%;
	float: right;
	text-align: right;
}

.lien {
	position: absolute;
	top: 0;
	left: 0;
	display: none;
}

@media print {
	.lien {
		display: none;
	}
}
</style>

</head>

<body>

<div class="entete">
<div class="logo"><?php print '<img src="'.DOL_URL_ROOT.'/viewimage.php?modulepart=companylogo&amp;file='.urlencode('/thumbs/'.$mysoc->logo_small).'">'; ?>
</div>
<div class="infos">
<p class="address"><?php echo $mysoc->name; ?><br>
<?php print dol_nl2br(dol_format_address($mysoc)); ?><br>
</p>

<p class="date_heure"><?php
// Recuperation et affichage de la date et de l'heure
$now = dol_now();
print dol_print_date($now,'dayhourtext').'<br>';
print $object->ref;
?></p>
</div>
</div>

<br>

<table class="liste_articles">
	<tr class="titres">
		<th><?php print $langs->trans("Code"); ?></th>
		<th><?php print $langs->trans("Label"); ?></th>
		<th><?php print $langs->trans("Qty"); ?></th>
		<th><?php print $langs->trans("Discount").' (%)'; ?></th>
		<th><?php print $langs->trans("TotalHT"); ?></th>
	</tr>

	<?php

	$tab=array();
    $tab = $_SESSION['poscart'];

    $tab_size=count($tab);
    for($i=0;$i < $tab_size;$i++)
    {
        $remise = $tab[$i]['remise'];
        echo ('<tr><td>'.$tab[$i]['ref'].'</td><td>'.$tab[$i]['label'].'</td><td>'.$tab[$i]['qte'].'</td><td>'.$tab[$i]['remise_percent'].'</td><td class="total">'.price2num($tab[$i]['total_ht'],'MT').' '.$conf->currency.'</td></tr>'."\n");
    }

	?>
</table>

<table class="totaux">
<?php
echo '<tr><th nowrap="nowrap">'.$langs->trans("TotalHT").'</th><td nowrap="nowrap">'.price2num($obj_facturation->prixTotalHt(),'MT')." ".$conf->currency."</td></tr>\n";
echo '<tr><th nowrap="nowrap">'.$langs->trans("TotalVAT").'</th><td nowrap="nowrap">'.price2num($obj_facturation->montantTva(),'MT')." ".$conf->currency."</td></tr>\n";
echo '<tr><th nowrap="nowrap">'.$langs->trans("TotalTTC").'</th><td nowrap="nowrap">'.price2num($obj_facturation->prixTotalTtc(),'MT')." ".$conf->currency."</td></tr>\n";
?>
</table>

<script type="text/javascript">
	window.print();
</script>

<a class="lien" href="#"
	onclick="javascript: window.close(); return(false);"><?php echo $langs->trans("Close"); ?></a>

</body>