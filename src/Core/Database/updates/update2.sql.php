<?php


namespace Botnyx\Sfe\Backend\Core\Database\updates;
//extends Botnyx\Sfe\Backend\Core\Database\updateInterface
class update2 extends \Botnyx\Sfe\Backend\Core\Database\updateInterface {
	
	var $thisVersion = "2";
	var $previousVersion = "1";
	
	function start(){
		print_r( $this->exec("SHOW TABLES;") );
	}
	
	
}