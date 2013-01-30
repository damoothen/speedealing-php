<?php

require_once(DOL_DOCUMENT_ROOT . '/core/class/nosqlDocument.class.php');

class AbstractInvoice extends nosqlDocument {

    public $lines = array();

    public function fetch($id) {
        $res = parent::fetch($id);
        
        // Attribuer un id à chaque ligne
        for ($i = 0; $i < count($this->lines); $i++) {
            $this->lines[$i]->id = $i+1;
        }
        
        return $res;
    }
    
    public function record(){
        
        // ne pas sauvegarder l'id des lignes
        for ($i = 0; $i < count($this->lines); $i++)
            $this->lines[$i]->id = null;
        
        return parent::record();
        
    }
    
    /**
     * 	Add an order line into database (linked to product/service or not)
     *
     * 	@param      int				$commandeid      	Id of line
     * 	@param      string			$desc            	Description of line
     * 	@param      double			$pu_ht    	        Unit price (without tax)
     * 	@param      double			$qty             	Quantite
     * 	@param      double			$txtva           	Taux de tva force, sinon -1
     * 	@param      double			$txlocaltax1		Local tax 1 rate
     * 	@param      double			$txlocaltax2		Local tax 2 rate
     * 	@param      int				$fk_product      	Id du produit/service predefini
     * 	@param      double			$remise_percent  	Pourcentage de remise de la ligne
     * 	@param      int				$info_bits			Bits de type de lignes
     * 	@param      int				$fk_remise_except	Id remise
     * 	@param      string			$price_base_type	HT or TTC
     * 	@param      double			$pu_ttc    		    Prix unitaire TTC
     * 	@param      timestamp		$date_start       	Start date of the line - Added by Matelli (See http://matelli.fr/showcases/patchs-dolibarr/add-dates-in-order-lines.html)
     * 	@param      timestamp		$date_end         	End date of the line - Added by Matelli (See http://matelli.fr/showcases/patchs-dolibarr/add-dates-in-order-lines.html)
     * 	@param      int				$type				Type of line (0=product, 1=service)
     * 	@param      int				$rang             	Position of line
     * 	@param		int				$special_code		Special code
     * 	@param		int				$fk_parent_line		Parent line
     *  @param		int				$fk_fournprice		Id supplier price
     *  @param		int				$pa_ht				Buying price (without tax)
     *  @param		string			$label				Label
     * 	@return     int             					>0 if OK, <0 if KO
     *
     * 	@see        add_product
     *
     * 	Les parametres sont deja cense etre juste et avec valeurs finales a l'appel
     * 	de cette methode. Aussi, pour le taux tva, il doit deja avoir ete defini
     * 	par l'appelant par la methode get_default_tva(societe_vendeuse,societe_acheteuse,produit)
     * 	et le desc doit deja avoir la bonne valeur (a l'appelant de gerer le multilangue)
     */
    function addline($commandeid, $desc, $pu_ht, $qty, $txtva, $txlocaltax1 = 0, $txlocaltax2 = 0, $fk_product = 0, $remise_percent = 0, $info_bits = 0, $fk_remise_except = 0, $price_base_type = 'HT', $pu_ttc = 0, $date_start = '', $date_end = '', $type = 0, $rang = -1, $special_code = 0, $fk_parent_line = 0, $fk_fournprice = null, $pa_ht = 0, $label = '') {
        dol_syslog(get_class($this) . "::addline commandeid=$commandeid, desc=$desc, pu_ht=$pu_ht, qty=$qty, txtva=$txtva, fk_product=$fk_product, remise_percent=$remise_percent, info_bits=$info_bits, fk_remise_except=$fk_remise_except, price_base_type=$price_base_type, pu_ttc=$pu_ttc, date_start=$date_start, date_end=$date_end, type=$type", LOG_DEBUG);

        include_once DOL_DOCUMENT_ROOT . '/core/lib/price.lib.php';

        // Clean parameters
        if (empty($remise_percent))
            $remise_percent = 0;
        if (empty($qty))
            $qty = 0;
        if (empty($info_bits))
            $info_bits = 0;
        if (empty($rang))
            $rang = 0;
        if (empty($txtva))
            $txtva = 0;
        if (empty($txlocaltax1))
            $txlocaltax1 = 0;
        if (empty($txlocaltax2))
            $txlocaltax2 = 0;
        if (empty($fk_parent_line) || $fk_parent_line < 0)
            $fk_parent_line = 0;

        $remise_percent = price2num($remise_percent);
        $qty = price2num($qty);
        $pu_ht = price2num($pu_ht);
        $pu_ttc = price2num($pu_ttc);
        $pa_ht = price2num($pa_ht);
        $txtva = price2num($txtva);
        $txlocaltax1 = price2num($txlocaltax1);
        $txlocaltax2 = price2num($txlocaltax2);
        if ($price_base_type == 'HT') {
            $pu = $pu_ht;
        } else {
            $pu = $pu_ttc;
        }
        $label = trim($label);
        $desc = trim($desc);

        // Check parameters
//        if ($type < 0)
//            return -1;

        if ($this->Status == "DRAFT") {

            // Calcul du total TTC et de la TVA pour la ligne a partir de
            // qty, pu, remise_percent et txtva
            // TRES IMPORTANT: C'est au moment de l'insertion ligne qu'on doit stocker
            // la part ht, tva et ttc, et ce au niveau de la ligne qui a son propre taux tva.
            $tabprice = calcul_price_total($qty, $pu, $remise_percent, $txtva, $txlocaltax1, $txlocaltax2, 0, $price_base_type, $info_bits, $type);
            $total_ht = $tabprice[0];
            $total_tva = $tabprice[1];
            $total_ttc = $tabprice[2];
            $total_localtax1 = $tabprice[9];
            $total_localtax2 = $tabprice[10];

            // Rang to use
            $rangtouse = $rang;
//            if ($rangtouse == -1) {
//                $rangmax = $this->line_max($fk_parent_line);
//                $rangtouse = $rangmax + 1;
//            }
            // TODO A virer
            // Anciens indicateurs: $price, $remise (a ne plus utiliser)
            $price = $pu;
            $remise = 0;
            if ($remise_percent > 0) {
                $remise = round(($pu * $remise_percent / 100), 2);
                $price = $pu - $remise;
            }

            // Insert line
            $line = new stdClass();

            $line->fk_commande = $commandeid;
            $line->label = $label;
            $line->description = $desc;
            $line->qty = $qty;
            $line->tva_tx = $txtva;
            $line->localtax1_tx = $txlocaltax1;
            $line->localtax2_tx = $txlocaltax2;
            $line->fk_product = $fk_product;
            $line->fk_remise_except = $fk_remise_except;
            $line->remise_percent = $remise_percent;
            $line->subprice = $pu_ht;
//            $line->rang = $rangtouse;
            $line->info_bits = $info_bits;
            $line->total_ht = $total_ht;
            $line->total_tva = $total_tva;
            $line->total_localtax1 = $total_localtax1;
            $line->total_localtax2 = $total_localtax2;
            $line->total_ttc = $total_ttc;
            $line->product_type = $type;
            $line->special_code = $special_code;
            $line->fk_parent_line = $fk_parent_line;

            $line->date_start = $date_start;
            $line->date_end = $date_end;

            // infos marge
            $line->fk_fournprice = $fk_fournprice;
            $line->pa_ht = $pa_ht;

            // TODO Ne plus utiliser
            $line->price = $price;
            $line->remise = $remise;
            
            $this->lines[] = $line;
            $this->lines = array_merge($this->lines);
                       
            $this->record();
            $this->update_price();

            return 1;
        }
        return -1;
    }
    
    /**
     *  Update a line in database
     *
     *  @param    	int				$rowid            	Id of line to update
     *  @param    	string			$desc             	Description de la ligne
     *  @param    	double			$pu               	Prix unitaire
     *  @param    	double			$qty              	Quantity
     *  @param    	double			$remise_percent   	Pourcentage de remise de la ligne
     *  @param    	double			$txtva           	Taux TVA
     * 	@param		double			$txlocaltax1		Local tax 1 rate
     *  @param		double			$txlocaltax2		Local tax 2 rate
     *  @param    	string			$price_base_type	HT or TTC
     *  @param    	int				$info_bits        	Miscellaneous informations on line
     *  @param    	timestamp		$date_start        	Start date of the line
     *  @param    	timestamp		$date_end          	End date of the line
     * 	@param		int				$type				Type of line (0=product, 1=service)
     * 	@param		int				$fk_parent_line		Id of parent line (0 in most cases, used by modules adding sublevels into lines).
     * 	@param		int				$skip_update_total	Keep fields total_xxx to 0 (used for special lines by some modules)
     *  @param		int				$fk_fournprice		Id of origin supplier price
     *  @param		int				$pa_ht				Price (without tax) of product when it was bought
     *  @param		string			$label				Label
     *  @return   	int              					< 0 if KO, > 0 if OK
     */
    function updateline($rowid, $desc, $pu, $qty, $remise_percent, $txtva, $txlocaltax1 = 0, $txlocaltax2 = 0, $price_base_type = 'HT', $info_bits = 0, $date_start = '', $date_end = '', $type = 0, $fk_parent_line = 0, $skip_update_total = 0, $fk_fournprice = null, $pa_ht = 0, $label = '') {
        global $conf;

        dol_syslog(get_class($this) . "::updateline $rowid, $desc, $pu, $qty, $remise_percent, $txtva, $txlocaltax1, $txlocaltax2, $price_base_type, $info_bits, $date_start, $date_end, $type");
        include_once DOL_DOCUMENT_ROOT . '/core/lib/price.lib.php';

        if ($this->Status == "DRAFT") {
            // Clean parameters
            if (empty($qty))
                $qty = 0;
            if (empty($info_bits))
                $info_bits = 0;
            if (empty($txtva))
                $txtva = 0;
            if (empty($txlocaltax1))
                $txlocaltax1 = 0;
            if (empty($txlocaltax2))
                $txlocaltax2 = 0;
            if (empty($remise))
                $remise = 0;
            if (empty($remise_percent))
                $remise_percent = 0;
            $remise_percent = price2num($remise_percent);
            $qty = price2num($qty);
            $pu = price2num($pu);
            $pa_ht = price2num($pa_ht);
            $txtva = price2num($txtva);
            $txlocaltax1 = price2num($txlocaltax1);
            $txlocaltax2 = price2num($txlocaltax2);

            // Calcul du total TTC et de la TVA pour la ligne a partir de
            // qty, pu, remise_percent et txtva
            // TRES IMPORTANT: C'est au moment de l'insertion ligne qu'on doit stocker
            // la part ht, tva et ttc, et ce au niveau de la ligne qui a son propre taux tva.
            $tabprice = calcul_price_total($qty, $pu, $remise_percent, $txtva, $txlocaltax1, $txlocaltax2, 0, $price_base_type, $info_bits, $type);
            $total_ht = $tabprice[0];
            $total_tva = $tabprice[1];
            $total_ttc = $tabprice[2];
            $total_localtax1 = $tabprice[9];
            $total_localtax2 = $tabprice[10];

            // Anciens indicateurs: $price, $subprice, $remise (a ne plus utiliser)
            $price = $pu;
            $subprice = $pu;
            $remise = 0;
            if ($remise_percent > 0) {
                $remise = round(($pu * $remise_percent / 100), 2);
                $price = ($pu - $remise);
            }

            $line = $this->lines[$rowid-1];

            $line->id = $rowid;
            $line->label = $label;
            $line->description = $desc;
            $line->qty = $qty;
            $line->tva_tx = $txtva;
            $line->localtax1_tx = $txlocaltax1;
            $line->localtax2_tx = $txlocaltax2;
            $line->remise_percent = $remise_percent;
            $line->subprice = $subprice;
            $line->info_bits = $info_bits;
            $line->special_code = 0; // To remove special_code=3 coming from proposals copy
            $line->total_ht = $total_ht;
            $line->total_tva = $total_tva;
            $line->total_localtax1 = $total_localtax1;
            $line->total_localtax2 = $total_localtax2;
            $line->total_ttc = $total_ttc;
            $line->date_start = $date_start;
            $line->date_end = $date_end;
            $line->product_type = $type;
            $line->fk_parent_line = $fk_parent_line;
            $line->skip_update_total = $skip_update_total;

            // infos marge
            $line->fk_fournprice = $fk_fournprice;
            $line->pa_ht = $pa_ht;

            // TODO deprecated
            $line->price = $price;
            $line->remise = $remise;

            $this->lines = array_merge($this->lines);
            $this->record();
            $this->update_price();
            return 1;
        } else {
            $this->error = get_class($this) . "::updateline Order status makes operation forbidden";
            $this->errors = array('OrderStatusMakeOperationForbidden');
            return -2;
        }
    }
    
        /**
     *  Delete an order line
     *
     *  @param      int		$lineid		Id of line to delete
     *  @return     int        		 	>0 if OK, 0 if nothing to do, <0 if KO
     */
    function deleteline($lineid) {
        global $user;
        if ($this->Status == "DRAFT") {
            
            unset($this->lines[$lineid - 1]);
            $this->lines = array_merge($this->lines);
            
            $this->record();
            $this->update_price(1);

            return 1;

        } else {
            return -2;
        }
    }
    
    function update_price($exclspec = 0, $roundingadjust = -1, $nodatabaseupdate = 0) {

        include_once DOL_DOCUMENT_ROOT . '/core/lib/price.lib.php';

        $this->total_ht = 0;
        $this->total_localtax1 = 0;
        $this->total_localtax2 = 0;
        $this->total_tva = 0;
        $this->total_ttc = 0;

        foreach ($this->lines as $line) {
            $this->total_ht += $line->total_ht;
            $this->total_localtax1 += $line->total_localtax1;
            $this->total_localtax2 += $line->totaltaxt1;
            $this->total_tva += $line->total_tva;
            $this->total_ttc += $line->total_ttc;
        }

        $this->record();

        return 1;
    }
    
    public function showLines() {
        
        global $langs;
        
        print start_box($langs->trans('OrderLines'), "twelve", $object->fk_extrafields->ico, false);

//        print $this->datatablesEdit("listlines", $langs->trans("Lines"));

        $i = 0;
        print '<table class="display dt_act" id="listlines" >';
        // Ligne des titres 
        print'<thead>';
        print'<tr>';
        print'<th>';
        print'</th>';
        $obj->aoColumns[$i] = new stdClass();
        $obj->aoColumns[$i]->mDataProp = "id";
        $obj->aoColumns[$i]->bUseRendered = false;
        $obj->aoColumns[$i]->bSearchable = false;
        $obj->aoColumns[$i]->bVisible = false;
        $obj->aoColumns[$i]->sDefaultContent = $i+1;
        $i++;
        print'<th class="essential">';
        print $langs->trans("Description");
        print'</th>';
        $obj->aoColumns[$i] = new stdClass();
        $obj->aoColumns[$i]->mDataProp = "description";
        $obj->aoColumns[$i]->bUseRendered = false;
        $obj->aoColumns[$i]->bSearchable = true;
        $obj->aoColumns[$i]->editable = true;
        $i++;
        print'<th class="essential">';
        print $langs->trans("VAT");
        print'</th>';
        $obj->aoColumns[$i] = new stdClass();
        $obj->aoColumns[$i]->mDataProp = "tva_tx";
        $obj->aoColumns[$i]->bUseRendered = false;
        $obj->aoColumns[$i]->bSearchable = true;
        $obj->aoColumns[$i]->editable = true;
        $i++;
        print'<th class="essential">';
        print $langs->trans("PriceUHT");
        print'</th>';
        $obj->aoColumns[$i] = new stdClass();
        $obj->aoColumns[$i]->mDataProp = "subprice";
        $obj->aoColumns[$i]->bUseRendered = false;
        $obj->aoColumns[$i]->bSearchable = true;
        $obj->aoColumns[$i]->editable = true;
        $obj->aoColumns[$i]->fnRender = $this->datatablesFnRender("subprice", "price");
        $i++;
        print'<th class="essential">';
        print $langs->trans("Qty");
        print'</th>';
        $obj->aoColumns[$i] = new stdClass();
        $obj->aoColumns[$i]->mDataProp = "qty";
        $obj->aoColumns[$i]->bUseRendered = false;
        $obj->aoColumns[$i]->bSearchable = true;
        $obj->aoColumns[$i]->editable = true;
        $i++;
        print'<th class="essential">';
        print $langs->trans("TotalHTShort");
        print'</th>';
        $obj->aoColumns[$i] = new stdClass();
        $obj->aoColumns[$i]->mDataProp = "total_ht";
        $obj->aoColumns[$i]->bUseRendered = false;
        $obj->aoColumns[$i]->bSearchable = true;
        $obj->aoColumns[$i]->editable = true;
        $obj->aoColumns[$i]->fnRender = $this->datatablesFnRender("total_ht", "price");        
        $i++;
//        print'<th class="essential">';
//        print $langs->trans('Company');
//        print'</th>';
//        $obj->aoColumns[$i] = new stdClass();
//        $obj->aoColumns[$i]->mDataProp = "client.name";
//        $obj->aoColumns[$i]->sDefaultContent = "";
//        $obj->aoColumns[$i]->fnRender = $societe->datatablesFnRender("client.name", "url", array('id' => "client.id"));
//        $i++;
//        print'<th class="essential">';
//        print $langs->trans("RefCustomer");
//        print'</th>';
//        $obj->aoColumns[$i] = new stdClass();
//        $obj->aoColumns[$i]->mDataProp = "ref_client";
//        $obj->aoColumns[$i]->bUseRendered = false;
//        $obj->aoColumns[$i]->bSearchable = true;
//        $obj->aoColumns[$i]->editable = true;
//        $obj->aoColumns[$i]->sDefaultContent = "";
//        $i++;
//        print'<th class="essential">';
//        print $langs->trans('Date');
//        print'</th>';
//        $obj->aoColumns[$i] = new stdClass();
//        $obj->aoColumns[$i]->mDataProp = "date";
//        $obj->aoColumns[$i]->sClass = "center";
//        $obj->aoColumns[$i]->sDefaultContent = "";
//        $obj->aoColumns[$i]->bUseRendered = false;
//        $obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("date", "date");
//        $obj->aoColumns[$i]->editable = true;
//        $i++;
//print'<th class="essential">';
//print $langs->trans('DateEnd');
//print'</th>';
//$obj->aoColumns[$i] = new stdClass();
//$obj->aoColumns[$i]->mDataProp = "date_livraison";
//$obj->aoColumns[$i]->sClass = "center";
//$obj->aoColumns[$i]->sDefaultContent = "";
//$obj->aoColumns[$i]->bUseRendered = false;
//$obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("date_livraison", "date");
//$obj->aoColumns[$i]->editable = true;
//$i++;
//print'<th class="essential">';
//print $langs->trans('Contact');
//print'</th>';
//$obj->aoColumns[$i]->mDataProp = "contact.name";
//$obj->aoColumns[$i]->sDefaultContent = "";
//$obj->aoColumns[$i]->fnRender = $contact->datatablesFnRender("contact.name", "url", array('id' => "contact.id"));
//$i++;
//print'<th class="essential">';
//  print $langs->trans('Author');
//  print'</th>';
//  $obj->aoColumns[$i] = new stdClass();
//  $obj->aoColumns[$i]->mDataProp = "author";
//  $obj->aoColumns[$i]->sDefaultContent = "";
//  $obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("author.name", "url", array('id' => "author.id"));
//  $i++;
//        print'<th class="essential">';
//        print $langs->trans("Status");
//        print'</th>';
//        $obj->aoColumns[$i] = new stdClass();
//        $obj->aoColumns[$i]->mDataProp = "Status";
//        $obj->aoColumns[$i]->sClass = "center";
//        $obj->aoColumns[$i]->sDefaultContent = "DRAFT";
//        $obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("Status", "status");
//        $obj->aoColumns[$i]->editable = true;
//        $i++;
//        print'<th class="essential">';
//        print $langs->trans('Action');
//        print'</th>';
//        $obj->aoColumns[$i] = new stdClass();
//        $obj->aoColumns[$i]->mDataProp = "";
//        $obj->aoColumns[$i]->sClass = "center content_actions";
//        $obj->aoColumns[$i]->sWidth = "60px";
//        $obj->aoColumns[$i]->bSortable = false;
//        $obj->aoColumns[$i]->sDefaultContent = "";
//
//        $url = "commande/fiche.php";
//        $obj->aoColumns[$i]->fnRender = 'function(obj) {
//	var ar = [];
//	ar[ar.length] = "<a href=\"' . $url . '?id=";
//	ar[ar.length] = obj.aData._id.toString();
//	ar[ar.length] = "&action=edit&backtopage=' . $_SERVER['PHP_SELF'] . '\" class=\"sepV_a\" title=\"' . $langs->trans("Edit") . '\"><img src=\"' . DOL_URL_ROOT . '/theme/' . $conf->theme . '/img/edit.png\" alt=\"\" /></a>";
//	ar[ar.length] = "<a href=\"\"";
//	ar[ar.length] = " class=\"delEnqBtn\" title=\"' . $langs->trans("Delete") . '\"><img src=\"' . DOL_URL_ROOT . '/theme/' . $conf->theme . '/img/delete.png\" alt=\"\" /></a>";
//	var str = ar.join("");
//	return str;
//}';
        print'</tr>';
        print'</thead>';
        print'<tfoot>';
        /* input search view */
        $i = 0; //Doesn't work with bServerSide
        print'<tr>';
        print'<th id="' . $i . '"></th>';
        $i++;
        print'<th id="' . $i . '"><input type="text" placeholder="' . $langs->trans("Search Description") . '" /></th>';
        $i++;
        print'<th id="' . $i . '"><input type="text" placeholder="' . $langs->trans("Search VAT") . '" /></th>';
        $i++;
        print'<th id="' . $i . '"><input type="text" placeholder="' . $langs->trans("Search PriceUHT") . '" /></th>';
        $i++;
        print'<th id="' . $i . '"><input type="text" placeholder="' . $langs->trans("Search Qty") . '" /></th>';
        $i++;
        print'<th id="' . $i . '"><input type="text" placeholder="' . $langs->trans("Search TotalHTShort") . '" /></th>';
        $i++;
//print'<th id="' . $i . '"><input type="text" placeholder="' . $langs->trans("Search author") . '" /></th>';
//$i++;
//        print'<th id="' . $i . '"><input type="text" placeholder="' . $langs->trans("Search Status") . '" /></th>';
//        $i++;
//        print'<th id="' . $i . '"></th>';
//        $i++;
        print'</tr>';
        print'</tfoot>';
        print'<tbody>';
        print'</tbody>';

        print "</table>";

        $obj->aaSorting = array(array(1, 'asc'));
//$obj->bServerSide = true;
//if ($all) {
//    if ($type == "DONE")
//        $obj->sAjaxSource = "core/ajax/listdatatables.php?json=actionsDONE&class=" . get_class($object);
//    else
//        $obj->sAjaxSource = "core/ajax/listdatatables.php?json=actionsTODO&class=" . get_class($object);
//} else {
//    if ($type == "DONE")
//        $obj->sAjaxSource = $_SERVER["PHP_SELF"] . "?json=listDONEByUser";
//    else
//        $obj->sAjaxSource = $_SERVER["PHP_SELF"] . "?json=listTODOByUser";
//
//}
        $obj->sAjaxSource = $_SERVER["PHP_SELF"] . "?json=lines";

        $this->datatablesCreate($obj, "listlines", true, true);
        
        print end_box();
    }

}

class InvoiceLine {
    
}

?>
