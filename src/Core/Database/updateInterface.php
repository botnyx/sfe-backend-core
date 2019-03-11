<?php
namespace Botnyx\Sfe\Backend\Core\Database;


interface updateInterfaceIf { 
	
	public function start();
}

abstract class updateInterface  { 
	
	var $thisVersion = false;
	var $previousVersion = false;
	
	
	function __construct($pdo){
		$this->pdo=$pdo;
		if($this->thisVersion ==false || $this->previousVersion == false){
			throw new \Exception("INVALID UPDATEFILE, Aborting...");
		}else{
			echo "\n------------------------------------\nUpdating database from version ".$this->previousVersion." to version ".$this->thisVersion."\n";
		}
		
		
	}
	
	
	
	function exec($sql){	
		$stmt = $this->pdo->prepare($sql);
		return $stmt->execute(); 
	}
}
