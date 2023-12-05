<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{

    /**
     * @Route("/cart", name="cart_index")
     */

    public function index(SessionInterface $session, ProduitRepository $produitRepository)
    {
        $panier = $session->get("panier", []);

        // Prepare cart data
        $dataPanier = [];
        $total = 0;

        foreach ($panier as $id => $quantite) {
            $produit = $produitRepository->find($id);

            if ($produit) {
                $dataPanier[] = [
                    "produit" => $produit,
                    "quantite" => $quantite
                ];
                $total += $produit->getPrix() * $quantite;
            }
        }

        return $this->render('cart/index.html.twig', [
            'dataPanier' => $dataPanier,
            'total' => $total,
        ]);
    }

    /**
     * @Route("/cart/add/{id}", name="cart_add")
     */
    public function add($id, SessionInterface $session, ProduitRepository $produitRepository)
    {
        $produit = $produitRepository->find($id);

        if (!$produit) {
            throw $this->createNotFoundException('Produit not found');
        }

        $panier = $session->get("panier", []);

        if (!empty($panier[$id])) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }

        $session->set("panier", $panier);

        return $this->redirectToRoute("cart_index");
    }



      #[Route("/cart/remove/{id}", name: "cart_remove")]

    public function remove($id, SessionInterface $session, ProduitRepository $produitRepository)
    {
        $produit = $produitRepository->find($id);

        if (!$produit) {
            throw $this->createNotFoundException('Produit not found');
        }

        $panier = $session->get("panier", []);
        if (!empty($panier[$id])) {
            if ($panier[$id] > 1) {
                $panier[$id]--;
            } else {
                unset($panier[$id]);
            }
        }

        $session->set("panier", $panier);

        return $this->redirectToRoute("cart_index");
    }

    /**
     * @Route("/cart/delete/{id}", name="cart_delete")
     */
    public function deleteConfirm($id, ProduitRepository $produitRepository, SessionInterface $session, Request $request)
    {
        $confirm = $request->query->get('confirm');
        $produit = $produitRepository->find($id);

        if (!$produit) {
            throw $this->createNotFoundException('Produit not found');
        }

        if ($confirm === 'true') {
            $this->deleteProduct($produit, $session);
            $this->addFlash('success', 'Product deleted successfully.');
        } else {
            $this->addFlash('info', 'Product deletion canceled.');
        }

        return $this->redirectToRoute("cart_index");
    }


    /**
     * @Route("/cart/delete-all", name="cart_delete_all")
     */
    public function deleteAll(SessionInterface $session)
    {
        $session->remove("panier");

        return $this->redirectToRoute("cart_index");
    }

    private function deleteProduct(Produit $produit, SessionInterface $session): void
    {
        $panier = $session->get("panier", []);
        $id = $produit->getId();

        // Convert the product ID to string since session keys are strings
        $idString = (string)$id;

        if (!empty($panier[$idString])) {
            unset($panier[$idString]);
        }

        $session->set("panier", $panier);
    }

}
