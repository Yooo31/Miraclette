<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\MenuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/order', name: 'order.')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class OrderController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(MenuRepository $menuRepository): Response
    {
        $user = $this->getUser();

        if ($user instanceof User) {
            $first_name = $user->getFirstName();
            $role = $user->getRoles()[0];
            $server_id = $user->getId();
        } else {
            throw new \LogicException('User is not of type App\Entity\User.');
        }

        $allMenu = $menuRepository->findBy(['available' => true]);

        return $this->render('order/index.html.twig', [
            'first_name' => $first_name,
            'role' => $role,
            'server_id' => $server_id,
            'menu' => $allMenu
        ]);
    }
}
