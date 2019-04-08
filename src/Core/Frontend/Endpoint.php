<?php


namespace Botnyx\Sfe\Backend\Core\Frontend;

use Slim\Http;
use Slim\Views;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

use Botnyx\Sfe\Shared;
use Twig\Error;



class Endpoint{
	
	function __construct(ContainerInterface $container){
		$this->container = $container;
		$this->sfe = $container->get('sfe');
		$this->cache  = $container->get('cache');
		
		$this->paths = $this->sfe->paths;
		
		$pdo  = $container->get('pdo');
		
		
	
		/*
		$this->sfe->type
		$this->sfe->paths
		$this->sfe->hosts
		$this->sfe->clientid
		$this->sfe->debug
			*/
		
		#$this->settings  = $container->get('sfe')['sfe'];
		#$this->paths  = $container->get('settings')['paths'];
		
		$this->feConfig = new \Botnyx\Sfe\Backend\Core\Database\FrontendConfig($pdo);

		
		#var_dump($this->feConfig);
		
		#die();
		
		$this->outputFormat = new \Botnyx\Sfe\Shared\ApiResponse\Formatter();
		
	//	print_r($container->get('settings'));
		//die();
		
		//var_dump((bool)$this->settings['debug']);
		
		//die();
		$this->debug = $this->sfe->debug;//(bool)$this->settings['debug'];
		
		/*
			$this->settings 
			
			Array
			(
				[clientId] => 709b6bb0-63a79f2-47da-a24b-0de26c7cd22c
				[clientSecret] => Somesecret
				[sfeCdn] => https://cdn.yourserver.ext
				[sfeBackend] => https://backend.yourserver.ext
				[sfeAuth] => https://auth.yourserver.ext
				[conn] => Array
					(
						[dsn] => mysql:host=localhost;dbname=SomeName
						[dbuser] => SomeUser
						[dbpassword] => SomePass
					)

			)
		*/
		
		
		
		
		
		
	}
	
	
	private function parsePath($path,$routeInfo){
		
		$pathParts = explode('/',$path);
		
		// move the internal pointer to the end of the array
		end($pathParts);
		
		// fetches the key of the element pointed to by the internal pointer
		$key = key($pathParts);
		$reqUrlParts =parse_url($routeInfo['request'][1]);
		
		// fill teh req $variables
		parse_str(str_replace('.html','',$reqUrlParts['query']), $variables);
		
		if( count($variables)>0 ){
			// this url has a dynamic variable.
			echo "DYNAMIC<br>";
		}else{
			echo "STATIC<br>";
		}
		
		// /////////////////////////////////////////////////////////
		
		$templateFile = implode('/',$pathParts).".html";
		if($templateFile=='.html'){ $templateFile='index.html'; }
		
		
		
		
		if( count($variables)>0 ){
			$requestedPath = "/".str_replace(".html","",$templateFile)."/"."{".key($variables)."}" ;
		}else{
			$requestedPath = "/".str_replace(".html","",$templateFile) ;
		}
		
		if($requestedPath=="/index"){ $requestedPath="/";}
		
		# echo "<br>real requestedPath:".$requestedPath."<br>";
		
		$clientid=$args['clientid'];
		$variables;
		
		return array("templateFile"=>$templateFile,"variables"=>$variables,"requestedPath"=>$requestedPath);
	}
	
	function get(ServerRequestInterface $request, ResponseInterface $response, array $args = []){
		
		//
		$clientID = $args['clientid'];
		
		// get the configuration for this client.
		
		$ClientConfig =$this->feConfig->getConfigByClientId($clientID);
		if($ClientConfig==false){
			// Clientid not found in database
			die('xx');
			return \Botnyx\Sfe\Shared\ExceptionResponse::get($response,1105);
		}
		
		
		// get the routes for this client.
		
		$ClientRoutes =$this->feConfig->getFrontendEndpoints($clientID);
		if($ClientRoutes==false){
			// Clientid returned no frontend endpoints.
			return \Botnyx\Sfe\Shared\ExceptionResponse::get($response,1106);
		}
		
		$tmp['routes'] =$this->feConfig->getFrontendEndpoints($clientID);
		$tmp['menus'] =$this->feConfig->getByMenuClientId($args['clientid']);
		
		echo "<pre>";
		//print_r($ClientConfig);
		//print_r($tmp);
		$route = $request->getAttribute('route');
    	//$courseId = $route->getArgument('id');
		
		print_r($route);
		
		// 
		$parsedPath = $this->parsePath($args['path'],$request->getAttributes('route')['routeInfo']);
		
		
		echo "<pre>";
		
		// we now know:
		$parsedPath->variables;
		$parsedPath->templateFile;
		$parsedPath->requestedPath;
		
		//print_r($parsedPath);
		
		
		print_r($parsedPath);
		//echo "<br>TemplateFile:".$parsedPath['templateFile']."<br>";
		
		//die();
		
		
		
		//echo $parsedPath['requestedPath']."\n";
		
		#print_r($tmp['routes']);
		
		//print_r($tmp['routes']);
		//var_dump($requestedPath);
		//var_dump($requestedPath);

		
die();		
		
		
		//print_r($tmp['routes']);
		
		/* define a 404 route 
		$xthisRoute=array(	"id"=>0,
						 	"uri"=>"/_404.html",
						 	"fnc"=>"\\Botnyx\\Sfe\\Frontend\\Endpoint:get",
						 	"tmpl"=>"botnyx/bootstrap3",
						 	"client_id"=>$clientID
						);
		*/
		$thisRoute = false;
		foreach($ClientRoutes as $route){
			if($route['uri']==$parsedPath['requestedPath']){
				$thisRoute = $route;
				break;
			}
		}
		
		
		
		
		if($thisRoute==false){
			throw new \Exception("Route doesnt exist.",404);
			die("x");
			return \Botnyx\Sfe\Shared\ExceptionResponse::get( $response, 2203, $parsedPath['requestedPath'],$this->debug);
		}
		
		
		
		$pathInfo = array_merge($parsedPath,$thisRoute);
		
		#var_dump($this->paths['root']."/vendor/botnyx/sfe-backend-core/templates");
		#var_dump(file_exists($this->paths['root']."/vendor/botnyx/sfe-backend-core/templates"));
		#die();
		
		
		$pathInfo['_template_sfecore'] = $this->paths->root."/vendor/botnyx/sfe-backend-core/templates";
		
		
		if(!file_exists( $this->paths->templates."/_Clients" )){
			return \Botnyx\Sfe\Shared\ExceptionResponse::get( $response, 2200, $this->paths->templates."/_Clients" ,$this->debug);
		}
		$pathInfo['_template_client'] = $this->paths->templates."/_Clients/".$pathInfo['client_id'];
		
		//var_dump($pathInfo['_template_client']);
		
		if(!file_exists($pathInfo['_template_client'])){
			return \Botnyx\Sfe\Shared\ExceptionResponse::get( $response, 2201, $pathInfo['_template_client'],$this->debug );
			//return $response->withJson($this->outputFormat->response("no templatepath not found for client",404))->withStatus(404);
		}
		
		
		
		#var_dump(file_exists($this->paths['root']."/vendor/botnyx/sfe-backend-core/templates"));
		$pathInfo['_template_origin'] = $this->paths->templates."/".$pathInfo['tmpl'];
		if(!file_exists($pathInfo['_template_origin'])){
			return \Botnyx\Sfe\Shared\ExceptionResponse::get($response,2202,$pathInfo['_template_origin'],$this->debug);
			//return $response->withJson($this->outputFormat->response("Origin template not found for client",500))->withStatus(500);
		}
		
		
		
		
		$pathInfo['_template_file']=$parsedPath['templateFile'];
		
		
		
		#echo "<pre>";
		#echo "thisRoute\n";
		
		#print_r($pathInfo);
		
		
		//  we have every info to render a static page.
		
		/*
			File lookup order.
			
			(_template_sfecore)
			
			initially, the base html is searched in the client's template folder ( _template_client ).
			
			If no file is found,  we will look into the template specific folder. ( _template_origin )
			
			
			maincontent 
			
		*/
		
		
		
		
		
		
		/*
			 get the page assets, and inject them in the base html via template vars..
		
		*/
		try{
			$SfePageAssets = new \Botnyx\Sfe\Backend\Core\Template\AssetsLoader($pathInfo['_template_sfecore']);
			
		}catch(\Exception $e){
			//Thrown when an error occurs during template loading.
			return \Botnyx\Sfe\Shared\ExceptionResponse::get( $response, 3206, $e->getMessage(),$this->debug );
		}
		
		$SfePageAssets->css;
		$SfePageAssets->js;
	
		
		try{
			$ClientPageAssets = new \Botnyx\Sfe\Backend\Core\Template\AssetsLoader($pathInfo['_template_client']);
			
		}catch(\Exception $e){
			//Thrown when an error occurs during template loading.
			return \Botnyx\Sfe\Shared\ExceptionResponse::get( $response, 3206, $e->getMessage(),$this->debug );
		}
		
		$ClientPageAssets->css;
		$ClientPageAssets->js;
		
		
		// dedupe and merge the assets.
		
		// inject the css/js
		
		$templateVars=array(
			"thisRoute"=>$thisRoute,
			"assets"=>array(
				"sfe"=>array(
					"css"=>$SfePageAssets->css,
					"js" =>$SfePageAssets->js ),
				"client"=>array(
					"css"=>$ClientPageAssets->css,
					"js" =>$ClientPageAssets->js ) 
			)
			
		);
		#print_r("<pre>");
		#print_r($templateVars);
		#die();
		
		
		
		/*
			Base loader,  loads the main document, head and body section (from core)
		*/
		$base_paths = array($pathInfo['_template_client'],$pathInfo['_template_sfecore'] );
		try{
			$baseHtmlLoader = new \Botnyx\Sfe\Backend\Core\Template\BaseLoader($base_paths,$clientID,$this->paths);
			
			$html = $baseHtmlLoader->get($templateVars);
			
		}catch(LoaderError $e){
			//Thrown when an error occurs during template loading.
			return \Botnyx\Sfe\Shared\ExceptionResponse::get( $response, 3200, $e->getMessage(),$this->debug );
		}catch(SyntaxError $e){
			//Thrown to tell the user that there is a problem with the template syntax.
			return \Botnyx\Sfe\Shared\ExceptionResponse::get( $response, 3201, $e->getMessage(),$this->debug );
		}catch(RuntimeError $e){
			//Thrown when an error occurs at runtime (when a filter does not exist for instance).
			return \Botnyx\Sfe\Shared\ExceptionResponse::get( $response, 3202, $e->getMessage(),$this->debug );
		}catch(SecurityError $e){
			//Thrown when an unallowed tag, filter, or method is called in a sandboxed template.
			return \Botnyx\Sfe\Shared\ExceptionResponse::get( $response, 3203, $e->getMessage(),$this->debug );
		}catch(Error $e){
			return \Botnyx\Sfe\Shared\ExceptionResponse::get( $response, 3204, $e->getMessage(),$this->debug );
		}catch(\Exception $e){
			return \Botnyx\Sfe\Shared\ExceptionResponse::get( $response, 3205, $e->getMessage(),$this->debug );
			print_r($e->getMessage());
		}
		
		
		#var_dump($thisRoute);
		#die();
		
		
		
		
		
		
		
		
		#return $response->write( $html );
		
		#die();
		//$pathInfo['_template_file']
		$parsedPath->variables;
		
		$base_paths= array();
		$base_paths = array($pathInfo['_template_client'],$pathInfo['_template_origin'],$pathInfo['_template_sfecore'] );
		try{
			$clientHtmlLoader = new \Botnyx\Sfe\Backend\Core\Template\ClientLoader($base_paths,$clientID,$this->paths);
			//$html = $clientHtmlLoader->get();
			
			$html = $clientHtmlLoader->fromString($html,$templateVars);
			
		}catch(\Twig\Error\LoaderError $e){
			//Thrown when an error occurs during template loading.
			return \Botnyx\Sfe\Shared\ExceptionResponse::get( $response, 3200, $e->getMessage(),$this->debug );
		}catch(\Twig\Error\SyntaxError $e){
			//Thrown to tell the user that there is a problem with the template syntax.
			return \Botnyx\Sfe\Shared\ExceptionResponse::get( $response, 3201, $e->getMessage(),$this->debug );
		}catch(\Twig\Error\RuntimeError $e){
			//Thrown when an error occurs at runtime (when a filter does not exist for instance).
			return \Botnyx\Sfe\Shared\ExceptionResponse::get( $response, 3202, $e->getMessage(),$this->debug );
		}catch(\Twig\Sandbox\SecurityError $e){
			//Thrown when an unallowed tag, filter, or method is called in a sandboxed template.
			return \Botnyx\Sfe\Shared\ExceptionResponse::get( $response, 3203, $e->getMessage(),$this->debug );
		}catch(\Twig\Error\Error $e){
			return \Botnyx\Sfe\Shared\ExceptionResponse::get( $response, 3204, $e->getMessage(),$this->debug );
		}catch(\Exception $e){
			return \Botnyx\Sfe\Shared\ExceptionResponse::get( $response, 3205, $e->getMessage(),$this->debug );
			//print_r($e->getMessage());
		}
		
		
		
		
		return $response->write( $html );
		
		die();
		
		//die($template->render());
		
		//die($this->templateFolder."/".$clientid);
		$_path=$this->rootFolder."/templates/";
		
		$templateVars=array();
		$defaultBody='body.html';
		$templateCache=false;

		/* create the templateLoader with options. */
		try{
			$tloader = new \Botnyx\SfeBackend\Config\templateLoader($_path,$templateVars,$defaultBody,$templateCache);
		}catch(\Exception $e){
			die("eek!");
		}

		//die("xxxxxxxxxxxxxxxxx");
		
		/* get the config for all html */
		try{
			$templateBase = $tloader->get($templateName);
		}catch(\Exception $e){
			//$templateBase = array("error"=>$e->getMessage());
			throw new \Exception($e->getMessage());
		}
		
		/*
		$templateBase['css'];
							'html'=>$this->loadTemplate(),
					'js' =>$this->loadJs(),
					'css'=>$this->loadCss(),
					'template'=>$this->template
						*/
		
		
		/*
		laborator/neon-bootstrap-admin-theme"
		botnyx/newspaper
		
		*/
		
		 $thisRoute['defpage'];
		
#		$extraconfig = $this->fe_cfg->getConfigByClientId($args['clientid']);
		$extcfg = array(
			'clientId'=>$args['clientid'],
			'htmlts'=>1234,
			'html'=>null,
			'lang'=>explode(',',$extraconfig['languages']),
			'js'=>array(),
			'css'=>array(),
			'template'=>$extraconfig['template'],
			'extracfg'=>$extraconfig
		);
		$extcfg['extracfg']['defaultpage']=$thisRoute['defpage'];
		//sfeBackend
		
		$html =  $template->render([
			'sfeCdn' 		=> "https://".$extraconfig['cdnhostname'],
			'sfeBackend'	=> "https://".$extraconfig['backendhostname'],
			'pageTitle'		=> "pagetitle",
			'headTags'		=> array( /* the meta tags like og: or twitter */
				array("itemName"=>"test",
					  "itemValue"=>"test",
					  "itemContent"=>"testcontent"
					 )
			),
			'cssTags'		=> $templateBase['css'], /* array("https://cdn.devpoc.nl/a/css/bootstrap.css"),*/
			'scriptTags'	=> array_merge(array("/a/js/sfe-bootstrap.js"),$templateBase['js']),
			'extcfg'		=> json_encode($extcfg)
			
		]);
		
		
		//die();
		
		return $response->write( $html );
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		die();

		
		$lastUpdated = time() - 3600;
		
		$res = $response->withJson( $this->outputFormat->response($data) );
        //$resWithExpires = $this->cache->withExpires($res, time() + 3600);
        //$res = $this->cache->withExpires($res, time() + 3600);
        $resWithLastMod = $this->cache->withLastModified($res, $lastUpdated);

        return $resWithLastMod;
		
	}
}