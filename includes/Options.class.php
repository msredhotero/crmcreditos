<?php

class Options
{

	private $catalogo = '';
	private $option_select = '';
	private $data_spc = array();

	/**
	 *
	 * @return array $data_spc
	 */
	public function get_data_spc()
	{
		return $this->data_spc;
	}

	/**
	 *
	 * @param array $data_spc
	 */
	public function set_data_spc($data_spc)
	{
		$this->data_spc = array_merge($this->data_spc, $data_spc);
	}

	public function __construct($catalogo, $option_select)
	{
		$this->catalogo = $catalogo;
		$this->option_select = $option_select;
	}

	public function get_options($config, $option_select = '')
	{
		$options = array();

		$options[] = array(
			'tag' => 'option',
			'value' => '',
			'inside' => array(
				'[SELECCIONAR]'
			)
		);

		if ($option_select != '') {
			$this->option_select = $option_select;
		}

		$cat_nombre = $this->catalogo;

		$filtros = array();
		if (isset($config['filtros'])) {
			$filtros = $config['filtros'];
		}

		$string = '';

		switch ($cat_nombre) {
			case 'edo':
				$string .= 'SELECT';
				$string .= ' `estado_char` AS `opc_id`';
				$string .= ' ,`descripcion` AS `opc_descripcion`';
				$string .= ' ,NULL AS `opc_nota`';
				$string .= ' ,NULL AS `opc_especifique`';
				$string .= ' FROM `catalogo`.`estado`';
				break;

			case 'mun':
				$string .= 'SELECT';
				$string .= ' `municipio_char` AS `opc_id`';
				$string .= ' ,`descripcion` AS `opc_descripcion`';
				$string .= ' ,NULL AS `opc_nota`';
				$string .= ' ,NULL AS `opc_especifique`';
				$string .= ' FROM `catalogo`.`municipio`';
				break;

			case 'loc':
				$string .= 'SELECT';
				$string .= ' `localidad_char` AS `opc_id`';
				$string .= ' ,`nom_loc` AS `opc_descripcion`';
				$string .= ' ,NULL AS `opc_nota`';
				$string .= ' ,NULL AS `opc_especifique`';
				$string .= ' FROM `catalogo`.`cat_localidad_geo`';
				break;

			default:
				$string .= 'SELECT * FROM `'.$cat_nombre.'`';
				break;
		}

		
			$options = $this->generate_options($string, $filtros, $options);
		

		return $options;
	}

	function generate_options($string_query, $filtros, $options)
	{
		$query = new Query();
		$data_spc = array();

		$string_query .= $this->filtros($filtros);

		$query->setQuery($string_query);
		$rs = $query->eject();

		while ($rw = $query->fetchObject($rs)) {
			$rw->opc_descripcion = $rw->opc_descripcion;
			$rw->opc_nota = $rw->opc_nota;
			$description = ($rw->opc_nota != '') ? $rw->opc_nota : $rw->opc_descripcion;

			$option = array(
				'tag' => 'option',
				'value' => $rw->opc_id,
				'data-description' => $rw->opc_descripcion,
				'inside' => array(
					$rw->opc_id . ' ' . $description
				)
			);

			if ($this->option_select == $rw->opc_id) {
				$option['selected'] = 'selected';
			}

			$options[] = $option;			
		}

		$this->set_data_spc($data_spc);

		return $options;
	}

	function generate_options_group($string, $filtros_gpo, $filtros, $options)
	{
		$query = new Query();

		$string_gpo = $string . $this->filtros($filtros_gpo);

		$query->setQuery($string_gpo);
		$rs = $query->eject();

		while ($rw_gpo = $query->fetchObject($rs)) {
			$filtros_opc_gpo = $filtros;
			$filtros_opc_gpo[] = array(
				'field' => 'cat_gpo_id',
				'value' => $rw_gpo->opc_id
			);

			$options_gpo = array();
			$options_gpo = $this->generate_options($string, $filtros_opc_gpo, $options_gpo);

			$options[] = array(
				'tag' => 'optgroup',
				'data-group' => $rw_gpo->opc_id,
				'label' => $rw_gpo->opc_descripcion,
				'inside' => $options_gpo
			);
		}

		return $options;
	}

	private function filtros($filtros)
	{
		$string = '';

		if ($filtros) {
			$_filtro = array();

			foreach ($filtros as $cnf_filtro) {
				$_string_filtro = '`' . $cnf_filtro['field'] . '`';

				if (is_array($cnf_filtro['value'])) {
					$_string_filtro .= ' IN (\'' . implode('\', \'', $cnf_filtro['value']) . '\')';
				} else {
					$_string_filtro .= ' LIKE \'' . $cnf_filtro['value'] . '\'';
				}

				$_filtro[] = $_string_filtro;
			}

			$string = implode(' AND ', $_filtro);
		}

		if ($string != '') {
			$string = ' WHERE ' . $string;
		}

		return $string;
	}
}

?>