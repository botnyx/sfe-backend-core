<?php


namespace Botnyx\Sfe\Backend\Core\Frontend;


class ComponentBase {
	
	
	function __construct( ComponentConfig $config ){
		$this->client_id  = $config->client_id;
		$this->endpoint_id  = $config->endpoint_id;
		$this->roles = $config->roles;
		$this->language = $config->language;
		
		if($config->user_id!="false" ){
			$this->user_id = $config->user_id;
		}else{
			$this->user_id = false;
		}
		
		$this->name = str_replace('Botnyx\\Sfe\\Backend\\Components\\','',get_class($this) );
	}
	
	function get(){
		throw new \Exception("Missing get() function in component.");
	}

}