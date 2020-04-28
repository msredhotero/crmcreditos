<?php

class Options
{

	private $cat_cuestionario_id = '';

	private $option_select = '';

	private $data_spc = array();

	private $opc_descripcion = '';
	private $cat_nombre ='';

	/**
	 *
	 * @return array $data_spc
	 */
	public function get_data_spc()
	{
		return $this->data_spc;
	}

	public function getCatNombre()
	{
		return $this->cat_nombre;
	}

	public function setCatNombre($nombre)
	{
		
		$this->cat_nombre = $nombre;
	}



	public function get_descripcion()
	{
		return $this->opc_descripcion;
	}

	public function set_descripcion($descripcion)
	{
		
		return $this->opc_descripcion = $descripcion;

	}

	/**
	 *
	 * @param array $data_spc
	 */
	public function set_data_spc($data_spc)
	{
		$this->data_spc = array_merge($this->data_spc, $data_spc);
	}

	public function __construct($cat_cuestionario_id, $option_select)
	{
		$this->cat_cuestionario_id = $cat_cuestionario_id;
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
		$cat_nombre = $config['cat_nombre'];
		$this->setCatNombre( $cat_nombre);

		if ($option_select != '') {
			$this->option_select = $option_select;
		}else{
			if($cat_nombre =='nacionalidad'  ){
			#$this->option_select = 58;
			
		}

		if($cat_nombre =='dbnacionalidades'  ){
			#$this->option_select = 223; 
			
		}
		}



		$cat_nombre = $config['cat_nombre'];
		$id_cat = $config['id_cat'];
		$campo_desc = isset($config['descripcion'])?$config['descripcion']:'descripcion';


		


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

			case 'ddr':
				$string .= 'SELECT';
				$string .= ' `cat_ddr_id` AS `opc_id`';
				$string .= ' ,`descripcion` AS `opc_descripcion`';
				$string .= ' ,NULL AS `opc_nota`';
				$string .= ' ,NULL AS `opc_especifique`';
				$string .= ' FROM `catalogo`.`cat_ddr`';
				break;

			case 'cader':
				$string .= 'SELECT';
				$string .= ' `cat_cader_id` AS `opc_id`';
				$string .= ' ,`descripcion` AS `opc_descripcion`';
				$string .= ' ,NULL AS `opc_nota`';
				$string .= ' ,NULL AS `opc_especifique`';
				$string .= ' FROM `catalogo`.`cat_cader`';
				break;

			

			default:
				$string .= 'SELECT *, '.$id_cat.' AS opc_id,  UPPER('.$campo_desc.') AS descripcion FROM '.$cat_nombre.'';
				$filtrosx[] = array(
					'field' => 'cat_nombre',
					'value' => $cat_nombre
				);
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

		if($this->option_select ==''){

			
			if($this->cat_nombre =='nacionalidad'  ){
			$this->option_select = 58;
			
		}

		if($this->cat_nombre =='dbnacionalidades'  ){
			$this->option_select = 223; 
			
		}

		}

		#while ($rw = $rs->fetch_object()) {		
		while ($rw = mysql_fetch_object($rs)) {			
			$rw->descripcion = $rw->descripcion;			
			$description =  $rw->descripcion;
			
			//print_r($rw);
			$option = array(
				'tag' => 'option',
				'value' => $rw->opc_id,
				'data-description' => $rw->descripcion,
				'inside' => array(
						 $description
				)
			);



			if ($this->option_select == $rw->opc_id) {
				$option['selected'] = 'selected';				
				$this->set_descripcion($description);
			}else{



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

		#while ($rw_gpo = $rs->fetch_object()) {
		while ($rw_gpo = mysql_fetch_object($rs)) {
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