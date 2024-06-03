<?php

namespace App\Controller\Backend;

use App\Entity\Users;
use App\Form\UserType;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/admin/users', 'admin.users')]
class UserController extends AbstractController
{
    public function __construct(
        private UsersRepository $userRepo,
        private EntityManagerInterface $em
    ) {
    }

    #[Route('', name: '.index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('Backend/User/index.html.twig', [
            'users' => $this->userRepo->findAll(),
        ]);
    }

    #[Route('/create', name: '.create', methods: ['GET', 'POST'])]
    public function create(Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $em): Response|RedirectResponse
    {
        $user = new Users;
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($hasher->hashPassword($user, $form->get('password')->getData()));
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'L\'utilisateur a bien été ajouté');
            return $this->redirectToRoute('.index');
        }
        return $this->render('Backend/Users/index.html.twig', ['form' => $form]);
    }

    #[Route('/{id}/edit', '.edit', methods: ['GET', 'POST'])]
    public function edit(?Users $user, Request $request): Response|RedirectResponse
    {
        if (!$user) {
            $this->addFlash('error', 'Utilisateur non trouvé');
            return $this->redirectToRoute('admin.users.index');
        }

        $form = $this->createForm(UserType::class, $user, ['isAdmin' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($user);
            $this->em->flush();

            $this->addFlash('success', 'Utilisateur modifié avec succès');
            return $this->redirectToRoute('admin.users.index');
        }

        return $this->render('Backend/Users/edit.html.twig', ['form' => $form]);
    }

    #[Route('/{id}/delete', '.delete', methods: ['POST'])]
    public function delete(?Users $user, Request $request): RedirectResponse
    {
        if (!$user) {
            $this->addFlash('error', 'Utilisateur non trouvé');
            return $this->redirectToRoute('admin.users.index');
        }
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('token'))) {
            $this->em->remove($user);
            $this->em->flush();
            $this->addFlash('success', 'Utilisateur supprimé avec succès');
        } else {
            $this->addFlash('error', 'token CSRF invalide');
        }
        return $this->redirectToRoute('admin.users.index');
    }
}