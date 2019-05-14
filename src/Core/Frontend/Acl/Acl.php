<?php

namespace Botnyx\Sfe\Backend\Core\Frontend\Acl;


use Zend\Permissions\Acl\Acl as ZendAcl;

class Acl extends ZendAcl
{
    const ROLE_EVERYONE = 'guest';
    const ROLE_MR_X     = 'MR_X';
    protected $roles = [
        'member',
        /*'manager',
        'owner',*/
        'admin',
    ];
    protected $resources = [
        'current-endpoint',
        /*'member-logic',
        'manager-logic',
        'owner-logic',
        'admin-logic',*/
    ];

    public function getRoles()
    {
        return $this->roles;
    }
	
	
    public function __construct(array $roles = [])
    {
        // hard-coded role
        $this->addRole(self::ROLE_EVERYONE);
        // configure roles
        foreach ($this->roles as $role) $this->addRole($role);



        // configured resources
        foreach ($this->resources as $res) $this->addResource($res);


        // "everyone: assignment
        //$this->allow(self::ROLE_EVERYONE, 'current-endpoint', ['guest']);

        
        // basic assignments
        $this->allow('member',  'current-endpoint',    ['member','guest']);
        //$this->allow('manager', 'member-logic',  ['view']);
        //$this->allow('manager', 'manager-logic', ['view']);
        //$this->allow('owner', 'manager-logic',   ['view']);
        //$this->allow('owner', 'owner-logic',     ['view']);
        //$this->allow('admin', 'owner-logic',     ['view']);
        
		//$this->allow('admin', 'current-endpoint',   ['member']);
        $this->allow('admin', 'current-endpoint',   [ 'admin','member','guest']);


        // add "everyone" to $roles
        if (!in_array(self::ROLE_EVERYONE, $roles)) $roles[] = self::ROLE_EVERYONE;
        $this->addRole(self::ROLE_MR_X, $roles);
    }




	
		
	function filterAclRoles($providedRoles)
	{
		$acl = new Acl();
		$availableRoles = $acl->getRoles();
		$Roles = array();
		foreach( $providedRoles as $providedRole ){
			//echo $providedRole."<br>";
			if( in_array( $providedRole, $availableRoles ) ){
				$Roles[]=$providedRole;
			}
		}
		if(count($Roles)==0){
			$Roles[]="guest";
		}
		return $Roles;
	}
	
	
	
	function testAcl($roles, $resource, $rights)
	{
		$roles = $this->filterAclRoles($roles) ;
		$acl = new Acl($roles );
		//return ($acl->isAllowed(Acl::ROLE_MR_X, $resource, $rights)) ? 'YES' : 'NO';

		$output = 'Mr X has this role(s): ' . implode(',', $roles) . PHP_EOL
				. 'Is Mr X is allowed to use the '
				. $resource . ' with '
				. $rights . ' access? ';
		$output .= ($acl->isAllowed(Acl::ROLE_MR_X, $resource, $rights)) ? 'YES' : 'NO';
		return $output . PHP_EOL . PHP_EOL;
		/*
		*/
	}
	
	function hasAccess($roles, $resource, $rights)
	{
		$roles = $this->filterAclRoles($roles) ;
		$acl = new Acl($roles );
		return ($acl->isAllowed(Acl::ROLE_MR_X, $resource, $rights)) ? true : false;

		$output = 'Mr X has this role(s): ' . implode(',', $roles) . PHP_EOL
				. 'Is Mr X is allowed to use the '
				. $resource . ' with '
				. $rights . ' access? ';
		$output .= ($acl->isAllowed(Acl::ROLE_MR_X, $resource, $rights)) ? 'YES' : 'NO';
		return $output . PHP_EOL . PHP_EOL;
		/*
		*/
	}
	
	
	

}
