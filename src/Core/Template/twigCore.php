<?php

namespace Botnyx\Sfe\Backend\Core\Template;


/*
	ElementLoader : Loads the specific elements needed for the html .
	
	
	new ElementLoader(  );
	

*/




class twigCore {
	
	
	function __construct( twigConfig $config){
		
		
		
	}
	
	private function getCacheDir(){
		return $this->tmp."/".$this->clientid."/".$fromFileCachePrefix;
	}
	
	private function getDebugDir(){
		return $this->debug;
	}
	
	private function getTemplatePaths(){
		return $this->paths;
	}
	
	
	function get($fileName,$templateVars=array()){
		/*
			
		*/
		$loader = new \Twig\Loader\FilesystemLoader( $this->getTemplatePaths() );			
		
		/*
			Add the debug extension.
		*/ 
		$twig = new \Twig\Environment($loader, [
			'cache' => $this->getCacheDir(),
			'debug' => $this->getDebugDir()
		]);

		/*
			Add the debug extension.
		*/ 
		if($this->debug==true){
			$twig->addExtension(new \Twig_Extension_Debug() );
		}
		
		/*
			Load the main html frame.
		*/
		$template = $twig->load($fileName);
		
		$html = $template->render( $templateVars );
		
		return $html;
	}
	
}