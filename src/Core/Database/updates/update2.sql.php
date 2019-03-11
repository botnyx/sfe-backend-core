<?php


namespace Botnyx\Sfe\Backend\Core\Database\updates;


class update2 extends \Botnyx\Sfe\Backend\Core\Database\updateInterface implements  \Botnyx\Sfe\Backend\Core\Database\updateInterfaceIf {
	
	var $thisVersion = 2;
	var $previousVersion = 1;
	
	function start(){
		
		echo "Alter table dbversion.\n";
		var_dump($this->exec($this->dbversion()) );
		
		/* finally, update the version number. */
		$this->exec("INSERT INTO dbversion ( version ) VALUES ('".$this->thisVersion."');");
	}
	
	function dbversion(){
		return "ALTER TABLE dbversion ADD COLUMN ts TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER version;";
	}
	
}