<?php


namespace Botnyx\Sfe\Backend\Core;




use Slim\Http;
use Slim\Views;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;


class Configuration {

    function __construct(ContainerInterface $container){
        $this->pdo  = $container->get('pdo');
        $this->cache  = $container->get('cache');
    }

    public function get(ServerRequestInterface $request, ResponseInterface $response, array $args = []){

        $extcfg = array(
          'clientId'=>'709b6bb0-devpoc-website',
          'htmlts'=>1234,
          'html'=>null,
          'lang'=>array('en-UK','nl-NL'),
          'js'=>array(),
          'css'=>array(),
          'template'=>'laborator/neon-bootstrap-admin-theme',
          'extracfg'=>array(
              "allowedorigin"=>"*",
              "backendhostname"=>"backend.devpoc.nl",
              "cdnhostname"=>"cdn.devpoc.nl",
              "client_id"=>"709b6bb0-devpoc-website",
              "defaultpage"=>"home",
              "disabled"=>0,
              "disabledreason"=>"",
              "hostname"=>"devpoc",
              "htmlstamp"=>"123",
              "languages"=>"en-UK,nl-NL",
              "requestedLanguage"=>array("en-UK,nl-NL"),
              "template"=>"laborator/neon-bootstrap-admin-theme",
              "workbox"=>0,
              "workboxnav"=>null
            )
          );
        //$fe_cfg = new \Botnyx\SfeBackend\Database\frontend_config($this->pdo);

        //$localRoutes = $fe_cfg->getStaticUrlsByClientId($args['clientid']);
		
		
		
		// Botnyx\\Sfe\\Backend\\Core\\FrontendEndpoint:get
        $localRoutes[]=array(
          "uri"=>"/",
	      "fnc"=>"\\Botnyx\\Sfe\\Frontend\\Core\\Frontendendpoint:get",
          "tmpl"=>"laborator/neon-bootstrap-admin-theme"
        );
        $localRoutes[]=array(
          "uri"=>"/newspaper/edition/{edition}",
          "fnc"=>"\\Botnyx\\Sfe\\Frontend\\Core\\Frontendendpoint:get",
          "tmpl"=>"botnyx/newspaper"
        );
        $localRoutes[]=array(
          "uri"=>"/newspaper/article/{articleid}",
          "fnc"=>"\\Botnyx\\Sfe\\Frontend\\Core\\Frontendendpoint:get",
          "tmpl"=>"botnyx/newspaper"
        );
        $localRoutes[]=array(
          "uri"=>"/newspaper",
          "fnc"=>"\\Botnyx\\Sfe\\Frontend\\Core\\FrontendEndpoint:get",
          "tmpl"=>"botnyx/newspaper"
        );
        $localRoutes[]=array(
          "uri"=>"/sw.js",
          "fnc"=>"\\Botnyx\\Sfe\\Frontend\\Core\\Frontend:getServiceWorker",
          "tmpl"=>""
        );


        $lastUpdated = time() - 3600;

        $data = array(
          'lastupdated'=>$lastUpdated,
          'routes'=>$localRoutes,
          'clientid'=>$args['clientid'],
          'userprefs'=>array("language"=>"nl_NL"),
          'status'=>'ok',
        );
        //return $response->write('')->withStatus(401);
        //return $response->withJson($data);//->withStatus(500);



        $res = $response->withJson($data);
        //$resWithExpires = $this->cache->withExpires($res, time() + 3600);
        //$res = $this->cache->withExpires($res, time() + 3600);
        $resWithLastMod = $this->cache->withLastModified($res, $lastUpdated);

        return $resWithLastMod;

    }


    public function getByClientId($clientId){
        
    }

    public function getByHostName($hostname){

    }



}
