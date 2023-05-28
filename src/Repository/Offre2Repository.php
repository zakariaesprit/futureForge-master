<?php

namespace App\Repository;

use App\Entity\Offre2;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Offre2>
 *
 * @method Offre2|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offre2|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offre2[]    findAll()
 * @method Offre2[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class Offre2Repository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offre2::class);
    }

    public function save(Offre2 $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Offre2 $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
