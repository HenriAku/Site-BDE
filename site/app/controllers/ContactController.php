<?php

require_once './app/core/Controller.php';
require_once './app/repositories/ContactRepository.php';
require_once './app/trait/FormTrait.php';

class ContactController extends Controller
{
    use FormTrait;
    public function index()
    {
        $this->view('/contact/contact.html.twig');
    }
}