<?php
/**
 * Config
 * User: ttt
 * Date: 2017/3/8
 * Time: 15:07
 */

namespace Moon;

class Config
{
    protected static $configDir;

    public static function setConfigDir($dir)
    {
        if (!is_dir($dir)) {
            throw new Exception("Directory '$dir' is not exists!");
        }
        static::$configDir = realpath($dir);
    }
    /**
     * get a config
     * @param string $key
     * @param bool $throw
     * @return mixed|null
     * @throws Exception
     */
    public static function get($key, $throw = false)
    {
        $arr = explode('.', $key);
        $configFile = static::$configDir . DIRECTORY_SEPARATOR . $arr[0] . '.php';
        if (!file_exists($configFile)) {
            if ($throw) {
                throw new Exception("Config file `$configFile` is not exists");
            } else {
                return null;
            }
        }
        unset ($arr[0]);
        $config = require $configFile;
        $count = count($arr);
        if ($count == 0) {
            return $config;
        } else {
            $arr = array_values($arr);
            $path = '';
            $value = $config;
            for ($i = 0; $i < $count; $i++) {
                $key = $arr[$i];
                $path .= '[' . $key . ']';
                if (!isset($value[$key])) {
                    if ($throw) {
                        throw new Exception("Offset `Array $path` is not defined in config file `$configFile`");
                    } else {
                        return null;
                    }
                }
                $value = $value[$key];
            }
        }
        return $value;
    }
}