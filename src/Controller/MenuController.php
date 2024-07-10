<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Form\MenuType;
use App\Repository\MenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/menu', name: 'menu.')]
#[IsGranted('ROLE_ADMIN')]
class MenuController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request, MenuRepository $menuRepository): Response
    {
        $available = $request->query->get('available');
        $unavailable = $request->query->get('unavailable');

        if ($available !== null) {
            $allMenu = $menuRepository->findBy(['available' => true]);
        } elseif ($unavailable !== null) {
            $allMenu = $menuRepository->findBy(['available' => false]);
        } else {
            $allMenu = $menuRepository->findAll();
        }

        $menu = new Menu();
        $form = $this->createForm(MenuType::class, $menu);

        return $this->render('menu/index.html.twig', [
            'menu' => $allMenu,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $menu = new Menu();

        $form = $this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($menu);
            $em->flush();

            return new JsonResponse(['success' => true]);
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return new JsonResponse(['success' => false, 'errors' => $errors]);
    }

    #[Route('/inactive/{id}', name: 'inactive')]
    public function inactive(Menu $menu, EntityManagerInterface $em): Response
    {
        $menu->setAvailable(false);
        $em->flush();

        return $this->redirectToRoute('menu.index');
    }

    #[Route('/active/{id}', name: 'active')]
    public function active(Menu $menu, EntityManagerInterface $em): Response
    {
        $menu->setAvailable(true);
        $em->flush();

        return $this->redirectToRoute('menu.index');
    }
}
