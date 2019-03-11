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
			echo "creating table...";
			try{
				var_dump($this->dbexec($o));
			}catch(\Exception $e){
				echo "\n === FATAL ERROR ====\n";
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
	
	
	public function update($vendordir,$version){
		
		return $this->getUpdates($vendordir,$version);
		
	}
	
	public function getVersion(){
		$sql = "SELECT version FROM dbversion order by version DESC LIMIT 1";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute(); // just merge two arrays
		$sqlResult = $stmt->fetch();
		return $sqlResult;
	}
	
	private function getUpdates($vendordir,$currentversion){
		
		$path = $vendorDir."/botnyx/sfe-backend-core/src/Core/Database/updates/";
		$array= array();
		foreach (glob($path."*.sql.php") as $filename) {
			$version = (int)str_replace(".sql.php","",str_replace($path,"",$filename));
			if($version>$currentversion){
				$array[]= array("filename"=>$filename,"version"=>$version);
				echo $filename." size " . filesize($filename) . "\n";
			}
			
		}
		return $array;
	}
}
