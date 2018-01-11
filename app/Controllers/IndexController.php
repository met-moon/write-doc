<?php
/**
 * Created by PhpStorm.
 * User: ttt
 * Date: 2018/1/11
 * Time: 22:11
 */
namespace App\Controllers;

use Moon\Controller;

class IndexController extends Controller
{
    public function say(){
        return [
            'aa'=>'11'
        ];
    }
}