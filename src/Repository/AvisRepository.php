<?php

namespace App\Repository;

use App\Entity\Avis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Avis>
 *
 * @method Avis|null find($id, $lockMode = null, $lockVersion = null)
 * @method Avis|null findOneBy(array $criteria, array $orderBy = null)
 * @method Avis[]    findAll()
 * @method Avis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AvisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Avis::class);
    }

    public function save(Avis $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Avis $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function sortByAscRating(): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.rate', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function sortByDescRating(): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.rate', 'DESC')
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
//     * @return Avis[] Returns an array of Avis objects
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

//    public function findOneBySomeField($value): ?Avis
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
