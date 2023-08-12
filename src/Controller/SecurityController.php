<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    #[Route('/logout', name: 'app_logout', methods: ['POST', 'GET'])]
    public function logout(): never
    {
        // controller can be blank: it will never be called!
        throw new \Exception('Something went wrong with logging out');
    }

}