<?php


namespace Botnyx\Sfe\Backend\Core\Database\updates;


class update4 extends \Botnyx\Sfe\Backend\Core\Database\updateInterface implements  \Botnyx\Sfe\Backend\Core\Database\updateInterfaceIf {
	
	var $thisVersion = 4;
	var $previousVersion = 3;
	
	function start(){
		
		echo "Alter config table, add hostname.\n";
		var_dump($this->exec($this->endpointTableFix()) );
		
		/* finally, update the version number. */
		echo "Update dbversion number.\n";
		var_dump($this->updateVersionNumber());
		
	}
	
	
	function endpointTableFix(){
		//return 'ALTER TABLE '.$this->dbname.'.frontend_endpoints ADD COLUMN method varchar(10) NULL DEFAULT "GET" AFTER `client_id`;';
		return'ALTER TABLE '.$this->dbname.'.frontend_config 
ADD COLUMN `hostname` varchar(180) NULL AFTER `backendhostname`;';
	}
	
	
	
	
}
