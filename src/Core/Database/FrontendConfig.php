<?php
namespace Botnyx\Sfe\Backend\Core\Database;
//namespace Botnyx\SfeBackend\Database;


class FrontendConfig {

	var $table='frontend_config';

	function __construct($pdo){
		$this->pdo=$pdo;
	}

	function getByClientId($clientId){

		$sql = "SELECT * FROM frontend_config WHERE client_id=:clientid";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute(array('clientid'=>$clientId)); // just merge two arrays
		$sqlResult = $stmt->fetch();

		return $sqlResult;
	}

	function getByHostName($hostname){

		$sql = "SELECT * FROM ".$this->table." WHERE hostname=:hostname";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute(array('hostname'=>$hostname)); // just merge two arrays
		$sqlResult = $stmt->fetch();

		return $sqlResult;
	}

	function getConfigByClientId($clientId){

		$sql = "SELECT * FROM frontend_config WHERE client_id=:clientid";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute(array('clientid'=>$clientId)); // just merge two arrays
		$sqlResult = $stmt->fetch();

		return $sqlResult;
	}

	function getStaticUrlsByClientId($clientId){

		$sql = "SELECT * FROM frontend_static_url WHERE client_id=:clientid";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute(array('clientid'=>$clientId)); // just merge two arrays
		$sqlResult = $stmt->fetchAll();

		return $sqlResult;
	}

	function getByMenuClientId($clientId){

		$sql = "SELECT * FROM frontend_menu WHERE clientId=:clientid";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute(array('clientid'=>$clientId)); // just merge two arrays
		$sqlResult = $stmt->fetchAll();

		return $sqlResult;
	}


	
	function getFrontendEndpoints($clientId){
		$sql = "SELECT * FROM frontend_endpoints WHERE client_id=:clientid";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute(array('clientid'=>$clientId)); // just merge two arrays
		$sqlResult = $stmt->fetchAll();
		return $sqlResult;
	}

}
