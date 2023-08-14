<?php

namespace App\Controller;

use App\Repository\NavigationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class MainController extends AbstractController
{
    public function __construct(protected NavigationRepository $navigationRepository)
    {}
    public function getNavigationElements(): array
    {
       return $this->navigationRepository->findAll();
    }
}