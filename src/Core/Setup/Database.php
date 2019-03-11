<?php

namespace Botnyx\Sfe\Backend\Core\Setup;


/*

	botnyx/sfe-backend-core/src/Core/Database/sql/setup.sql  
	botnyx/sfe-backend-core/src/Core/Database/updates/*.sql

*/
class Database {
	
	var $pdo;
	
	function __construct($pdo){
		$this->pdo= $pdo;
	}
	/*
		Get the main installation sql 
	*/
	public function create(){
		echo "\nBotnyx\Sfe\Backend\Core\Setup\create()\n";
		
		//"botnyx/sfe-backend-core/src/Core/Database/sql/setup.sql.php";
		$installObject = new \Botnyx\Sfe\Backend\Core\Database\sql\SfeBackendCoreSql();
		print_r($installObject);
		
		foreach( get_object_methods ( $installObject ) as $o){
			print_r($o);	
		}
		
		
	}
	
	public function update(){
		
	}
	
	private function getUpdates(){
		
		foreach (glob("botnyx/sfe-backend-core/src/Core/Database/updates/*.sql.php") as $filename) {
			echo "$filename size " . filesize($filename) . "\n";
		}
		return $array;
	}
}
