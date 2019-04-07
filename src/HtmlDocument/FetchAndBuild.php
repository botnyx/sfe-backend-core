<?php

namespace Botnyx\Sfe\Backend\HtmlDocument;


class FetchAndBuild{
	
	private $fetchConfig;
	private $buildConfig;
	
	private $originalHtml;
	private $originalHtmlObject;
	
	
	function __construct($fetchConfigArray=array()){
		
		$array=array(
			"clientid" => "0000-0000-0000-0000-000000",
			"endpoint" => "/",
			"template"=>"",
			"cdnserver"=>"freelance.bss.servenow.nl",
			"backendserver"=>"",
			"authserver"=>""
		);

		$this->fetchConfig = new FetcherConfig($array);
		
		$this->originalHtml = $this->fetchHtml($this->fetchConfig);
		
		
		
		$this->originalHtmlObject = $this->parseHtml($this->originalHtml);
		
		
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

		$this->buildConfig = new BuilderConfig( $buildconf );
			
		
		$this->html = $this->constructHtml($this->buildConfig, $this->originalHtmlObject);
		
		
	}
	
	function __toString(){
		return (string)$this->html;
	}
	
	function fetchHtml( $fetcherconfig){
		$html = new Fetcher( $fetcherconfig );
		return $html->get();
	}
	
	
	function parseHtml($html){
		$templateParser = new Parser ($html);
		
		return $templateParser;
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

		return $page;
	}
	
}