<?php
/* Copyright (C) 2013	Regis Houssin	<regis.houssin@capnetworks.com>
 * Copyright (C) 2013	Herve Prot		<herve.prot@symeos.com>
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
 */

namespace datatables;

class Datatables {

	const REQUEST_GET = 'GET';
	const REQUEST_POST = 'POST';

	protected $schema;
	protected $callbacks = array();
	protected $tabletools = array();
	protected $colvis = array();
	protected $plugins = array();
	protected $config = array();
	protected $chain = array();
	protected $params = array(
			'bProcessing'       => true,
			'bServerSide'       => false,
			'sAjaxSource'       => null,
			'bPaginate'         => true,
			'sPaginationType'   => 'full_numbers',
			'bLengthChange'     => true,
			'bFilter'           => true,
			'bSort'             => true,
			'bInfo'             => false,
			'bAutoWidth'        => false,
			'bStateSave'        => false,
			'aLengthMenu'       => array(5, 10, 20, 50, 100, 500, 'All'),
			'aaSorting'         => array(array(1, 'asc')),
			//'sScrollY'          => '400px',
			//'sScrollX'          => '100%',
			//'sScrollXInner'     => '100%',
			'bScrollCollapse'   => false,
			'bScrollInfinite'   => false,
			'bJQueryUI'         => true,
			'iDisplayLength'    => 20,
			'oLanguage'         => array(),
			'sDom'              => '<"dataTables_header"lfr>t<"dataTables_footer"p><"dataTables_actions">'
	);

	protected $htmlTemplate = "
			<table class=\"{:container_class}\" id=\"{:container_id}\">
				<thead>
					<tr>
					{:headers}
					</tr>
				</thead>
				<tbody>
				</tbody>
				<tfoot>
					<tr>
					{:footers}
					</tr>
				</tfoot>
			</table>
			";

	protected $jsTemplate = "
			<script type=\"text/javascript\">
				$(document).ready(function() {
				<!-- // <![CDATA[
					var {:var_name} = jQuery('#{:container_id}').dataTable({:config}){:chain};
					$('tfoot input').keyup( function () {
						/* Filter on the column */
						var id = $(this).parent().attr('id');
						{:var_name}.fnFilter( this.value, id);
					});
				// ]]> -->
				});
			</script>
			";

	/* ______________________________________________________________________ */

	public function __construct(array $config = array()) {
		$defaults = array(
				'request_method'	=> self::REQUEST_GET,
				'data_source'		=> null,
				'var_name'			=> 'oTable',
				'container_id'		=> 'datatableTable',
				'container_class'	=> 'display dt_act',
				'headers_th_class'	=> 'essential'
		);
		$this->config = $config + $defaults;
	}

	/* ______________________________________________________________________ */

	public function __toString() {
		$table = '';
		try {
			$table = (string) $this->render();
		} catch(\Exception $e) {
			$table = 'Could not render table, possibly caused by wrong configuration';
			$table = $e->getMessage();
		}
		return $table;
	}

	/* ______________________________________________________________________ */

	public function setConfig($name, $value) {
		return $this->config[$name] = $value;
	}

	/* ______________________________________________________________________ */

	public function unsetConfig($name) {
		unset($this->config[$name]);
	}

	/* ______________________________________________________________________ */

	public function getConfig($name) {
		return (isset($this->config[$name])) ? $this->config[$name] : null;
	}

	/* ______________________________________________________________________ */

	public function setParam($name, $value = '') {
		if($name == 'fnDrawCallback') {
			$this->params['fnDrawCallback'] = '{:callback}';
			return $this->callbacks[] = $value;
		} else if($name == 'oTableTools') {
			$this->params['oTableTools'] = '{:tabletools}';
			return $this->tabletools[] = $value;
		} else if($name == 'oColVis') {
			$this->params['oColVis'] = '{:colvis}';
			return $this->colvis[] = $value;
		}
		return $this->params[$name] = $value;
	}

	/* ______________________________________________________________________ */

	public function unsetParam($name) {
		if($name == 'fnDrawCallback') {
			$this->callbacks = array();
		} else if($name == 'oTableTools') {
			$this->tabletools = array();
		} else if($name == 'oColVis') {
			$this->colvis = array();
		}
		unset($this->params[$name]);
	}

	/* ______________________________________________________________________ */

	public function getParam($name) {
		if($name == 'fnDrawCallback') {
			return $this->callbacks;
		} else if($name == 'oTableTools') {
			return $this->tabletools;
		} else if($name == 'oColVis') {
			return $this->colvis;
		}
		return (isset($this->params[$name])) ? $this->params[$name] : null;
	}

	/* ______________________________________________________________________ */

	public function translate($key, $str) {
		$this->params['oLanguage'][$key] = $str;
	}

	/* ______________________________________________________________________ */

	public function setTranslationArray(array $t) {
		$this->params['oLanguage'] = $t;
	}

	/* ______________________________________________________________________ */

	public function plug(PluginInterface $plugin) {
		$plugin->apply($this);
	}

	/* ______________________________________________________________________ */

	public function chain($chain) {
		$chain = rtrim($chain, ';'); // removes semicolon at the end
		$this->chain[] = "{$chain}";
	}

	/* ______________________________________________________________________ */

	public function callback($callback) {
		$this->callbacks[] = $callback;
		$this->params['fnDrawCallback'] = '{:callback}';
	}

	/* ______________________________________________________________________ */

	public function tabletools($tabletools) {
		$this->tabletools[] = $tabletools;
		$this->params['oTableTools'] = '{:tabletools}';
	}

	/* ______________________________________________________________________ */

	public function colvis($colvis) {
		$this->colvis[] = $colvis;
		$this->params['oColVis'] = '{:colvis}';
	}

	/* ______________________________________________________________________ */

	public function setSchema(Schema $schema) {
		$this->schema = $schema;
	}

	/* ______________________________________________________________________ */

	public function getSchema() {
		if( ! ($this->schema instanceof Schema)) {
			throw new \RuntimeException("Datatables schema is not set.");
		}
		return $this->schema;
	}

	/* ______________________________________________________________________ */

	public function getFieldType() {
		if( ! ($this->schema instanceof Schema)) {
			throw new \RuntimeException("Datatables schema is not set.");
		}
		return $this->schema->getType();
	}

	/* ______________________________________________________________________ */

	public function formatJsonOutput(array $data, $totalRecords = null) {
		if(is_null($totalRecords)) {
			$totalRecords = count($data);
		}
		$data = array(
				'iTotalRecords'			=> $totalRecords,
				'iTotalDisplayRecords'	=> $totalRecords,
				'aaData'				=> $data
		);
		return json_encode($data);
	}

	/* ______________________________________________________________________ */

	public function render() {
		if($this->getParam('bServerSide') == true) {
			if( ! $this->getParam('sAjaxSource')) {
				if( ! $this->getConfig('data_source')) {
					throw new \RuntimeException('Data source is not set.');
				}
				$this->setParam('sAjaxSource', $this->getConfig('data_source'));
			}
		} else {
			if( ! $this->getParam('aaData')) {
				$this->setParam('aaData', array());
				// throw new \RuntimeException('Datatables: `aaData` is not set.');
			}
		}

		$this->setParam('sAjaxSource', $this->getConfig('data_source'));

		if( ! ($this->schema instanceof Schema)) {
			throw new \RuntimeException("Datatables schema is not set.");
		}

		$var_name = $this->config['var_name'];
		$container_id = $this->config['container_id'];
		$container_class = $this->config['container_class'];
		$chain = empty($this->chain) ? null : '.' . implode('.', $this->chain);
		$callback = "function(oSettings) {\n" . implode("\n", $this->callbacks) . "}\n";
		$tabletools = "{\n" . implode("\n", $this->tabletools) . "}\n";
		$colvis = "{\n" . implode("\n", $this->colvis) . "}\n";

		$i = 0;
		$cols = array();
		$render = array();
		$headers = '';
		$editable = '';
		$footers = '';
		foreach($this->schema->data() as $key => $config) {
			$cols[] = $config['aoColumns'] + array(
					'mData'				=> $key,
					'mRender'			=> (!empty($config['render']) ? '{:render_'.$key.'}' : ''),
					'sDefaultContent'	=> $config['default'] ? $config['default'] : '',
					'sWidth'			=> $config['width'] ? intval($config['width']) . 'px' : '',
					'sClass'			=> $config['class'] ? $config['class'] : '',
					'bSortable'			=> (bool) $config['sortable'],
					'bSearchable'		=> (bool) $config['searchable'],
					'bVisible'			=> (bool) $config['visible']
					//'sType'				=> 'html'
			);

			// display header label
			$headers .= "<th class=\"{$this->config['headers_th_class']}\">{$config['label']}</th>\n";

			// build editable
			if(!empty($config['editable'])) {
				$editable .= self::insert($config['editable'], $config);
			} else if($config['visible']) {
				$editable .= self::insert('null,', $config);
			}

			// display mRender
			if (!empty($config['render']))
				$render['render_'.$key] = self::insert($config['render'], $config);

			// display footer
			$footer = '';
			if(!empty($config['footer'])) {
				$footer = self::insert($config['footer'], $config);
			}
			$footers .= "<th id=\"{$i}\">{$footer}</th>\n";

			$i++;
		}
		$this->params['aoColumns'] = $cols;

		// convert 'oLanguage' to object
		$this->params['oLanguage'] = (object) $this->params['oLanguage'];

		// display editable
		$chain = preg_replace('/\{:editable\}/', $editable, $chain);
		// display editable options
		$chain = preg_replace('/\{:editable_options\}/', $editable_options, $chain);

		$config = json_encode($this->params);
		// display callback
		$config = preg_replace('/"\{:callback\}"/', $callback, $config);
		// display tabletools
		$config = preg_replace('/"\{:tabletools\}"/', $tabletools, $config);
		// display colvis
		$config = preg_replace('/"\{:colvis\}"/', $colvis, $config);

		// display render
		if (!empty($render)) {
			foreach($render as $key => $value) {
				$config = preg_replace('/"\{:'.$key.'\}"/', $value, $config);
			}
		}

		$params = compact('var_name', 'container_id', 'container_class', 'chain', 'config', 'headers', 'footers');
		return self::insert($this->htmlTemplate . $this->jsTemplate, $params);
	}

	public static function insert($str, array $data) {
		if (empty($data)) {
			return $str;
		}

		$replace = array();
		foreach ($data as $key => $value) {
			$replace['{:' . $key . '}'] = $value;
		}
		return strtr($str, $replace);
	}
}