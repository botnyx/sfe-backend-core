<?php

namespace Botnyx\Sfe\Backend\Core\Frontend;


class ComponentConfig extends \Botnyx\SimpleObject {
	function objectProperties(){
		
		return array(
			"client_id"		=> array("type"=>"string","required"=>true),
			"endpoint_id"	=> array("type"=>"string","required"=>true),
			"user_id"		=> array("type"=>"string","required"=>false),
			"roles"			=> array("type"=>"array","required"=>true),
			"language"		=> array("type"=>"string","required"=>true),
		);
		
	}
}