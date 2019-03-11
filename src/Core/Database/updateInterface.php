<?php
namespace Botnyx\Sfe\Backend\Core\Database;


interface updateInterfaceIf { 
	
	public function start();
}

abstract class updateInterface  { 
	
	var $thisVersion;
	var $previousVersion;
	
	
	function __construct($pdo){
		$this->pdo=$pdo;
	}
	
	
	
	function exec($sql){	
		$stmt = $this->pdo->prepare($sql);
		return $stmt->execute(); 
	}
}
