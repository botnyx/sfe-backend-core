<?php

namespace Botnyx\Sfe\Backend\HtmlDocument;


class Parser {
	
	
	var $pageTitle;
	var $pageDescription;
	var $pageKeywords;
	var $pageViewport;
	var $pageCharset;
	
	
	var $headCss=array();
	var $headJs=array();
	var $bodyJs=array();
	
	
	var $bodyScripts;
	var $pageBodyLines;	
	
	var $bodyElements;
	var $sfeComponents;
	
	protected $xpath;
	protected $doc;
	
		
	function __construct($html){
		
		$this->doc = $doc = new \DOMDocument();
		$this->doc->encoding='utf-8';
		$this->doc->loadHTML($html, LIBXML_NOWARNING | LIBXML_NOERROR);
		$this->xpath = new \DOMXpath($this->doc);
		
		$this->parseHEAD();
		$this->parseBODYforComponents();
		$this->parseBODYforScripts();
		$this->getCleanBODY();
		
	}
	
	public function getBody(){
		return $this->bodyElements;
		
		$out = "";
		foreach($this->pageBodyLines as $l){
			$out .= $l.PHP_EOL;
		}
		return $out;
	}
	public function getCss(){
		return $this->headCss;
	}
	public function getScripts(){
		return $this->headJs;
	}
	public function getComponents(){
		$out = array();
		foreach($this->sfeComponents as $c){
			$out[]=$c['component'];
		}
		return $out;
	}
	public function getBodyJs(){
		return $this->bodyJs;
	}
	public function getViewport(){
		return $this->pageViewport;
	}
	
	
	private function parseTitle($element){
		$this->pageTitle = $element->nodeValue;
	}
	private function parseViewport($element){
		$this->pageViewport = $element->nodeValue;
		//var_dump($this->pageViewport);
	}
	private function parseLink($element){
		if ( $element->hasAttributes() ){
			$attr = $this->attributesToArray($element->attributes);
			if( 	  array_key_exists('rel',$attr) &&  $attr['rel']=='stylesheet' ){
				//print_r($attr);
				$tmp = $this->headCss;
				$tmp[] = $attr['href'];
				$this->headCss = $tmp;				
			}
			
		}
	}
	private function parseScript($element){
		if ( $element->hasAttributes() ){
			$attr = $this->attributesToArray($element->attributes);
			if( 	  array_key_exists('src',$attr) ){
				//print_r($attr);
				$tmp = $this->headJs;
				$tmp[] = $attr['src'];
				$this->headJs = $tmp;				
			}
			
		}
	}
	private function attributesToArray($attributes){
		$array=array();
		foreach($attributes as $attr){
			$array[$attr->nodeName]=$attr->nodeValue;
		}
		return $array;
	}
	private function parseMeta($element){
		
		if ( $element->hasAttributes() ){
			
			$attr = $this->attributesToArray($element->attributes);
			//print_r($attr);
			
			
			//$attr[];
			
			if( 	  array_key_exists('name',$attr) &&  $attr['name']=='viewport' ){
				#print_r($attr['name']);
				$this->pageViewport = $attr['content'];
				
			} elseif( array_key_exists('charset',$attr) ){
				$this->pageCharset = $attr['charset'];
				
			}elseif( array_key_exists('name',$attr) &&  $attr['name']=='cid'){
				//$this->pageCharset = $attr['charset'];
				
			}elseif( array_key_exists('name',$attr) &&  $attr['name']=='description'){
				//$this->pageCharset = $attr['charset'];
				$this->pageDescription = $attr['content'];
			}
			elseif( array_key_exists('name',$attr) &&  $attr['name']=='keywords'){
				//$this->pageCharset = $attr['charset'];
				$this->pageKeywords = $attr['content'];
			}
			else{
				
				//print_r($attr);
				//die();
				
			}
			
			/*elseif( array_key_exists('rel',$attr) && $attr['rel']=='stylesheet'){
				$arr = $this->headCss;
				$arr[] = $attr['href'];
				$this->headCss = $arr;
				die();
				
			} elseif( array_key_exists('keywords',$attr) ){
				$this->pageKeywords = $attr['nodeValue'];
				
			} elseif( array_key_exists('description',$attr) ){
				$this->description = $attr['description'];
				
			} 
			*/
			//die();
			
			//;
			//;
			//$attr->nodeName=='keywords';
			//$attr->nodeName=='description';
			
			foreach ($element->attributes as $attr) {
				$name = $attr->nodeName;
				$value = $attr->nodeValue;
				//echo "Attribute :'$name' :: '$value'<br />";
				
			}
		}
	}
	private function parseHEADnode($element){
		switch ($element->nodeName) {
			case "meta":
				//echo "meta<br>";
				$this->parseMeta($element);
				break;
			case "link":
				$this->parseLink($element);
				//echo "link<br>";
				break;
			case "title":
				$this->parseTitle($element);
				break;
			case "script":
				$this->parseScript($element);
				//echo "script";
				break;
			
		}
	}
	private function parseHEAD(){
		//echo "parsehead<hr>";
		$elements = $this->xpath->query("//head");
		//var_dump($elements);
		if (!is_null($elements)) {
			foreach ($elements as $headelement) {
				//echo "<br>".strtoupper($headelement->nodeName)."<br>";
				
				foreach($headelement->childNodes as $element){
					//$html = $element->saveHTML(); 
					//var_dump($element);
					if($element->nodeName!="#text"){
						$this->parseHEADnode($element);
					
						//echo "<br/>[". $element->nodeName. "] <br>"; //$element->getAttribute('name')." ".$element->getAttribute('content');

						if ( $element->hasAttributes() ){
							foreach ($element->attributes as $attr) {
								$name = $attr->nodeName;
								$value = $attr->nodeValue;
						//		echo "Attribute '$name' :: '$value'<br />";
							}
						}
						//print(htmlentities( $this->elementToHtml($element) ) );
					}	
				}
			}	

		}
		
	}
	private function parseBODY(){
		$this->parseBODYforComponents();
		$this->parseBODYforScripts();
	}
	private function elementToHtml($element){
		return $element->ownerDocument->saveHTML($element);
	}
	private function parseBODYforComponents(){
		//$elements = $this->xpath->query("//div[@data-sfcomp]");
		$elements = $this->xpath->query("//*[@data-sfcomp]");
		$components=array();
		
		if (!is_null($elements)) {
				
			foreach ($elements as $element) {
				//error_log(">>".$element->nodeName);
				//echo "<br/>[". $element->nodeName. " ".$element->getAttribute('data-sfcomp')."]";
				if ( $element->hasAttributes() ){
					 foreach ($element->attributes as $attr) {
						$name = $attr->nodeName;
						$value = $attr->nodeValue;
						if($name=='data-sfcomp'){
							//$value;
							$components[] = array("component"=>$value,"element"=>$element);
						}
						//echo "Attribute '$name' :: '$value'<br />";
					  }
				} 
				//$nodes = $element->childNodes;
				//foreach ($nodes as $node) {
				  //echo $node->nodeValue. "\n";
				//}

			}
		}
		//error_log("Components: ".count($components) );
		$this->sfeComponents = $components;
		return $components;
	}
	private function parseBODYforScripts(){
		$elements = $this->xpath->query("//body/script");
		//var_dump($elements);
		$this->bodyJs = array(
			"https://cdn.servenow.nl/assets/js/js-cookie/js-cookie/cookie.js",
			"https://cdn.servenow.nl/assets/js/botnyx/sfe/sfe.js" );
		$bodyscripts=array();
		
		if (!is_null($elements)) {
			foreach ($elements as $element) {
				//error_log($element->nodeName);
				//echo "<br/>[". $element->nodeName. "]  <br>"; //$element->getAttribute('name')." ".$element->getAttribute('content');
				if ( $element->hasAttributes() ){
			  		foreach ($element->attributes as $attr) {
						$name = $attr->nodeName;
						$value = $attr->nodeValue;
						//echo "Attribute '$name' :: '$value'<br />";
					}
				}
				$bodyscripts[]=$element;
				
				if ( $element->hasAttributes() ){
					$attr = $this->attributesToArray($element->attributes);
					if( 	  array_key_exists('src',$attr) ){
						//print_r($attr);
						$tmp = $this->bodyJs;
						$tmp[] = $attr['src'];
						$this->bodyJs = $tmp;				
					}

				}
				//print( htmlentities( $this->elementToHtml($element) ) );
				//$element->parentNode->removeChild($element); 
				
			}
			
		}
		
		
		//print_r($elements);
		#foreach( $elements as $s ){
		#	$elements->removeChild($s);
		#}
		
		
		$this->bodyScripts = $bodyscripts;
		return $bodyscripts;		
	}
	private function getCleanBODY(){
		$elements = $this->xpath->query("//body");
		$bodylines=array();
		if (!is_null($elements)) {
			foreach ($elements as $element) {
				
				//echo "<br/>[". $element->nodeName. "]  <br>"; //$element->getAttribute('name')." ".$element->getAttribute('content');
				if ( $element->hasAttributes() ){
			  		foreach ($element->attributes as $attr) {
						$name = $attr->nodeName;
						$value = $attr->nodeValue;
						//echo "Attribute '$name' :: '$value'<br />";
					}
				}

				
				foreach($element->childNodes as $bodyelement){
					//echo $bodyelement->nodeName."<br>";
					if( $bodyelement->nodeName!='#text'  ){
						//var_dump(($bodyelement->nodeName!='script'));
						//var_dump(($bodyelement->nodeName!='#text'));
						//var_dump($bodyelement);
						//die();
						if( $bodyelement->nodeName!='script' ){
							$bodyElements[]=$bodyelement;
							$bodylines[]=$this->elementToHtml($bodyelement);
						}
					}
				}
				
			}
		}
		$this->pageBodyLines = $bodylines;
		$this->bodyElements = $bodyElements;
	}
	
}
