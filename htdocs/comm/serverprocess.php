
<?php

require("../main.inc.php");
require_once(DOL_DOCUMENT_ROOT . "/comm/prospect/class/prospect.class.php");
require_once(DOL_DOCUMENT_ROOT . "/core/class/html.formother.class.php");
$langs->load("companies");
$langs->load("customers");
$langs->load("suppliers");
$langs->load("commercial");
/* Array of database columns which should be read and sent back to DataTables. Use a space where
 * you want to insert a non-database field (for example a counter or static image)
 */
$aColumns = array('', 'company', 'ville', 'departement', 'cp', 'datec', 'fk_prospectlevel', 'fk_stcomm', 'etat', 'priv');
$aColumnsSql = array('', 's.nom', 's.ville', 'd.nom', 's.cp', 's.datec', 's.fk_prospectlevel', 'fk_stcomm', '', '');
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
            $sOrder .= $aColumnsSql[intval($_GET['iSortCol_' . $i])] . "
				 	" . ($_GET['sSortDir_' . $i]) . ", ";
        }
    }
    $sOrder = substr_replace($sOrder, "", -2);
    if ($sOrder == "ORDER BY") {
        $sOrder = "";
    }
    if ($Cols == 9 || $Cols == 0 || $Cols == 8) {
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
        if ($aColumnsSql[$i] != '') {
            $sWhere .= $aColumnsSql[$i] . " LIKE '%" . $_GET['sSearch'] . "%' OR ";
        }
    }
    $sWhere = substr_replace($sWhere, "", -3);
    $sWhere .= ')';
}
// If the user must only see his prospect, force searching by him
if (!$user->rights->societe->client->voir && !$socid)
    $search_sale = $user->id;

/* sql query */
$sql = "SELECT s.rowid, s.nom, s.ville, s.datec, s.datea, s.status as status,";
$sql.= " st.libelle as stcomm, s.prefix_comm, s.fk_stcomm, s.fk_prospectlevel,st.type,";
$sql.= " d.nom as departement, s.cp as cp";
// Updated by Matelli 
// We'll need these fields in order to filter by sale (including the case where the user can only see his prospects)
if ($search_sale)
    $sql .= ", sc.fk_soc, sc.fk_user";
$sql .= " FROM (" . MAIN_DB_PREFIX . "societe as s";
// We'll need this table joined to the select in order to filter by sale
if (!$user->rights->societe->client->voir)
    $sql.= ", " . MAIN_DB_PREFIX . "societe_commerciaux as sc";

$sql.= " ) ";
$sql.= " LEFT JOIN " . MAIN_DB_PREFIX . "c_departements as d on (d.rowid = s.fk_departement)";
$sql.= " LEFT JOIN " . MAIN_DB_PREFIX . "c_stcomm as st ON st.id = s.fk_stcomm";
$sql.= " WHERE s.client in (1,2,3)";
if ($type != '')
    $sql.= " AND st.type=" . $type;

$sql.= " AND s.entity = " . $conf->entity;
if ($search_sale) {
    $sql.= " AND s.rowid = sc.fk_soc";  // Join for the needed table to filter by sale
}
// Insert sale filter
if ($search_sale) {
    $sql .= " AND sc.fk_user = " . $db->escape($search_sale);
}

//print $sql;
$result = $db->query($sql);
if ($result) {
    $iTotal = $db->num_rows($result);
    $sql.= $sWhere;
    $sql.= $sOrder;
    $sql.= $sLimit;
    $result = $db->query($sql);
    $prospectstatic = new Prospect($db);

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

            if ($aColumns[$i] == "company") {
                $prospectstatic->id = $aRow->rowid;
                $prospectstatic->nom = $aRow->nom;
                $prospectstatic->status = $aRow->status;
                if ($aRow->type == 2)
                    $row[] = $prospectstatic->getNomUrl(1, 'customer');
                else
                    $row[] = $prospectstatic->getNomUrl(1, 'prospect');
            }
            else if ($aColumns[$i] == "datec") {
                $row[] = dol_print_date($db->jdate($aRow->datec));
            } else if ($aColumns[$i] == "etat") {
                $prospectstatic->stcomm_id = $aRow->fk_stcomm;
                $prospectstatic->type = $aRow->type;
                $row[] = $prospectstatic->getIconList(DOL_URL_ROOT . "/comm/list.php?socid=" . $aRow->rowid . $param . '&action=cstc&amp;' . ($page ? '&amp;page=' . $page : ''));
            } else if ($aColumns[$i] == "fk_prospectlevel") { // Level
                $row[] = $prospectstatic->LibLevel($aRow->fk_prospectlevel);
            } else if ($aColumns[$i] == "fk_stcomm") { //status
                $row[] = $prospectstatic->LibProspStatut($aRow->fk_stcomm, 2);
            } else if ($i == 0) {
                $row[] = '<img id="' . $aRow->rowid . '" class="plus" src="../theme/cameleo/img/details_open.png">';
            } else if ($i == (count($aColumns) - 1)) { //last
                $row[] = $prospectstatic->getLibStatut(3);
            } else if ($aColumns[$i] != ' ') {
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
} else {
    dol_print_error($db);
}
?>