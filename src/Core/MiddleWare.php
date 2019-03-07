<?php

namespace Botnyx\Sfe\Backend\Core;

use \Firebase\JWT\JWT;


class MiddleWare {

  var $client_id=false;
	var $hostname=false;
	var $auth_token=false;
	var $clientIssuer = "https://auth.devpoc.nl";
	var $jwtData = false;
	var $accessControlHeaders = array('Content-Type','Access-Control-Allow-Headers','Authorization','X-Requested-With','X-State');


	/*
		The middleware start
	*/
	function __construct($request, $response,$pdo,$clientIssuer){
		$this->pdo = $pdo;
		$this->clientIssuer=$clientIssuer;

		$this->Request	= $request;
		$this->Response	= $response;
		//$this->Next		= $next;

		//if($this->isSpecialUrl( $request )){
			// special url, has clientID.
			//error_log("[".$this->Request->getMethod()."] middleware: special url, has client_id:".$this->client_id);
		//}

		$this->detectReferrer($request);

		if($this->hasValidAuthHeader($request)){
			// is requested with token.
			error_log("[".$this->Request->getMethod()."]middleware: has auth token!");
		}

	}

	private function detectReferrer( $request ){
		error_log("detectReferrer");
		$referrer = $request->getHeader('HTTP_REFERER')[0];


		//var_dump($request->getHeaders());
		if( !filter_var($referrer, FILTER_VALIDATE_URL) ){
			// no valid referrer?!!!
			//var_dump($referrer);
			error_log($request->getAttribute('clientid'));
			error_log("[".$this->Request->getMethod()."]middleware: no valid referer?! (".$request->getUri().") ");
			$this->hostname = false;
			return;
		}
		$result = parse_url($referrer);

		//
		$this->hostname = str_replace('www.','',$result['host']);
		error_log($this->hostname);
		return;
		//$db = new Database\frontend_config($this->pdo);


		//$sqlResult = $db->getByHostName($result['host']);

		//if($sqlResult==false){
		//	error_log('NO CONFIG RESULTS!!');
		//}

		//return $sqlResult;


		#foreach ($headers as $name => $values) {
		#	echo $name . ": " . implode(", ", $values);
		#}
		//print_r($request->getHeader("HTTP_REFERER"));
		//var_dump($request->getReferrer());
		//die();
		#$baseUrl = $uri->getBaseUrl();
		#die($baseUrl);

		//$uriparts=explode("/",$uri->getPath());
		//if( $uriparts[1]==='api' && $uriparts[2]==='sfe'){
		//	$this->client_id = $uriparts[3];
			//error_log("[".$this->Request->getMethod()."] middleware: clientId =".$this->client_id);
		//	return true;
		//}
		//return false;
	}


	/*
		The middleware request
	*/
	function addRequestAttributes($request){

		/* get the Client Config */
		//if($this->client_id!=false){
		if($this->hostname!=false){

			$attributes = $this->getClientConfig();

		}else{
			$attributes = array();
		}



		/* get the language */
		if(isset($request->getHeader('HTTP_CONTENT_LANGUAGE')[0]) ){
			$attributes['requestedLanguage'] = $request->getHeader('HTTP_CONTENT_LANGUAGE')[0];
		}else{
			$attributes['requestedLanguage'] = explode( ',', $attributes['languages'] ,1);
		}


		if($this->auth_token!=false){
			//

		}
		//$attributes = array_merge($attributes,(array)$this->jwtData);
		//$attributes['tokendata']=$this->jwtData;
		//$attributes['authtoken']=$this->auth_token ;
		//print_r($attributes);
		//die();
		if($this->jwtData==false){
			$anontokenData['iss']='';
			$anontokenData['sub']='visitor';
			$anontokenData['aud']=$this->client_id;
			$anontokenData['scope']='';

			return $request->withAttribute('frontend-config',$attributes)->withAttribute('token-data',(object)$anontokenData);
		}
		return $request->withAttribute('frontend-config',$attributes)->withAttribute('token-data',$this->jwtData->tokenData);
	}

	/*
		The middleware response
	*/
	function addResponseHeaders($response){
		//return $response->write("HALLO?")
		$response = $response->withHeader( 'Access-Control-Allow-Headers', 'Content-Type,Access-Control-Allow-Headers,Authorization,X-Requested-With,X-State'  );
		$response = $response->withHeader('Access-Control-Allow-Origin','*');
		$response = $response->withHeader('Vary','Origin');
		return $response;
		//return $this->Request->withAttribute('frontend-config',$attributes);
	}



	private function getClientConfig(){
		//var_dump($clientId);



		$db = new Database\frontend_config($this->pdo);

		//$clientId="95ccf98a-demo-4438-b7a7-f244613b61a1";
		//$clientId= "709b6bb0-a1fa-47da-a24b-0de26c7cd22c";
		//$sql = "SELECT * FROM frontend_config WHERE client_id=:clientid";
		//$stmt = $this->pdo->prepare($sql);
		//$stmt->execute(array('clientid'=>$clientId)); // just merge two arrays

		//$sqlResult = $db->getByClientId($clientId); // $stmt->fetch();


		//die( $this->hostname) ;


		error_log($this->hostname);

		$sqlResult = $db->getByHostName($this->hostname);
		//$sqlResult=false;

		if($sqlResult==false){

			echo "/* Hostname issue.<br>
					src\\botnyx\\sfeBackend\\middleware.php<br>";

			echo "this->hostname=".$this->hostname." */";
			error_log('NO CONFIG RESULTS!!');
			//die('<hr>NO CONFIG RESULTS!!');
		}


		//error_log($this->hostname);
		//var_dump($sqlResult);
		//var_dump( $this->hostname );
		//error_log("xx");
		//die("x");

		//die();
		/*
		if($sqlResult==false){
			$out['disabled']=false;
			$out['disabledreason']='notpayed';
			$out['template']='blackrockdigital/startbootstrap-sb-admin-2-master';
			$out['allowedorigin']='https://www.servenow.nl';
			$out['languages']		='en-UK';

		}else{
			$out['disabled']		=$sqlResult['disabled'];
			$out['disabledreason']	=$sqlResult['disabledreason'];
			$out['template']		=$sqlResult['template'];
			$out['allowedorigin']	=$sqlResult['allowedorigin'];
			$out['languages']		=$sqlResult['languages'];
		}*/

		// or false if not exist.

		return $sqlResult;
	}

	private function hasValidAuthHeader($request){



		$arr = $request->getHeaders();
		//error_log(json_encode($arr['HTTP_AUTHORIZATION'][0]!="")) ;

		$rq = $request->getQueryParams();
		if(isset($rq['x-token'])){
			$this->auth_token="Bearer ".$rq['x-token'];
			$this->jwtData = new \Botnyx\Sfe\jwtToken\decodeJwt($rq['x-token'],$this->clientIssuer);
			return true;
		}



		if($arr['HTTP_AUTHORIZATION'][0]=="visitor" && strtoupper($request->getMethod())!='OPTIONS' ){
			error_log("Botnyx\SfeBackend\sfeBackendMiddleWare::hasValidAuthHeader  missing 'iss'");
			$a['iss']= '';
			$a['aud']= $this->client_id;
			$a['sub']= 'visitor';
			$this->jwtData= (object)$a;
			$this->auth_token=false;
			return false;
		}

		if($arr['HTTP_AUTHORIZATION'][0]!="visitor" && $arr['HTTP_AUTHORIZATION'][0]!="" && array_key_exists('HTTP_AUTHORIZATION',$arr) && strtoupper($request->getMethod())!='OPTIONS' ){
			$this->auth_token = $request->getHeader('HTTP_AUTHORIZATION')[0];
			$this->state = $request->getHeader('X-State');

			//print_r($this->auth_token);

			try{
				$tkn = explode(' ',$this->auth_token)[1];
				error_log("before ".$tkn);
				$this->jwtData = new \Botnyx\Sfe\jwtToken\decodeJwt($tkn,$this->clientIssuer);

				error_log("after");

			}catch(\Exception $e){
				error_log("Exception". $e->getMessage());
				error_log($e->getMessage());
				$this->auth_token=false;
				return false;
			}
			return true;

		}


		$this->auth_token=false;
		return false;

		//error_log($request->getHeader('HTTP_AUTHORIZATION'));

		//error_log(json_encode($_SERVER['HTTP_AUTHORIZATION'] ) );
		//$this->auth_token;

	}



	private function isSpecialUrl( $request ){
		$uri = $request->getUri();
		$headers = $request->getHeaders();
		#foreach ($headers as $name => $values) {
		#	echo $name . ": " . implode(", ", $values);
		#}

		//var_dump($request->getReferrer());

		#$baseUrl = $uri->getBaseUrl();
		#die($baseUrl);

		$uriparts=explode("/",$uri->getPath());
		if( $uriparts[1]==='api' && $uriparts[2]==='sfe'){
			$this->client_id = $uriparts[3];
			//error_log("[".$this->Request->getMethod()."] middleware: clientId =".$this->client_id);
			return true;
		}
		return false;
	}


}
