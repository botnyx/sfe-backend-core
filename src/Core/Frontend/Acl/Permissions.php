<?php

namespace Botnyx\Sfe\Backend\Core\Frontend\Acl;

use Zend\Permissions\Acl\Acl as Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;

/*
   define the permissions
*/
class Permissions {

    private $permissions;

    function __construct(array $perms){
        $this->permissions = $perms;
    }


    function __get($propertyName){
        if(!isset($this->$propertyName)){
            throw new Exception("No such propterty.");
        }
        return $this->$propertyName;
    }

}