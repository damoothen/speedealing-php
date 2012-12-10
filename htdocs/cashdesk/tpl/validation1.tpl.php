<?php
/* Copyright (C) 2007-2008	Jeremie Ollivier	<jeremie.o@laposte.net>
 * Copyright (C) 2011		Juanjo Menent		<jmenent@2byte.es>
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
 *
 */

$langs->load("main");

?>

<fieldset class="cadre_facturation"><legend class="titre1"><?php echo $langs->trans("Summary"); ?></legend>

	<table class="table_resume">

		<tr><td class="resume_label"><?php echo $langs->trans("Invoice"); ?></td><td><?php  echo $obj_facturation->numInvoice(); ?></td></tr>
		<tr><td class="resume_label"><?php echo $langs->trans("TotalHT"); ?></td><td><?php echo price2num($obj_facturation->prixTotalHt(),'MT').' '.$conf->currency; ?></td></tr>
		<?php
			// Affichage de la tva par taux
			if ( $obj_facturation->montantTva() ) {

				echo ('<tr><td class="resume_label">'.$langs->trans("VAT").'</td><td>'.price2num($obj_facturation->montantTva(),'MT').' '.$conf->currency.'</td></tr>');

			}
			else
			{

				echo ('<tr><td class="resume_label">'.$langs->trans("VAT").'</td><td>'.$langs->trans("NoVAT").'</td></tr>');

			}
		?>
		<tr><td class="resume_label"><?php echo $langs->trans("TotalTTC"); ?> </td><td><?php echo price2num($obj_facturation->prixTotalTtc(),'MT').' '.$conf->currency; ?></td></tr>
		<tr><td class="resume_label"><?php echo $langs->trans("PaymentMode"); ?> </td><td>
		<?php
		switch ($obj_facturation->getSetPaymentMode())
		{
			case 'ESP':
				echo $langs->trans("Cash");
				$filtre='courant=2';
				if (!empty($_SESSION["CASHDESK_ID_BANKACCOUNT_CASH"]))
					$selected = $_SESSION["CASHDESK_ID_BANKACCOUNT_CASH"];
				break;
			case 'CB':
				echo $langs->trans("CreditCard");
				$filtre='courant=1';
				if (!empty($_SESSION["CASHDESK_ID_BANKACCOUNT_CB"]))
					$selected = $_SESSION["CASHDESK_ID_BANKACCOUNT_CB"];
				break;
			case 'CHQ':
				echo $langs->trans("Cheque");
				$filtre='courant=1';
				if (!empty($_SESSION["CASHDESK_ID_BANKACCOUNT_CHEQUE"]))
					$selected = $_SESSION["CASHDESK_ID_BANKACCOUNT_CHEQUE"];
				break;
			case 'DIF':
				echo $langs->trans("Reported");
				$filtre='courant=1 OR courant=2';
				$selected='';
				break;
			default:
				$filtre='courant=1 OR courant=2';
				$selected='';
		}

		?>
		</td></tr>

		<?php
			// Affichage des infos en fonction du mode de paiement
			if ( $obj_facturation->getsetPaymentMode() == 'DIF' ) {

				echo ('<tr><td class="resume_label">'.$langs->trans("DateEcheance").'</td><td>'.$obj_facturation->paiementLe().'</td></tr>');

			} else {

				echo ('<tr><td class="resume_label">'.$langs->trans("Received").'</td><td>'.price2num($obj_facturation->montantEncaisse(),'MT').' '.$conf->currency.'</td></tr>');

			}

			// Affichage du montant rendu (reglement en especes)
			if ( $obj_facturation->montantRendu() ) {

				echo ('<tr><td class="resume_label">'.$langs->trans("Change").'</td><td>'.price2num($obj_facturation->montantRendu(),'MT').' '.$conf->currency.'</td></tr>');

			}

		?>

	</table>

	<form id="frmValidation" class="formulaire2" method="post" action="validation_verif.php?action=valide_facture">
		<input type="hidden" name="token" value="<?php echo $_SESSION['newtoken']; ?>" />
		<p class="note_label">
			<?php
				echo $langs->trans("BankToPay"). "<br>";
				$form = new Form($db);
				$form->select_comptes($selected,'cashdeskbank',0,$filtre);
			?>
		</p>
		<p class="note_label"><?php echo $langs->trans("Notes"); ?><br><textarea class="textarea_note" name="txtaNotes"></textarea></p>

		<span><input class="bouton_validation" type="submit" name="btnValider" value="<?php echo $langs->trans("ValidateInvoice"); ?>" /></span>
		<p><a class="lien1" href="affIndex.php?menu=facturation"><?php echo $langs->trans("RestartSelling"); ?></a></p>
	</form>



</fieldset>
