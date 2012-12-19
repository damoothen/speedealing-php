<?php
/* Copyright (C) 2009 Laurent Destailleur  <eldy@users.sourceforge.net>
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

/**
 *	\file       htdocs/bookmarks/bookmarks.lib.php
 *	\ingroup	bookmarks
 *	\brief      File with library for bookmark module
 */

/**
 * Add area with bookmarks in menu
 *
 * @param 	DoliDb  	$aDb		Database handler
 * @param 	Translate	$aLangs		Object lang
 * @return	string
 */
function printBookmarksList($aDb, $aLangs)
{
	global $conf, $user;

	$db = $aDb;
	$langs = $aLangs;

	require_once DOL_DOCUMENT_ROOT.'/bookmarks/class/bookmark.class.php';
	if (! isset($conf->global->BOOKMARKS_SHOW_IN_MENU)) $conf->global->BOOKMARKS_SHOW_IN_MENU=5;

	$bookm = new Bookmark($db);

	$langs->load("bookmarks");

	$url= $_SERVER["PHP_SELF"].(! empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:'');

	$ret = '';
	// Menu bookmark
	$ret.= '<div class="menu_titre">';
	$ret.= '<table class="nobordernopadding" width="100%" summary="bookmarkstable"><tr><td>';
	$ret.= '<a class="vmenu" href="'.DOL_URL_ROOT.'/bookmarks/liste.php">'.$langs->trans('Bookmarks').'</a>';
	$ret.= '</td><td align="right">';
	if ($user->rights->bookmark->creer)
	{
		$ret.= '<a class="vsmenu" href="'.DOL_URL_ROOT.'/bookmarks/fiche.php?action=create&amp;urlsource='.urlencode($url).'&amp;url='.urlencode($url).'">';
		//$ret.=img_picto($langs->trans('AddThisPageToBookmarks'),'edit_add').' ';
		$ret.=img_object($langs->trans('AddThisPageToBookmarks'),'bookmark');
		$ret.= '</a>';
	}
	$ret.= '</td></tr></table>';
	$ret.= '</div>';

	$ret.= '<div class="menu_top"></div>'."\n";

	// Menu with all bookmarks
	if (! empty($conf->global->BOOKMARKS_SHOW_IN_MENU))
	{
		$sql = "SELECT rowid, title, url, target FROM ".MAIN_DB_PREFIX."bookmark";
		$sql.= " WHERE (fk_user = ".$user->id." OR fk_user is NULL OR fk_user = 0)";
		$sql.= " ORDER BY position";
		if ($resql = $db->query($sql) )
		{
			$i=0;
			while ($i < $conf->global->BOOKMARKS_SHOW_IN_MENU && $obj = $db->fetch_object($resql))
			{
				$ret.='<div class="menu_contenu"><a class="vsmenu" title="'.$obj->title.'" href="'.$obj->url.'"'.($obj->target == 1?' target="_blank"':'').'>';
				$ret.=' '.img_object('','bookmark').' ';
				$ret.= dol_trunc($obj->title, 20).'</a><br></div>';
				$i++;
			}
		}
		else
		{
			dol_print_error($db);
		}
	}

	$ret .= '<div class="menu_end"></div>';

	return $ret;
}

?>
