<?php
namespace Botnyx\Sfe\Backend\Core\Template;

class BaseLoader {
	
	var $debug=true;
	
	function __construct($paths,$clientid){
		$this->paths = $paths;
		$this->clientid = $clientid;
	}
	
	
	function get(){
		
		$loader = new \Twig\Loader\FilesystemLoader( $this->paths );			
		/*
			Add the debug extension.
		*/ 
		$twig = new \Twig\Environment($loader, [
			'cache' => $this->paths['temp']."/".$this->clientid."/_base",
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

		$html = $template->render();
		
		return $html;
	}
	
	
	function fromString($html){
		
		$twig = new \Twig_Environment(
			[
				'cache' => $this->paths['temp']."/".$this->clientid."/_prepped",
				'debug' => $this->debug
			]s
		);

		$template = $twig->createTemplate($html);
		
		
		return  $template->render(['name' => 'Bob']);
	}
	
}