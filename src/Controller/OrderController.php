<?php

// src/Controller/OrderController.php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use App\Service\SMSService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OrderController extends AbstractController
{

    #[Route("/order", name:"app_order")]

    public function index(Request $request, SMSService $smsService, SessionInterface $session): Response
    {
        $cart = $session->get('panier', []);

        // Check if the cart is empty
        if (empty($cart)) {
            $this->addFlash('warning', 'Your cart is empty.');
            //  redirect to the cart page
            return $this->redirectToRoute('cart_index');
        }

        $order = new Order();
        $form = $this->createForm(OrderType::class, $order);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($order);
            $entityManager->flush();

            // Send SMS to the customer using the injected SMSService
            $smsService->sendSMS($order->getPhoneNumber(), 'Thank you for choosing Smartech! Your order will reach you within 48h');

            // Render the thank you template from the 'order' directory
            return $this->render('order/thank_you.html.twig');
        }

        return $this->render('order/confirmer.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
