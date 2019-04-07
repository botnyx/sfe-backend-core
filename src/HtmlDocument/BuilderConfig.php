<?php

namespace Botnyx\Sfe\Backend\HtmlDocument;


class BuilderConfig extends Botnyx\SimpleObject {
	function objectProperties(){
		return array(
			"baseDomain"=>	array( "type"=>"string",  "required"=>false,  "defval"=>"localhost"  ),
			"cache"		=>	array( "type"=>"bool", 	  "required"=>false, "defval"=>false  ), 
			"visibility"=>	array(  "type"=>"string", "required"=>false, "defval"=>"private" /* public/private */ ), 
			"type"		=>	array(  "type"=>"string", "required"=>false, "defval"=>"site"  ) /* article/site/list */, 
			"language"	=>	array(  "type"=>"string", "required"=>false, "defval"=>"english" ),
			"title"		=>	array(  "type"=>"string", "required"=>false, "defval"=>""  ),
			"description"=>	array(  "type"=>"string", "required"=>false, "defval"=>""  ),
			"keywords"=>	array(  "type"=>"string", "required"=>false, "defval"=>""  ),
			"image"		=>	array(  "type"=>"string", "required"=>false, "defval"=>""  ),
			
		);
	}
}
