<?php
/* Copyright (C) 2012 Regis Houssin <regis.houssin@capnetworks.com>
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

$module = $object->element;
$note_public = 'note_public';
$note_private = 'note';

$colwidth=(isset($colwidth)?$colwidth:25);
$permission=(isset($permission)?$permission:(isset($user->rights->$module->creer)?$user->rights->$module->creer:0));    // If already defined by caller page
$moreparam=(isset($moreparam)?$moreparam:'');

// Special cases
if ($module == 'propal')                 { $permission=$user->rights->propal->creer; }
elseif ($module == 'fichinter')         { $permission=$user->rights->ficheinter->creer; $note_private = 'note_private'; }
elseif ($module == 'project')           { $permission=$user->rights->projet->creer; $note_private = 'note_private'; }
elseif ($module == 'project_task')      { $permission=$user->rights->projet->creer; $note_private = 'note_private'; }
elseif ($module == 'invoice_supplier')  { $permission=$user->rights->fournisseur->facture->creer; }
elseif ($module == 'order_supplier')    { $permission=$user->rights->fournisseur->commande->creer; }

if (! empty($conf->global->FCKEDITOR_ENABLE_SOCIETE)) $typeofdata='ckeditor:dolibarr_notes:100%:200::1:12:100';
else $typeofdata='textarea:12:100';
?>

<!-- BEGIN PHP TEMPLATE NOTES -->
<div class="table-border">
	<div class="table-border-row">
		<div class="table-key-border-col"<?php echo ' style="width: '.$colwidth.'%"'; ?>><?php echo $form->editfieldkey("NotePublic", $note_public, $object->note_public, $object, $permission, $typeofdata, $moreparam); ?></div>
		<div class="table-val-border-col"><?php echo $form->editfieldval("NotePublic", $note_public, $object->note_public, $object, $permission, $typeofdata, '', null, null, $moreparam); ?></div>
	</div>
<?php if (! $user->societe_id) { ?>
	<div class="table-border-row">
		<div class="table-key-border-col"<?php echo ' style="width: '.$colwidth.'%"'; ?>><?php echo $form->editfieldkey("NotePrivate", $note_private, $object->note_private, $object, $permission, $typeofdata, $moreparam); ?></div>
		<div class="table-val-border-col"><?php echo $form->editfieldval("NotePrivate", $note_private, $object->note_private, $object, $permission, $typeofdata, '', null, null, $moreparam); ?></div>
	</div>
<?php } ?>
</div>
<!-- END PHP TEMPLATE NOTES-->
