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
			
			$uri = $request->getUri();
			$urlparts = explode("/",substr($uri->getPath(), 1));
			
			#var_dump($uri->getQuery() )  ;
			#var_dump( $urlparts )  ;
			#echo "<hr>";
			#var_dump( $urlparts[0] )  ;
			#var_dump( $urlparts[1] )  ;
			#var_dump( $urlparts[2] )  ;
			//$x = explode("/",$uri->getPath()) ;
			#print_r($urlparts);
			#die();
			if( ($urlparts[0]=='api') && ( ($urlparts[1]=='cfg') ^ ($urlparts[1]=='sfe') ) ){
				$clientid = $urlparts[2];
			}elseif( ($urlparts[0]=='assets') ){
				$clientid = $urlparts[1];
			}elseif(($urlparts[0]=='robots.txt') ^ ($urlparts[0]=='.well-known')){
				
			}else{
				throw new \Exception( " UNKNOWN ROUTE - MIDDLEWARE.php" );
			}
			//$x[0];
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			#echo "<hr>";
			#die();
			
			$feConfig = new \Botnyx\Sfe\Backend\Core\Database\FrontendConfig($this->get('pdo'));
			
			$ClientConfig =$feConfig->getConfigByClientId($clientid);
			$request = $request->withAttribute('clientconfig', $ClientConfig );
			
			
			$ClientRoutes =$feConfig->getFrontendEndpoints($clientid);
			$request = $request->withAttribute('clientroutes', $ClientRoutes );
			
			//$this->container->get('settings')['sfe']->role;
			$clientIssuer="https://".$this->get('sfe')->hosts->auth;
			
			
			
			$stack = \GuzzleHttp\HandlerStack::create();
			$stack->push(
				  new \Kevinrob\GuzzleCache\CacheMiddleware(
					new \Kevinrob\GuzzleCache\Strategy\PrivateCacheStrategy(
					  new \Kevinrob\GuzzleCache\Storage\DoctrineCacheStorage(
						new \Doctrine\Common\Cache\FilesystemCache($this->get('sfe')->paths->temp.'/pubkey')
					  ),36000
					)
				  ),
				  'cache'
				);
			$cachedClient = new \GuzzleHttp\Client([
				'handler' => $stack
			]);
			
			try{
				$res = $cachedClient->request(
					'GET', 
					"https://".$this->get('sfe')->configuration->hosts->auth.'/api/jwt/public-key?applicationId='.$clientid
				);
				
			}catch(Exception $e){
				die($e->getMessage());
			}
			
			//die($c->get('sfe')->configuration->hosts->auth.'/api/jwt/public-key?applicationId='.$c->get('sfe')->configuration->clientid);
			
			
			
			$request = $request->withAttribute('pubkey', json_decode($res->getBody()) );
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			if ( $request->hasHeader('HTTP_AUTHORIZATION') && $request->getHeader('Authorization')[0]!="" ) {
			// Do something
				error_log("Authorization");
				error_log($request->getHeader('Authorization')[0] );
			//print_r( $request->getHeaders() );
			//var_dump( $request->getHeader('Authorization')[0] 
				$request = $request->withAttribute('token', $request->getHeader('HTTP_AUTHORIZATION')[0] );
			//die();
			}
			

			//$sfeBackendMiddleWare = new \Botnyx\Sfe\Backend\Core\MiddleWare($request, $response,$this->pdo,$clientIssuer );
			// Add requestAttributes.
			//$request = $sfeBackendMiddleWare->addRequestAttributes($request);
			
			$response = $next($request, $response);
			// Add responseHeaders.
			//$response = $sfeBackendMiddleWare->addResponseHeaders($response);
			return $response->withHeader('Access-Control-Allow-Origin','*')->withHeader('Access-Control-Allow-Headers','Origin, Content-Type, X-Auth-Token, Authorization');
		};
		/*
			
		*/

		/**/
		$app->add($backendMiddleware);

		return $app;
	}

		
	
}
