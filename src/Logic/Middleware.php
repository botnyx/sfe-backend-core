<?php

namespace Botnyx\Sfe\Backend\Logic;

class Middleware {
	
	
	public function get($app,$container){
		
		
		
		if(!array_key_exists('pdo',$container)){
			/* Database initialization*/
			$conn = $container->get('sfe')->role->conn;
			if(isset($pdo)==false){

				$dboptions = array(
					\PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
					\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
					\PDO::ATTR_EMULATE_PREPARES   => false,
				);

				/*  make db connection.  */
				try{
					//$pdo = new PDO($ini['database']['pdodsn']  );

					$pdo = new \PDO($conn->dsn, $conn->dbuser,$conn->dbpassword,$dboptions );
					// set the default schema for the oauthserver.
					//$result = $pdo->exec('SET search_path TO oauth2'); # POSTGRESQL Schema support
				}catch(Exception $e){
					die($e->getMessage());

				}



			}
			$container['pdo'] = $pdo;
		}
		
		
		
		// Register globally to app
		$container['session'] = function ($c) {
		  return new \SlimSession\Helper;
		};

		
		$backendMiddleware = function ($request, $response, $next)  {
			
			
			//echo "<pre>";
			#print_r($this->get('sfe')->hosts);
			#die();
			
			//$this->container->get('settings')['sfe']->role;
			$clientIssuer="https://".$this->get('sfe')->hosts->auth;

			$sfeBackendMiddleWare = new \Botnyx\Sfe\Backend\Core\MiddleWare($request, $response,$this->pdo,$clientIssuer );
			// Add requestAttributes.
			$request = $sfeBackendMiddleWare->addRequestAttributes($request);
			
			$response = $next($request, $response);
			// Add responseHeaders.
			$response = $sfeBackendMiddleWare->addResponseHeaders($response);
			return $response;//->withHeader('Access-Control-Allow-Origin','*');
		};
		/*
			
		*/

		/**/
		$app->add($backendMiddleware);

		return $app;
	}

		
	
}
