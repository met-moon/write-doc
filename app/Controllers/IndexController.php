<?php
/**
 * Created by PhpStorm.
 * User: ttt
 * Date: 2018/1/11
 * Time: 22:11
 */
namespace App\Controllers;

use App\Models\User;
use Moon\Controller;

class IndexController extends Controller
{
    public function index(){
        return 'welcome to write-doc';
    }

    public function login(){
        return $this->render('login');
    }
}