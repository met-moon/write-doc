<?php
/**
 * Created by PhpStorm.
 * User: ttt
 * Date: 2018/10/13
 * Time: 22:11
 */
namespace Moon\WriteDoc;

use cebe\markdown\GithubMarkdown;

class WriteDoc
{
    public function __construct($docs_path)
    {
        $this->docs_path = $docs_path;
    }

    protected $docs_path;
    public function show($project, $page, $safe_path = ''){
        $project_path = realpath($this->docs_path.'/'.$project);
        if(!is_dir($project_path)){
            throw new \Exception("Doc system can not found project '$project'.");
        }
        if(!file_exists($project_path.'/config.json')){
            throw new \Exception("Doc system can not found 'config.json' file.");
        }
        $config = json_decode(file_get_contents($project_path.'/config.json'), 1);
        $dst_path = realpath($project_path.'/'.$config['dst_path']);
        if(!is_dir($dst_path)){
            throw new \Exception("Doc system can not found dst path in project '$project'.");
        }
        $page = realpath($dst_path.'/'.$page.'.html');
        if(!file_exists($dst_path)){
            throw new \Exception("Doc system can not found dst file in project '$project'.");
        }

        //make sure file include is safe
        if(!empty($safe_path) && strpos($page, $safe_path) === false){
            throw new \Exception('Doc system not allow to access this file', 403);
        }
        return file_get_contents($page);
    }

    public function build($project){
        $start_time = microtime(true);
        $project_path = realpath($this->docs_path.'/'.$project);
        if(!is_dir($project_path)){
            throw new \Exception("Doc build system can not found project '$project'.");
        }

        if(!file_exists($project_path.'/config.json')){
            throw new \Exception("Doc build system can not found 'config.json' file.");
        }

        $config = json_decode(file_get_contents($project_path.'/config.json'), 1);
        $this->clear_tmp($project_path.'/'.$config['tmp_path']);

        foreach($config['include_pages'] as $page){
            $this->build_page($page, false, $config['layout'], $project_path.'/'.$config['src_path'], $project_path.'/'.$config['dst_path'], $project_path.'/'.$config['tmp_path']);
        }
        $this->build_page($config['index_page'], true, $config['layout'], $project_path.'/'.$config['src_path'], $project_path.'/'.$config['dst_path'], $project_path.'/'.$config['tmp_path']);

        $used_time = microtime(true) - $start_time;
        return 'Build complete! Used '.$used_time.' second. At '.date('Y-m-d H:i:s').'.';
    }

    private function build_page($input, $is_index = false, $layout = 'main', $src_path = '.', $dst_path = '.', $tmp_path = './tmp'){
        $src_path = realpath($src_path);
        $dst_path = realpath($dst_path);
        $tmp_path = realpath($tmp_path);

        $src = $src_path.'/'.$input.'.md';

        $dst = $dst_path.'/'.$input.'.html';

        if(!file_exists($src)){
            throw new \Exception('File `'.$src.'` is not exists!');
        }

        if(!file_exists($src_path.'/layouts/'.$layout.'.html')){
            throw new \Exception('File `'.$src_path.'/layouts/'.$layout.'.html` is not exists!');
        }

        $content = file_get_contents($src);

        if($is_index){
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

        $parser = new GithubMarkdown();
        $parser->html5 = true;
        $content = $parser->parse($content);

        $html = file_get_contents($src_path.'/layouts/'.$layout.'.html');

        $html = preg_replace('#({{content}})#U', $content, $html);

        if(!$is_index){  //读取目录
            $dom = new \DOMDocument();
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
        return $response;
    }

    protected function clear_tmp($tmp_path){
        $tmp_path = realpath($tmp_path);
        if(empty($tmp_path)){
            return false;
        }
        $dh = opendir($tmp_path);
        while (($file = readdir($dh)) !== false) {
            if(strpos($file, '_menu_') === 0){
                @unlink($tmp_path.'/'.$file);
            }
        }
        closedir($dh);
    }
}