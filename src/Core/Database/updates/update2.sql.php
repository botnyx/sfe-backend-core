<?php


namespace Botnyx\Sfe\Backend\Core\Database\updates;


class update2 extends \Botnyx\Sfe\Backend\Core\Database\updateInterface implements  \Botnyx\Sfe\Backend\Core\Database\updateInterfaceIf {
	
	var $thisVersion = 2;
	var $previousVersion = 1;
	
	function start(){
		print_r( $this->exec("SHOW TABLES;") );
	}
	
	
}