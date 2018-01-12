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
        return 'welcome';
    }

    public function say(){
        return [
            'aa'=>'11'
        ];
    }

    public function login(){

        /*$id = request('id', 1);

        $user = new User();
        $list = $user->where('id', $id)->get();
        dd($id, $list->toArray());*/
        return $this->render('login');
    }
}