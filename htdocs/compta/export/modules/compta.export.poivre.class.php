<?PHP
/* Copyright (C) 2004-2007 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2006 Laurent Destailleur  <eldy@users.sourceforge.net>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 *
 * $Id: compta.export.poivre.class.php,v 1.19 2007/01/17 11:24:57 rodolphe Exp $
 * $Source: /cvsroot/dolibarr/dolibarr/htdocs/compta/export/modules/compta.export.poivre.class.php,v $
 */

/**
   \file       htdocs/compta/export/modules/compta.export.poivre.class.php
   \ingroup    compta
   \brief      Modele d'export compta poivre, export au format tableur
   \remarks    Ce fichier doit etre utilise comme un exemple, il est specifique a une utilisation particuliere
   \version    $Revision: 1.19 $
*/

require_once(PHP_WRITEEXCEL_PATH."/class.writeexcel_workbook.inc.php");
require_once(PHP_WRITEEXCEL_PATH."/class.writeexcel_worksheet.inc.php");

/**
   \class      ComptaExportPoivre
   \brief      Classe permettant les exports comptables au format tableur
*/

class ComptaExportPoivre extends ComptaExport
{
    var $db;
    var $user;

  /**
     \brief      Constructeur de la class
     \param      DB          Object de base de données
     \param      USER        Object utilisateur
  */
    function ComptaExportPoivre ($DB, $USER)
    {
        $this->db = $DB;
        $this->user = $USER;
    }

  /**
   * Agrégation des lignes de facture
   */
    function Agregate($line_in)
    {
        dolibarr_syslog("ComptaExportPoivre::Agregate");
        dolibarr_syslog("ComptaExportPoivre::Agregate " . sizeof($line_in) . " lignes en entrées");
        $i = 0;
        $j = 0;
        $n = sizeof($line_in);

        // On commence par la ligne 0

        $this->linefact[$j] = $line_in[$i];

        //print "$j ".$this->linefact[$j][8] . "<br>";

        for ( $i = 1 ; $i < $n ; $i++)
        {
            // On agrége les lignes avec le méme code comptable

            //if ( ($line_in[$i][1] == $line_in[$i-1][1]) && ($line_in[$i][4] == $line_in[$i-1][4]) )
            //{
            //$this->linefact[$j][8] = ($this->linefact[$j][8] + $line_in[$i][8]);
            //}
            //else
            //{
            $j++;
            $this->linefact[$j] = $line_in[$i];
            // }
        }

        dolibarr_syslog("ComptaExportPoivre::Agregate " . sizeof($this->linefact) . " lignes en sorties");

        return 0;
    }

  /*
   *
   */
    function ExportJournalVente($dir, $linefact, $id=0)
    {
        dolibarr_syslog("Entré dans ExportJournalVente ");

        $error = 0;

        dolibarr_syslog("ComptaExportPoivre::Export");
        dolibarr_syslog("ComptaExportPoivre::Export " . sizeof($linec) . " lignes en entrées");

        //$this->Agregate($linec);

        $this->db->begin();

        if ($id == 0)
        {
            $dt = strftime('EC%y%m', time());

            $sql = "SELECT count(ref) FROM ".MAIN_DB_PREFIX."export_compta";
            $sql .= " WHERE ref like '$dt%'";

            if ($this->db->query($sql))
            {
                $row = $this->db->fetch_row();
                $cc = $row[0];
            }
            else
            {
                $error++;
                dolibarr_syslog("ComptaExportPoivre::Export Erreur Select");
            }


            if (!$error)
            {
                $this->ref = $dt . substr("000".$cc, -2);

                $sql = "INSERT INTO ".MAIN_DB_PREFIX."export_compta (ref, date_export, fk_user)";
                $sql .= " VALUES ('".$this->ref."', now(),".$this->user->id.")";

                if ($this->db->query($sql))
                {
                    $this->id = $this->db->last_insert_id(MAIN_DB_PREFIX."export_compta");
                }
                else
                {
                    $error++;
                    dolibarr_syslog("ComptaExportPoivre::Export Erreur INSERT");
                }
            }
        }
        else
        {
            $this->id = $id;

            $sql = "SELECT ref FROM ".MAIN_DB_PREFIX."export_compta";
            $sql .= " WHERE rowid = ".$this->id;

            $resql = $this->db->query($sql);

            if ($resql)
            {
                $row = $this->db->fetch_row($resql);
                $this->ref = $row[0];
            }
            else
            {
                $error++;
                dolibarr_syslog("ComptaExportPoivre::Export Erreur Select");
            }
        }


        if (!$error)
        {
            dolibarr_syslog("ComptaExportPoivre::Export ref : ".$this->ref);

            $fxname = $dir . "/".$this->ref.".xls";
            dolibarr_syslog("ComptaExportPoivre::name : ".$fxname);
            $workbook = new writeexcel_workbook($fxname);

            $page = $workbook->addworksheet('Export');

            $page->set_column(0,0,8); // A
            $page->set_column(1,1,6); // B
            $page->set_column(2,2,9); // C
            $page->set_column(3,3,14); // D
            $page->set_column(4,4,44); // E
            $page->set_column(5,5,9); // F Numéro de piéce
            $page->set_column(6,6,8); // G


            // Pour les factures

            // A 0 Date Opération
            // B 1 Journal
            // C 2 Compte
            // D 3 Numero de piéce
            // E 4 Libellé
            // F 5 Débit
            // G 6 Credit
            // H 7 Monnaie

            // Pour les paiements

            $i = 0;
            $this->j = 0;
            $n = sizeof($linefact);
            //Permet de savoir quand on change de facture
            $oldfacture = 0;

            // Libellé des colonnes
            $page->write_string($this->j, 0, "Date");
            $page->write_string($this->j, 1, "Journal");
            $page->write_string($this->j, 2, "compte");
            $page->write_string($this->j, 3, "Numéro de piéce");
            $page->write_string($this->j, 4, "Libellé");
            $page->write_string($this->j, 5, "Débit");
            $page->write_string($this->j, 6, "Crédit");
            $page->write_string($this->j, 7, "Monnaie");

            $this->j++;

            //Recuperation de chaque ligne de facture
            for ( $i = 0 ; $i < $n ; $i++)
            {

                if ( $oldfacture <> $linefact[$i][1])
                {

                    $page->write_string($this->j, 0, strftime("%d/%m/%Y",$linefact[$i][0]));
                    $page->write_string($this->j, 1, "VTE");
                    $page->write_string($this->j, 2, $linefact[$i][2]); // Code Comptable
                    $page->write_string($this->j, 3, round(substr($linefact[$i][5],7,strlen($linefact[$i][5]))));
                    $page->write_string($this->j, 4, $linefact[$i][2]." ".$linefact[$i][5]);

                    if($linefact[$i][12] <> 2 )
                    {
                        $page->write_number($this->j, 5, price2num($linefact[$i][7]));
                        $page->write_number($this->j, 6, price2num(0));
                    }
                    else {
                        $page->write_number($this->j, 5, price2num(0));
                        $page->write_number($this->j, 6, abs(price2num($linefact[$i][7])));
                    }
                    $page->write_string($this->j, 7, 'E'); // E euro
                    $this->j++;
                    $oldfacture = $linefact[$i][1];
                }
                $tva_product = "0";

                if($linefact[$i][11] == NULL ){
                    $code_compta_vente = "70600000";
                }else {
                    $code_compta_vente = $linefact[$i][11];
                }
                if($linefact[$i][14] == "0"){
                    $tva_product = "4457100000" ;
                }else {
                    $tva_product = "4457400000";
                }
                $page->write_string($this->j, 0, strftime("%d/%m/%Y",$linefact[$i][0]));
                $page->write_string($this->j, 1, 'VTE');
                $page->write_string($this->j, 2, $code_compta_vente); // Code Comptable
                $page->write_string($this->j, 3, round(substr($linefact[$i][5],7,strlen($linefact[$i][5]))));
                $page->write_string($this->j, 4, $linefact[$i][2]." ".$linefact[$i][5]);

                //Si la facture est un avoir ou si la ligne est negative
                if($linefact[$i][12] == 2 || $linefact[$i][13] < 0 )
                {
                    $page->write_number($this->j, 5, abs(price2num($linefact[$i][13]))); // Numéro de facture
                    $page->write_number($this->j, 6, price2num(0)); // Montant de TVA
                }
                else {

                    $page->write_number($this->j, 5, price2num(0)); // Numéro de facture
                    $page->write_number($this->j, 6, price2num($linefact[$i][13])); // Montant de TVA
                }

                $page->write_string($this->j, 7, 'E');// E euro

                $this->j++;

                //ligne TVA
                $page->write_string($this->j, 0, strftime("%d/%m/%Y",$linefact[$i][0]));
                $page->write_string($this->j, 1, "VTE");
                $page->write_string($this->j, 2, $tva_product); // Code Comptable
                $page->write_string($this->j, 3, round(substr($linefact[$i][5],7,strlen($linefact[$i][5]))));
                $page->write_string($this->j, 4, $linefact[$i][2]." ".$linefact[$i][5]);
                //Si la facture est un avoir ou si la ligne est negative
                if($linefact[$i][12] == 2 || $linefact[$i][15] < 0)
                {
                    $page->write_number($this->j, 5, abs(price2num($linefact[$i][15])));// Montant de TVA
                    $page->write_number($this->j, 6,price2num(0)); // Montant de TVA
                }
                else {
                    $page->write_number($this->j, 5, price2num(0)); //Montant de TVA
                    $page->write_number($this->j, 6, price2num($linefact[$i][15]));

                }
                $page->write_string($this->j, 7, 'E'); // E euro

                $this->j++;

            }
            $workbook->close();
            // Tag des lignes de factures
            $n = sizeof($linefact);
            dolibarr_syslog("Taille linefact ".$n);

            for ( $i = 0 ; $i < $n ; $i++)
            {
                $sql = "UPDATE ".MAIN_DB_PREFIX."facturedet";
                $sql .= " SET fk_export_compta=".$this->id ;//.", fk_code_ventilation = ".$this->id;
                $sql .= " WHERE rowid = ".$linefact[$i][10];

                if (!$this->db->query($sql))
                {
                    $error++;
                }
            }

        }
    }

    function ExportJournalReglement($dir, $linep, $linech, $linevir, $lineod, $id=0) {

        // A 0 Date Opération
        // B 1 Journal
        // C 2 Compte
        // D 3 Numero de piéce
        // E 4 Libellé
        // F 5 Débit
        // G 6 Credit
        // H 7 Monnaie

        $error = 0;

        dolibarr_syslog("ComptaExportPoivre::Export reglement");
        dolibarr_syslog("ComptaExportPoivre::Export reglement" . sizeof($linep) . " lignes en entrées");

        //$this->Agregate($linep);

        $this->db->begin();

        if ($id == 0)
        {
            $dt = strftime('REG%y%m', time());

            $sql = "SELECT count(ref) FROM ".MAIN_DB_PREFIX."export_compta";
            $sql .= " WHERE ref like '$dt%'";

            $resql = $this->db->query($sql);
            if ($resql)
            {
                $row = $this->db->fetch_row($resql);
                $cc = $row[0];
            }
            else
            {
                $error++;
                dolibarr_syslog("ComptaExportPoivre::Export Erreur Select");
            }


            if (!$error)
            {
                $this->ref = $dt . substr("000".$cc, -2);

                $sql = "INSERT INTO ".MAIN_DB_PREFIX."export_compta (ref, date_export, fk_user)";
                $sql .= " VALUES ('".$this->ref."', now(),".$this->user->id.")";

                if ($this->db->query($sql))
                {
                    $this->id = $this->db->last_insert_id(MAIN_DB_PREFIX."export_compta");
                }
                else
                {
                    $error++;
                    dolibarr_syslog("ComptaExportPoivre::Export Erreur INSERT");
                }
            }
        }
        else
        {
            $this->id = $id;

            $sql = "SELECT ref FROM ".MAIN_DB_PREFIX."export_compta";
            $sql .= " WHERE rowid = ".$this->id;

            $resql = $this->db->query($sql);

            if ($resql)
            {
                $row = $this->db->fetch_row($resql);
                $this->ref = $row[0];
            }
            else
            {
                $error++;
                dolibarr_syslog("ComptaExportPoivre::Export Erreur Select");
            }
        }

        if (!$error)
        {
            dolibarr_syslog("ComptaExportPoivre::Export ref : ".$this->ref);

            $fxname = $dir . "/".$this->ref.".xls";
            dolibarr_syslog("ComptaExportPoivre::name : ".$fxname);
            $workbook = new writeexcel_workbook($fxname);

            $page = $workbook->addworksheet('Export');

            $page->set_column(0,0,8); // A
            $page->set_column(1,1,6); // B
            $page->set_column(2,2,9); // C
            $page->set_column(3,3,14); // D
            $page->set_column(4,4,44); // E
            $page->set_column(5,5,9); // F Numéro de piéce
            $page->set_column(6,6,8); // G

            $i = 0;
            $this->j = 0;
            $n = sizeof($linep);

            $oldfacture = 0;

            // Libellé des colonnes
            $page->write_string($this->j, 0, "Date");//strftime("%d%m%y",$this->linefact[$i][0]));
            $page->write_string($this->j, 1,  "Journal");
            $page->write_string($this->j, 2,  "compte");
            $page->write_string($this->j, 3,"Numéro de piéce");//stripslashes($this->linefact[$i][2]));
            $page->write_string($this->j, 4, "Libellé");//stripslashes($this->linefact[$i][3])." Facture");
            $page->write_string($this->j, 5, "Débit");//$this->linefact[$i][5]); // Numéro de factur
            $page->write_string($this->j, 6, "Crédit");//price2num($this->linefact[$i][7]));
            $page->write_string($this->j, 7, "Monnaie");//'Debit' ); // D pour débit

            $this->j++;

            $this->CreationLignesPrelevements($page, $linep);
            
            // Tag des lignes de factures
            $n = sizeof($linep);
            dolibarr_syslog("ComptaExportPoivre::Export nombre de lignes de paiement : ".$n);
            for ( $i = 0 ; $i < $n ; $i++)
            {
                $sql = "UPDATE ".MAIN_DB_PREFIX."paiement";
                $sql .= " SET fk_export_compta=".$this->id;
                $sql .= " WHERE rowid = ".$linep[$i][1];

                if (!$this->db->query($sql))
                {
                    $error++;
                }
            }

            $this->CreationLignesCheques($page, $linech);

            // Tag des lignes de paiement
            $n = sizeof($linech);
            dolibarr_syslog("ComptaExportPoivre::Export nombre de lignes de paiement : ".$n);
            for ( $i = 0 ; $i < $n ; $i++)
            {
                $sql = "UPDATE ".MAIN_DB_PREFIX."paiement";
                $sql .= " SET fk_export_compta=".$this->id;
                $sql .= " WHERE rowid = ".$linech[$i][1];

                if (!$this->db->query($sql))
                {
                    $error++;
                }
            }

            $this->CreationLignesVirements($page, $linevir);

            // Tag des lignes de paiement
            $n = sizeof($linevir);
            dolibarr_syslog("ComptaExportPoivre::Export nombre de lignes de paiement : ".$n);
            for ( $i = 0 ; $i < $n ; $i++)
            {
                $sql = "UPDATE ".MAIN_DB_PREFIX."paiement";
                $sql .= " SET fk_export_compta=".$this->id;
                $sql .= " WHERE rowid = ".$linevir[$i][1];

                if (!$this->db->query($sql))
                {
                    $error++;
                }
            }

            //Mise en place des lignes d'OD
            $this->CreationLignesOD($page, $lineod);

            $workbook->close();

            if (!$error)
            {
                $this->db->commit();
                dolibarr_syslog("ComptaExportPoivre::Export reglement COMMIT");
            }
            else
            {
                $this->db->rollback();
                dolibarr_syslog("ComptaExportPoivre::Export reglement ROLLBACK");
            }

            return 0;

        }
    }
    function CreationLignesPrelevements($page, $linep)
    {
            $bordereau = 0;
            $n = sizeof($linep);
            for ( $i = 0 ; $i < $n ; $i++)
            {
                if($bordereau <> $linep[$i][10] && $bordereau <> 0 )
                {
                    $page->write_string($this->j,0, strftime("%d/%m/%Y",$linep[$i-1][0]));
                    $page->write_string($this->j,1, 'BQ2');
                    $page->write_string($this->j,2,'51212000000');
                    $page->write_string($this->j,3, "AVP_".$linep[$i-1][10]);
                    $page->write_string($this->j,4, "AVP ".strftime("%m/%Y",$linep[$i-1][11])); //
                    $page->write_number($this->j,5, $linep[$i-1][12]);     // Montant de la ligne
                    $page->write_number($this->j,6, 0);  // Montant de la ligne
                    $page->write_string($this->j,7, "E");

                    $bordereau= $linep[$i][10];
                    $this->j++;
                }
                if($bordereau == $linep[$i][10] || $bordereau == 0)
                {
                    $debit = 0;
                    $credit = 0;

                    if($linep[$i][5] >=0 )
                    {
                        $credit = $linep[$i][5];
                    }else {
                        $debit = abs($linep[$i][5]);
                    }
                    $page->write_string($this->j,0, strftime("%d/%m/%Y",$linep[$i][0]));
                    $page->write_string($this->j,1, 'BQ2');
                    $page->write_string($this->j,2, $linep[$i][2]);

                    if($linep[$i][9]== "Rejet") {
                        $page->write_string($this->j,3, "AVP");
                    }
                    else {
                        $page->write_string($this->j,3, "AVP_".$linep[$i][10]);
                    }
                    $page->write_string($this->j,4, $linep[$i][4]); //
                    $page->write_number($this->j,5, $debit);     // Montant de la ligne
                    $page->write_number($this->j,6, $credit);  // Montant de la ligne
                    $page->write_string($this->j,7, "E");

                    $this->j++;
                    if($i == $n-1 ||$linep[$i][9]== "Rejet" ){

                        $page->write_string($this->j,0, strftime("%d/%m/%Y",$linep[$i][0]));
                        $page->write_string($this->j,1, 'BQ2');
                        $page->write_string($this->j,2, '51212000000');

                        //Si c'est une ligne de rejet
                        if($linep[$i][9]== "Rejet"){
                            $page->write_number($this->j,5, 0);
                            $page->write_number($this->j,6, abs($linep[$i][5]));
                            $page->write_string($this->j,3, "AVP");
                            $page->write_string($this->j,4, "Rejet AVP ".strftime("%m/%Y",$linep[$i][0]));
                        }else {
                            $page->write_number($this->j,5, $linep[$i][12]);
                            $page->write_number($this->j,6, 0);
                            $page->write_string($this->j,3, "AVP_".$linep[$i][10]);
                            $page->write_string($this->j,4, "AVP ".strftime("%m/%Y",$linep[$i][11]));

                        }
                        // Montant de la ligne
                        $page->write_string($this->j,7, "E");
                        $this->j++;
                    }
                    $bordereau= $linep[$i][10];

                }
            }
    }


    /*
     * \brief Function permettant de creer les lignes de reglements par cheque
     * \param j position de la ligne du fichier excel
     * \param page objet excel permettant de rajouter dees lignes dans le fichier
     * \param linech tableau avec les lignes de paiements par cheque
     */
    function CreationLignesCheques( $page, $linech)
    {
        $bordereau = 0;
        $nch = sizeof($linech);
        $i = 0;
        for($i =0 ; $i < $nch ; $i++)
        {
            dolibarr_syslog("Numero de bordereau ".$linech[$i][11]);

            if($bordereau <> $linech[$i][11] && $bordereau <> 0 )
            {

                $page->write_string($this->j,0, strftime("%d/%m/%Y",$linech[$i-1][12]));

                if($linech[$i-1][13] == 1) {
                    $banqueid = "BQ2"; //512120
                    $bankAccount = "51212000000";
                }else {
                    $banqueid = "BQ1";//512110
                    $bankAccount = "51211000000";
                }

                $page->write_string($this->j,1, $banqueid);
                $page->write_string($this->j,2,$bankAccount );
                $page->write_string($this->j,3, "REMCHQ");
                $page->write_string($this->j,4, "REMCHQ"."_B".$linech[$i-1][11]." ".strftime("%m/%Y",$linech[$i-1][12])); //
                $page->write_number($this->j,5, $linech[$i-1][14]);     // Numéro de facture
                $page->write_number($this->j,6, 0);  // Montant de la ligne
                $page->write_string($this->j,7, "E");
                $page->write_string($this->j,8,$linech[$i-1][11] );
                $bordereau = $linech[$i][11];
                $this->j++;
            }
            if($bordereau == $linech[$i][11] || $bordereau == 0)
            {
                //Traitement specifique avec le tableau linech pour avoir la banque et les bordereaux
                $page->write_string($this->j,0, strftime("%d/%m/%Y",$linech[$i][0]));
                if($linech[$i][10] == 1) {
                    $banqueid = "BQ2";
                }else {
                    $banqueid = "BQ1";
                }

                if($linech[$i][5] >=0 )
                {
                    $credit = $linech[$i][5];
                }else {
                    $debit = abs($linech[$i][5]);
                }

                $page->write_string($this->j,1, $banqueid);
                $page->write_string($this->j,2,$linech[$i][2] );
                $page->write_string($this->j,3, "CHQ".$linech[$i][9]."_B".$linech[$i][11]);
                $page->write_string($this->j,4, $linech[$i][2]." ".$linech[$i][4]); //
                $page->write_number($this->j,5, $debit);    
                $page->write_number($this->j,6, $credit);  
                $page->write_string($this->j,7, "E");
                $page->write_string($this->j,8,$linech[$i][11] );

                $this->j++;//On change de ligne


                if($i == $nch-1)
                {
                    dolibarr_syslog("Affichage derniere ligne CHQ");

                    $page->write_string($this->j,0, strftime("%d/%m/%Y",$linech[$i][12]));

                    if($linech[$i][13] == 1) {
                        $banqueid = "BQ2";
                        $bankAccount = "51212000000";
                    }else {
                        $banqueid = "BQ1";
                        $bankAccount = "51211000000";
                    }

                    $page->write_string($this->j,1, $banqueid);
                    $page->write_string($this->j,2, $bankAccount );
                    $page->write_string($this->j,3, "REMCHQ");
                    $page->write_string($this->j,4, "REMCHQ_B".$linech[$i][11]." ".strftime("%m/%Y",$linech[$i][12])); //
                    $page->write_number($this->j,5, $linech[$i][14]);     // Numéro de facture
                    $page->write_number($this->j,6, 0);  // Montant de la ligne
                    $page->write_string($this->j,7, "E");
                    $page->write_string($this->j,8,$linech[$i][11] );
                    $this->j++;//on change de ligne
                }

                $bordereau = $linech[$i][11];
            }
        }

    }

    /*
     * \brief Function permettant de creer les lignes de reglements par cheque
     * \param j position de la ligne du fichier excel
     * \param page objet excel permettant de rajouter dees lignes dans le fichier
     * \param linevir tableau avec les lignes de paiements par vir
     */
    function CreationLignesVirements( $page, $linevir)
    {
        //Traitement des virements
        $paiement = 0;
        $nvir = sizeof($linevir);
        $i = 0;
        for($i =0 ; $i < $nvir ; $i++)
        {
            if($paiement <> $linevir[$i][1])
            {
                $page->write_string($this->j,0, strftime("%d/%m/%Y",$linevir[$i][0]));

                if($linevir[$i][10] == 1) {
                    $banqueid = "BQ2";
                }else {
                    $banqueid = "BQ1";
                }

                if($linevir[$i][5] >=0 )
                {
                    $credit = $linevir[$i][5];
                }else {
                    $debit = abs($linevir[$i][5]);
                }
                $page->write_string($this->j,1, $banqueid);
                $page->write_string($this->j,2,$linevir[$i][2] );
                $page->write_string($this->j,3, "VIR");//$linevir[$i][4]);
                $page->write_string($this->j,4, "VIR ".$linevir[$i][4]);
                $page->write_number($this->j,5, $debit);
                $page->write_number($this->j,6, $credit);
                $page->write_string($this->j,7, "E");

                $paiement = $linevir[$i][1];
                $this->j++;

                $page->write_string($this->j,0, strftime("%d/%m/%Y",$linevir[$i][0]));

                if($linevir[$i][10] == 1) {
                    $banqueid = "BQ2";
                    $bankAccount = "51212000000";
                }else {
                    $banqueid = "BQ1";
                    $bankAccount = "51211000000";
                }

                $page->write_string($this->j,1, $banqueid);
                $page->write_string($this->j,2, $bankAccount );
                $page->write_string($this->j,3, "VIR");
                $page->write_string($this->j,4, "VIR ".$linevir[$i][4]);
                $page->write_number($this->j,5, $linevir[$i][5]);
                $page->write_number($this->j,6, 0);
                $page->write_string($this->j,7, "E");

                $paiement = $linevir[$i][1];
                $this->j++;


            }
        }

    }
    /*
     * \brief Function permettant de creer les lignes de reglements par cheque
     * \param j position de la ligne du fichier excel
     * \param page objet excel permettant de rajouter dees lignes dans le fichier
     * \param lineod tableau avec les lignes de regularisation de tva paiements par vir
     */
    function CreationLignesOD( $page, $lineod)
    {
        $nod = sizeof($lineod);
        for($i =0; $i < $nod ; $i++)
        {
            if($lineod[$i][14] == "1" && $lineod[$i][13] == "1")
            {

                $page->write_string($this->j,0, strftime("%d/%m/%Y",$lineod[$i][0]));
                $page->write_string($this->j,1, "OD");
                $page->write_string($this->j,2, '4457400000');
                $page->write_string($this->j,3, round(substr($lineod[$i][4],7,strlen($lineod[$i][4]))));
                $page->write_string($this->j,4, $lineod[$i][2]." ".$lineod[$i][4]);
                $page->write_number($this->j,5, $lineod[$i][12]);
                $page->write_number($this->j,6, 0);
                $page->write_string($this->j,7, "E");
                $this->j++;

                $page->write_string($this->j,0, strftime("%d/%m/%Y",$lineod[$i][0]));

                $page->write_string($this->j,1, "OD");
                $page->write_string($this->j,2,'4457100000' );
                $page->write_string($this->j,3, round(substr($lineod[$i][4],7,strlen($lineod[$i][4]))));
                $page->write_string($this->j,4, $lineod[$i][2]." ".$lineod[$i][4]);
                $page->write_number($this->j,5, 0);
                $page->write_number($this->j,6, $lineod[$i][12]);
                $page->write_string($this->j,7, "E");


                $this->j++;

            }

        }

    }
}