<?php

namespace App\Controller;

use App\Entity\Page;
use App\Entity\User;
use App\Form\PageType;
use App\Repository\NavigationRepository;
use App\Repository\PageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/page')]
//#[Security("is_granted('USER')")]
class PageController extends MainController
{

    public function __construct(protected SluggerInterface $slugger, protected NavigationRepository $navigationRepository)
    {
        parent::__construct($this->navigationRepository);
    }

    #[Route('/', name: 'app_page_index', methods: ['GET'])]
    public function index(PageRepository $pageRepository): Response
    {
        return $this->render('page/index.html.twig', [
            'pages' => $pageRepository->findAll(),
            'navigations' => $this->getNavigationElements()
        ]);
    }

    #[Route('/new', name: 'app_page_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $page = new Page();
        $user = $this->getUser();

        $form = $this->createForm(PageType::class, $page);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $page->setAuthor($user);

            $this->imageSave($form, $page);

            $entityManager->persist($page);
            $entityManager->flush();

            return $this->redirectToRoute('app_page_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('page/new.html.twig', [
            'form' => $form,
            'page' => $page,
            'navigations' => $this->getNavigationElements()
        ]);
    }

    #[Route('/{id}', name: 'app_page_show', methods: ['GET'])]
    public function show(Page $page): Response
    {
        return $this->render('page/show.html.twig', [
            'page' => $page,
            'navigations' => $this->getNavigationElements()
        ]);
    }

    #[Route('/{id}/edit', name: 'app_page_edit', methods: ['GET', 'POST'])]
    #[IsGranted('EDIT', subject: 'page')]
    public function edit(Request $request, Page $page, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->imageSave($form, $page);

            $entityManager->flush();

            return $this->redirectToRoute('app_page_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('page/edit.html.twig', [
            'page' => $page,
            'form' => $form,
            'navigations' => $this->getNavigationElements()
        ]);
    }

    #[Route('/{id}', name: 'app_page_delete', methods: ['POST'])]
    #[IsGranted('DELETE', subject: 'page')]
    public function delete(Request $request, Page $page, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $page->getId(), $request->request->get('_token'))) {
            try{
                $entityManager->remove($page);
                $entityManager->flush();
            }
            catch (\Exception $exception)
            {}
        }

        return $this->redirectToRoute('app_page_index', [], Response::HTTP_SEE_OTHER);
    }

    protected function imageSave(FormInterface $form, Page $page): void
    {
        $image = $form->get('image')->getData();

        if ($image) {
            $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = $this->slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();

            // Move the file to the directory where brochures are stored
            try {
                $image->move(
                    $this->getParameter('image_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            $page->setImage($newFilename);
        }

    }
}
