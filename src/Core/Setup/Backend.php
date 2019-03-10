<?php

namespace Botnyx\Sfe\Backend\Core\Setup;


use Composer\Script\Event;
use Composer\Installer\PackageEvent;


class Backend {
    
	
	static function postInstall(Event $event){
		
		//$vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');
		$vendorDir = realpath($event->getComposer()->getConfig()->get('vendor-dir'));
     	$projectDir = realpath($vendorDir . '/..');
		
		echo "line1";
		echo "line2";
		echo "line3\n";
		print_r($projectDir );
		
	}
}
