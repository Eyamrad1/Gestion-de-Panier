<?php

namespace App\Controller;


use App\Entity\Livraison;
use App\Form\LivraisonType;
use App\Repository\LivraisonRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class LivraisonController extends AbstractController
{
    #[Route('/livraison', name: 'app_livraison')]
    public function index(): Response
    {
        return $this->render('livraison/index.html.twig', [
            'livraison' => 'LivraisonController',
        ]);
    }
    #[Route('/AfficheL', name: 'AfficheL')]
    public function AfficheL(LivraisonRepository $repo): Response
    {
        $livraison = $repo->findAll();
        return $this->render('Livraison/showL.html.twig', [
            'livraison' => $livraison,
        ]);
    }
    #[Route('/supprimerL/{i}', name: 'suppL')]
    public function supprimerL($i,LivraisonRepository $repo, ManagerRegistry $doctrine ): Response
    {
        //recuperer l'auteur a supprimer
        $livraison = $repo->find($i);
        //recuperer l'entity manager : le chef d'orchestre de l'ORM
        $em=$doctrine->getManager();
        //Action suppression
        $em->remove($livraison);

        //commit
        $em->flush();
        return $this->redirectToRoute('AfficheL');    }
    #[Route('/ajoutL', name: 'ajoutL')]
    public function ajoutL(ManagerRegistry $doctrine,Request $req): Response
    {
        //instancier un nouvel auteur
        $livraison = new Livraison();
        //creer l'objet form
        $form =$this->createForm(LivraisonType::class, $livraison);
        //recuperer les donnes saisies dans le formulaire
        $form->handleRequest($req);
        if ($form->isSubmitted()){
            $em = $doctrine->getManager();
            $em ->persist($livraison);
            $em ->flush();
            return $this->redirectToRoute('AfficheL');
        }
        return $this->renderForm('Livraison/add.html.twig', [
            'f' => $form,
        ]);
    }
    #[Route('/updateL/{id}', name: 'updateL')]
    public function updateL(ManagerRegistry $doctrine, Request $req, $id): Response
    {
        $em = $doctrine->getManager();
        $livraison = $em->getRepository(Livraison::class)->find($id);

        $form = $this->createForm(LivraisonType::class, $livraison);

        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();

            $em->flush();

            return $this->redirectToRoute('AfficheL');
        }

        return $this->renderForm('Livraison/edit.html.twig', [
            'f1' => $form,
        ]);
    }
    public function livraisonsData(): JsonResponse
    {
        // Fetch data from the Livraison entity, replace this with your actual logic
        $livraisonsData = $this->getDoctrine()->getRepository(Livraison::class)->getLivraisonsData();

        return new JsonResponse($livraisonsData);
    }
    #[Route('/livraison-statistics', name: 'livraison_statistics')]
    public function livraisonStatistics(LivraisonRepository $livraisonRepository): Response
    {
        $livraisonsStatistics = $livraisonRepository->getLivraisonsWithHighestNbrC();

        return $this->render('livraison/statistics.html.twig', [
            'livraisonsStatistics' => $livraisonsStatistics,
        ]);
    }
}
