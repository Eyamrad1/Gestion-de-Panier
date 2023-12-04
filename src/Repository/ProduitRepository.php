<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityRepository;

/**
 * @extends ServiceEntityRepository<Produit>
 *
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

//    /**
//     * @return Produit[] Returns an array of Produit objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Produit
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function getStatistiquesProduits()
    {
        // Exemple : récupérer le produit le plus cher
        $produitPlusCher = $this->createQueryBuilder('p')
            ->orderBy('p.prix', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        // Exemple : récupérer le produit le moins cher
        $produitMoinsCher = $this->createQueryBuilder('p')
            ->orderBy('p.prix', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        // Exemple : récupérer le nombre total de produits
        $nombreTotalProduits = $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->getQuery()
            ->getSingleScalarResult();

        return [
            'produitPlusCher'  => [
                'name' => $produitPlusCher ? $produitPlusCher->getNomProduit() : null,
                'prix' => $produitPlusCher ? $produitPlusCher->getPrix() : null,
            ],
            'produitMoinsCher'  => [
                'name' => $produitMoinsCher ? $produitMoinsCher->getNomProduit() : null,
                'prix' => $produitMoinsCher ? $produitMoinsCher->getPrix() : null,
            ],
            'nombreTotalProduits' => $nombreTotalProduits,
        ];
    }
}
