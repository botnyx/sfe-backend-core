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
		
		$this->parseHtml($html);
		
	}
	
	
	function fetchHtml( $fetcherconfig){
		$html = new Fetcher( $fetcherconfig );
		return $html->get();
	}
	
	
	function parseHtml($html){
		$templateParser = new templateParser ($html);
		
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
	
	function constructHtml(BuilderConfig $cfg){
		
		$page = new buildPage( $cfg );

		$page->setViewport( $templateParser->getViewport()  );
		$page->addHeadJs( $templateParser->getScripts() );
		$page->addCss( $templateParser->getCss() );
		$page->addJs( $templateParser->getBodyJs() );

		$page->addBody( $body );

		echo $page;
	}
	
}