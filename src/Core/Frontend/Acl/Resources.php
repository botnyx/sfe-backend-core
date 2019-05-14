<?php

namespace Botnyx\Sfe\Backend\Core\Frontend\Acl;

use Zend\Permissions\Acl\Acl as Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;


class Resources{
    private $resources;

    function __construct(array $dbresult){
        $this->setResources($dbresult);
    }
    private function setResources($dbresult){
        $out = array();
        foreach($dbresult as $r){
			if(is_array($r['scopes'])){
				$r['scopes']=$r['scopes'];
			}else{
				$r['scopes']=explode(",",$r['scopes']);
			}
            
            $out[$r['id']]=$r;
        }
        $this->resources = $out;
    }

    function __get($propertyName){
        if(!isset($this->$propertyName)){
            throw new Exception("No such propterty.");
        }
        return $this->$propertyName;
    }
}