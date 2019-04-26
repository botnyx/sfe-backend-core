<?php


namespace Botnyx\Sfe\Backend\Core\Frontend;

use Slim\Http;
use Slim\Views;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

use Botnyx\Sfe\Shared;
use Twig\Error;

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
		
		$clientID = $args['clientid'];
		$endpointID = $args['pid'];
		$component = $args['component'];
		$language = $args['language'];
		
		
// sfe.url.get('/api/components/records/posts?join=categories&join=tags&join=comments&filter=id,eq,1'
		
		
		
		return $response->write("EEEeeeek!");
	}
	
}