<?php

namespace App\Controller\Frontend;

use App\Entity\Users;
use App\Form\UserType;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/users', '.users')]
class UserController extends AbstractController
{
    public function __construct(
        private UsersRepository $userRepo,
        private EntityManagerInterface $em
    ) {
    }

    #[Route('/{id}', '.details', methods: ['GET', 'POST'])]
    public function details(): Response
    {
        return $this->render('Frontend/Users/details.html.twig', [
            'users' => $this->userRepo->findOneByEmail(),
        ]);
    }

    #[Route('/{id}/edit', '.edit', methods: ['GET', 'POST'])]
    public function edit(?Users $user, Request $request): Response|RedirectResponse
    {

        $form = $this->createForm(UserType::class, $user,);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($user);
            $this->em->flush();


            return $this->redirectToRoute('users.edit');
        }

        return $this->render('Frontend/Users/edit.html.twig', ['form' => $form]);
    }
}
