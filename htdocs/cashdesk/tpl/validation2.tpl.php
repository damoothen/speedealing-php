<?php
/* Copyright (C) 2007-2008	Jeremie Ollivier	<jeremie.o@laposte.net>
 * Copyright (C) 2012       Marcos García       <marcosgdf@gmail.com>
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
 *
 */

$langs->load("main");

?>

<h3 class="titre1"><?php echo $langs->trans("SellFinished"); ?></h3>

<div class="cadre_facturation">

<script type="text/javascript">

	function popupTicket()
	{
		largeur = 600;
		hauteur = 500
		opt = 'width='+largeur+', height='+hauteur+', left='+(screen.width - largeur)/2+', top='+(screen.height-hauteur)/2+'';
		window.open('validation_ticket.php?facid=<?php echo $_GET['facid']; ?>', '<?php echo $langs->trans('PrintTicket') ?>', opt);
	}

	popupTicket();

</script>

<p><a class="lien1" href="<?php echo DOL_URL_ROOT ?>/compta/facture.php?action=builddoc&facid=<?php echo $_GET['facid']; ?>" target="_blank"><?php echo $langs->trans("ShowInvoice"); ?></a></p>
<br>
<p><a class="lien1" href="#" onclick="Javascript: popupTicket(); return(false);"><?php echo $langs->trans("PrintTicket"); ?></a></p>

</div>
