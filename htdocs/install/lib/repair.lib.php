<?php
/* Copyright (C) 2012 Regis Houssin	<regis.houssin@capnetworks.com>
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
 * or see http://www.gnu.org/
 */

/**
 *	\file			htdocs/install/lib/repair.lib.php
 *  \brief			Library of repair functions
 */

/**
 *  Check if an element exist
 *
 *  @param	int		$id			Element id
 *  @param	string	$table		Table of Element
 *  @return	boolean				True if child exists
 */
function checkElementExist($id, $table)
{
	global $db;

	$sql = 'SELECT rowid FROM ' . MAIN_DB_PREFIX . $table;
	$sql.= ' WHERE rowid = '.$id;
	$resql=$db->query($sql);
	if ($resql)
	{
		$num = $db->num_rows($resql);
		if ($num > 0) return true;
		else return false;
	}
	else return true; // for security
}

/**
 * Check linked elements and delete if invalid
 *
 * @param	string	$sourcetype		Source element type
 * @param	string	$targettype		Target element type
 * @return	string
 */
function checkLinkedElements($sourcetype, $targettype)
{
	global $db, $langs;

	$elements=array();
	$deleted=0;

	$sourcetable=$sourcetype;
	$targettable=$targettype;

	if ($sourcetype == 'shipping') $sourcetable = 'expedition';
	else if ($targettype == 'shipping') $targettable = 'expedition';
	if ($sourcetype == 'delivery') $sourcetable = 'livraison';
	else if ($targettype == 'delivery') $targettable = 'livraison';
	if ($sourcetype == 'order_supplier') $sourcetable = 'commande_fournisseur';
	else if ($targettype == 'order_supplier') $targettable = 'commande_fournisseur';
	if ($sourcetype == 'invoice_supplier') $sourcetable = 'facture_fourn';
	else if ($targettype == 'invoice_supplier') $targettable = 'facture_fourn';

	$out = $langs->trans('SourceType').': '.$sourcetype.' => '.$langs->trans('TargetType').': '.$targettype.' ';

	$sql = 'SELECT * FROM '.MAIN_DB_PREFIX .'element_element';
	$sql.= ' WHERE sourcetype="'.$sourcetype.'" AND targettype="'.$targettype.'"';
	$resql=$db->query($sql);
	if ($resql)
	{
		$num = $db->num_rows($resql);
		if ($num)
		{
			$i = 0;
			while ($i < $num)
			{
				$obj = $db->fetch_object($resql);
				$elements[$obj->rowid]=array($sourcetype => $obj->fk_source, $targettype => $obj->fk_target);
				$i++;
			}
		}
	}

	if (! empty($elements))
	{
		foreach($elements as $key => $element)
		{
			if (! checkElementExist($element[$sourcetype], $sourcetable) || ! checkElementExist($element[$targettype], $targettable))
			{
				$sql = 'DELETE FROM '.MAIN_DB_PREFIX .'element_element';
				$sql.= ' WHERE rowid = '.$key;
				$resql=$db->query($sql);
				$deleted++;
			}
		}
	}

	if ($deleted) $out.= '('.$langs->trans('LinkedElementsInvalidDeleted', $deleted).')<br>';
	else $out.= '('.$langs->trans('NothingToDelete').')<br>';

	return $out;
}

/**
 * Clean data into ecm_directories table
 *
 * @return	void
 */
function clean_data_ecm_directories()
{
	global $db, $langs;

	// Clean data from ecm_directories
	$sql="SELECT rowid, label FROM ".MAIN_DB_PREFIX."ecm_directories";
	$resql=$db->query($sql);
	if ($resql)
	{
		while($obj=$db->fetch_object($resql))
		{
			$id=$obj->rowid;
			$label=$obj->label;
			$newlabel=dol_sanitizeFileName($label);
			if ($label != $newlabel)
			{
				$sqlupdate="UPDATE ".MAIN_DB_PREFIX."ecm_directories set label='".$newlabel."' WHERE rowid=".$id;
				print '<tr><td>'.$sqlupdate."</td></tr>\n";
				$resqlupdate=$db->query($sqlupdate);
				if (! $resqlupdate) dol_print_error($db,'Failed to update');
			}

		}
	}
	else dol_print_error($db,'Failed to run request');

	return;
}

?>
