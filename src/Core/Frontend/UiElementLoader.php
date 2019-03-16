<?php

namespace Botnyx\Sfe\Backend\Core\Frontend;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;



class UiElementLoader {
	
	function __construct(ContainerInterface $container){
		
	}
	
	function get(ServerRequestInterface $request, ResponseInterface $response, array $args = []){
		
				
		return  $response->write( "<li><a href='#'>hssoi</a></li>" )->withStatus(200)->withHeader('Access-Control-Allow-Origin','*');
	}
	
	function getMain(ServerRequestInterface $request, ResponseInterface $response, array $args = []){
		
				
		return  $response->write( "<div>hssoi</div>" )->withStatus(200)->withHeader('Access-Control-Allow-Origin','*');
	}
}
