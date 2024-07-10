<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/team', name: 'team.')]
#[IsGranted('ROLE_ADMIN')]
class MakeTeamController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAllExceptAdmins();

        return $this->render('make_team/index.html.twig', [
            'team' => $users,
        ]);
    }

    #[Route('/set-role/{id}', name: 'set-role', methods: ['POST'])]
    public function setRole(Request $request, User $user, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['role'])) {
            $user->setRoles([$data['role']]);
            $em->persist($user);
            $em->flush();

            return new JsonResponse(['success' => true]);
        }

        return new JsonResponse(['success' => false], 400);
    }

}
