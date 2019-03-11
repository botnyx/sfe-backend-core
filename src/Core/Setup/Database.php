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
		
		$installObject = new \Botnyx\Sfe\Backend\Core\Database\sql\SfeBackendCoreSql();
		
		/* execute the sql */
		foreach( $installObject->sql as $o){
			echo "\ncreating table...";
			try{
				var_dump($this->dbexec($o));
			}catch(\Exception $e){
				echo "\n === FATAL ERROR ====";
				throw new \Exception($e->getMessage());
			}
			
		}
		
		
	}
	
	private function dbexec($sql){	
		//$sql = "SELECT * FROM frontend_config WHERE client_id=:clientid";
		$stmt = $this->pdo->prepare($sql);
		return $stmt->execute(); // just merge two arrays
		//$sqlResult = $stmt->fetch();
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
