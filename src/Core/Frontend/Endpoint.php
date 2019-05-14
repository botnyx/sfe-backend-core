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
		
		//echo "<pre>";
		
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
			var_dump($key);
			echo "<hr>";
			throw new \Exception("Route doesnt exist.",404);
			
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
		
		
		
		if( !in_array('guest', $parsedPath->scopes) ){
			//echo $this->testAcl($roles, 'current-endpoint', 'guest');
			//$this->hasAccess($roles, 'current-endpoint', 'member');
			$has_access = ( $this->hasAccess($roles, 'current-endpoint', 'guest') ) ? true : false;
		}else{
			$has_access = true;
		}
		
		
						
		if($has_access==false){
			//return \Botnyx\Sfe\Shared\ExceptionResponse::get($response,1106);
			//return \Botnyx\Sfe\Shared\ExceptionResponse::get($response,401);
			return $response->write("401")->withStatus( "401",401 );
			// NONEXISTENT ROUTE!
			//throw new \Exception("Not Authorized.",401);
			#var_dump($key);
			#echo "<hr>";
			#die();
		}
		
		
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
		
		//var_dump($has_access);,
		//"has_access"=>$has_access
		$pagefetcher = new HtmlDocument\FetchAndBuild($array,$has_access);
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
	
	
	
	
	function filterAclRoles($providedRoles)
	{
		$acl = new SfeAcl\Acl();
		return $acl->filterAclRoles($providedRoles);
	}

	function testAcl($roles, $resource, $rights)
	{
		$acl = new SfeAcl\Acl();
		return $acl->testAcl($roles, $resource, $rights);
	}
	
	function hasAccess($roles, $resource, $rights)
	{
		$acl = new SfeAcl\Acl();
		return $acl->hasAccess($roles, $resource, $rights);
	}
	
	
	
}