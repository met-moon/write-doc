<?php
/**
 * twig view
 * User: ttt
 * Date: 2017/3/8
 * Time: 14:34
 */

namespace Moon;


class View
{
    protected $viewPath;

    public function __construct($viewPath)
    {
        $this->viewPath = $viewPath;
    }

    /**
      * @param string $view
     * @param array $data
     * @return string
     * @throws Exception
     */
    public function render($view, array $data = [])
    {
        $viewFile = $this->viewPath.'/'.$view.'.php';
        if(!file_exists($viewFile)){
            throw new Exception("View file `$viewFile` is not exists");
        }

        extract($data);

        ob_start();
        include $viewFile;
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    public function escape($var){
        return htmlspecialchars($var);
    }
}