<?php

namespace Botnyx\Sfe\Backend\Core\Template;


/*
	ElementLoader : Loads the specific elements needed for the html .
	
	
	new ElementLoader(  );
	

*/




class twigConfig {
	
	var $extensions = array();
	var $paths 		= array();
	
	var $clientid 	= "";
	
	function Exception( $type,$value ) {
		throw new \Exception("FAIL: ".ucfirst($type)." wanted " . gettype($value) . " received");
	}
	
	function __set($name, $value) {
        switch ($name) {
            case "clientid":
                $valid = is_string($value);
				$error = array( 'String',$value ); 
                break;
            case "paths":
                $valid = is_array($value);
				$error = array( 'Array',$value ); 
                break;
            case "extension":
                $valid = is_object($value);
				$error = array( 'Object',$value ); 
                break;
            case "percent":
                $valid = is_float($value) && $value >= 0 && $value <= 100;
				$error = array( 'Float',$value ); 
                break;
            default:
                $valid = false; // allow all other attempts to set values (or make this false to deny them)
				$error = array( 'Unknown variable!' ); 
        }

        if ($valid) {
            $this->{$name} = $value;

            // just for demonstration
            echo "pass: Set \$this->$name = ";
            var_dump($value);
        } else {
            // throw an error, raise an exception, or otherwise respond
			if( count($error)==1 ){
				new Exception("FAIL: "."FAIL: Cannot set \$this->$name = ");
			}else{
				$this->Exception( $type,$value );
			}
			
            // just for demonstration
            //echo "FAIL: Cannot set \$this->$name = ";
            var_dump($value);
			
        }
    }
	
	
	
	function __construct($clientid){
		$this->clientid = $clientid;
	}
	
	function setPaths( array $paths ){
		
	}
	
	
	
	private function setCacheDir(){
		return $this->tmp."/".$this->clientid."/".$fromFileCachePrefix;
	}
	
	private function setDebugDir(){
		return $this->debug;
	}
	
	private function setTemplatePaths(){
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