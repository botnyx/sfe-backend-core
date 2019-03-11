<?php
namespace Botnyx\Sfe\Backend\Core\Database;


class updateInterface { 
	
	var $thisVersion;
	var $previousVersion;
	
	
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
