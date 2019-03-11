<?php


namespace Botnyx\Sfe\Backend\Core\Database\updates;

use \Botnyx\Sfe\Backend\Core\Database;

class update2 extends updateInterface implements  updateInterfaceIf {
	
	var $thisVersion = 2;
	var $previousVersion = 1;
	
	function start(){
		print_r( $this->exec("SHOW TABLES;") );
	}
	
	
}