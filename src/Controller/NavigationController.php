<?php

namespace App\Controller;

use App\Entity\Navigation;
use App\Form\NavigationType;
use App\Repository\NavigationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/navigation')]
#[Security("is_granted('ADMIN')")]
class NavigationController extends MainController
{
    #[Route('/', name: 'app_navigation_index', methods: ['GET'])]
    public function index(NavigationRepository $navigationRepository): Response
    {
        return $this->render('navigation/index.html.twig', [
            'navigations' => $navigationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_navigation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $navigation = new Navigation();
        $form = $this->createForm(NavigationType::class, $navigation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($navigation);
            $entityManager->flush();

            return $this->redirectToRoute('app_navigation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('navigation/new.html.twig', [
            'navigation' => $navigation,
            'form' => $form,
            'navigations' => $this->getNavigationElements()
        ]);
    }

    #[Route('/{id}', name: 'app_navigation_show', methods: ['GET'])]
    public function show(Navigation $navigation): Response
    {
        return $this->render('navigation/show.html.twig', [
            'navigation' => $navigation,
            'navigations' => $this->getNavigationElements()
        ]);
    }

    #[Route('/{id}/edit', name: 'app_navigation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Navigation $navigation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(NavigationType::class, $navigation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_navigation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('navigation/edit.html.twig', [
            'navigation' => $navigation,
            'form' => $form,
            'navigations' => $this->getNavigationElements()
        ]);
    }

    #[Route('/{id}', name: 'app_navigation_delete', methods: ['POST'])]
    public function delete(Request $request, Navigation $navigation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$navigation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($navigation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_navigation_index', [], Response::HTTP_SEE_OTHER);
    }
}
