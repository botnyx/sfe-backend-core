<?php


namespace Botnyx\Sfe\Backend\Core\Frontend;


class ComponentBase {
	
	var $dataserver = 'https://data.servenow.nl';
	
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
	
	
	function call($url,$params){
		
		
		$client = new \GuzzleHttp\Client(['base_uri' => $this->dataserver ]);
		$res = $client->request('GET', $url, $params );
		
		return json_decode($res->getBody()->getContents(),true);
	}
	
}