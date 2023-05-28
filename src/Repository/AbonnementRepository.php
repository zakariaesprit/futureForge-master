<?php

namespace App\Repository;

use App\Entity\Abonnement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Abonnement>
 *
 * @method Abonnement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Abonnement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Abonnement[]    findAll()
 * @method Abonnement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AbonnementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Abonnement::class);
    }

    public function save(Abonnement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Abonnement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    
    public function findByNom($nom)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.nom LIKE :nom')
            ->setParameter('nom', '%' . $nom . '%')
            ->getQuery()
            ->getResult();
    }

    public function sortByAscDate(): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.dated', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function sortByDescDate(): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.dated', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    // public function findByNom($query)
    // {
    //     $qb = $this->createQueryBuilder('e');
    //     $qb->where($qb->expr()->orX(
    //         $qb->expr()->like('e.nom', ':query'),
    //         $qb->expr()->like('e.prenom', ':query'),
    //         $qb->expr()->like('e.email', ':query')
    //     ));
    //     $qb->setParameter('query', '%'.$query.'%');

    //     return $qb->getQuery()->getResult();
    // }

    // public function sortByAscEtat(): array
    // {
    //     return $this->createQueryBuilder('c')
    //         ->orderBy('c.etat', 'ASC')
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }
    
    // public function sortByDescEtat(): array
    // {
    //     return $this->createQueryBuilder('c')
    //         ->orderBy('c.etat', 'DESC')
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }

    // public function sortByAscNom(): array
    // {
    //     return $this->createQueryBuilder('c')
    //         ->orderBy('c.nom', 'ASC')
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }
    
    // public function sortByDescNom(): array
    // {
    //     return $this->createQueryBuilder('c')
    //         ->orderBy('c.nom', 'DESC')
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }

//    /**
//     * @return Abonnement[] Returns an array of Abonnement objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Abonnement
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
