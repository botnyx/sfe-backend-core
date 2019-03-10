<?php

namespace Botnyx\Sfe\Backend\Core\Setup;


use Composer\Script\Event;
use Composer\Installer\PackageEvent;


class Backend {
    
	static function postUpdate(Event $event){
		//$vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');
		$vendorDir = realpath($event->getComposer()->getConfig()->get('vendor-dir'));
     	$projectDir = realpath($vendorDir . '/..');
		$vendorDir."/composer/installed.json";
		
		
		$installed = json_decode(\Botnyx\Sfe\Backend\Core\Setup\Backend::readfile($vendorDir."/composer/installed.json"));
		
		echo "\n";
		echo "Updating: `".$installed[0]->name."`\n";
		echo "version: `".$installed[0]->version."`\n";
		//$installed[0]->version_normalized;
		
		
		#var_dump("xyz");
		echo "-----------------------------------------\n";
		echo "Database setup/update should go here\n";
		
		print_r($projectDir );
		echo "\n";
	}
	
	static function postInstall(Event $event){
		
		//$vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');
		$vendorDir = realpath($event->getComposer()->getConfig()->get('vendor-dir'));
     	$projectDir = realpath($vendorDir . '/..');
		
		
		$vendorDir."/composer/installed.json";
		
		
		$installed = json_decode(\Botnyx\Sfe\Backend\Core\Setup\Backend::readfile($vendorDir."/composer/installed.json"));
		
		echo "\n";
		echo "Setting up: `".$installed[0]->name."`\n";
		echo "version: `".$installed[0]->version."`\n";
		//$installed[0]->version_normalized;
		
		
		#var_dump("xyz");
		echo "-----------------------------------------\n";
		echo "Database setup/update should go here\n";
		
		print_r($projectDir );
		echo "\n";
	}
	
	
	static function readfile ($filename){
		$handle = fopen($filename, "rb");
		$contents = fread($handle, filesize($filename));
		fclose($handle);
		return $contents;
	}
	
}
