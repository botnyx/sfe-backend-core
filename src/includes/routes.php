<?php




use Slim\Http;
use Slim\Views;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;


$app->get('/api/cfg/{clientid}', function ( $request,  $response, array $args){


	$extcfg = array(
		'clientId'=>'709b6bb0-devpoc-website',
		'htmlts'=>1234,
		'html'=>null,
		'lang'=>array('en-UK','nl-NL'),
		'js'=>array(),
		'css'=>array(),
		'template'=>'laborator/neon-bootstrap-admin-theme',
		'extracfg'=>array(
				"allowedorigin"=>"*",
				"backendhostname"=>"backend.devpoc.nl",
				"cdnhostname"=>"cdn.devpoc.nl",
				"client_id"=>"709b6bb0-devpoc-website",
				"defaultpage"=>"home",
				"disabled"=>0,
				"disabledreason"=>"",
				"hostname"=>"devpoc",
				"htmlstamp"=>"123",
				"languages"=>"en-UK,nl-NL",
				"requestedLanguage"=>array("en-UK,nl-NL"),
				"template"=>"laborator/neon-bootstrap-admin-theme",
				"workbox"=>0,
				"workboxnav"=>null
			)
		);
	$fe_cfg = new \Botnyx\SfeBackend\Database\frontend_config($this->pdo);

	$localRoutes = $fe_cfg->getStaticUrlsByClientId($args['clientid']);

	$zlocalRoutes[]=array(
		"uri"=>"/",
		"fnc"=>"\\Botnyx\\SfeFrontend\\Endpoint:get",
		"tmpl"=>"laborator/neon-bootstrap-admin-theme"
	);
	$zlocalRoutes[]=array(
		"uri"=>"/newspaper/edition/{edition}",
		"fnc"=>"\\Botnyx\\SfeFrontend\\Endpoint:get",
		"tmpl"=>"botnyx/newspaper"
	);
	$zlocalRoutes[]=array(
		"uri"=>"/newspaper/article/{articleid}",
		"fnc"=>"\\Botnyx\\SfeFrontend\\Endpoint:get",
		"tmpl"=>"botnyx/newspaper"
	);
	$zlocalRoutes[]=array(
		"uri"=>"/newspaper",
		"fnc"=>"\\Botnyx\\SfeFrontend\\Endpoint:get",
		"tmpl"=>"botnyx/newspaper"
	);
	$zlocalRoutes[]=array(
		"uri"=>"/sw.js",
		"fnc"=>"\\Botnyx\\SfeFrontend\\Endpoint:getServiceWorker",
		"tmpl"=>""
	);


	$lastUpdated = time() - 3600;

 	$data = array(
		'lastupdated'=>$lastUpdated,
		'routes'=>$localRoutes,
		'clientid'=>$args['clientid'],
		'userprefs'=>array("language"=>"nl_NL"),
		'status'=>'ok',
	);
	//return $response->write('')->withStatus(401);
	//return $response->withJson($data);//->withStatus(500);



	$res = $response->withJson($data);
	//$resWithExpires = $this->cache->withExpires($res, time() + 3600);
	//$res = $this->cache->withExpires($res, time() + 3600);
	$resWithLastMod = $this->cache->withLastModified($res, $lastUpdated);

	return $resWithLastMod;



});

/*

	Site config endpoint..

*/
$app->get('/api/sfe/ui/extcfg','\\Botnyx\\SfeBackend\\Api\\Config:get');

/*

	ServiceWorker JS endpoint..

*/

$app->get('/api/sfe/{clientid}/ui/sw','\\Botnyx\\SfeBackend\\Serviceworker\\generator:get');


/*
	Static url proxy

*/
$app->get('/api/sfe/{clientid}/uri/[{path:.*}]','\\Botnyx\\SfeBackend\\StaticFrontEnd:get');




/*
$app->get('/api/sfe/ui/{o}/extcfg','\\Botnyx\\SfeBackend\\Api\\Config:get');
*/


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

	ui load endpoint..

*/
$app->get('/api/sfe/{clientId}/ui/load', '\\Botnyx\\SfeBackend\\Api\\Load:get' );


/*

	ui click endpoint..

*/
$app->get('/api/sfe/{clientId}/ui/click', '\\Botnyx\\SfeBackend\\Api\\Click:get' );





/*

	CallbackRoute for Ext. auth provider

*/
//$app->get('/ext/{providerUrlKey}/cb',  "\\Botnyx\\SfeBackend\\Api\\Callback:get");
$app->get('/ext/{authReqClientId}/{providerUrlKey}/cb',  "\\Botnyx\\SfeBackend\\Api\\Callback:get");





	$app->get('/robots.txt',  function ( $request,  $response, array $args){
		$res = "User-agent: *".PHP_EOL."Disallow: /";
		return $response->write($res);

	});
