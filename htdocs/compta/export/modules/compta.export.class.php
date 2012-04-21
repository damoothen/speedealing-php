<?PHP
/* Copyright (C) 2004-2006 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2006      Laurent Destailleur  <eldy@users.sourceforge.net>
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
 * $Id: compta.export.class.php,v 1.15 2007/06/11 22:51:54 hregis Exp $
 * $Source: /cvsroot/dolibarr/dolibarr/htdocs/compta/export/modules/compta.export.class.php,v $
 */

/**
   \file       htdocs/compta/export/modules/compta.export.class.php
   \ingroup    compta
   \brief      Fichier de la classe d'export compta
   \version    $Revision: 1.15 $
*/


/**
   \class      ComptaExport
   \brief      Classe permettant les exports comptables
*/

class ComptaExport
{
  /**
     \brief      Constructeur de la class
     \param      DB          Object de base de données
     \param      USER        Object utilisateur
     \param      classe      Nom de la classe utilisée pour formater les rapports
  */
  function ComptaExport ($DB, $USER, $classe)
  {
    $this->db = $DB;
    $this->user = $USER;
    $this->classe_export = $classe;
    $this->error_message = '';
  }
  
  
  /**
     \brief      Lecture des factures dans la base
     \param      id      Id ligne
  */
  function ReadLines($id=0, $type='new')
  {
    global $langs;
    
    dolibarr_syslog("ComptaExport::ReadLines id=".$id." Type ".$type);
    
    $error = 0;
    
    $sql = "SELECT f.rowid as facid, f.facnumber, f.datef as datef";
    $sql .= " , f.total_ttc, f.tva, f.type ";
    $sql .= " ,s.nom, s.rowid as socid, s.code_compta";
    $sql .= " , l.price, l.tva_tx, l.total_tva as ltotaltva";
    $sql .= " , f.increment";
    $sql .= " , l.rowid as lrowid";
    $sql .= " , p.code_comptable_vente, p.fk_product_type as ptype, l.total_ht as pht";
    //Left join pour sortir les lignes de factures sans lien produit
    $sql .= " FROM ".MAIN_DB_PREFIX."facturedet as l left join llx_product as p on l.fk_facture = p.rowid";
    $sql .= " , ".MAIN_DB_PREFIX."facture as f";
    $sql .= " , ".MAIN_DB_PREFIX."societe as s";
    $sql .= " WHERE f.rowid = l.fk_facture ";
    $sql .= " AND s.rowid = f.fk_soc";
 
    if($type == 'rebuild') {

    $sql .= " AND l.fk_export_compta = ".$id;
    $sql .= " AND l.fk_code_ventilation <> 0 ";
   
   }else {
    $sql .= " AND l.fk_export_compta = 0";
    $sql .= " AND l.fk_code_ventilation = 0 ";
   }
    
     $sql .= " ORDER BY f.rowid ASC";
    
    dolibarr_syslog("ComptaExport::Export requete vente ".$sql);
    $resql = $this->db->query($sql);
    $i =0;
    $num=0;
    if ($resql)
      {
	$num = $this->db->num_rows($resql);
	//$i = 0;
	$this->linec = array();
	
	while ($i < $num)
	  {
	    $obj = $this->db->fetch_object($resql);
	    
	    $this->linec[$i][0] = $this->db->jdate($obj->datef);
	    $this->linec[$i][1] = $obj->facid;
	    $this->linec[$i][2] = $obj->code_compta;
	    $this->linec[$i][3] = $obj->nom;
	    $this->linec[$i][4] = $obj->numero;
	    $this->linec[$i][5] = $obj->facnumber;
	    $this->linec[$i][6] = $obj->tva;
	    $this->linec[$i][7] = $obj->total_ttc;
	    $this->linec[$i][8] = $obj->price;
	    $this->linec[$i][9] = $obj->increment;
	    $this->linec[$i][10] = $obj->lrowid;
        $this->linec[$i][11] = $obj->code_comptable_vente;
        $this->linec[$i][12] = $obj->type;
	    $this->linec[$i][13] = $obj->pht;
        $this->linec[$i][14] = $obj->ptype;
        $this->linec[$i][15] = $obj->ltotaltva;

	    if ($obj->code_compta == '')
	      {
		$societe=new Societe($this->db);
		$societe->fetch($obj->socid);
		$this->error_message.= $langs->transnoentities("ErrorWrongAccountancyCodeForCompany",$societe->getNomUrl(1))."<br>";
		$error++;
	      }
	    
	    $i++;
	  }
      $this->db->free($resql);
}
   return $error;
  }
  
  /**
     \brief      Lecture des paiements dans la base
     \param      id      Id ligne
  */
  
  function ReadLinesPayment($id=0, $type='new')
  {
    dolibarr_syslog("ComptaExport::ReadLinesPayment id=".$id);
    $error = 0;

    /*Creation table des cheques*/
    $sql1 = "SELECT p.rowid as paymentid, f.facnumber, p.num_paiement";
    $sql1 .= " ,p.datep as datep";
    $sql1 .= " , p.amount, p.fk_paiement as paiementtype";
    $sql1 .= " , s.nom, s.code_compta, ba.rowid";
    $sql1 .= " , cp.libelle, b.fk_bordereau as bordereau";
    $sql1 .= "  ,bc.date_bordereau as date_bordereau, bc.fk_bank_account, bc.amount as bordereau_amount";
    $sql1 .=" , b.fk_bordereau, ba.rowid as banqueid";

    $sql1 .= " FROM ".MAIN_DB_PREFIX."paiement_facture as pf";
    $sql1 .= " , ".MAIN_DB_PREFIX."c_paiement as cp";
    $sql1 .= " , ".MAIN_DB_PREFIX."facture as f";
    $sql1 .= " , ".MAIN_DB_PREFIX."societe as s";
    $sql1 .= " , ".MAIN_DB_PREFIX."bank as b";
    $sql1 .= " , ".MAIN_DB_PREFIX."paiement as p";
    $sql1 .= " , ".MAIN_DB_PREFIX."bordereau_cheque as bc";
    $sql1 .= " , ".MAIN_DB_PREFIX."bank_account as ba";
   

    $sql1 .= " WHERE p.fk_export_compta = ".$id;
    $sql1 .= " AND p.rowid = pf.fk_paiement";
    $sql1 .= " AND cp.id = p.fk_paiement";
    //$sql1 .= " AND fdet.fk_facture = f.rowid";
    $sql1 .= " AND f.rowid = pf.fk_facture";
    $sql1 .= " AND f.fk_soc = s.rowid";
    $sql1 .= " AND p.fk_bank = b.rowid";
    $sql1 .= " AND b.fk_account = ba.rowid";
    $sql1 .= " AND p.fk_paiement = 7 ";
    $sql1 .= " AND b.fk_bordereau = bc.rowid";
   // $sql1 .= " AND pro.rowid = fdet.fk_product";
    $sql1 .= " group by p.rowid order by bc.rowid";
   // $sql1 .= " order by bc.rowid";
    //$sql .= " ORDER BY f.rowid ASC, p.rowid ASC";
    
    dolibarr_syslog("ComptaExport::Export requete reglement cheque ".$sql1);
    $resql1 = $this->db->query($sql1);

    if ($resql1)
      {
	$num = $this->db->num_rows($resql1);
	$i = 0;
	$this->linech = array();

	while ($i < $num)
	  {
	    $objch = $this->db->fetch_object($resql1);

	    $this->linech[$i][0] = $this->db->jdate($objch->datep);
	    $this->linech[$i][1] = $objch->paymentid;
	    $this->linech[$i][2] = $objch->code_compta;
	    $this->linech[$i][3] = $objch->nom;
	    $this->linech[$i][4] = $objch->facnumber;
	    $this->linech[$i][5] = $objch->amount;
	    $this->linech[$i][6] = $objch->libelle;

	    if (strlen(trim( $objch->increment)) > 0)
	      {
		$this->linech[$i][7] = $objch->increment;
	      }
	    else
	      {
		$this->linech[$i][7] = $objch->facnumber;
	      }
        $this->linech[$i][8] = $objch->paiementtype;
        $this->linech[$i][9] = $objch->num_paiement;
	    $this->linech[$i][10] = $objch->banqueid;
        $this->linech[$i][11] = $objch->bordereau;
        $this->linech[$i][12] = $this->db->jdate($objch->date_bordereau);
        $this->linech[$i][13] = $objch->fk_bank_account;
        $this->linech[$i][14] = $objch->bordereau_amount;
        $i++;
	  }

	$this->db->free($resql1);

      }
    else
      {
	$error++;
      }

    
    $sql = "SELECT p.rowid as paymentid, f.facnumber, p.num_paiement";
    $sql .= " ,p.datep as datep";
    $sql .= " , p.amount, p.fk_paiement as paiementtype";
    $sql .= " , s.nom, s.code_compta";
    $sql .= " , cp.libelle, f.increment, pb.rowid as pbon";
    $sql .= " ,pb.datec as datebon";
    $sql .= " , pb.amount as pbonamount";

    $sql .= " FROM ".MAIN_DB_PREFIX."paiement_facture as pf";
    $sql .= " , ".MAIN_DB_PREFIX."c_paiement as cp";
    $sql .= " , ".MAIN_DB_PREFIX."facture as f";
    $sql .= " , ".MAIN_DB_PREFIX."societe as s";
    //$sql .= " , ".MAIN_DB_PREFIX."paiement as p";
    $sql .= " , ".MAIN_DB_PREFIX."paiement as p left join ".MAIN_DB_PREFIX."prelevement_bons as pb on pb.ref = p.num_paiement";

    $sql .= " WHERE p.fk_export_compta = ".$id;
    $sql .= " AND p.rowid = pf.fk_paiement";
    $sql .= " AND cp.id = p.fk_paiement";
    //$sql .= " AND fdet.fk_facture = f.rowid";
    $sql .= " AND f.rowid = pf.fk_facture";
    $sql .= " AND f.fk_soc = s.rowid";
    $sql .= " AND p.fk_paiement = 3 ";
    //$sql .= " AND pb.ref = p.num_paiement";
    //$sql .= " AND p.fk_bank = b.rowid";
    //$sql .= " AND p.statut = 1 ";
    $sql .= " group by p.rowid order by pb.rowid";
    //$sql .= " ORDER BY f.rowid ASC, p.rowid ASC";
    
    dolibarr_syslog("ComptaExport::Export requete reglement ".$sql);
    $resql = $this->db->query($sql);

    if ($resql)
      {
	$num = $this->db->num_rows($resql);
	$i = 0;
	$this->linep = array();

	while ($i < $num)
	  {
	    $obj = $this->db->fetch_object($resql);

	    $this->linep[$i][0] = $this->db->jdate($obj->datep);
	    $this->linep[$i][1] = $obj->paymentid;
	    $this->linep[$i][2] = $obj->code_compta;
	    $this->linep[$i][3] = $obj->nom;
	    $this->linep[$i][4] = $obj->facnumber;
	    $this->linep[$i][5] = $obj->amount;
	    $this->linep[$i][6] = $obj->libelle;

	    if (strlen(trim( $obj->increment)) > 0)
	      {
		$this->linep[$i][7] = $obj->increment;
	      }
	    else
	      {
		$this->linep[$i][7] = $obj->facnumber;
	      }
        $this->linep[$i][8] = $obj->paiementtype;
        $this->linep[$i][9] = $obj->num_paiement;
	    $this->linep[$i][10] = $obj->pbon;
        $this->linep[$i][11] = $this->db->jdate($obj->datebon);
        $this->linep[$i][12] = $obj->pbonamount;
        $i++;
	  }

	$this->db->free($resql);
   
      }
    else
      {
	$error++;
      }

   //Traitement des virements
    $sql2 = "SELECT p.rowid as paymentid, f.facnumber, p.num_paiement";
    $sql2 .= " ,p.datep as datep";
    $sql2 .= " , p.amount, p.fk_paiement as paiementtype";
    $sql2 .= " , s.nom, s.code_compta";
    $sql2 .= " , cp.libelle, f.increment";
   // $sql2 .= " , b.fk_account , factdet.total_tva, f.paye , pro.fk_product_type";
    $sql2 .= " , b.fk_account";
    $sql2 .= " FROM ".MAIN_DB_PREFIX."paiement_facture as pf";
    $sql2 .= " , ".MAIN_DB_PREFIX."c_paiement as cp";
    $sql2 .= " , ".MAIN_DB_PREFIX."facture as f";
    //$sql2 .= " , ".MAIN_DB_PREFIX."facturedet as factdet";
    $sql2 .= " , ".MAIN_DB_PREFIX."societe as s";
    $sql2 .= " , ".MAIN_DB_PREFIX."paiement as p";
    $sql2 .= " , ".MAIN_DB_PREFIX."bank as b";
    //$sql2 .= " , ".MAIN_DB_PREFIX."product as pro";

    $sql2 .= " WHERE p.fk_export_compta = ".$id;
    $sql2 .= " AND p.rowid = pf.fk_paiement";
    $sql2 .= " AND cp.id = p.fk_paiement";
    //$sql .= " AND fdet.fk_facture = f.rowid";
    $sql2 .= " AND f.rowid = pf.fk_facture";
    $sql2 .= " AND f.fk_soc = s.rowid";
    $sql2 .= " AND p.fk_paiement = 2 ";
    //$sql2 .= " AND pro.rowid = factdet.fk_product";
    $sql2 .= " AND p.fk_bank = b.rowid";
    //$sql .= " AND p.statut = 1 ";
    //$sql2 .= " AND factdet.fk_facture = f.rowid";
    $sql2 .= " group by p.rowid order by p.rowid";
    //$sql .= " ORDER BY f.rowid ASC, p.rowid ASC";
    //$sql2 .= " order by p.rowid";
    
    dolibarr_syslog("ComptaExport::Export requete reglement virements".$sql2);
    $resql2 = $this->db->query($sql2);

    if ($resql2)
      {
	$numvir = $this->db->num_rows($resql2);
	$i = 0;
	$this->linevir = array();

	while ($i < $numvir)
	  {
	    $objvir = $this->db->fetch_object($resql2);

	    $this->linevir[$i][0] = $this->db->jdate($objvir->datep);
	    $this->linevir[$i][1] = $objvir->paymentid;
	    $this->linevir[$i][2] = $objvir->code_compta;
	    $this->linevir[$i][3] = $objvir->nom;
	    $this->linevir[$i][4] = $objvir->facnumber;
	    $this->linevir[$i][5] = $objvir->amount;
	    $this->linevir[$i][6] = $objvir->libelle;

	    if (strlen(trim( $obj->increment)) > 0)
	      {
		$this->linevir[$i][7] = $objvir->increment;
	      }
	    else
	      {
		$this->linevir[$i][7] = $objvir->facnumber;
	      }
        $this->linevir[$i][8] = $objvir->paiementtype;
        $this->linevir[$i][9] = $objvir->num_paiement;
	    $this->linevir[$i][10] = $objvir->fk_account;
        $this->linevir[$i][11] = $objvir->num_paiement;
        $i++;
	  }

    $this->db->free($resql2);


      }
    else
      {
	$error++;
      }



      //Traitement des OD
    $sqlod = "SELECT p.rowid as paymentid, f.facnumber, p.num_paiement";
    $sqlod .= " ,p.datep as datep";
    $sqlod .= " , p.amount, p.fk_paiement as paiementtype";
    $sqlod .= " , s.nom, s.code_compta";
    $sqlod .= " , cp.libelle, f.increment";
    $sqlod .= " , factdet.total_tva, f.paye , pro.fk_product_type, factdet.total_tva , f.paye, pro.fk_product_type";

    $sqlod .= " FROM ".MAIN_DB_PREFIX."paiement_facture as pf";
    $sqlod .= " , ".MAIN_DB_PREFIX."c_paiement as cp";
    $sqlod .= " , ".MAIN_DB_PREFIX."facture as f";
    $sqlod .= " , ".MAIN_DB_PREFIX."facturedet as factdet";
    $sqlod .= " , ".MAIN_DB_PREFIX."societe as s";
    $sqlod .= " , ".MAIN_DB_PREFIX."paiement as p";
    $sqlod .= " , ".MAIN_DB_PREFIX."product as pro";

    $sqlod .= " WHERE p.fk_export_compta = ".$id;
    $sqlod .= " AND p.rowid = pf.fk_paiement";
    $sqlod .= " AND cp.id = p.fk_paiement";
    $sqlod .= " AND f.rowid = pf.fk_facture";
    $sqlod .= " AND f.fk_soc = s.rowid";
    $sqlod .= " AND pro.rowid = factdet.fk_product";
    $sqlod .= " AND factdet.fk_facture = f.rowid";
    $sqlod .= " AND f.paye = 1";
    $sqlod .= " order by p.rowid";

    dolibarr_syslog("ComptaExport::Export requete OD ".$sqlod);
    $resqlod = $this->db->query($sqlod);

    if ($resqlod)
      {
	$numod = $this->db->num_rows($resqlod);
	$i = 0;
	$this->lineod = array();

	while ($i < $numod)
	  {
	    $objod = $this->db->fetch_object($resqlod);

	    $this->lineod[$i][0] = $this->db->jdate($objod->datep);
	    $this->lineod[$i][1] = $objod->paymentid;
	    $this->lineod[$i][2] = $objod->code_compta;
	    $this->lineod[$i][3] = $objod->nom;
	    $this->lineod[$i][4] = $objod->facnumber;
	    $this->lineod[$i][5] = $objod->amount;
	    $this->lineod[$i][6] = $objod->libelle;

	    if (strlen(trim( $objod->increment)) > 0)
	      {
		$this->lineod[$i][7] = $objod->increment;
	      }
	    else
	      {
		$this->lineod[$i][7] = $objod->facnumber;
	      }
        $this->lineod[$i][8] = $objod->paiementtype;
        $this->lineod[$i][9] = $objod->num_paiement;
	    $this->lineod[$i][10] = $objod->fk_account;
        $this->lineod[$i][11] = $objod->num_paiement;
        $this->lineod[$i][12] = $objod->total_tva;
        $this->lineod[$i][13] = $objod->paye;
        $this->lineod[$i][14] = $objod->fk_product_type;
        $i++;
	  }

    $this->db->free($resqlod);


      }
    else
      {
	$error++;
      }


    return $error;
  }

  /**
    \brief      Créé le fichier d'export
  */

  function Export($id=0, $dir, $type)
  {
    $error = 0;

    dolibarr_syslog("ComptaExport::Export");

   $error += $this->ReadLines($id, $type);
   $error += $this->ReadLinesPayment($id, $type);

    dolibarr_syslog("ComptaExport::Export Lignes de factures  : ".sizeof($this->linec));
    dolibarr_syslog("ComptaExport::Export Lignes de paiements : ".sizeof($this->linep));

    if (!$error && (sizeof($this->linec) > 0 || sizeof($this->linep) > 0))
      {
	include_once DOL_DOCUMENT_ROOT.'/compta/export/modules/compta.export.'.strtolower($this->classe_export).'.class.php';  

	$objexport_name = "ComptaExport".$this->classe_export;
	$objexport = new $objexport_name($this->db, $this->user);
        
        print sizeof($this->linec)."toto";

	if(sizeof($this->linec) != 0)
        {
            print "toto";
            $objexport->ExportJournalVente($dir, $this->linec, $id);
        }
        if(sizeof($this->linep) != 0 || sizeof($this->linech) != 0 || sizeof($this->linevir) != 0)
        {
            $objexport->ExportJournalReglement($dir,$this->linep,$this->linech,$this->linevir,$this->lineod,$id);
        }


	$this->id = $objexport->id;
	$this->ref = $objexport->ref;
      }
  }

}

