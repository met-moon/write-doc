<?php
/**
 * Created by PhpStorm.
 * User: ttt
 * Date: 2018/1/12
 * Time: 15:37
 */

if (!function_exists('is_cli')) {
    /**
     * check if php running in cli mode
     */
    function is_cli()
    {
        return preg_match("/cli/i", php_sapi_name()) ? true : false;
    }
}

if (!function_exists('dump')) {
    /**
     * pretty var_dump
     * @param $var
     * @params mixed $var
     */
    function dump($var)
    {
        if (is_cli()) {
            foreach (func_get_args() as $var) {
                var_dump($var);
            }
        } else {
            echo '<pre>';
            foreach (func_get_args() as $var) {
                var_dump($var);
            }
            echo '</pre>';
        }
    }
}

if (!function_exists('dd')) {
    /**
     * pretty var_dump and exit 1
     * @param mixed $var
     */
    function dd($var)
    {
        dump($var);
        exit(1);
    }
}

if (!function_exists('root_path')) {
    /**
     * @param string $path
     * @return string
     */
    function root_path($path = '')
    {
        return \Moon::$app->getRootPath() . (strlen($path) ? DIRECTORY_SEPARATOR . $path : '');
    }
}

if (!function_exists('app_path')) {
    /**
     * @param string $path
     * @return string
     */
    function app_path($path = '')
    {
        return \Moon::$app->getAppPath() . (strlen($path) ? DIRECTORY_SEPARATOR . $path : '');
    }
}

if (!function_exists('storage_path')) {
    /**
     * @param string $path
     * @return string
     */
    function storage_path($path = '')
    {
        return \Moon::$app->getRootPath() . DIRECTORY_SEPARATOR . 'storage' . (strlen($path) ? DIRECTORY_SEPARATOR . $path : '');
    }
}

if (!function_exists('runtime_path')) {
    /**
     * @param string $path
     * @return string
     */
    function runtime_path($path = '')
    {
        return \Moon::$app->getRootPath() . DIRECTORY_SEPARATOR . 'runtime' . (strlen($path) ? DIRECTORY_SEPARATOR . $path : '');
    }
}

if (!function_exists('public_path')) {
    /**
     * @param string $path
     * @return string
     */
    function public_path($path = '')
    {
        return \Moon::$app->getRootPath() . DIRECTORY_SEPARATOR . 'public' . (strlen($path) ? DIRECTORY_SEPARATOR . $path : '');
    }
}

if (!function_exists('asset')) {
    /**
     * @param string $path
     * @param bool $full
     * @return string
     */
    function asset($path, $full = true)
    {
        /**
         * @var \Symfony\Component\HttpFoundation\Request $request
         */
        $request = \Moon::$app->get('request');
        if ($full) {
            return $request->getSchemeAndHttpHost() . $request->getBasePath() . '/' . $path;
        }
        return $request->getBasePath() . '/' . $path;
    }
}

if (!function_exists('app')) {
    /**
     * @return \Moon\Application
     */
    function app()
    {
        return \Moon::$app;
    }
}

if (!function_exists('request')) {
    /**
     * @param null|string $key
     * @param null|mixed $default
     * @return null|mixed|\Symfony\Component\HttpFoundation\Request $request
     */
    function request($key = null, $default = null)
    {
        $request = \Moon::$app->get('request');
        if(is_null($key)){
            return $request;
        }
        $value = $request->get($key);
        return is_null($value) || strlen($value) == 0 ? $default : $value;
    }
}