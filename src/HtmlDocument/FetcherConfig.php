<?php

namespace Botnyx\Sfe\Backend\HtmlDocument;


// extends ob_Props
class FetcherConfig extends \Botnyx\SimpleObject {
	
	function objectProperties(){
		return array(
			"clientid"=>	array( "type"=>"string", "required"=>true, "defval"=>"defaultVal"  ),
			"endpoint"=>	array( "type"=>"string", "required"=>false, "defval"=>""  ), 
			"template"=>	array(  "type"=>"string", "required"=>false, "defval"=>""  ), 
			"frontendserver"=>array("type"=>"string", "required"=>true ),
			"cdnserver"=>	array(  "type"=>"string", "required"=>false, "defval"=>""  ), 
			"backendserver"=>array(  "type"=>"string", "required"=>false, "defval"=>"" ),
			"authserver"=>	array(  "type"=>"string", "required"=>false, "defval"=>""  )
		);
	}
	
	
}
