<?php
include_once('TagFormatter.class.php');

class Formulario extends TagFormatter{
	private $title = '';
	private $nameForm = '';	
	private $content = array();
	private $footer = array();
	
	public function __construct($formatter = false){
		parent::__construct($formatter);
		
		if(isset($_SERVER['HTTPS'])){
			$_host = 'https://'.$_SERVER['HTTP_HOST'];
		}else{
			$_host = 'http://'.$_SERVER['HTTP_HOST'];
		}		
	}	
	
		
	public function add_content($content){
		$this->content = $content;
	}

	public function htmlWizard($string_return = false,  $size, $title1, $title2, $idFormulario, $action){
		$class= 'col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-10 col-lg-offset-1 form-box';
		$content = array();
		$formulario = $this->content;
		$title1a  = array();
		$title2a  = array();
		$title1a[] = $title1;
		$title2a[] = $title2;
		if(!empty($action)){
			array_push($formulario, array('tag'=>'br'), array('tag'=>'div', 'class'=>'ocultos', 'inside'=>array(
				array('tag'=>'input', 'type'=>'hidden', 'name'=>'accion', 'id'=>'accion', 'value'=>$action ),	
			)));
			$this->add_content($formulario);
		}

		
		$content = 	array('tag'=>'div', 'class'=>$class, 'inside'=>array(
					   		array('tag'=>'div', 'class'=>'card', 'inside' => array(
								array('tag'=>'div', 'class'=>'headerformulario', 'inside'=>array(
									array('tag'=>'span', 'class'=>'', 'inside'=>$title1a),
									array('tag'=>'h4', 'inside'=>$title2a),	
								)),
								array('tag'=>'div', 'class'=>'body', 'inside'=>array(
									array('tag'=>'form', 'class'=>'f1', 'rol'=>'form','id'=>$idFormulario, 'novalidate'=>'novalidate', 'inside'=>$this->content),
								)),
							)),
				   		));
				   		

			

		$h_body = array(
			array('tag'=>'div', 'class'=>'wizardCard', 'inside'=>array($content)),);



		$string_html = $this->tags($h_body);		
		if($string_return){
			return $string_html;
		}else{
			return $string_html;
		}			   			

	}

public function htmlCardFormulario($string_return = false, $class_card ='card-red', $size=12, $idFormulario, $action, $titulo){
	$titulos_blascos= array('card-red','card-navi');
	$clas_text_title =  in_array($class_card, $titulos_blascos)?'text-white':'';
	$class= 'vol-xl-'.$size.' col-lg-'.$size.' col-md-'.$size.' col-sm-'.$size.' col-xs-12';
	$content = array();
		$formulario = $this->content;
		$title1a  = array();
		$title2a  = array();
		$title1 = '';
		$title2 ='';
		$title1a[] = $title1;
		$title2a[] = $title2;
		if(!empty($action)){
			array_push($formulario, array('tag'=>'br'), array('tag'=>'div', 'class'=>'ocultos', 'inside'=>array(
				array('tag'=>'input', 'type'=>'hidden', 'name'=>'accion', 'id'=>'accion', 'value'=>$action ),	
			)));
			$this->add_content($formulario);
		}

		$content = 	array('tag'=>'div', 'class'=>'card '.$class_card, 'inside'=>array(
						array('tag'=>'div', 'class'=>'card-header','inside'=>array(
							array('tag'=>'h3', 'class'=>'card-title '.$clas_text_title , 'inside'=>array($titulo) )
						)),
						array('tag'=>'div', 'class'=>'card-body', 'inside'=>array(
							array('tag'=>'form', 'class'=>'f1s', 'enctype'=>'multipart/form-data', 'rol'=>'forms','id'=>$idFormulario, 'novalidate'=>'novalidate', 'inside'=>$this->content),

						)),
						
		));


		$h_body = array(
			array('tag'=>'div', 'class'=>$class, 'inside'=>array($content)),);



		$string_html = $this->tags($h_body);		
		if($string_return){
			return $string_html;
		}else{
			return $string_html;
		}			   			



}	

public function htmlCard($string_return = false, $class_card,  $idFormulario, $action){
		$class= 'col-lg-'.$size.' col-md-'.$size.' col-sm-'.$size.' col-xs-12';
		$content = array();
		$formulario = $this->content;
		$title1a  = array();
		$title2a  = array();
		$title1a[] = $title1;
		$title2a[] = $title2;
		if(!empty($action)){
			array_push($formulario, array('tag'=>'br'), array('tag'=>'div', 'class'=>'ocultos', 'inside'=>array(
				array('tag'=>'input', 'type'=>'hidden', 'name'=>'accion', 'id'=>'accion', 'value'=>$action ),	
			)));
			$this->add_content($formulario);
		}

		
		$content = 	array('tag'=>'div', 'class'=>'card-header', 'inside'=>array(
							array('tag'=>'h3', 'class'=>'card-title text-white', 'inside'=>array('')),
					   		array('tag'=>'div', 'class'=>'card', 'inside' => array(
								array('tag'=>'div', 'class'=>'headerformulario', 'inside'=>array(
									array('tag'=>'span', 'class'=>'', 'inside'=>$title1a),
									array('tag'=>'h4', 'inside'=>$title2a),	
								)),
								array('tag'=>'div', 'class'=>'body', 'inside'=>array(
									array('tag'=>'form', 'class'=>'f1', 'rol'=>'form','id'=>$idFormulario, 'novalidate'=>'novalidate', 'inside'=>$this->content),
								)),
							)),
				   		));
				   		

		/*$content = 	array(
						array('tag'=>'div', 'class'=>'card-header', 'inside'=>array(
							array('tag'=>'h3', 'class'='card-title text-white', 'inside'=>array('Información del cliente'</h3>
						)),
						array(),
		);*/

		$h_body = array(
			array('tag'=>'div', 'class'=>'card '.$class_card, 'inside'=>array($content)),);



		$string_html = $this->tags($h_body);		
		if($string_return){
			return $string_html;
		}else{
			return $string_html;
		}			   			

	}	

public function htmlWizardDos($string_return = false,  $size, $title1, $title2, $idFormulario, $action){
		$class= 'col-xl-12 col-lg-12 col-md-12 col-12';
		$content = array();
		$formulario = $this->content;
		$title1a  = array();
		$title2a  = array();
		$title1a[] = $title1;
		$title2a[] = $title2;
		if(!empty($action)){
			array_push($formulario, array('tag'=>'br'), array('tag'=>'div', 'class'=>'ocultos', 'inside'=>array(
				array('tag'=>'input', 'type'=>'hidden', 'name'=>'accion', 'id'=>'accion', 'value'=>$action ),	
			)));
			$this->add_content($formulario);
		}

		
		$content = 	
					   		array('tag'=>'div', 'class'=>'card card-red card-outline elevation-5', 'style'=>'text-align:center', 'inside' => array(
								array('tag'=>'div', 'class'=>'card-header', 'inside'=>array(
									array('tag'=>'span', 'class'=>'', 'inside'=>$title1a),
									array('tag'=>'h4', 'inside'=>$title2a),	
								)),
								array('tag'=>'div', 'class'=>'body', 'inside'=>array(
									array('tag'=>'form', 'class'=>'f1', 'rol'=>'form','id'=>$idFormulario, 'novalidate'=>'novalidate', 'inside'=>$this->content),
								)),
							));
				   		
				   		

			

		$h_body = array(
			array('tag'=>'div', 'class'=>$class, 'inside'=>array($content)),);



		$string_html = $this->tags($h_body);		
		if($string_return){
			return $string_html;
		}else{
			return $string_html;
		}			   			

	}	
			
public function html($string_return = false,  $card = true, $size, $title, $idFormulario, $action){
		
		$class= 'col-lg-'.$size.' col-md-'.$size.' col-sm-'.$size.' col-xs-12';
		
		$string_return = true;
		$titles = array();
		$titles[] =  $title;
		$content = array();

		$formulario = $this->content;

		if(!empty($action)){
			array_push($formulario, array('tag'=>'br'), array('tag'=>'div', 'class'=>'ocultos', 'inside'=>array(
				array('tag'=>'input', 'type'=>'hidden', 'name'=>'accion', 'id'=>'accion', 'value'=>$action ),	
			)));
			$this->add_content($formulario);
		}


		//array('tag'=>'div', 'class'=>'', 'inside'=>$this->content),
		if($card){
			$content = array('tag'=>'div', 'class'=>$class, 'inside'=>array(
				array('tag'=>'div', 'class'=>'card', 'inside' => array(
					array('tag'=>'div', 'class'=>'headerformulario', 'inside'=>$titles),
					array('tag'=>'div', 'class'=>'body', 'inside'=>array(
						array('tag'=>'form', 'class'=>'formulario', 'rol'=>'form','id'=>$idFormulario, 'novalidate'=>'novalidate', 'inside'=>$this->content),
					)),
				)),

			));

		}else{
			$content = array('tag'=>'div', 'class'=>'row', 'inside'=>array(
				array('tag'=>'div', 'class'=>'col-lg-12 col-md-12 col-sm-12 col-xs-12', 'inside' => array(
					array('tag'=>'div', 'class'=>'card', 'inside'=>$this->content),
				)),

			));

		}
		
		$h_body = array(
			array('tag'=>'div', 'class'=>'', 'inside'=>array(
						
				$content,
// 				array('tag'=>'div', 'class'=>'content-wrapper', 'inside'=>$this->content),
				array('tag'=>'div', 'class'=>''),
			)),
			
		);
		
		
		
		
		
		$string_html = $this->tags($h_body);
		
		if($string_return){
			return $string_html;
		}else{
			echo $string_html;
		}
	}


	public function generaHtml($string_return = false, $container = true, $class, $btonOpc , $idFormulario){
					
		$content = array();
		$botonClass = $btonOpc['class'];
		$botonlabel = $btonOpc['label'];
		$string_return = true;

		if($container){
			$content = array('tag'=>'div', 'class'=>'', 'inside'=>array(
				array('tag'=>'div', 'class'=>'', 'inside'=>$this->content),
			));
		}else{
			$content = array('tag'=>'div', 'class'=>'', 'inside'=>$this->content);
		}
		
		$htmlFormulario = array('tag'=>'div', 'inside'=>array(
			array('tag'=>'form', 'class'=>$claseFormulario, 'id'=>$idFormulario, 'inside'=>array(
								$content,
								array('tag'=>'button', 'class'=>$botonClass, 'inside'=>$botonlabel),
							)),),
		);
							
			//$content,
		
		$string_html = $this->tags($htmlFormulario);		
		if($string_return){
			return $string_html;
		}else{
			echo $string_html;
		}
	}
}

?>