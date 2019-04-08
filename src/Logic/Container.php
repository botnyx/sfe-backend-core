<?php

namespace Botnyx\Sfe\Backend\Logic;

class Container {
	
	
	public function get($container){
		
		
		#print_r($container->get('sfe'));
		#die();
		#$container->get('sfe')->clientid;
		#$container->get('sfe')->paths->templates;
		#$container->get('sfe')->hosts->backend;
		#$container->get('sfe')->debug;
		
		#$container->get('sfe')->twig('cache');
		#$container->get('sfe')->twig('debug');
		#$container->get('sfe')->twig('extensions');
		
		$container['cache'] = function ($c) {
			return new \Slim\HttpCache\CacheProvider();
		};

		
		$container['view'] = function ($c){

			$view = new \Slim\Views\Twig($container->get('sfe')->paths->templates, [
				'cache' => $container->get('sfe')->twig('cache'),
				'debug' => $container->get('sfe')->twig('debug')
			]);

			// Instantiate and add Slim specific extension
			$basePath = rtrim(str_ireplace('index.php', '', $c->get('request')->getUri()->getBasePath()), '/');

			// Add twigExtension
			$view->addExtension(new \Slim\Views\TwigExtension($c->get('router'), $basePath));

			// Add the debug extension
			#if(_SETTINGS['twig']['debug']==true){
			#	$view->addExtension(new \Twig_Extension_Debug() );
			#}
			
			foreach( $container->get('sfe')->twig('extensions') as $ext){
				$view->addExtension( $ext );
			}
				
			// add Translation extensions.
			

			//$view->addExtension(new \Twig_Extensions_Extension_Intl());
			//$twig->addExtension(new Project_Twig_Extension());
			//
			//$twig->addFunction('functionName', new Twig_Function_Function('someFunction'));

			#if(array_key_exists('sfeBackend',_SETTINGS)){
				//$view->addFunction('functionName', new Twig_Function_Function('someFunction'));

				//$view->addExtension ( new \Botnyx\sfeBackend\twigExtension\Userinfo() );
			#}
			return $view;
		};

		//echo "<pre>";
		//print_r($container);
		
		return $container;
	}
	
	
	
}
