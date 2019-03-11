<?php


namespace Botnyx\Sfe\Backend\Core\Database\updates;


class update1 extends \Botnyx\Sfe\Backend\Core\Database\updateInterface implements  \Botnyx\Sfe\Backend\Core\Database\updateInterfaceIf {
	
	var $thisVersion = 1;
	var $previousVersion = 1;
	
	
	function start(){
		echo "DUMMY!\n";
	}
	
}