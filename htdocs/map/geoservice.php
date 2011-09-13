<?php
require("../main.inc.php");

$socid=$_GET['socid'];

?>
{
	features: [
<?php
$sql="SELECT rowid, address, ville ,nom , latitude, longitude FROM ".MAIN_DB_PREFIX."societe WHERE longitude > 0 AND latitude > 0 AND fk_stcomm > 0 AND rowid !=".$socid;
$resql = $db->query($sql);
if ($resql)
{
	$nump = $db->num_rows($resql);
        if ($nump)
        {
	        $i = 0;
                while ($i < $nump)
                {
        	        $obj = $db->fetch_object($resql);
?>

	{
            "type": "Feature",
            "toponym": null,
            "title": "<?php print '<a href=\"'.DOL_URL_ROOT."/societe/soc.php?socid=".$obj->rowid.'\">'.$obj->nom.'</a>'; ?>",
            "author": "",
            "id": <?php echo $obj->rowid; ?>,
            "description": "<?php /*print $obj->address.'<br/>*/'<b>'.$obj->ville.'</b>'; ?>",
            "categories": "",
            "geometry": {
                "type": "Point",
                "coordinates": [<?php echo $obj->longitude; ?>,<?php echo $obj->latitude; ?>]
            },
            "icon_shadow": "",
            "icon_shadow_size": [0,0],
		
            "icon_size": [32,32],

            "icon": "<?php if( $obj->rowid == $socid ) print DOL_URL_ROOT."/map/img/red-dot.png"; else print DOL_URL_ROOT."/map/img/green-dot.png"; ?>",
            "line_opacity": 1.0,
            "line_width": 1.0,
            "poly_color": "",
            "source_id": <?php echo $obj->rowid; ?>
        }
<?php
                         print ",";
                        $i++;
			
		}
        }
}
$resql = $db->query("SELECT rowid, address, ville ,nom , latitude, longitude FROM ".MAIN_DB_PREFIX."societe WHERE longitude > 0 AND latitude > 0 AND rowid=".$socid );
if ($resql)
{
	$nump = $db->num_rows($resql);
        if ($nump)
        {
	        $i = 0;
                while ($i < $nump)
                {
        	        $obj = $db->fetch_object($resql);
?>

	{
            "type": "Feature",
            "toponym": null,
            "title": "<?php print '<a href=\"'.DOL_URL_ROOT."/societe/soc.php?socid=".$obj->rowid.'\">'.$obj->nom.'</a>'; ?>",
            "author": "",
            "id": <?php echo $obj->rowid; ?>,
            "description": "<?php /*print $obj->address.'<br/>*/'<b>'.$obj->ville.'</b>'; ?>",
            "categories": "",
            "geometry": {
                "type": "Point",
                "coordinates": [<?php echo $obj->longitude; ?>,<?php echo $obj->latitude; ?>]
            },
            "icon_shadow": "",
            "icon_shadow_size": [0,0],

            "icon_size": [32,32],

            "icon": "<?php if( $obj->rowid == $socid ) print DOL_URL_ROOT."/map/img/red-dot.png"; else print DOL_URL_ROOT."/map/img/green-dot.png"; ?>",
            "line_opacity": 1.0,
            "line_width": 1.0,
            "poly_color": "",
            "source_id": <?php echo $obj->rowid; ?>
        }
<?php
                         print ",";
                        $i++;

		}
        }
}
else
{
    print '"error"';
}

?>
	]
}
