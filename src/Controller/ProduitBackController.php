<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\Persistence\ManagerRegistry;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;





//#[Route('/produit')]
class ProduitBackController extends AbstractController
{
    #[Route('/prod', name: 'app_produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('produit_back/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

    #[Route('/afficheP', name: 'afficheP')]
    public function afficheP(ProduitRepository $repo): Response
    {
        $em = $this->getDoctrine()->getManager();
        $produit = $repo->findAll();
        return $this->render('produit_back/showP.html.twig', array(
            'produit' => $produit,


        ));
    }
    #[Route('/supprimerP/{i}', name: 'suppP')]
    public function supprimerP($i,ProduitRepository $repo, ManagerRegistry $doctrine ): Response
    {
        //recuperer l'auteur a supprimer
        $produit = $repo->find($i);
        //recuperer l'entity manager : le chef d'orchestre de l'ORM
        $em=$doctrine->getManager();
        //Action suppression
        $em->remove($produit);

        //commit
        $em->flush();
        $this->addFlash('noticedelete', 'produit a été bien supprimé');

        return $this->redirectToRoute('afficheP');
    }

    #[Route('/ajoutP', name: 'ajoutP')]
    public function ajoutP(ManagerRegistry $doctrine,Request $req): Response
    {

        //instancier un nouvel auteur
        $produit = new Produit();

        //creer l'objet form
        $form =$this->createForm(ProduitType::class, $produit);
        //recuperer les donnes saisies dans le formulaire
        $form->handleRequest($req);
        if ($form->isSubmitted()&& $form->isValid()){
            $fileUpload = $form->get('image')->getData();
            $fileName = md5(uniqid()) . '.' . $fileUpload->guessExtension();
            $fileUpload->move($this->getParameter('kernel.project_dir') . '/public/upload', $fileName);// Creation dossier uploads
            $produit->setImage($fileName);


            $em = $doctrine->getManager();
            $namePng = uniqid('', '') . '.png';


            $em ->persist($produit);
            $em ->flush();
            $this->addFlash(
                'notice', 'produit a été bien ajoutée '
            );

            return $this->redirectToRoute('afficheP');


        }
        return $this->renderForm('produit_back/new.html.twig', [
            'f' => $form,

        ]);
    }

    #[Route('/updateP/{id}', name: 'updateP')]
    public function updateP(ManagerRegistry $doctrine, Request $req, $id): Response
    {
        $em = $doctrine->getManager();
        $produit = $em->getRepository(Produit::class)->find($id);

        $form = $this->createForm(ProduitType::class, $produit);

        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();

            $em->flush();

            return $this->redirectToRoute('afficheP');
        }

        return $this->renderForm('produit_back/edit.html.twig', [
            'f1' => $form,
        ]);
    }

    #[Route('/stat-produits', name: 'statistiques_produits')]
    public function statistiquesProduits(ProduitRepository $produitRepository): Response
    {
        $statistiquesProduits = $produitRepository->getStatistiquesProduits();

        return $this->render('produit_back/statistiques.html.twig', [
            'statistiquesProduits' => $statistiquesProduits,
        ]);
    }

    #[Route('/excel', name: 'export_Excel')]
    public function exportToExcel(): Response
    {
        $produits = $this->getDoctrine()->getRepository(Produit::class)->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Add headers
        $headers = ['ID', 'Prix', 'Nom Produit', 'Description', 'Image', 'Nombre Produit', 'Type Produit'];
        $sheet->fromArray([$headers], null, 'A1');

        // Add data
        $row = 2;
        foreach ($produits as $produit) {
            $sheet->setCellValue('A' . $row, $produit->getId());
            $sheet->setCellValue('B' . $row, $produit->getPrix());
            $sheet->setCellValue('C' . $row, $produit->getNomProduit());
            $sheet->setCellValue('D' . $row, $produit->getDescription());
            $sheet->setCellValue('E' . $row, $produit->getImage());
            $sheet->setCellValue('F' . $row, $produit->getNombreProduit());
            $sheet->setCellValue('G' . $row, $produit->getIdT()->getNomType()); // Assuming Typeproduit has a method getNom()

            $row++;
        }

        // Create a temporary file to store the exported data
        $filename = tempnam(sys_get_temp_dir(), 'export_') . '.xlsx';

        // Save the spreadsheet to the temporary file
        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);

        // Set up the response
        $response = new Response(file_get_contents($filename));
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename=export.xlsx');
        $response->headers->set('Cache-Control', 'max-age=0');

        // Clean up the temporary file
        unlink($filename);

        return $response;

    }


}



