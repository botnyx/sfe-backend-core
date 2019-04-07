<?php

namespace Botnyx\Sfe\Backend\HtmlDocument;

//  pageFetcher;
class Fetcher {
	
	function __construct(endpointSettings $endpointSettings){
		$this->endpointSettings= $endpointSettings;
		//print_r($endpointSettings);
		
		$this->endpointSettings->cdnserver;
		$this->endpointSettings->endpoint;
		
	}
	
	function get (){
		$url= 'http://'.$this->endpointSettings->cdnserver.$this->endpointSettings->endpoint;
		$html = "";
		if ($stream = fopen($url, 'r')) {
			$html =  stream_get_contents($stream);
			fclose($stream);
		}
		return $html;
	}
	
}