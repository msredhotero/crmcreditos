<?php

class Forma2{
	
	
	private $nuevo = true;
	private $permite = true;
	private $siguiente = true;
	private $lectura = true;	
	private $datos;
	private $data_values = array();
	private $style_name = false;	
	private $campos_no_en_bd = array();
	private $forma;
	
	public function __construct()
	{
		
	}
	
		
	/**
	 * @return boolean $nuevo
	 */
	public function get_nuevo()
	{
		return $this->nuevo;
	}

	/**
	 * @param boolean $nuevo
	 */
	public function set_nuevo($nuevo)
	{
		$this->nuevo = $nuevo;
	}
	
	/**
	 * @return boolean $lectura
	 */
	public function get_lectura()
	{
		return $this->lectura;
	}

	/**
	 * @param boolean $lectura
	 */
	public function set_lectura($lectura)
	{
		$this->lectura = $lectura;
	}
	
	/**
	 * @return boolean $style_name
	 */
	public function get_style_name()
	{
		return $this->style_name;
	}

	/**
	 * @param boolean $style_name
	 */
	public function set_style_name($style_name)
	{
		$this->style_name = $style_name;
	}

	/**
	 * @return array
	 */
	public function get_values(){
		return $this->data_values;
	}
		
	/**
	 * @return string
	 */
	public function get_forma_actual(){
		return $this->forma_actual;
	}
	
	/**
	 * @param string $forma_actual
	 */
	public function set_forma_actual($forma_actual){
		$this->forma_actual = $forma_actual;
	}
	
		
	/**
	 * @return mixed
	 */
	public function getDatos()
	{
		return $this->datos;
	}
	
	/**
	 * @param mixed $datos
	 */
	public function setDatos($datos)
	{
		$this->datos = $datos;
	}
	
	/**
	 * @return mixed
	 */
	public function getCampos_no_en_bd()
	{
		return $this->campos_no_en_bd;
	}
	
	
	
	private function generate_query_cuestionario($table, $fields = array()){
		$string_fields = '*';
		
		if($fields){
			$string_fields = '`'.implode('`, `', $fields).'`';
		}
		
		$qd  = 'SELECT '.$string_fields.' FROM `'.$table.'`';
		$qd .= ' WHERE';
		$qd .= ' `IdCuestionario` LIKE \''.$this->IdCuestionario.'\' LIMIT 0,1';
		
		return $qd;
	}
	
	protected function get_value($name){
		$value = '';
		
		if(isset($this->datos->$name)){
			$value = $this->datos->$name;
		}else{
			$this->campos_no_en_bd[] = $name;
		}
		
		return $value;
	}
	
	protected function set_value($name, $value){
		if(!$this->export_file){
			$this->datos->$name = $value;
		}
	}
	
	protected function set_values($fields){
		if(!$this->export_file){
			
			foreach ($fields as $field){
				$this->data_values[$field] = $this->get_value($field);
			}
		}
	}
	
	

	public function nav_bar_links($actual, $secciones, $formas){
		$links = array();
		$aplica = explode(',', $secciones);
		$this->forma_actual = $actual;
		
		foreach ($formas as $forma){
// 			$agregar_link = false;
			$titulo = $forma->tit_corto;
			
			if(!in_array($actual, $aplica)){
// 				$this->lectura = false;
			}
			
			$href = sprintf('formas.php?IdCuestionario=%s&forma=%s', $this->IdCuestionario, $forma->nombre_archivo);
				$btn_sec = array(
					'tag'    => 'a',
					'inside' => array($titulo),
					'href'   => $href,
				);				
				$links[] = $btn_sec;
// 			}
		}		
		return $links;
	}
	
	public function form($content){
		$type_inicio = 'hidden';
		
		if($this->getCampos_no_en_bd() && !$this->export_file){
			array_unshift($content, array('tag'=>'br'), array('tag'=>'div', 'class'=>'alert alert-danger', 'inside'=>array(
				array('tag'=>'h4', 'inside'=>array(
					array('tag'=>'i', 'class'=>'icon fa fa-ban'),
					'Campos que no existen',
				)),
				'<p>Dentro de la forma se están llamando estos campos, pero no estan declarados en la base de datos.</p>',
				array('tag'=>'ul', 'inside'=>array(
					'<li>'.implode('</li><li>', $this->getCampos_no_en_bd()).'</li>'
				)),
			)));
		}
		
		
		$content[] = array(
			'tag'=>'input', 
			'type'=>$type_inicio, 
			'name'=>'lectura', 
			'id'=>'lectura', 
			'class'=>'form-control',
			'value'=>$this->lectura
		);
		
		$content[] = array(
			'tag'=>'input', 
			'type'=>$type_inicio, 
			'name'=>'forma_actual',
			'id'=>'forma_actual',
			'class'=>'form-control',
			'value'=>$this->forma_actual,
		);
		
		
		
		$content[] = array('tag'=>'p', 'inside'=>array('&nbsp;'));
		
		$action = ($this->lectura)?'':'guardar.php';
		
		$form = array(array(
			'tag'   => 'form',
			'id'    => 'cuestionario',
			'method'=> 'post',
			'action'=> $action,
			'inside'=> $content
		));
		
		return $form;
	}
	
	protected  function header($h, $title){
		return array('tag'=>'section', 'class'=>'content-header', 'inside'=>array(
			array('tag'=>$h, 'class'=>'text-center', 'inside'=>array($title)),
		));
	}
	
	
	protected  function headerBorder($h, $title){
	    return array('tag'=>'section', 'class'=>'content-header-border', 'inside'=>array(
	        array('tag'=>$h, 'class'=>'text-center', 'inside'=>array($title)),
	    ));
	}
		
	protected function section($is_form, $inside){
	    $css_form = ($is_form)?' form-horizontal':'';
	    if($is_form){
	        return array('tag'=>'section', 'class'=>'contentBorder'.$css_form, 'inside'=>$inside);
	    }else{
	        return array('tag'=>'section', 'class'=>'content'.$css_form, 'inside'=>$inside);
	    }
	}
	
	protected function form_group($inside, $id = ''){
		$_fg = array('tag'=>'div', 'class'=>'row', 'inside'=>$inside);
		
		if($id != ''){
			$_fg['id'] = 'frm_g_'.$id;
		}
		
		return $_fg;
	}
	
	protected function form_label($column, $clear, $is_control, $label){
		$css_colum   = ($column)?'col-sm-'.$column:'';
		$css_clear   = ($clear)?' col-sm-offset-'.$clear:'';
		$css_control = ($is_control)?' control-label':'';
		
		$css = $css_colum.$css_clear.$css_control;
		
		$title = array();
		if(is_array($label)){
			$title = $label;
		}else{
			$title[] = $label;
		}
		
		return array('tag'=>'label', 'class'=>$css, 'inside'=>$title);
	}
	
	protected function form_column_old($column, $clear, $inside){
		$css_colum   = ($column)?'col-sm-'.$column:'';
		$css_clear   = ($clear)?' col-sm-offset-'.$clear:'';		
		$css = $css_colum.$css_clear;		
		return array('tag'=>'div', 'class'=>$css, 'inside'=>$inside);
	}

	protected function form_column($column, $clear, $inside){
		$css_colum   = ($column)?'col-sm-'.$column:'';
		$css_clear   = ($clear)?' col-sm-offset-'.$clear:'';		
		$css = $css_colum.$css_clear;		
		return array('tag'=>'div', 'class'=>$css, 'inside'=>$inside);
	}
	
	protected function form_group_title($inside, $id = ''){
		$_a = array('tag'=>'div', 'class'=>'visible-md-inline visible-lg-inline form-group '.COLOR_INPUT_HELP.' text-center', 'inside'=>$inside);
		
		if($id != ''){
			$_a['id'] = 'frm_g_'.$id;
		}
		
		return $_a;
	}
	
	protected function form_label_title($column, $clear, $is_label, $label){
		$css_colum   = ($column)?'col-sm-'.$column:'';
		$css_clear   = ($clear)?' col-sm-offset-'.$clear:'';
		$lbl_control = ($is_label)?'em':'div';
		
		$css = $css_colum.$css_clear;
		
		$title = array();
		if(is_array($label)){
			$title = $label;
		}else{
			$title[] = $label;
		}
		
		return array('tag'=>$lbl_control, 'class'=>$css, 'inside'=>$title);
	}
	
	protected function modal_information_button($modal_id, $icon_class = 'fa fa-info'){
		return array(
			'tag'=>'button',
			'type'=>'button',
			'class'=>'btn '.COLOR_MODAL_BUTTON.' btn-sm',
			'data-toggle'=>'modal',
			'data-target'=>'#md_'.$modal_id,
			'inside'=>array(array('tag'=>'i', 'class'=>$icon_class)),
		);
	}
	
	protected function modal_information_window($modal_id, $inside, $title = 'Información'){
		return array('tag'=>'div', 'id'=>'md_'.$modal_id, 'class'=>'modal fade', 'tabindex'=>'-1', 'role'=>'dialog', 'aria-labelledby'=>$modal_id.'Label', 'aria-hidden'=>'true', 'inside'=>array(
			array('tag'=>'div', 'class'=>'modal-dialog', 'role'=>'document', 'inside'=>array(
				array('tag'=>'div', 'class'=>'modal-content', 'inside'=>array(
					array('tag'=>'div', 'class'=>'modal-header', 'inside'=>array(
						array('tag'=>'h5', 'class'=>'modal-title', 'inside'=>array($title))
					)),
					array('tag'=>'div', 'class'=>'modal-body', 'inside'=>$inside),
					array('tag'=>'div', 'class'=>'modal-footer', 'inside'=>array(
						array('tag'=>'button', 'type'=>'button', 'class'=>'btn btn-primary', 'data-dismiss'=>'modal', 'inside'=>array('Aceptar')),
					)),
				)),
			))
		));
	}
	
	private function load_lectura(&$options){
		$lectura = $this->lectura;
		
		if(isset($options['lectura'])){
			$lectura = $options['lectura'];
			unset($options['lectura']);
		}
		
		return $lectura;
	}
	
	private function input_style_name($name){
		if($this->style_name){
			return array(
				'tag'=>'div',
				'class'=>'text-center text-red',
				'style'=>'margin-bottom:3px; font-weight: bold;',
				'inside'=>array($name)
			);
		}else{
			return array();
		}
	}
	
	private function input_text_general($type, $name, $options){
		$lectura = $this->load_lectura($options);
		
		if($lectura){
			$type = 'hidden';
		}
		
		$input_text = array(
			'tag'=>'input',
			'class'=>'form-control strtoupper',
			'type'=>$type,
			'name'=>$name,
			'id'=>$name,
			'value'=>$this->get_value($name),
		);
		
		$input_text = array_merge($input_text, $options);
		
		$inside = array();
		
		$inside[] = $this->input_style_name($name);
		$inside[] = $input_text;
		
		if($lectura){
			$inside[] = array(
				'tag'=>'span', 'inside'=>array($this->get_value($name)),
			);
		}
		
		$div_text = array(
			'tag'=>'div',
			'id'=>'div_'.$name,
			'inside'=>$inside,
		);
		
		return $div_text;
	}
	
	protected function input_text($name, $options = array()){
		return $this->input_text_general('text', $name, $options);
	}
	
	protected function input_hidden($name, $options = array()){
		return $this->input_text_general('hidden', $name, $options);
	}
	
	protected function input_number($name, $dec = 0, $options = array()){
		$class_number = ($dec == 0)?' positive':' decimal-'.$dec.'-places';
		$options['class'] = 'form-control '.$class_number.' text-right';
		
		$type = 'text';
		
		if($this->export_file){
			$type = 'number';
		}
		
		return $this->input_text_general($type, $name, $options);
	}
	
	protected function input_help($text, $visible_in_md = false){
		$inside = array();
		
		if(is_array($text)){
			$inside = $text;
		}else{
			$inside[] = $text;
		}
		
		$visible = 'hidden-md hidden-lg ';
		
		if($visible_in_md){
			$visible = '';
		}
		
		return array('tag'=>'em', 'class'=>$visible.'help-block '.COLOR_INPUT_HELP, 'inside'=>$inside);
	}
	
	protected function input_select($name, $config = array(), $spc = array(), $options = array()){
		$lectura = $this->load_lectura($options);
		
		$inside_options = array();
		$data_spc = array();
		
		$option_select = $this->get_value($name);
		
		$gen_options = new Options($this->cat_cuestionario_id, $option_select);
		
		if(isset($config['cat_nombre'])){
			$inside_options = $gen_options->get_options($config);
		}
		
		if($lectura){
			$select = array(
				'tag'=>'input',
				'name'=>$name,
				'id'=>$name,
				'type'=>'hidden',
				'value'=>$this->get_value($name),
			);
		}else{
			$select = array(
				'tag'=>'select',
				'class'=>'form-control select2',
				'name'=>$name,
				'id'=>$name,
				'style'=>'width: 100%;',
				'data-spc'=>implode(',', $gen_options->get_data_spc()),
				'inside'=>$inside_options
			);
		}
		
		$select_desc = $this->input_hidden($name.'_desc', array('lectura'=>$lectura));
		
		$inside = array();
		
		$inside[] = $this->input_style_name($name);
		$inside[] = $select;
		$inside[] = $select_desc;
		
		$div_select = array(
			'tag'=>'div',
			'id'=>'div_'.$name,
			'inside'=>$inside,
		);
		
		if($spc){
			$input_esp = array();
			
			if(isset($spc['especifique']) && $spc['especifique'] != ''){
				$type_especifique = $spc['especifique'];
				
				switch ($type_especifique) {
					case 'number':
						$dec = (isset($spc['dec']))?$spc['dec']:0;
						$input_esp = $this->input_number($name.'_esp', $dec);
					break;
					
					default:
						$input_esp = $this->input_text($name.'_esp');
					break;
				}
			}
			
			$div_select['inside'][] = $input_esp;
		}
		
		return $div_select;
	}
	
	protected function input_checkbox($name, $label, $spc = array(), $options = array()){
		$lectura = $this->load_lectura($options);
		
		$inside = array();
		
		$tag_label = array();
		$val = $this->get_value($name);
		
		if($lectura){
			$css = ($val)?'fa-check-square-o':'fa-square-o';
			$tag_label[] = array('tag'=>'i', 'class'=>'fa '.$css, 'style'=>'margin-left:-20px;');
			$tag_label[] = array(
				'tag'=>'input',
				'type'=>'hidden',
				'name'=>$name,
				'id'=>$name,
				'value'=>$val,
			);
		}else{
			$checkbox = array(
				'tag'=>'input',
				'type'=>'checkbox',
				'name'=>$name,
				'id'=>$name,
				'value'=>$val,
				'data-spc'=>'',
			);
			
			if($val == '1'){
				$checkbox['checked'] = 'checked';
			}
			
			if($spc){
				$checkbox['data-spc'] = '1';
			}
			
			$tag_label[] = $checkbox;
		}
		
		if($label){
			$tag_label[] = ' '.$label;
		}
		
		$inside[] = $this->input_style_name($name);
		$inside[] = array('tag'=>'label', 'style'=>'width: 100%;', 'inside'=>$tag_label);
		
		if($spc){
			$input_esp = array();
			
			if(isset($spc['especifique']) && $spc['especifique'] != ''){
				$type_especifique = $spc['especifique'];
				
				switch ($type_especifique) {
					case 'number':
						$dec = (isset($spc['dec']))?$spc['dec']:0;
						$input_esp = $this->input_number($name.'_esp', $dec);
						break;
						
					default:
						$input_esp = $this->input_text($name.'_esp');
						break;
				}
			}
			
			$inside[] = $input_esp;
		}
		
		return array('tag'=>'div', 'id'=>'div_'.$name, 'class'=>'checkbox', 'inside'=>$inside);
	}
	
	protected function input_textarea($name, $option = array()){
		$inside = array();
		
		$inside[] = $this->input_style_name($name);
		
		$ta = array(
			'tag' => 'textarea',
			'id' => $name,
			'name' => $name,
			'class' => 'form-control',
			'rows' => 3,
			'inside'=>array($this->get_value($name)),
		);
		
		if($this->lectura){
			$inside[] = array('tag'=>'div', 'style'=>'display:none;', 'inside'=>array($ta));
			$inside[] = array('tag'=>'div', 'inside'=>array(nl2br($this->get_value($name))));
		}else{
			$inside[] = $ta;
		}
		
		$div_ta = array('tag'=>'div', 'id'=>'div_'.$name, 'inside'=>$inside);
		
		return $div_ta;
	}
}

?>
