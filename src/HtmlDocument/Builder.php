<?php

namespace Botnyx\Sfe\Backend\HtmlDocument;


class Builder{
	
	var $viewport = "width=device-width, initial-scale=1.0, shrink-to-fit=no";
	var $clientid = "demo";
	
	var $headJs 	= array();
	var $Js 		= array();
	var $Css 		= array();
	
	var $htmltidy = false;
	private $pageConfig;
	
	
	function __construct( BuilderConfig $Config){
		$this->pageConfig = $Config;
	}
	function setViewport($v){
		$this->viewport = $v;
	}
	
	function addCss( $css ){
		$this->Css= $css;
	}
	function addJs( $js ){
		$this->Js= $js;
	}
	function addHeadJs( $js ){
		$this->headJs= $js;
	}
	function addBody($body){
		$this->BODY = $body;
		
	}
	
	function __toString(){
		
		$doc = new \DOMDocument;
		$html = $doc->appendChild( $doc->createElement('html'));
		$head = $html->appendChild($doc->createElement('head'));
		$body = $html->appendChild($doc->createElement('body'));
		
		/*
			META SECTION
		*/
		$meta[]=array('charset' => 'utf-8');
		$meta[]=array('name'=>'viewport','content'=> $this->viewport  );
		$meta[]=array('name'=>'CID', 'content' => $this->pageConfig->client_id);
		
			
		
		
#		$meta[]=array('title' => $this->pageConfig->title);
#		$meta[]=array('description' => $this->pageConfig->description);
	#	$meta[]=array('keywords' 	=> $this->pageConfig->keywords);
		$meta[]=array('generator' 	=> "SFE-FRAMEWORK");
		$meta[]=array('name'		=>'generated', 'content' => date(DATE_RFC2822));
			
			
			
	#	$meta[]=array('name' => 'language',	 			'content' => 'English');
			
		/*
			Cache control.
		*/
		if( $this->pageConfig->cache==true ){
			if( $this->pageConfig->visibility=='private' ){
				$meta[]=array('http-equiv' => 'Cache-control',	'content' => 'private');/* public/no-cache/no-store */
			}else{
				$meta[]=array('http-equiv' => 'Cache-control',	'content' => 'public');/* public/no-cache/no-store */
			}
		}else{
			$meta[]=array('http-equiv' => 'Cache-control',	'content' => 'no-cache');/* public/no-cache/no-store */
		}
		
		
			
		/*	
			
		$meta[]=array('name' => 'dc.date',	 			'content' => 'Foo Bar');
		$meta[]=array('name' => 'dc.creator', 			'content' => 'Foo Bar');
		$meta[]=array('name' => 'dc.description',		'content' => 'Foo Bar');
		$meta[]=array('name' => 'dc.date',	 			'content' => 'Foo Bar');
		$meta[]=array('name' => 'dc.source',			'content' => 'Foo Bar');
		$meta[]=array('name' => 'dc.language',			'content' => 'Foo Bar');
			
			
			
			
		$meta[]=array('name' => 'og.title', 			'content' => 'Foo Bar');
		$meta[]=array('name' => 'og.image', 			'content' => 'Foo Bar');
		$meta[]=array('name' => 'og.type', 				'content' => 'website'); // article
		$meta[]=array('name' => 'og.description', 		'content' => 'Foo Bar');
		$meta[]=array('name' => 'og.url', 				'content' => 'Foo Bar');
			
			
		$meta[]=array('name' => 'twitter:card', 		'content' => 'summary');// summary_large_image
		$meta[]=array('name' => 'twitter:title', 		'content' => 'title');
		$meta[]=array('name' => 'twitter:description',	'content' => 'description');
		$meta[]=array('name' => 'twitter:image', 		'content' => 'image.png');
		*/	
		
		foreach ($meta as $attributes) {
			$node = $head->appendChild($doc->createElement('meta'));
			foreach ($attributes as $key => $value) {
				$node->setAttribute($key, $value);
			}
		}
		
		
		/*
			TITLE SECTION
		*/
		$node = $head->appendChild($doc->createElement('title'));
		$node->nodeValue = "Titel!";
		/*
			LINK SECTION
		*/
		
			
		$link=array();	
		foreach($this->Css as $cSS){
			$link[]=array('rel' => 'stylesheet','src'=>$cSS);	
		}
		
			
		#$link[]=array('rel'=>'icon', 	'type'=>'image/png', 'sizes'=>'256x256','href'=>'/assets/img/profile.png' );
		#$link[]=array('rel'=>'manifest','href'=>'/manifest.json');
			
		foreach ($link as $attributes) {
			$node = $head->appendChild($doc->createElement('link'));
			foreach ($attributes as $key => $value) {
				$node->setAttribute($key, $value);
			}
		}
		
		/*
			HEAD SCRIPT SECTION
		*/
		
		foreach ($this->headJs as $value) {
			#$node = $head->appendChild($doc->createElement('script'));
			#$node->setAttribute('src', $value);
			
		}
		
		
		
		//$node = $doc->createElement('link');
		//$node->setAttibute('key','value');
		//$node = $head->appendChild();
		
		
		/* BODY SECTION */
		foreach( $this->BODY as $element){
			$newelement = $doc->importNode($element, true);
			$body->appendChild($newelement);
		}
		
		
		
		/*
			BODY SCRIPTS SECTION
		*/
		//print_r($this->Js);
		foreach ($this->Js as $value) {
			$node = $body->appendChild($doc->createElement('script'));
			$node->setAttribute('src', $value);
		}
		
		
		$doc->formatOutput = true;
		
		//$doc->saveHTML();	
		
		$config = array(
           'indent'         => 2,
           'output-xhtml'   => false,
           'wrap'           => 200);
		
		if($this->htmltidy==true){
			$tidy = new tidy();
			$tidy->parseString($doc->saveHTML(), $config, 'utf8');
			//$tidy->cleanRepair();

			// Output
			return (string)$tidy;	
		}else{
			return $doc->saveHTML();
		}
		
	}
}

