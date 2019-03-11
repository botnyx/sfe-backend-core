<?php


namespace Botnyx\Sfe\Backend\Core\Database\updates;

use \Botnyx\Sfe\Backend\Core\Database;

class update1 extends updateInterface implements  updateInterfaceIf  {
	
	var $thisVersion = 1;
	var $previousVersion = 1;
	
	
	function start(){
		echo "DUMMY!\n";
	}
	
}