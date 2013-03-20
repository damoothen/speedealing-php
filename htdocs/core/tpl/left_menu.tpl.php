<?php
/* Copyright (C) 2010-2013	Regis Houssin	<regis.houssin@capnetworks.com>
 * Copyright (C) 2011-2013	Herve Prot		<herve.prot@symeos.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */
?>

	<!-- Left menu -->

	<ul id="shortcuts" role="complementary" class="children-tooltip tooltip-right">
		<li class="home">
			<a href="index.php?idmenu=menu:home" class="shortcut-dashboard" title="<?php echo $langs->trans("Dashboard"); ?>">
				<?php echo $langs->trans("Dashboard"); ?>
			</a>
		</li>
		<li>
			<span class="shortcut-messages" title="<?php echo $langs->trans("Messages"); ?>">
				<?php echo $langs->trans("Messages"); ?>
			</span>
		</li>
		<li class="agendaList">
			<?php if ($conf->agenda->enabled) : ?>
			<a href="agenda/list.php?idmenu=menu:agendaList" class="shortcut-agenda" title="<?php echo $langs->trans("Agenda"); ?>">
				<?php echo $langs->trans("Agenda"); ?>
			</a>
			<?php else: ?>
			<span class="shortcut-agenda" title="<?php echo $langs->trans("Agenda"); ?>">
				<?php echo $langs->trans("Agenda"); ?>
			</span>
			<?php endif; ?>
		</li>
		<li>
			<span class="shortcut-contacts" title="<?php echo $langs->trans("Contacts"); ?>">
				<?php echo $langs->trans("Contacts"); ?>
			</span>
		</li>
		<li>
			<span class="shortcut-medias" title="<?php echo $langs->trans("Medias"); ?>">
				<?php echo $langs->trans("Medias"); ?>
			</span>
		</li>
		<li>
			<span class="shortcut-stats" title="<?php echo $langs->trans("Stats"); ?>">
				<?php echo $langs->trans("Stats"); ?>
			</span>
		</li>
		<li>
			<span class="shortcut-notes" title="<?php echo $langs->trans("Notes"); ?>">
				<?php echo $langs->trans("Notes"); ?>
			</span>
		</li>
		<li class="at-bottom">
			<span class="shortcut-settings" title="<?php echo $langs->trans("Settings"); ?>">
				<?php echo $langs->trans("Settings"); ?>
			</span>
		</li>
		<li class="trashList at-bottom-trash">
			<a href="trash/list.php?idmenu=menu:trashList" class="shortcut-trash-empty" title="<?php echo $langs->trans("RecycleBin"); ?>">
				<?php echo $langs->trans("RecycleBin"); ?>
			</a>
		</li>
	</ul>
	<script type="text/javascript">
		$(document).ready(function() {
			var trashStatus = requestCore('getTrash', 'count');
			if (trashStatus) {
				var trash = $('#shortcuts li.trashList a.shortcut-trash-empty');
				trash.removeClass('shortcut-trash-empty').addClass('shortcut-trash-full');
			} else {
				var trash = $('#shortcuts li.trashList a.shortcut-trash-full');
				trash.removeClass('shortcut-trash-full').addClass('shortcut-trash-empty');
			}
			$('#shortcuts li.<?php echo $shortcut; ?>').addClass('current');
		});
	</script>
