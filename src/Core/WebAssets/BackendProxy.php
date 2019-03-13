<?php

namespace Botnyx\Sfe\Backend\Core\WebAssets;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class BackendProxy {
	
	
	
	function __construct(ContainerInterface $container){
		
		$this->proxy = new \Botnyx\Sfe\Shared\WebAssets\AssetProxy($container);
	}
	
	function get(ServerRequestInterface $request, ResponseInterface $response, array $args = []){
		
		$r = explode("/",$args['path']);
		$lastElement = end($r); 
		if(strpos($lastElement,"sfe-")){
			// this is a SFE combined lib.
			
		}else{
			// this is a CDN request
			//proxy $args['path']
			
			
			
			
		}
		
		
		
		return $response->withJson( $r );
		
	}
	
}
