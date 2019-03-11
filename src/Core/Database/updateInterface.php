<?php
namespace Botnyx\Sfe\Backend\Core\Database;


class updateInterface { 
	
	var $thisVersion = "0.1.1";
	var $previousVersion = "0.1";
	
	
	function __construct($pdo){
		$this->pdo=$pdo;
	}
	function _checkVersion(){
		$this->previousVersion;
	}
	function _update(){
		$this->start();
	}
	
}
