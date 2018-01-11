<?php
namespace Moon;

use Moon\Routing\Router;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class Application
 * @package Moon
 */
class Application
{
    protected $rootPath;
    protected $configPath;
    protected $appPath;

    protected $config = [];

    protected $errorReportingLevel = E_ALL;
    protected $debug = false;
    protected $charset = 'UTF-8';
    protected $timezone = 'UTC';

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var Request
     */
    protected $request;

    public function __construct($rootPath, $configPath = null, $appPath = null)
    {
        if(!is_dir($rootPath)){
            throw new Exception("Directory '$rootPath' is not exists!");
        }
        $this->rootPath = realpath($rootPath);

        if(is_null($configPath)){
            $configPath = $this->rootPath.'/config';
        }
        if(!is_dir($configPath)){
            throw new Exception("Directory '$configPath' is not exists!");
        }
        $this->configPath = realpath($configPath);

        Config::setConfigDir($this->configPath);
        $this->config = Config::get('app', true);

        if(is_null($appPath)){
            $appPath = $this->rootPath.'/app';
        }
        if(!is_dir($appPath)){
            throw new Exception("Directory '$appPath' is not exists!");
        }
        $this->appPath = realpath($appPath);

        $this->init();

        Moon::$app = $this;
    }

    protected function init(){
        if (!empty($this->config['timezone'])) {
            $this->timezone = $this->config['timezone'];
            date_default_timezone_set($this->timezone);
        }

        if (!empty($this->config['charset'])) {
            $this->charset = $this->config['charset'];
        }

        if (isset($this->config['session']['auto_start']) && $this->config['session']['auto_start'] == false) {
        }else{
            if (!empty($this->config['session']['name'])) {
                session_name($this->config['session']['name']);
            }
            session_start();
        }
    }

    public function enableDebug($errorReportingLevel = E_ALL){
        $this->errorReportingLevel = $errorReportingLevel;
        $this->debug = true;
        return $this;
    }

    public function run(){
        $this->request = Request::createFromGlobals();

        $this->router = new Router(null, [
            'namespace'=>'App\\Controllers',
            //'middleware'=>['sessionStart'],
            //'prefix'=>''
        ]);

        require $this->rootPath.'/routes/web.php';

        $routes = $this->router->getRoutes();

        $request = Request::createFromGlobals();

        try{
            $response = $this->resolveRequest($request, $routes);
        }catch (ResourceNotFoundException $e){
            $response = $this->makeResponse($e->getMessage(), 404);
        }catch (MethodNotAllowedException $e){
            $response = $this->makeResponse('Method not allow', 405);
        }

        $response->send();
    }

    /**
     * @param Request $request
     * @param RouteCollection $routes
     * @return JsonResponse|Response
     */
    protected function resolveRequest(Request $request, RouteCollection $routes){
        $context = new RequestContext();
        $context->fromRequest($request);

        //match
        $matcher = new UrlMatcher($routes, $context);
        $matchResult = $matcher->match($request->getPathInfo());

        return $this->resolveController($matchResult);
    }

    /**
     * @param mixed $data
     * @param int $status
     * @return JsonResponse|Response
     */
    protected function makeResponse($data, $status = 200){
        if($data instanceof Response){
            return $data;
        }else if(is_array($data) || is_object($data)){
            return new JsonResponse($data, $status);
        }else{
            return new Response(strval($data), $status);
        }
    }

    /**
     * @param array $matchResult
     * @return JsonResponse|Response
     */
    protected function resolveController($matchResult){
        /**
         * resolve controller
         */
        $action = $matchResult['_controller'];
        unset($matchResult['_controller']);
        unset($matchResult['_route']);
        if($action instanceof \Closure){
            $data = call_user_func($action, $this->router);
            return $this->makeResponse($data);
        }else{
            $actionArr = explode('::', $action);
            $controllerName = $actionArr[0];
            if(!class_exists($controllerName)){
                return $this->makeResponse("Controller class '$controllerName' is not exists!", 404);
            }
            $controller = new $controllerName;
            $methodName = $actionArr[1];
            if(!method_exists($controller, $methodName)){
                return $this->makeResponse("Controller method '$controllerName::$methodName' is not defined!", 404);
            }

            if(empty($matchResult)){
                $data = call_user_func([$controller, $methodName]);
            }else{
                $data = call_user_func_array([$controller, $methodName], $matchResult);
            }

            return $this->makeResponse($data);
        }
    }

    public function __call($name, $arguments)
    {
        if (strpos($name, 'get') === 0) { //get protected attribute
            $attribute = lcfirst(substr($name, 3));
            if (isset($this->$attribute)) {
                return $this->$attribute;
            }
        }
        throw new Exception('Call to undefined method ' . get_class($this) . '::' . $name . '()');
    }
}