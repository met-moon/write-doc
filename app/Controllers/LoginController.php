<?php
/**
 * Created by PhpStorm.
 * User: ttt
 * Date: 2018/2/2
 * Time: 13:45
 */

namespace App\Controllers;


use Moon\Controller;

class LoginController extends Controller
{
    public function login(){
        return $this->render('login');
    }
}