<?php

namespace Botnyx\Sfe\Backend\Core\WebAssets;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;



use Botnyx\Sfe\Backend\Core\Frontend as Frontend;
use Botnyx\Sfe\Backend\Core\Database as Database;






class BackendProxy {



	function __construct(ContainerInterface $container){

		$this->proxy = new \Botnyx\Sfe\Shared\WebAssets\AssetProxy($container);

		// \Slim\HttpCache\CacheProvider($type = 'private', $maxAge = 86400, $mustRevalidate = false)
		$this->cacher =new \Slim\HttpCache\CacheProvider('public', $maxAge = 86400, false);

		//$this->cacher = $container->get('cache');
		$this->paths = $container->get('sfe')->paths;
		$this->hosts = $container->get('sfe')->hosts;
		$this->debug = true;


		#$root = $this->paths['root'];//."/vendor/botnyx/sfe-backend-js/src/sfe/";
		#$temp = $this->paths['temp'];//."/sfe-js";

		$this->sfeJS = new \Botnyx\Sfe\Javascript\sfelib($this->paths->root,$this->paths->temp);

		$this->feConfig = new Database\FrontendConfig($container->get('pdo') );




	}

	function get(ServerRequestInterface $request, ResponseInterface $response, array $args = []){

		$r = explode("/",$args['path']);
		//$client_id = $r[0];
		$lastElement = end($r);
		$r['_']=str_replace($r[0]."/","",$args['path']);

		//echo $lastElement;
		//Thu, 08 Dec 2016 01:00:57 GMT
		//var_dump( strpos($lastElement,"sfe-") );
		#echo "<pre>";

		#print_r($r);
		#print_r($args['clientid']);
		#echo "<br>";
		#die();
		$clientconfig 	= $this->feConfig->getConfigByClientId($args['clientid']);
		//print_r($clientconfig['template']);


		if(strpos($lastElement,"sfe-")===0){

			// this is a SFE  lib.
			try{
				$javascript =  $this->sfeJS->get($r['_'],array("client_id"=>$client_id));
			} catch(\Twig\Error\LoaderError $e){
				// Thrown when an error occurs during template loading.
				throw new \Exception($e->getMessage(),404);

				print_r((string)$e->getCode());
				print_r((string)$e);
			}


			//die($args['path']);

			$response = $response->write( $javascript )->withHeader("content-type", "text/javascript");
			$LastModified = time() + 3600;

			//$response = $response->withHeader('Cache-Control',$headerval);
			$response = $this->cacher->withExpires($response, time() + 3600);
			return $response = $this->cacher->withLastModified($response, $LastModified );



			//$response = $response->withHeader('Last-Modified',$headerval);
			//$response = $response->withHeader('Expires',$headerval);

			//$response->withHeader('Pragma',$headerval);


			die("......./.../...");
		}elseif(strpos($lastElement,"bundle-")===0){
			// this is a bundle request.
			$r['_TODO']="bundle-request  BackendProxy.php";

		}else{
			// this is a CDN request
			//proxy $args['path']
			/*
				strip the clientid from path, as the cdn has no clientspecific stuff..
			*/
			//print_r($args['clientid']);

			$uri = $this->hosts->cdn."/templates/".$clientconfig['template']."/assets/".str_replace($r[0]."/","",$args['path']);;

			//die($uri);
			//$uri = "http://freelance.bss.servenow.nl/".$args['path'];
			try{
				return $this->proxy->get($response,$uri);
			}catch(\Exception $e){
				throw new \Exception('Cdn reports: 404 Not Found',404);
				//return $response->withStatus($e->getCode());
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
