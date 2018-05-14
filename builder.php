<?php
/**
 * Created by PhpStorm.
 * User: ttt
 * Date: 2018/5/7
 * Time: 12:01
 */

//todo 1.默认样式 2.锚点 3.生成目录

require 'vendor/autoload.php';

$input = isset($_GET['input']) ? $_GET['input'] : 'index';
$output = isset($_GET['output']) ? $_GET['output'] : $input;
$layout = isset($_GET['layout']) ? $_GET['layout'] : 'main';

$src_path = 'src';
$dst_path = 'dst';
$tmp_path = 'tmp';

$src = $src_path.'/'.$input.'.md';
$dst = $dst_path.'/'.$output.'.html';

if(!file_exists($src)){
    throw new Exception('File `'.$src.'` is not exists!');
}

if(!file_exists($src_path.'/layouts/'.$layout.'.html')){
    throw new Exception('File `'.$src_path.'/layouts/'.$layout.'.html` is not exists!');
}

$content = file_get_contents($src);

if(!empty($_GET['put_menu'])){  //放入目录列表
    $dh = opendir($tmp_path);
    $menu_src = '';
    while (($file = readdir($dh)) !== false) {
        if(strpos($file, '_menu_') === 0){
            $menu_src .= file_get_contents($tmp_path.'/'.$file);
        }
    }
    closedir($dh);
    $content = str_replace('<!-- menu -->', $menu_src, $content);
}

$parser = new \cebe\markdown\GithubMarkdown();
$parser->html5 = true;
$content = $parser->parse($content);

$html = file_get_contents($src_path.'/layouts/'.$layout.'.html');

$html = preg_replace('#({{content}})#U', $content, $html);

if(!empty($_GET['get_menu'])){  //读取目录
    $dom = new DOMDocument();
    $dom->loadHTML($html);
    $menu = '';
    $h2 = $dom->getElementsByTagName('h2')[0];
    $title = $h2->textContent;
    //$h2->setAttribute('id', urlencode($title));
    $h2->setAttribute('id', base64_encode($title));
    $number = '';
    if(preg_match("#^([0-9\.]+)\s#", $title, $matches)){
        $number = trim($matches[0]);
        $title = str_replace($number, '', $title);
    }

    $menu .= strlen($number) > 0 ? $number.' ['.$title.']('.$input.'.html)'.PHP_EOL : '* ['.$title.']('.$input.'.html)'.PHP_EOL;

    foreach($dom->getElementsByTagName('h3') as $h3){
        //$menu .= '    * ['.$h3->textContent.']('.$input.'.html#'.urlencode($h3->textContent).')'.PHP_EOL;
        //$h3->setAttribute('id', urlencode($h3->textContent));
        $menu .= '    * ['.$h3->textContent.']('.$input.'.html#'.base64_encode($h3->textContent).')'.PHP_EOL;
        $h3->setAttribute('id', base64_encode($h3->textContent));
    }
    $dom->saveHTMLFile($dst);
    file_put_contents($tmp_path.'/_menu_'.$number.$input.'.md', $menu);
    $response =  file_get_contents($dst);
}else{
    file_put_contents($dst, $html);
    $response =  $html;
}

$url = dirname($_SERVER['REQUEST_URI']).'/'.$input.'.html';

?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>API生成预览</title>
    <style>
        html,body{width: 100%; height: 100%; margin: 0;overflow: hidden}
        iframe{width: 100%; height: 100%; margin-top: 0; border-style: none;}
        #top_bar{width: 100%; background-color: #ef6f48; color: #f8f8f8; height:40px; line-height: 40px; padding-left: 10px; border-bottom: 1px solid #ccc;position: fixed;}
        #iframe_url{color: #f8f8f8; text-decoration:none;}
        #iframe_url:hover{text-decoration:underline;}
    </style>
</head>
<body>
<div id="top_bar">文档地址：<a id="iframe_url" title="点击访问" target="_blank" href="<?= $url?>"><?= $url?></a></div>
<iframe src="<?=$url?>?v=<?= time()?>" onload="loadFrame(this)"></iframe>
</body>
<script>
    function loadFrame(obj){
        var a = document.getElementById('iframe_url');
        a.href = a.innerText = obj.contentWindow.location.href;
    }
</script>
</html>