<?php

//namespace Botnyx\Sfe\Backend\HtmlDocument;
namespace Botnyx\Sfe\Backend\Components\Simple;
	
class Menu {
	
	
	
	
	function __construct( $clientID,$endpointID,$language ){
		$this->language  = $language;
		$this->clientid  = $clientID;
		$this->endpointid=$endpointID;
		$this->name = str_replace('Botnyx\\Sfe\\Backend\\Components\\','',get_class($this) );
	}
	
	
	
	function get(){
		$params = [
		   'query' => [
			  'filter' => 'clientid,eq,'.$this->clientid			  
		   ]
		];
		
		$client = new \GuzzleHttp\Client(['base_uri' => 'https://data.servenow.nl']);
		$res = $client->request('GET', '/records/sf_menu',$params);
		//echo $res->getStatusCode();
		// https://data.servenow.nl/records/sf_menu?filter=clientid,eq,65ef4f99-f676-468b-89c4-5845f62e742e
		//echo $res->getBody()->getContents();
		
		return json_decode($res->getBody()->getContents());
	}
	
	
	
}