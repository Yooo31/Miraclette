<?php

namespace App\Controller;

use App\Entity\Bookings;
use App\Form\BookingType;
use App\Repository\BookingsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/booking', name: 'booking.')]
#[IsGranted('ROLE_ADMIN')]
class BookingsController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(BookingsRepository $bookingsRepository): Response
    {
        $bookings = $bookingsRepository->findAll();

        $booking = new Bookings();
        $form = $this->createForm(BookingType::class, $booking);

        return $this->render('bookings/index.html.twig', [
            'bookings' => $bookings,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $menu = new Bookings();

        $form = $this->createForm(BookingType::class, $menu);
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
}
