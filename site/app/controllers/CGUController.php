<?php
require_once './app/core/Controller.php';
require_once './app/services/AuthService.php';

class CGUController extends Controller
{
    public function index()
    {
        $servUser = new AuthService();
        if($servUser->getUser() === null)
        {
            $this->view('cgu.html.twig', ['admin' => null]);

        }else{
            $user = $servUser->getUser();
            $perm = $user->getAdmin();
            
            $this->view('cgu.html.twig', ['admin' => $perm]);
        }
       
    }
}