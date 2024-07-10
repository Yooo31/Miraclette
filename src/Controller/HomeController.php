<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/', name: 'home.')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class HomeController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        // Get current username and ROLE
        $user = $this->getUser();
        $username = $user->getUsername();
        $role = $user->getRoles()[0]; // Assuming only one role per user

        if ($role === 'ROLE_ADMIN') {
            return $this->render('home/admin/index.html.twig', [
                'username' => $username,
                'role' => $role,
            ]);
        }

        return $this->render('home/index.html.twig', [
            'username' => $username,
            'role' => $role,
        ]);
    }
}
