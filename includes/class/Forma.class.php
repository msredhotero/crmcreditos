<?php


class Forma{
	private $IdCuestionario = '';
	private $cat_cuestionario_id = '';
	private $nuevo = true;
	private $permite = true;
	private $siguiente = true;
	private $lectura = true;
	private $forma_actual = '';
	private $datos;
	private $data_values = array();
	private $style_name = false;
	private $export_file = false;
	
	private $titulo_cuestionario = array();
	
	private $files_js = array();
	
	private $campos_no_en_bd = array();
	
	public function __construct()
	{
		
	}
	
	/**
	 * @return string $IdCuestionario
	 */
	public function get_idCuestionario()
	{
		return $this->IdCuestionario;
	}

	/**
	 * @param string $IdCuestionario
	 */
	public function set_idCuestionario($IdCuestionario)
	{
		$this->IdCuestionario = $IdCuestionario;
	}
	
	/**
	 * @return string $cat_cuestionario_id
	 */
	public function get_cat_cuestionario_id()
	{
		return $this->cat_cuestionario_id;
	}

	/**
	 * @param string $cat_cuestionario_id
	 */
	public function set_cat_cuestionario_id($cat_cuestionario_id)
	{
		$this->cat_cuestionario_id = $cat_cuestionario_id;
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
	 * @return boolean $export_file
	 */
	public function get_export_file()
	{
		return $this->export_file;
	}

	/**
	 * @param boolean $export_file
	 */
	public function set_export_file($export_file)
	{
		$this->export_file = $export_file;
	}
	
	/**
	 * @return array
	 */
	public function get_values(){
		return $this->data_values;
	}
	
	/**
	 * @param array $titulo_cuestionario
	 */
	public function set_titulo_cuestionario($titulo_cuestionario){
		$this->titulo_cuestionario = $titulo_cuestionario;
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
	 * @return array
	 */
	public function get_titulo_cuestionario(){
		return $this->titulo_cuestionario;
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
	public function getFiles_js()
	{
		return $this->files_js;
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
	
	protected function add_file_js($dir_js_file){
		if($dir_js_file != ''){
			$this->files_js[] = $dir_js_file;
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
			

			
// 			if($agregar_link){
				$btn_sec = array(
					'tag'    => 'a',
					'inside' => array($titulo),
					'href'   => $href,
				);
				
				if($this->export_file){
					unset($btn_sec['href']);
					$btn_sec['onclick'] = 'irA(\''.$forma->nombre_archivo.'\')';
				}
				
				$links[] = $btn_sec;
// 			}
		}
		
		$seccion = $formas[$this->forma_actual];
		

		
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
			'name'=>'finalizar', 
			'id'=>'finalizar', 
			'class'=>'form-control',
			'value'=>$this->get_value('finalizar'),
		);
		
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
		
		$content[] = array(
			'tag'=>'input', 
			'type'=>$type_inicio, 
			'name'=>'IdCuestionario',
			'id'=>'IdCuestionario',
			'class'=>'form-control',
			'value'=>$this->IdCuestionario
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
		//return array('tag'=>'section', 'class'=>'content-header', 'inside'=>array(
		return array('tag'=>'section', 'class'=>'headerformulario', 'inside'=>array(
			array('tag'=>$h, 'class'=>'text-center', 'inside'=>array($title)),
		));
	}
	
	
	protected  function headerBorder($h, $title){
	    return array('tag'=>'section', 'class'=>'content-header-border', 'inside'=>array(
	        array('tag'=>$h, 'class'=>'text-center', 'inside'=>array($title)),
	    ));
	}
	
	
	
	protected function section($is_form, $inside, $id=''){
	    $css_form = ($is_form)?' form-horizontal':'';
	    if($is_form){
	        return array('tag'=>'section', 'class'=>''.$css_form, 'id'=>'seccion_'.$id, 'inside'=>$inside);
	    }else{
	        return array('tag'=>'section', 'class'=>''.$css_form, 'id'=>'seccion_'.$id, 'inside'=>$inside);
	    }
	}

	protected function title_seccion($label){
		$title = array();
		if(is_array($label)){
			$title = $label;
		}else{
			$title[] = $label;
		}
		$_a = array('tag'=>'div', 'class'=>'encabezadoDatos pt-3 mb-4 lead', 'inside'=>$title);

		return $_a;

	}
	protected function footer_form($inside){
		$footer = '';

		$footer = array('tag'=>'div', 'class'=>'footer', 'inside'=>$inside);
		return $footer;
	}

	protected function form_group($inside, $id = ''){
		$_a = array('tag'=>'div', 'class'=>' form-group row ', 'inside'=>$inside);
		#$_a = array('tag'=>'div', 'class'=>'row', 'inside'=>array(
				#array('tag'=>'div', 'class'=>'form-group', 'inside'=>$inside)
			#));
		
		if($id != ''){
			$_a['id'] = 'frm_g_'.$id;
		}
		
		return $_a;
	}

	protected function row_col_label($size, $label, $refLabel, $inside){
		$class = 'col-lg-'.$size.' col-md-'.$size.' col-sm-'.$size.' col-xs-12';
		$title = array();
		if(is_array($label)){
			$title = $label;
		}else{
			$title[] = $label;
		}
		$_a  = array('tag'=>'div', 'class'=>$class, 'stlye'=>'display:block', 'inside'=>array(
			array('tag'=>'label','for'=> $refLabel, 'class' =>'control-label', 'style'=>'text-align:left', 'inside'=>$title ),
			array('tag'=>'div', 'class'=>'input-group col-12', 'inside'=> $inside),

		),);
		return $_a;
	}


	protected function  f1_steps($data_now_value =0, $number_steps = 0, $width = 0, $inside ){
		$dv = ($data_now_value)? $data_now_value:16.6;
		$ns = ($number_steps)?$number_steps:3;
		$st = ($width)?'width: '.$width.'%;':'width: 16.66%;';
		$progres = $this->f1_progres($dv,$ns, $st, $inside);
		//$inside = array_unshift($inside , $progres );
		#print_r($inside);
		#die();
		$content = array('tag'=>'div', 'class'=>'f1-steps', 'inside'=>array(
					array('tag'=>'div', 'class'=>'f1-progress-line', 'data-now-value'=>$dv, 'data-number-of-steps'=>$ns, 'style'=>$st, 'inside'=>$inside),				
			));
			
		$content = array('tag'=>'div', 'class'=>'f1-steps', 'inside'=>$inside);
					                    			
		return $content;
	}

	protected function f1_progres($dv, $ns, $st){
		$content = array('tag'=>'div', 'class'=>'f1-progress', 'inside'=>array(
						array('tag'=>'div', 'class'=>'f1-progress-line', 'data-now-value'=>$dv, 'data-number-of-steps'=>$ns, 'style'=>$st, 'inside'=>array()),
					));				                    			
		return $content;
	}

	protected function f1_step($active = false, $icon,$tex)
	{
		$class =($active)?'f1-step active':'f1-step';
		$classIcon = 'fa '.trim($icon);

		$title = array();
		if(is_array($tex)){
			$title = $tex;
		}else{
			$title[] = $tex;
		}
		$content = array('tag'=>'div', 'class'=>$class, 'inside'=>array(
				array('tag'=>'div', 'class'=>'f1-step-icon', 'inside'=>array(
					array('tag'=>'i', 'class'=>$classIcon, 'inside'=>array(
						array('tag'=>'p', 'inside'=>$title)
					)),
				)),
			));

		$content = array('tag'=>'div', 'class'=>$class, 'inside'=>array(
				array('tag'=>'div', 'class'=>'f1-step-icon', 'inside'=>array(
					array('tag'=>'i', 'class'=>$classIcon, 'inside'=>array()),		
				)),
				array('tag'=>'p', 'inside'=>$title),
			));
		
		return $content;		
	}

	protected function f1_fieldset($tex = array(), $inside){
		$title = array();
		if(is_array($tex)){
			$title = $tex;
		}else{
			$title[] = $tex;
		}
		//print_r($inside);
		$content = array('tag'=>'fieldset', 'inside'=>array(
					array('tag'=>'p', 'inside'=>$title ),
					array('tag'=>'span', 'inside'=>$inside),
		));

		//return array('tag'=>'fieldset', 'inside'=>$inside);
		return $content;
		


	}

	protected function f1_buttons($siguente =true, $anterior = true, $submit = false, $class =''){
		$botones1 = array('Anterior');
		$botones2 = array('Siguiente');
		$botones3 = array('Enviar solicitud');
		$content = array();
		if($siguente && $anterior && !$submit){
			$content = array('tag'=>'div', 'class'=>'f1-buttons', 'inside'=>array(
							array('tag'=>'button', 'type'=>'submit', 'class'=>'btnw btn-previous btn-primary btn-sm', 'inside'=>$botones1),
							array('tag'=>'button', 'type'=>'submit', 'class'=>'btnw btn-next btn-primary btn-sm', 'inside'=>$botones2),
					));
				
		}else if($siguente){
			$content = array('tag'=>'div', 'class'=>'f1-buttons', 'inside'=>array(						
						array('tag'=>'button', 'type'=>'submit', 'class'=>'btnw btn-next btn-primary btn-sm', 'inside'=>$botones2),
					));
				

		}else if($anterior && !$submit){
			$content = array('tag'=>'div', 'class'=>'f1-buttons', 'inside'=>array(						
						array('tag'=>'button', 'type'=>'submit', 'class'=>'btnw btn-previous btn-primary btn-sm', 'inside'=>$botones1),
					));
				
		}else if($submit && $anterior){
			$content = array('tag'=>'div', 'class'=>'f1-buttons', 'inside'=>array(	
						array('tag'=>'button', 'type'=>'submit', 'class'=>'btnw btn-previous btn-primary btn-sm', 'inside'=>$botones1),
						array('tag'=>'button', 'type'=>'submit', 'class'=>'btnw btn-next  btn-primary btn-sm'.$class , 'inside'=>$botones3),
					));
		}

		return $content;
	}
	

	
	protected function form_label($column, $clear, $is_control, $label){
		$css_colum   = ($column)?'col-sm-'.$column:'';
		$css_clear   = ($clear)?' offset-sm-'.$clear:'';
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

	protected function form_label_muted($column, $clear, $is_control, $label){
		$css_colum   = ($column)?'col-sm-'.$column:'';
		$css_clear   = ($clear)?' offset-sm-'.$clear:'';
		$css_control = ($is_control)?' control-label text-dark ':'';
		
		$css = $css_colum.$css_clear.$css_control;
		
		$title = array();
		if(is_array($label)){
			$title = $label;
		}else{
			$title[] = $label;
		}
		
		return array('tag'=>'label', 'class'=>$css, 'inside'=>$title);
	}

		protected function form_label_muted_hidden($column, $clear, $is_control, $label ,$visible_in_md =false){
		$css_colum   = ($column)?'col-sm-'.$column:'';
		$css_clear   = ($clear)?' offset-sm-'.$clear:'';
		$css_control = ($is_control)?' control-label text-dark ':'';
		
		$css = $css_colum.$css_clear.$css_control;
		
		$visible = 'visible-xl visible-lg visible-md';
		
		if($visible_in_md){
			$visible = '';
		}
		
		$title = array();
		if(is_array($label)){
			$title = $label;
		}else{
			$title[] = $label;
		}
		
		return array('tag'=>'label', 'class'=>$visible." ".$css, 'inside'=>$title);
	}

	protected function form_label_muted_justify($column, $clear, $is_control, $label){
		$css_colum   = ($column)?'col-sm-'.$column:'';
		$css_clear   = ($clear)?' offset-sm-'.$clear:'';
		$css_control = ($is_control)?'   control-label  text-justify ':'';
		
		$css = $css_colum.$css_clear.$css_control;
		
		$title = array();
		if(is_array($label)){
			$title = $label;
		}else{
			$title[] = $label;
		}


		$content = array('tag'=>'h6', 'class'=>'text-dark', 'inside'=>array($title));	
		//return array('tag'=>'label', 'class'=>$css, 'inside'=>$title);

		return array('tag'=>'label', 'class'=>$css, 'inside'=>array(
						array('tag'=>'h6', 'class'=>'text-dark', 'inside'=>$title),
					));
	}
	
	protected function form_column($column, $clear, $inside){
		$css_colum   = ($column)?'col-sm-'.$column:'';
		$css_clear   = ($clear)?' offset-sm-'.$clear:'';
		
		$css = $css_colum.$css_clear;
		
		return array('tag'=>'div', 'class'=>$css, 'inside'=>$inside);
	}
	
	protected function form_group_title($inside, $id = ''){
		$_a = array('tag'=>'div', 'class'=>'visible-md-inline visible-lg-inline form-group  text-center', 'inside'=>$inside);
		
		if($id != ''){
			$_a['id'] = 'frm_g_'.$id;
		}
		
		return $_a;
	}
	
	protected function form_label_title($column, $clear, $is_label, $label){
		$css_colum   = ($column)?'col-sm-'.$column:'';
		$css_clear   = ($clear)?' offset-sm-'.$clear:'';
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
			'class'=>'form-control strtoupper form-control-sm',
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
			'class' =>'form-line col-12',
			'inside'=>$inside,
		);
		
		return $div_text;
	}

	private function input_text_general_addon($type, $name, $options){
		$lectura = $this->load_lectura($options);
		
		if($lectura){
			$type = 'hidden';
		}
		
		$input_text = array(
			'tag'=>'input',
			'class'=>'form-control strtoupper form-control-sm',
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
			'class' =>'form-line col-10',
			'inside'=>$inside,
		);
		
		return $div_text;
	}
	
	protected function input_text($name, $options = array()){
		return $this->input_text_general('text', $name, $options);
	}

	protected function input_text_addon($name, $options = array()){
		return $this->input_text_general_addon('text', $name, $options);
	}

	protected function input_date($name, $options = array()){
		return $this->input_text_general('date', $name, $options);
	}
	
	protected function input_hidden($name, $options = array()){
		return $this->input_text_general('hidden', $name, $options);
	}
	protected function muestra_descripcion($camponame,$catalogo,$campoWh, $valorWh ){
		$query = new Query();
		$valorCampo = '';
		if($valorWh !='' && $valorWh != 0 ){
			$sqlQuery = "SELECT ".$camponame." as valorCampo FROM ".$catalogo." WHERE ".$campoWh ." = ".$valorWh;
			#echo $sqlQuery ."\n";	
			$query->setQuery($sqlQuery);
			$rsQ = $query->eject();
			$objQ = $query->fetchObject($rsQ);
			$valorCampo = $objQ->valorCampo;
		}
		
		$inside[] = array(
				'tag'=>'span', 'inside'=>array($valorCampo),
		);		
		$div_text = array(
			'tag'=>'div',
			'id'=>'div_'.$camponame,
			'class' =>'form-line col-10',
			'inside'=>$inside,
		);
		return $div_text;

	}


	protected function muestra_descripcion_val($camponame){
		$query = new Query();
		$valorCampo = '';	
		$inside[] = array('tag'=>'span', 'inside'=>array($this->get_value($camponame)));		
		$div_text = array(
			'tag'=>'div',
			'id'=>'div_'.$camponame,
			'class' =>'form-line col-10',
			'inside'=>$inside,
		);
		return $div_text;

	}

	protected function input_file($name,  $options = array()){
		$lectura = $this->load_lectura($options);
		$idCG = $this->get_value('idcontratoglobal');
		#print_r($this->getDatos());

		$ruta_archivo = DIR_LOCAL."upload/".$idCG."/".$this->get_value('documento_'.$name);
		$ruta_archivo = DIR_LOCAL.trim($this->get_value('ruta_'.$name),'/').'/'.$this->get_value('documento_'.$name);;
		#echo $ruta_archivo."\n";
		$descripcion = $options['desc'];
		$req = $options['req'];
		$especificaciones = $options['det'];
		$type = 'file';
		if($lectura){
			$type = 'hidden';
		}
		
		$input_file = array(
			'tag'=>'input',
			'class'=>'form-control strtoupper form-control-sm',
			'type'=>$type,
			'name'=>$name,
			'id'=>$name,
			'value'=>$this->get_value($name),
		);
		
		$input_file = array_merge($input_file, $options);
		
		$inside = array();
		
		$inside[] = $this->input_style_name($name);
		$inside[] = $input_file;
		
		if($lectura){
			$inside[] = array(
				'tag'=>'span', 'inside'=>array($this->get_value($name)),
			);
		}
		
		$div_text = array(
			'tag'=>'div',
			'id'=>'div_'.$name,
			'class' =>'form-line col-12',
			'inside'=>$inside,
		);
		
		


	$div_file =	array('tag'=>'div', 'class'=>'documento_'.$name.' form-group', 'inside'=>array(
				array('tag'=>'label', 'for'=>$name.'InputFile', 'class'=>'text-dark', 'inside'=>array($descripcion)),
				array('tag'=>'div', 'class'=>'input-group', 'inside'=>array(
					array('tag'=>'div', 'class'=>'custom-file', 'inside'=>array(
						array('tag'=>'input', 'type'=>'file', 'class'=>'custom-file-input', 'id'=>'customFileLangHTML'.$name, 'name'=>$name, 'value'=>$this->get_value('documento_'.$name), 'inside'=>array()),
						array('tag'=>'label', 'class'=>'custom-file-label ', 'for'=>'customFileLangHTML'.$name,  'data-browse'=>'Cargar',  'inside'=>array('')),

					)),
				)),
				array('tag'=>'div', 'class'=>'text-info', 'inside'=>array($especificaciones)),
				array('tag'=>'input', 'type'=>'hidden', 'name'=>$name.'_requerido', 'id'=>$name.'_requerido', 'value'=>$req),
				array('tag'=>'div', 'inside'=>array($this->get_value($name))),
				
			));

	if($this->get_value('documento_'.$name)){
			$div_file = array(
				'tag'=>'div', 'id'=>'div_documento_'.$name, 'inside'=>array(
					array('tag'=>'label', 'for'=>$name.'InputFile', 'class'=>'text-dark', 'inside'=>array($descripcion)),
					array('tag'=>'div', 'inside'=>array(
						array('tag'=>'a', 'target'=>"_blank", 'href'=>$ruta_archivo , 'inside'=>array($this->get_value('documento_'.$name))),

						)),)
			);
			// si es administracion puede cambiar el documento
			#if()
		}

		return $div_file;
	
	}

	protected function cardOutLine($titulo, $mensaje, $color= NULL)
	{
		$colorCard = (!empty($color))?$color:'card-warning';
		$card = 
					array('tag'=>'div', 'class'=>'', 'inside'=>array(
						array('tag'=>'div', 'class'=>'card card-outline '.$colorCard.'', 'inside'=>array(
							array('tag'=>'div', 'class'=>'card-header', 'inside'=>array(
								array('tag'=>'h3', 'class'=>'card-title', 'inside'=>array($titulo)),
								array('tag'=>'div', 'class'=>'card-tools', 'inside'=>array(
									array('tag'=>'button', 'type'=>'button', 'data-card-widget'=>'remove' , 'class'=>'btn btn-tool', 'inside'=>array(
										array('tag'=>'i', 'class'=>'fas x', 'inside'=>array('x')),		
									)),
								)),
							)),
							array('tag'=>'div', 'class'=>'card-body', 'inside'=>array($mensaje)),

						)),
					));		
	

		return $card;

		// return array('tag'=>'section', 'class'=>'headerformulario', 'inside'=>array(
		// 	array('tag'=>$h, 'class'=>'text-center', 'inside'=>array($title)),
		// ));
	}

	protected function botonGuardar($id, $value, $class = NULL, $inside){
		$lectura = $this->load_lectura($options);
		$disable = ($lectura)?'disabled':'';
        if($lectura){
			return array('tag'=>'row col-sm-2', 'class'=>'', 'inside'=>array(
						array('tag'=>'button', 'id'=>'btn_guardar', 'disabled'=>$disable, 'value'=>$value, 'class'=>'btn btn-block btn-info btn-sm col-sm-1 col-12 float-right '. $class, 'inside'=>array($inside)),
					));
		}else{
			return array('tag'=>'row col-sm-2', 'class'=>'', 'inside'=>array(
						array('tag'=>'button', 'id'=>'btn_guardar',  'value'=>$value, 'class'=>'btn btn-block btn-info btn-sm col-sm-1 col-12 float-right '. $class, 'inside'=>array($inside)),
					));

		}


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
		
		return array('tag'=>'div', 'class'=>$visible.'help-block  text-info ', 'inside'=>$inside);
	}
	
	protected function input_select($name ,$config = array(), $spc = array(), $options = array()){
		$lectura = $this->load_lectura($options);

		#print_r($config);
		
		$inside_options = array();
		$data_spc = array();
		
		$option_select = $this->get_value($name);
	#	echo $option_select ,"1********\n";
		
		$gen_options = new Options($this->cat_cuestionario_id, $option_select );
		
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
				'class'=>'form-control select2 form-control-sm',
				'name'=>$name,
				'id'=>$name,
				'style'=>'width: 100%;',
				'data-spc'=>implode(',', $gen_options->get_data_spc()),
				'inside'=>$inside_options
			);
		}
		
		#$select_desc = $this->input_hidden('descripcion', array('lectura'=>$lectura));
		
		$inside = array();
		$desc = $gen_options->get_descripcion();

		#echo "****=>".$desc;
		
		$inside[] = $this->input_style_name($name);
		$inside[] = $select;
		
		if($lectura){
			$inside[] = array('tag'=>'span', 'inside'=>array($desc));
			$inside[] = $select;
		}
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
			$css = ($val)?'fa-check-square pl-3':'fa-square-o';
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
		$inside[] = array('tag'=>'label', 'style'=>'width: 100%;', 'class'=>'text-dark text-justify', 'inside'=>$tag_label);
		
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
