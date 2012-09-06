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
	public $error;
	public $errors;
	public $canvas; // Contains canvas name if it is
	public $fk_extrafields;
	public $no_save = array("no_save", "global", "token", "id", "fk_extrafields", "fk_country", "couchdb", "db", "canvas",
		"error", "errors", "childtables", "element", "fk_element", "ismultientitymanaged", "dbversion");

	/**
	 * 	class constructor
	 *
	 * 	@param	couchClient	$db		Database handler
	 */
	function __construct($db) {
		$this->class = get_class($this);
		$this->db = $db;
		$this->useDatabase();
	}

	/**
	 * load couchdb parameters
	 * @param	$dbname		string			name of database
	 * @return int 
	 * 
	 */
	public function useDatabase($dbname = "") {
		global $conf, $couch;

		if (empty($this->couchdb)) {
			$this->couchdb = clone $couch;
		}

		if (!empty($dbname))
			$this->couchdb->useDatabase($dbname);
		else
			$this->couchdb->useDatabase($conf->Couchdb->name);
	}

	function fetch($rowid) { // old dolibarr rowid
		if (is_int($rowid)) {
			try {
				$result = $this->getView("rowid", array("key" => intval($rowid)));
				$this->load($result->rows[0]->value);
			} catch (Exception $e) {
				$this->error = "Fetch : Something weird happened: " . $e->getMessage() . " (errcode=" . $e->getCode() . ")\n";
				dol_print_error($this->db, $this->error);
				return 0;
			}
		} else {
			try {
				$this->load($rowid);
			} catch (Exception $e) {
				$this->error = "Fetch : Something weird happened: " . $e->getMessage() . " (errcode=" . $e->getCode() . ")\n";
				dol_print_error($this->db, $this->error);
				return 0;
			}
		}


		return 1;
	}

	function update($user) {
		if ($this->id) // only update
			$this->UserUpdate = $user->login;
		else { // Create
			$this->UserCreate = $user->login;
			$this->UserUpdate = $user->login;
		}

		return $this->record();
	}

	/**
	 * 	Set a value and modify type for couchdb
	 * @param string $key
	 * @param string $value 
	 */
	public function set($key, $value) {
		if (is_numeric($value))
			$this->$key = (int) $value;
		else
			$this->$key = $value;

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

		if ($cache) {
			$values = dol_getcache($id);
			if (is_object($values)) {
				$found = true;
			}
		}

		if (!$found) {
			$values = array();
			$values = $this->couchdb->getDoc($id); // load extrafields for class

			if ($cache) {
				dol_setcache($id, $values);
			}
		}
		$this->id = $values->_id;

		foreach (get_object_vars($values) as $key => $aRow)
			$this->$key = $aRow;

		return $values;
	}

	/**
	 * 	save values object document
	 *  @param	$cache	if true remove element from cache
	 * @return value of storeDoc
	 */
	public function record($cache = false) {
		global $conf;

		foreach (get_object_vars($this) as $key => $aRow)
			if (!empty($aRow) && !in_array($key, $this->no_save))
				$values->$key = $aRow;

		$values->class = get_class($this);

		try {
			$this->couchdb->clean($values);
			$result = $this->couchdb->storeDoc($values);
			$this->id = $result->id;
			$this->_id = $result->id;
			$this->_rev = $result->rev;
			if ($cache) {
				dol_setcache($id, $values);
			}
		} catch (Exception $e) {
			dol_print_error("", $e->getMessage());
			$this->dol_syslog(get_class($this) . "::get " . $error, LOG_WARN);
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
	public function getDoc($obj) {
	    return $this->couchdb->getDoc($obj);
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
	public function deleteDoc($obj = null) {
		if (empty($obj)) {
			$obj = new stdClass();
			$obj->_id = $this->_id;
			$obj->_rev = $this->_rev;
		}
		return $this->couchdb->deleteDoc($obj);
	}

	/**
	 * 	store a file in document
	 *  @param	$name		Name of the variable
	 *  @return value of storeAttachment
	 */
	public function storeFile($name = 'addedfile') {
		global $_FILES;

		return $this->couchdb->storeAttachment($this, $_FILES[$name]['tmp_name'], $_FILES[$name]['type'], $_FILES[$name]['name']);
	}

	/**
	 * 	get an attachement file in base64
	 *  @param	$filename		Name of the file
	 *  @return value of storeAttachment
	 */
	public function getFileBase64($filename) {
		return $this->couchdb->getAttachment($this, $filename);
	}

	/**
	 * 	get URL a of file in document
	 *  @return value URL of storeAttachment
	 */
	public function getFile($filename) {
		$url_server = $this->couchdb->getServerUri() . "/" . $this->couchdb->getDatabaseName();

		return $url_server . "/" . $this->id . "/" . $filename;
	}

	/**
	 * 	delete a file in document
	 *  @param	$filename		name of the file
	 *  @return value of storeAttachment
	 */
	public function deleteFile($filename) {
		return $this->couchdb->deleteAttachment($this, $filename);
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

		if ($cache) {
			$result = dol_getcache(get_class($this) . ":" . $name);
			if (is_object($result)) {
				$found = true;
			}
		}

		if (!$found) {
			$result = new stdClass();
			try {
				/* if (!empty($conf->view_limit))
				  $params['limit'] = $conf->global->MAIN_SIZE_LISTE_LIMIT; */
				//$params['limit'] = $conf->view_limit;
				if (is_array($params))
					$this->couchdb->setQueryParameters($params);

				$result = $this->couchdb->getView(get_class($this), $name);

				if ($cache) {
					dol_setcache(get_class($this) . ":" . $name, $result);
				}
			} catch (Exception $e) {
				error_log($e->getMessage());
				dol_syslog(get_class($this) . "::getView " . $error, LOG_WARN);
				$result->total_rows = 0;
			}
		}

		return $result;
	}

	/** Call an Indexed view with lucene on couchdb
	 * 
	 * @param	$name			string			name of the view
	 * @param	$params			array			params ['group'],['level'],['key'],...
	 * @return  array
	 */
	public function getIndexedView($name, $params = array()) {
		global $conf;

		$result = new stdClass();
		try {
			/* if (!empty($conf->view_limit))
			  $params['limit'] = $conf->global->MAIN_SIZE_LISTE_LIMIT; */
			//$params['limit'] = $conf->view_limit;

			$params['include_docs'] = true;
			$this->couchdb->setQueryParameters($params);

			$result = $this->couchdb->getIndexedView(get_class($this), $name);
		} catch (Exception $e) {
			error_log($e->getMessage());
			dol_syslog(get_class($this) . "::getView " . $error, LOG_WARN);
			$result->total_rows = 0;
		}

		return $result;
	}

	/**
	 *    Return label of status (activity, closed)
	 *
	 *    @return   string        		Libelle
	 */
	function getLibStatus() {
		return $this->LibStatus($this->Status);
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
	 *  @param	int		$statut				Id statut
	 *  @param  date	$expiration_date	Automatic Status with an expiration date (expired or actived)
	 *  @return	string						Libelle du statut
	 */
	function LibStatus($status, $params = array()) {
		global $langs, $conf;

		//if (empty($status))
		//	$status = $this->fk_extrafields->fields->Status->default;

		if (isset($params["dateEnd"]) && isset($this->fk_extrafields->fields->Status->values->$status->dateEnd)) {
			if ($params["dateEnd"] < dol_now())
				$status = $this->fk_extrafields->fields->Status->values->$status->dateEnd[0];
			else
				$status = $this->fk_extrafields->fields->Status->values->$status->dateEnd[1];
		}

		if (isset($this->fk_extrafields->fields->Status->values->$status->label))
			return '<span class="tag ' . $this->fk_extrafields->fields->Status->values->$status->cssClass . '">' . $langs->trans($this->fk_extrafields->fields->Status->values->$status->label) . '</span>';
		else
			return '<span class="tag ' . $this->fk_extrafields->fields->Status->values->$status->cssClass . '">' . $langs->trans($status) . '</span>';
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
									"sAjaxSource" : "<?php echo DOL_URL_ROOT . '/core/ajax/listDatatables.php'; ?>?json=list&bServerSide=<?php echo $obj->bServerSide; ?>&class=<?php echo get_class($this); ?>",
			<?php endif; ?>
		<?php endif; ?>
		<?php if (!empty($obj->iDisplayLength)): ?>
							"iDisplayLength": <?php echo $obj->iDisplayLength; ?>,
		<?php else : ?>
							"iDisplayLength": <?php echo (int) $conf->global->MAIN_SIZE_LISTE_LIMIT; ?>,
		<?php endif; ?>
						"aLengthMenu": [[5, 10, 25, 50, 100],[5, 10, 25, 50, 100]],
						"bProcessing": true,
						"bJQueryUI": true,
						"bAutoWidth": false,
						/*"sScrollY": "500px",
						"oScroller": {
							"loadingIndicator": true
						},*/
		<?php if ($obj->bServerSide) : ?>
							"bServerSide": true,
		<?php else : ?>
							"bServerSide": false,
		<?php endif; ?>
						"bDeferRender": true,
						"oLanguage": { "sUrl": "<?php echo DOL_URL_ROOT . '/includes/jquery/plugins/datatables/langs/' . ($langs->defaultlang ? $langs->defaultlang : "en_US") . ".txt"; ?>"},
						/*$obj->sDom = '<\"top\"Tflpi<\"clear\">>rt<\"bottom\"pi<\"clear\">>';*/
						/*"sPaginationType": 'full_numbers',*/
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
							//"sDom": "C<\"clear\"fr>lt<\"clear\"rtip>",
							"sDom": "<\"dataTables_header\"lfr>t<\"dataTables_footer\"ip>",
							//"sDom": "C<\"clear\"fr>tiS",
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
		<?php if (isset($obj->fnRowCallback)): ?>
							"fnRowCallback": <?php echo $obj->fnRowCallback; ?>,
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
											$("td.dol_edit", this.fnGetNodes()).editable( '<?php echo DOL_URL_ROOT . '/core/ajax/saveinplace.php'; ?>?json=edit&class=<?php echo get_class($this); ?>', {
												"callback": function( sValue, y ) {
													oTable.fnDraw();
													//oTable.fnReloadAjax();
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
											$("td.dol_select", this.fnGetNodes()).editable( '<?php echo DOL_URL_ROOT . '/core/ajax/saveinplace.php'; ?>?json=edit&class=<?php echo get_class($this); ?>', {
												"callback": function( sValue, y ) {
													oTable.fnDraw();
													//oTable.fnReloadAjax();
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
		global $langs, $conf;

		$form = new Form($this->db);

		$rtr = "";

		if ($aRow->enable) {
			$rtr.= '<div class="formRow elVal">' . "\n";

			$label = $langs->transcountry($key, $this->Country);
			if (!$label)
				$label = $langs->trans($key);

			$rtr.= '<label for="' . $key . '">' . $label . '</label>' . "\n";
			switch ($aRow->type) {
				case "textarea" :
					$rtr.= '<textarea maxlength="' . $aRow->length . '" class="' . $cssClass . '" id="' . $key . '" name="' . $key . '" cols="1" rows="4">' . $this->$key . '</textarea>';
					$rtr.= '<script> $(document).ready(function() { $("#' . $key . '").counter({ goal: 120 });});	</script>';
					break;
				case "select" :
					if ($cssClass == "small")
						$style = "width:200px;";
					else
						$style = "width:400px;";
					$rtr.= '<select data-placeholder="' . $langs->trans($key) . '&hellip;" class="chzn-select expand" style="' . $style . '" id="' . $key . '" name="' . $key . '" >';
					if (isset($aRow->dict)) {
						require_once(DOL_DOCUMENT_ROOT . "/admin/class/dict.class.php");
						// load from dictionnary
						try {
							$dict = new Dict($this->db);
							$values = $dict->load($aRow->dict, true);
							//filter for country
							foreach ($values->values as $idx => $row) {
								if (empty($row->pays_code) || $this->Country == $row->pays_code)
									$aRow->values[$idx] = $row;
							}
						} catch (Exception $e) {
							dol_print_error('', $e->getMessage());
						}
					}
					if (empty($this->$key))
						$this->$key = $aRow->default;

					foreach ($aRow->values as $idx => $row) {
						if ($row->enable) {
							$rtr.= '<option value="' . $idx . '"';

							if ($this->$key == $idx)
								$rtr.= ' selected="selected"';

							$rtr.= '>';

							if (isset($row->label))
								$rtr.= $langs->trans($row->label);
							else
								$rtr.= $langs->trans($idx);
							$rtr.='</option>';
						}
					}

					$rtr.= '</select>';

					break;
				case "checkbox" :
					if (isset($this->$key))
						$value = $this->$key;
					else
						$value = $aRow->default;

					if ($value)
						$rtr.= '<input type="checkbox" id="' . $key . '" name="' . $key . '" checked="checked"/>';
					else
						$rtr.= '<input type="checkbox" id="' . $key . '" name="' . $key . '" />';
					break;
				case "uploadfile" :
					$rtr.= '<input type="file" class="flat" name="' . $key . '" id="' . $key . '">';
					break;
				default :
					if (isset($aRow->mask))
						$rtr.= '<input type="text" maxlength="' . $aRow->length . '" id="' . $key . '" name="' . $key . '" value="' . $this->$key . '" class="input-text ' . $aRow->css . " " . $cssClass . '" mask="' . $key . '"/>' . "\n";
					else
						$rtr.= '<input type="text" maxlength="' . $aRow->length . '" id="' . $key . '" name="' . $key . '" value="' . $this->$key . '" class="input-text ' . $aRow->css . " " . $cssClass . '"/>' . "\n";
			}
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
	public function datatablesFnRender($key, $type, $params = array()) {
		global $langs, $conf;

		switch ($type) {
			case "url":
				if (empty($params['url'])) // default url
					$url = strtolower(get_class($this)) . '/fiche.php?id=';
				else
					$url = $params['url'];

				$rtr = 'function(obj) {
				var ar = [];
				ar[ar.length] = "<img src=\"theme/' . $conf->theme . $this->fk_extrafields->ico . '\" border=\"0\" alt=\"' . $langs->trans("See " . get_class($this)) . ' : ";
				ar[ar.length] = obj.aData.' . $key . '.toString();
				ar[ar.length] = "\" title=\"' . $langs->trans("See " . get_class($this)) . ' : ";
				ar[ar.length] = obj.aData.' . $key . '.toString();
				ar[ar.length] = "\"> <a href=\"' . $url . '";
				ar[ar.length] = obj.aData._id;
				ar[ar.length] = "\">";
				ar[ar.length] = obj.aData.' . $key . '.toString();
				ar[ar.length] = "</a>";
				var str = ar.join("");
				return str;
			}';
				break;

			case "email":
				$rtr = 'function(obj) {
				var ar = [];
				ar[ar.length] = "<a href=\"mailto:";
				ar[ar.length] = obj.aData.' . $key . '.toString();
				ar[ar.length] = "\">";
				ar[ar.length] = obj.aData.' . $key . '.toString();
				ar[ar.length] = "</a>";
				var str = ar.join("");
				return str;
			}';
				break;

			case "date":
				$rtr = 'function(obj) {
			if(obj.aData.' . $key . ')
			{
				var date = new Date(obj.aData.' . $key . '*1000);
				return date.toLocaleDateString();
			}
			else
				return null;
			}';
				break;

			case "datetime" :
				$rtr = 'function(obj) {
			if(obj.aData.' . $key . ')
			{
				var date = new Date(obj.aData.' . $key . '*1000);
				return date.toLocaleDateString()+"\n"+date.toLocaleTimeString();
			}
			else
				return null;
			}';
				break;

			case "status":
				$rtr = 'function(obj) {
					var now = Math.round(+new Date()/1000);
					var status = new Array();
					var expire = new Array();
					var stat = obj.aData.' . $key . ';
					if(stat === undefined)
						stat = "' . $this->fk_extrafields->fields->$key->default . '";';
				foreach ($this->fk_extrafields->fields->$key->values as $key => $aRow) {
					if (isset($aRow->label))
						$rtr.= 'status["' . $key . '"]= new Array("' . $langs->trans($aRow->label) . '","' . $aRow->cssClass . '");';
					else
						$rtr.= 'status["' . $key . '"]= new Array("' . $langs->trans($key) . '","' . $aRow->cssClass . '");';
					if (isset($aRow->dateEnd)) {
						$rtr.= 'var statusDateEnd = "' . $key . '";';
						foreach ($aRow->dateEnd as $idx => $row) {
							$rtr.= 'expire["' . $idx . '"]="' . $row . '";';
						}
					}
				}

				if (isset($params["dateEnd"])) {
					$rtr.= 'if(obj.aData.' . $params["dateEnd"] . ' === undefined)
						obj.aData.' . $params["dateEnd"] . ' = "";';
					$rtr.= 'if(stat == statusDateEnd && obj.aData.' . $params["dateEnd"] . ' != "")';
					$rtr.= 'if(obj.aData.' . $params["dateEnd"] . ' < now)';
					$rtr.= 'stat = expire[0];
							else stat = expire[1];';
				}
				$rtr.= 'var ar = [];
				ar[ar.length] = "<span class=\"tag ";
				ar[ar.length] = status[stat][1];
				ar[ar.length] = " \">";
				ar[ar.length] = status[stat][0];
				ar[ar.length] = "</span>";
				var str = ar.join("");
				return str;
			}';
				break;

			case "attachment" :
				$url_server = "/db/" . $this->couchdb->getDatabaseName();

				$rtr = 'function(obj) {
				var ar = [];
				ar[ar.length] = "<img src=\"theme/' . $conf->theme . $this->fk_extrafields->ico . '\" border=\"0\" alt=\"' . $langs->trans("See " . get_class($this)) . ' : ";
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
				break;

			case "sizeMo":
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
				var str = ar.join("");
				return str;
			}
			}';
				break;

			case "price":
				$rtr = 'function(obj) {
				var ar = [];
			if(obj.aData.' . $key . ')
			{
				var price = obj.aData.' . $key . ';
				price = ((Math.round(price*100))/100).toFixed(2);
				ar[ar.length] = price;
				ar[ar.length] = " €";
				var str = ar.join("");
				return str;
			}
			else
			{
				ar[ar.length] = "0.00 €";
				var str = ar.join("");
				return str;
			}
			}';
				break;
			case "pourcentage":
				$rtr = 'function(obj) {
				var ar = [];
			if(obj.aData.' . $key . ')
			{
				var total = obj.aData.' . $key . ';
				price = ((Math.round(total*100))/100).toFixed(2);
				ar[ar.length] = total;
				ar[ar.length] = " %";
				var str = ar.join("");
				return str;
			}
			else
			{
				ar[ar.length] = "0.00 %";
				var str = ar.join("");
				return str;
			}
			}';
				break;

			default :
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

	/**
	 * Compare function for sorting two aaData rows in datatable
	 */
	public function sortDatatable(&$array, $key, $dir) {
		if ($dir == "desc")
			usort($array, function($a, $b) use ($key) {
						return $a->$key > $b->$key ? -1 : 1;
					});
		else
			usort($array, function($a, $b) use ($key) {
						return $a->$key > $b->$key ? 1 : -1;
					});
	}

	/**
	 *  Return list of tags in an object
	 *
	 *  @return 	array	List of types of members
	 */
	function listTag() {
		global $conf, $langs;

		$list = array();

		$result = $this->getView('tag', array("group" => true));

		if (count($result->rows) > 0)
			foreach ($result->rows as $aRow) {
				$list[] = $langs->trans($aRow->key);
			}

		return $list;
	}

	/**
	 *    	Renvoie tags list clicable (avec eventuellement le picto)
	 *
	 * 		@param		int		$withpicto		0=Pas de picto, 1=Inclut le picto dans le lien, 2=Picto seul
	 * 		@param		int		$maxlen			length max libelle
	 * 		@return		string					String with URL
	 */
	function getTagUrl($withpicto = 0, $maxlen = 0) {
		global $langs;

		$result = '';

		if (count($this->Tag)) {
			for ($i = 0; $i < count($this->Tag); $i++) {
				$lien = '<a href="' . DOL_URL_ROOT . '/adherent/type.php?id=' . $this->Tag[$i] . '">';
				$lienfin = '</a> ';

				$picto = 'group';
				$label = $langs->trans("ShowTypeCard", $this->Tag[$i]);

				if ($withpicto)
					$result.=($lien . img_object($label, $picto) . $lienfin);
				if ($withpicto && $withpicto != 2)
					$result.=' ';
				$result.=$lien . ($maxlen ? dol_trunc($this->Tag[$i], $maxlen) : $this->Tag[$i]) . $lienfin;
			}
		}
		return $result;
	}
	
	function directory($key) {
		$couchdb = clone $this->couchdb;
		$couchdb->useDatabase("directory");
		
		$couchdb->setQueryParameters(array("key"=>$key));
		$result = $couchdb->getView("Directory","mail");
		
		return $result->rows[0]->value;
		
	}

}
?>
