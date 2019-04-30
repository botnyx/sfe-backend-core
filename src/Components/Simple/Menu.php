<?php

//namespace Botnyx\Sfe\Backend\HtmlDocument;
namespace Botnyx\Sfe\Backend\Components\Simple;

use Botnyx\Sfe\Backend\Core\Frontend as FrontEnd;

class Menu extends Frontend\ComponentBase {
	
	
	
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
	
	
	
};