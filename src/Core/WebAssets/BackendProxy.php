<?php

namespace Botnyx\Sfe\Backend\Core\WebAssets;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class BackendProxy {
	
	
	
	function __construct(ContainerInterface $container){
		
		$this->proxy = new \Botnyx\Sfe\Shared\WebAssets\AssetProxy($container);
		
		// \Slim\HttpCache\CacheProvider($type = 'private', $maxAge = 86400, $mustRevalidate = false)
		$this->cacher =new \Slim\HttpCache\CacheProvider('public', $maxAge = 86400, false);
		
		//$this->cacher = $container->get('cache');
		$this->paths = $container->get('settings')['paths'];
		$this->debug = true;
		
				
		$root = $this->paths['root'];//."/vendor/botnyx/sfe-backend-js/src/sfe/";
		$temp = $this->paths['temp'];//."/sfe-js";
		
		$this->sfeJS = new \Botnyx\Sfe\Javascript\sfelib($root,$temp);
		
		
		
		
	}
	
	function get(ServerRequestInterface $request, ResponseInterface $response, array $args = []){
		
		$r = explode("/",$args['path']);
		$client_id = $r[0];
		$lastElement = end($r); 
		$r['_']=str_replace($r[0]."/","",$args['path']);
		
		//echo $lastElement;
		//Thu, 08 Dec 2016 01:00:57 GMT
		
		if(strpos($lastElement,"sfe-")==0){
			// this is a SFE  lib.
			try{
				$javascript =  $this->sfeJS->get($r['_'],array("client_id"=>$client_id));
			}catch(\Exception $e){
				//echo $e->getMessage();
				return \Botnyx\Sfe\Shared\ExceptionResponse::get($response,3207,$e->getMessage(),true);
				//return $response->withJson( array("error"=>$e->getMessage()) );//->withStatus(404);
			}
			
			
			die($args['path']);
			
			$response = $response->write( $javascript )->withHeader("content-type", "text/javascript");
			$LastModified = time() + 3600;
			
			//$response = $response->withHeader('Cache-Control',$headerval);
			$response = $this->cacher->withExpires($response, time() + 3600);
			return $response = $this->cacher->withLastModified($response, $LastModified );
			
			
			
			//$response = $response->withHeader('Last-Modified',$headerval);
			//$response = $response->withHeader('Expires',$headerval);
			
			//$response->withHeader('Pragma',$headerval);
			
			
			die("......./.../...");
		}elseif(strpos($lastElement,"bundle-")==0){
			// this is a bundle request.
			$r['_TODO']="bundle-request  BackendProxy.php";
			
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
