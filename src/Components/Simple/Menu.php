<?php

//namespace Botnyx\Sfe\Backend\HtmlDocument;
namespace Botnyx\Sfe\Backend\Components\Simple;

use Botnyx\Sfe\Backend\Core\Frontend as FrontEnd;

class Menu extends Frontend\ComponentBase {
	
	// Botnyx\Sfe\Backend\Core\Frontend\Acl
	
	
	
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
		
		guest - anon visitor
		user	- authenticated user
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
		
		//$this->acl->testAcl($this->sfe_roles, 'current-endpoint', "guest");
		
		
		$array = $this->get_data();
		$records = array();
		
		foreach( $array['records'] as $menuItem ){
			//echo "<hr>".$menuItem["link"]." ".$menuItem['scopes']."<br>" ;
			
			//echo "<pre>";
			//print_r($this->acl->filterAclRoles($this->roles));
			//var_dump(is_null($menuItem['scopes']));
			$has_access = false;
			if(is_null($menuItem['scopes'])){
				$has_access = false;
			}elseif(!in_array('guest',$this->acl->filterAclRoles($this->scopes($menuItem['scopes']))) ){
				$has_access = ( $this->acl->hasAccess($this->roles, 'current-endpoint', 'guest') ) ? true : false;
			}else{
				$has_access = true;
			}
			
			//var_dump($has_access);
			//echo "</pre>";
			//die();
			//;
			//$x = $this->acl->testAcl($this->roles, 'current-endpoint', $this->scopes($menuItem['scopes']) );
			
			//die();
			if( $has_access ){
				$records[]=$menuItem;
			}
			
			
		}
		//die();
		
		return array("records"=>$records);
	}
	
	
	
};