<?php

namespace Botnyx\Sfe\Backend\Core\Template;

/*
	BaseLoader :Loads the base html.
	
	which is   <html><head></head><body></body></html>

*/


class BaseLoader {
	
	var $debug=true;
	var $fromFileCachePrefix = "_sbase";
	var $fromStringCachePrefix = "_fbase";
	
	function __construct($lookpaths,$clientid,$configpaths){
		$this->paths = $lookpaths;
		$this->clientid = $clientid;
		$this->tmp = $configpaths->temp;
		
	}
	
	
	function get($templateVars=array()){
		
		$loader = new \Twig\Loader\FilesystemLoader( $this->paths );			
		/*
			Add the debug extension.
		*/ 
		$twig = new \Twig\Environment($loader, [
			'cache' => $this->tmp."/".$this->clientid."/".$fromFileCachePrefix,
			'debug' => $this->debug
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
		$template = $twig->load("sfe_document.phtml");

		$html = $template->render( $templateVars );
		
		return $html;
	}
	
	
	function fromString($html,$templateVars=array()){
		
		$loader = new \Twig\Loader\FilesystemLoader( $this->paths );	
		$twig = new \Twig_Environment($loader,
			[
				'cache' => $this->tmp."/".$this->clientid."/".$fromStringCachePrefix,
				'debug' => $this->debug
			]
		);
		if($this->debug==true){
			$twig->addExtension(new \Twig_Extension_Debug() );
		}
		$template = $twig->createTemplate($html);
		
		
		return  $template->render($templateVars);
	}
	
	
	
	
	
	
}