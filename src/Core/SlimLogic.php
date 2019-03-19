<?php

namespace Botnyx\Sfe\Backend\Core;


use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;


use Slim\Http;
use Slim\Views;


class SlimLogic {
	
	
	public function getContainer($container){
		
		


		
		$container['cache'] = function ($c) {
			return new \Slim\HttpCache\CacheProvider();
		};

		
		$container['view'] = function ($c){

			$view = new \Slim\Views\Twig(_SETTINGS['paths']['templates'], [
				'cache' => false /*_SETTINGS['twig']['cache']*/,
				'debug' => _SETTINGS['twig']['debug']
			]);

			// Instantiate and add Slim specific extension
			$basePath = rtrim(str_ireplace('index.php', '', $c->get('request')->getUri()->getBasePath()), '/');

			// Add twigExtension
			$view->addExtension(new \Slim\Views\TwigExtension($c->get('router'), $basePath));

			// Add the debug extension
			if(_SETTINGS['twig']['debug']==true){
				$view->addExtension(new \Twig_Extension_Debug() );
			}

			// add Translation extensions.
			$view->addExtension(new \Twig_Extensions_Extension_I18n());

			//$view->addExtension(new \Twig_Extensions_Extension_Intl());
			//$twig->addExtension(new Project_Twig_Extension());
			//
			//$twig->addFunction('functionName', new Twig_Function_Function('someFunction'));

			if(array_key_exists('sfeBackend',_SETTINGS)){
				//$view->addFunction('functionName', new Twig_Function_Function('someFunction'));

				//$view->addExtension ( new \Botnyx\sfeBackend\twigExtension\Userinfo() );
			}
			return $view;
		};

		//echo "<pre>";
		//print_r($container);
		
		return $container;
	}
	
	public function getMiddleware($app,$container){
		
		
		if(!array_key_exists('pdo',$container)){
			/* Database initialization*/
			$conn =$container->get('settings')['sfe']->conn;
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
			
			
			print_r($this->get('settings'));
			die();
			
			//$this->container->get('settings')['sfe']->role;
			$clientIssuer=_SETTINGS['sfeBackend']['sfeAuthSrv'];//"https://auth.devpoc.nl";

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
	
	public function getRoutes($app,$container){
		/*
			Frontend configuration.
		*/
		$app->get('/api/cfg/{clientid}','\\Botnyx\\Sfe\\Backend\\Core\\Frontend\\Configuration:get');

		/*
			Static url proxy : /_/a/js/sfe-bootstrap.js
		*/
		$app->get('/_/assets/[{path:.*}]','\\Botnyx\\Sfe\\Backend\\Core\\WebAssets\\BackendProxy:get');

		/*
			Static assets url proxy
		*/
		$app->get('/api/sfe/{clientid}/uri/[{path:.*}]','\\Botnyx\\Sfe\\Backend\\Core\\Frontend\\Endpoint:get');








		/*

			ui load endpoint..

		*/
		$app->get('/api/sfe/{clientId}/ui/load', '\\Botnyx\\Sfe\\Backend\\Core\\Frontend\\UiElementLoader:get' );


		/*

			ui click endpoint..

		*/
		$app->get('/api/sfe/{clientId}/ui/click', '\\Botnyx\\Sfe\\Backend\\Core\\Frontend\\UiElementLoader:getMain' );









		/*

			ServiceWorker JS endpoint..

		* /

		$app->get('/api/sfe/{clientid}/ui/sw','\\Botnyx\\SfeBackend\\Serviceworker\\generator:get');





		//$app->get('/api/cfg/{clientid}', function ( $request,  $response, array $args){ 
		//});



		/*

			Site config endpoint..

		* /
		$app->get('/api/sfe/ui/extcfg','\\Botnyx\\SfeBackend\\Api\\Config:get');



		/*
		$app->get('/api/sfe/ui/{o}/extcfg','\\Botnyx\\SfeBackend\\Api\\Config:get');
		* /


		$app->get('/api/sfe/{clientid}/ui/status', function ( $request,  $response, array $args){
			//https://ec.europa.eu/esco/api/suggest2?type=occupation&language=nl&text=vakken&offset=0&limit=20&alt=true
			$data = array(
				'clientid'=>$args['clientid'],
				'userprefs'=>array("language"=>"nl_NL"),
				'status'=>'ok',
			);
			//return $response->write('')->withStatus(401);
			return $response->withJson($data);//->withStatus(500);
		});






		/*

			CallbackRoute for Ext. auth provider

		* /
		//$app->get('/ext/{providerUrlKey}/cb',  "\\Botnyx\\SfeBackend\\Api\\Callback:get");
		$app->get('/ext/{authReqClientId}/{providerUrlKey}/cb',  "\\Botnyx\\SfeBackend\\Api\\Callback:get");


		*/


			$app->get('/robots.txt',  function ( $request,  $response, array $args){
				$res = "User-agent: *".PHP_EOL."Disallow: /";
				return $response->write($res);

			});

		
		return $app;
	}

}