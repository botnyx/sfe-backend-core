<?php

namespace Botnyx\Sfe\Backend\HtmlDocument;

//  pageFetcher;
class Fetcher {
	
	function __construct(FetcherConfig $config){
		$this->endpointSettings= $config;
		//print_r($config);
		
		$this->endpointSettings->cdnserver;
		$this->endpointSettings->endpoint;
		
		
		
		
	}
	
	function get (){
		//$url= 'http://'.$this->endpointSettings->endpoint;
		
		if( strpos($this->endpointSettings->template,"http")==0 ){
			// 	
			$url= $this->endpointSettings->template;
		}else{
			//
			$url= 'http://'.$this->endpointSettings->cdnserver.$this->endpointSettings->endpoint;
		}
		
		#echo $this->endpointSettings->template;
		#var_dump(strpos($this->endpointSettings->template,"http"));
		
		//var_dump($url);
		
		$html = "";
		if ($stream = fopen($url, 'r')) {
			$html =  stream_get_contents($stream);
			fclose($stream);
		}
		return $html;
	}
	
}