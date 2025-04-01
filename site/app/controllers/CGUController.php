<?php
require_once './app/core/Controller.php';

class CGUController extends Controller
{
    public function index()
    {
        $this->view('cgu.html.twig');
    }
}