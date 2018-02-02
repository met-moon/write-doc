<?php

namespace Moon;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Moon\Config\Config;
use Moon\Routing\Router;
use Moon\Container\Container;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PlainTextHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

/**
 * Class Application
 * @package Moon
 */
class Application extends Container
{
    protected $rootPath;
    protected $configPath;
    protected $appPath;

    protected $config = [];

    protected $environment = 'production';
    protected $debug = false;
    protected $charset = 'UTF-8';
    protected $timezone = 'UTC';

    public function __construct($rootPath, $configPath = null, $appPath = null)
    {
        if (!is_dir($rootPath)) {
            throw new Exception("Directory '$rootPath' is not exists!");
        }
        $this->rootPath = realpath($rootPath);

        if (is_null($configPath)) {
            $configPath = $this->rootPath . '/config';
        }
        if (!is_dir($configPath)) {
            throw new Exception("Directory '$configPath' is not exists!");
        }
        $this->configPath = realpath($configPath);

        Config::setConfigDir($this->configPath);
        $this->config = Config::get('app', true);

        if (is_null($appPath)) {
            $appPath = $this->rootPath . '/app';
        }
        if (!is_dir($appPath)) {
            throw new Exception("Directory '$appPath' is not exists!");
        }
        $this->appPath = realpath($appPath);

        $this->init();

        $this->add('request', Request::createFromGlobals());

        require_once __DIR__ . '/helpers.php';

        \Moon::$app = $this;
    }

    protected function handleError()
    {
        $logger = new Logger('app');
        $this->add('logger', $logger);
        $whoops = new Run();
        if ($this->debug) {
            if ($this->get('request')->isXmlHttpRequest()) {
                $whoops->pushHandler(new JsonResponseHandler());
            } else {
                $handler = new PrettyPageHandler();
                $handler->setPageTitle('Moon App');
                $whoops->pushHandler($handler);
            }
        }
        $handler = new PlainTextHandler($logger);
        $handler->loggerOnly(true);
        $whoops->pushHandler($handler);
        $whoops->register();

        $filename = $this->rootPath . '/runtime/logs/app-' . date('Y-m-d') . '.log';
        $logger->pushHandler(new StreamHandler($filename, Logger::ERROR));
    }

    protected function init()
    {
        if (!empty($this->config['timezone'])) {
            $this->timezone = $this->config['timezone'];
            date_default_timezone_set($this->timezone);
        }

        if (!empty($this->config['charset'])) {
            $this->charset = $this->config['charset'];
        }
    }

    public function enableDebug()
    {
        $this->debug = true;
        return $this;
    }

    /*public function bootstrap($plugins)
    {
        $this->plugins = (array)$plugins;
        return $this;
    }*/

    protected function bootstrapPlugins()
    {
        $plugins = isset($this->config['bootstrap']) ? $this->config['bootstrap'] : [];
        foreach ($plugins as $plugin) {
            include $this->rootPath . DIRECTORY_SEPARATOR . 'bootstrap' . DIRECTORY_SEPARATOR . $plugin . '.php';
        }
        return $this;
    }

    public function run()
    {
        $this->handleError();

        $router = new Router(null, [
            'namespace' => 'App\\Controllers',
        ]);

        $this->add('router', $router);

        require $this->rootPath . '/routes/web.php';

        $routes = $router->getRoutes();

        try {
            $response = $this->resolveRequest($this->get('request'), $routes);
        } catch (ResourceNotFoundException $e) {
            $response = $this->makeResponse($e->getMessage(), 404);
        } catch (MethodNotAllowedException $e) {
            $response = $this->makeResponse('Method not allow', 405);
        }

        $response->send();
    }

    /**
     * @param Request $request
     * @param RouteCollection $routes
     * @return JsonResponse|Response
     */
    protected function resolveRequest(Request $request, RouteCollection $routes)
    {
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
    protected function makeResponse($data, $status = 200)
    {
        if ($data instanceof Response) {
            return $data;
        } else if (is_array($data) || is_object($data)) {
            return new JsonResponse($data, $status);
        } else {
            return new Response(strval($data), $status);
        }
    }

    /**
     * @param Request $request
     * @param array $middlewareList
     * @return mixed
     * @throws Exception
     */
    protected function filterMiddleware($request, $middlewareList)
    {
        if (empty($middlewareList)) {
            return null;
        }
        $middleware = array_shift($middlewareList);
        if (!class_exists($middleware)) {
            throw new Exception('Class ' . $middleware . ' is not exists!');
        }
        $middlewareObj = new $middleware();
        return $middlewareObj->handle($request, function ($request) use ($middlewareList) {
            return $this->filterMiddleware($request, $middlewareList);
        });
    }

    /**
     * @param array $matchResult
     * @return JsonResponse|Response
     */
    protected function resolveController($matchResult)
    {
        $routeName = $matchResult['_route'];
        $router = $this->get('router');
        $route = $router->getRoute($routeName);
        $middlewareList = $route->getMiddleware();
        $request = $this->get('request');
        $result = $this->filterMiddleware($request, $middlewareList);

        if (!is_null($result)) {
            return $this->makeResponse($result);
        }

        /**
         * resolve controller
         */
        $action = $matchResult['_controller'];
        unset($matchResult['_controller']);
        unset($matchResult['_route']);
        if ($action instanceof \Closure) {
            $data = call_user_func($action, $router);
            return $this->makeResponse($data);
        } else {
            $actionArr = explode('::', $action);
            $controllerName = $actionArr[0];
            if (!class_exists($controllerName)) {
                return $this->makeResponse("Controller class '$controllerName' is not exists!", 404);
            }
            $controller = new $controllerName;
            $methodName = $actionArr[1];
            if (!method_exists($controller, $methodName)) {
                return $this->makeResponse("Controller method '$controllerName::$methodName' is not defined!", 404);
            }

            if (empty($matchResult)) {
                $data = call_user_func([$controller, $methodName]);
            } else {
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