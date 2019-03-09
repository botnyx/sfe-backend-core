<?php


namespace Botnyx\Sfe\Backend\Core\Frontend;

use Slim\Http;
use Slim\Views;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

use Botnyx\Sfe\Shared;


class Endpoint{
	
	function __construct(ContainerInterface $container){
		$this->container = $container;
		$pdo  = $container->get('pdo');
		$this->cache  = $container->get('cache');
		
		$this->settings  = $container->get('settings')['sfe'];
		$this->paths  = $container->get('settings')['paths'];
		
		$this->feConfig = new \Botnyx\Sfe\Backend\Core\Database\FrontendConfig($pdo);

		$this->outputFormat = new \Botnyx\Sfe\Shared\ApiResponse\Formatter();
		
	//	print_r($container->get('settings'));
		//die();
		
		//var_dump((bool)$this->settings['debug']);
		
		//die();
		$this->debug = (bool)$this->settings['debug'];
		
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
			#echo "DYNAMIC<br>";
		}else{
			#echo "STATIC<br>";
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
		
		//
		$ClientConfig =$this->feConfig->getConfigByClientId($clientID);
		if($ClientConfig==false){
			// Clientid not found in database
			return \Botnyx\Sfe\Shared\ExceptionResponse::get($response,1105);
		}
		//var_dump($ClientConfig);
		//die();
		
		
		$ClientRoutes =$this->feConfig->getFrontendEndpoints($clientID);
		if($ClientRoutes==false){
			// Clientid returned no frontend endpoints.
			return \Botnyx\Sfe\Shared\ExceptionResponse::get($response,1106);
		}
		
		//$tmp['routes'] =$this->feConfig->getFrontendEndpoints($clientID);
		//$tmp['menus'] =$this->fe_cfg->getByMenuClientId($args['clientid']);
		
		
		//var_dump($tmp);
		//die();
		
		// 
		$parsedPath = $this->parsePath($args['path'],$request->getAttributes('route')['routeInfo']);
		
		
		//echo "<pre>";
		
		// we now know:
		$parsedPath->variables;
		$parsedPath->templateFile;
		$parsedPath->requestedPath;
		
		//print_r($parsedPath);
		
		
		//print_r($parsedPath);
		//echo "<br>TemplateFile:".$parsedPath->templateFile."<br>";
		
		
		
		
		
		//echo $parsedPath['requestedPath']."\n";
		
		#print_r($tmp['routes']);
		
		//print_r($tmp['routes']);
		//var_dump($requestedPath);
		//var_dump($requestedPath);

		//print_r($tmp['routes']);
		
		foreach($ClientRoutes as $route){
			if($route['uri']==$parsedPath['requestedPath']){
				$thisRoute = $route;
				break;
			}
		}
		
		
		$pathInfo = array_merge($parsedPath,$thisRoute);
		
		#var_dump($this->paths['root']."/vendor/botnyx/sfe-backend-core/templates");
		#var_dump(file_exists($this->paths['root']."/vendor/botnyx/sfe-backend-core/templates"));
		#die();
		
		
		$pathInfo['_template_sfecore'] = $this->paths['root']."/vendor/botnyx/sfe-backend-core/templates";
		
		
		if(!file_exists( $this->paths['templates']."/_Clients" )){
			return \Botnyx\Sfe\Shared\ExceptionResponse::get( $response, 2200, $this->paths['templates']."/_Clients" ,$this->debug);
		}
		$pathInfo['_template_client'] = $this->paths['templates']."/_Clients/".$pathInfo['client_id'];
		
		//var_dump($pathInfo['_template_client']);
		
		if(!file_exists($pathInfo['_template_client'])){
			return \Botnyx\Sfe\Shared\ExceptionResponse::get( $response, 2201, $pathInfo['_template_client'],$this->debug );
			//return $response->withJson($this->outputFormat->response("no templatepath not found for client",404))->withStatus(404);
		}
		
		
		
		#var_dump(file_exists($this->paths['root']."/vendor/botnyx/sfe-backend-core/templates"));
		$pathInfo['_template_origin'] = $this->paths['templates']."/".$pathInfo['tmpl'];
		if(!file_exists($pathInfo['_template_origin'])){
			return \Botnyx\Sfe\Shared\ExceptionResponse::get($response,2202,$pathInfo['_template_origin'],$this->debug);
			//return $response->withJson($this->outputFormat->response("Origin template not found for client",500))->withStatus(500);
		}
		
		
		
		
		$pathInfo['_template_file']=$parsedPath['templateFile'];
		
		die();
		
		echo "<pre>";
		echo "thisRoute\n";
		
		print_r($pathInfo);
		
		
		//  we have every info to render a static page.
		
		/*
			File lookup order.
			
			(_template_sfecore)
			
			initially, the base html is searched in the client's template folder ( _template_client ).
			
			If no file is found,  we will look into the template specific folder. ( _template_origin )
			
			
			maincontent 
			
		*/
		
		
		
		// BASE HTML LOADER, CUSTOM WITH FALLBACK.
		$loader = new \Twig\Loader\FilesystemLoader( [$pathInfo['_template_client'],$pathInfo['_template_sfecore'] ] );
		$twig = new \Twig\Environment($loader, [
			'cache' => $this->tempDir."/".$clientid,
			'debug' => $this->debug
		]);
		
		// Add the debug extension
		if(_SETTINGS['twig']['debug']==true){
			$twig->addExtension(new \Twig_Extension_Debug() );
		}
		/*
			Load the main html frame.
		*/
		$template = $twig->load("sfe_document.phtml");
		
		
		
		return $response->write( $template->render() );
		
		
		
		
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
		
		$extraconfig = $this->fe_cfg->getConfigByClientId($args['clientid']);
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