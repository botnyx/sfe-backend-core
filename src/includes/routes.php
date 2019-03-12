<?php




use Slim\Http;
use Slim\Views;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;


$app->get('/api/cfg/{clientid}','\\Botnyx\\Sfe\\Backend\\Core\\Frontend\\Configuration:get');



/*
	Static url proxy

*/
$app->get('/api/sfe/{clientid}/uri/[{path:.*}]','\\Botnyx\\Sfe\\Backend\\Core\\Frontend\\Endpoint:get');




#$app->get('/api/sfe/{clientid}/uri/[{path:.*}]','\\Botnyx\\SfeBackend\\StaticFrontEnd:get');





//$app->get('/api/cfg/{clientid}', function ( $request,  $response, array $args){ 
//});



/*

	Site config endpoint..

* /
$app->get('/api/sfe/ui/extcfg','\\Botnyx\\SfeBackend\\Api\\Config:get');

/*

	ServiceWorker JS endpoint..

* /

$app->get('/api/sfe/{clientid}/ui/sw','\\Botnyx\\SfeBackend\\Serviceworker\\generator:get');




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

	ui load endpoint..

* /
$app->get('/api/sfe/{clientId}/ui/load', '\\Botnyx\\SfeBackend\\Api\\Load:get' );


/*

	ui click endpoint..

* /
$app->get('/api/sfe/{clientId}/ui/click', '\\Botnyx\\SfeBackend\\Api\\Click:get' );





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
