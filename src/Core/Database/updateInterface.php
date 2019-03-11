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
			echo "\n------------------------------------\nUpdating ".$this->thisVersion." to ".$this->previousVersion."\n";
		}
		
		
	}
	
	
	
	function exec($sql){	
		$stmt = $this->pdo->prepare($sql);
		return $stmt->execute(); 
	}
}
