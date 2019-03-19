<?php

namespace Botnyx\Sfe\Backend\Core\Objects\config;

//namespace Botnyx\Sfe\Backend\Core;

class Backend {
	
	var $clientid="";
	var $clientsecret="";
	var $conn;
	
	function __construct($settings){
		
		
		
		if(!array_key_exists('clientId',$settings['sfeBackend'])){
			throw new \Exception("Fatal Error in Configuration.ini : Missing `clientId` in the `sfeBackend` section.");
		}
		$this->clientid = $settings['sfeBackend']['clientId'];
		
		#$section_settings = $settings['sfeBackend'];

		
		
		
		if(!array_key_exists('clientSecret',$settings['sfeBackend'])){
			throw new \Exception("Fatal Error in Configuration.ini : Missing `clientSecret` in the `sfeBackend` section.");
		}else{
			$this->clientsecret = new \Botnyx\Sfe\Shared\ProtectedValue($settings['sfeBackend']['clientSecret']);
		}
		
		
		$hosts = new \Botnyx\Sfe\Shared\Objects\config\SfeHosts();

		if(!array_key_exists('sfeAuth',$settings['sfeBackend'])){
			throw new \Exception("Fatal Error in Configuration.ini : Missing `sfeAuth` in the `sfeBackend` section.");
		}
		$hosts->auth = $settings['sfeBackend']['sfeAuth'];
		
		
		if(!array_key_exists('sfeCdn',$settings['sfeBackend'])){
			throw new \Exception("Fatal Error in Configuration.ini : Missing `sfeCdn` in the `sfeBackend` section.");
		}
		$hosts->cdn = $settings['sfeBackend']['sfeCdn'];
		
		if(!array_key_exists('sfeBackend',$settings['sfeBackend'])){
			throw new \Exception("Fatal Error in Configuration.ini : Missing `sfeBackend` in the `sfeBackend` section.");
		}
		$hosts->backend = $settings['sfeBackend']['sfeBackend'];
		
		
		
		$this->hosts = $hosts;
		
		if(!array_key_exists('conn',$settings['sfeBackend'])){
			throw new \Exception("Fatal Error in Configuration.ini : Missing `conn` in the `sfeFrontend` section.");
		}else{
			
			$this->conn = (object) array("dsn"=> $settings['sfeBackend']['conn']['dsn'],
				 "dbuser"=> $settings['sfeBackend']['conn']['dbuser'],
				 "dbpassword"=> new \Botnyx\Sfe\Shared\ProtectedValue($settings['sfeBackend']['conn']['dbpassword'])
				 );
			
		}
		//return $data;
	}
	
	
}