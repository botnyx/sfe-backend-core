<?php
namespace Botnyx\Sfe\Backend\Core\Database;


interface updateInterfaceIf { 
	
	public function start();
}

abstract class updateInterface  { 
	
	var $thisVersion = false;
	var $previousVersion = false;
	
	
	function __construct($pdo,$dbname){
		$this->pdo=$pdo;
		$this->dbname=$dbname;
		
		if($this->thisVersion ==false || $this->previousVersion == false){
			throw new \Exception("INVALID UPDATEFILE, Aborting...");
		}else{
			echo "\n------------------------------------\nUpdating database `".$dbname."` from version ".$this->previousVersion." to version ".$this->thisVersion."\n";
		}
		
		$this->start();
		
	}
	
	function updateVersionNumber(){
		$this->exec("INSERT INTO ".$this->dbname." ( version ) VALUES ('".$this->thisVersion."');");
	}
	
	function fetch($sql){	
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute();
		return  $stmt->fetchAll();
	}
	function exec($sql){
		echo $sql."\n";
		$stmt = $this->pdo->prepare($sql);
		return $stmt->execute(); 
	}
}
