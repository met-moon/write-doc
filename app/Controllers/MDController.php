<?php
/**
 * Created by PhpStorm.
 * User: ttt
 * Date: 2018/5/2
 * Time: 18:05
 */

namespace App\Controllers;


use Moon\Controller;

class MDController extends Controller
{
    public function index(){
        //return 'md - index';
        $md = new \Parsedown();
        $content = $md->parse(file_get_contents(root_path('docs/index.md')));

        //todo 锚点

        return $this->render('md', ['content'=>$content]);
    }
}