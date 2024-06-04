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
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

#[Route('/admin/users', 'admin.users')]
class UserController extends AbstractDashboardController
{
    public function __construct(
        private UsersRepository $userRepo,
        private EntityManagerInterface $em
    ) {
    }

    #[Route('', name: '.index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('Backend/Users/index.html.twig', [
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


            return $this->redirectToRoute('admin.users.index');
        }
        return $this->render('Backend/Users/create.html.twig', ['form' => $form]);
    }

    #[Route('/{id}/edit', '.edit', methods: ['GET', 'POST'])]
    public function edit(?Users $user, Request $request): Response|RedirectResponse
    {
        if (!$user) {

            return $this->redirectToRoute('admin.users.index');
        }

        $form = $this->createForm(UserType::class, $user, ['isAdmin' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($user);
            $this->em->flush();


            return $this->redirectToRoute('admin.users.index');
        }

        return $this->render('Backend/Users/edit.html.twig', ['form' => $form]);
    }

    #[Route('/{id}/delete', '.delete', methods: ['POST'])]
    public function delete(?Users $user, Request $request): RedirectResponse
    {
        if (!$user) {

            return $this->redirectToRoute('admin.users.index');
        }
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('token'))) {
            $this->em->remove($user);
            $this->em->flush();
        }
        return $this->redirectToRoute('admin.users.index');
    }
}
