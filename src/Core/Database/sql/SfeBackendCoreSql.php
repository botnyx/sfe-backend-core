<?php


namespace Botnyx\Sfe\Backend\Core\Database\sql;

class SfeBackendCoreSql {
	
	function __construct(){
		
		$this->sql = array(
			$this->authproviders_identities(),
			$this->authproviders_providers(),
			$this->client_userlink(),
			$this->frontend_config(),
			$this->frontend_endpoints(),
			$this->frontend_menu(),
			$this->frontend_serviceworker(),
			$this->dbversion(),
			$this->setversion()
		);
		/*$this->authproviders_identities = $this->authproviders_identities();
		$this->authproviders_providers = $this->authproviders_providers();
		$this->client_userlink = $this->client_userlink();
		$this->frontend_config = $this->frontend_config();
		$this->frontend_endpoints = $this->frontend_endpoints();
		$this->frontend_menu = $this->frontend_menu();
		$this->frontend_serviceworker = $this->frontend_serviceworker();
		$this->dbversion = $this->dbversion();
		$this->setversion = $this->setversion();*/
	}
	
	public function authproviders_identities(){
		//DROP TABLE IF EXISTS `authproviders_identities`;
return "CREATE TABLE `authproviders_identities`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ext_authproviderid` int(11) NOT NULL,
  `token` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `refreshtoken` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `user_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";	
		
	}
	
	
	public function authproviders_providers (){
		//DROP TABLE IF EXISTS `authproviders_providers`;
return "CREATE TABLE `authproviders_providers`  (
  `providerId` int(11) NOT NULL AUTO_INCREMENT,
  `providerName` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `providerClientId` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `providerClientSecret` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `providerUrlKey` varchar(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `discoveryData` varchar(2000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`providerId`, `providerUrlKey`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";		
	}
	
	
	public function client_userlink (){
		// DROP TABLE IF EXISTS `client_userlink`;
		return "CREATE TABLE `client_userlink`  (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `client_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `internal_id` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
	}
	
	
	public function frontend_config(){
		// DROP TABLE IF EXISTS `frontend_config`;
return "CREATE TABLE `frontend_config`  (
  `client_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `template` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `allowedorigin` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `htmlstamp` bigint(18) NULL DEFAULT NULL,
  `languages` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `disabled` int(11) NULL DEFAULT NULL,
  `disabledreason` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `defaultpage` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `cdnhostname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `backendhostname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`client_id`) USING BTREE,
  INDEX `index_client_id`(`client_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";

	}
	
	
	public function frontend_endpoints(){
		//DROP TABLE IF EXISTS `frontend_endpoints`;
return "CREATE TABLE `frontend_endpoints`  (
  `id` bigint(255) NOT NULL AUTO_INCREMENT,
  `uri` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `fnc` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '\\Botnyx\\Sfe\\Frontend\\Endpoint:get',
  `tmpl` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `client_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;"; 
		
	}
	
		
	public function frontend_menu(){
		// DROP TABLE IF EXISTS `frontend_menu`;
return "CREATE TABLE `frontend_menu`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `link` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `text` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `icon` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `parent` int(255) NOT NULL,
  `menu` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `scopes` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `sortorder` int(255) NULL DEFAULT NULL,
  `linkattribute` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `clientId` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";		
	}
	
	
	public function frontend_serviceworker(){
		// DROP TABLE IF EXISTS `frontend_serviceworker`;

return "CREATE TABLE `frontend_serviceworker`  (
  `client_id` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `workbox` int(255) NULL DEFAULT 0,
  `workboxnav` int(255) NULL DEFAULT 0,
  PRIMARY KEY (`client_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";

"SET FOREIGN_KEY_CHECKS = 1;";		
		
		
	}
	
	
	public function dbversion(){
		// DROP TABLE IF EXISTS `dbversion`;
return "CREATE TABLE `dbversion`  (
  `version` bigint(6) UNSIGNED ZEROFILL NOT NULL,
  PRIMARY KEY (`version`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";

	}
	
	public function setversion(){
		return "INSERT INTO `dbversion` VALUES ('000001');";
	}
	
}


/*
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
*/

