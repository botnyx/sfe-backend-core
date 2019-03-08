<?php


namespace Botnyx\Sfe\Backend\Core\Frontend;




use Slim\Http;
use Slim\Views;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;


class Configuration {

    function __construct(ContainerInterface $container){
        $pdo  = $container->get('pdo');
        $this->cache  = $container->get('cache');
		
		$this->feConfig = new \Botnyx\Sfe\Backend\Core\Database\FrontendConfig($pdo);
		
		$this->outputFormat = new \Botnyx\Sfe\Shared\ApiResponse\Formatter();
		
    }

    public function get(ServerRequestInterface $request, ResponseInterface $response, array $args = []){

        $extcfg = array(
          'clientId'=>'909b6bb0-servenow-website',
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
		
		$config 	= $this->feConfig->getConfigByClientId($args['clientid']);
		
		$menus      = $this->feConfig->getByMenuClientId($args['clientid']);
		
		$endpoints 	= $this->feConfig->getFrontendEndpoints($args['clientid']);
		
		//$endpoints 	= $this->feConfig->getFrontendEndpoints($args['clientid']);
		
		
		$lastUpdated = time() - 3600;
		
		$data = array(
          'lastupdated'=>$lastUpdated,
          'routes'=>$endpoints,
		  'menus'=>$menus,
          'config'=>$config
        );
		
		
        

        $xdata = array(
          'routes'=>$endpoints,
		  'menus'=>$menus,
          'clientid'=>$args['clientid'],
          'userprefs'=>array("language"=>"nl_NL"),
          'status'=>'ok',
        );
        //return $response->write('')->withStatus(401);
        //return $response->withJson($data);//->withStatus(500);

		

        $res = $response->withJson( $this->outputFormat->response($data) );
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
