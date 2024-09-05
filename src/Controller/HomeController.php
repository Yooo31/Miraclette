<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/', name: 'home.')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class HomeController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $user = $this->getUser();

        if ($user instanceof User) {
            $username = $user->getUsername();
            $first_name = $user->getFirstName();
            $role = $user->getRoles()[0];
        } else {
            throw new \LogicException('User is not of type App\Entity\User.');
        }


        if ($role === 'ROLE_ADMIN') {
            return $this->render('home/admin/index.html.twig', [
                'username' => $username,
                'first_name' => $first_name,
                'role' => $role,
            ]);
        } else if ($role === 'Service') {
            return $this->render('home/service/index.html.twig', [
                'username' => $username,
                'first_name' => $first_name,
                'role' => $role,
                'orders' => []
            ]);
        }
    }
}