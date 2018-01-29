<?php
/**
 * Created by PhpStorm.
 * User: ttt
 * Date: 2018/1/11
 * Time: 22:08
 */

/**
 * Class Route
 */
class Route{

    public static function __callStatic($name, $arguments)
    {
        /**
         * @var \Moon\Routing\Router $router
         */
        $router = \Moon::$app->get('router');
        return call_user_func_array([$router, $name], $arguments);
    }
}