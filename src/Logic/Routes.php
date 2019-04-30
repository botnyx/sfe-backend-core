<?php

namespace Botnyx\Sfe\Backend\Logic;

class Routes {
		
	public function get($app,$container){
		/*
			Frontend configuration.
		*/
		$app->get('/api/cfg/{clientid}','\\Botnyx\\Sfe\\Backend\\Core\\Frontend\\Configuration:get')->setName("Configuration:get");
		
		
		
		
		
		/*
			Static url proxy : /_/a/js/sfe-bootstrap.js
		*/
		$app->get('/assets/{clientid}/[{path:.*}]','\\Botnyx\\Sfe\\Backend\\Core\\WebAssets\\BackendProxy:get')->setName("BackendProxy:get");
		//$app->get('/_/assets/[{path:.*}]','\\Botnyx\\Sfe\\Backend\\Core\\WebAssets\\BackendProxy:aget');
		
		
		/*
			Static assets url proxy
		*/
		$app->get('/api/sfe/{clientid}/uri/{language}/[{path:.*}]','\\Botnyx\\Sfe\\Backend\\Core\\Frontend\\Endpoint:get')->setName("Endpoint:get");
		//$app->get('/api/sfe/{clientid}/uri/[{path:.*}]','\\Botnyx\\Sfe\\Backend\\Core\\Frontend\\Endpoint:get');
		
		
		
		
		
		
		/*
			Static components proxy
		*/
		//         /api/sfe/{clientid}/e/{pid}/component/{component}/{language}
		$app->get('/api/sfe/{clientid}/e/{pid}/component/{component}/{language}','\\Botnyx\\Sfe\\Backend\\Core\\Frontend\\Component:get')->setName("Component:get");
		//$app->get('/api/sfe/{clientid}/uri/[{path:.*}]','\\Botnyx\\Sfe\\Backend\\Core\\Frontend\\Endpoint:get');
		
		
		
		
		
		
		
		
		
		
		
		
		/*
			ui load endpoint..
		*/
		$app->get('/api/sfe/{clientId}/ui/load', '\\Botnyx\\Sfe\\Backend\\Core\\Frontend\\UiElementLoader:get' )->setName("UiElementLoader:get");
		
		/*
			ui click endpoint..
		*/
		$app->get('/api/sfe/{clientId}/ui/click', '\\Botnyx\\Sfe\\Backend\\Core\\Frontend\\UiElementLoader:getMain' )->setName("UiElementLoader:getMain");


		/*
			ServiceWorker JS endpoint..
		* /
		$app->get('/api/sfe/{clientid}/ui/sw','\\Botnyx\\SfeBackend\\Serviceworker\\generator:get');





		//$app->get('/api/cfg/{clientid}', function ( $request,  $response, array $args){ 
		//});



		/*

			Site config endpoint..

		* /
		$app->get('/api/sfe/ui/extcfg','\\Botnyx\\SfeBackend\\Api\\Config:get');



		/*
		$app->get('/api/sfe/ui/{o}/extcfg','\\Botnyx\\SfeBackend\\Api\\Config:get');
		* /


		$app->get('/api/sfe/{clientid}/ui/status', function ( $request,  $response, array $args){
			//https://ec.europa.eu/esco/api/suggest2?type=occupation&language=nl&text=vakken&offset=0&limit=20&alt=true
			$data = array(
				'clientid'=>$args['clientid'],
				'userprefs'=>array("language"=>"nl_NL"),
				'status'=>'ok',
			);
			//return $response->write('')->withStatus(401);
			return $response->withJson($data);//->withStatus(500);
		});






		/*

			CallbackRoute for Ext. auth provider

		* /
		//$app->get('/ext/{providerUrlKey}/cb',  "\\Botnyx\\SfeBackend\\Api\\Callback:get");
		$app->get('/ext/{authReqClientId}/{providerUrlKey}/cb',  "\\Botnyx\\SfeBackend\\Api\\Callback:get");


		*/


			$app->get('/robots.txt',  function ( $request,  $response, array $args){
				$res = "User-agent: *".PHP_EOL."Disallow: /";
				//print_r($this->get('sfe'));
				//die();
				return $response->write($res);

			});

		
		return $app;
	}
	
	
}
