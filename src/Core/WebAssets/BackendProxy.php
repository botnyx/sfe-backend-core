<?php

namespace Botnyx\Sfe\Backend\Core\WebAssets;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class BackendProxy {
	
	
	
	function __construct(ContainerInterface $container){
		
		$this->proxy = new \Botnyx\Sfe\Shared\WebAssets\AssetProxy($container);
		$this->cacher = $container->get('cache');
		$this->paths = $container->get('settings')['paths'];
		$this->debug = true;
	}
	
	function get(ServerRequestInterface $request, ResponseInterface $response, array $args = []){
		
		$r = explode("/",$args['path']);
		$client_id = $r[0];
		$r['_']=str_replace($r[0]."/","",$args['path']);
		
		$lastElement = end($r); 
		if(strpos($lastElement,"sfe-")==0){
			// this is a SFE  lib.
			
			
		}elseif(strpos($lastElement,"bundle-")==0){
			// this is a bundle request.
			
			
		}else{
			// this is a CDN request
			//proxy $args['path']
			/* 
				strip the clientid from path, as the cdn has no clientspecific stuff..
			*/
			$uri = $this->paths['cdn']."/assets/".str_replace($r[0]."/","",$args['path']);;
			try{
				return $this->proxy->get($response,$uri);	
			}catch(\Exception $e){
				$response = \Botnyx\Sfe\Shared\ExceptionResponse::get($response,$e->getCode(),'Cdn reports: 404 Not Found');
				return $response->withStatus($e->getCode());
			}
			
			
			
		}
		//$r['uri']=$uri;
		
		//print_r($this->settings);
		
		return  $response->withJson( $r );
		
		/*
		$responseWithCacheHeader = $this->cacher->withExpires($res, time() + 3600);
		$responseWithCacheHeader = $this->cacher->withLastModified($responseWithCacheHeader, $returnedData['Last-Modified'] );
		return $responseWithCacheHeader;
		*/
		
		
		
		//return $response->withJson( $r );
		
	}
	
}
