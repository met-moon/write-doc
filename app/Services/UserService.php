<?php
/**
 * Created by PhpStorm.
 * User: ttt
 * Date: 2018/1/23
 * Time: 18:02
 */
namespace App\Services;

class UserService
{
    /**
     * @param string $password
     * @param string $salt
     * @return string
     */
    public static function encrypt($password, $salt)
    {
        return md5($salt . md5($password));
    }

    /**
     * @return string
     */
    public static function salt()
    {
        return md5(uniqid() . time() . rand(0, 1000000));
    }
}