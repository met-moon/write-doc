<?php
/**
 * Routes for web
 * User: ttt
 * Date: 2018/1/11
 * Time: 22:05
 */

/**
 * @var \Moon\Routing\Router $router
 */
$router = Moon::$app->get('router');

$router->group(['middleware'=>\App\Middleware\SessionStart::class], function ($router){
    /**
     * @var \Moon\Routing\Router $router
     */
    $router->get('', 'IndexController::index')->name('index');
    $router->get('login', 'LoginController::login');
    $router->post('login', 'LoginController::post_login');
    $router->get('logout', 'LoginController::logout');

    $router->group(['prefix'=>'user', 'middleware'=>\App\Middleware\Auth::class], function ($router){
        /**
         * @var \Moon\Routing\Router $router
         */
        $router->get('', 'UserController::index')->name('user');
    });
});

Route::get('md', 'MDController::index');