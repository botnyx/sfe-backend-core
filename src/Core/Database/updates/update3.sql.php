<?php


namespace Botnyx\Sfe\Backend\Core\Database\updates;


class update3 extends \Botnyx\Sfe\Backend\Core\Database\updateInterface implements  \Botnyx\Sfe\Backend\Core\Database\updateInterfaceIf {
	
	var $thisVersion = 3;
	var $previousVersion = 2;
	
	function start(){
		
		echo "Alter Endpoint table, add method.\n";
		var_dump($this->exec($this->endpointTableFix()) );
		
		/* finally, update the version number. */
		echo "Update dbversion number.\n";
		var_dump($this->updateVersionNumber());
		
	}
	
	
	function endpointTableFix(){
		return 'ALTER TABLE '.$this->dbname.'.frontend_endpoints ADD COLUMN method varchar(10) NULL DEFAULT "GET" AFTER `client_id`;';
	}
	
	
	
	
}