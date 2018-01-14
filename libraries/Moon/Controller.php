<?php
/**
 * Controller
 * User: ttt
 * Date: 2017/3/8
 * Time: 14:30
 */

namespace Moon;


use Symfony\Component\HttpFoundation\Response;

class Controller
{
    protected $viewPath;
    protected $view;

    /**
     * @throws Exception
     * @return View
     */
    public function getView(){
        if(is_null($this->view) || !$this->view instanceof View){

            if(is_null($this->viewPath)){
                $this->viewPath = \Moon::$app->getAppPath().'/views';
            }else{
                if(!is_dir($this->viewPath)){
                    throw new Exception("Directory '$this->viewPath' is not exists!");
                }
                $this->viewPath = realpath($this->viewPath);
            }
            $this->view = new View($this->viewPath);
        }
        return $this->view;
    }

    /**
     * @param string $view
     * @param array $data
     * @return Response
     */
    public function render($view, $data = []){
        $string = $this->getView()->render($view, $data);
        return new Response($string);
    }
}