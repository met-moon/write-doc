<?php
/**
 * Created by PhpStorm.
 * User: ttt
 * Date: 2018/1/29
 * Time: 14:14
 */
namespace App\Middleware;

use \Symfony\Component\HttpFoundation\Request;
use \Closure;

class Auth
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        if(empty($_SESSION['user'])){
            return ('login');
        }

        return $next($request);
    }
}