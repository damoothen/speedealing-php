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

	<!-- Main menu -->

	<!-- Sidebar/drop-down menu -->
	<section id="menu" role="complementary">
		<!-- This wrapper is used by several responsive layouts -->
		<div id="menu-content">
			<header>
				<form action="search.php" id="search_box" method="post">
					<input name="query" id="query" type="text" size="40" placeholder="<?php echo $langs->trans("SearchOf"); ?>..." autocomplete="off" />
				</form>
			</header>

			<div id="profile">
				<?php if (!empty($user->Photo)) : ?>
				<img alt="User name" src="<?php echo $user->getFile($user->Photo); ?>" width="64" class="user-icon">
				<?php else : ?>
				<img src="theme/symeos/img/user.png" width="64" class="user-icon" alt="User name">
				<?php endif; ?>
				<?php echo $langs->trans('Hello'); ?><span class="name"><?php echo $user->Firstname; ?> <b><?php echo $user->Lastname; ?></b></span>
			</div>

			<!-- By default, this section is made for 4 icons, see the doc to learn how to change this, in "basic markup explained" -->
			<ul id="access" class="children-tooltip">
				<li style="width: 20%;">
					<a href="index.php?idmenu=menu:home" title="<?php echo $langs->trans("Home"); ?>">
						<span class="icon-home"></span>
					</a>
				</li>
				<li style="width: 20%;">
					<span href="inbox.html" title="Messages">
						<span class="icon-inbox"></span>
					</span>
				</li>
				<li style="width: 20%;">
				<?php if (!empty($countTODO)) : ?>
					<a href="agenda/list.php?idmenu=menu:myagendaListTODO" title="<?php echo $langs->trans("Agenda"); ?>">
						<span class="icon-calendar"></span>
						<?php if ($countTODO->rows[0]->value) { ?>
						<span class="count"><?php echo $countTODO->rows[0]->value; ?></span>
						<?php $count_icon+=$countTODO->rows[0]->value; ?>
						<?php } ?>
					</a>
				<?php else: ?>
					<span href="agenda/list.php" title="<?php echo $langs->trans("Agenda"); ?>">
						<span class="icon-calendar"></span>
					</span>
				<?php endif; ?>
				</li>
				<li style="width: 20%;">
					<a href="user/fiche.php?id=<?php echo $user->id; ?>" title="Profile">
						<span class="icon-gear"></span>
					</a>
				</li>
				<li style="width: 20%;">
					<a href="user/logout.php" title="Log out">
						<span class="icon-unlock"></span>
					</a>
				</li>
			</ul>

			<?php $menu->showmenuTop(); ?>

			<?php if (!empty($listMyTasks->rows)) : ?>
				<ul class="unstyled-list">
					<li class="title-menu">Today's event</li>
					<li>
						<ul class="calendar-menu">
						<?php foreach ($listMyTasks->rows as $aRow) { ?>
							<li>
								<a href="agenda/fiche.php?id=<?php echo $aRow->value->_id; ?>" title="<?php echo $aRow->value->societe->name; ?>">
									<time datetime="<?php echo dol_print_date($aRow->value->datep, "day"); ?>">
										<b><?php echo date("d", $aRow->value->datep); ?></b><?php echo date("M", $aRow->value->datep); ?>
									</time>
									<small class="green"><?php echo dol_print_date($aRow->value->datep, "hour"); ?></small>
									<?php echo $aRow->value->label; ?>
								</a>
							</li>
						<?php } ?>
						</ul>
					</li>
				</ul>
			<?php endif; ?>
		</div>
		<!-- End content wrapper -->

		<!-- This is optional -->
		<footer id="menu-footer">
			<div>
				<p>Speedealing v.<?php echo DOL_VERSION; ?></p>
			</div>
		</footer>

	</section>
	<!-- End sidebar/drop-down menu -->
	<script type="text/javascript">
		$(document).ready(function() {
			$('#query').sautocomplete('search/data.php', {
				delay: 10,
				minChars: 2,
				max: 6,
				matchCase: 1,
				indicator: indicatorInPlace,
				width: 212
			}).result(function(event, query_val) {
				$.modal({
					title: 'Result content',
					minWidth: 200,
					minHeight: 200,
					resizable: false,
					url: 'search/search_result.php',
					ajax: {
						type: "POST",
						data: "search_item=" + query_val
					}
				});
			});
			$('#search_box').submit(function() {
				var query_val = $("#query").val();
				$.modal({
					title: 'Result content',
					minWidth: 200,
					minHeight: 200,
					resizable: false,
					url: 'search/search_result.php',
					ajax: {
						type: "POST",
						data: "search_item=" + query_val
					}
				});
				return false;
			});
		});
	</script>
	<!--Begin left area - menu <?php echo $left_menu; ?> -->