<?php

namespace App\Controller\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app.login', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authUtils): Response
    {
        if ($this->getUser()) {
            if ($this->getUser()->getRoles() == 'ROLE_ADMIN') {
                return $this->redirectToRoute('/admin/users/index');
            } elseif ($this->getUser()->getRoles() == 'ROLE_USER') {
                return $this->RedirectToRoute('app.users.details', ['id' => $this->getUser()->getId()]);
            }
        }


        $error = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('Security/login.html.twig', [
            'error' => $error,
            'lastUsername' => $lastUsername
        ]);
    }
}
