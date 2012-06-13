<?php
/* Copyright (C) 2011-2012 Herve Prot			<herve.prot@symeos.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

require_once(DOL_DOCUMENT_ROOT . "/core/class/commonobject.class.php");

/**
 * 	Parent class of all other business classes (invoices, contracts, proposals, orders, ...)
 */
abstract class nosqlDocument extends CommonObject {

	protected $couchdb; // TODO must to be private !!!!!
	public $id;
	public $values;
	public $error;
	public $errors;
	public $canvas; // Contains canvas name if it is
	public $fk_extrafields;

	/**
	 * 	class constructor
	 *
	 * 	@param	couchClient	$db		Database handler
	 */
	function __construct($db) {
		$this->class = get_class($this);
		$this->db = $db;
		$this->loadDatabase();
	}

	/**
	 * load couchdb parameters
	 * @param	$dbname		string			name of database
	 * @return int 
	 * 
	 */
	public function loadDatabase($dbname = "") {
		global $conf;

		if (empty($dbname))
			$dbname = $conf->Couchdb->name;

		$this->couchdb = new couchClient($conf->Couchdb->host . ':' . $conf->Couchdb->port . '/', $dbname);
		$this->couchdb->setSessionCookie($_SESSION['couchdb']);
	}

	function fetch($rowid) { // old dolibarr rowid
		try {
			$result = $this->getView("rowid", array("key" => intval($rowid)));
			$this->load($result->rows[0]->value);
		} catch (Exception $e) {
			$this->error = "Fetch : Something weird happened: " . $e->getMessage() . " (errcode=" . $e->getCode() . ")\n";
			dol_print_error($this->db, $this->error);
			return 0;
		}

		return 1;
	}

	function update($user) {
		if ($this->id) // only update
			$this->values->UserUpdate = $user->login;
		else { // Create
			$this->values->UserCreate = $user->login;
			$this->values->UserUpdate = $user->login;
		}

		return $this->commit();
	}

	/**
	 * 	Set a value and modify type for couchdb
	 * @param string $key
	 * @param string $value 
	 */
	public function set($key, $value) {
		if (is_numeric($value))
			$this->values->$key = (int) $value;
		else
			$this->values->$key = $value;

		$params = new stdClass();

		$params->field = $key;
		$params->value = $value;

		return $this->couchdb->updateDoc(get_class($this), "in-place", $params, $this->id);
	}

	/**
	 * 	Get a value from a key
	 * @param string $key
	 * @return value 
	 */
	public function get($key) {
		return $this->values->$key;
	}

	/**
	 * 	load a $id document in values
	 * @param $id
	 * @return $value
	 */
	public function load($id, $cache = false) {
		global $conf;

		$found = false;

		if ($conf->Memcached->enabled && $cache) {
			$this->values = dol_getcache($id);
			if (is_object($this->values)) {
				$found = true;
			}
		}

		if (!$found) {
			$this->values = array();
			$this->values = $this->couchdb->getDoc($id); // load extrafields for class

			if ($conf->Memcached->enabled && $cache) {
				dol_setcache($id, $this->values);
			}
		}
		$this->id = $this->values->_id;
		return $this->values;
	}

	/**
	 * 	save values object document
	 *  @param	$cache	if true remove element from cache
	 * @return value of storeDoc
	 */
	public function record($cache = false) {
		global $conf;

		$this->values->class = get_class($this);

		if ($conf->Memcached->enabled && $cache) {
			dol_delcache($this->id);
		}

		try {
			$this->couchdb->clean($this->values);
			$result = $this->couchdb->storeDoc($this->values);
			$this->id = $result->id;
			$this->values->_id = $result->id;
			$this->values->_rev = $result->rev;
		} catch (Exception $e) {
			dol_print_error("", $e->getMessage());
			dol_syslog(get_class($this) . "::get " . $error, LOG_WARN);
		}
		return $result;
	}

	/**
	 * 	save values objects documents
	 *  @param	$obj		array of objects
	 *  @return value of storeDoc
	 */
	public function storeDocs($obj) {
		return $this->couchdb->storeDocs($obj);
	}

	/**
	 * 	delete values objects documents
	 *  @param	$obj		array of objects
	 *  @return value of storeDoc
	 */
	public function deleteDocs($obj) {
		return $this->couchdb->deleteDocs($obj);
	}

	/**
	 * 	save values of one object documents
	 *  @param	$obj		object
	 *  @return value of storeDoc
	 */
	public function storeDoc($obj) {
		return $this->couchdb->storeDoc($obj);
	}

	/**
	 * 	delete a object document
	 *  @param	$obj		object
	 *  @return value of storeDoc
	 */
	public function deleteDoc($obj) {
		return $this->couchdb->deleteDoc($obj);
	}

	/**
	 * 	store a file in document
	 *  @return value of storeAttachment
	 */
	public function storeFile() {
		global $_FILES;
		
		return $this->couchdb->storeAttachment($this->values, $_FILES['addedfile']['tmp_name'], $_FILES['addedfile']['type'], $_FILES['addedfile']['name']);
	}
	
	/**
	 * 	get URL a of file in document
	 *  @return value URL of storeAttachment
	 */
	public function getFile($filename) {
		$url_server = $this->couchdb->getServerUri() . "/" . $this->couchdb->getDatabaseName();
		
		return $url_server."/".$this->id."/".$filename;
	}
	
	/**
	 * 	delete a file in document
	 *  @param	$filename		name of the file
	 *  @return value of storeAttachment
	 */
	public function deleteFile($filename) {
		return $this->couchdb->deleteAttachment($this->values, $filename);
	}

	/**
	 * 	Return id of document
	 * @return string
	 */
	public function id() {
		return $this->id;
	}

	/** Call a view on couchdb
	 * 
	 * @param	$name			string			name of the view
	 * @param	$params			array			params ['group'],['level'],['key'],...
	 * @param	$cache			bool			load from cache
	 * @return  array
	 */
	public function getView($name, $params = array(), $cache = false) {
		global $conf;

		$found = false;

		if ($conf->Memcached->enabled && $cache) {
			$result = dol_getcache(get_class($this) . ":" . $name);
			if (is_object($result)) {
				$found = true;
			}
		}

		if (!$found) {
			$result = array();
			try {
				if (!empty($conf->view_limit))
					$params['limit'] = $conf->view_limit;
				$this->couchdb->setQueryParameters($params);

				$result = $this->couchdb->getView(get_class($this), $name);

				if ($conf->Memcached->enabled && $cache) {
					dol_setcache(get_class($this) . ":" . $name, $result);
				}
			} catch (Exception $e) {
				dol_print_error("", $e->getMessage());
				dol_syslog(get_class($this) . "::getView " . $error, LOG_WARN);
			}
		}

		return $result;
	}

	/**
	 *    Return label of status (activity, closed)
	 *
	 *    @return   string        		Libelle
	 */
	function getLibStatus() {
		return $this->LibStatus($this->values->Status);
	}

	/**
	 *    Flush the cache
	 * 	@param		$id					key to delete, nothing flush_all
	 *  @return		string        		Libelle
	 */
	function flush($id = '') {
		if (!empty($id))
			return dol_delcache($id);
		else
			return dol_flushcache();
	}

	/**
	 *  Renvoi le libelle d'un statut donne
	 *
	 *  @param	int		$statut         Id statut
	 *  @return	string          		Libelle du statut
	 */
	function LibStatus($status) {
		global $langs, $conf;

		if (empty($status))
			$status = $this->fk_extrafields->fields->Status->default;

		if (isset($this->fk_extrafields->fields->Status->values->$status->label))
			return '<span class="lbl ' . $this->fk_extrafields->fields->Status->values->$status->cssClass . ' sl_status ">' . $langs->trans($this->fk_extrafields->fields->Status->values->$status->label) . '</span>';
		else
			return '<span class="lbl ' . $this->fk_extrafields->fields->Status->values->$status->cssClass . ' sl_status ">' . $langs->trans($status) . '</span>';
	}

	/**
	 *  For Generate a datatable
	 *
	 *  @param $obj object of aocolumns parameters
	 *  @param $ref_css name of #list
	 *  @return string
	 */
	public function datatablesCreate($obj, $ref_css, $json = false, $ColSearch = false) {
		global $conf, $langs;
		?>
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				var oTable = $('#<?php echo $ref_css ?>').dataTable( {
					"aoColumns" : [
		<?php
		$nb = count($obj->aoColumns);
		foreach ($obj->aoColumns as $i => $aRow):
			?>
								{
			<?php foreach ($aRow as $key => $fields): ?>
				<?php if ($key == "mDataProp" || $key == "sClass" || $key == "sDefaultContent" || $key == "sType" || $key == "sWidth") : ?>
												"<?php echo $key; ?>":"<?php echo $fields; ?>",
				<?php elseif ($key == "fnRender") : ?>
												"<?php echo $key; ?>": <?php echo $fields; ?>,	    
				<?php else : ?>
												"<?php echo $key; ?>": <?php echo ($fields ? "true" : "false"); ?>,
				<?php endif; ?>
				<?php
			endforeach;
			if ($nb - 1 == $i)
				echo "}"; else
				echo"},";
			?>
		<?php endforeach; ?>
						],
		<?php if (!isset($obj->aaSorting) && $json) : ?>
							"aaSorting" : [[1,"asc"]],
		<?php else : ?>
							"aaSorting" : <?php echo json_encode($obj->aaSorting); ?>,
		<?php endif; ?>
		<?php if ($json) : ?>
			<?php if (!empty($obj->sAjaxSource)): ?>
									"sAjaxSource": "<?php echo $obj->sAjaxSource; ?>",
			<?php else : ?>
									"sAjaxSource" : "<?php echo DOL_URL_ROOT . '/core/ajax/listDatatables.php'; ?>?json=list&class=<?php echo get_class($this); ?>",
			<?php endif; ?>
		<?php endif; ?>
		<?php if (!empty($obj->iDisplayLength)): ?>
							"iDisplayLength": <?php echo $obj->iDisplayLength; ?>,
		<?php else : ?>
							"iDisplayLength": <?php echo (int) $conf->global->MAIN_SIZE_LISTE_LIMIT; ?>,
		<?php endif; ?>
						"aLengthMenu": [[10, 25, 50, 100, 1000, -1],[10, 25, 50, 100,1000,"All"]],
						"bProcessing": true,
						"bJQueryUI": true,
						"bAutoWidth": false,
						/*$obj->bServerSide = true;*/
						"bDeferRender": true,
						"oLanguage": { "sUrl": "<?php echo DOL_URL_ROOT . '/includes/jquery/plugins/datatables/langs/' . ($langs->defaultlang ? $langs->defaultlang : "en_US") . ".txt"; ?>"},
						/*$obj->sDom = '<\"top\"Tflpi<\"clear\">>rt<\"bottom\"pi<\"clear\">>';*/
						/*$obj->sPaginationType = 'full_numbers';*/
						/*$obj->sDom = 'TC<\"clear\">lfrtip';*/
						"oTableTools": { "sSwfPath": "<?php echo DOL_URL_ROOT . '/includes/jquery/plugins/datatables/extras/TableTools/media/swf/copy_csv_xls.swf'; ?>"},
						//if($obj->oTableTools->aButtons==null)
						//$obj->oTableTools->aButtons = array("xls");
											    
						"oColVis": { "buttonText" : 'Voir/Cacher',
							"aiExclude": [0,1] // Not cacheable _id and name
						},
						//$obj->oColVis->bRestore = true;
						//$obj->oColVis->sAlign = 'left';
										            
						// Avec export Excel
		<?php if (!empty($obj->sDom)) : ?>
							//"sDom": "Cl<fr>t<\"clear\"rtip>",
							"sDom": "<?php echo $obj->sDom; ?>",
		<?php else : ?>
							"sDom": "C<\"clear\"fr>lt<\"clear\"rtip>",
							//"sDom": "TC<\"clear\"fr>lt<\"clear\"rtip>",
		<?php endif; ?>
						// bottons
		<?php if ($obj->oTableTools->aButtons != null) : ?>
							"oTableTools" : { "aButtons": [
			<?php foreach ($obj->oTableTools->aButtons as $i => $aRow): ?>
											{
				<?php foreach ($aRow as $key => $fields): ?>
					<?php if ($key == "fnClick" || $key == "fnAjaxComplete") : ?>
																"<?php echo $key; ?>": <?php echo $fields; ?>,	    
					<?php else : ?>
																"<?php echo $key; ?>":"<?php echo $fields; ?>",
					<?php endif; ?>
				<?php endforeach; ?>
												},
			<?php endforeach; ?>
									]},
															 
		<?php endif; ?>
		<?php if (!defined('NOLOGIN')) : ?>
			<?php if (isset($obj->fnDrawCallback)): ?>
									"fnDrawCallback": <?php echo $obj->fnDrawCallback; ?>,
			<?php else : ?>
									// jeditable
									"fnDrawCallback": function () {
										var columns = [
				<?php foreach ($obj->aoColumns as $i => $aRow) : ?>
													"<?php echo $aRow->mDataProp; ?>",
				<?php endforeach; ?>
											];
											$("td.edit", this.fnGetNodes()).editable( '<?php echo DOL_URL_ROOT . '/core/ajax/saveinplace.php'; ?>?json=edit&class=<?php echo get_class($this); ?>', {
												"callback": function( sValue, y ) {
													oTable.fnDraw();
												},
												"submitdata": function ( value, settings ) {
													return { "id": oTable.fnGetData( this.parentNode, 0), 
														"key": columns[oTable.fnGetPosition( this )[2]]};
												},
												"height": "14px",
												"tooltip": "Cliquer pour éditer...",
												"indicator" : "<?php echo '<div style=\"text-align: center;\"><img src=\"' . DOL_URL_ROOT . '/theme/' . $conf->theme . '/img/working.gif\" border=\"0\" alt=\"Saving...\" title=\"Enregistrement en cours\" /></div>'; ?>",
												"placeholder" : ""
																				                
											} );
											$("td.select", this.fnGetNodes()).editable( '<?php echo DOL_URL_ROOT . '/core/ajax/saveinplace.php'; ?>?json=edit&class=<?php echo get_class($this); ?>', {
												"callback": function( sValue, y ) {
													oTable.fnDraw();
												},
												"submitdata": function ( value, settings ) {
													//alert( 'Number of rows: '+ oTable.fnGetData( this.parentNode, oTable.fnGetPosition( this )[2] ));
													return { "id": oTable.fnGetData( this.parentNode, 0), 
														"key": columns[oTable.fnGetPosition( this )[2]]};
												},
												"loadurl" : '<?php echo DOL_URL_ROOT . '/core/ajax/loadinplace.php'; ?>?json=Status&class=<?php echo get_class($this); ?>',
												"type" : 'select',
												"submit" : 'OK',
												"height": "14px",
												"tooltip": "Cliquer pour éditer...",
												"indicator" : "<?php echo '<div style=\"text-align: center;\"><img src=\"' . DOL_URL_ROOT . '/theme/' . $conf->theme . '/img/working.gif\" border=\"0\" alt=\"Saving...\" title=\"Enregistrement en cours\" /></div>'; ?>",
												"placeholder" : ""
																				                
											} );
										}
			<?php endif; ?>
		<?php endif; ?>
					});
		<?php if ($ColSearch) : ?>
						$("tfoot input").keyup( function () {
							/* Filter on the column */
							var id = $(this).parent().attr("id");
							oTable.fnFilter( this.value, id);
						} );
						/*send selected level value to server */        
						$("tfoot #level").change( function () {
							/* Filter on the column */
							var id = $(this).parent().attr("id");
							var value = $(this).val();
							oTable.fnFilter( value, id);
						} );
						/*send selected stcomm value to server */   
						$("tfoot .flat").change( function () {
							/* Filter on the column */
							var id = $(this).parent().attr("id");
							var value = $(this).val();
							oTable.fnFilter( value, id);
						} );
		<?php endif; ?>
					// Select_all
					$(document).ready(function() {
						prth_datatable.dt_actions();
					});
				});
		</script>
		<?php
		//$output.= "});"; // ATTENTION AUTOFILL NOT COMPATIBLE WITH COLVIS !!!!
		/* $output.= 'new AutoFill( oTable, {
		  "aoColumnDefs": [
		  {
		  "bEnable":false,
		  "aTargets": [ 0,1,2,3,5,6,8]
		  },
		  {
		  "fnCallback": function ( ao ) {
		  var n = document.getElementById(\'output\');
		  for ( var i=0, iLen=ao.length ; i<iLen ; i++ ) {
		  n.innerHTML += "Update: old value: {"+
		  ao[i].oldValue+"} - new value: {"+ao[i].newValue+"}<br>";
		  }
		  n.scrollTop = n.scrollHeight;
		  },
		  "bEnable" : true,
		  "aTargets": [ 4,7 ]
		  }]
		  } );'; */

		return;
	}

	/**
	 * 	Contruct a HTML From for a fields
	 *
	 * 	@param	array	$aRow		parameter of the field
	 * 	@param	string	$key		Name of the field
	 * 	@param	string	$cssClass	CSS Classe for the form
	 * 	@return	string
	 */
	public function form($aRow, $key, $cssClass) {
		global $langs;

		$rtr = "";

		if ($aRow->enable) {
			$rtr.= '<div class="formRow">' . "\n";
			$rtr.= '<label for="' . $key . '">' . $langs->trans($key) . '</label>' . "\n";
			$rtr.= '<input type="text" maxlength="' . $aRow->length . '" id="' . $key . '" name="' . $key . '" value="' . $this->values->$key . '" class="input-text ' . $cssClass . '" />' . "\n";
			$rtr.= '</div>' . "\n";
		}
		return $rtr;
	}

	/**
	 *  For Generate fnRender param for a datatable parameter
	 *
	 *  @param $obj object of aocolumns parameters
	 *  @param $ref_css name of #list
	 *  @return string
	 */
	public function datatablesFnRender($key, $type, $url = "") {
		global $langs, $conf;

		if ($type == "url") {
			if (empty($url)) // default url
				$url = DOL_URL_ROOT . '/' . strtolower(get_class($this)) . '/fiche.php?id=';

			$rtr = 'function(obj) {
				var ar = [];
				ar[ar.length] = "<img src=\"' . DOL_URL_ROOT . '/theme/' . $conf->theme . $this->fk_extrafields->ico . '\" border=\"0\" alt=\"' . $langs->trans("See " . get_class($this)) . ' : ";
				ar[ar.length] = obj.aData.' . $key . '.toString();
				ar[ar.length] = "\" title=\"' . $langs->trans("See " . get_class($this)) . ' : ";
				ar[ar.length] = obj.aData.' . $key . '.toString();
				ar[ar.length] = "\"></a> <a href=\"' . $url . '";
				ar[ar.length] = obj.aData._id;
				ar[ar.length] = "\">";
				ar[ar.length] = obj.aData.' . $key . '.toString();
				ar[ar.length] = "</a>";
				var str = ar.join("");
				return str;
			}';
		}
		elseif ($type == "date") {
			$rtr = 'function(obj) {
			if(obj.aData.' . $key . ')
			{
				var date = new Date(obj.aData.' . $key . '*1000);
				return date.toLocaleDateString();
			}
			else
				return null;
			}';
		} elseif ($type == "datetime") {
			$rtr = 'function(obj) {
			if(obj.aData.' . $key . ')
			{
				var date = new Date(obj.aData.' . $key . '*1000);
				return date.toLocaleDateString()+"\n"+date.toLocaleTimeString();
			}
			else
				return null;
			}';
		} elseif ($type == "status") {
			$rtr = 'function(obj) {
					var status = new Array();
					var stat = obj.aData.' . $key . ';
					if(stat === undefined)
						stat = "DISABLE";';
			foreach ($this->fk_extrafields->fields->$key->values as $key => $aRow) {
				if (isset($aRow->label))
					$rtr.= 'status["' . $key . '"]= new Array("' . $langs->trans($aRow->label) . '","' . $aRow->cssClass . '");';
				else
					$rtr.= 'status["' . $key . '"]= new Array("' . $langs->trans($key) . '","' . $aRow->cssClass . '");';
			}
			$rtr.= 'var ar = [];
				ar[ar.length] = "<span class=\"lbl ";
				ar[ar.length] = status[stat][1];
				ar[ar.length] = " sl_status\">";
				ar[ar.length] = status[stat][0];
				ar[ar.length] = "</span>";
				var str = ar.join("");
				return str;
			}';
		}
		elseif ($type == "attachment") {
			$url_server = $this->couchdb->getServerUri() . "/" . $this->couchdb->getDatabaseName();

			$rtr = 'function(obj) {
				var ar = [];
				ar[ar.length] = "<img src=\"' . DOL_URL_ROOT . '/theme/' . $conf->theme . $this->fk_extrafields->ico . '\" border=\"0\" alt=\"' . $langs->trans("See " . get_class($this)) . ' : ";
				ar[ar.length] = obj.aData.' . $key . '.toString();
				ar[ar.length] = "\" title=\"' . $langs->trans("See " . get_class($this)) . ' : ";
				ar[ar.length] = obj.aData.' . $key . '.toString();
				ar[ar.length] = "\"></a> <a href=\"' . $url_server . '/";
				ar[ar.length] = obj.aData._id;
				ar[ar.length] = "/";
				ar[ar.length] = obj.aData.' . $key . '.toString();
				ar[ar.length] = "\">";
				ar[ar.length] = obj.aData.' . $key . '.toString();
				ar[ar.length] = "</a>";
				var str = ar.join("");
				return str;
			}';
		} elseif ($type == "sizeMo") {
			$rtr = 'function(obj) {
				var ar = [];
			if(obj.aData.' . $key . ')
			{
				var size = obj.aData.' . $key . '/1000000;
				size = (Math.round(size*100))/100;
				ar[ar.length] = size;
				ar[ar.length] = " Mo";
				var str = ar.join("");
				return str;
			}
			else
			{
				ar[ar.length] = "0 Mo";
				return null;
			}
			}';
		} else {
			dol_print_error($db, "Type of fnRender must be url, date, datetime, attachment or status");
			exit;
		}

		return $rtr;
	}

	/**
	 * Function for ajax inbox to create an new object
	 * @param	$url	string		url of the create page
	 * @return string
	 */
	public function buttonCreate($url) {
		global $langs;

		print '<a href="#fd_input" class="gh_button pill icon add" id="fd3">' . $langs->trans("Create") . '</a>';
		?>
		<div style="display:none">
			<div id="inlineDialog">
				<div id="fd_input">
					<div class="fd3_pane">
						<form action="<?php echo $url; ?>" class="nice" style="width:220px">
							<label><?php echo $this->fk_extrafields->labelCreate; ?></label>
							<input type="text" class="input-text fd3_name_input expand" name="id" />
							<a href="#" class="gh_button small pill fd3_submit">Create</a>
						</form>
					</div>
				</div>
			</div>
		</div>
		<?php ?>
		<script type="text/javascript" charset="utf-8">
				$(document).ready(function() {
					$("#fd3").fancybox({
						'overlayOpacity'	: '0.2',
						'transitionIn'		: 'elastic',
						'transitionOut'		: 'fade',
						'onCleanup'			: function() {
							if($('.fd3_pane:first').is(':hidden')){$('.fd3_pane').toggle();$.fancybox.resize();}
							$('.fd3_pane label.error').remove();
						}
					});
				});
		</script>
		<?php
		return 1;
	}

}
?>
