<?php

/**
*	Middleware for the backend role.
*
*
*
*
**/

if(!array_key_exists('pdo',$container)){
	$container['pdo'] = $pdo;
}

// Register globally to app
$container['session'] = function ($c) {
  return new \SlimSession\Helper;
};



$backendMiddleware = function ($request, $response, $next)  {

  	$clientIssuer=_SETTINGS['sfeBackend']['sfeAuthSrv'];//"https://auth.devpoc.nl";

  	$sfeBackendMiddleWare = new \Botnyx\Sfe\Backend\Core\MiddleWare($request, $response,$this->pdo,$clientIssuer );
  	// Add requestAttributes.
  	$request = $sfeBackendMiddleWare->addRequestAttributes($request);
  	$response = $next($request, $response);
  	// Add responseHeaders.
  	$response = $sfeBackendMiddleWare->addResponseHeaders($response);
  	return $response;;//->withHeader('Access-Control-Allow-Origin','*');
};
/*
*/

/**/
$app->add($backendMiddleware);
