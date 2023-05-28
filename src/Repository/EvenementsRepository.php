<?php

namespace App\Repository;

use App\Entity\Evenements;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Evenements>
 *
 * @method Evenements|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evenements|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evenements[]    findAll()
 * @method Evenements[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvenementsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evenements::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Evenements $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Evenements $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Evenements[] Returns an array of Evenements objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Evenements
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findAllJoinedToCategories()
{
    $qb = $this->createQueryBuilder('e')
        ->leftJoin('e.Categories_id', 'c')
        ->addSelect('c');

    return $qb->getQuery()->getResult();
}
public function findBySearchQuery(string $searchQuery)
{
    return $this->createQueryBuilder('a')
        ->andWhere('a.title LIKE :searchQuery OR a.content LIKE :searchQuery')
        ->setParameter('searchQuery', '%'.$searchQuery.'%')
        ->getQuery()
        ->getResult();
}
public function findBySearchCriteria(array $criteria): array
    {
        $qb = $this->createQueryBuilder('a');
    
        if (isset($criteria['nom'])) {
            $qb->andWhere('a.nom LIKE :nom')
                ->setParameter('nom', '%' . $criteria['nom'] . '%');
        }
    
        if (isset($criteria['Categories_id'])) {
            $qb->join('a.Categories_id', 'c')
               ->andWhere('c = :Categories_id')
               ->setParameter('Categories_id', $criteria['Categories_id']);
        }
    
    
        if (isset($criteria['type'])) {
            $qb->andWhere('a.type = :type')
                ->setParameter('type', $criteria['type']);
        }
        $resultats = $qb->getQuery()->getResult();
    
        return $resultats;
    }
    

    public function countEvenementByMonth()
    {
        $qb = $this->createQueryBuilder('a');
        $qb->select("SUBSTRING(a.date, 6, 2) as month, COUNT(a.id) as count")
           ->groupBy("month")
           ->orderBy("month", "ASC");
        $query = $qb->getQuery();
        $results = $query->getResult();
    
        $ByMonth = [];
        foreach ($results as $result) {
            $EvenementsByMonth[$result['month']] = $result['count'];
        }
        
        return $EvenementsByMonth;
    }
    public function countEvenemenByCategories_id()
{
    $results = $this->createQueryBuilder('e')
    ->select('c.nom', 'count(e.id) as nb_events')
    ->join('e.Categories_id', 'c')
    ->groupBy('c.nom')
    ->getQuery()
    ->getResult();


    $data = [];
    foreach ($results as $result) {
        $data[] = [
            'name' => $result['nom'],
            'y' => (int) $result['nb_events'],
        ];
    }

    return $data;
}
}
