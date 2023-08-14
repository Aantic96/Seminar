<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class HomepageController extends MainController
{
    #[Route('/', name: 'app_homepage')]
    public function index(RouterInterface $router): Response
    {
        $user = $this->getUser();

        if(!$user){
            return new RedirectResponse($router->generate('app_login'));
        }

        return $this->render('homepage/index.html.twig', ['user' => $user, 'navigations' => $this->getNavigationElements()]);
    }

}