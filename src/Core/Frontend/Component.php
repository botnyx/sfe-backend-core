<?php


namespace Botnyx\Sfe\Backend\Core\Frontend;

use Slim\Http;
use Slim\Views;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

use Botnyx\Sfe\Shared;
use Twig\Error;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

use Botnyx\Sfe\Backend\HtmlDocument as HtmlDocument;
//'https://data.servenow.nl/records/sf_menu?filter=clientid,eq,'+cid
class Component{
	
	function __construct(ContainerInterface $container){
		$this->container = $container;
		$this->sfe = $container->get('sfe');
		$this->cache  = $container->get('cache');
		
		$this->paths = $this->sfe->paths;
		
		$pdo  = $container->get('pdo');
		
		$this->hosts = $this->sfe->hosts;
		
		
	
		/*
		$this->sfe->type
		$this->sfe->paths
		$this->sfe->hosts
		$this->sfe->clientid
		$this->sfe->debug
			*/
		
		#$this->settings  = $container->get('sfe')['sfe'];
		#$this->paths  = $container->get('settings')['paths'];
		
//		$this->feConfig = new \Botnyx\Sfe\Backend\Core\Database\FrontendConfig($pdo);
///		$ClientConfig =$this->feConfig->getConfigByClientId($clientID);
		
		#var_dump($this->feConfig);
		
		#die();
		
		//$this->outputFormat = new \Botnyx\Sfe\Shared\ApiResponse\Formatter();
		
	//	print_r($container->get('settings'));
		//die();
		
		//var_dump((bool)$this->settings['debug']);
		
		//die();
		$this->debug = $this->sfe->debug;//(bool)$this->settings['debug'];
		
		
		
		
		
		
	}
	
	
	
	//         /api/sfe/{clientid}/e/{pid}/component/{component}/{language}
	
	function get(ServerRequestInterface $request, ResponseInterface $response, array $args = []){
		
		
		//  https://account.trustmaster.org/api/jwt/validate
		//print_r($request->getAttribute('token'));
		
		try{
			$headers = ['Authorization' => 'JWT '.str_replace("Bearer ","JWT ",$request->getAttribute('token'))];
			
			$client = new Client([
				// Base URI is used with relative requests
				'base_uri' => 'https://account.trustmaster.org',
				// You can set any number of default request options.
				'timeout'  => 2.0,
				'http_errors'=>false
			]);
			$guzzleresponse = $client->request('GET', '/api/jwt/validate',$headers);


		}catch(\Exception $e){
			
		}
		
		
		
		
		$roles = array();
		if( $guzzleresponse->getStatusCode()==200 ){
			$roles = $token->jwt->roles;
			$userid= $token->jwt->sub;
			
		}elseif( $guzzleresponse->getStatusCode()==401  ){
			$roles = array();
			
		}else{
			throw new \Exception("JWT Validate Exception",$guzzleresponse->getStatusCode())	;
			
		}
		
		$token = json_decode($guzzleresponse);
		$token->jwt->aud;
		$token->jwt->sub;
		$token->jwt->roles;
		
		
		
		
		
		
		
		$clientID 	= $args['clientid'];
		$endpointID = $args['pid'];
		$language 	= $args['language'];
		
		$component = str_replace('-','\\',strtolower($args['component']));
		
		$cmpel = explode( '-' , strtolower($args['component']) );
		$cmpels=array();
		foreach($cmpel as $el){
			$cmpels[] = ucfirst($el);
		}
		
		
		$componentClass = "\\Botnyx\\Sfe\\Backend\\Components\\".implode('\\',$cmpels);
		
		if( !class_exists($componentClass) ){
			throw new \Exception("COMPONENT '.$componentClass.' DOES NOT EXIST");
		}
		
		
		
		$component = new $componentClass( $clientID,$endpointID,$language );
		
		try {
			$result = $component->get();	
		}catch(\Exception $e){
			
		}
		
		
		
		/*
			Call the component, with args.
			
			clientid
			endpointID
			language 
			
			component
			
		*/
		
		$component;
		
		
		
		//$result = $request->getAttribute('token');// "Bearer TOKENSTRINGDINGES"
		
		
		
// sfe.url.get('/api/components/records/posts?join=categories&join=tags&join=comments&filter=id,eq,1'
		
		
		//->withHeader('Access-Control-Allow-Headers','Origin, Content-Type, X-Auth-Token,Authorization');
		
		return $response->withJson($result)->withHeader("Access-Control-Allow-Origin","*")->withHeader('Access-Control-Allow-Methods','GET,OPTIONS')->withHeader('Access-Control-Allow-Headers','Origin, Content-Type, X-Auth-Token, Authorization')->withAddedHeader('Access-Control-Allow-Origin', '*');
	}
	
}