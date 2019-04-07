<?php

namespace Botnyx\Sfe\Backend\HtmlDocument;


class FetchAndBuild{
	
	function __construct(){
		
		$array=array(
			"clientid" => "0000-0000-0000-0000-000000",
			"endpoint" => "/",
			"template"=>"",
			"cdnserver"=>"freelance.bss.servenow.nl",
			"backendserver"=>"",
			"authserver"=>""
		);

		$fetchConfig = new FetcherConfig($array);
		
		$html = $this->fetchHtml($fetchConfig);
		
		
		$parsedObject = $this->parseHtml($html);
		
		
		$buildconf = array( 
			"baseDomain"=>"localhost", 
			"cache"=>false, 
			"visibility"=>"", 
			"type"=>"", 
			"language"=>"",
			"title"=>"", 
			"description"=>"",
			"keywords"=>"",
			"image"=>""
		);

		$cfg = new BuilderConfig( $buildconf );
			
		$this->constructHtml($cfg, $parsedObject);
		
		
	}
	
	
	function fetchHtml( $fetcherconfig){
		$html = new Fetcher( $fetcherconfig );
		return $html->get();
	}
	
	
	function parseHtml($html){
		$templateParser = new Parser ($html);
		
		return $templateParser;
		
		$viewport 	= $templateParser->getViewport();
		$css 		= $templateParser->getCss();
		$js 		= $templateParser->getScripts();
		$body 		= $templateParser->getBody();
		$bodyjs 	= $templateParser->getBodyJs();
		
		$components = $templateParser->getComponents();
	}
	
	
	
	
	
	function BuildConfig( $configArray ){
		return new BuilderConfig( $configArray );
	}
	
	function constructHtml(BuilderConfig $cfg, Parser $templateParser){
		
		$viewport 	= $templateParser->getViewport();
		$css 		= $templateParser->getCss();
		$js 		= $templateParser->getScripts();
		$body 		= $templateParser->getBody();
		$bodyjs 	= $templateParser->getBodyJs();
		
		$components = $templateParser->getComponents();
		
		
		
		$page = new Builder( $cfg );

		$page->setViewport( $templateParser->getViewport()  );
		$page->addHeadJs( $templateParser->getScripts() );
		$page->addCss( $templateParser->getCss() );
		$page->addJs( $templateParser->getBodyJs() );

		$page->addBody( $body );

		echo $page;
	}
	
}