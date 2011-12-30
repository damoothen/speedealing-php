
<?php

require("../main.inc.php");
require_once(DOL_DOCUMENT_ROOT . "/contact/class/contact.class.php");

/* Array of database columns which should be read and sent back to DataTables. Use a space where
 * you want to insert a non-database field (for example a counter or static image)
 */
$aColumns = array('', 'name', 'firstname', 'poste', 'nom', 'phone', 'email', 'cpost', 'tms', 'priv', '');
$aColumnsSql = array('p.name', 'p.firstname', 'p.poste', 's.nom', 'p.phone', 'p.email', 's.cp', 'p.tms', 'p.priv');
/*
 * Paging
 */
$sLimit = "";
if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
    $sLimit = " LIMIT " . $_GET['iDisplayStart'] . ", " .
            $_GET['iDisplayLength'];
}

/*
 * Ordering
 */


if (isset($_GET['iSortCol_0'])) {
    $sOrder = " ORDER BY  ";
    for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
        if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
            $Cols = intval($_GET['iSortCol_' . $i]);
            $sOrder .= $aColumns[intval($_GET['iSortCol_' . $i])] . "
				 	" . ($_GET['sSortDir_' . $i]) . ", ";
        }
    }
    $sOrder = substr_replace($sOrder, "", -2);
    if ($sOrder == "ORDER BY") {
        $sOrder = "";
    }
    if ($Cols == 10 || $Cols == 0) {
        $sOrder = "";
    }
}

/*
 * Filtering
 * NOTE this does not match the built-in DataTables filtering which does it
 * word by word on any field. It's possible to do here, but concerned about efficiency
 * on very large tables, and MySQL's regex functionality is very limited
 */
$sWhere = "";
if ($_GET['sSearch'] != "") {
    $sWhere = " AND (";
    for ($i = 0; $i < count($aColumnsSql); $i++) {
        $sWhere .= $aColumnsSql[$i] . " LIKE '%" . $_GET['sSearch'] . "%' OR ";
    }
    $sWhere = substr_replace($sWhere, "", -3);
    $sWhere .= ')';
}

/* sql query */
$sql = "SELECT s.rowid as socid, s.nom,";
$sql.= " s.cp as cpost, p.rowid as cidp, p.name, p.firstname, p.poste, p.email,";
$sql.= " p.phone, p.phone_mobile, p.fax, p.fk_pays, p.priv,";
$sql.= " p.tms,";
$sql.= " cp.code as pays_code";
$sql.= " FROM " . MAIN_DB_PREFIX . "socpeople as p";
$sql.= " LEFT JOIN " . MAIN_DB_PREFIX . "c_pays as cp ON cp.rowid = p.fk_pays";
$sql.= " LEFT JOIN " . MAIN_DB_PREFIX . "societe as s ON s.rowid = p.fk_soc";
if (!$user->rights->societe->client->voir && !$socid)
    $sql .= " LEFT JOIN " . MAIN_DB_PREFIX . "societe_commerciaux as sc ON s.rowid = sc.fk_soc";
$sql.= " WHERE p.entity = " . $conf->entity . ' ';
if (!$user->rights->societe->client->voir && !$socid) { //restriction
    $sql .= " AND (sc.fk_user = " . $user->id . " OR p.fk_soc IS NULL)";
}

if ($_GET["userid"]) {    // propre au commercial
    $sql .= " AND p.fk_user_creat=" . $_GET["userid"];
}

// Filter to exclude not owned private contacts
if ($search_priv != '0' && $search_priv != '1') {
    $sql .= " AND (p.priv='0' OR (p.priv='1' AND p.fk_user_creat=" . $user->id . "))";
} else {
    if ($search_priv == '0')
        $sql .= " AND p.priv='0'";
    if ($search_priv == '1')
        $sql .= " AND (p.priv='1' AND p.fk_user_creat=" . $user->id . ")";
}

//print $sql;

$result = $db->query($sql);
if ($result) {
    $iTotal = $db->num_rows($result);
    $sql.= $sWhere;
    $sql.= $sOrder;
    $sql.= $sLimit;
    $result = $db->query($sql);
    $contactstatic = new Contact($db);

    /*
     * Output
     */
    $output = array(
        "sEcho" => intval($_GET['sEcho']),
        "iTotalRecords" => $iTotal,
        "iTotalDisplayRecords" => $iTotal,
        "aaData" => array()
    );

    while ($aRow = $db->fetch_object($result)) {
        $row = array();

        for ($i = 0; $i < count($aColumns); $i++) {

            if ($aColumns[$i] == "name") {
                $contactstatic->name = $aRow->name;
                $contactstatic->firstname = '';
                $contactstatic->id = $aRow->cidp;
                $row[] = $contactstatic->getNomUrl(1, '', 20);
                
            }
            else if($aColumns[$i]=="tms"){
                 
                 $row[] = dol_print_date($db->jdate($aRow->tms),"day");
            }
            else if($aColumns[$i]=="email"){
                 $row[] = dol_print_email($aRow->email,$aRow->cidp,$aRow->socid,'AC_EMAIL',18);
            }
            else if ($aColumns[$i] == "priv") {             
                $row[] = $contactstatic->LibPubPriv($aRow->priv);
            
            }
            else if ($i == 0) {
                $row[] = '<img id="' . $aRow->cidp . '" class="plus" src="../theme/cameleo/img/details_open.png">';
            }
            else if ($i == (count($aColumns) - 1)) { //last
                $row[] = '<a href="' . DOL_URL_ROOT . '/comm/action/fiche.php?action=create&amp;backtopage=1&amp;contactid=' . $aRow->cidp . '&amp;socid=' . $aRow->socid . '">' . img_object($langs->trans("AddAction"), "action") . '</a>'
                        . ' &nbsp;'
                        . ' &nbsp;' .
                        '<a href="' . DOL_URL_ROOT . '/contact/vcard.php?id=' . $aRow->cidp . '">' .
                        img_picto($langs->trans("VCard"), 'vcard.png') . ' ' .
                        '</a>';
            }
            else if ($aColumns[$i] != ' ') {
                /* General output */
                $attribut = $aColumns[$i];
                $row[] = $aRow->$attribut;
            }
        }

        $output['aaData'][] = $row;
    }
    $db->free($result);
    header('Content-type: application/json');
    echo json_encode($output);
}
else {
    dol_print_error($db);
}
?>