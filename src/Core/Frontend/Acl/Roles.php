<?php

namespace Botnyx\Sfe\Backend\Core\Frontend\Acl;

use Zend\Permissions\Acl\Acl as Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;

class Roles {
	
	private $userrole;
	
    private $roles;

    function __construct( array $roles, array $userRoles = array() ){
        $this->roles = $this->setRoles($roles);
		
		$this->setUserRole($userRoles);
		
    }
	
	private function setUserRole($userRoles){
		
		if(empty($userRoles)){
			 //$this->userrole =  $this->userrole;
		}else{
			foreach($userRoles as $r){
				if( array_key_exists($r,$this->roles) ){
					$this->userrole = $r;
					break;
				}
				
			}
			$this->userrole =  $this->userrole;
		}
		
	}
	
    private function setRoles($roles){
        $out = array();
        foreach($roles as $r){
            $out[$r['role']]=$r;
        }
        return $out;
    }

    function __get($propertyName){
        if(!isset($this->$propertyName)){
            throw new Exception("No such propterty.");
        }
        return $this->$propertyName;
    }

	
	
	
	

    function __ROLES (){
        #ROLES
        return array(
          'guest'=>array(			  'inheritfrom'=>'', 'desc'=>'duh'),
          'user'=>array(			  'inheritfrom'=>'guest', 'desc'=>'Average Joe'),
          'critic'=>array(		  'inheritfrom'=>'user', 'desc'=>'can rate and review content, but not create original content'),
          'subscriber'=>array(	'inheritfrom'=>'user', 'desc'=>'Paying Average Joe'),
          'member'=>array(		  'inheritfrom'=>'user', 'desc'=>'Special user access'),
          'ambassador'=>array(	'inheritfrom'=>'', 'desc'=>'site rep for external communications, has access to site email, PR materials'),
          'emeritus'=>array(		'inheritfrom'=>'', 'desc'=>'retired key users who no longer contribute, but whose contributions are honored'),
          'contributors'=>array('inheritfrom'=>'', 'desc'=>'Authors with limited rights'),
          'author'=>array(		  'inheritfrom'=>'', 'desc'=>'Write important content'),
          'editor'=>array(		  'inheritfrom'=>'', 'desc'=>'Doing some stuff beyond writing: scheduling and managing content'),
          'manager'=>array(		  'inheritfrom'=>'', 'desc'=>'Manage most aspects of the site'),
          'admin'=>array(			  'inheritfrom'=>'', 'desc'=>'Manage everything'),
          'moderator'=>array(		'inheritfrom'=>'', 'desc'=>'Moderate user content')
        );
    }
}