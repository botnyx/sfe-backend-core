<?php
namespace Botnyx\Sfe\Backend\Core\Database;


interface updateInterfaceIf { 
	
	var $thisVersion;
	var $previousVersion;
	
	function start();
}

class updateInterface  implements updateInterfaceIf { 
	
	var $thisVersion;
	var $previousVersion;
	
	
	function __construct($pdo){
		$this->pdo=$pdo;
	}
	
	function start(){
		
	}
	
	function exec($sql){	
		//$sql = "SELECT * FROM frontend_config WHERE client_id=:clientid";
		$stmt = $this->pdo->prepare($sql);
		return $stmt->execute(); // just merge two arrays
		//$sqlResult = $stmt->fetch();
	}
}
