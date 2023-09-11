<?php

namespace Controllers;

use Library\MVC\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        $this->view->aaaa = 'zzzz';
    }

    public function index()
    {
        $this->view->aaaa = 'zzzz';
        $this->view->pick('index/index');
        /*$this->translate->getText('window', 1, 'pl');
        die();
         $this->translate->getText('window', 2, 'pl');
         $this->translate->getText('window', 3, 'pl');
         $this->translate->getText('window', 99, 'pl');
         $this->translate->getText('window', 1, 'en');
         $this->translate->getText('window', 2, 'en');
         $this->translate->getText('window', 3, 'en');
         $this->translate->getText('window', 99, 'en');*/
    }
}

