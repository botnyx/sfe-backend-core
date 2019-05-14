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

use Botnyx\Sfe\Backend\Core\Frontend\Acl as SfeAcl;

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
		
		$this->acl = new SfeAcl\Acl();
	
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
		/* 
			Validation Request for token
			
		*/
		try{
			$headers = [ 'Authorization' => str_replace("Bearer","JWT",$request->getAttribute('token') ) ];
			
			$client = new Client([
				// Base URI is used with relative requests
				// You can set any number of default request options.
				'headers' => $headers,
				'timeout'  => 3.0,
				'http_errors'=>false
			]);
			$guzzleresponse = $client->request('GET', 'https://account.trustmaster.org/api/jwt/validate');
			
		}catch(\Exception $e){
				
		}
		
		
		
		$cfg = array();
		/* 
			
			Verify the token (if one was provided.)
			set the userid and roles.
			
		*/
		//var_dump($guzzleresponse->getStatusCode());
		
		// return $this->acl->filterAclRoles($providedRoles);
		
		// return $this->acl->hasAccess($roles, $resource, $rights);
		
		$roles = array();
		if( $guzzleresponse->getStatusCode()==200 ){
			$token = json_decode($guzzleresponse->getBody()->getContents());
			$cfg["user_id"]		=$token->jwt->sub;
			
			if(count($token->jwt->roles)==0){
				$cfg["roles"]		=array('guest');
			}else{
				$cfg["roles"]		=$token->jwt->roles;
			}
			
			
			
		}elseif( $guzzleresponse->getStatusCode()==401  ){
			$cfg["roles"]		=array('guest');
			$cfg["user_id"]		="false";
		}else{
			throw new \Exception("JWT Validate Exception",$guzzleresponse->getStatusCode())	;
			
		}
		
		
		$cfg["roles"] 		= $cfg["roles"] ;
		$cfg["sfe_roles"] 	= $this->acl->filterAclRoles( $cfg["roles"] );
		
		$cfg["client_id"] 		=$args['clientid'];
		$cfg["endpoint_id"]		=(string)$args['pid'];
		$cfg["language"]		=$args['language'];
		
		
		/*
			Create new config for the component.
		*/
		$CompConfig = new ComponentConfig( $cfg );
		
		
		
		/* 
			Construct the requested class.
		
		*/		
		$component = str_replace('-','\\',strtolower($args['component']));
		$cmpel = explode( '-' , strtolower($args['component']) );
		$cmpels=array();
		foreach($cmpel as $el){$cmpels[] = ucfirst($el);}
		$componentClass = "\\Botnyx\\Sfe\\Backend\\Components\\".implode('\\',$cmpels);
		
		if( !class_exists($componentClass) ){
			throw new \Exception("COMPONENT '.$componentClass.' DOES NOT EXIST");
		}
			
		
		
		/*
			Finally, call the component.
		*/
		$component = new $componentClass( $CompConfig );
		try {
			$result = $component->get();	
		}catch(\Exception $e){
			
		}
		
		return $response->withJson($result)->withHeader("Access-Control-Allow-Origin","*")->withHeader('Access-Control-Allow-Methods','GET,OPTIONS')->withHeader('Access-Control-Allow-Headers','Origin, Content-Type, X-Auth-Token, Authorization')->withAddedHeader('Access-Control-Allow-Origin', '*');
	}
	
}