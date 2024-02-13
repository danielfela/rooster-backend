<?php

namespace Controllers;

use Library\MVC\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {

    }

    public function index()
    {
        $this->view->pick('index/index');
    }
}

