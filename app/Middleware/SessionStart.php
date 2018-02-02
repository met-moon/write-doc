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

class SessionStart
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        $config = config('session');
        if(!isset($_SESSION)){
            if(isset($config['session']['name'])){
                session_name($config['session']['name']);
                unset($config['session']['name']);
            }
            empty($config) ? session_start() : session_start($config);
        }

        return $next($request);
    }
}