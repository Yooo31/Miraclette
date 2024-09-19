<?php

namespace App\Controller;

use App\Entity\Bookings;
use App\Entity\Menu;
use App\Entity\Orders;
use App\Entity\OrdersElements;
use App\Entity\OrderStatus;
use App\Entity\User;
use App\Repository\MenuRepository;
use App\Repository\OrdersElementsRepository;
use App\Repository\OrdersRepository;
use App\Repository\OrderStatusRepository;
use Doctrine\Common\Collections\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        } else {
            throw new \LogicException('User is not of type App\Entity\User.');
        }

        $allMenu = $menuRepository->findBy(['available' => true]);

        return $this->render('order/index.html.twig', [
            'first_name' => $first_name,
            'role' => $role,
            'menu' => $allMenu
        ]);
    }

    #[Route('/new', name: 'new', methods: ['POST'])]
    public function newOrder(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return new JsonResponse(['success' => false, 'message' => 'Invalid data'], 400);
        }

        $clientId = $data[0]['clientId'];
        $orderId = $this->setOrders($clientId, $entityManager);
        array_shift($data);

        foreach ($data as $orderLine) {
            $id = $orderLine['id'];
            $quantity = $orderLine['quantity'];

            if ($quantity > 1) {
                for ($i = 0; $i < $quantity; $i++) {
                    $this->setOrderElement($orderId, $id, $entityManager);
                }
            } else {
                $this->setOrderElement($orderId, $id, $entityManager);
            }
        }

        return new JsonResponse(['success' => true, 'message' => 'Commande validée']);
    }

    #[Route('/success', name: 'success')]
    public function success(): Response
    {
        return $this->render('order/success.html.twig');
    }

    #[Route('/details/{id}', name: 'details')]
    public function getOrderDetails(int $id, OrdersElementsRepository $ordersElementsRepository, OrdersRepository $ordersRepository): JsonResponse
    {
        $order = $ordersRepository->findOneBy(['id' => $id]);

        if (!$order) {
            throw $this->createNotFoundException('Commande non trouvée');
        }

        $elements = $ordersElementsRepository->findAllByOrderId($order);

        $menuData = [];

        foreach ($elements as $element) {
            $menu = $element->getMenu();
            $menuId = $menu ? $menu->getId() : null;
            $menuName = $menu ? $menu->getName() : 'Inconnu';

            if ($menuId !== null) {
                if (isset($menuData[$menuId])) {
                    $menuData[$menuId]['count'] += 1;
                } else {
                    $menuData[$menuId] = [
                        'id' => $menuId,
                        'name' => $menuName,
                        'count' => 1,
                    ];
                }
            }
        }

        $orderData = [
            'id' => $order->getId(),
            'menus' => array_values($menuData)
        ];

        return $this->json($orderData);
    }

    #[Route('/update-status/{id}', name: 'update-status', methods: ['POST'])]
    public function updateStatus($id, Request $request, OrdersRepository $ordersRepository, OrderStatusRepository $orederStatusRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $order = $ordersRepository->findOneBy(['id' => $id]);

        if (!$order) {
            return new JsonResponse(['error' => 'Order not found'], 404);
        }

        $data = json_decode($request->getContent(), true);
        $newStatus = $data['status'] ?? null;

        $orderStatus = $orederStatusRepository->findOneBy(['name' => $newStatus]);

        if (!$orderStatus) {
            return new JsonResponse(['error' => 'Invalid status'], 400);
        }

        $order->setStatus($orderStatus);
        $entityManager->persist($order);
        $entityManager->flush();

        return new JsonResponse(['success' => 'Status updated']);
    }

    #[Route('/closed', name: 'closed')]
    public function closedOrders(OrdersRepository $ordersRepository): Response
    {
        $user = $this->getUser();

        if ($user instanceof User) {
            $username = $user->getUsername();
            $first_name = $user->getFirstName();
            $role = $user->getRoles()[0];
        } else {
            throw new \LogicException('User is not of type App\Entity\User.');
        }

        $orderList = $ordersRepository->findAllClosedOrders();

            return $this->render('home/cuisine/index.html.twig', [
                'orders' => $orderList,
                'username' => $username,
                'first_name' => $first_name,
                'role' => $role,
                'orders' => $orderList
            ]);
    }

    private function setOrders($clientId, $entityManager) {
        $user = $this->getUser();

        if ($user instanceof User) {
            $server_id = $user->getId();
        } else {
            throw new \LogicException('User is not of type App\Entity\User.');
        }

        $orderStatusRepository = $entityManager->getRepository(OrderStatus::class);
        $orderStatus = $orderStatusRepository->findOneBy(['name' => 'En cours']);

        $bookingsRepository = $entityManager->getRepository(Bookings::class);
        $table = $bookingsRepository->findOneBy(['location' => $clientId]);

        $userRepository = $entityManager->getRepository(User::class);
        $server = $userRepository->findOneBy(['id' => $server_id]);


        $order = new Orders;
        $order->setBooking($table);
        $order->setManager($server);
        $order->setStatus($orderStatus);

        $entityManager->persist($order);
        $entityManager->flush();

        return $order->getId();
    }

    private function setOrderElement($orderId, $id, $entityManager) {
        $orderRepository = $entityManager->getRepository(Orders::class);
        $order = $orderRepository->findOneBy(['id' => $orderId]);

        $menuRepository = $entityManager->getRepository(Menu::class);
        $menu = $menuRepository->findOneBy(['id' => $id]);

        $orderElement = new OrdersElements;
        $orderElement->setMainOrder($order);
        $orderElement->setMenu($menu);

        $entityManager->persist($orderElement);
        $entityManager->flush();
    }
}
