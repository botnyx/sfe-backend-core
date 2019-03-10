<?php

namespace Botnyx\Sfe\Backend\Core\Template;

/*
	BaseLoader :Loads the base html.
	
	which is   <html><head></head><body></body></html>

*/
class ClientLoader extends BaseLoader{
	var $debug=true;
	
	var $fromFileCachePrefix = "_sclient";
	var $fromStringCachePrefix = "_fclient";
}