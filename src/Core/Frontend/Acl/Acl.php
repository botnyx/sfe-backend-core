<?php

namespace Botnyx\Sfe\Backend\Core\Frontend\Acl;

use Zend\Permissions\Acl\Acl as ZendAcl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;

class Acl {

    private $acl;
	private $userrole;
	
    function __construct(Roles $roles){
        $this->acl = new ZendAcl();
        $this->createRoles($roles);
        #$this->permissions = new Permissions();
    }

    private function createRoles ($roles){
		
		$this->userrole = $roles->userrole;
		
		//echo "createRoles ()<br>";
        foreach( $roles->roles as $role=>$roleinfo ){
			if($roleinfo['inherits']!=""){
              	//echo $role." inherits -> ".$roleinfo['inherits']."<br>";
				$this->acl->addRole(new Role($role,$roleinfo['inherits']) );
            }else{
              $this->acl->addRole(new Role($role));
            }
        }
		//print_r($this);
    }



    public function allow($role,$resource=null,$permission){
        return $this->acl->allow($role,$resource,$permission);
    }

    public function isAllowed($userrole,string $permission){
		
        return $this->acl->isAllowed($userrole,null,$permission);
		
    }

    public function addResource(array $array){
        $this->acl->addResource($array);
    }

}