<?php
require("../main.inc.php");

$object = new Societe($db);

$id = $_GET['id'];
?>
{
features: [
<?php
$result = $object->getView(listGPS);
if (count($result->rows)) {
    foreach ($result->rows as $idx => $aRow) {
        $obj = $aRow->value;
        if ($obj->_id != $id) {
            ?>
            {
            "type": "Feature",
            "toponym": null,
            "title": "<?php print '<a href=\"' . DOL_URL_ROOT . "/societe/fiche.php?id=" . $obj->_id . '\">' . $obj->name . '</a>'; ?>",
            "author": "",
            "id": "<?php echo $obj->_id; ?>",
            "description": "<?php /* print $obj->address.'<br/> */'<b>' . $obj->town . '</b>'; ?>",
            "categories": "",
            "geometry": {
            "type": "Point",
            "coordinates": [<?php echo $obj->gps[0]; ?>,<?php echo $obj->gps[1]; ?>]
            },
            "icon_shadow": "",
            "icon_shadow_size": [0,0],

            "icon_size": [32,32],

            "icon": "<?php if ($obj->_id == $id) print DOL_URL_ROOT . "/map/img/red-dot.png"; else print DOL_URL_ROOT . "/map/img/green-dot.png"; ?>",
            "line_opacity": 1.0,
            "line_width": 1.0,
            "poly_color": "",
            "source_id": "<?php echo $obj->_id; ?>"
            }
            <?php
            print ",";
        } else {
            $idx_soc = $idx;
        }
    }
}

if (isset($idx_soc)) {
    $aRow = $result->rows[$idx_soc];
    $obj = $aRow->value;
    ?>
    {
    "type": "Feature",
    "toponym": null,
    "title": "<?php print '<a href=\"' . DOL_URL_ROOT . "/societe/fiche.php?id=" . $obj->_id . '\">' . $obj->name . '</a>'; ?>",
    "author": "",
    "id": "<?php echo $obj->_id; ?>",
    "description": "<?php /* print $obj->address.'<br/> */'<b>' . $obj->town . '</b>'; ?>",
    "categories": "",
    "geometry": {
    "type": "Point",
    "coordinates": [<?php echo $obj->gps[1]; ?>,<?php echo $obj->gps[0]; ?>]
    },
    "icon_shadow": "",
    "icon_shadow_size": [0,0],

    "icon_size": [32,32],

    "icon": "<?php if ($obj->_id == $id) print DOL_URL_ROOT . "/map/img/red-dot.png"; else print DOL_URL_ROOT . "/map/img/green-dot.png"; ?>",
    "line_opacity": 1.0,
    "line_width": 1.0,
    "poly_color": "",
    "source_id": "<?php echo $obj->_id; ?>"
    }
    <?php
} else {
    print '"error"';
}
?>
]
}
