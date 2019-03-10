<?php

namespace Botnyx\Sfe\Backend\Core\Setup;


use Composer\Script\Event;
use Composer\Installer\PackageEvent;


class Backend {
    
	function __construct( $vendorDir){
		$this->vendorDir = $vendorDir;
		$this->projectDir = realpath($vendorDir . '/..');
		
		$this->installedComponents = json_decode(\Botnyx\Sfe\Backend\Core\Setup\Backend::readfile($vendorDir."/composer/installed.json"));
		
	}
	
	
	static function postUpdate(Event $event){
		
		$vendorDir = realpath($event->getComposer()->getConfig()->get('vendor-dir'));
				
		$installer = new \Botnyx\Sfe\Backend\Core\Setup\Backend($vendorDir);
		$installer->update();
		
	}
	
	static function postInstall(Event $event){
		
		$vendorDir = realpath($event->getComposer()->getConfig()->get('vendor-dir'));
		
		
		$installer = new \Botnyx\Sfe\Backend\Core\Setup\Backend($vendorDir);
		$installer->setup();
		
		
	}
	
	static function readfile ($filename){
		$handle = fopen($filename, "rb");
		$contents = fread($handle, filesize($filename));
		fclose($handle);
		return $contents;
	}
	
	
	
	public function update (){
		echo "\n";
		echo "Updating: `".$this->installedComponents[0]->name."`\n";
		echo "version: `".$this->installedComponents[0]->version."`\n";
		//$installed[0]->version_normalized;
		
		#var_dump("xyz");
		echo "-----------------------------------------\n";
		echo "\n\nUNFINISHED!!\n\n";
		
	}
	public function setup (){
		echo "\n";
		echo "Setting up: `".$this->installedComponents[0]->name."`\n";
		echo "version: `".$this->installedComponents[0]->version."`\n";
		//$installed[0]->version_normalized;
		
		
		
		
		#var_dump("xyz");
		echo "-----------------------------------------\n";
		
		$searchDir = realpath($this->vendorDir . '/../..');
		if( file_exists($searchDir."/configuration.ini") ){
			echo "found a previous `configuration.ini` ( ".$searchDir."/configuration.ini"." ) \n";	
			
			$app = $this->readConfiguration($searchDir."/configuration.ini" );
			$coreversion = $this->getComponentVersion($this->installedComponents);
			
			print_r($app->settings);
			print_r($app->paths);
			print_r($coreversion);
			
			echo "need to add config-parse code here..\n";
			
			echo "\n\nUNFINISHED!!\n\n";
			
		}else{
			echo "\n No configuration found, starting setup.\n";
			$public_html = $this->public_html();
			$tempFolder = $this->tempFolder();
			$dbCredentials = $this->dbCredentials();
			echo "\n\nUNFINISHED!!\n\n";
		}
		echo "\n";
	}
	
	
	private function getComponentVersion($installedComponents){
		foreach($installedComponents as $c){
			if($c->name=="botnyx/sfe-backend-core"){
				return $c;
			}
		}
		
	}
	
	
	private function readConfiguration($configfile){
		$app = new \Botnyx\Sfe\Shared\Application(parse_ini_file($configfile, true));
		return $app;
	}
	
	private function dbCredentials(){
		echo "---------------------\n";
		echo "Configuration step 3.\n";
		echo "Please enter the connection dsn  (mysql:host=localhost;dbname=backend)\n";
		$dsn = rtrim(fgets(STDIN));
		
		echo "Please enter the database username \n";
		$user = rtrim(fgets(STDIN));
		echo "Please enter the database password \n";
		$pass = rtrim(fgets(STDIN));
		
		
		return array("dsn"=>$dsn,"user"=>$user,"pass"=>$pass);
	}
	
	private function public_html(){
		echo "---------------------\n";
		echo "Configuration step 1.\n";
		echo "Please enter the location of your web-facing directory \n";
		$input = rtrim(fgets(STDIN));
		
		if( !file_exists( $input )){
			echo "Error: this location doesnt exist. try again.\n\n\n";
			$this->public_html();
		}
		return $input;
	}
	
	private function tempFolder(){
		echo "---------------------\n";
		echo "Configuration step 2.\n";
		echo "Please enter the location of your /tmp directory \n";
		$input = rtrim(fgets(STDIN));
		
		if( !file_exists( $input )){
			echo "Error: this location doesnt exist. try again.\n\n\n";
			$this->tempFolder();
		}
		return $input;
	}
	
	
}
