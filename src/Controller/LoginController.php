<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('/697868c2e3364b0c6529e42626305015', name: 'dashboard-')]
class LoginController extends AbstractController
{
    // login page
    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $auth): Response
    {
        // view redirect
        if($this->getUser()) {
            return $this->redirectToRoute('dashboard-account');
        }
        // error handling
        $error = $auth->getLastAuthenticationError();
        // view render
        return $this->render('login/index.html.twig', ['error' => $error]);        
    }

    // logout page
    #[Route('/logout', name: 'logout')]
    public function logout(): Response
    {
        // view render
        return $this->redirectToRoute('dashboard-login');
    }
}
