<?php


namespace Botnyx\Sfe\Backend\Core\Frontend;

use Slim\Http;
use Slim\Views;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

use Botnyx\Sfe\Shared;
use Twig\Error;

use Firebase\JWT\JWT;

use Botnyx\Sfe\Backend\HtmlDocument as HtmlDocument;

use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;

use Botnyx\Sfe\Backend\Core\Frontend\Acl as SfeAcl;

class Endpoint{
	
	function __construct(ContainerInterface $container){
		
		$this->container= $container;
		$this->sfe		= $container->get('sfe');
		$this->cache	= $container->get('cache');
		$this->paths	= $this->sfe->paths;
		$this->hosts 	= $this->sfe->hosts;
		//$this->pubkey	= $container->get('pubkey');
		$pdo  			= $container->get('pdo');
		
		
	
		/*
		$this->sfe->type
		$this->sfe->paths
		$this->sfe->hosts
		$this->sfe->clientid
		$this->sfe->debug
			*/
		
		#$this->settings  = $container->get('sfe')['sfe'];
		#$this->paths  = $container->get('settings')['paths'];
		
//		$this->feConfig = new \Botnyx\Sfe\Backend\Core\Database\FrontendConfig($pdo);
///		$ClientConfig =$this->feConfig->getConfigByClientId($clientID);
		
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
	
	
	
	
	
	function get(ServerRequestInterface $request, ResponseInterface $response, array $args = []){
		
		$token = $request->getAttribute("token");
		$cid = $request->getAttribute("clientid");
		
		$ClientConfig = $request->getAttribute("clientconfig");
		$ClientRoutes = $request->getAttribute("clientroutes");
		
		
		//return $response->withJson( $token );
		
		echo "<pre>";
		
		//$language = $request->getAttribute("language");
		#echo "<pre>";
		//var_dump($token);
		//var_dump($cid);
		//var_dump($ClientConfig);
		//
		//die($token);
		
		$allGetVars = $request->getQueryParams();
		$allPostPutVars = $request->getParsedBody();
		
		#print_r($allGetVars);
		
		//
		$clientID = $args['clientid'];
		$language = $args['language'];
		
		// get the configuration for this client.
		
		//$ClientConfig =$this->feConfig->getConfigByClientId($clientID);
		if($ClientConfig==false){
			// Clientid not found in database
			die('xx');
			return \Botnyx\Sfe\Shared\ExceptionResponse::get($response,1105);
		}
		
		//var_dump($ClientConfig);
		//echo "</pre>";
		// get the routes for this client.
		
		//$ClientRoutes =$this->feConfig->getFrontendEndpoints($clientID);
		if($ClientRoutes==false){
			// Clientid returned no frontend endpoints.
			return \Botnyx\Sfe\Shared\ExceptionResponse::get($response,1106);
		}
		
		
		
		
		//$tmp['routes'] =$ClientRoutes;
		//$tmp['menus'] =$this->feConfig->getByMenuClientId($args['clientid']);
		
		
		
		$key = array_search($args['path'], array_column($ClientRoutes, 'id'));
		
		//print_r($args['path']);
		if($key===false){
			// NONEXISTENT ROUTE!
			throw new \Exception("Route doesnt exist.",404);
			var_dump($key);
			echo "<hr>";
			die();
		}
		
		//$ClientRoutes[$key]['tmpl'];
		
		//print_r( $ClientRoutes[$key] );
		
		$parsedPath = (object)array(
			"language"=>$language,
			"templateFile"=>$ClientRoutes[$key]['tmpl'],
			"requestedPath"=>$ClientRoutes[$key]['uri'],
			"variables"=>$allGetVars,
			"clientId"=>$ClientRoutes[$key]['client_id'],
			"scopes"=>explode(",",$ClientRoutes[$key]['scope'])
			
		);
		
		
		#echo "\n\n<b>$"."ClientRoute</b>\n";
		#print_r($ClientRoutes[$key]);
		
		
		
		
		
		
		/*
			Authentication section.
		
		*/
		
		#var_dump($request->hasHeader('Authorization'));
		#var_dump($request->getHeader("Authorization"));
		$roles = array();
		if( $request->hasHeader('Authorization') ){
			#var_dump($request->hasHeader("Authorization"));
			#var_dump($request->getHeader("Authorization"));
			
			$token = str_replace('Bearer ','',$request->getHeader("Authorization")[0] );
			
			if((strlen($token)!=0)  ){
				$decoded = JWT::decode($token, $request->getAttribute('pubkey')->publicKey, array('RS256')); 
				$roles = $decoded->roles;
			}
			
		}
		
		//return $response->withJson( $request->getAttribute('pubkey')->publicKeys );
		
		
		//$decoded = JWT::decode($token, $request->getAttribute('pubkey')->publicKey, array('RS256'));
		
		
		//return $response->withJson( $token );
		
		
		
		
		
		
		
		//($userRoles,$endpointScope)
		$has_access = $this->Acl($roles,$parsedPath->scopes);
		
		if($has_access===false){
			// NONEXISTENT ROUTE!
			throw new \Exception("Not Authorized.",401);
			var_dump($key);
			echo "<hr>";
			die();
		}
		
		//return $response->write( "No Access.." );
		
		
		
		
		
		
		
		//die();
		//print_r($ClientConfig);
		//print_r($tmp);
		//$route = $request->getAttribute('route');
    	//$courseId = $route->getArgument('id');
		
		//print_r($route);
		
		
		
		
		// 
		//$parsedPath = $this->parsePath($args['path'],$request->getAttributes('route')['routeInfo']);
		
		
		//echo "<pre>";
		
		// we now know:
		//$parsedPath->variables;
		//$parsedPath->templateFile;
		//$parsedPath->requestedPath;
		
		//print_r($tmp);
		
		
		//print_r($parsedPath);
		#echo "<br>TemplateFile:".$parsedPath['templateFile']."<br>";
		
		
		#print_r($this->paths);
		#print_r($this->hosts);
		//echo "<pre>";
		//print_r();
		
	//	"language" => $parsedPath->language,
	///	"getvars"	=>$parsedPath->variables,
		
		$array=array(
			"language" => $parsedPath->language,
			"clientid" => $parsedPath->clientId,
			"endpoint_id" => $ClientRoutes[$key]['id'],
			"endpoint" => $parsedPath->requestedPath,
			"template"=> $parsedPath->templateFile,
			"frontendserver"=>$ClientConfig['hostname'],
			"cdnserver"=>$ClientConfig['cdnhostname'],
			"backendserver"=>$ClientConfig['backendhostname'],
			"authserver"=>$this->hosts->auth
		);
		
		
		//print_r($array);
		//print_r();
		
		
		$pagefetcher = new HtmlDocument\FetchAndBuild($array);
		#echo "<pre>";
		
		//print_r($pagefetcher->components);
		
		
		
		//print_r($parsedPath);
		//print_r($_SERVER['HTTP_HOST']);
		//die();
		$array["brand"]="{{ brand }}";
		$array["menu"]["portfolio"]="mybrand";
		$array["menu"]["about"]="about";
		$array["menu"]["contact"]="contact";
		$array["subject"]["name"]="name";
		$array["subject"]["tagline"]="tagline";
		$array["subject"]["image"]="image";
		
		$array["section"]["portfolio"]['title']="Portfolio";
		$array["section"]["portfolio"]['items'][]=array("text"=>"text","image"=>"https://via.placeholder.com/450");
		$array["section"]["portfolio"]['items'][]=array("text"=>"text","image"=>"https://via.placeholder.com/450");
		$array["section"]["portfolio"]['items'][]=array("text"=>"text","image"=>"https://via.placeholder.com/450");
		$array["section"]["portfolio"]['items'][]=array("text"=>"text","image"=>"https://via.placeholder.com/450");
		
		
		$array["section"]["about"]['title']="title";
		$array["section"]["about"]['text']="text";
		
		$array["section"]["contact"]['title']="contact";
		$array["section"]["contact"]['nameplaceholder']="nameplaceholder";
		$array["section"]["contact"]['emailplaceholder']="emailplaceholder";
		$array["section"]["contact"]['phoneplaceholder']="phoneplaceholder";
		$array["section"]["contact"]['messageplaceholder']="messageplaceholder";
		$array["section"]["contact"]['sendbutton']="sendbutton";
		
		
		$array["section"]["xtra"]['col1']="col1";
		$array["section"]["xtra"]['col1text']="col1text";
		$array["section"]["xtra"]['col2']="col2";
		$array["section"]["xtra"]['socials'][]=array("link"=>"https://www.nu.nl","icon"=>"fa-facebook");
		$array["section"]["xtra"]['col3']="col2";
		$array["section"]["xtra"]['col3text']="col3text";
		
		$json = '{
				"brand": "mybrand",
				"menu":{
					"portfolio":"portfolio",
					"about":"about",
					"contact":"contact"
				},
				"subject":{
					"name":"myname",
					"tagline":"mytagline",
					"image":"https://via.placeholder.com/450","text":"some text goes here."
				},
				"section":{
					"portfolio":{
						"title":"Portfolio",
						"items":[
							{"text":"blag","image":"https://via.placeholder.com/450"},
							{"text":"blag","image":"https://via.placeholder.com/450"},
							{"text":"blag","image":"https://via.placeholder.com/450"}

						]
					},
					"about":{
						"title":"About",
						"text":"some boring text about me"
					},
					"contact":{
						"title":"Contact",
						"nameplaceholder":"nameplaceholder",
						"emailplaceholder":"emailplaceholder",
						"phoneplaceholder":"phoneplaceholder",
						"messageplaceholder":"messageplaceholder",
						"sendbutton":"sendbutton"
					},
					"xtra":{
						"col1":"xtra",
						"col1text":"xtra cool",
						"col2":"xtra2",
						"socials":[
							{"link":"https://www.nu.nl","icon":"fa-facebook"},
							{"link":"https://www.nu.nl","icon":"fa-google-plus"},
							{"link":"https://www.nu.nl","icon":"fa-twitter"},
							{"link":"https://www.nu.nl","icon":"fa-dribbble"},
						],
						"col2text":"xtra cool2",
						"col3":"xtra3",
						"col3text":"xtra cool3"

					}
					


				},
				"smallfooter": "blablabla copywrong 2012BC"
			}';
		//  Now run the html through twig.
		
		$templateVars= $array;//json_decode($json,true);
		#var_dump($templateVars);
		#die();
		
		//$parsedPath->language;
		#$str = mb_convert_encoding((string)$pagefetcher , "UTF-8","ASCII");
		
		#$cc = (string)$pagefetcher ;
		#$uu = utf8_encode($cc);
		
		
		#var_dump((string)$pagefetcher);
		
		
		// create loader, render html.
		$loader = new \Twig\Loader\ArrayLoader([
			$parsedPath->templateFile =>utf8_encode( (string)$pagefetcher )/*(string)$pagefetcher*/ ,
		]);
		
		//$function = new \Twig\TwigFunction('twigjs', function ($text) {
			// ...
		//	return "{{ ".$text." }}";
		//});
		
		$twig = new \Twig\Environment($loader);
		$twig->setCache($this->paths->temp."/".$parsedPath->clientId."/pages");
		
		// enable caching.
		$twig->setCache(false);
		
		//echo "".$parsedPath->templateFile;
		
		$html =  $twig->render($parsedPath->templateFile, $templateVars);
		
		return $response->write( $html );
	}
	
	
	
	
	function AclPermissions(){
		$dbresult = array(
			/*'is_anon'		=>'anonymous visitor',
			'is_registered'	=>'registered user',*/

			'can_view'		=>'can view items'/*,
			'can_edit'		=>'can edit items'
			,
			'can_submit'	=>'can submit items',
			'can_revise'	=>'can revise items',

			'can_publish'	=>'can publish items',
			'can_archive'	=>'can archive items',
			'can_delete'	=>'can delete items'
			*/
		);
		return $dbresult;
	}
		
	function AclRoles(){
		$dbresult = array(
			array("role"=>"guest",	"inherits"=>"",			"desc"=>""),
			array("role"=>"user",	"inherits"=>"",			"desc"=>""),
			array("role"=>"admin",	"inherits"=>"user",		"desc"=>""),
			array("role"=>"superadmin",	"inherits"=>"admin","desc"=>""),
			
		);
		return $dbresult;
	}
	
	function getSfeRole($userRoles){
		print_r($userRoles);
		foreach( $this->AclRoles() as $role){
			if( in_array(  $role['role'],$userRoles ) ){
				echo "".$role['role']." in userRoles<br>";
				
				//if(){
				return $this->inheritsfrom($role['role']);	
				//}
				
			}
		}
	}
	
	function inheritsfrom($role){
		
		
		
		foreach($this->AclRoles() as $r){
			//print_r($r);
			if($r['role']==$role && $r['inherits']==""){
				echo "* Role:".$role." inherits from ";
				echo "nobody!<br>";
				//return $r['role'];
			}elseif($r['role']==$role){
				
				return $this->inheritsfrom($r['inherits']);
			}
		}
		//echo "";
		//return false;
	}
	
	
	
	function Acl($userRoles,$endpointRole){
		
		//echo "function Acl()<br>";
		
		#if( empty( $userRoles ) ){
		#	$userRoles[]="guest";
		#	$userRoles[]="admin";
		#}
		
		
		//$sfeRole = $this->getSfeRole($userRoles); 
		
		echo "<br><b>userRoles:</b>";
		print_r($userRoles);
		echo "<hr>";
		echo "<br><b>endpointRole:</b>";
		print_r($endpointRole);
		
		
		die();
		#$userRoles=array();
		#$userRoles[]="admin";
		#$userRoles[]="user";
		#$userRoles[]="guest";
		
		/* Permissions */
		$permissions = new SfeAcl\Permissions( $this->AclPermissions() );
		#echo "\n\n<b>$"."permissions</b>\n";
		#print_r($permissions);
						
		
		/*
			this defines what roles can access the endpoint. (AclResources)
		*/
		$endpointRole = array("id"=>"id","link"=>"link","text"=>"text","scopes"=>$endpointRole);
		
		
		/* 
			Resources 
				
		*/
		$resources = new SfeAcl\Resources( array($endpointRole) );
		echo "\n\n<b>$"."resource scopes</b>: ".implode(",",$resources->resources['id']['scopes']);
		
		#foreach($resources->resources['id']['scopes'] as $scope){
		#	echo $scope." ";
		#}
		
		#echo "\n";
		
		
		
//		in_array($needle,$hooiberg);
		
				
		//print_r( $resources);
		
		print_r($this->AclRoles());
		
		/* Roles */
		$roles = new SfeAcl\Roles( $this->AclRoles() , $userRoles );
		
		
		
		
		
		//print_r($roles);
		
		/* AccessControlList */		
		$AccesControlList = new SfeAcl\Acl($roles);
		
		
			
		
		
		
		
		#foreach( $resources->resources['scopes'] as $scope){
			/* Allow access to this resource. */
		#	$AccesControlList->allow($scope , null, "is_anon");
			//$AccesControlList->allow('user', null,  "is_registered");	
		#}
		
		/* Allow access to this resource. */
		//                        ROL           WAT HIJ KAN.
		foreach($resources->resources['id']['scopes'] as $scope){
			$AccesControlList->allow($scope, null, "can_view");
		}
		
		#$AccesControlList->allow('user', null,  "can_view");
		#$AccesControlList->allow('admin', null, "can_view");
		//$AccesControlList->allow('user', null,  "can_view");
		
		
		//foreach(){
		//	echo "<br>".$AccesControlList->isAllowed("guest",null, 'is_anon') ? 'allowed' : 'denied';
			
		//}
		
		
		/* Check if user has access for this page. */
		echo "<br>You (".$roles->userrole.") :";
		echo $AccesControlList->isAllowed($roles->userrole,'can_view') ? 'allowed' : 'denied';
		
		#echo "<br>guest :";
		#echo $AccesControlList->isAllowed("guest",'can_view') ? 'allowed' : 'denied';
		
		#echo "<br>user :";
		#echo $AccesControlList->isAllowed("user" ,'can_view') ? 'allowed' : 'denied';
		
		#echo "<br>admin :";
		#echo $AccesControlList->isAllowed("admin" ,'can_view') ? 'allowed' : 'denied';
		
		#echo "<br>".$AccesControlList->isAllowed($currentUserRole,"1", 'can_view') ? 'allowed' : 'denied';
		#echo "<br>".$AccesControlList->isAllowed($currentUserRole,"2", 'can_view') ? 'allowed' : 'denied';
		
		#echo "<br>x :";
		#echo $AccesControlList->isAllowed( 'is_anon' ) ? 'allowed' : 'denied';

		//die();
		
		return $AccesControlList->isAllowed($roles->userrole,'can_view') ? true : false;
	}
	
	
}