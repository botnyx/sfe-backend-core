<?php

/**
*	Dependencies for the backend role.
*
*
*
*
**/
session_start();

/* Database initialization*/
if(isset($pdo)==false){

	$dboptions = array(
		PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::ATTR_EMULATE_PREPARES   => false,
	);

	/*  make db connection.  */
	try{
		//$pdo = new PDO($ini['database']['pdodsn']  );

		$pdo = new PDO(_SETTINGS['sfeBackend']['conn']['dsn'], _SETTINGS['sfeBackend']['conn']['dbuser'],_SETTINGS['sfeBackend']['conn']['dbpassword'],$dboptions );
		// set the default schema for the oauthserver.
		//$result = $pdo->exec('SET search_path TO oauth2'); # POSTGRESQL Schema support
	}catch(Exception $e){
		die($e->getMessage());

	}



}
