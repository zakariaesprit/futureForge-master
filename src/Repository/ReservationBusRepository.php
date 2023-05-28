<?php

namespace App\Repository;

use App\Entity\ReservationBus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReservationBus>
 *
 * @method ReservationBus|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReservationBus|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReservationBus[]    findAll()
 * @method ReservationBus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationBusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReservationBus::class);
    }

    public function save(ReservationBus $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ReservationBus $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // public function findByNom($query)
    // {
    //     $qb = $this->createQueryBuilder('e');
    //     $qb->where($qb->expr()->orX(
    //         $qb->expr()->like('e.nom', ':query'),
    //         $qb->expr()->like('e.prenom', ':query')
    //     ));
    //     $qb->setParameter('query', '%'.$query.'%');

    //     return $qb->getQuery()->getResult();
    // }
    
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
            ->orderBy('c.date', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function sortByDescDate(): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

//    /**
//     * @return ReservationBus[] Returns an array of ReservationBus objects
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

//    public function findOneBySomeField($value): ?ReservationBus
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
