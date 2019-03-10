<?php

namespace Botnyx\Sfe\Backend\Core\Template;


/*
	AssetsLoader : Loads the assets needed for the html from css/jss.list files.
	
	the object contains both required css and js in an array

*/

class AssetsLoader {
	
	var $css;
	var $js;
	
	
	function __construct($path){
		if(!file_exists($path)){
			$error = 'folder does not exist: '.$path;
    		throw new Exception($error);
		}
		$this->js = $this->loadJs($path."/js.list.txt");
		$this->css = $this->loadCss($path."/css.list.txt");
	}

	private function loadJs($file){
		$scripts=[];
		
		if(!file_exists($file)){
			return array();
			//$error = 'file does not exist: '.$file;
    		//sthrow new \Exception($error);
		}
		$handle = fopen($path.'/'.$file, "r");
		if ($handle) {
			while (($line = fgets($handle)) !== false) {
				// process the line read.
				$scripts[]=trim($line);
			}
			fclose($handle);
		} else {
			// error opening the file.
		} 
		return $scripts;
	}
	
	private function loadCss ($file){
		$css=[];
		
		//$css[]="/assets/js/codeseven/toastr/2.1.1/toastr.css";
		
		if(!file_exists($file)){
			return array();
			//$error = 'file does not exist: '.$file;
    		//throw new \Exception($error);
		}
		$handle = fopen($file, "r");
		if ($handle) {
			while (($line = fgets($handle)) !== false) {
				// process the line read.
				$css[]=trim($line);
			}
			fclose($handle);
		} else {
			// error opening the file.
		} 
		return $css;
	}
	


}