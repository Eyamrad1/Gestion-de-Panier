<?php

namespace App\Controller;

use App\Entity\Typeproduit;
use App\Form\TypeproduitType;
use App\Repository\TypeproduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class TypeproduitController extends AbstractController
{
    #[Route('/', name: 'app_typeproduit_index', methods: ['GET'])]
    public function index(TypeproduitRepository $typeproduitRepository): Response
    {
        return $this->render('typeproduit/index.html.twig', [
            'typeproduit' => $typeproduitRepository->findAll(),
        ]);
    }

    #[Route('/afficheT', name: 'AfficheT')]
    public function AfficheT(TypeproduitRepository $repo): Response
    {
        $typeproduit = $repo->findAll();
        return $this->render('typeproduit/showT.html.twig', [
            'typeproduit' => $typeproduit,
        ]);
    }
    #[Route('/supprimerT/{i}', name: 'suppT')]
    public function supprimerT($i,TypeproduitRepository $repo, ManagerRegistry $doctrine ): Response
    {
        //recuperer l'auteur a supprimer
        $typeproduit = $repo->find($i);
        //recuperer l'entity manager : le chef d'orchestre de l'ORM
        $em=$doctrine->getManager();
        //Action suppression
        $em->remove($typeproduit);

        //commit
        $em->flush();
        return $this->redirectToRoute('AfficheT');    }

    #[Route('/ajoutT', name: 'ajoutT')]
    public function ajoutT(ManagerRegistry $doctrine,Request $req): Response
    {
        //instancier un nouvel auteur
        $typeproduit = new Typeproduit();
        //creer l'objet form
        $form =$this->createForm(TypeproduitType::class, $typeproduit);
        //recuperer les donnes saisies dans le formulaire
        $form->handleRequest($req);
        if ($form->isSubmitted()){
            $em = $doctrine->getManager();
            $em ->persist($typeproduit);
            $em ->flush();
            return $this->redirectToRoute('AfficheT');
        }
        return $this->renderForm('typeproduit/new.html.twig', [
            'f' => $form,
        ]);
    }
    #[Route('/updateT/{id}', name: 'updateT')]
    public function updateT(ManagerRegistry $doctrine, Request $req, $id): Response
    {
        $em = $doctrine->getManager();
        $typeproduit = $em->getRepository(Typeproduit::class)->find($id);

        $form = $this->createForm(TypeproduitType::class, $typeproduit);

        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();

            $em->flush();

            return $this->redirectToRoute('AfficheT');
        }

        return $this->renderForm('typeproduit/modifierT.html.twig', [
            'f1' => $form,
        ]);
    }
}
