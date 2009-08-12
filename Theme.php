<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Theme{
	
	private $_assets = array(),
			$_header, $_footer,	
			$_partials = array();

	public function __construct(){
		$this->ci = &get_instance();
	}

	public function setPageTitle($__title){
		$this->_assets['pageTitle'] = $__title;
	}

	public function addBreadcrumb($bc){
		$this->_assets['breadcrumbs'][] = $bc;
	}

	public function registerScript($__src){
		$this->_assets['scripts'][] = $__src;
	}

	public function registerStylesheet($__src){
		$this->_assets['stylesheets'][] = $__src;
	}

	public function registerHeader($__view){
		$this->registerPartial('header', $__view );
	}

	public function registerFooter($__view){
		$this->_footer = $__view;
	}

	public function registerMenuItem($__menu, $__title, $__url, $__class=array(), $__id=''){
		$this->_partials[$__menu]['data']['links'][$__title] = array('url' => $__url, 'id' => $__id, 'class' => $__class);
	}

	
	
	public function registerPartial($__name, $__view, $__data='', $__location='end', $__locnode=''){
		if ($__location != 'end'){
			
			// Work out our offset
			if ($__location == 'after'){ $offset = 1;}
			elseif($__location == 'before') { $offset = 0; }
			
			// Get the location to be splicing
			$keys = array_keys($this->_partials);
			$index = array_search($__locnode, $keys, true);
			
			$this->_insertIntoArray($this->_partials, $index+$offset, array( $__name => array('view' => $__view, 'data' => $__data)));
		  
		}else{
			$this->_partials[$__name] = array('view' => $__view, 'data' => $__data);
		}
		
	}
	
	public function outputPage(){

		$this->registerPartial('footer', $this->_footer, '');

		$str = '';
		foreach ($this->_partials as $name => $details){
			if ($name == 'header'){ $details['data'] = $this->_assets; }
			$str .= $this->ci->load->view($details['view'], $details['data'], true);
		}
		return $str;
	}



	/* Helper Functions */
	private function _insertIntoArray(&$__array, $__offset, $__insert){
		// Can't use array_splice due to keys
		$firstPart = array_slice($__array, 0, $__offset);
		$secondPart = array_slice($__array, $__offset);
			
		// Merge it all back into one
		$__array = array_merge($firstPart, $__insert, $secondPart);
	}
	
}

?>
