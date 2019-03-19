<?php

namespace Botnyx\Sfe\Backend\Core\Frontend;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;



class UiElementLoader {
	
	function __construct(ContainerInterface $container){
		
	}
	
	function get(ServerRequestInterface $request, ResponseInterface $response, array $args = []){
		
		$item = $request->getQueryParam('item');
		
		$client_id = $args['clientId'];		
		
		
		
		#print_r($request->getQueryParam('item'));
		#print_r($request->getParsedBody());
		
		return  $response->write( "<li><a href='#c/test1'>test1</a></li><li><a href='#'>test2</a></li>" )->withStatus(200)->withHeader('Access-Control-Allow-Origin','*');
	}
	
	
	function getMain(ServerRequestInterface $request, ResponseInterface $response, array $args = []){
		
				
		return  $response->write( "<hr><div class='container'><div class='row'>hssoi</div></div>" )->withStatus(200)->withHeader('Access-Control-Allow-Origin','*');
	}
}
