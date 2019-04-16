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
		
		//var_dump(strpos($this->endpointSettings->template,"http"));
		
		if( strpos($this->endpointSettings->template,"http")!==false ){
			// 	
			$url= $this->endpointSettings->template;
		}else{
			//
			$url= 'http://'.$this->endpointSettings->cdnserver."/templates/".$this->endpointSettings->template;
		}
		//echo $url;
		//echo "<b>template: ".$this->endpointSettings->template."</b><br>";
		//echo "<b>endpoint: ".$this->endpointSettings->endpoint."</b><br>";
		//echo "<b>templat: ".$this->endpointSettings->template."</b><br>";
		//die();
		//echo $this->endpointSettings->template;
		#var_dump(strpos($this->endpointSettings->template,"http"));
		
		//var_dump($url);
		
		$html = "";
		if ($stream = fopen($url, 'r')) {
			//stream_filter_append($stream, 'convert.iconv.UTF-8/UTF-8');
			$html =  stream_get_contents($stream);
			fclose($stream);
		}
		
		//$doc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
		//return mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
		return $html;
	}
	
}