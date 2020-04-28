<?php

class TagFormatter{
	private $formatter = false;
	private $char_break_line = "\n";
	private $char_tabulator = "\t";
	private $tag_self_closers = array('input', 'img', 'hr', 'br', 'meta', 'link');
	private $tag_no_break_line = array('span', 'i');
	private $tag_no_formatter = array('title');
	
	public function __construct($formatter = false){
		$this->formatter = $formatter;
	}
	
	public function set_formatter($formatter){
		$this->formatter = $formatter;
	}
	
	public function set_char_break_line($char_break_line){
		$this->char_break_line = $char_break_line;
	}
	
	public function set_char_tabulator($char_tabulator){
		$this->char_tabulator = $char_tabulator;
	}
	
	public function tag_no_break_line_add($tag){
		if(is_string($tag) && !in_array($tag, $this->tag_no_break_line)){
			$this->tag_no_break_line[] = $tag;
		}
	}
	
	public function tag_no_break_line_delete($tag){
		if(is_string($tag)){
			if (($key = array_search($tag, $this->tag_no_break_line)) !== false) {
				unset($this->tag_no_break_line[$key]);
			}
		}
	}
	
	public function tags($tags, $level = 0){
		$string = '';
		
		if($tags){
			foreach ($tags as $tag) {
				if($tag || is_string($tag)){
					$string .= $this->tag($tag, $level);
				}
			}
		}
		
		return $string;
	}
	
	public function tag($tag, $level, $in_formatter = true){
		$string = '';
		
		$tab = '';
		$brk = '';
		
		if($this->formatter && $level > 0 && $in_formatter){
			$tab = str_repeat($this->char_tabulator, $level);
		}
		
		if($this->formatter && $in_formatter){
			$brk = $this->char_break_line;
		}
		
		if(is_array($tag) && key_exists('tag', $tag) && $tag['tag'] != ''){
			$brk_inside = $brk;
			$tab_inside = $tab;
			$name_tag = $tag['tag'];
			unset($tag['tag']);
			
			if($name_tag == 'textarea'){
				$brk_inside = '';
				$tab_inside = '';
				$in_formatter = false;
			}
			
			$inside_tag = '';
			if(key_exists('inside', $tag)){
				$inside_tag = $brk_inside.$this->tags($tag['inside'], ($level + 1), $in_formatter).$tab_inside;
				unset($tag['inside']);
			}
			
			$string  = $tab.'<'.$name_tag;
			$string .= $this->tag_param($tag);
			
			if(in_array($name_tag, $this->tag_self_closers)){
				$string .= ' />'.$brk;
			}else{
				$string .= '>'.$inside_tag.'</'.$name_tag.'>'.$brk;
			}
		}else{
			$string .= $tab.$tag.$brk;
		}
		
		return $string;
	}
	
	private function tag_param($params){
		$tag_params = '';
		
		if($params){
			foreach ($params as $key => $value) {
				$tag_params .= ' '.$key.'="'.$value.'"';
			}
		}
		
		return $tag_params;
	}
}

?>
