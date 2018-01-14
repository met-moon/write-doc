<?php
/**
 * twig view
 * User: ttt
 * Date: 2017/3/8
 * Time: 14:34
 */

namespace Moon;


class TwigView
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
     */
    public function render($view, array $data = [])
    {
        $loader = new \Twig_Loader_Filesystem($this->viewPath);
        $twig = new \Twig_Environment($loader, array(
            'cache' => App::$app->getRootPath().'/runtime/cache/twig',
            'debug' => App::$app->getDebug(),
            'charset' => App::$app->getCharset(),
        ));
        $view .= '.twig';
        return $twig->render($view, $data);
    }
}