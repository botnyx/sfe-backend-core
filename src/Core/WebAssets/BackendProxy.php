<?php

namespace Botnyx\Sfe\Backend\Core\WebAssets;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class BackendProxy {
	
	
	
	function __construct(ContainerInterface $container){
		
		$this->proxy = new \Botnyx\Sfe\Shared\WebAssets\AssetProxy($container);
		$this->cacher = $container->get('cache');
		
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
		
		
		return  $response->withJson( $r );
		
		/*
		$responseWithCacheHeader = $this->cacher->withExpires($res, time() + 3600);
		$responseWithCacheHeader = $this->cacher->withLastModified($responseWithCacheHeader, $returnedData['Last-Modified'] );
		return $responseWithCacheHeader;
		*/
		
		
		
		//return $response->withJson( $r );
		
	}
	
}
