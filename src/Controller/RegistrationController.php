<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use App\Form\RegistrationType;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/registration', name: 'app_registration')]
    public function register(Request $request,
                             UserPasswordHasherInterface $passwordHasher,
                             EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RegistrationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $user = new User();

            $user->setName($formData['name']);

            $user->setEmail($formData['email']);

            $hashedPassword = $passwordHasher->hashPassword($user, $formData['password']);
            $user->setPassword($hashedPassword);

            $roleRepository = $entityManager->getRepository(Role::class);

            $role = $roleRepository->findOneBy(['name' => 'USER']);

            $user->setRole($role);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('registration/index.html.twig', [
            'form' => $form->createView(),
            'navigations' => []
        ]);
    }
}
