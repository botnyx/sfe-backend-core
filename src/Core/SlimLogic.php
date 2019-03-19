<?php

namespace Botnyx\Sfe\Backend\Core;


use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;


class SlimLogic {
	
	
	public function getContainer($container){
		
		
		
		
		$container['cache'] = function ($c) {
			return new \Slim\HttpCache\CacheProvider();
		};

		
		//echo "<pre>";
		//print_r($container);
		
		return $container;
	}
	
	public function getMiddleware($app,$container){
		
		
		return $app;
	}
	
	public function getRoutes($app,$container){
		
		
		
		//foreach($container['frontendconfig']->routes as $route){
		//	$app->get( $route->uri,$route->fnc );

		//}
		
		return $app;
	}

}