<?php

//namespace Botnyx\Sfe\Backend\HtmlDocument;
namespace Botnyx\Sfe\Backend\Components\Simple;

use Botnyx\Sfe\Backend\Core\Frontend as FrontEnd;

class Menu extends Frontend\ComponentBase {
	
	
	var $dataEndpoint = '/records/sf_menu';
	
	/*
		var $client_id;
		var $endpoint_id;
		var $roles;
		var $language;
		var $user_id;
		
		admin
	*/
	
	/*
		Possible Scopes:
		
		visitor - anon visitor
		user	- authenticated user
		client 	- applicationOwner
		admin   - admin
		
		
	*/
	
	
	function scopes($scopes){
		return explode(',',$scopes);
	}
	
	
	function get_data(){
		$params = [
		   'query' => [
			  'filter'=>'clientid,eq,'.$this->client_id			  
		   ]
		];
		return $this->call( $this->dataEndpoint, $params );
	}
	
	
	function get(){
		
		$array = $this->get_data();
		$records = array();
		
		foreach( $array['records'] as $item ){
			if( !array_diff($this->roles, $this->scopes($item['scopes'])) ){
				$records[]=$item;
			}
		}
		
		
		return array("records"=>$records);
	}
	
	
	
};