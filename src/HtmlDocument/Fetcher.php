<?php

namespace Botnyx\Sfe\Backend\HtmlDocument;

//  pageFetcher;
class Fetcher {
	
	function __construct(FetcherConfig $config){
		$this->endpointSettings= $config;
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