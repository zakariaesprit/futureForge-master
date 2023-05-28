<?php

namespace App\Repository;

use App\Entity\ReservationCovoiturage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReservationCovoiturage>
 *
 * @method ReservationCovoiturage|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReservationCovoiturage|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReservationCovoiturage[]    findAll()
 * @method ReservationCovoiturage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationCovoiturageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReservationCovoiturage::class);
    }

    public function save(ReservationCovoiturage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ReservationCovoiturage $entity, bool $flush = false): void
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

    public function sortByAscNbrPlace(): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.nbrPlace', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function sortByDescNbrPlace(): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.nbrPlace', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

//    /**
//     * @return ReservationCovoiturage[] Returns an array of ReservationCovoiturage objects
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

//    public function findOneBySomeField($value): ?ReservationCovoiturage
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
