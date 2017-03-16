<?php
namespace App\Controller;


class Controller
{
    protected $view;

    public function __construct($view) {
        $this->view = $view;
    }

    public function index($request, $response, $args) {
        return $this->view->render($response, 'index.phtml', $args);
    }
}
