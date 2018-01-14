<?php
/**
 * Route
 * User: ttt
 * Date: 2017/3/8
 * Time: 11:04
 */

namespace Moon;

use Symfony\Component\Routing\RouteCollection;

/**
 * Class Route
 * @package Moon
 */
class Route
{
    /**
     * All of the verbs supported by the router.
     *
     * @var array
     */
    public static $verbs = ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'];

    /**
     * @var RouteCollection
     */
    protected static $routes;

    /**
     * Register a new custom method route.
     *
     * @param  array $methods
     * @param  string $uri
     * @param  \Closure|array|string $action
     */
    public static function match($methods, $uri, $action){
        static::addRoute($methods, $uri, $action);
    }

    /**
     * Register a new route.
     *
     * @param  string $uri
     * @param  \Closure|array|string $action
     */
    public static function any($uri, $action){
        static::addRoute(static::$verbs, $uri, $action);
    }

    /**
     * Register a new GET route.
     *
     * @param  string $uri
     * @param  \Closure|array|string $action
     */
    public static function get($uri, $action)
    {
        static::addRoute(['GET', 'HEAD'], $uri, $action);
    }

    /**
     * Register a new POST route with the router.
     *
     * @param  string $uri
     * @param  \Closure|array|string $action
     * @return static
     */
    public static function post($uri, $action)
    {
        return static::addRoute('POST', $uri, $action);
    }

    /**
     * Register a new PUT route with the router.
     *
     * @param  string $uri
     * @param  \Closure|array|string $action
     * @return static
     */
    public static function put($uri, $action)
    {
        return static::addRoute('PUT', $uri, $action);
    }

    /**
     * Register a new PATCH route with the router.
     *
     * @param  string $uri
     * @param  \Closure|array|string $action
     * @return static
     */
    public static function patch($uri, $action)
    {
        return static::addRoute('PATCH', $uri, $action);
    }

    /**
     * Register a new DELETE route with the router.
     *
     * @param  string $uri
     * @param  \Closure|array|string $action
     * @return static
     */
    public static function delete($uri, $action)
    {
        return static::addRoute('DELETE', $uri, $action);
    }

    /**
     * Register a new DELETE route with the router.
     *
     * @param  string $uri
     * @param  \Closure|array|string $action
     * @return static
     */
    public static function options($uri, $action)
    {
        return static::addRoute('OPTIONS', $uri, $action);
    }

    /**
     * @param string $methods
     * @param string $uri
     * @param \Closure|array|string $action
     * @return \Symfony\Component\Routing\Route
     */
    protected static function addRoute($methods, $uri, $action)
    {
        $route = new \Symfony\Component\Routing\Route($uri);
        $route->setMethods($methods);
        $route->setDefault('_controller', $action);
        static::getRouteCollection()->add(uniqid(), $route);
    }

    public static function getRouteCollection()
    {
        if (is_null(static::$routes)) {
            return static::$routes = new RouteCollection();
        }
        return static::$routes;
    }
}